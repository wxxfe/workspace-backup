function VideoPlayerShare() {
	return this;
}

VideoPlayerShare.prototype = new VideoPlayer();
VideoPlayerShare.prototype.constructor = VideoPlayerShare;
VideoPlayerShare.fn = VideoPlayerShare.prototype;

VideoPlayerShare.fn.parentInit = VideoPlayerShare.fn.init;
VideoPlayerShare.fn.init = function(options) {
	
	var _self = this;
	var afterGetUrlCb = function(videoSrc) {
		this.element.classList.add('video-player');
		this._constructOther();
		if(Utils.browserInfo.isMobileDevice) {
			options.videoSrc = videoSrc;
			options.autoConstruct = true;
			VideoPlayerShare.fn.parentInit.call(this, options);
			this.element.querySelector('a').addEventListener('click', function() {
				this.style.zIndex = -9;
				
				_self.invoke('play');
				_self.Parts.controlPanelComp.hide();
			});
		} else {
			_self.element.style.height = _self.element.offsetWidth*9/16 + 'px'; // 给云视频的容器宽高比大于16/9，chrome下云视频不会自动播放
			_self.element.insertAdjacentHTML('beforeEnd', '<div class="loading-mask">努力加载中...</div>');
			this.element.querySelector('a').addEventListener('click', function() {
				this.style.zIndex = -9;
				if(typeof(window.sBIAOSHSHIFOUYINRU)== 'undefined' ) {
					Utils.loadJs("http://www.baofengcloud.com/html/src/cloudsdk.js", function() {
						window.sBIAOSHSHIFOUYINRU='isexist';
						window.videoareaname = 'videoarea_'+Utils.randomString(5);

						_self.element.insertAdjacentHTML('beforeEnd', '<div id="'+videoareaname+'"></div>');

						var vodparam = videoSrc;
						cloudsdk.initplay(window.videoareaname,{"src":vodparam,"id":"cloudsdk","isautosize":"1"});
					});
				}
				
			});
		}

		options.afterInit && options.afterInit.call(this, options);
	};
	this.element = options.element;
	this.isLive = options.isLive;
	this.createDownButton = typeof options.createDownButton === 'undefined' ? false : options.createDownButton;
	
	var infoNode = this.element.querySelector('[data-video]');
	if(infoNode) {
		var videoInfoStr = infoNode.getAttribute('data-video');
		var videoInfo = JSON.parse(videoInfoStr);
		VideoSrcGetter.getSrc(videoInfo, this.isLive).then(afterGetUrlCb.bind(this));
	} else {
		infoNode = this.element.querySelector('a[data-vid],a[title]');
		var videoId = infoNode.getAttribute('data-vid') || infoNode.getAttribute('title');
		VideoSrcGetter.getByVideoId(videoId).then(afterGetUrlCb.bind(this));
	}
	
	
//	var infoNode = this.element.querySelector('a[data-vid],a[title]');
//	if(infoNode) {
//		var videoId = infoNode.getAttribute('data-vid') || infoNode.getAttribute('title');
//		VideoSrcGetter.getByVideoId(videoId).then(afterGetUrlCb.bind(this));
//	} else {
//		infoNode = this.element.querySelector('a[data-video]');
//		var videoInfoStr = infoNode.getAttribute('data-video');
//		var videoInfo = JSON.parse(videoInfoStr);
//		VideoSrcGetter.getByVideoCidAndSize(videoInfo.cid, videoInfo.size).then(afterGetUrlCb.bind(this));
//	}
	
//	var infoNode = this.element.querySelector('a[data-video]');
//	var cloudurl = "servicetype=2&uid=48842737&fid=3EDECD6B2A405805B5144F25342586C7672FDB24&auto=1&si=1&vr=0";
//	if(cloudurl) {
//		this._setVideoUrlFromBFCloud(cloudurl, afterGetUrlCb);
//	} else
//	if(infoNode) {
//		var videoInfoStr = infoNode.getAttribute('data-video');
//		var videoInfo = JSON.parse(videoInfoStr);
//		this._setVideoUrl(videoInfo.cid, videoInfo.size, afterGetUrlCb);
//	} else {
//		infoNode = this.element.querySelector('a[data-vid],a[title]');
//		var videoId = infoNode.getAttribute('a[data-vid]') || infoNode.getAttribute('title');
//		$.ajax({
//			type:"get",
//			url:"http://m.sports.baofeng.com/api/getVideoInfo/" + videoId,
//			async:true,
//			dataType: 'json',
//			success: function(data){
//				if(data.status === 1) {
//					_self._setVideoUrl(data.data.box_cid, data.data.file_size, afterGetUrlCb);
//				}
//			},
//			error: function(xhr, type){
//				alert('Ajax error!')
//			}
//		});
//	}
	
	
	return this;
}

VideoPlayerShare.fn._constructOther = function() {
	if(this.createDownButton) {
		if(this.element.nextElementSibling && this.element.nextElementSibling.classList.contains('video-title')) {
			this.element.nextElementSibling.insertAdjacentHTML('afterEnd', '<a class="button button-block" href="http://a.app.qq.com/o/simple.jsp?pkgname=com.sports.baofeng" target="_blank">下载暴风体育，更流畅、更高清<i class="fa fa-angle-right fa-lg icon"></i></a>');
		} else {
			this.element.insertAdjacentHTML('afterEnd', '<a class="button button-block" href="http://a.app.qq.com/o/simple.jsp?pkgname=com.sports.baofeng" target="_blank">下载暴风体育，更流畅、更高清<i class="fa fa-angle-right fa-lg icon"></i></a>');
		}
	}
}

function VideoPlayerShareFactory(options) {
	return new VideoPlayerShare().init(options);
}

