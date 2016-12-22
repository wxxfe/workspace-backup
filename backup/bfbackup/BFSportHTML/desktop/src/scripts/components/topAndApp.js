define(function () {
    function topAndApp() {

        //当window的scrolltop距离大于1时，go to top按钮淡出，反之淡入
        function topAndAppVisible() {
            var dst = $(document).scrollTop();

            if (dst > 1) {
                $(".top-and-app").fadeIn("fast");
            } else {
                $(".top-and-app").fadeOut("fast");
            }
            topAndAppPosition();
        }

        topAndAppVisible();
        $(window).scroll(topAndAppVisible);

        // to top
        $(".back-top").click(function () {
            $("html,body").animate({scrollTop: 0}, "slow");
            return false;
        });


        //如果小于1300,right为0,否则按钮容器右边离居中容器100像素的距离>
        function topAndAppPosition() {
            var dsh = $('html').prop('scrollHeight');
            var dst = $(document).scrollTop();
            var wh = $(window).height();
            var vw = $(window).width();
            var l = '';
            var r = '';
            if ((dst + wh) >= (dsh - 136)) {
                if (vw <= 1400) {
                    r = '0px';
                } else {
                    r = '-100px'
                }
                $(".top-and-app").css({'position': 'absolute', 'bottom': '-18px', 'left': 'auto', 'right': r});
            } else {
                var s = $('.container').prop('offsetLeft') + 1200;
                if (vw <= 1400) {
                    l = s + 'px';
                } else {
                    l = (s + 50) + 'px';
                }
                $(".top-and-app").css({'position': 'fixed', 'bottom': '12px', 'left': l, 'right': 'auto'});
            }

        }

        topAndAppPosition();
        $(window).resize(topAndAppPosition);


        //app二维码显示和隐藏
        $('.go-to-app').hover(
            function () {
                $('.app-qr-code').show();
            },
            function () {
                $('.app-qr-code').hide();
            }
        );
    }

    return topAndApp;
});
