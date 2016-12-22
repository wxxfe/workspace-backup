import './style.css';
import * as React from 'react';

/**
 * 弹出提示组件
 */
export default class DialogBox extends React.Component {
    static defaultProps = {
        styleo: 'overlay1',//对话框遮罩背景样式
        stylec: 'content1',//对话框内容box的样式
        stylect: 'font1'//对话框内容文本的样式
    }

    static propTypes = {
        txtc: React.PropTypes.string.isRequired, // 对话框内容
        stylec: React.PropTypes.string, // 对话框内容box的样式类名
        stylect: React.PropTypes.string, // 对话框内容文本的样式类名
        animate: React.PropTypes.string,// 对话框的动画效果
        styleb1: React.PropTypes.string, // 对话框按钮1样式类名
        txtb1: React.PropTypes.string, // 对话框按钮1文字
        styleb2: React.PropTypes.string, // 对话框按钮2样式类名
        txtb2: React.PropTypes.string, // 对话框按钮2文字
        closeHandler: React.PropTypes.func, // 对话框关闭回调函数
        confirmHandler: React.PropTypes.func, // 对话框确认回调函数
        allHitClose: React.PropTypes.bool, // 是否全部为关闭事件热区
        autoCloseTime: React.PropTypes.number,//自动关闭对话框时间
        styleo: React.PropTypes.string // 对话框遮罩背景样式
    }

    componentDidMount() {
        //如果组件出现动画效果为放大
        if (this.props.animate === 'scale') {
            $(this.refs.contentBox).css({transform: 'scale(0, 0) translateX(-50%)', transformOrigin: '0 50%'});
            $(this.refs.contentBox).animate(
                {
                    transform: 'scale(1, 1) translateX(-50%)'
                },
                100,
                'ease-in'
            );
        }

        //淡入
        if (this.props.animate === 'fade') {
            $(this.refs.contentBox).css({opacity: 0.0});
            $(this.refs.contentBox).animate(
                {
                    opacity: 1.0
                },
                100,
                'ease-in'
            );
        }

        //自动关闭
        if (this.props.autoCloseTime && this.props.closeHandler) {
            setTimeout(this.props.closeHandler, this.props.autoCloseTime);
        }
    }

    render() {
        //第一个按钮,通过为关闭,取消,no之类
        let btn1Comp = null;
        if (this.props.txtb1) {
            btn1Comp = <button className={'btn0 ' + this.props.styleb1}
                               onClick={this.props.closeHandler}>{this.props.txtb1}</button>
        }
        //第二个按钮,通常为确定,yes之类
        let btn2Comp = null;
        if (this.props.txtb2) {
            btn2Comp = <button className={'btn0 ' + this.props.styleb2}
                               onClick={this.props.confirmHandler}>{this.props.txtb2}</button>
        }

        //全部为关闭事件热区
        let dialogBoxCloseHandler = null;
        let overlayCloseHandler = this.props.closeHandler;
        if (this.props.allHitClose) {
            dialogBoxCloseHandler = this.props.closeHandler;
            overlayCloseHandler = null;
        }

        return (
            <div className='dialogBox' onClick={dialogBoxCloseHandler}>
                <div className={this.props.styleo} onClick={overlayCloseHandler}></div>
                <div ref='contentBox' className={this.props.stylec}>
                    <div className={this.props.stylect}>{this.props.txtc}</div>
                    {btn1Comp}
                    {btn2Comp}
                </div>
            </div>
        );
    }
}

