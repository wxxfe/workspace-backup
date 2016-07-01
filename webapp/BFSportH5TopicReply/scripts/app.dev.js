/**
 * 接口前缀
 */
var apiTopicPrefix = 'http://api.board.sports.baofeng.com/api/v1/h5/topic/thread/';
/**
 * 图文话题列表
 * 参数：
 * id：话题id
 */
var apiTopicList = apiTopicPrefix + 'content/list';
/**
 * 图文话题列表更多
 * 参数：
 * id：话题id
 * pos：参考位置(最后一条位置)>=0
 */
var apiTopicListMore = apiTopicPrefix + 'content/list/more';
/**
 * 图文话题单个回复数据
 * 参数：
 * id：回复id
 */
var apiTopicOne = apiTopicPrefix + 'post/find';
/**
 * 图文话题我要发言
 * 参数：
 * user：用户认证信息
 * id：话题id
 * content：帖子内容base64
 * pid：发的图url
 */
var apiTopicPost = apiTopicPrefix + 'content/post';
/**
 * 图文话题删除帖子
 * 参数：
 * id：回帖id
 * user：用户认证信息
 */
var apiTopicDelete = apiTopicPrefix + 'content/delete';
/**
 * 举报
 * 参数：
 * id：帖子id
 * user_id：用户id(可以为空)
 */
var apiTopicReport = apiTopicPrefix + 'post/report';
/**
 * 点赞
 * 参数：
 * id：帖子id
 * user_id：用户id(可以为空)
 */
var apiTopicLike = apiTopicPrefix + 'post/like';
/**
 * 用户登陆注册
 路径：/api1/(android|iphone)/user/login
 域名：api.board.sports.baofeng.com
 方法：POST
 类型：application/x-www-form-urlencoded
 参数：
 user：登录之后的加密串（必填）
 nickname：昵称
 mobile：手机号
 token：暴风用户token
 响应：application/json
 {}
 */
var apiSportsLogin = 'http://api.board.sports.baofeng.com/api/v1/android/user/login';

/**
 * 上传图片
 * user - String - Base64(token:user_id) - 某些数据不需用户信息 用户认证信息 - 参见 `/api/v1/commit` 接口参数
 * image - Binary - 图片数据
 * 响应：
 *{
 *    "pid": "e4eb1d6be5b0c7470a7c5aa391bf9d26"
 *}
 */
var apiUploadImage = 'http://upload.image.sports.baofeng.com/upload';

/**
 * 获得用户数据
 * var d = miniSSOLogin.getCookie("bfuid");
 * $.get('http://sports.baofeng.com/login/sso/' + d.info.uid,function(d){
                var obj = jQuery.parseJSON(d);
                localStorage.set('stoken',obj.token);
            });
 */
var apiSportsGetUser = 'http://sports.baofeng.com/login/sso/';

/**
 * http://image.sports.baofeng.com/bf673a6f67fa799d49c089ddaff337c4"
 */
var imageSportsPath = 'http://image.sports.baofeng.com/';
/**
 * 图片路径 imgPath = '../images/' 'http://image.sports.baofeng.com/images/'
 */
var imgPath = 'images/';
/**
 * APP下载链接
 */
var downloadAppUrl = 'http://a.app.qq.com/o/simple.jsp?pkgname=com.sports.baofeng';
/**
 * 页面地址
 */
var sportsH5Url = 'http://sports.baofeng.com/m/topic_share/index.html';
//var sportsH5Url = 'http://sports.baofeng.com';
/**
 * 用户登录地址
 */
var loginUrl = 'http://sso.baofeng.net/api/mlogin/default?from=sports_h5&version=1&did=&btncolor=blue&next_action=';
//var loginUrl = 'http://sso.baofeng.net/api/mlogin/default?from=sports_h5&version=1&did=&btncolor=blue&next_action=' + sportsH5Url + '&selfjumpurl=' + sportsH5Url;

