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
        $('.events-tab').show();
    });
    $('.events-tab').on('mouseleave',function(){
        $(this).hide();
    });
    
    matchData.init(matchSlider,300,16);

    $('.events-tab li a').eq(1).trigger('click');
    matchSlider.go(16);

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

    //专栏推荐
    ////滑上展开效果
    $('.news-mixins-list a').mouseenter(function () {
        $('.news-mixins-list a').removeClass();
        $(this).addClass('active');
    });
    ////换一批
    new ReplaceABatch($(".section-wrap"), 6, "http://sports.baofeng.com/api/getSection/");

    //节目推荐
    ////换一批
    new ReplaceABatch($(".program-wrap"), 3, "http://sports.baofeng.com/api/getProgram/");

    //榜单
    new RankingList($(".ranking-wrap"));

    //回到顶部和app
    topAndApp();

    //国际足球
    indexNews.init();
    //精彩瞬间
    moment.init();

    $('.lazy').lazyload({
        effect:'fadeIn'
    });
});
