require.config({
    baseUrl: 'src/scss'
});

require([
    'components/libs/fastclick',
    'components/libs/zepto.min',
    'components/button/index',
    'components/abase/util',
    'components/abase/index',
    'components/deviceapi/index',
    'components/videonew/index',
    'components/progress/index',
    'components/videosrcgetter/cloudlive',
    'components/videosrcgetter/cloudvod',
    'components/videosrcgetter/index',
    'components/videocontrolpanel/index',
    'components/videoplayer/index',
    'components/videoplayershare/index',
    'components/toast/index',
    'components/channel/index',
    'components/sharepage/index'
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
        createDownButton: false,
        isLive: true
    }));

    downloadtoast && ( downloadtoastComp = ToastFactory({element: downloadtoast}) );

    $('.go') && $('.go').click(function (e) {
        e.preventDefault();
        DeviceApi.jumpTo(this);
    });
});