var Utils = {

    /**
     * 获得url参数
     * @param name 参数名
     * @returns {*} 参数值
     */
    urlParam: function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results == null) {
            return null;
        } else {
            return decodeURIComponent(results[1]) || 0;
        }
    },
    /**
     * 获得Cookie项目值
     */
    docCookiesGetItem: function (sKey) {
        if (!sKey) {
            return null;
        }
        return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) + '' || null;
    },
    /**
     * 写入Cookie项目值
     */
    docCookiesSetItem: function (sKey, sValue) {
        if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) {
            return false;
        }
        document.cookie = encodeURIComponent(sKey) + '=' + encodeURIComponent(sValue) + '; domain=.baofeng.com';
        return true;
    },
    /**
     * 转换成b64编码
     */
    b64btoa: function b64Encode(str) {
        //return window.btoa(str);
        return window.btoa(encodeURI(str));
    },
    b64atob: function b64Decode(str) {
        return decodeURI(window.atob(str));
    },

    /**
     * 对日期进行格式化，
     * @param date 要格式化的日期
     * @param format 进行格式化的模式字符串
     *     支持的模式字母有：
     *     y:年,
     *     M:年中的月份(1-12),
     *     d:月份中的天(1-31),
     *     h:小时(0-23),
     *     m:分(0-59),
     *     s:秒(0-59),
     *     S:毫秒(0-999),
     *     q:季度(1-4)
     * @return String
     * @author yanis.wang@gmail.com
     */
    dateFormat: function (date, format) {
        if (format === undefined) {
            format = date;
            date = new Date();
        }
        var map = {
            "M": date.getMonth() + 1, //月份
            "d": date.getDate(), //日
            "h": date.getHours(), //小时
            "m": date.getMinutes(), //分
            "s": date.getSeconds(), //秒
            "q": Math.floor((date.getMonth() + 3) / 3), //季度
            "S": date.getMilliseconds() //毫秒
        };
        format = format.replace(/([yMdhmsqS])+/g, function (all, t) {
            var v = map[t];
            if (v !== undefined) {
                if (all.length > 1) {
                    v = '0' + v;
                    v = v.substr(v.length - 2);
                }
                return v;
            }
            else if (t === 'y') {
                return (date.getFullYear() + '').substr(4 - all.length);
            }
            return all;
        });
        return format;
    },

    /**
     * 根据用户ID获取用户头像
     */
    getUserAvatar: function (uid) {
        return 'http://img.baofeng.net/head/' + uid.substr(-4, 4) + '/' + uid.substr(-8, 4) + '/' + uid.substr(-12, 4) + '/' + uid + '/100_80_80.jpg?t=' + new Date().getTime();
    },

    /**
     * 获得点赞数据
     */
    getLiked: function () {
        var liked = Utils.docCookiesGetItem('baofengsportsliked');
        if (liked) liked = JSON.parse(liked);
        if (!Array.isArray(liked)) liked = null;
        return liked;
    },

    /**
     * 写入或者删除点赞数据
     * @param id 要处理的ID
     * @param add true 加入 false 删除
     */
    setLiked: function (id, add) {
        var liked = Utils.getLiked();
        if (liked == null)liked = [];
        for (var i = liked.length - 1; i > -1; i--) {
            if (liked[i] == id) liked.splice(i, 1);
        }
        if (add) liked.push(id);
        Utils.docCookiesSetItem('baofengsportsliked', JSON.stringify(liked));
    },

    /**
     * 异步初始数据状态检查
     * @returns {boolean}
     */
    initDataReadyCheck: function () {
        DataConfig.initDataReadyNum++;
        if (DataConfig.initDataReadyNum >= DataConfig.initDataNum) {
            return true;
        } else {
            return false;
        }
    },
    getInitDataCookie: function () {
        if (!DataConfig.topicTitle) DataConfig.topicTitle = Utils.docCookiesGetItem('topictitle');
        if (!DataConfig.topicId) DataConfig.topicId = Utils.docCookiesGetItem('topicid');
        if (!DataConfig.topicReplyId) DataConfig.topicReplyId = Utils.docCookiesGetItem('topicreplyid');
        Utils.docCookiesSetItem('topictitle', '');
        Utils.docCookiesSetItem('topicid', '');
        Utils.docCookiesSetItem('topicreplyid', '');

        //alert(document.cookie);
    },
    setInitDataCookie: function () {
        var url = '';
        if (DataConfig.topicId) {
            Utils.docCookiesSetItem('topicid', DataConfig.topicId);
            url += ('topicid=' + DataConfig.topicId);
        }
        if (DataConfig.topicReplyId) {
            Utils.docCookiesSetItem('topicreplyid', DataConfig.topicReplyId);
            url += ('&topicreplyid=' + DataConfig.topicReplyId);
        }
        if (DataConfig.topicTitle) {
            Utils.docCookiesSetItem('topictitle', DataConfig.topicTitle);
            url += ('&topictitle=' + DataConfig.topicTitle);
        }
        return url;
    },
    /**
     * 返回字符的字节长度（汉字算2个字节）
     * @param {string}{number}
     * @returns {string}   +'...'
     */

    cutStrForNum: function (str, num) {
        var len = 0;
        var newStr;
        for (var i = 0; i < str.length; i++) {
            if (str[i].match(/[^x00-xff]/ig) != null) //全角
                len += 2;
            else
                len += 1;
        }
        if (len >= num) {
            newStr = str.substring(0, num);
        }
        return newStr;
    }
}


var Refresher = {
    info: {
        "pullDownLable": "下拉刷新...",
        "pullingDownLable": "释放刷新...",
        "pullUpLable": "上拉加载更多...",
        "pullingUpLable": "释放加载...",
        "loadingLable": "加载中..."
    },
    init: function (parameter) {
        var pullDownEl = parameter.box.querySelector(".pullDown");
        var pullDownOffset = pullDownEl.offsetHeight;
        var pullUpEl = parameter.box.querySelector(".pullUp");
        var pullUpOffset = pullUpEl.offsetHeight;

        pullDownEl.querySelector('.pullDownLabel').innerHTML = Refresher.info.pullDownLable;

        document.addEventListener('touchmove', function (e) {
            e.preventDefault();
        }, false);

        var instance = new iScroll(parameter.box, {
            useTransition: true,
            vScrollbar: false,
            mouseWheel: true,
            topOffset: pullDownOffset,
            onRefresh: function () {
                Refresher.onRelease(pullDownEl, pullUpEl);
            },
            onScrollMove: function () {
                Refresher.onScrolling(this, pullDownEl, pullUpEl, pullUpOffset);
            },
            onScrollEnd: function () {
                Refresher.onPulling(pullDownEl, parameter.pullDownAction, pullUpEl, parameter.pullUpAction);
            }
        });

        return instance;
    },
    onScrolling: function (e, pullDownEl, pullUpEl, pullUpOffset) {
        if (e.y > -(pullUpOffset)) {
            pullDownEl.id = '';
            pullDownEl.querySelector('.pullDownLabel').innerHTML = Refresher.info.pullDownLable;
            e.minScrollY = -pullUpOffset;
        }
        if (e.y > 0) {
            pullDownEl.classList.add("pullFlip");
            pullDownEl.querySelector('.pullDownLabel').innerHTML = Refresher.info.pullingDownLable;
            e.minScrollY = 0;
        }
        if (e.scrollerH < e.wrapperH && e.y < (e.minScrollY - pullUpOffset) || e.scrollerH > e.wrapperH && e.y < (e.maxScrollY - pullUpOffset)) {
            pullUpEl.style.display = "block";
            pullUpEl.classList.add("pullFlip");
            pullUpEl.querySelector('.pullUpLabel').innerHTML = Refresher.info.pullingUpLable;
        }
        if (e.scrollerH < e.wrapperH && e.y > (e.minScrollY - pullUpOffset) && pullUpEl.id.match('pullFlip') || e.scrollerH > e.wrapperH && e.y > (e.maxScrollY - pullUpOffset) && pullUpEl.id.match('pullFlip')) {
            pullDownEl.classList.remove("pullFlip");
            pullUpEl.querySelector('.pullUpLabel').innerHTML = Refresher.info.pullUpLable;
        }
    },
    onRelease: function (pullDownEl, pullUpEl) {
        if (pullDownEl.className.match('pullLoading')) {
            pullDownEl.classList.toggle("pullLoading");
            pullDownEl.querySelector('.pullDownLabel').innerHTML = Refresher.info.pullDownLable;
            pullDownEl.style.lineHeight = pullDownEl.offsetHeight + "px";
        }
        if (pullUpEl.className.match('pullLoading')) {
            pullUpEl.classList.toggle("pullLoading");
            pullUpEl.querySelector('.pullUpLabel').innerHTML = Refresher.info.pullUpLable;
            pullUpEl.style.lineHeight = pullUpEl.offsetHeight + "px";
        }
    },
    onPulling: function (pullDownEl, pullDownAction, pullUpEl, pullUpAction) {
        if (pullDownEl.className.match('pullFlip') /*&&!pullUpEl.className.match('pullLoading')*/) {
            pullDownEl.classList.add("pullLoading");
            pullDownEl.classList.remove("pullFlip");
            pullDownEl.querySelector('.pullDownLabel').innerHTML = Refresher.info.loadingLable;
            pullDownEl.style.lineHeight = "20px";
            if (pullDownAction) pullDownAction();
        }
        if (pullUpEl.className.match('pullFlip') /*&&!pullDownEl.className.match('pullLoading')*/) {
            pullUpEl.classList.add("pullLoading");
            pullUpEl.classList.remove("pullFlip");
            pullUpEl.querySelector('.pullUpLabel').innerHTML = Refresher.info.loadingLable;
            pullUpEl.style.lineHeight = "20px";
            if (pullUpAction) pullUpAction();
        }
    }
}

