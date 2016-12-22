/**
 * 接口前缀
 */
let topicPrefix = 'http://api.board.sports.baofeng.com/api/v1/h5/board/thread/';

export const API = {
    /**
     * 图文话题列表
     参数：
     id: 话题Thread.id
     user_id: 用户id (可选)
     key: 更多参考位置 (可选)
     limit: 分页大小 (可选）
     order_type 值：update，like (可选）
     */
    topicList: topicPrefix + 'content/list',

    /**
     * 图文话题单个回复数据
     * 参数：
     * id：回复id
     {
       "data": {
         "body": {
           "content": "fgggg",
           "created_at": 1469184185,
           "featured": 0,
           "icon": "http://img.baofeng.net/head/8802/9984/9200/135601920099848802/100_80_80.jpg?t=1467793049.96",
           "id": 11,
           "image": "",
           "likes": 0,
           "likes_json": "[]",
           "nickname": "1371_3425_5",
           "seq": 7,
           "user_id": "135601920099848802"
         }
       }
     }
     */
    topicOne: topicPrefix + 'post/id',

    /**
     * 图文话题我要发言
     * 参数：
     * user_id：用户
     * id：话题id
     * content：帖子内容base64
     * pid：发的图url
     */
    topicPost: topicPrefix + 'content/post',

    /**
     * 点赞
     * 参数：
     * id：帖子id
     * user_id：用户id(可以为空)
     * nickname：用户名(可以为空)
     */
    topicLike: topicPrefix + 'post/like',

    /**
     路径："/api/v1/(android|iphone)/board/login"
     域名：api.board.sports.baofeng.com
     方法：POST
     参数：
     id: 用户id
     nickname: 用户昵称 Base64
     avatar: 用户头像url
     vip_syn: vip标识 (默认为0)
     expired_time: vip过期时间
     token: 令牌Token Base64
     */
    sportsLogin: 'http://api.board.sports.baofeng.com/api/v1/h5/board/login',

    /**
     * 上传图片
     * FormData
     * user - String - Base64(token:user_id) - 某些数据不需用户信息 用户认证信息 - 参见 `/api/v1/commit` 接口参数
     * image - Binary - 图片数据
     * 响应：
     *{
 *    "pid": "e4eb1d6be5b0c7470a7c5aa391bf9d26"
 *}
     */
    uploadImage: 'http://upload.image.sports.baofeng.com/upload',

    /**
     * 获得用户数据
     * let d = miniSSOLogin.getCookie("bfuid");
     * $.get('http://sports.baofeng.com/login/sso/' + d.info.uid,function(d){
                let obj = jQuery.parseJSON(d);
                localStorage.set('stoken',obj.token);
            });
     */
    sportsGetUser: 'http://sports.baofeng.com/login/sso/'
};

export const CONFIG = {
    /**
     * APP下载链接
     */
    downloadAppUrl: 'http://a.app.qq.com/o/simple.jsp?pkgname=com.sports.baofeng',
    /**
     * 用户登录地址
     */
    loginUrl: 'http://sso.baofeng.net/api/mlogin/default?from=sports_h5&version=1&did=&btncolor=blue&next_action='
//let loginUrl = 'http://sso.baofeng.net/api/mlogin/default?from=sports_h5&version=1&did=&btncolor=blue&next_action=' + sportsH5Url + '&selfjumpurl=' + sportsH5Url;

};

export const STORAGE_KEY = {
    uploadImg: 'topicflagsuploadimg',
    canvasImg: 'topicflagscanvasimg',
    text: 'topicflagstext',
    editStatus: 'topicflagseditstatus',
    liked: 'topicflagsliked',
    share: 'topicflagsshare'
};

export const COOKIES_KEY = {
    uid: 'bfuid',
    uname: 'bfuname'
};

export const URL_PARAM_NAME = {
    replyId: 'topicflagsreplyid'
};

