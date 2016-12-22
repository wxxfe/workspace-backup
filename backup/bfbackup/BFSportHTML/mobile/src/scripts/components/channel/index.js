/**
 * 分享和渠道推广交互，如当前页面url有渠道数据，则当前页面里所有跳转的url都要补上渠道数据
 * @sample
 *  ChannelShare.init();
 */

function ChannelShare() {

    //立即下载按钮
    this.downloadButton = $('.download-button');

    //相关资讯链接
    this.newsA = $('.news-action a');

    this.loadIframe = null;

    this.init();

    return this;
}

ChannelShare.fn = ChannelShare.prototype;

ChannelShare.fn.browser = {
    versions: function () {
        var u = navigator.userAgent, app = navigator.appVersion;
        return {
            trident: u.indexOf('Trident') > -1, //IE内核
            presto: u.indexOf('Presto') > -1, //opera内核
            webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
            gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,//火狐内核
            mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
            android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
            iPhone: u.indexOf('iPhone') > -1, //是否为iPhone或者QQHD浏览器
            iPad: u.indexOf('iPad') > -1, //是否iPad
            webApp: u.indexOf('Safari') == -1, //是否web应该程序，没有头部与底部
            weixin: u.indexOf('MicroMessenger') > -1, //是否微信 （2015-01-22新增）
            qq: u.match(/\sQQ/i) == " qq" //是否QQ
        };
    }(),
    language: (navigator.browserLanguage || navigator.language).toLowerCase()
};

ChannelShare.fn.init = function () {
    var _self = this;

    //处理是否给url加上渠道数据
    this.replaceDownloadUrl();

    //立即下载按钮事件
    this.downloadButton.click(function (e) {
        e.preventDefault();
        DeviceApi.loadSchema(DeviceApi.getAppUrl($(this).data('info')), $(this).data('url'));
    });
}

ChannelShare.fn.queryString = function (url, name) {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var spArr = url.split('?');
    var r = spArr.length > 1 && spArr[1].match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}

ChannelShare.fn.replaceDownloadUrl = function () {
    var channel = this.queryString(window.location.href, 'channel');
    if (!channel || channel === 'undefined') return false;
    var downloadUrl = this.getDownloadUrl(channel);
    this.replaceUrl(downloadUrl);
    // this.replaceRelatedUrl();

    //百度点击统计
    _hmt.push(['_trackEvent', channel, 'download', 'channel app']);
}

ChannelShare.fn.getDownloadUrl = function (channel) {
    return 'http://wx.dl.baofeng.com/mobile/bfsport/bfsports_' + channel + '.apk';
}

ChannelShare.fn.replaceUrl = function (downloadUrl) {
    //this.downloadButton.attr('href', downloadUrl);
    this.downloadButton.data('url', downloadUrl);
}

ChannelShare.fn.replaceRelatedUrl = function () {
    var search = window.location.search;
    if (search == '') return false;
    this.newsA.each(function () {
        var currentUrl = $(this).data('url');
        $(this).data('url', currentUrl + search);
    });
}

function ChannelShareFactory() {
    return new ChannelShare();
}