/**
 * topictitle 话题标题
 * topicid 话题ID
 * topicreplyid 需要置顶的话题回复ID
 * base64TokenUserId 验证用户的数据
 * 异步初始数据状态相关
 */
var DataConfig = {
    bfuid: Utils.docCookiesGetItem('bfuid'),
    topicTitle: Utils.urlParam('topictitle'),
    topicId: Utils.urlParam('topicid'),
    topicReplyId: Utils.urlParam('topicreplyid'),
    base64TokenUserId: '',
    initDataNum: 2,
    initDataReadyNum: 0,
    writeAnimate: true
}


//=========================================
//React

var Style = Radium.Style;
var StyleRoot = Radium.StyleRoot;

/**
 * 固定下载广告组件
 */
var DownloadADComp = React.createClass({
    render: function () {
        return (
            <div className="downloadAD" onClick={this.downloadHandler}>
                {StyleRulesDownloadAD}

                <img src={imgPath+'logo.png'}/>
                <h1>暴风体育</h1>
                <p>有趣的话题，有趣的球迷</p>

                <button className="downloadApp">立即下载</button>
                {/*<button className="closeAD" onClick={this.props.closeHandler}><img src={imgPath+'close.png'}/>
                 </button>*/}
            </div>
        );
    },
    downloadHandler: function (e) {
        e.preventDefault();

        _hmt.push(['_trackEvent', 'H5话题分享', '点击下载APP按钮', 'app']);

        window.open(downloadAppUrl, '_blank');
    }
});
DownloadADComp = Radium(DownloadADComp);
var SDAConfig = {
    h1pLeft: 140
};
var StyleRulesDownloadAD = <Style
    scopeSelector=".downloadAD"
    rules={{
              background: 'rgba(0, 0, 0, .8)',
              height: 114,
              width: 750,
              position: 'fixed',
              bottom: 0,
              overflow: 'hidden',
              'img,h1,p,button': {
                position: 'absolute',
                color: '#fff'
              },
              img: {
                width: 80,
                height: 80,
                top: 20,
                left: 40
              },
              h1: {
                top: 28,
                left: SDAConfig.h1pLeft,
                'font-size': 36,
                'line-height': '100%'
              },
              p: {
                top: 68,
                left: SDAConfig.h1pLeft,
                'font-size': 26,
                'line-height': '100%'
              },
              button: {
                top: 28,
                right: 40,
                width: 170,
                height: 64,
                background: '#ff5f00',
                'border-radius': 6,
                'font-size': 30,
                'line-height': '100%'
              }
            }}
/>;


/**
 * 内容区广告组件
 */
var ContentADComp = React.createClass({
    render: function () {
        return (
            <div style={StylesAppAD.row1} onClick={this.downloadHandler}>
                <h1 style={StylesAppAD.font1}>想看看大家还在热议什么？快来下载暴风体育</h1>
                <span style={StylesAppAD.font2}>></span>
            </div>
        );
    },
    downloadHandler: function (e) {
        e.preventDefault();

        _hmt.push(['_trackEvent', 'H5话题分享', '点击下载APP按钮', 'app']);

        window.open(downloadAppUrl, '_blank');
    }
});
ContentADComp = Radium(ContentADComp);
var StylesAppAD = {
    row1: {
        width: '100%',
        height: 120,
        position: 'relative',
        'border-top': '20px solid #f0f0f0',
        // 'border-bottom': '20px solid #f0f0f0'
    },
    font1: {
        position: 'absolute',
        top: 38,
        left: 20,
        'font-size': 30,
        'line-height': '100%',
        color: '#ff5f00'
    },
    font2: {
        position: 'absolute',
        top: 38,
        right: 20,
        'font-size': 30,
        'line-height': '100%',
        color: '#afafaf'
    }
};


/**
 * 话题回复列表
 */
var ReplyListComp = React.createClass({
    render: function () {
        var list = this.props.data.map(function (item) {
            return <ReplyComp data={item} liked={Utils.getLiked()}/>;
        });

        if (list.length > 5) {
            list.splice(4, 0, <ContentADComp />);
        } else {
            list.push(<ContentADComp />);
        }

        return (
            <div>
                {list}
            </div>
        );
    }
});

/**
 * 话题回复内容组件
 */
