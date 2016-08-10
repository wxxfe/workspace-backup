import './style.css';
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
            }

            dialogComp = <DialogBox {...dialogProps}/>;
        }

        //没有输入内容时发送按钮的样式
        var noContentStyle = 'btn2';
        if (this.state.text) noContentStyle = 'btn2 btn3';

        return (
            <div className='editTxtSend'>
                <div ref='box'>
                    <div className='topBar'>
                        <button className='btn1' onClick={this.closeHandler}>取消</button>
                        <button className={noContentStyle} onClick={this.sendHandler}>发送</button>
                    </div>
                    <textarea ref='txt' className='textarea' placeholder='请输入回复内容,限制1000字'
                              value={this.state.text}
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

    }

}