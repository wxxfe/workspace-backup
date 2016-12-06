function Video() {
	return this;
}

Video.prototype = new BaseComp();
Video.prototype.constructor = Video;
Video.fn = Video.prototype;

Video.fn.parentInit = Video.fn.init;

Video.State = {
	UNSTARTED: 'unstarted',
	PLAYING: 'playing',
	PAUSED: 'paused',
	ENDED: 'ended'
}

Video.fn.init = function(options) {
	Video.fn.parentInit.call(this, options);
	this.options = options;
	this.element = options.element;
	
	this.hasStarted = false; // 是否启动过
	
	this.Parts = {};
	this._initParts();
	
	window.makeVideoPlayableInline && window.makeVideoPlayableInline(this.element, !this.element.hasAttribute('muted'), true);

	this._registerEvents();
	return this;
}

Video.fn._initParts = function(options) {
}

Video.fn._registerEvents = function() {
	var _self = this;
	var events = ['canplay', 'loadedmetadata', 'timeupdate', 'waiting', 'progress', 'play', 'pause', 'ended', 'click'];
	for(var i = 0, len = events.length;i < len;i++) {
		this.element.addEventListener(events[i], (function(index) {
			return function() {
				_self.trigger(events[index]);
			}
		})(i));
	}
	this.element.addEventListener('play', function() {
		_self.hasStarted = true;
	});
}

Video.fn.play = function() {
	this.element.play();
}

Video.fn.pause = function() {
	this.element.pause();
}

Video.fn.isPaused = function() {
	return this.element.paused;
}

Video.fn.getDuration = function() {
	return this.element.duration*1000;
}

Video.fn.getCurrentTime = function() {
	return this.element.currentTime*1000;
}

Video.fn.setCurrentTime = function(time) {
	return this.element.currentTime = time/1000;
}

Video.fn.getBuffered = function() {
	return this.element.buffered;
}

Video.fn.enterFullScreen = function() {
	return this.element.webkitEnterFullScreen();
}

Video.fn.exitFullScreen = function() {
	return this.element.webkitExitFullScreen();
}


// 请用getState
Video.fn.getHasStarted = function() {
	return this.hasStarted;
}

Video.fn.setSrc = function(url) {
	return this.element.setAttribute('src', url);
}

Video.fn.getState = function() {
	var state;
	if (this.getCurrentTime() < 1) {
		// 未开始
        state = Video.State.UNSTARTED;
   } else if(this.element.ended) {
   		state = Video.State.ENDED;
   } else {
   		state = this.isPaused() ? Video.State.PAUSED : Video.State.PLAYING;
   }
   return state;
}

// 记录video当前的状态，包括播放状态和播放时间，以后可以再扩展
Video.fn.recordCurrent = function() {
	this._recordObj = {
		state: this.getState(),
		currentTime: this.getCurrentTime()
	}
}

// 恢复video的上一次记录的状态，目前只处理了播放状态
Video.fn.resume = function() {
	if(!this._recordObj) return ;
	if(this._recordObj.state === Video.State.UNSTARTED) {
		
	}  else if(this._recordObj.state === Video.State.ENDED) {
		
	} else if(this._recordObj.state === Video.State.PLAYING) {
		this.play();
	} else if(this._recordObj.state === Video.State.PAUSED) {
		this.pause();
	} 
	
}





function VideoFactory(options) {
	return new Video().init(options);
}

