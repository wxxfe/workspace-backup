require.config({
    baseUrl: 'src/scripts'
});

require(['components/player','components/navigation'], function (Player,Navigation) {
        Navigation.init();

        var debug = function (d) {
            if (!d) {
                window.console = {
                    log: function () {
                    }
                };
            }
        };

        debug(false);

        //视频url数组
        var pl = playList ? playList : [];
        //默认单个视频播放,如果数组大于1,则出现播放列表
        var plw = 1200;
        if (pl.length > 1) {
            plw = 915;
            $('.video-list-wrap').show();
        }

        //播放
        var player = new Player({wrap: $('#player'), playList: pl, width: plw, height: 567});
        window.cloudsdk = {};
        window.cloudsdk.onActionTojs = function () {
            var type = arguments[0];
            console.log('----' + player.playIndex + '----');
            switch (type) {
                case 'cloudstatus':
                    console.log(type + '--' + arguments[1] + ':' + arguments[2]);
                    break;
                case 'displayChange':
                    console.log(type + ' -- ' + arguments[1]);
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
        };

        var goPlay = function () {
            var pi = player.playIndex;
            if (pi < pl.length - 1){
                player.playIndex++;
            } else {
                player.playIndex = 0;
            }
            player.playVideo(pl[player.playIndex]);
            showPlayingVideo(player.playIndex);
        };

        var videos = $('.video-list li a');
        var videoListWrap = $('.video-list-wrap');
        var showPlayingVideo = function (index) {
            var item = videos.eq(index);
            videos.removeClass();
            item.addClass('active');
            var position = item.position();
            if (index % 3 == 0) {
                videoListWrap.scrollTop(position.top);
            }
        };

        videos.on('click', function () {
            var index = videos.index(this);
            videos.removeClass();
            $(this).addClass('active');
            player.playIndex = index;
            player.playVideo(pl[index]);
            $('h1').text($(this).find('p').text());
        });

        //播放End

        //分享
        $('.share').mouseover(function () {
            $('.jiathis_style_24x24').show();
        });
        $('.share').mouseout(function () {
            $('.jiathis_style_24x24').hide();
        });
    }
);

