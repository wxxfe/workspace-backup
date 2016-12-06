function VideoList() {
	return this;
}

VideoList.prototype = new BaseComp();
VideoList.prototype.constructor = VideoList;
VideoList.fn = VideoList.prototype;

VideoList.fn.init = function(options) {
	this.element = options.element;

	this.swiperWrapper = this.element.querySelector('.swiper-wrapper');
	this.countAll = this.swiperWrapper.querySelectorAll('.swiper-slide').length;

	var slide = this.swiperWrapper.querySelector('.swiper-slide'),
		slideWidth = slide.offsetWidth,
		slideMrStr = $(slide).css('margin-right'),
		slideMarginRight = slideMrStr.substring(0, slideMrStr.length-2);

	this.swiperWrapper.style.width = this.countAll * (slideWidth + slideMarginRight) + 'px';

	this._registerEvents();
	return this;
}

VideoList.fn._registerEvents = function() {
	var _self = this, slides = this.element.querySelectorAll('.swiper-slide');
	for(var i = 0, len = slides.length;i < len;i++) {
		 $(slides[i]).on('touchstart touchmove touchend', function(event) {
		    switch(event.type) {
		        case 'touchstart':
		            falg = false;
		            break;
		        case 'touchmove':
		            falg = true;
		             break;
		        case 'touchend':
		            if( !falg ) {
						var videoInfoStr = this.getAttribute('data-video');
						var videoInfo = JSON.parse(videoInfoStr);
						_self.trigger('videolist.videoSelect', videoInfo);
						// 更新播放与否的展示样式
						var played = _self.element.querySelector('.slide-video-wrapper.playing');
						played.classList.remove('playing');
						played.querySelector('.tag').innerHTML = played.parentNode.getAttribute('data-chapter');
						
						this.querySelector('.slide-video-wrapper').classList.add('playing');
						this.querySelector('.tag').innerHTML = '播放中'
		            } else {
//		                console.log('滑动');
		            }
		            break;
		    }
		});
		
	}
}

function VideoListFactory(options) {
	return new VideoList().init(options);
}