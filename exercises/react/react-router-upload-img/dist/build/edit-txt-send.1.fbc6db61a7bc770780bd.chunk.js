webpackJsonp([1,3],{151:function(t,e,o){"use strict";function n(t){if(t&&t.__esModule)return t;var e={};if(null!=t)for(var o in t)Object.prototype.hasOwnProperty.call(t,o)&&(e[o]=t[o]);return e["default"]=t,e}function r(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function s(t,e){if(!t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!e||"object"!=typeof e&&"function"!=typeof e?t:e}function a(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+typeof e);t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,enumerable:!1,writable:!0,configurable:!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(t,e):t.__proto__=e)}Object.defineProperty(e,"__esModule",{value:!0});var i=function(){function t(t,e){for(var o=0;o<e.length;o++){var n=e[o];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}return function(e,o,n){return o&&t(e.prototype,o),n&&t(e,n),e}}();o(343);var l=o(10),c=n(l),p=function(t){function e(){return r(this,e),s(this,(e.__proto__||Object.getPrototypeOf(e)).apply(this,arguments))}return a(e,t),i(e,[{key:"componentDidMount",value:function(){"scale"===this.props.animate&&($(this.refs.contentBox).css({transform:"scale(0, 0) translateX(-50%)",transformOrigin:"0 50%"}),$(this.refs.contentBox).animate({transform:"scale(1, 1) translateX(-50%)"},100,"ease-in")),"fade"===this.props.animate&&($(this.refs.contentBox).css({opacity:0}),$(this.refs.contentBox).animate({opacity:1},100,"ease-in")),this.props.autoCloseTime&&this.props.closeHandler&&setTimeout(this.props.closeHandler,this.props.autoCloseTime)}},{key:"render",value:function(){var t=null;this.props.txtb1&&(t=c.createElement("button",{className:"btn0 "+this.props.styleb1,onClick:this.props.closeHandler},this.props.txtb1));var e=null;this.props.txtb2&&(e=c.createElement("button",{className:"btn0 "+this.props.styleb2,onClick:this.props.confirmHandler},this.props.txtb2));var o=null,n=this.props.closeHandler;return this.props.allHitClose&&(o=this.props.closeHandler,n=null),c.createElement("div",{className:"dialogBox",onClick:o},c.createElement("div",{className:this.props.styleo,onClick:n}),c.createElement("div",{ref:"contentBox",className:this.props.stylec},c.createElement("div",{className:this.props.stylect},this.props.txtc),t,e))}}]),e}(c.Component);p.defaultProps={styleo:"overlay1",stylec:"content1",stylect:"font1"},p.propTypes={txtc:c.PropTypes.string.isRequired,stylec:c.PropTypes.string,stylect:c.PropTypes.string,animate:c.PropTypes.string,styleb1:c.PropTypes.string,txtb1:c.PropTypes.string,styleb2:c.PropTypes.string,txtb2:c.PropTypes.string,closeHandler:c.PropTypes.func,confirmHandler:c.PropTypes.func,allHitClose:c.PropTypes.bool,autoCloseTime:c.PropTypes.number,styleo:c.PropTypes.string},e["default"]=p},153:function(t,e,o){"use strict";function n(t){return t&&t.__esModule?t:{"default":t}}function r(t){if(t&&t.__esModule)return t;var e={};if(null!=t)for(var o in t)Object.prototype.hasOwnProperty.call(t,o)&&(e[o]=t[o]);return e["default"]=t,e}function s(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function a(t,e){if(!t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!e||"object"!=typeof e&&"function"!=typeof e?t:e}function i(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+typeof e);t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,enumerable:!1,writable:!0,configurable:!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(t,e):t.__proto__=e)}Object.defineProperty(e,"__esModule",{value:!0});var l=function(){function t(t,e){for(var o=0;o<e.length;o++){var n=e[o];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}return function(e,o,n){return o&&t(e.prototype,o),n&&t(e,n),e}}();o(345);var c=o(10),p=r(c),f=o(151),u=n(f),d=o(75),b=["发送中...","发送成功","发送失败","请输入内容","确定退出?","发送成功,前往首页"],h=function(t){function e(t){s(this,e);var o=a(this,(e.__proto__||Object.getPrototypeOf(e)).call(this,t));return o.state={text:o.props.text,dialogContentIndex:-1},o.closeHandler=function(){o.state.text?o.setState({dialogContentIndex:4}):o.confirmCloseHandler()},o.sendHandler=function(){},o.textChangeHandler=function(t){o.setState({text:t.target.value})},o.closeDialogHandler=function(){1===o.state.dialogContentIndex?o.confirmCloseHandler():o.setState({dialogContentIndex:-1})},o.confirmCloseHandler=function(){d.browserHistory.goBack()},o}return i(e,t),l(e,[{key:"componentDidMount",value:function(){this.refs.txt.focus(),setTimeout(function(){this.focus()}.bind(this.refs.txt),100)}},{key:"render",value:function(){var t=null;if(this.state.dialogContentIndex>-1){var e={animate:"scale",txtc:b[this.state.dialogContentIndex]};switch(this.state.dialogContentIndex){case 1:case 2:case 3:e.styleb1="btn1",e.txtb1="关闭",e.closeHandler=this.closeDialogHandler;break;case 4:e.styleb1="btn2",e.txtb1="取消",e.closeHandler=this.closeDialogHandler,e.styleb2="btn3",e.txtb2="确认",e.confirmHandler=this.confirmCloseHandler}t=p.createElement(u["default"],e)}return p.createElement("div",{className:"editTxtSend"},p.createElement("div",{ref:"box"},p.createElement("div",{className:"topBar"},p.createElement("button",{className:"btn-close",onClick:this.closeHandler},"取消"),p.createElement("button",{className:"btn-send",onClick:this.sendHandler},"发送")),p.createElement("textarea",{ref:"txt",className:"textarea",placeholder:"请输入回复内容,限制1000字",value:this.state.text,onChange:this.textChangeHandler}),p.createElement("img",{className:"img1",src:this.props.canvasImg})),t)}}]),e}(p.Component);h.propTypes={canvasImg:p.PropTypes.string,text:p.PropTypes.string},e["default"]=h},337:function(t,e,o){e=t.exports=o(136)(),e.push([t.id,".dialogBox{overflow:hidden;z-index:2}.dialogBox,.dialogBox .overlay1{position:fixed;top:0;bottom:0;left:0;right:0}.dialogBox .overlay1{background:rgba(0,0,0,.8)}.dialogBox .content1{margin:auto;position:fixed;top:6%;left:50%;min-width:600px;min-height:300px;max-width:80%;max-height:80%;transform:translateX(-50%) translateY(-6%);background:#fff;border:1px solid #ddd;border-radius:12px;text-align:center;font-size:28px}.dialogBox .font1{top:40%;transform:translate(-50%,-40%);width:80%;margin:auto;position:absolute;left:50%}.dialogBox .btn0{position:absolute;bottom:10%;width:90px;height:50px;background:#ff5f00;border-radius:6px;font-size:26px;line-height:100%;color:#fff}.dialogBox .btn1{margin:0 auto;left:0;right:0}.dialogBox .btn2{left:32%}.dialogBox .btn3{left:52%}",""])},338:function(t,e,o){e=t.exports=o(136)(),e.push([t.id,".topBar>.btn-close,.topBar>.btn-send{position:absolute;width:62px;height:100%;font-size:30px;color:#9c9c9c;background:transparent;z-index:1}.topBar>.btn-close{left:30px}.topBar>.btn-send{right:30px}.editTxtSend{background:#fff}.editTxtSend .textarea{width:100%;height:300px;border:1px solid #e3e3e3;font-size:28px;padding:10px;background:#fff}.editTxtSend .img1{width:100%;height:750px;position:relative;top:-4px;left:0;border:2px solid #e3e3e3}",""])},343:function(t,e,o){var n=o(337);"string"==typeof n&&(n=[[t.id,n,""]]);o(149)(n,{});n.locals&&(t.exports=n.locals)},345:function(t,e,o){var n=o(338);"string"==typeof n&&(n=[[t.id,n,""]]);o(149)(n,{});n.locals&&(t.exports=n.locals)}});