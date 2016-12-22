import 'babel-polyfill';
import './common.css';
import './edit.css';
import Data, {DataEdit, Utils, API} from './data';
import * as React from 'react';
import * as ReactDOM from 'react-dom';
import EditImage from './components/edit-image/edit-image';
import EditTxtSend from './components/edit-txt-send/edit-txt-send';
import DialogBox from './components/dialog-box/dialog-box';

/**
 * 编辑页面
 */
class Edit extends React.Component {
    constructor(props) {
        super(props);

        //请求浏览器存储数据
        Utils.getInitDataCookie();

        //请求浏览器存储数据,主要是url传参或者跳到第三方页面前保存的数据,跳回来后需要恢复跳前页面状态。
        Utils.getInitDataStorage();

        //话题标题
        document.title = Data.topicTitle;

        /**
         * editStatus: number 编辑状态 0 编辑图片 1编辑文字
         * cookiePrompt:Boolean 是否显示设置cookie的提示,如果是登录页跳转回来后,依然没有获得uid,则就是cookie设置问题
         */
        this.state = {
            editStatus: DataEdit.editStatus,
            cookiePrompt: Boolean((DataEdit.editStatus == 1) && !Data.bfuid)
        };
    }

    componentDidMount() {
        //请求服务器的初始数据
        this.userGet(Data.bfuid);
    }

    render() {

        //设置cookie的提示
        let cookiePromptComp = null;
        if (this.state.cookiePrompt) {
            let dialogProps = {
                animate: 'scale',
                txtc: '获取登录信息失败,请点击设置-Safari-阻止Cookie,修改为始终允许,然后再登录一次。',
                styleb1: 'btn1',
                txtb1: '关闭',
                closeHandler: this.closeDialogHandler
            };
            cookiePromptComp = <DialogBox {...dialogProps}/>;
        }

        return (
            <div className='edit pwh'>
                <EditImage editStatus={this.state.editStatus} editStatusHandler={this.editStatusHandler}/>
                <EditTxtSend editStatus={this.state.editStatus}
                             canvasImg={DataEdit.canvasImg}
                             text={DataEdit.text}
                             editStatusHandler={this.editStatusHandler}/>
                {cookiePromptComp}
            </div>
        );
    }

    /**
     * 改变编辑状态
     * @param id 状态id
     * @param img 编辑完的图片
     */
    editStatusHandler = (id, img) => {
        if (img) DataEdit.canvasImg = img;
        this.setState({editStatus: id});
    }

    /**
     * 关闭对话框
     */
    closeDialogHandler = () => {
        this.setState({cookiePrompt: false});
    }

    userGet = (id) => {
        if (id) {
            $.ajax({
                type: 'GET',
                url: API.sportsGetUser + id,
                //data: id,
                dataType: 'json',
                timeout: 500,
                success: function (data) {
                    var s = data.token + ':' + id;
                    Data.base64TokenUserId = Utils.b64btoa(s);
                    Data.userToken = Utils.b64btoa(data.token);
                    this.userLogin();
                }.bind(this),
                error: function (xhr, type) {
                    console.log(API.sportsGetUser + id);
                }.bind(this)
            });

        }
    }

    userLogin = () => {
        $.ajax({
            type: 'POST',
            timeout: 500,
            url: API.sportsLogin,
            data: {
                id: Data.bfuid,
                nickname: Utils.b64btoa(Data.bfuname),
                token: Data.userToken
            },
            error: function (xhr, type) {
                console.log(API.sportsLogin);
            }.bind(this)
        });

    }
}

ReactDOM.render(<Edit />, document.getElementById('mountNode'));