var ReplyComp = React.createClass({
    getInitialState: function () {
        var likedTemp = (this.props.liked && this.props.liked.indexOf(this.props.data.id) !== -1);
        this.plusMinusFun(likedTemp);
        return {liked: likedTemp};
    },
    render: function () {

        var data = this.props.data;

        var avatarUrl = Utils.getUserAvatar(data.user_id);

        /*
         当小于1分钟时，显示“刚刚”
         1-59分钟内：XX分钟前
         1小时-24小时内：XX小时前
         1天-7天内：X天前
         7天以上-今年内：月-日 12:00；
         今年以前：年-月-日 12:00
         */
        var timeTxt = Utils.dateFormat(new Date(data.created_at * 1000), 'yyyy-MM-dd hh:mm');
        var elapsed = (Date.now() / 1000) - data.created_at; // 运行时间
        if (elapsed < 60) {
            timeTxt = '刚刚';
        } else if (elapsed >= 60 && elapsed < 3600) {
            timeTxt = Math.floor(elapsed / 60) + '分钟前';
        } else if (elapsed >= 3600 && elapsed < 86400) {
            timeTxt = Math.floor(elapsed / 3600) + '小时前';
        } else if (elapsed >= 86400 && elapsed < 604800) {
            timeTxt = Math.floor(elapsed / 86400) + '天前';
        } else if (elapsed >= 604800 && elapsed < 31536000) {
            timeTxt = Utils.dateFormat(new Date(data.created_at * 1000), 'MM-dd hh:mm');
        }

        var imgComp1 = data.image ?
            <img ref='contentIMG' style={StylesReply.img1} src={data.image} onError={this.imageError}/> : null;
        var txtComp1 = data.content ? <p style={StylesReply.txt1}>{decodeURI(data.content)}</p> : null;

        var actualLikes = data.likes + this.plusMinus;

        return (
            <div style={{'border-top': 'solid 20px #f0f0f0'}}>
                <div style={StylesReply.row1}>
                    <img style={StylesReply.avatar} src={avatarUrl}/>
                    <h1 style={[StylesReply.name, StylesReply.left1]}>{decodeURI(data.nickname)}</h1>
                    <span
                        style={[StylesReply.time, StylesReply.left1, StylesReply.top1, StylesReply.font1]}>{timeTxt}</span>
                    <span style={[StylesReply.ordinal, StylesReply.top1, StylesReply.font1]}>{data.seq}楼</span>
                </div>
                {imgComp1}
                {txtComp1}
                <div style={StylesReply.row2}>
                    <button style={StylesReply.like1} onClick={this.likeHandler}>
                        <img src={imgPath+'praise'+Number(this.state.liked)+'.png'}/>
                    </button>
                    <span style={[StylesReply.like2,StylesReply.font1]}>{actualLikes}</span>
                    {/*<button style={StylesReply.more} onClick={this.props.moreHandler}>
                     <img src={imgPath+'more' + Number(this.props.more) + '.png'}/>
                     </button>*/}
                </div>
            </div>
        );
    },
    likeHandler: function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            timeout: 500,
            url: apiTopicLike,
            data: {
                id: this.props.data.id,
                user_id: DataConfig.base64TokenUserId,
                cancel: Number(this.state.liked)
            },
            success: function (data) {
            }.bind(this),
            error: function (xhr, type) {
                console.log(apiTopicLike);
            }.bind(this)
        });

        this.plusMinusFun(!this.state.liked);

        Utils.setLiked(this.props.data.id, !this.state.liked);

        this.setState({liked: !this.state.liked});

        _hmt.push(['_trackEvent', 'H5话题分享', '点击点赞按钮', '点赞']);

    },
    imageError: function () {
        var imgDOMNode = this.refs.contentIMG;
        imgDOMNode.onerror = null;
        imgDOMNode.src = imgPath + 'default_image.png';
    },
    plusMinus: 0,
    plusMinusFun: function (liked) {
        //如果初始化后从本地历史数据得知已经点了赞,并且点赞数已经使用后端数据,取消赞事件应该减1,点赞则恢复用后端数据
        //其他情况点赞加1,取消用后端数据
        // if (this.plusMinusType == 1) {
        //     if (this.state.liked) {
        //         this.plusMinus = -1;
        //         //this.setState({plusMinus: -1})
        //     } else {
        //         this.plusMinus = 0;
        //         //this.setState({plusMinus: 0})
        //     }
        // } else {
            if (liked) {
                this.plusMinus = 1;
                //this.setState({plusMinus: 0})
            } else {
                this.plusMinus = 0;
                // this.setState({plusMinus: 1})
            }
        //}
    }
});
ReplyComp = Radium(ReplyComp);
var StylesReply = {
    row1: {
        width: '100%',
        height: 110,
        'border-top': 'solid 1px #e3e3e3',
        position: 'relative'
    },
    avatar: {
        position: 'absolute',
        top: 22,
        left: 20,
        'border-radius': '50%',
        width: 65,
        height: 65
    },
    name: {
        position: 'absolute',
        top: 25,
        'font-size': 30,
        'line-height': '100%'
    },
    time: {
        position: 'absolute'
    },
    ordinal: {
        position: 'absolute',
        right: 24
    },
    top1: {
        top: 66
    },
    left1: {
        left: 104
    },
    font1: {
        'font-size': 24,
        'line-height': '100%',
        color: '#9c9c9c'
    },
    img1: {
        'border-top': 'solid 1px #e3e3e3',
        width: '100%',
        'max-height': 1200,
        'object-fit': 'cover'
    },
    txt1: {
        'border-top': 'solid 1px #e3e3e3',
        width: 708,
        margin: '0 auto',
        padding: '34px 0',
        'font-size': 38
    },
    row2: {
        width: '100%',
        height: 88,
        position: 'relative'
    },
    like1: {
        position: 'absolute',
        top: 24,
        left: 22,
        width: 34,
        height: 36,
        background: 'rgba(0, 0, 0, .0)'
    },
    like2: {
        position: 'absolute',
        top: 34,
        left: 72
    },
    more: {
        position: 'absolute',
        top: 40,
        right: 20,
        width: 36,
        height: 8,
        background: '#ffffff'
    }

};

/**
 * 下拉刷新和上拉加载更多组件
 */
var RefreshLoadInfoComp = React.createClass({
    render: function () {
        return (
            <div className={this.props.c1}>
                <div className={this.props.c2}>{this.props.t1}</div>
            </div>
        );
    }
});

/**
 * 弹出提示组件
 */
