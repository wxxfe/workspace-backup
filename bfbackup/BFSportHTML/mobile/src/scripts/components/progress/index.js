function Progress() {
	return this;
}

Progress.prototype = new BaseComp();
Progress.prototype.constructor = Progress;
Progress.fn = Progress.prototype;

Progress.EVENTS = {
	PROCESS_STATE_CHANGE: 'processStateChange',
	PROCESS_STATE_CHANGE_END: 'processStateChangeEnd',
};

Progress.fn.parentInit = Progress.fn.init;
Progress.fn.init = function(options) {
	Progress.fn.parentInit.call(this, options);
	this.options = options;
	this.element = options.element;
	this.onProcessStateChange = options.onProcessStateChange;
	this.onProcessStateChangeEnd = options.onProcessStateChangeEnd;
	this.slidebarInitialRate = typeof options.slidebarInitialRate !== 'undefined' ? options.slidebarInitialRate : 0;
	this.timeShowFormat = options.timeShowFormat || '00:00';
	
	this.totalbar = this.element.querySelector('.progress-bar.total');
	this.bufferedbar = this.element.querySelector('.progress-bar.buffered');
	this.processedbar = this.element.querySelector('.progress-bar.processed');
	this.slidebar = this.element.querySelector('.processed-slide-bar');
	this.totalTimeNode = this.element.querySelector('.progress-totaltime span');
	this.playedTimeNode = this.element.querySelector('.progress-playedtime span');
	
	this.lastTouchPos;
	this.isSlideMoving = false;
	this.currentProcessedTimeMilliseconds = 0;
	
	this._registerEvents();
	
	this.setTotalTime(options.totalTime);
	this.setProcessedProcess(this.slidebarInitialRate);
	
	return this;
}

Progress.fn._registerEvents = function() {
	var _self = this;
	this.slidebar.addEventListener('touchstart', this._sildebarTouchStartHandler.bind(this));
	this.slidebar.addEventListener('touchmove', throttle(this._sildebarTouchMoveHandler.bind(this), 20));
	this.slidebar.addEventListener('touchend', this._sildebarTouchEndHandler.bind(this));
	this.totalbar.parentNode.addEventListener('click', this._processBarClickHandler.bind(this));
}

Progress.fn._sildebarTouchStartHandler = function(event) {
	var touch = event.targetTouches[0];     //touches数组对象获得屏幕上所有的touch，取第一个touch
	this.lastTouchPos = {x: touch.clientX, y: touch.clientY};    //取第一个touch的坐标值
}

Progress.fn._sildebarTouchMoveHandler = function(event) {
	//当屏幕有多个touch或者页面被缩放过，就不执行move操作
	if (event.targetTouches.length > 1 || event.scale && event.scale !== 1) return;
	var touch = event.targetTouches[0],
		rate,
		range = this._getSldieMoveRange(touch);
	
	if(range == 'rightExceed') {
		rate = 1;
	} else if(range == 'leftExceed') {
		rate = 0;
	} else {
		var distance = {x: touch.clientX - this.lastTouchPos.x, y: touch.clientY - this.lastTouchPos.y};     //获取所移动的距离
	    this.lastTouchPos = {x: touch.clientX, y: touch.clientY};
	    // distance.x小于0时左移， 大于0时右移
		rate = (this.processedbar.offsetWidth + distance.x) / this.totalbar.offsetWidth;
	}
	
    this._processStateChangeHandler(rate);
    this.isSlideMoving = true;
}

Progress.fn._sildebarTouchEndHandler = function(event) {
	this.isSlideMoving = false;
	this.onProcessStateChangeEnd && this.onProcessStateChangeEnd.call(null, this.currentProcessedTimeMilliseconds);
	this.trigger(Progress.EVENTS.PROCESS_STATE_CHANGE_END, this.currentProcessedTimeMilliseconds);
}

// 判断是否超过拖动的范围
Progress.fn._getSldieMoveRange = function(touch) {
	var totalRect = this.totalbar.getBoundingClientRect(),
		rangeLeft = totalRect.left,
		rangeRight = totalRect.left + totalRect.width;
	if(touch.clientX > rangeRight) return 'rightExceed';
	if(touch.clientX < rangeLeft) return 'leftExceed';
	return 'normal';
}

