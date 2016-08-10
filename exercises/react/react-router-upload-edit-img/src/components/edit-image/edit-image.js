import 'babel-polyfill';
import lrz from '../../lib/js/lrz.all.bundle';
import * as React from 'react';
import EditImageBar from './edit-image-bar';
import './style.css';

export let DataEdit = {
    flagThumbnailImages: [
        {img: './img/flags/1', title: '方形国旗'},
        {img: './img/flags/2', title: '心星国旗'},
        {img: './img/flags/3', title: '五角星'},
        {img: './img/flags/4', title: '奥运五环'},
        {img: './img/flags/5', title: '里约2016'}
    ]
};
/**
 * 画布图片物体初始化配置
 * @type {{borderColor: string, cornerColor: string, cornerSize: number, rotatingPointOffset: number, lockUniScaling: boolean}}
 */
const canvasImgInitOptions = {
    cornerSize: 50,
    transparentCorners: false,
    padding: 10,
    borderColor: 'rgba(33,118,255,1.0)',
    cornerColor: 'rgba(255,255,255,1.0)',
    cornerStrokeColor: 'rgba(33,118,255,1.0)',
    cornerStyle: 'circle',
    borderOpacityWhenMoving: 0.6,
    borderScaleFactor: 0.2,
    rotatingPointOffset: 56,
    lockUniScaling: true
};

export default class EditImage extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            selectedFlagIndex: 0,
            bgImg: ''
        };
    }

    componentDidMount() {
        this.initCanvas();
    }

    render() {
        return (
            <div className='editImage'>
                <div className='topBar'>
                    <div className='btn2'>传图
                        <input className='uploadInput' type='file' capture='camera' accept='image/*' id='file'
                               onChange={this.uploadHandler}/>
                    </div>
                </div>
                <div className='editImageMain'>
                    <img className='bgImg' src={this.state.bgImg}/>
                    <canvas id='editCanvas'></canvas>
                </div>
                <EditImageBar index={this.state.selectedFlagIndex}
                              data={DataEdit.flagThumbnailImages}
                              selectedHandler={this.selectedHandler}/>
                <button className='nextBtn' onClick={this.nextHandler}>下一步</button>
            </div>
        );
    }

    /**
     * 上传图片
     * @param evnet
     */
    uploadHandler = (evnet) => {
        evnet.preventDefault();

        lrz(document.querySelector('.uploadInput').files[0], {width: 750})
            .then(function (rst) {
                this.setState({bgImg: rst.base64});
                return rst;
            }.bind(this))
            .catch(function (err) {
                // 万一出错了，这里可以捕捉到错误信息 而且以上的then都不会执行
            })
            .always(function () {
                // 不管是成功失败，这里都会执行
            });
    }

    nextHandler = (evnet) => {
        //下一步，合成图片后编辑文字内容
        evnet.preventDefault();
    }

    /**
     * 选中国旗缩略图事件
     * @param evnet
     */
    selectedHandler = (evnet) => {
        evnet.preventDefault();
        //取得选中元素的索引值,如果与之前选中的索引不一样,则存下来,并且替换画布国旗为选中的国旗
        let index = Number(evnet.currentTarget.getAttribute('data-index'));
        if (index !== this.state.selectedFlagIndex) {
            this.setState({selectedFlagIndex: index});
            this.replaceCanvasImg(index);
        }
    }

    /**
     * 初始化画布相关
     */
    initCanvas = () => {
        //画布初始化
        this.editCanvas = new fabric.Canvas(
            'editCanvas',
            {width: 750, height: 750, selection: false, allowTouchScrolling: true, controlsAboveOverlay: true}
        );

        //画布物体移动,防止出边界的处理
        let goodtop, goodleft;
        this.editCanvas.on('object:moving', (o)=> {
            let obj = o.target;
            obj.setCoords();
            if (obj.isContainedWithinRect(new fabric.Point(0, 0), new fabric.Point(750, 750))) {
                goodtop = obj.top;
                goodleft = obj.left;
            } else {
                obj.setTop(goodtop);
                obj.setLeft(goodleft);
            }
        });

        //选中画布物体,则禁用触摸滚动事件
        this.editCanvas.on('object:selected', (o)=> {
            this.editCanvas.allowTouchScrolling = false;
        });
        //取消选中画布物体,则启用触摸滚动事件
        this.editCanvas.on('selection:cleared', (o)=> {
            this.editCanvas.allowTouchScrolling = true;
        });

        //初始化画布物体,默认用第一个图
        let imgUrl1 = require(DataEdit.flagThumbnailImages[0].img + 'left.png');
        let imgUrl2 = require(DataEdit.flagThumbnailImages[0].img + 'right.png');
        this.creatCanvasImg(imgUrl1, {left: 200, top: 400}, 0);
        this.creatCanvasImg(imgUrl2, {left: 400, top: 400}, 1);
    }

    /**
     * 创建画布图片物体
     * @param url
     * @param options
     * @param index
     */
    creatCanvasImg = (url, options, index) => {
        fabric.Image.fromURL(url, (oImg) => {
            oImg.set(Object.assign({}, canvasImgInitOptions, options));
            this.editCanvas.insertAt(oImg, index);
        });
    }

    /**
     * 替换画布图片物体
     */
    replaceCanvasImg = (index) => {
        /**
         * 获得原图相关属性,用来赋值给替换后的新图
         * @param obj
         * @returns {{}}
         */
        function getOptions(obj) {
            let options = {};
            options.left = obj.getLeft();
            options.top = obj.getTop();
            options.angle = obj.getAngle();
            options.scaleX = obj.getScaleX();
            options.scaleY = obj.getScaleY();
            options.flipX = obj.getFlipX();
            options.flipY = obj.getFlipY();
            return options;
        }

        let item0Options = getOptions(this.editCanvas.item(0));
        let item1Options = getOptions(this.editCanvas.item(1));
        this.editCanvas.clear();
        let imgUrl1 = require(DataEdit.flagThumbnailImages[index].img + 'left.png');
        let imgUrl2 = require(DataEdit.flagThumbnailImages[index].img + 'right.png');
        this.creatCanvasImg(imgUrl1, item0Options, 0);
        this.creatCanvasImg(imgUrl2, item1Options, 1);
    }

}