var AlertComp = React.createClass({
    componentDidMount: function () {
        if (this.props.animate === 'scale') {
            //从下往上滑入
            $(this.refs.contentBox).css({transform: 'scale(0, 0) translateX(-50%)', transformOrigin: '0 50%'});
            $(this.refs.contentBox).animate({
                transform: 'scale(1, 1) translateX(-50%)'
            }, 100, 'ease-in');
        }
    },
    componentWillUnmount: function () {

    },
    render: function () {
        var btn1Comp = null;
        if (this.props.txtb1) {
            btn1Comp = <button style={[StylesAlert.btn0, StylesAlert[this.props.styleb1]]}
                               onClick={this.props.closeHandler}>{this.props.txtb1}</button>
        }
        var btn2Comp = null;
        if (this.props.txtb2) {
            btn2Comp = <button style={[StylesAlert.btn0, StylesAlert[this.props.styleb2]]}
                               onClick={this.props.confirmHandler}>{this.props.txtb2}</button>
        }
        var contentTxtComp = null;
        if (this.props.txtc) {
            contentTxtComp = <div style={[StylesReplyAll.centering,StylesAlert.font1]}>{this.props.txtc}</div>;
        }
        return (
            <div style={StylesAlert.box}>
                <div style={StylesAlert.overlay} onClick={this.props.closeHandler}></div>
                <div ref='contentBox' style={StylesAlert[this.props.stylec]}>
                    {contentTxtComp}
                    {btn1Comp}
                    {btn2Comp}
                </div>
            </div>
        );
    }
});
AlertComp = Radium(AlertComp);
var StylesAlert = {
    box: {
        width: '100%',
        height: '100%',
        position: 'fixed',
        top: 0,
        overflow: 'hidden',
        zIndex: 2
    },
    overlay: {
        position: 'absolute',
        top: 0,
        bottom: 0,
        left: 0,
        right: 0,
        background: 'rgba(0, 0, 0, .8)'
    },
    content1: {
        position: 'absolute',
        top: '6%',
        left: '50%',
        'min-width': 600,
        'min-height': 300,
        'max-width': '80%',
        'max-height': '80%',
        transform: 'translateX(-50%) translateY(-6%)',
        background: '#ffffff',
        border: '1px solid #ddd',
        'border-radius': 12

    },
    font1: {
        'font-size': 28,
        top: '40%',
        transform: 'translate(-50%, -40%)'
    },
    btn0: {
        position: 'absolute',
        bottom: '10%',
        width: 90,
        height: 50,
        background: '#ff5f00',
        'border-radius': 6,
        'font-size': 26,
        'line-height': '100%',
        color: '#fff'
    },
    btn1: {
        margin: '0 auto',
        left: 0,
        right: 0
    },
    btn2: {
        left: '32%'
    },
    btn3: {
        left: '52%'
    }
};
/**
 * 写回复组件
 */