Progress.fn._processBarClickHandler = function(event) {
	var touch = {clientX: event.clientX, clientY: event.clientY},
		rate,
		range = this._getSldieMoveRange(touch);
	if(range == 'rightExceed') {
		rate = 1;
	} else if(range == 'leftExceed') {
		rate = 0;
	} else {
		var	rect = this.processedbar.getBoundingClientRect(),
			processedX = rect.left + rect.width;
			distance = touch.clientX - processedX;
		// distance.x小于0时左移， 大于0时右移
		rate = (this.processedbar.offsetWidth + distance) / this.totalbar.offsetWidth;
	}
	this._processStateChangeHandler(rate);
	this.onProcessStateChangeEnd && this.onProcessStateChangeEnd.call(null, this.currentProcessedTimeMilliseconds);
	this.trigger(Progress.EVENTS.PROCESS_STATE_CHANGE_END, this.currentProcessedTimeMilliseconds);
	event.stopPropagation();
}

/**
 * @param {Object} rate 小数,如0.32,0-1之间
 */
Progress.fn._processStateChangeHandler = function(rate) {
	this.processedbar.style.width = rate*100 + '%';
	this.currentProcessedTimeMilliseconds = this.totalTimeMilliSeconds*rate;
	this.playedTimeNode.innerText = millisecondsToTimespan(this.currentProcessedTimeMilliseconds, this.timeShowFormat.split(':').length === 3);
	this.onProcessStateChange && this.onProcessStateChange.call(null, this.currentProcessedTimeMilliseconds);
}

Progress.fn._setInitialState = function() {
	this.processedbar.style.width = this.slidebarInitialRate;
}

Progress.fn.setProcessedProcess = function(currentTime) {
	if(this.isSlideMoving) return;
	var rate;
	if((''+currentTime).indexOf('%') > -1) {
		rate = currentTime;
	} else {
		rate = (currentTime/this.totalTimeMilliSeconds)*100 + '%';
	}
	this.processedbar.style.width = rate;
	this.setPlayedTime(currentTime);
}

/**
 * 设置缓冲条的缓冲区域
 * @param {Object} bufferArr 两种格式： [{start: 0, end: '10%'}, {start: '30%', end: '50%'}], [{start: 0, end: 30000}, {start: 50000, end: 90000, {start: '80%', end: '90%'}]
 */
Progress.fn.setBufferedProcess = function(bufferArr) {
	this.bufferedbar.innerHTML = '';
	
	for(var i=0,len=bufferArr.length;i < len;i++) {
		this.bufferedbar.insertAdjacentHTML('beforeEnd', '<div id="buffered-section-' + i + '" class="progress-bar buffered-section"></div>');
		var bufferedSection = this.bufferedbar.querySelector('#buffered-section-' + i);
		
		if((''+bufferArr[i].start).indexOf('%') > -1) {
			bufferArr[i].start = percentToDecimal(bufferArr[i].start);
			bufferArr[i].end = percentToDecimal(bufferArr[i].end);
		} else {
			bufferArr[i].start = millisecondsToDecimal(bufferArr[i].start, this.totalTimeMilliSeconds);
			bufferArr[i].end = millisecondsToDecimal(bufferArr[i].end, this.totalTimeMilliSeconds);
		}
		
		bufferedSection.style.left = bufferArr[i].start*100 + '%';
		bufferedSection.style.width = (bufferArr[i].end - bufferArr[i].start)*100 + '%';
	}
}

Progress.fn.setPlayedTime = function(time) {
	if((time+'').indexOf('%') > -1) {
		time = this.totalTimeMilliSeconds*percentToDecimal(time);
	}
	if((time+'').indexOf(':') == -1) {
		time = millisecondsToTimespan(time);
	}
	this.playedTimeNode.innerText = time;
}

Progress.fn.setTotalTime = function(time) {
	time = time || this.timeShowFormat;
	if((time+'').indexOf(':') == -1) {
		time = millisecondsToTimespan(time);
	}
	this.totalTime = time || this.timeShowFormat;
	this.totalTimeMilliSeconds = timespanToMilliseconds(this.totalTime);
	
	this.totalTimeNode.innerText = this.totalTime;
}

Progress.fn.getTotalTime = function(time) {
	return this.totalTimeMilliSeconds;
}


function ProgressFactory(options) {
	return new Progress().init(options);
}