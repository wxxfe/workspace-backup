require.config({
    baseUrl: 'src/scss'
});

require(['components/libs/fastclick', 
			'components/libs/zepto.min', 
			'components/article/index', 
			'components/button/index', 
			'components/libs/iphone-inline-video.browser',
			'components/abase/util',
			'components/abase/index',
			'components/deviceapi/index',
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
			'components/channel/index',
			'components/sharepage/index',
			'components/page/index'], function (FastClick) {
	SharePageFactory();
	FastClick.attach(document.body);
    ChannelShareFactory();
    
    var buttons = document.querySelectorAll('.button');
	for(var i = 0, len = buttons.length;i < len;i++) {
		ButtonFactory({element: buttons[i]})
	}
	
	var videoPlayer = document.querySelector('.embed-video'),
		videoPlayerComp;
	videoPlayerComp = VideoPlayerShareFactory({
		element: videoPlayer,
		createDownButton: false
	});

	var list = document.querySelector('.list');
	list && ListFactory({element: list});
	
});
