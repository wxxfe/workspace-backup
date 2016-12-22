function VideoPlayerApp() {
	return this;
}

VideoPlayerApp.prototype = new VideoPlayerShare();
VideoPlayerApp.prototype.constructor = VideoPlayerApp;
VideoPlayerApp.fn = VideoPlayerApp.prototype;

VideoPlayerApp.fn.parentInit = VideoPlayerApp.fn.init;
VideoPlayerApp.fn.init = function(options) {
	options.afterInit = function() {
		var _self = this;
		this.element = options.element;
		
		// 外面包上一层
		var elementContainer = document.createElement('div');
		elementContainer.classList.add('video-wrapper');
		this.element.parentNode.replaceChild(elementContainer, this.element);
		elementContainer.appendChild(this.element);
		this.elementContainer = elementContainer;
		
		var videoComp = this.Parts.videoComp,
			controlPanelComp = this.Parts.controlPanelComp;
		window.addEventListener('scroll', function() {
			if(!videoComp || videoComp.isPaused()) return;
			if(_self.isFullScreen()) return;
			var rect = _self.elementContainer.getBoundingClientRect();
			if(rect.top < 0 && rect.height + rect.top < 0) {
				_self.element.classList.add("video-minimized");
			} else {
				_self.element.classList.remove("video-minimized");
			}
		})
		
		this.element.insertAdjacentHTML('beforeEnd', '<div class="mini-close-btn"></div>');
		this.miniClose = _self.element.querySelector('.mini-close-btn');
		this.miniClose.addEventListener('click', function() {
			videoComp.pause();
			controlPanelComp.setButtonPause();
			_self.element.classList.remove('video-minimized');
		})
		
		videoComp.on('ended', function() {
			_self.element.classList.remove('video-minimized');
		})
		
		// video 事件
		videoComp.on('play', function() {
			window.webplay && window.webplay.videoPlay && window.webplay.videoPlay();
		})
		videoComp.on('pause', function() {
			window.webplay && window.webplay.videoPause && window.webplay.videoPause();
		})
		videoComp.on('ended', function() {
			window.webplay && window.webplay.videoEnd && window.webplay.videoEnd();
		})
		
		window.deviceCb = window.deviceCb || {};
		window.deviceCb.videoPlay = function() {
			videoComp.resume();
		}
		window.deviceCb.videoPause = function() {
			videoComp.recordCurrent();
			!videoComp.isPaused() && videoComp.pause();
		}
		window.deviceCb.videoDestroy = function() {
		}
		window.deviceCb.videoExitFullScreen = function() {
			// 全屏时客户端退出全屏
			_self.exitFullScreen();
		}
	}
	VideoPlayerApp.fn.parentInit.call(this, options);
	
	
	return this;
}


function VideoPlayerAppFactory(options) {
	return new VideoPlayerApp().init(options);
}

