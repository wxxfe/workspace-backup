DeviceApi = {
    APP_SCHEMA: 'BFSports://sports.baofeng.com',
    YYB_APP_URL: 'http://a.app.qq.com/o/simple.jsp?pkgname=com.sports.baofeng',
    openApp: function (appUrl) {
        DeviceApi.loadSchema(appUrl);
    },
    downloadApp: function () {
        var channel = Utils.getUrlParam(window.location.href, 'channel');
        if (!channel || channel == 'undefined') {
            channel = 'a01';
        }
        top.location.href = 'http://jump.sports.baofeng.com/mobile/bfsport/bfsports_' + channel + '.apk';
    },
    jumpTo: function (domNode) {
        if (Utils.pageType === 'app') {
            DeviceApi.jumpToInApp(domNode);
        } else {
            DeviceApi.jumpToInShare(domNode);
        }
    },
    jumpToInApp: function (domNode) {
        var info = $(domNode).data('info');
        if (!info) return;
        if (window.webplay.jumpTo) {
            var param = {};
            param.type = info.type;
            param.data = info;
            window.webplay.jumpTo(JSON.stringify(param));
        } else {
            // 兼容已废弃的老接口数据格式
            if (info.type === 'gallery') {
                window.webplay.jumpToGallery(info.id, info.title, info.image, info.brief);
            } else if (info.type === 'video') {
                window.webplay.jumpToVideo(info.title, info.site, info.id, info.image, info.play_url, info.play_code, info.isvr);
            } else if (info.type === 'news') {
                window.webplay.jumpToNews(info.id, info.title, info.image, info.large_img);
            }
        }
    },
    jumpToInShare: function (domNode) {
        var $node = $(domNode);
        var url = $node.data('url');
        var info = $node.data('info');
        var appUrl = DeviceApi.getAppUrl(info);
        if (url) {
            if (/(\/share)$/.test(url)) {
                window.location.href = url + '?__appurl=' + appUrl;
            } else {
                window.location.href = url + '/share?__appurl=' + appUrl;
            }
        }
    },
    shareTo: function (pf) {
        window.webplay.shareTo && window.webplay.shareTo(pf);
    },
    getAppUrl: function (info) {
        var appUrl = DeviceApi.APP_SCHEMA;
        if (info) {
            appUrl += '/' + info.type + '?' + Utils.convertJsonToUrlParams(info);
            // 兼容已废弃的老接口数据格式
            if (info.type === 'news') {
                appUrl += '&largeimage=' + info.large_image;
            }
        }
        return appUrl;
    },
    loadSchema: function (schemaUrl, failBackUrl) {

        //因为没有办法肯定并及时的判断是否成功打开APP。
        //尝试多种方式，不同设备和app支持不一样，有些APP，比如微信内是不能打开其他APP的。
        //只能一种种尝试，用定时器间隔尝试
        //因为在没有安装app的情况下，用iframe的方式，不会触发无效地址的错误提示，所以优先尝试
        //支持比较多的是直接赋值给当前顶层页面的location.href，但是没有安装app的情况下，会触发无效地址的错误提示
        //最后跳转到下载页面
        //这种流程很大可能会触发多次是否打开APP的提示或打开APP，或无效链接的错误提示，可以通过delay参数调节
        //没有安装APP的情况下，delay小些，能快点前往下载页
        //安装了APP的情况下，delay大些，能减少触发多次的问题。

        /* 尝试用iframe方式唤醒app */
        // var f = $("#open_app_iframe");
        // if (f.length == 0) {
        //     f = $("<iframe>");
        //     f.attr("id", "open_app_iframe");
        //     f.attr("name", "open_app_iframe");
        //     $("body").append(f);
        //     f.hide();
        // }
        // f.attr("src", schemaUrl);

        //iframe可能失败，尝试用location.href方式
        //setTimeout(function () {
        top.location.href = schemaUrl;
        //}, 200);

        if (failBackUrl) {

            // 如果LOAD_WAITING时间后,还是无法唤醒app，则直接打开下载页
            var start = Date.now();
            var delay = 1600;
            var loadTimer = setTimeout(function () {

                if (document.hidden || document.webkitHidden) {
                    return;
                }

                // 如果app启动，浏览器最小化进入后台，则计时器存在推迟或者变慢的问题
                // 那么代码执行到此处时，时间间隔必然大于设置的定时时间
                if (Date.now() - start > delay + 200) {
                    // come back from app

                    // 如果浏览器未因为app启动进入后台，则定时器会准时执行，故应该跳转到下载页
                } else {
                    top.location.href = failBackUrl;
                }

            }, delay);

            // app打开后，页面会隐藏掉，就会触发pagehide与visibilitychange事件
            // 在部分浏览器中可行，网上提供方案，作hack处理
            // 如果已经打开APP，并且定时器还没执行的话，则去掉定时器。
            var visibilitychange = function () {
                var tag = document.hidden || document.webkitHidden;
                if (tag) {
                    clearTimeout(loadTimer);
                }

            };
            document.addEventListener('visibilitychange', visibilitychange, false);
            document.addEventListener('webkitvisibilitychange', visibilitychange, false);
            // pagehide 必须绑定到window
            window.addEventListener('pagehide', function () {
                clearTimeout(loadTimer);
            }, false);
        }

    },
    videoEnterFullScreen: function() {
    		window.webplay && window.webplay.videoEnterFullScreen && window.webplay.videoEnterFullScreen();
    },
    videoExitFullScreen: function() {
    		window.webplay && window.webplay.videoExitFullScreen && window.webplay.videoExitFullScreen();
    }
    
    

}
