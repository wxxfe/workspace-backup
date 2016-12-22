require.config({
    baseUrl: 'src/scripts'
});


require(['components/slider','components/player','components/navigation'], function (Slider,Player,Navigation){

    Navigation.init();

    var debug = function(d){
        if(!d){
            window.console = {
                log : function(){}
            };
        }
    }

    debug(false);

    var pl = playList ? playList : [];

    //播放
    var player = new Player({wrap : $('#player'),playList : pl,width : 915,height : 567});
    window.cloudsdk = {};
    window.cloudsdk.onActionTojs = function(){
        var type = arguments[0];
        console.log('----' + player.playIndex + '----');
        switch(type){
            case 'cloudstatus':
                console.log(type + '--' + arguments[1] + ':' + arguments[2]);
                break;
            case 'displayChange':
                console.log(type + ' -- ' + arguments[1]) ;
                break;
            case 'playend':
                goPlay();
                break;
            case 'send_barrage_flash':
                console.log(arguments[1]);
                break;
            case 'send_barrage_resault':
                console.log(arguments[1]);
                break;
            case 'receive_barrage_flash':
                console.log(arguments[1]);
                break;
            case 'show_barrage_flash':
                console.log(arguments[1]);
                break;
        }
    }

    var goPlay = function(){
        var pi = player.playIndex;
        if(pi < pl.length){
            player.playIndex++;
        }else{
            player.playIndex = 0;

        }
        player.playVideo(pl[player.playIndex]);
        showPlayingVideo(player.playIndex);
    }


    var videos = $('.video-list li a');
    var videoListWrap = $('.video-list-wrap');
    var showPlayingVideo = function(index){
        var item = videos.eq(index);
        videos.removeClass();
        item.addClass('active');
        var position = item.position();
        if(index % 3 == 0){
            videoListWrap.scrollTop(position.top);
        }
    }

    videos.on('click',function(){
        var index = videos.index(this);
        videos.removeClass();
        $(this).addClass('active');
        player.playIndex = index;
        player.playVideo(pl[index]);
    });

    //播放End


    //名人榜
    new Slider({wrap: $('.slide-wrap'), prev: $('.prev'), next: $('.next')});
    ////如果有多屏则显示箭头按钮
    if ($('.slide-wrap > ul > li').length > 1) {
        $('.data-wrap > a').show();
    }

});

