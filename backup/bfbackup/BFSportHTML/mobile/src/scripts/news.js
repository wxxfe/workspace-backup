require.config({
    baseUrl: 'src/scss'
});

require(['components/libs/fastclick', 
			'components/libs/zepto.min',
			'components/deviceapi/index', 
			'components/article/index', 
			'components/button/index',
			'components/libs/iphone-inline-video.browser',
			'components/abase/util',
			'components/abase/index',
			'components/listitem/index',
			'components/list/index',
			'components/videonew/index',
			'components/progress/index',
			'components/videosrcgetter/cloudlive',
			'components/videosrcgetter/cloudvod',
			'components/videosrcgetter/index',
			'components/videocontrolpanel/index',
			'components/videoplayer/index',
			'components/videoplayershare/index',
			'components/videoplayerapp/index',
			'components/shareThird/index',
			'components/channel/index',
			'components/sharepage/index',
			'components/page/index'], function (FastClick) {
    	window.pageType = Utils.pageType;
    	
    pageType !== 'app' && SharePageFactory();
    	
    	FastClick.attach(document.body);
    	ChannelShareFactory();
    
    ArticleFactory({element: document.querySelector('.article'), enableShowMore: pageType !== 'app'});

    var buttons = document.querySelectorAll('.button');
	for(var i = 0, len = buttons.length;i < len;i++) {
		ButtonFactory({element: buttons[i]})
	}
	
	var shareThird = document.querySelector('.share-third');
	if(shareThird) {
		if(pageType === 'app') {
			// ios 下审核版本需要隐藏分享到第三方组件，window.webplay是在页面加载结束之后客户端赋值的
			window.addEventListener('load', function() {
				setTimeout(function() {
					if(Utils.browserInfo.isIos && window.webplay && window.webplay.isReviewVersion && window.webplay.isReviewVersion()) {
						shareThird.classList.add('hide');
					} else {
						ShareThirdFactory({element: shareThird});
					}
				}, 0);
			});
			
		} else {
			shareThird.classList.add('hide');
		}
	}
	
//	var video = document.querySelector('.embed-video');
//	if(video) {
//		if(pageType === 'app') {
//			VideoFactory({element: document.querySelector('.embed-video')});
//		} else {
//			VideoFactory({element: document.querySelector('.embed-video'), showDownButton: true});
//		}
//	}
	
	var videoPlayer = document.querySelector('.embed-video'),
		videoPlayerComp;
	if(videoPlayer) {
		if(pageType === 'app') {
//		if(true) {
			videoPlayerComp = VideoPlayerAppFactory({
				element: videoPlayer,
			});
		} else {
			videoPlayerComp = VideoPlayerShareFactory({
				element: videoPlayer,
				createDownButton: true,
			});
		}
	};
	
	var list = document.querySelector('.list');
	list && ListFactory({element: list});
	
});
