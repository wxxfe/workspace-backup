var typeNode = document.querySelector('#page-type');
window.pageType = typeNode && typeNode.value;
window.isInApp = window.pageType === 'app';
//window.isIOS10 = /OS 10_\d[_\d]* like Mac OS X/i.test(navigator.userAgent);
window.isIOS10 = true;

function Video() {
	return this;
}

Video.fn = Video.prototype;

Video.fn.init = function(options) {
	this.element = options.element;
	
	//  给video外面包上一层
	var elementContainer = document.createElement('div');
	elementContainer.classList.add('video-wrapper');
	this.element.parentNode.replaceChild(elementContainer, this.element);
	elementContainer.appendChild(this.element);
	
	// 加上下载按钮
	options.showDownButton && elementContainer.insertAdjacentHTML('beforeEnd', '<a class="button button-block" href="http://a.app.qq.com/o/simple.jsp?pkgname=com.sports.baofeng" target="_blank">下载暴风体育，更流畅、更高清<i class="fa fa-angle-right fa-lg icon"></i></a>');
	
	this._registerEvents();
	
	this._touchHandler();
	
	return this;
}

Video.fn._registerEvents = function() {
	var _self = this;
//	this.element.querySelector('.embed-video-play').addEventListener('touchend', this._touchHandler.bind(this));
	
	if(window.isInApp) {
		window.addEventListener('scroll', function() {
			var video = _self.element.querySelector('video');
			if(!video || video.paused) return;
			var rect = document.querySelector('.video-wrapper').getBoundingClientRect();
			if(rect.top < 0 && rect.height + rect.top < 0) {
				!_self.element.classList.contains('video-minimized') && _self.element.classList.add("video-minimized");
			} else {
				_self.element.classList.contains('video-minimized') && _self.element.classList.remove("video-minimized");
			}
		})
	}
}

Video.fn._touchHandler = function(e) {
//	if(this.element.querySelector('video')) return;
	var _self = this;
	var videoInfoStr = this.element.querySelector('a').getAttribute('data-video');
	var videoInfo = JSON.parse(videoInfoStr);
	var ios10InApp = window.isIOS10 && window.isInApp;
	if(videoInfo) {
		PlayerFactory({player : _self.element, cid : videoInfo.cid, size : videoInfo.size, autoplay: !ios10InApp, controls: !ios10InApp, afterVideoCreated: afterVideoCreated });
	} else {
		var videoId = this.element.querySelector('a').getAttribute('title');
		$.ajax({
			type:"get",
			url:"http://m.sports.baofeng.com/api/getVideoInfo/" + videoId,
			async:true,
			dataType: 'json',
			success: function(data){
				if(data.status === 1) {
					PlayerFactory({player : _self.element, cid : data.data.box_cid, size : data.data.file_size, autoplay: !ios10InApp, controls: !ios10InApp, afterVideoCreated: afterVideoCreated });
//					PlayerFactory({player : _self.element, cid : '236D18B1E33647FD25DD8E91F1F0D80445B63FDB', size : 36008850, autoplay: true, controls: true, afterVideoCreated: afterVideoCreated });
				}
			},
			error: function(xhr, type){
				alert('Ajax error!')
			}
		});
	}
}

