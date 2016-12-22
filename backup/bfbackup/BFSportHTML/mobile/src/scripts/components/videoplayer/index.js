function VideoPlayer() {
	return this;
}

VideoPlayer.prototype = new BaseComp();
VideoPlayer.prototype.constructor = VideoPlayer;
VideoPlayer.fn = VideoPlayer.prototype;

VideoPlayer.fn.parentInit = VideoPlayer.fn.init;
VideoPlayer.fn.init = function(options) {
	VideoPlayer.fn.parentInit.call(this, options);
	this.options = options;
	this.element = options.element;
	this.isLive = typeof options.isLive === 'undefined' ? false : options.isLive;
	this.controlPanelShowDuration = typeof options.controlPanelShowDuration === 'undefined' ? 6000 : options.controlPanelShowDuration;
	this.autoPlay = typeof options.autoPlay === 'undefined' ? false : options.autoPlay;
	this.videoSrc = typeof options.videoSrc === 'undefined' ? (typeof this.element.getAttribute('data-videoSrc') === 'undefined' ? '' : this.element.getAttribute('data-videoSrc')) : options.videoSrc;
	this.autoConstruct = typeof options.autoConstruct == 'undefined' ? false : options.autoConstruct; // 自动创建dom
	
	if(this.autoConstruct) {
		this._constructDom();
	}
	
	this.video = this.element.querySelector('.video');
	this.controlPanel = this.element.querySelector('.video-control-panel');
	this.loadingMask = this.element.querySelector('.loading-mask');
	
	this.Parts = {};
	this._initParts();
	
	this._registerEvents();
	
	this._initInitialState();
	return this;
}

VideoPlayer.fn._constructDom = function() {
	this._constructVideo();
	this._constructControlPanel();
}

VideoPlayer.fn._constructVideo = function() {
	this.element.insertAdjacentHTML('beforeEnd', '<video class="video" width="100%" height="100%" src="' + this.videoSrc + '" webkit-playsinline playsinline="true" preload="metadata"></video>');
}

VideoPlayer.fn._constructControlPanel = function() {
	var htmlArr = ['<div class="video-control-panel">',
		    '	<div class="bigplay pause js-video-control-play"></div>' , 
			'	<div class="video-control-bar">' ,
			'		<button class="fullscreen-btn"></button>' , 
			'		<div class="progress">',
			'			<div class="progress-playedtime">',
			'				<span >00:00</span>',
			'			</div>',
			'			<div class="progress-totaltime">',
			'				<span>00:00</span>',
			'			</div>',
			'			<div class="progress-bar-wrapper">',
			'				<div class="progress-bar total"></div>',
			'				<div class="progress-bar buffered"></div>',
			'				<div class="progress-bar processed">',
			'					<div class="processed-slide-bar">',
			'					</div>',
			'				</div>',
			'			</div>',
			'		</div>',
			'	</div>' , 
			'</div>',
			'<div class="loading-mask">努力加载中...</div>'];
//	if(window.makeVideoPlayableInline) {
//		// 直播没测 暂时修改
//		htmlArr.push('<div class="loading-mask">视频努力加载中...</div>');
//	}
	
	this.element.insertAdjacentHTML('beforeEnd', htmlArr.join(""));
}

