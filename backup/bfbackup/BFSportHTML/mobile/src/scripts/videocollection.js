require.config({
    baseUrl: 'src/scss'
});

require(['components/libs/fastclick', 
			'components/libs/zepto.min', 
			'components/libs/iphone-inline-video.browser', 
			// 'components/libs/swiper.min', 
			'components/abase/util', 
			'components/abase/index', 
			'components/deviceapi/index',
			'components/listitem/index',
			'components/list/index',
			'components/article/index', 
			'components/button/index', 
			'components/player/index',
			'components/videonew/index',
			'components/progress/index',
			'components/videosrcgetter/cloudlive',
			'components/videosrcgetter/cloudvod',
			'components/videosrcgetter/index',
			'components/videocontrolpanel/index',
			'components/videoplayer/index',
			'components/videoplayershare/index',
			'components/programprofile/index', 
			'components/videolist/index', 
			'components/channel/index',
			'components/sharepage/index'], function (FastClick) {
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
	
	// var swiper = new Swiper('.swiper-container', {
	//     pagination: '.swiper-pagination',
	//     slidesPerView: 3,
	//     paginationClickable: true,
	//     spaceBetween: 30
	// });
	
	var videoList = document.querySelector('.video-list'),
		playingChapter = videoList.getAttribute('data-playing'),
		items = videoList.querySelectorAll('.swiper-slide'),
		playIndex;
	for(var i=0;i<items.length;i++) {
		if(items[i].getAttribute('data-chapter') == playingChapter) {
			playIndex = i;
			break;
		}
	}
	//swiper.slideTo(playIndex);
	
	var videoListComp = VideoListFactory({element: document.querySelector('.video-list')});
	videoListComp.on('videolist.videoSelect', function(videoInfo) {
		VideoSrcGetter.getSrc(videoInfo).then(function(videoSrc) {
			videoPlayerComp.Parts.videoComp.setSrc(videoSrc);
			videoPlayerComp.Parts.videoComp.play();
		});
	});
	
	var list = document.querySelector('.list');
	list && ListFactory({element: list});
	
});
