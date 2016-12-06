require.config({
    baseUrl: 'src/scripts'
});

require(['components/slider', 'components/navigation'], function (Slider, Navigation) {

        Navigation.init();

        //slider的屏数
        var topLiLen = $('.top-slider .slide-wrap > ul > li').length;
        var bottomLiLen = $('.bottom-slider .slide-wrap > ul > li').length;

        //箭头a元素
        var tArrow = $('.top-slider > a');
        var bArrow = $('.bottom-slider > a');

        ////初始化时,如果有多屏则显示next按钮
        if (topLiLen > 1) {
            tArrow.eq(1).show(0);
        }
        if (bottomLiLen > 1) {
            bArrow.eq(1).show(0);
        }

        //下部缩略图所有a元素的jq对象
        var bla = $('.bottom-slider li > a');

        //bottom初始给第一个添加选中样式
        if (bla.length) {
            bla.eq(0).addClass('active');
        }

        //bottom 8条数据一个li
        var bottomNum = 8;

        //上部slider图片对应文本页码元素,文本元素
        var topTxtCurrentPage = $('.top-slider .txt strong');
        var topTxtP = $('.top-slider .txt p');

        //上部slider图片对应文本数据初始化
        topTxtDataUpdate(0);

        //上部slider图片对应文本数据的更新,页码等
        function topTxtDataUpdate(index) {
            var img = $('.top-slider li img').eq(index);
            //如果当前大图的src没有值,则用data-url赋值
            if (img.attr('src') == '') {
                img.attr('src', img.data('url'));
            }
            //页码更新
            topTxtCurrentPage.text(index + 1);
            //文本更新
            topTxtP.text(img.attr('alt'));
        }

        //箭头按钮的显示隐藏处理
        function showHideArrow(index, end, jq) {
            if (end > -1) {
                if (index == 0) {
                    jq.eq(0).hide(0);
                    jq.eq(1).show(0);
                } else if (index == end) {
                    jq.eq(0).show(0);
                    jq.eq(1).hide(0);
                } else {
                    jq.eq(0).show(0);
                    jq.eq(1).show(0);
                }
            }
        }

        //初始上面的slider
        var top = new Slider({
            wrap: $('.top-slider .slide-wrap'),
            prev: $('.top-slider .prev'),
            next: $('.top-slider .next'),
            onEnd: topCallback
        });

        //初始下面的slider
        var bottom = new Slider({
            wrap: $('.bottom-slider .slide-wrap'),
            prev: $('.bottom-slider .prev'),
            next: $('.bottom-slider .next'),
            onEnd: bottomCallback
        });

        //top滑动完成回调,参数是li的索引
        function topCallback(index) {

            //给对应的下面缩略图添加选中样式
            bla.removeClass().eq(index).addClass('active');

            var bi = Math.floor(index / bottomNum);
            //如果换算后的索引不等于当前索引,则下部slider滑到该索引
            if (bi != bottom.activeIndex) {
                bottom.go(bi);
            }

            showHideArrow(index, topLiLen - 1, tArrow);

            topTxtDataUpdate(index);

        }

        //bottom滑动完成回调,参数是li的索引
        function bottomCallback(index) {
            showHideArrow(index, bottomLiLen - 1, bArrow);
        }

        //下面缩略图点击
        bla.click(function (event) {
            event.preventDefault();
            //给对应的下面缩略图添加选中样式
            bla.removeClass();
            $(this).addClass('active');

            //上面的slider滑到对应的元素
            top.go(bla.index(this));
        });

    }
);

