require.config({
    baseUrl: 'src/scripts'
});

require(
    [],

    function () {

        $("#container").switchPage({switchCallback: switchCallback});

        var ready = [];
        var nowDom;

        function switchCallback(element) {
            var now = element.find("iframe");
            nowDom = now[0];
            $("iframe").not(now).css('display', 'none');
            now.css('display', 'block');

            if ($.inArray(nowDom, ready) > -1) {
                playPause(nowDom);
            } else {
                ready.push(nowDom);
                setTimeout($.proxy(playPause, null, nowDom), 2000);
            }

        }

        function playPause(iframeDom) {
            if (iframeDom == nowDom) {
                try {
                    $(ready).each(function () {
                        if (this != iframeDom) {
                            if (this.contentWindow.bfplayer.opts.player.jsToAction) this.contentWindow.bfplayer.pause();
                        }
                    });
                    if (iframeDom.contentWindow.bfplayer.opts.player.jsToAction) iframeDom.contentWindow.bfplayer.play();
                } catch (e) {
                }
            }
        }

        ready.push($("iframe")[0]);
        setTimeout(function () {
            if (ready[0].contentWindow.bfplayer.opts.player.jsToAction) ready[0].contentWindow.bfplayer.play();
        }, 2000);

    }
);

