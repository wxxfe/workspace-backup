require.config({
    baseUrl: 'src/scripts'
});

require(['components/bflogin', 'components/support', 'components/imgScale','components/player'], function (bflogin, support, imgScale,Player) {
    window.miniSSOLogin = bflogin;
	//登陆判断
    bflogin.loginJudge();

    var isLogin = bflogin.isLogin();

    if (!isLogin.status) {
        var iframeAddC = document.createElement("iframe");
        iframeAddC.setAttribute("src", "http://changyan.sohu.com/api/2/logout?client_id=6fd388e82ab57af24b52b801fa559995&callback=C66A5BAD9ED000011E5A1F685821111F");
        iframeAddC.setAttribute("id", "ssoLoginFrameC");
        iframeAddC.style.display = 'none';
        document.body.appendChild(iframeAddC);
    }
    //点赞
    new support({
        button: $(".zan"),
        progress: true,
        animate: true
    });

    //视频选择播放
    var matchvideoList = $("#match-video-list"); 
    var video = $("#video"); 
    var player = null;

    var firstVieo = $('a:first',matchvideoList);

    var _playCode = firstVieo.data('video');
    if(playCode){
        _playCode = playCode;
    }

    if(_playCode.indexOf('qstp://') > -1){
        player = new Player({wrap : video,playUrl : _playCode,width : '100%',height: '500px'});
        video.attr('data-bfplayer',1);
    }else{
        video.html(_playCode);
    }

    matchvideoList.on("click","a",function(event){
        var href = $(this).prop('href');
        if(href){
            $(this).off('click');
            return;
        }
		var playCode = $(this).data('video');
        $(this).parent().siblings().children('a').removeClass('active');
        $(this).addClass('active');
        if(playCode.indexOf('qstp://') > -1){
            var isBFPlayer = video.data('bfplayer');            
            if(!isBFPlayer){
                player = new Player({wrap : video,playUrl : playCode,width : '100%',height: '500px'});
            }else{
                player.playVideo({qstp : playCode});
            }
        }else{
            video.removeAttr('data-bfplayer');
            video.html(playCode);
        }
    });


    //点击登陆弹层上的x
    window.ssoNoticeMessage = function (p) {
        var a = decodeURI(p).split("&");
        if (a.shift() == "closeLoginWin") {
            bflogin.closeLoginWin();
        }

    }

    $('.lazy').lazyload({
        effect: 'fadeIn'
    });

    //等比例缩放图片尺寸
    imgScale("#active_chat");
});
