define(function() {

    function FlashPlayer() {
        this.storm;
        this.playelement;
        this.adscode;
        this.cache;
        this.cachedata;
        this.starttime = 0;
        this.playtime = 0;
        this.playing = false;
        this.playcode = 0;
        this.autoplay = 1;
        this.playid = "play" + Math.random().toString(36).substr(2, 6);
        this.playwidth = "100%";
        this.playheight = "100%";
        this.loadsuccess = false;
        this.playurl = "http://static.hd.baofeng.com/swf/player/loader.swf?ver=" + this.GetVersion();
        this.playinfo = {
            "ispay": 0, //0 普通影片  1 试看影片 2 购买影片
            "wid": '',
            "aid": '',
            "vid": '',
            "title": '',
            "count": '',
            "current": ''
        };
        this.cookie = $.cookie;
    }
    FlashPlayer.TMPL = '<object width="{width}" height="{height}" type="application/x-shockwave-flash" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="{id}"><param value="{data}" name="movie"><param value="High" name="quality"><param value="true" name="allowfullscreen"><param value="opaque" name="wmode"><param value="always" name="allowscriptaccess"><param value="adlink=" name="flashvars"><embed width="{width}" height="{height}" flashvars="" allowscriptaccess="always" wmode="opaque" allowfullscreen="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="{data}" name="{id}"></object>';
    FlashPlayer.CONST = {
        LogMap: {
            "cut": "flashcut",
            "err": "flasherr",
            "clickpay": "buytips"
        }
    };

    /**
     *  返回当前播放器是否可以播放
     */
    FlashPlayer.prototype.GetPlaying = function() {
        return this.playing;
    };

    /**
     * 设置视频相关标题信息
     * @param {json} info 视频信息 {"title":"标题","current":"当前集","count":总集数}
     */
    FlashPlayer.prototype.SetVideoInfo = function(info) {
        for (var key in info) {
            if (key in this.playinfo) {
                this.playinfo[key] = info[key];
            }
        }
    };

    /**
     * 设置专辑信息
     * @param {json} info {"wid":1,"aid":1,"vid":1}
     */
    FlashPlayer.prototype.SetMovieInfo = function(info) {
        for (var key in info) {
            if (key in this.playinfo) {
                this.playinfo[key] = info[key];
            }
        }
    };
    /**
     * 获取当前播放视频相关专辑信息
     * @param {string} key 专辑信息key
     */
    FlashPlayer.prototype.GetMovieInfo = function(key) {
        if (key in this.playinfo) {
            return this.playinfo[key];
        }
        return "00";
    }

    /**
     * 获取当前版本号
     * @param {string} key 专辑信息key
     */
    FlashPlayer.prototype.GetVersion = function() {
        var hlsurl, element, version = "201512281934";
        var scripts = document.getElementsByTagName('script');
        for (var i = 0, len = scripts.length; i < len; i++) {
            element = scripts[i];
            hlsurl = element.getAttribute("src");
            if (hlsurl && /\_(.[\d_]+?)\.js/ig.test(hlsurl)) {
                hlsurl.replace(/\_(.[\d_]+?)\./ig, function() {
                    version = arguments[1];
                });
            }
        }
        return version;
    };
    FlashPlayer.prototype.calCulate = function(index) {
        var temp = [536870912, 536870913, 536870914, 536870915, 536870916, 536870917, 536870918, 536870919];
        return temp[index];
    };
    /**
     * 设置广告展现方式
     * @param {int} index
     * 0  开启全部广告
     * 1  开启前播广告
     * 2  开启中播广告
     * 3  开启前播和中播广告
     */
    FlashPlayer.prototype.SetAds = function(index) {
        this.adscode = this.calCulate(index || 0);
    };

    /**
     * 获取视频当前播放时间
     */
    FlashPlayer.prototype.GetPlayTimes = function() {
        this.playtime = 0;
        if (this.playing) {
            try {
                this.playtime = parseInt($.parseJSON(this.playelement['jsToAction']("gettime"))["time"]);
            } catch (error) {
                this.playtime = 0;
            }
        }
        return this.playtime;
    };

    /**
     * 设置播放启动播放协议
     * @param {storm} storm 播放协议
     * @param {[type]} time 开始播放起点时间
     */
    FlashPlayer.prototype.SetStorm = function(storm, time) {
        this.storm = storm;
        this.starttime = time || 0;
    };

    /**
     *  设置当前播放器是否自动播放
     * @param {boolean} bool 1  可以播放  0 不可以播放
     */
    FlashPlayer.prototype.SetAutoPlay = function(bool) {
        this.autoplay = bool;
    };

    /**
     *  开始播放视频
     */
    FlashPlayer.prototype.playvideo = function() {
        //当播放器没有完全准备好存在调用 等待完成之后执行播放
        if (this.playcode == 1) {
            this.cache = true;
        }
        //第一次初始化播放器
        if (this.playcode == 2) {
            this.playcode = 3;
            this.playelement['jsToAction']("loadplayer", "autoPlay=" + this.autoplay + "&storm=" + this.storm + "&adsPurview=" + this.adscode + "&tltime=" + this.starttime + "&videoTitle=" + this.playinfo['title'] + "&videoCurrent=" + this.playinfo['current'] + "&videoCount=" + this.playinfo['count'] + "&ispay=" + this.playinfo['ispay']);
            return;
        }
        //初始化完毕切换剧集
        if (this.playing) {
            this.playinfo && this.playelement['jsToAction']("titlenumber", this.playinfo);
            this.playelement['jsToAction']("Call14Prop", this.adscode);
            this.playelement['jsToAction']("changevideo", this.storm + "", this.autoplay, 1);
        }
    };

    /**
     * 显示异常提示调用load.swf 里面的方法
     * @param  {string} name 异常提示的swf  f.hd.baofeng.com域名使用error005.swf error404.swf
     * @return null
     */
    FlashPlayer.prototype.showerror = function(name) {
        this.playing = false;
        this.playcode = 0;
        if (this.loadsuccess) {
            this.playelement['jsToAction']("loaderror", 1, name);
        } else {
            this.cachedata = name;
        }
    };

    /**
     * 删除播放器对象
     */
    FlashPlayer.prototype.remove = function() {
        this.playing = false;
        this.element.parentNode.removeChild(this.element);
        this.dispose();
    };

    /**
     * 播放器状态解解析并且派发事件
     * @param  {json} data flash返回的各种状态信息
     */
    FlashPlayer.prototype.statusResolve = function(json) {
        var head, request = true;
        if (json) {
            switch (json["action"]) {
                case "loaderCmp": //loading自身加载完毕
                    this.loadsuccess = true;
                    if (this.playcode == 0 || this.cachedata) {
                        this.showerror(this.cachedata || "error404.swf");
                        this.cachedata = null;
                        return;
                    }
                    this.playcode = 2;
                    //存在没有初始化就调用
                    if (this.cache) {
                        this.playvideo();
                    }
                    break;
                case "startplay": //播放器加密核心加载完毕
                    head = {
                        type: "status",
                        data: {
                            "name": "play",
                            "msg": "开始播放"
                        }
                    }
                    break;
                case "playerLoadCmp": //播放器加密核心加载完毕
                    this.playing = true;
                    head = {
                        type: "status",
                        data: {
                            "name": "loadcmp",
                            "msg": "加载完毕"
                        }
                    }
                    break;
                case "playerLoadError": //播放器加密核心加载失败
                    this.playing = false;
                    head = {
                        type: "status",
                        data: {
                            "name": "loaderr",
                            "msg": "加载失败"
                        }
                    }
                    this.loggerResolve({
                        "key": "loaderror"
                    });
                    this.showerror("error404.swf");
                    break;
                case "clickbuy": //显示购买界面 分为自动与手动
                    head = {
                        type: "status",
                        data: {
                            "name": "showpay",
                            "msg": "显示购买"
                        }
                    }
                    if (json["key"] == "auto") {
                        this.loggerResolve({
                            "key": "clickpay",
                            "value": "1"
                        });
                    } else {
                        this.loggerResolve({
                            "key": "clickpay",
                            "value": "2"
                        });
                    }
                    break;
                case "videoplayend": //当前播放视频结束
                    head = {
                        type: "status",
                        data: {
                            "name": "playend",
                            "msg": "视频结束"
                        }
                    }
                    break;
                case "errordownloadclick": //点击播放异常提示界面下载
                    this.playing = false;
                    head = {
                        type: "status",
                        data: {
                            "name": "download",
                            "msg": "点击下载"
                        }
                    }
                    break;
                case "getCookie": //获取页面cookie
                    for (var key in json["key"]) {
                        json["key"][key] = this.cookie(key);
                    }
                    return $.stringify(json["key"]);
                    break;
                case "getVideo": //获取页面video信息
                    for (var key in json["key"]) {
                        json["key"][key] = this.GetMovieInfo(key);
                    }
                    return $.stringify(json["key"]);
                    break;
                default:
            }
            head && this.dispatchEvent(head);
        }
    };
    /**
     * 播放器日志解解析并且派发事件
     * @param  {json} data flash返回的各种日志信息
     */
    FlashPlayer.prototype.dispatchEvent = function(json) {
        $(this).trigger(json.type, json.data);
    };
    /**
     * 播放器日志解解析并且派发事件
     * @param  {json} data flash返回的各种日志信息
     */
    FlashPlayer.prototype.loggerResolve = function(json) {
        if (json) {
            var logMap = FlashPlayer.CONST.LogMap;
            if (json["key"] in logMap) {
                //flash和js报数所用的字段不同，需要转一下
                json["key"] = logMap[json["key"]];
            }
            $(this).trigger("logger", {
                "name": json["key"],
                "msg": json["value"]
            });
        }
    }

    /**
     * as统一调用js接口函数入口
     * @param  {string} key  调用方法名称
     * @param  {[type]} data  携带对于执行函数参数
     * @return {[type]}      js处理结果返回flash
     */
    FlashPlayer.prototype.onActionTojs = function(key, data) {
        var json = data ? $.parseJSON(data) : "";
        switch (key) {
            case "action": //loading自身加载完毕
                return this.statusResolve(json);
                break;
            case "videoplayend": //当前视频播放完毕
                this.statusResolve({
                    "action": "videoplayend"
                });
                break;
            case "startplay": //当前视频开始播放(广告结束之后)
                this.statusResolve({
                    "action": "startplay"
                });
                break;
            case "logger": //播放器加密核心加载完毕
                this.loggerResolve(json);
                break;
            default:
        }
    }
    FlashPlayer.prototype.getSwf = function(id) {
        var swf, embed, element = document.getElementById(id) || null;
        if (element && element.nodeName.toUpperCase() == 'OBJECT') {
            if (typeof element.SetVariable != 'undefined') {
                swf = element;
            } else {
                embed = element.getElementsByTagName('embed')[0];
                if (embed) {
                    swf = embed;
                }
            }
        }
        return swf;
    };
    FlashPlayer.prototype.AnalyzeTPL = function(str, data) {
        if (data) {
            return str.replace(/\{(.*?)\}/ig, function() {
                if (typeof data[arguments[1]] == "undefined") {
                    return "";
                }
                return data[arguments[1]];
            });
        }
        return str;
    };
    /**
     * 初始化flash播放器
     * @param  {element} element 放入播放器的页面元素对象
     */
    FlashPlayer.prototype.decorateInternal = function(element) {
        var json = {
            "id": this.playid,
            "data": this.playurl,
            "width": this.playwidth,
            "height": this.playheight
        };
        //验证域名是否合法可以播放
        this.playcode = 1;
        window["onActionTojs"] = $.proxy(this.onActionTojs, this);
        element.innerHTML = this.AnalyzeTPL(FlashPlayer.TMPL, json);
        this.playelement = this.getSwf(this.playid);
        element = json = null;
    };

    return FlashPlayer;

});
