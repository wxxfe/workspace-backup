define(function() {

    function Player(options){

        this.opts = {
            //容器
            wrap : $(document.body),
            //播放器
            player : null,
            //播放列表
            playList : [],
            //是否显示弹幕
            danmaku : false,
            //播放器尺寸
            width : 640,
            height : 360,
            //播放地址
            playUrl : '',
            //自动播放
            auto : 1,
            //版本
            vr : 0,
            //开始播放回调
            playStart : function(){},
            //播放结束回调
            playEnd : function(){},
            //暂停播入回调
            playPause : function(){},
            //Seek回调
            playSeek : function(){}

        };


        this.init(options);
    }

    Player.prototype = {

        init : function(options){

            this.debug(false);
            $.extend(this.opts, options || {});
            this.insertPlayer();
			window.cloudsdk = {};
            window.cloudsdk.onActionTojs = this.playStatus;

        },

        debug : function(d){
            if(!d){
                window.console = {
                    log : function(){}
                };
            }
        },

        getSize : function(num){
            if(typeof num === 'string'){
                return num.replace(/[px|\%]/g,'');
            }else{
                return num;
            }
        },

        /**
         * 插入播放器HTML
         */
        insertPlayer : function(){
            var playerHTML = '<object ';
            playerHTML += '	classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ';
            playerHTML += '	id="sportsPlayer" ';
            playerHTML += '	width="'+ this.getSize(this.opts.width) +'" ';
            playerHTML += '	height="'+ this.getSize(this.opts.height) +'" style="width: '+ this.opts.width +';height: '+ this.opts.height +';">';
            playerHTML += '	<param name="wmode" value="direct">';
            playerHTML += '	<param name="quality" value="high">';
            playerHTML += '	<param name="allowScriptAccess" value="always">';
            playerHTML += '	<param name="allowFullScreen" value="true">';
            playerHTML += '	<param name="movie" value="src/player/cloud.swf">';
            playerHTML += '	<param name="flashvars" value="iid='+ encodeURIComponent(this.opts.playUrl) +'&width='+ this.opts.width +'&height='+ this.opts.height +'&auto='+ this.opts.auto +'&vr='+ this.opts.vr +'">';
            playerHTML += '	<embed ';
            playerHTML += '		width="'+ this.getSize(this.opts.width) +'" ';
            playerHTML += '		height="'+ this.getSize(this.opts.height) +'" ';
            playerHTML += '		style="width: '+ this.opts.width +';height: '+ this.opts.height +';"';
            playerHTML += '		allowScriptAccess="always" ';
            playerHTML += '		wmode="direct" ';
            playerHTML += '		quality="high"';
            playerHTML += '		allowFullScreen= "true"';
            playerHTML += '		flashvars="iid='+ encodeURIComponent(this.opts.playUrl) +'&width='+ this.opts.width +'&height='+ this.opts.height +'&auto='+ this.opts.auto +'&vr='+ this.opts.vr +'"';
            playerHTML += '		src="src/player/cloud.swf" ';
            playerHTML += '		name="sportsPlayer" ';
            playerHTML += '		type="application/x-shockwave-flash" ';
            playerHTML += '		allowFullScreen="true">';
            playerHTML += '</object>';
            this.opts.wrap.html(playerHTML);
            var cloudPlay = this.getSwf();
            this.opts.player = this.getSwf();
        },

        /**
         * 获取播放器
         * @return Object
         */
        getSwf : function(){
            var player = document.getElementById('sportsPlayer');
            return (navigator.appName.indexOf('Microsoft') != -1 ? player : player.getElementsByTagName('embed')[0]);
        },

        /**
         * 播放一个视频
         * @param Object playInfo
         * @example {qstp:"qstp://qkvp/pGp/bmxleqg/qey:XDXZ.s?jp=GGJ&jd=XADBAQJG0E0AZAQ11JWGDFZCTMAXFGTDAWWWJD0E&vd=DQGFTQJJDMWZGGWZEAJ1XBWAAATXEDTMTBETQMXW&lh=X1JQBZM&fz=DQQZ0E&pi=DT&pr=WM0XX&pps=A&ppk=A&pyp=X1JQBWE&blzd=JXA1&vk=A&pah=MCJMGEBWEJQFAGADD0QW0Z0GJFT1TAWZ&ped=pov", autoPlay:"1", vr:"0", ptime:"0",name:"测试切换"}
         *  - qstp 播放地址
         *  - autoPlay 自动播放
         *  - vr 版本号
         *  - ptimg 开始播放时间
         */
        playVideo : function(playInfo){
            var info = $.extend({qstp:'', autoPlay:'1', vr:'0', ptime:'0',name:''},playInfo || {});
            this.opts.player['jsToAction']('changevideo',info.qstp,info.autoPlay,info.vr,info.ptime);
        },

        /**
         * seek到time
         * @param int time 时间(单位为秒)
         */
        seek : function(time){
            this.opts.player['jsToAction']('seek',time);
            this.playSeek();
        },

        /**
         * 播放
         */
        play : function(){
            this.opts.player['jsToAction']('play');
        },

        /**
         * 暂停
         */
        pause : function(){
            this.opts.player['jsToAction']('pause');
        },

        /**
         * 重新播放
         */
        replay : function(){
            this.opts.player['jsToAction']('resume');
        },

        /**
         * 获取音量
         */
        getVolume : function(){
            return this.opts.player['jsToAction']('getVolume');
        },

        /**
         * 设置音量
         * @param Number 音量值如：0.2
         */
        setVolume : function(v){
            this.opts.player['jsToAction']('changevolume',v || 0.5);
        },

        /**
         * 获取下载进度
         */
        getBufferDownload : function(){
            return this.opts.player['jsToAction']('getBufferDownload');
        },

        playStatus : function(){
            var type = arguments[0];
            switch(type){
                case 'cloudstatus':
                    console.log(type + '--' + arguments[1] + ':' + arguments[2]);
                    break;
				case 'displayChange':
					console.log(type + ' -- ' + arguments[1]) ;
					break;
				case 'playend':
					console.log(type);
					break;
				case 'send_barrage_flash':
					console.log(arguments[1]);
					break;
				case 'send_barrage_resault':
					console.log(arguments[1]);
					break;
				case 'receive_barrage_flash':
					console.log(arguments[1]);
					break;
				case 'show_barrage_flash':
					console.log(arguments[1]);
					break;
            }
        }

    }

	return Player;

});
