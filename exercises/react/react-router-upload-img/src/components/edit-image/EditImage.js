import 'babel-polyfill';
import lrz from 'lrz';
import * as React from 'react';
import {Link, browserHistory} from 'react-router';
import './style.css';

export default class EditImage extends React.Component {
    state = {
        bgImg: ''
    }

    constructor(props) {
        super(props);

    }

    componentDidMount() {

    }

    render() {
        return (
            <div className='editImage'>
                <div className='topBar'>
                    <div>传图
                        <input className='uploadInput' type='file' capture='camera' accept='image/*' id='file'
                               onChange={this.uploadHandler}/>
                    </div>
                </div>
                <div className='editImageMain'>
                    <img className='bgImg' src={this.state.bgImg}/>
                </div>
                <Link to="/edit-txt-send">nextBtn</Link>
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
        //下一步，传图后编辑文字内容
        evnet.preventDefault();
        browserHistory.push('/edit-txt-send');
    }

}