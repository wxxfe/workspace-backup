require.config({
    baseUrl: 'src/scripts'
});

require([
        'components/navigation',
        'components/replaceABatch',
        'components/rankingList',
        'components/indexNews',
        'components/moment',
        'components/slider',
        'components/matchData',
        'components/bflogin',
        'components/topAndApp'
        ],
        function (navigation, ReplaceABatch, RankingList, indexNews, moment, Slider, matchData, bflogin, topAndApp) {

    //登陆判断
    bflogin.loginJudge();

    navigation.init();

    //赛程表
    var matchSlider = new Slider({wrap : $('.slide-wrap'),prev : $('.prev'),next : $('.next')});
    $('.all').on('mouseenter',function(){
        $('.events-rounds').show();
    });
    $('.events-rounds').on('mouseleave',function(){
        $(this).hide();
    });
    var rounds = $('.events-rounds a');
    rounds.on('click',function(){
       var r = $(this).data('index'); 
       rounds.removeClass();
       $(this).addClass('active');
       matchSlider.go(r);
    });

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

    //排行榜
    var rankingTabs = $('#ranking li');
    var rankingContent = $('.ranking-list');
    rankingTabs.on('mouseenter',function(){
        var index = rankingTabs.index(this);
        rankingTabs.removeClass().eq(index).addClass('active');
        rankingContent.hide().eq(index).show();
    });

});