export let Utils = {

    /**
     * 获得url参数
     * @param name 参数名
     * @returns {*} 参数值
     */
    urlParam (name) {
        let results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results == null) {
            return null;
        } else {
            return decodeURIComponent(results[1]) || 0;
        }
    },
    /**
     * 获得Cookie项目值
     */
    docCookiesGetItem (sKey) {
        if (!sKey) {
            return null;
        }
        return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) + '' || null;
    },
    /**
     * 写入Cookie项目值
     */
    docCookiesSetItem (sKey, sValue) {
        if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) {
            return false;
        }
        document.cookie = encodeURIComponent(sKey) + '=' + encodeURIComponent(sValue);
        //document.cookie = encodeURIComponent(sKey) + '=' + encodeURIComponent(sValue) + '; domain=.baofeng.com';
        return true;
    },
    /**
     * 对普通字符串进行b64编码
     */
    b64btoa (str) {
        return window.btoa(encodeURI(str));
    },
    /**
     * 对b64编码字符串解码,转换成普通字符串
     * @param str
     * @returns {string}
     */
    b64atob (str) {
        return decodeURI(window.atob(str));
    },
    /**
     * 把base64图片的dataURI字符串转换成Blob对象类型
     * @param dataURI
     * @returns {*}
     */
    dataURItoBlob (dataURI) {
        // convert base64 to raw binary data held in a string
        var byteString = atob(dataURI.split(',')[1]);

        // separate out the mime component
        var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

        // write the bytes of the string to an ArrayBuffer
        var arrayBuffer = new ArrayBuffer(byteString.length);
        var _ia = new Uint8Array(arrayBuffer);
        for (var i = 0; i < byteString.length; i++) {
            _ia[i] = byteString.charCodeAt(i);
        }

        var dataView = new DataView(arrayBuffer);
        var blob = new Blob([dataView], {type: mimeString});
        return blob;
    },
    /**
     * 写入localStorage项目值
     */
    storageSetObj (key, obj) {
        window.localStorage.setItem(key, JSON.stringify(obj));
    },
    /**
     * 获得localStorage项目值
     */
    storageGetObj (key) {
        let value = window.localStorage.getItem(key);
        if (value && value != 'undefined' && value != 'null') {
            return JSON.parse(value);
        } else {
            return null;
        }
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
    dateFormat (date, format) {
        if (format === undefined) {
            format = date;
            date = new Date();
        }
        let map = {
            "M": date.getMonth() + 1, //月份
            "d": date.getDate(), //日
            "h": date.getHours(), //小时
            "m": date.getMinutes(), //分
            "s": date.getSeconds(), //秒
            "q": Math.floor((date.getMonth() + 3) / 3), //季度
            "S": date.getMilliseconds() //毫秒
        };
        format = format.replace(/([yMdhmsqS])+/g, function (all, t) {
            let v = map[t];
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
    getUserAvatar (uid) {
        return 'http://img.baofeng.net/head/' + uid.substr(-4, 4) + '/' + uid.substr(-8, 4) + '/' + uid.substr(-12, 4) + '/' + uid + '/100_80_80.jpg?t=' + new Date().getTime();
    },

    /**
     * 获得点赞数据
     *
     */
    getLiked () {
        let liked = window.localStorage.getItem(STORAGE_KEY.liked);
        //let liked = Utils.docCookiesGetItem(COOKIES_KEY.liked);
        if (liked) liked = JSON.parse(liked);
        if (!Array.isArray(liked)) liked = null;
        return liked;
    },

    /**
     * 写入或者删除点赞数据
     * @param id 要处理的ID
     * @param add true 加入 false 删除
     */
    setLiked (id, add) {
        let liked = Utils.getLiked();
        if (liked == null)liked = [];
        for (let i = liked.length - 1; i > -1; i--) {
            if (liked[i] == id) liked.splice(i, 1);
        }
        if (add) liked.push(id);
        window.localStorage.setItem(STORAGE_KEY.liked, JSON.stringify(liked))
        //Utils.docCookiesSetItem(COOKIES_KEY.liked, JSON.stringify(liked));
    },
    getInitDataCookie () {
        if (!Data.topicReplyId) Data.topicReplyId = Utils.docCookiesGetItem(COOKIES_KEY.replyId);
        Utils.docCookiesSetItem(COOKIES_KEY.replyId, '');

        Data.bfuid = Utils.docCookiesGetItem(COOKIES_KEY.uid);
        Data.bfuname = Utils.docCookiesGetItem(COOKIES_KEY.uname);

    },
    setInitDataCookie () {
        let url = '';
        if (Data.topicReplyId) {
            Utils.docCookiesSetItem(COOKIES_KEY.replyId, Data.topicReplyId);
            url += ('&' + URL_PARAM_NAME.replyId + '=' + Data.topicReplyId);
        }
        window.location.search = url;
        return url;
    },
    getInitDataStorage () {
        DataEdit.editStatus = Utils.storageGetObj(STORAGE_KEY.editStatus);
        if (!DataEdit.editStatus) DataEdit.editStatus = 0;
        window.localStorage.removeItem(STORAGE_KEY.editStatus);

        DataEdit.canvasImg = Utils.storageGetObj(STORAGE_KEY.canvasImg);
        if (!DataEdit.canvasImg) DataEdit.canvasImg = '';
        window.localStorage.removeItem(STORAGE_KEY.canvasImg);

        DataEdit.text = Utils.storageGetObj(STORAGE_KEY.text);
        if (!DataEdit.text) DataEdit.text = '';
        window.localStorage.removeItem(STORAGE_KEY.text);

    },
    setInitDataStorage (clear) {
        if (clear) {
            window.localStorage.removeItem(STORAGE_KEY.editStatus);
            window.localStorage.removeItem(STORAGE_KEY.canvasImg);
            window.localStorage.removeItem(STORAGE_KEY.text);
        } else {
            Utils.storageSetObj(STORAGE_KEY.editStatus, 1);
            Utils.storageSetObj(STORAGE_KEY.canvasImg, DataEdit.canvasImg);
            Utils.storageSetObj(STORAGE_KEY.text, DataEdit.text);
        }
    },

    /**
     * 返回字符的字节长度（汉字算2个字节）
     * @param {string}{number}
     * @returns {string}   +'...'
     */
    cutStrForNum (str, num) {
        let len = 0;
        let newStr = str;
        for (let i = 0; i < str.length; i++) {
            if (str[i].match(/[^x00-xff]/ig) != null) //全角
                len += 2;
            else
                len += 1;
        }
        if (len >= num) {
            newStr = str.substring(0, num);
        }
        return newStr;
    },

    getUrlPrefix () {
        let index = window.location.href.lastIndexOf('/');
        if (index > 0) {
            return window.location.href.substring(0, index);
        }
        return '';
    }
};

/**
 * 编辑页面数据
 * maskImage 遮罩图片路径数组
 * flagThumbnailImage 国旗缩略图片路径数组
 * flagImage 国旗编辑图片路径数组
 */
export let DataEdit = {
    maskImages: [
        './img/mask/1.png',
        './img/mask/2.png',
        './img/mask/3.png',
        './img/mask/4.png',
        './img/mask/5.png'
    ],
    flagThumbnailImages: [
        {img: './img/flags/1', title: '方形国旗'},
        {img: './img/flags/2', title: '心星国旗'},
        {img: './img/flags/3', title: '五角星'},
        {img: './img/flags/4', title: '奥运五环'},
        {img: './img/flags/5', title: '里约2016'}
    ],
    text: '',
    canvasImg: '',
    editStatus: 0
};
/**
 * 首页数据
 */
export let DataIndex = {
    topicInfo: '制作专属你的奥运脸，和朋友们一起为奥运喝彩，为中国加油！',
    listOrderType: ['update', 'like'],
    listOrderTypeIndex: 0
};

/**
 * topictitle 话题标题
 * topicid 话题ID
 * topicreplyid 需要置顶的话题回复ID
 * base64TokenUserId 验证用户的数据
 *
 */
let Data = {
    topicTitle: '最美奥运脸',
    // topicId: 3,
    topicId: 373,
    topicReplyId: Utils.urlParam(URL_PARAM_NAME.replyId),
    bfuid: '',
    bfuname: '',
    userToken: '',
    base64TokenUserId: ''
};

export default Data;