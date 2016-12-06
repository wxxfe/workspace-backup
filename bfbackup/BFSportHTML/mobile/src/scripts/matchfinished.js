require.config({
    baseUrl: 'src/scss'
});

require([
    'components/libs/fastclick',
    'components/libs/zepto.min',
    'components/libs/iphone-inline-video.browser',
    // 'components/libs/swiper.min',
    'components/deviceapi/index',
    'components/button/index',
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
    'components/videolist/index',
    'components/toast/index',
    'components/channel/index',
    'components/sharepage/index',
    'components/page/index'
], function (FastClick) {
    SharePageFactory();

    FastClick.attach(document.body);
    ChannelShareFactory();
    var quiz = document.querySelector('.quiz'),
            downloadtoast = document.querySelector('.downloadtoast'),
            downloadtoastComp;

    var buttons = document.querySelectorAll('.button');
    for (var i = 0, len = buttons.length; i < len; i++) {
        ButtonFactory({element: buttons[i]})
    }

    var videoPlayer = document.querySelector('.embed-video'),
            videoPlayerComp;
    videoPlayer && (videoPlayerComp = VideoPlayerShareFactory({
        element: videoPlayer,
        createDownButton: false
    }));

    downloadtoast && ( downloadtoastComp = ToastFactory({element: downloadtoast}) )

    // if (document.querySelector('.swiper-container')) {
    //     var swiper = new Swiper('.swiper-container', {
    //         pagination: '.swiper-pagination',
    //         slidesPerView: 2.5,
    //         paginationClickable: true,
    //         spaceBetween: 15
    //     });
    // }

    var videoList = document.querySelector('.video-list');
    if (videoList) {
        var playingChapter = videoList.getAttribute('data-playing');
        var items = videoList.querySelectorAll('.swiper-slide');
        var playIndex;

        var videoListComp = VideoListFactory({element: document.querySelector('.video-list')});
        videoListComp.on('videolist.videoSelect', function (videoInfo) {
            VideoSrcGetter.getSrc(videoInfo).then(function (videoSrc) {
                videoPlayerComp.Parts.videoComp.setSrc(videoSrc);
                videoPlayerComp.Parts.videoComp.play();
            });
        });
    }

    var list = document.querySelector('.list');
    list && ListFactory({element: list});

    $('.go') && $('.go').click(function (e) {
        e.preventDefault();
        DeviceApi.jumpTo(this);
    });
});