VideoPlayer.fn._initParts = function() {
	var _self = this,
		videoComp,
		controlPanelComp;
	if(this.video) {
		videoComp = VideoFactory({
			element: this.video,
			Top: this
		});
		this.Parts.videoComp = videoComp;
		videoComp.on('click', function() {
			controlPanelComp.show();
//			clearTimeout(_self.timeout);
			_self.timeout = setTimeout(function() {
				controlPanelComp.hide();
			}, _self.controlPanelShowDuration)
			
		});
		videoComp.on('canplay', function() {
            if(!Utils.browserInfo.isAndroid) {
				_self.loadingMask.classList.add('hide');
			}
		});
		videoComp.on('play', function() {
			controlPanelComp.setButtonPause();
		});
		videoComp.on('pause', function() {
			controlPanelComp.setButtonPlay();
		});
		
		videoComp.on('loadedmetadata', function() {
			!_self.isLive && controlPanelComp.invoke('setTotalTime', videoComp.getDuration());
		});
		videoComp.on('timeupdate', function() {
			var totalTime = controlPanelComp.Parts.progressComp.getTotalTime();
			if(Utils.browserInfo.isAndroid && totalTime === 0 && videoComp.getDuration() !== 0) {
				// 解决android 下timeupdate后才会duration才会有值的问题
				controlPanelComp.invoke('setTotalTime', videoComp.getDuration());
			}
			if(Utils.browserInfo.isAndroid && videoComp.getCurrentTime() > 0) {
				// 有的安卓设备播放前就会调timeupdate
				!_self.loadingMask.classList.contains('hide') && _self.loadingMask.classList.add('hide');
			}
			if(_self.isLive && Utils.browserInfo.isAndroid) {
				!_self.loadingMask.classList.contains('hide') && _self.loadingMask.classList.add('hide');
			}
			var currentTime = videoComp.getCurrentTime();
			controlPanelComp.invoke('setProcessedProcess', currentTime);
		});
		videoComp.on('progress', function() {
		    var bufferedArr = [];
		    var bf = videoComp.getBuffered();
		    var time = videoComp.getCurrentTime();
			
			for(var i = 0, len = bf.length;i < len;i++) {
				bufferedArr.push({start: bf.start(i)*1000, end: bf.end(i)*1000});
			}
			controlPanelComp.invoke('setBufferedProcess', bufferedArr);
		});
		videoComp.on('ended', function() {
			controlPanelComp.setButtonPlay();
			controlPanelComp.show();
		});
	};
	if(this.controlPanel) {
		var state = this.autoPlay ? 'readyToPause' : 'readyToPlay';
		controlPanelComp = VideoControlPanelFactory({
			element: this.controlPanel,
			initialState: state,
			Top: this
		});
		this.isLive && controlPanelComp.Parts.progressComp.setProcessedProcess('100%');
		this.Parts.controlPanelComp = controlPanelComp;
		controlPanelComp.on(VideoControlPanel.EVENTS.CLICK, function() {
			this.hide();
		});
		controlPanelComp.on(VideoControlPanel.EVENTS.PLAY, function() {
			if(!videoComp.getHasStarted()) {
				_self.showLoading();
			}
			videoComp.play();
			controlPanelComp.hide();
			
		});
		controlPanelComp.on(VideoControlPanel.EVENTS.PAUSE, function() {
			videoComp.pause();
		});
		controlPanelComp.on(VideoControlPanel.EVENTS.ENTERFULLSCREEN, function() {
			if(Utils.isInApp && Utils.browserInfo.isIos) {
				// app内，ios 先走原生
				videoComp.enterFullScreen();
				return;
			}
			if(_self.isFullScreen()) {
				_self.exitFullScreen();
			} else {
				_self.enterFullScreen();
			}
			
		});
		controlPanelComp.on(Progress.EVENTS.PROCESS_STATE_CHANGE_END, function(milliseconds) {
			videoComp.setCurrentTime(milliseconds);
		});
	}
}

VideoPlayer.fn._registerEvents = function() {
	var _self = this;
}

VideoPlayer.fn._initInitialState = function() {
	if(this.videoSrc) {
		this.invoke('setVideoSrc', this.videoSrc);
	}
	if(this.autoPlay) {
		this.showLoading();
	} else {
		this.hideLoading();
	}
}

VideoPlayer.fn.isFullScreen = function() {
	return this.hasClass('is-fullscreen');
}

VideoPlayer.fn.enterFullScreen = function() {
    this.addClass('is-fullscreen');
	DeviceApi.videoEnterFullScreen();
}

VideoPlayer.fn.exitFullScreen = function() {
	this.removeClass('is-fullscreen');
	DeviceApi.videoExitFullScreen();
}

VideoPlayer.fn.showLoading = function() {
//	this.loadingMask.classList.remove('hide');
}

VideoPlayer.fn.hideLoading = function() {
//	this.loadingMask.classList.add('hide');
}

function VideoPlayerFactory(options) {
	return new VideoPlayer().init(options);
}

