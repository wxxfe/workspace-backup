function VideoControlPanel() {
	return this;
}

VideoControlPanel.prototype = new BaseComp();
VideoControlPanel.prototype.constructor = VideoControlPanel;
VideoControlPanel.fn = VideoControlPanel.prototype;

VideoControlPanel.EVENTS = {
	PLAY: 'play',
	PAUSE: 'pause',
	ENTERFULLSCREEN: 'enterfullscreen',
	CLICK: 'click'
};

VideoControlPanel.fn.parentInit = VideoControlPanel.fn.init;
VideoControlPanel.fn.init = function(options) {
	VideoControlPanel.fn.parentInit.call(this, options);
	this.options = options;
	this.element = options.element;
	this.initialState = options.initialState || 'readyToPlay';
	
	this.playBtns = this.element.querySelectorAll('.js-video-control-play');
	this.fullScreenBtn = this.element.querySelector('.fullscreen-btn');
	
	this.Parts = {};
	this._initParts();
	this._registerEvents();
	
	this._initInitialState();
	return this;
}

VideoControlPanel.fn._initParts = function(options) {
	var _self = this,
		progress = this.element.querySelector('.progress'),
		progressComp;
	if(progress) {
		progressComp = ProgressFactory({
			element: progress,
			top: this
		});
		this.Parts.progressComp = progressComp;
	}
}

VideoControlPanel.fn._registerEvents = function() {
	var _self = this;
	for(var i = 0, len = this.playBtns.length; i < len; i++) {
		this.playBtns[i].addEventListener('click', function(e) {
			if(this.classList.contains('pause')) {
				this.classList.remove('pause');
				_self.trigger(VideoControlPanel.EVENTS.PAUSE);
			} else {
				this.classList.add('pause');
				_self.trigger(VideoControlPanel.EVENTS.PLAY);
			}
			e.stopPropagation();
		});
	};
	this.fullScreenBtn.addEventListener('click', function(e) {
		_self.trigger(VideoControlPanel.EVENTS.ENTERFULLSCREEN);
		e.stopPropagation();
	});
	this.element.addEventListener('click', function() {
		_self.trigger(VideoControlPanel.EVENTS.CLICK);
	});
}

VideoControlPanel.fn._initInitialState = function() {
	if(this.initialState === 'readyToPlay') {
		this.setButtonPlay();
	} else if(this.initialState === 'readyToPause') {
		this.setButtonPause();
	}
}

VideoControlPanel.fn.setButtonPlay = function() {
	for(var i = 0, len = this.playBtns.length; i < len; i++) {
		this.playBtns[i].classList.remove('pause');
	}
}

VideoControlPanel.fn.setButtonPause = function() {
	for(var i = 0, len = this.playBtns.length; i < len; i++) {
		this.playBtns[i].classList.add('pause');
	}
}

function VideoControlPanelFactory(options) {
	return new VideoControlPanel().init(options);
}