var WriteReplyComp = React.createClass({
    getInitialState: function () {
        return {text: '', imgData: null, alert: 0, alertSendStatus: ''};
    },
    componentDidMount: function () {

        // if(DataConfig.writeAnimate){
        //     //从下往上滑入
        //     $(this.refs.box).css('transform', 'translate(0, 60%)');
        //     //$(this.refs.box).css('transform', 'translate(0, ' + document.documentElement.clientHeight + 'px)');
        //     $(this.refs.box).animate({
        //         transform: 'translate(0, 0)'
        //     }, 100);
        // }

        DataConfig.writeAnimate = true;

        //延时才能聚焦成功
        this.refs.txt.focus();
        setTimeout(function () {
            this.focus();
        }.bind(this.refs.txt), 100);

    },
    render: function () {

        var imgComp = <div style={StylesWriteReply.imgBox}>

            <img style={StylesWriteReply.img1} src={imgPath + 'upload0.png'}/>

            <span style={StylesWriteReply.font1}>添加图片</span>

            <input style={StylesWriteReply.uploadInput} type='file' capture='camera' accept='image/*' id='file'
                   onChange={this.imgChangeHandler} ref='uploadimg'/>

        </div>;
        //如果有图片数据,则显示预览和删除图片按钮
        if (this.state.imgData) {
            imgComp = <button style={StylesWriteReply.imgBox} onClick={this.delImgHandler}>

                <img style={StylesWriteReply.img1} src={this.state.imgData.base64}/>

                <span style={StylesWriteReply.font1}>删除图片</span>

            </button>;
        }

        var alertComp = null;
        if (this.state.alertSendStatus !== '') {
            if (this.state.alertSendStatus !== AlertSendTxt.alertSend0) {
                alertComp =
                    <AlertComp animate='scale' txtc={this.state.alertSendStatus} stylec='content1' styleb1='btn1'
                               txtb1='关闭'
                               closeHandler={this.closeAlertHandler}/>;
            } else {
                alertComp = <AlertComp animate='scale' txtc={this.state.alertSendStatus} stylec='content1'/>;
            }

        } else if (this.state.alert === 1) {
            alertComp = <AlertComp animate='scale' txtc='请输入内容或添加图片' stylec='content1' styleb1='btn1' txtb1='关闭'
                                   closeHandler={this.closeAlertHandler}/>;
        } else if (this.state.alert === 2) {
            alertComp = <AlertComp animate='scale' txtc='确定退出回复话题?' stylec='content1' styleb1='btn2' txtb1='取消'
                                   closeHandler={this.closeAlertHandler} styleb2='btn3' txtb2='确认'
                                   confirmHandler={this.confirmCloseHandler}/>;
        }

        var noContentStyle = [StylesWriteReply.btn, StylesWriteReply.btn3];
        if (this.state.text || this.state.imgData) noContentStyle = [StylesWriteReply.btn, StylesWriteReply.btn2];

        return (
            <div style={StylesWriteReply.box}>
                <div ref='box'>
                    <div style={StylesWriteReply.row1}>
                        <button style={[StylesWriteReply.btn,StylesWriteReply.btn1]} onClick={this.closeHandler}>取消
                        </button>
                        <h1 style={[StylesWriteReply.title]}>回复话题</h1>
                        <button style={noContentStyle} onClick={this.sendHandler}>发送
                        </button>
                    </div>
                <textarea autofocus style={StylesWriteReply.textarea} placeholder='请输入回复内容,限制1000字'
                          value={Utils.cutStrForNum(this.state.text, 1000)}
                          onChange={this.textChangeHandler} ref='txt'></textarea>
                    {imgComp}
                </div>
                {alertComp}
            </div>
        );
    },
    closeAlertHandler: function () {
        if (this.state.alertSendStatus == AlertSendTxt.alertSend1) {
            this.confirmCloseHandler(true);
        } else {
            this.setState({alert: 0, alertSendStatus: ''});
        }
    },
    closeAlertDelayHandler: function () {
        setTimeout(function () {
            this.setState({alert: 0, alertSendStatus: ''});
        }.bind(this), 1000);
    },
    confirmCloseHandler: function (refresh) {
        this.props.closeHandler(refresh);
    },
    textChangeHandler: function (e) {
        // this.setState({text: Utils.cutStrForNum(e.target.value, 1000)});
        this.setState({text: e.target.value});
    },
    imgChangeHandler: function (e) {
        //var name = this.refs.uploadimg.files[0].name;
        lrz(this.refs.uploadimg.files[0], {width: 1024})
            .then(function (rst) {
                this.setState({imgData: rst});
                return rst;
            }.bind(this))
            .catch(function (err) {
                // 万一出错了，这里可以捕捉到错误信息 而且以上的then都不会执行
            })
            .always(function () {
                // 不管是成功失败，这里都会执行
            });
    },
    delImgHandler: function (e) {
        this.setState({imgData: null});
    },
    sendHandler: function (e) {
        console.log("send");
        if (this.state.text || this.state.imgData) {
            this.setState({alertSendStatus: AlertSendTxt.alertSend0});
            if (this.state.imgData) {
                this.sendImgData();
            } else {
                this.sendAllData();
            }
        } else {
            this.setState({alert: 1});
        }
    },
    closeHandler: function (e) {
        if (this.state.text || this.state.imgData) {
            this.setState({alert: 2});
        } else {
            this.confirmCloseHandler();
        }
    },
    sendImgData: function () {
        var xhr = new XMLHttpRequest();
        xhr.timeout = 1000;
        xhr.open('POST', apiUploadImage);
        xhr.onload = function (e) {
            if (xhr.status === 200) {
                console.log('上传成功');
                var data = JSON.parse(xhr.response);
                if (data.errno === null || data.errno === 10000) {
                    this.sendAllData(data.data.pid);
                    if(timeoutID){
                        clearTimeout(timeoutID);
                        timeoutID = undefined;
                    }
                } else {
                    //this.setState({alertSendStatus: AlertSendTxt.alertSend2});
                }

            } else {
                console.log('处理其他情况');
                //this.setState({alertSendStatus: AlertSendTxt.alertSend2});
            }
        }.bind(this);

        xhr.onerror = function () {
            console.log('处理错误');
            //this.setState({alertSendStatus: AlertSendTxt.alertSend2});
        }.bind(this);

        xhr.upload.onprogress = function (e) {
            // console.log('上传进度 var percentComplete = ((e.loaded / e.total) || 0) * 100;');
        };

        // 添加参数
        this.state.imgData.formData.append('image', this.state.imgData.file);
        this.state.imgData.formData.append('user', DataConfig.base64TokenUserId);

        // 触发上传
        xhr.send(this.state.imgData.formData);

        var timeoutID = setTimeout(function () {
            if( timeoutID && this ) this.setState({alertSendStatus: AlertSendTxt.alertSend2});
        }.bind(this), 1000);
    },
    sendAllData: function (imgpid) {
        console.log(imgpid);

        $.ajax({
            type: 'POST',
            timeout: 500,
            url: apiTopicPost,
            data: {
                user: DataConfig.base64TokenUserId,
                id: DataConfig.topicId,
                content: Utils.b64btoa(this.state.text),
                pid: imgpid
            },
            success: function (data) {
                if (data.errno === null || data.errno === 10000) {
                    this.setState({alertSendStatus: AlertSendTxt.alertSend1});
                } else {
                    this.setState({alertSendStatus: AlertSendTxt.alertSend2});
                }
            }.bind(this),
            error: function (xhr, type) {
                console.log(apiTopicPost);
                this.setState({alertSendStatus: AlertSendTxt.alertSend2});
            }.bind(this)
        });
    }
});
WriteReplyComp = Radium(WriteReplyComp);
var AlertSendTxt = {
    alertSend0: '发送中...',
    alertSend1: '发送成功',
    alertSend2: '发送失败'
}
var StylesWriteReply = {
    box: {
        background: '#f0f0f0',
        height: '100%',
        width: 750,
        position: 'fixed',
        top: 0,
        overflow: 'hidden',
        zIndex: 1

    },
    row1: {
        width: '100%',
        height: 94,
        'border-top': 'solid 1px #e3e3e3',
        position: 'relative',
        background: '#fff'
    },
    btn: {
        position: 'absolute',
        top: 30,
        width: 62,
        height: 28,
        'font-size': 28,
        'line-height': '100%',
        background: '#fff',
        zIndex: 1

    },
    btn1: {
        left: 30
    },
    btn2: {
        right: 30,
        color: '#ff6c00'

    },
    btn3: {
        right: 30,
        color: '#9c9c9c'

    },
    title: {
        position: 'absolute',
        left: 0,
        right: 0,
        top: 28,
        'font-size': 36,
        'line-height': '100%',
        'text-align': 'center'
    },
    textarea: {
        width: '100%',
        height: 300,
        'border': 'solid 1px #e3e3e3',
        'font-size': 28,
        padding: 10,
        background: '#fff'
    },
    imgBox: {
        width: '100%',
        height: 160,
        position: 'relative',
        background: '#fff'
    },
    uploadInput: {
        width: '100%',
        height: '100%',
        opacity: .01,
        position: 'absolute',
        left: 0,
        top: 0
    },
    img1: {
        width: 140,
        height: 140,
        position: 'absolute',
        left: 10,
        top: 10
    },
    font1: {
        'font-size': 26,
        'line-height': '100%',
        position: 'absolute',
        left: 160,
        top: 70,
        color: '#9c9c9c'
    }
};


/**
 * 入口组件
 */
