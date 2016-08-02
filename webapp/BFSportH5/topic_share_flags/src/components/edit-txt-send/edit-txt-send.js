import './style.css';
import Data, {DataEdit, CONFIG, URL_PARAM_NAME, STORAGE_KEY, Utils, API} from '../../data';
import * as React from 'react';
import DialogBox from '../dialog-box/dialog-box';

/**
 * 弹出框内容
 */
const DialogContent = [
    '发送中...',
    '发送成功',
    '发送失败',
    '请输入内容',
    '确定退出?',
    '发送成功,前往首页'
];

/**
 * 编辑文字发送组件
 */
export default class EditTxtSend extends React.Component {
    static propTypes = {
        editStatusHandler: React.PropTypes.func.isRequired, //改变编辑状态
        editStatus: React.PropTypes.number.isRequired, //编辑状态
        canvasImg: React.PropTypes.string, //编辑完成的图片
        text: React.PropTypes.string //编辑好的文字
    }

    constructor(props) {
        super(props);

        this.state = {
            text: this.props.text,
            dialogContentIndex: -1
        };
    }

    componentDidMount() {
        //延时才能聚焦成功
        this.refs.txt.focus();
        setTimeout(function () {
            this.focus();
        }.bind(this.refs.txt), 100);
    }

    render() {

        /*
         * 对话框,如果对话框内容索引大于-1,则弹出对话框
         * 内部逻辑看DialogContent常量内容
         */
        let dialogComp = null;
        if (this.state.dialogContentIndex > -1) {
            let dialogProps = {
                animate: 'scale',
                txtc: DialogContent[this.state.dialogContentIndex]
            };

            switch (this.state.dialogContentIndex) {
                case 1:
                case 2:
                case 3:
                    dialogProps.styleb1 = 'btn1';
                    dialogProps.txtb1 = '关闭';
                    dialogProps.closeHandler = this.closeDialogHandler;
                    break;
                case 4:
                    dialogProps.styleb1 = 'btn2';
                    dialogProps.txtb1 = '取消';
                    dialogProps.closeHandler = this.closeDialogHandler;
                    dialogProps.styleb2 = 'btn3';
                    dialogProps.txtb2 = '确认';
                    dialogProps.confirmHandler = this.confirmCloseHandler;
                    break;
                case 5:
                    dialogProps.styleb1 = 'btn1';
                    dialogProps.txtb1 = '确认';
                    dialogProps.closeHandler = this.goIndexHandler;
                    break;
            }

            dialogComp = <DialogBox {...dialogProps}/>;
        }

        //没有输入内容时发送按钮的样式
        var noContentStyle = 'btn2';
        if (this.state.text) noContentStyle = 'btn2 btn3';

        //默认不显示
        //如果组件索引和当前状态索引一致,则显示
        let styleObj = {display: 'none'};
        if (this.props.editStatus === 1) styleObj = {display: 'block'};

        return (
            <div className='editTxtSend' style={styleObj}>
                <div ref='box'>
                    <div className='topBar'>
                        <button className='btn1' onClick={this.closeHandler}>取消</button>
                        <h1>{Data.topicTitle}</h1>
                        <button className={noContentStyle} onClick={this.sendHandler}>发送</button>
                    </div>
                    <textarea ref='txt' className='textarea' placeholder='请输入回复内容,限制1000字' autofocus
                              value={Utils.cutStrForNum(this.state.text, 1000)}
                              onChange={this.textChangeHandler}>
                    </textarea>
                    <img className='img1' src={this.props.canvasImg}/>
                </div>
                {dialogComp}
            </div>
        );
    }

    /**
     * 取消按钮点击事件,关闭编辑文字组件
     */
    closeHandler = () => {
        /*
         * 如果有输入文字,则弹出确认关闭的对话框
         * 否则直接关闭编辑文字组件
         */
        if (this.state.text) {
            this.setState({dialogContentIndex: 4});
        } else {
            this.confirmCloseHandler();
        }
    }

    /**
     * 发送按钮点击事件
     */
    sendHandler = () => {
        //如果已登录,直接发送数据
        //否则跳转登录页
        if (Data.base64TokenUserId) {
            this.setState({dialogContentIndex: 0});
            this.sendImgData();
        } else {
            if (this.state.text) {
                DataEdit.text = Utils.cutStrForNum(this.state.text, 1000);
            }

            //保存数据,跳回来恢复页面用
            Utils.setInitDataStorage();
            //var loginUrl = 'http://sso.baofeng.net/api/mlogin/default?from=sports_h5&version=1&did=&btncolor=blue&next_action=' + sportsH5Url + '&selfjumpurl=' + sportsH5Url;
            let url = encodeURIComponent(window.location.href);
            window.location.href = CONFIG.loginUrl + url + '&selfjumpurl=' + url;
        }

    }

    /**
     * 发送图片数据
     */
    sendImgData = () => {
        //错误处理
        let timeoutID = setTimeout(function () {
            if (timeoutID && this) this.setState({dialogContentIndex: 2});
        }.bind(this), 800);

        let xhr = new XMLHttpRequest();
        xhr.timeout = 800;
        xhr.open('POST', API.uploadImage);
        xhr.onload = function (e) {
            if (xhr.status === 200) {
                console.log('上传成功');
                let data = JSON.parse(xhr.response);
                if (data.errno === null || data.errno === 10000) {
                    this.sendAllData(data.data.pid);
                    if (timeoutID) {
                        clearTimeout(timeoutID);
                        timeoutID = undefined;
                    }
                }

            }
        }.bind(this);

        // 发送数据
        let formData = new FormData();
        formData.append('image', Utils.dataURItoBlob(DataEdit.canvasImg));
        formData.append('user', Data.base64TokenUserId);
        xhr.send(formData);

    }

    /**
     * 发送图文数据
     */
    sendAllData = (imgpid) => {
        $.ajax({
            type: 'POST',
            timeout: 500,
            url: API.topicPost,
            data: {
                id: Data.topicId,
                user_id: Data.bfuid,
                content: this.state.text,
                pid: imgpid
            },
            success: function (data) {
                if (data.errno === null || data.errno === 10000) {
                    //跳回首页显示分享提示
                    Utils.storageSetObj(STORAGE_KEY.share, true);
                    //置顶当前发送成功的回复
                    Data.topicReplyId = Number(data.data.post.id);
                    //提示发送成功并回首页
                    this.setState({dialogContentIndex: 5});
                    setTimeout(function () {
                        this.goIndexHandler();
                    }.bind(this), 1000);
                } else {
                    this.setState({dialogContentIndex: 2});
                }

            }.bind(this),
            error: function (xhr, type) {
                this.setState({dialogContentIndex: 2});
            }.bind(this)
        });
    }

    /**
     * 保存输入的文字数据
     * @param event
     */
    textChangeHandler = (event) => {
        this.setState({text: event.target.value});
    }

    /**
     * 关闭对话框
     */
    closeDialogHandler = () => {
        if (this.state.dialogContentIndex === 1) {
            this.confirmCloseHandler();
        } else {
            this.setState({dialogContentIndex: -1});
        }
    }

    /**
     * 确认关闭编辑文字组件
     */
    confirmCloseHandler = () => {
        this.setState({dialogContentIndex: -1});
        //改变编辑状态为0,即回到编辑图片
        this.props.editStatusHandler(0);
    }

    /**
     * 去首页
     */
    goIndexHandler = (event) => {
        if (event) evnet.preventDefault();
        window.location.href = Utils.getUrlPrefix() + '/index.html?' + URL_PARAM_NAME.replyId + '=' + Data.topicReplyId;

    }
}