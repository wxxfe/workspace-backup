require.config({
    baseUrl: 'src/scripts'
});

require(['components/slider','components/player','components/navigation'], function (Slider,Player,Navigation){
    var debug = function (d) {
        if (!d) {
            window.console = {
                log: function () {
                }
            };
        }
    };

    debug(false);

    Navigation.init();

    //轮播图
    var dot = $('.active-dot'), dotStr = '';
    $('.slider-news li').each(function (index) {
        if (index == 0) {
            dotStr += '<a class="active" href="javascript:void(0)"></a>';
        } else {
            dotStr += '<a href="javascript:void(0)"></a>';
        }
    });
    dot.html(dotStr);
    var dots = dot.children('a');
    var newsSlider = new Slider({
        wrap: $('.slider-news'), animate: 'fade', auto: true, onEnd: function (index) {
            dots.removeClass().eq(index).addClass('active');
        }
    });

    dots.on('click', function () {
        var thisIndex = dots.index(this);
        newsSlider.go(thisIndex);
    });

    //赛程表
    var matchSlider = new Slider({
        wrap: $('.match-slider'),
        prev: $('.prev'),
        next: $('.next')
    });

    var today = $('.match-table').data('day');
    today = (today < 10 && today.indexOf('0') > -1) ? today.split('')[1] : today;
    if(today < 9){
        matchSlider.go(0);
    }else if(today >= 9 && today <= 13){
        matchSlider.go(1);
    }else if(today >= 14 && today <= 18){
        matchSlider.go(2);
    }else if(today >= 19){
        matchSlider.go(3);
    }

    var mtable = $('.match-table');

    var showMatchList = function(day){
        var hasList = $('#list-' + day).length > 0;
        $('.match-list').hide();
        if(hasList){
            $('#list-' + day).show();
            return false;
        }
        $.get('http://2016.sports.baofeng.com/api/getSchedule/' + day,function(d){
            var listHtml = '<ul class="match-list" id="list-'+ day +'">';
            var data = typeof d === 'string' ? (new Function('return ' + d))() : d;
            var matchs = data.result.data;
            for(var i=0; i<5;i++){
                listHtml += '<li>'+ matchs[i].nice_date +' '+ matchs[i].round +' '+ (matchs[i].is_china == '1' ? '<i class="is_china"></i>' : '') +'</li>';
            }
            listHtml += '</ul>';
            mtable.append(listHtml);
        });
        
    }

    var days = $('.match-slider ul li a');

    days.on('click',function(){
        var d = this.id.split('-')[1];
        days.removeClass();
        $('#day-' + d).addClass('active');
        showMatchList(d);
    });
    days.removeClass();
    $('#day-' + today).trigger('click');

    //视频url数组
    var pl = playList ? playList : [];
    //默认单个视频播放,如果数组大于1,则出现播放列表

    //播放
    var player = new Player({wrap: $('#player'), playList: pl, width : 280,height : 173});
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
        if (pi < pl.length - 1) {
            player.playIndex++;
        } else {
            player.playIndex = 0;
        }
        player.playVideo(pl[player.playIndex]);
    };
    //播放End

});