var App = React.createClass({
    /**
     * repliesList 回复列表数组,示例数据结构[
     {
       "content": "Hello World",
       "created_at": 1464346709,
       "icon": "http://www.baidu.com/favico.ico2",
       "id": 875,
       "image": "",
       "likes": 0,
       "nickname": "alec",
       "seq": 102,
       "shares": 0,
       "team_icon": null,
       "user_id": "123456789"
       "key": "index"
     },...]
     * replyTop 置顶回复,数据和列表中单个一样
     * downloadADVisible 下载组件是否显示,downloadADVisible: true
     */
    getInitialState: function () {
        return {
            repliesList: null,
            replyTop: null,
            initDataReady: false,
            writeReplyVisible: false
        };
    },
    componentDidMount: function () {
        // Utils.docCookiesSetItem('bfuid','135601920077074447');//测试数据
        // DataConfig.bfuid = Utils.docCookiesGetItem('bfuid');//测试数据
        // DataConfig.topicTitle = '话题标题';//测试数据
        // DataConfig.topicId = '1';//测试数据
        // DataConfig.topicReplyId = '1';//测试数据

        Utils.getInitDataCookie();
        //Utils.setInitDataCookie();

        this.userGet(DataConfig.bfuid);
        this.repliesListGet(DataConfig.topicId);
        this.replyTopGet(DataConfig.topicReplyId);

        if (Utils.docCookiesGetItem('writeReplyVisible') === '1' && DataConfig.bfuid) {
            DataConfig.writeAnimate = false;
            this.setState({writeReplyVisible: true});
        }
        Utils.docCookiesSetItem('writeReplyVisible', '0');


        //下拉刷新和上拉加载更多
        var appRefresher = Refresher.init({
            box: this.refs.iscroll,//<------------------------------------------------------------------------------------┐
            pullDownAction: function () {
                console.log("Refresh");
                this.repliesListGet(DataConfig.topicId, appRefresher);
            }.bind(this),
            pullUpAction: function () {
                console.log("Load More");
                this.repliesListMoreGet(DataConfig.topicId, appRefresher);
            }.bind(this)
        });
        //下拉上拉hack
        setTimeout(function () {
            this.refresh();
        }.bind(appRefresher), 200);
        document.addEventListener("visibilitychange", function () {
            if (!document.hidden) this.refresh();
        }.bind(appRefresher), false);

    },
    render: function () {

        //处理初始异步数据加载完成前后的组件显示状态
        //如果初始异步数据都已经加载完成
        //页面为空时，显示空白提示
        //刷新加载提示
        var visible = {visibility: 'hidden'};
        var appADcomp = null;
        var writeBTN = null;
        var infoComp = null;
        var refreshInfoComp = <RefreshLoadInfoComp c1='pullDown' c2='pullDownLabel' t1='下拉刷新...'/>;
        var loadMoreInfoComp = <RefreshLoadInfoComp c1='pullUp' c2='pullUpLabel' t1='上拉加载更多...'/>
        if (this.state.initDataReady) {
            visible = {visibility: 'visible'};
            if (!this.state.replyTop && !this.state.repliesList) {

                infoComp = <h2 style={{width: '100%', 'text-align': 'center'}}>该话题还没有回复，快抢沙发吧!</h2>
            } else if (!this.state.repliesList) {
                infoComp = <h2 style={{width: '100%', 'text-align': 'center'}}>该话题还没有其他回复，快盖楼吧!</h2>
            }

            if (!(this.state.repliesList && this.state.repliesList.length)) {
                appADcomp = <ContentADComp />
            }

            writeBTN = <div style={StylesReplyAll.writeBTNBox}>
                <button style={StylesReplyAll.writeBTN} onClick={this.writeHandler}>
                    <img src={imgPath+'post0.png'}/>
                </button>
            </div>


        }

        /**
         * 如果有置顶的话题回复数据,则置顶此回复组件后再显示回复列表
         * 否则只显示回复列表
         */
        var replyTopComp = null;
        var repliesListTitleComp = null;
        if (this.state.replyTop) {
            //liked 用户已点赞的回复ID数组
            //liked =[1];//测试数据
            //this.state.replyTop.likes+=1;//测试数据
            replyTopComp = <ReplyComp data={this.state.replyTop} liked={Utils.getLiked()}/>
            repliesListTitleComp = <h1 style={[StylesReplyAll.title, StylesReplyAll.two]}>大家的回复</h1>
        }

        var replyListComp = null;
        if (this.state.repliesList) {
            replyListComp = <ReplyListComp data={this.state.repliesList}/>
        }

        //是否显示下载组件
        // var downloadADComp = this.state.downloadADVisible ?
        //<DownloadADComp closeHandler={this.closeDownloadAD}/> : null;

        var page1Visible = StylesReplyAll.pwh;
        var writeReplyComp = null;

        if (this.state.writeReplyVisible) {
            writeReplyComp = <WriteReplyComp closeHandler={this.closeWriteReplyHandler}/>
            page1Visible = [StylesReplyAll.pwh, visible];
        }

        var titleTemp;
        try {
            titleTemp = Utils.b64atob(DataConfig.topicTitle);
        }
        catch (e) {
            titleTemp = DataConfig.topicTitle;
        }


        return (
            <StyleRoot style={[StylesReplyAll.pwh,visible]}>
                <div ref='iscroll' style={page1Visible}>
                    <div style={{position: 'relative','min-height':document.documentElement.clientHeight}}>
                        {refreshInfoComp}
                        <h1 style={[StylesReplyAll.title, StylesReplyAll.one]}>{titleTemp}</h1>
                        {replyTopComp}
                        {repliesListTitleComp}
                        {appADcomp}
                        {replyListComp}
                        {infoComp}
                        {loadMoreInfoComp}
                    </div>
                    {writeBTN}
                    <DownloadADComp />
                </div>
                {writeReplyComp}
                {StyleRulesGlobal}
            </StyleRoot>
        );
    },
    repliesListMoreGet: function (id, refresher) {

        if (id) {

            $.ajax({
                type: 'GET',
                url: apiTopicListMore,
                data: {id: id, key: this.state.repliesList[this.state.repliesList.length - 1].key},
                dataType: 'json',
                timeout: 500,
                success: function (data) {
                    if (Array.isArray(data.data.more) && data.data.more.length) {
                        var list = this.state.repliesList;
                        if (!list) list = [];
                        var newList = list.concat(data.data.more);
                        this.setState({repliesList: newList});
                    }
                    if (refresher) refresher.refresh();
                }.bind(this),
                error: function (xhr, type) {
                    console.log(apiTopicListMore);
                    if (refresher) refresher.refresh();
                }.bind(this)
            });


        } else if (refresher) {
            refresher.refresh();
        }
    },
    repliesListGet: function (id, refresher) {
        if (id) {
            $.ajax({
                type: 'GET',
                url: apiTopicList,
                data: {id: id},
                dataType: 'json',
                timeout: 500,
                success: function (data) {
                    if (data && data.data && data.data.list && Array.isArray(data.data.list.posts) && data.data.list.posts.length) {
                        //var repliesListArray = data.data.list.posts;
                        //if(refresher){

                            //var list = this.props.data.map(function (item) {
                               // return <ReplyComp data={item} liked={Utils.getLiked()}/>;
                            //});

                            var allLiked = Utils.getLiked();
                            var repliesListArray = data.data.list.posts.map(function (item) {
                                if(allLiked && allLiked.indexOf(item.id) !== -1){
                                    item.likes--;
                                }

                                return item;
                            });
                        //}
                        this.setState({repliesList: repliesListArray});
                    }
                    if (Utils.initDataReadyCheck() && !this.state.initDataReady) this.setState({initDataReady: true});
                    if (refresher) refresher.refresh();

                }.bind(this),
                error: function (xhr, type) {
                    console.log(apiTopicList + '?id=' + id);
                    if (Utils.initDataReadyCheck() && !this.state.initDataReady) this.setState({initDataReady: true});
                    if (refresher) refresher.refresh();
                }.bind(this)
            });

        } else {
            if (refresher) refresher.refresh();
            if (Utils.initDataReadyCheck() && !this.state.initDataReady) this.setState({initDataReady: true});
        }
    },
    replyTopGet: function (id) {
        if (id) {

            $.ajax({
                type: 'GET',
                url: apiTopicOne,
                data: {id: id},
                dataType: 'json',
                timeout: 500,
                success: function (data) {
                    this.setState({replyTop: data.data.find});
                    if (Utils.initDataReadyCheck() && !this.state.initDataReady) this.setState({initDataReady: true});
                }.bind(this),
                error: function (xhr, type) {
                    console.log(apiTopicOne + '?id=' + id);
                    if (Utils.initDataReadyCheck() && !this.state.initDataReady) this.setState({initDataReady: true});
                }.bind(this)
            });

        } else {
            if (Utils.initDataReadyCheck() && !this.state.initDataReady) this.setState({initDataReady: true});
        }
    },
    userGet: function (id) {
        if (id) {
            $.ajax({
                type: 'GET',
                url: apiSportsGetUser + id,
                //data: id,
                dataType: 'json',
                timeout: 500,
                success: function (data) {
                    var s = data.token + ':' + id;
                    DataConfig.base64TokenUserId = Utils.b64btoa(s);
                    this.userLogin();
                }.bind(this),
                error: function (xhr, type) {
                    console.log(apiSportsGetUser + id);
                }.bind(this)
            });

        }
    },
    userLogin: function () {

        $.ajax({
            type: 'POST',
            url: apiSportsLogin,
            data: {
                user: DataConfig.base64TokenUserId,
                nickname: Utils.b64btoa(Utils.docCookiesGetItem('bfuname'))
            },
            error: function (xhr, type) {
                console.log(apiSportsLogin);
            }.bind(this)
        });

    },
    writeHandler: function (e) {
        e.preventDefault();
        if (!this.state.writeReplyVisible) {
            if (DataConfig.base64TokenUserId && !this.state.writeReplyVisible) {
                this.setState({writeReplyVisible: true});
            } else {
                //Utils.setInitDataCookie();
                Utils.docCookiesSetItem('writeReplyVisible', '1');

                //var loginUrl = 'http://sso.baofeng.net/api/mlogin/default?from=sports_h5&version=1&did=&btncolor=blue&next_action=' + sportsH5Url + '&selfjumpurl=' + sportsH5Url;

                var url = encodeURIComponent(sportsH5Url + '?' + Utils.setInitDataCookie());

                window.location = loginUrl + url + '&selfjumpurl=' + url;
            }
        }
        _hmt.push(['_trackEvent', 'H5话题分享', '点击回复按钮', '回复']);
    },
    closeWriteReplyHandler: function (refresh) {
        this.setState({writeReplyVisible: false});
        if (refresh) this.repliesListGet(DataConfig.topicId);
    }

});
App = Radium(App);
/**
 * 全局CSS
 */