function afterVideoCreated() {
	var video = document.querySelector('video');
	if(window.isInApp) {
		video.addEventListener('ended', function() {
			video.parentNode.classList.remove('video-minimized');
		})
		
		video.parentNode.insertAdjacentHTML('beforeEnd', '<div class="mini-close-btn"></div>');
		video.parentNode.querySelector('.mini-close-btn').addEventListener('touchend', function() {
			video.pause();
			window.isIOS10 && video.parentNode.querySelector('.js-video-control-play').classList.remove('pause');
			video.parentNode.classList.remove('video-minimized');
		})
		
		// video 事件
		video.addEventListener('play', function() {
			window.webplay && window.webplay.videoPlay && window.webplay.videoPlay();
		})
		
		video.addEventListener('pause', function() {
			window.webplay && window.webplay.videoPause && window.webplay.videoPause();
		})
		
		video.addEventListener('ended', function() {
			window.webplay && window.webplay.videoEnd && window.webplay.videoEnd();
		})
	}
		
	if(window.isIOS10) {
		video.parentNode.classList.add('ios10');
		video.parentNode.insertAdjacentHTML('beforeEnd', 
					   ['<div class="video-control-panel hide">',
					    '	<div class="bigplay pause js-video-control-play"></div>' , 
						'	<div class="video-control-bar">' ,
						'		<button class="fullscreen-btn"></button>' , 
						'		<div class="progress">',
						'			<div class="progress-playedtime">',
						'				<span >00:00</span>',
						'			</div>',
						'			<div class="progress-totaltime">',
						'				<span>03:36</span>',
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
					    '<div class="loading-mask">视频努力加载中...</div>'].join(""));
		
		enableVideos();
		
		var progress = this.querySelector('.progress'),progressComp;
		progress && (progressComp = ProgressFactory({element: progress, onProcessStateChangeEnd: function(milliseconds) {
				video.currentTime = milliseconds/1000;
		}}) );
		
		function enableVideos(everywhere) {
			window.makeVideoPlayableInline(video, !video.hasAttribute('muted'), !everywhere);
			enableButtons();
			
			video.parentNode.addEventListener('touchend', function() {
				video.parentNode.querySelector('.video-control-panel').classList.remove('hide');
				clearTimeout(window.to);
				window.to = setTimeout(function() {
					video.parentNode.querySelector('.video-control-panel').classList.add('hide');
				}, 8000)
			})
			
			video.addEventListener('canplay', function() {
				console.log(22)
				video.parentNode.querySelector('.loading-mask').classList.add('hide');
			})
			
			video.addEventListener('ended', function() {
				video.parentNode.querySelector('.js-video-control-play').classList.remove('pause');
				video.parentNode.querySelector('.video-control-panel').classList.remove('hide');
			})
			
			video.addEventListener('loadedmetadata', function() {
				progressComp.setTotalTime(video.duration*1000);
			})
			video.addEventListener('timeupdate', function() {
				var currentTime = video.currentTime*1000;
				progressComp.setProcessedProcess(currentTime);
			});
			video.addEventListener('waiting', function() {
				video.parentNode.querySelector('.loading-mask').classList.remove('hide');
			});
			video.addEventListener('progress', function() {
			    var bufferedArr = [];
			    var bf = this.buffered;
			    var time = this.currentTime;
				
				for(var i = 0, len = bf.length;i < len;i++) {
					bufferedArr.push({start: bf.start(i)*1000, end: bf.end(i)*1000});
				}
				progressComp.setBufferedProcess(bufferedArr);
			});

		}
		
		function enableButtons() {
			video.parentNode.querySelector('.embed-video-play').addEventListener('touchend', function() {
				this.parentNode.style.zIndex = -9;
				video.play();
			})
			
			var playBtn = video.parentNode.querySelector('.js-video-control-play');
			var fullscreenButton = video.parentNode.querySelector('.fullscreen-btn');
	
			if (playBtn) {
				playBtn.addEventListener('touchend', function () {
					if (video.paused) {
						video.play();
						video.parentNode.querySelector('.video-control-panel').classList.add('hide');
						playBtn.classList.add('pause')
					} else {
						video.pause();
						playBtn.classList.remove('pause')
					}
				});
			}
	
			if (fullscreenButton) {
				fullscreenButton.addEventListener('touchend', function () {
					video.webkitEnterFullScreen();
				});
			}
		}
	}
}

function VideoFactory(options) {
	return new Video().init(options);
}
// 已废弃
window.onWindowScrollForIOS = onWindowScrollForIOSFunc;
	
function onWindowScrollForIOSFunc(scrollTop, screenHeight) {
	screenHeight = screenHeight*window.devicePixelRatio;
	scrollTop = scrollTop*window.devicePixelRatio;
	var ele = document.querySelector('.embed-video'),
		video = ele.querySelector('video');
	// 还没点开视频前
	if(!video || video.paused) return;
	
	// 点开视频后，视频大小切换
	var rect = ele.parentNode.getBoundingClientRect(),
		viedoBottomToWinTop = rect.height + rect.top;
		
	if(viedoBottomToWinTop < scrollTop) {
		!ele.classList.contains('video-minimized') && ele.classList.add("video-minimized");
	} else {
		ele.classList.contains('video-minimized') && ele.classList.remove("video-minimized");
	}
	
	var winHeight = document.documentElement.getBoundingClientRect().height;
	
	// 处理小窗口出现后，小窗口位置
	if(ele.classList.contains('video-minimized') ) {
//		ele.style.top = scrollTop + 'px';
		ele.style.bottom = (winHeight - screenHeight - scrollTop < 0 ? 0 : winHeight - screenHeight - scrollTop) + 'px';
	};
}

window.deviceCb = window.deviceCb || {};

window.deviceCb.videoPlay = function() {
	var video = document.querySelector('video');
	video && video.paused && video.play();
}

window.deviceCb.videoPause = function() {
	var video = document.querySelector('video');
	video && !video.paused && video.pause();
}

window.deviceCb.videoDestroy = function() {
//	var video = document.querySelector('video');
//  video.pause();
//	video.setAttribute("src","data:video/mp4;base64,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");
//	video.load();
//	video.remove();
}
