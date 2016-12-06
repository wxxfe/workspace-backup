require.config({
    baseUrl: 'src/scripts'
});

define(['components/polyfill', 'components/pages'], function (polyfill, pages) {
    /**
     * @param boxJQ  容器的jquery对象
     * @param pageSize 每页条数
     * @param apiUrl 接口url
     * @constructor
     */
    function ReplaceABatch(boxJQ, pageSize, apiUrl) {

        //IE8垫片代码,用来让IE8支持已经在现代浏览器上广泛支持,但IE8不支持的API
        polyfill();


        if (!boxJQ) throw "boxJQ 容器的jquery对象没有值";


        //操作需要的相关容器对象
        this.boxJQ = boxJQ;
        this.contentJQ = this.boxJQ.find(".replace-content");
        this.loadingJQ = this.boxJQ.find(".loading");

        //总页数
        var totalPages = this.boxJQ.data('pages');

        //请求处理状态
        this.processing = false;

        //请求后的回调函数
        var callback = function (data) {

            //如果回调函数没有数据，则还原
            //否则用新数据更新内容
            if (data === null) {
                this.reset();
            } else {
                this.render(data);
            }
        }.bind(this);

        //分页
        this.pages = new pages(totalPages, 1, pageSize, apiUrl, callback);

        //换一批事件
        this.boxJQ.on("click", ".replace", function (event) {
            event.preventDefault();
            this.nextPage();
        });
    }

    //还原处理,去掉加载提示，恢复显示内容
    ReplaceABatch.prototype.reset = function () {
        this.loadingJQ.removeClass("run");
        this.contentJQ.find(".loading").remove();
        this.contentJQ.children().show();
        this.processing = false;
    };

    //渲染模板
    ReplaceABatch.prototype.render = function (data) {
        this.contentJQ.empty();

        var temp = '';
        var i = 0;
        var len = data.length;
        var obj;
        var activeS;
        var relS;
        var markS;

        if (this.pages.apiUrl.indexOf("getSection") !== -1) {
            //专栏推荐
            // [
            //     {
            //         "id": "95",
            //         "section_id": "11",
            //         "title": "巨星今何在：1996欧洲杯捷克黄金一代",
            //         "site": "bfonline",
            //         "publish_tm": "2016-07-06 13:00:35",
            //         "section_logo": "http://image.sports.baofeng.com/c2fedafe5cf3d295f2d2efc447f7b1b5",
            //         "section_name": "说球",
            //         "url": "http://sports.baofeng.com/section/detail/95"
            //     },
            //     ...
            // ]
            temp += '<ul class="news-mixins-list">';
            for (i; i < len; i++) {
                obj = data[i];

                //第一条默认展开样式
                activeS = '';
                if (i === 0) activeS = ' class="active"';

                //不是暴风来源的加nofollow标识
                relS = '';
                if (obj.site != "bfonline") relS = ' rel="nofollow"';

                temp += '<li>';
                temp += '<a href="' + obj.url + '" target="_blank"' + activeS + relS + '>';
                temp += '<p class="title">' + obj.title + '</p>';
                temp += '<div class="one-row detail">';
                temp += '<div class="col-3 pic"><img src="' + obj.image + '" alt="' + obj.title + '"></div>';
                temp += '<div class="col-5 info">';
                temp += '<h3>' + obj.title + '</h3>';
                temp += '<p class="desc">';
                temp += '<span class="time">' + obj.publish_tm.split(' ')[0] + '</span>';
                temp += '<img src="' + obj.section_logo + '" alt="' + obj.title + '">' + obj.section_name;
                temp += '</p>';
                temp += '</div>';
                temp += '</div>';
                temp += '</a>';
                temp += '</li>';
            }
            temp += '</div>';

            this.contentJQ.append(temp);

            ////滑上展开效果
            $('.news-mixins-list a').mouseenter(function () {
                $('.news-mixins-list a').removeClass();
                $(this).addClass('active');
            });

        } else if (this.pages.apiUrl.indexOf("getProgram") !== -1) {
            //节目推荐
            // [
            //     {
            //         "id": "283",
            //         "image": "http://image.sports.baofeng.com/b20cdf6ff1ac288210f1690115ae5251",
            //         "play_url": "http://sports.sina.com.cn/euro2016/video/footballtactics/22/#250660469",
            //         "title": "《欧洲杯道中道》第22期：最强东道主期待最强决战",
            //         "site": "sina",
            //         "program_name": "欧洲杯道中道"
            //     },
            //     ...
            // ]
            for (i; i < len; i++) {
                obj = data[i];

                //暴风来源的加图标样式,不是的加nofollow标识
                markS = '';
                relS = '';
                if (obj.site == "bfonline") {
                    markS = ' class="bf-mark"';
                } else {
                    relS = ' rel="nofollow"';
                }

                temp += '<div class="media large photo">';
                temp += '<span' + markS + '></span>';
                temp += '<a href="' + obj.play_url + '" target="_blank"' + relS + '>';
                temp += '<img src="' + obj.image + '" alt="' + obj.title + '">';
                temp += '<p class="title">' + obj.title + '</p>';
                temp += '</a>';
                temp += '</div>';

            }

            this.contentJQ.append(temp);
        }


        this.reset();
    };

    /**
     * 下一页
     */
    ReplaceABatch.prototype.nextPage = function () {
        //如果上次点击处理完成则继续
        if (!this.processing) {
            this.processing = true;

            //更新当前页数据,并且返回页面缓存数据
            var nextPageData = this.pages.nextPageRepeat();

            //如果有缓存数据，直接用缓存数据更新内容
            //否则请求新数据
            if ($.isArray(nextPageData) && nextPageData.length) {
                this.render(nextPageData);
            } else {
                //先把现有内容隐藏，只是隐藏，如果请求数据失败，则直接恢复显示
                this.contentJQ.children().hide();
                //加载提示
                this.contentJQ.append('<i class="loading run"></i>');
                this.loadingJQ.addClass("run");

                //请求数据
                this.pages.getPageData(this.pages.currentPage);
            }
        }
    };

    return ReplaceABatch;
});