var StyleRulesGlobal = <Style rules={{
    '*, *:before, *:after': {
         'box-sizing': 'border-box',
         padding: 0,
         margin: 0
     },
    'html,body': {
        width: '100%',
        height: '100%'
    },
    button: {
        outline: 'none',
        border: 'none',
        overflow: 'hidden'
    },
    '#app': {
        width: 750,
        height: '100%',
        position: 'relative',
        margin: '0 auto',
        'overflow-x': 'hidden'
    },
    '.hide': {
      'text-indent': '100%',
      'white-space': 'nowrap',
      overflow: 'hidden',
      'font-size': 0,
      height: 0
    },
    '.pullDown, .pullUp': {
        'text-align': 'center',
        height: 40,
        'line-height': 40,
        'font-size': 24,
        color: '#888'
    },
    '.pullUp': {
        display: 'block',
        height: 94
    }
}}/>;
var StylesReplyAll = {
    pwh: {
        position: 'relative',
        width: '100%',
        height: '100%'
    },
    centering: {
        margin: 'auto',
        position: 'absolute',
        top: '50%',
        left: '50%',
        transform: 'translate(-50%, -50%)'
    },
    title: {
        'padding-left': 22,
        width: '100%',
        'min-height': 64,
        'border-top': 'solid 1px #e3e3e3',
        'font-size': 32,
        'line-height': '100%',
        overflow: 'hidden',
        'margin-bottom': 36
    },
    one: {
        'padding-top': 36
    },
    two: {
        'min-height': 80,
        'margin-bottom': 0,
        'padding-top': 50,
        background: '#f0f0f0'
    },
    writeBTNBox: {
        position: 'fixed',
        bottom: 165,
        width: 750,
        overflow: 'hidden'
    },
    writeBTN: {
        position: 'relative',
        left: 630,
        width: 100,
        height: 100,
        background: 'rgba(0, 0, 0, .0)'
    }
};

/**
 * 渲染APP
 */
ReactDOM.render(
    <App/>,
    document.getElementById('app')
);

