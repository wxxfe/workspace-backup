/**
 * @fileOverview Widget - 幻灯片播放器
 * @author yangbolun@126.com
 * @version 20121108
 * config：对象型参数，参数属性如下
 * "type":"slideSmallImgHorizontal",//轮播类型(string)，'schedule'赛程表,'slideSmallImgHorizontal'带横向图片指示器的轮播
 *  "id":"banner_carousel",//轮播器id(string)
 *  "isAutoScrollTime": 3000,//是否自动滚动(数字)，不用滚动不加此参数，参数值：自动滚动间隔时间，
 *  "mode":"gradient",//大图轮播模式(string) horizontal：横向滚动，gradient：渐隐渐现
 *  "edge":"false"//左右按钮是否永久显示(string)，true：永久显示；false：如果没有上一张或下一张，将取消显示相应按钮；类型：字符串
 *  实例如下：
 *  new WidgetSlideshow({
 *      "type":"slideSmallImgHorizontal"，
 *      "id":"banner_carousel",
 *      "isAutoScrollTime": 3000,
 *      "mode":"gradient",
 *      "edge":"false"
 *  });
 *  require&amd规范，请用对应以来的参数名直接new，
 *  require(['slideshow'],function(slideshow){new slideshow({参数体})}])
 */
define(function () {
    function WidgetSlideshow(config) {
        //初始化对象变量
        if (!config) {
            alert("请在初始化js里设置幻灯片类别")
        }
        else {
            this.slideShowType = config.type;
            this.slideID = config.id;
            this.callBack = config.callBack;
            this.isAutoScrollTime = config.isAutoScrollTime;
            this.mode = config.mode;
            this.edge = config.edge;
            //var animateType = config.pointersAnimateType
        }
        this.$slideshow = $("#" + this.slideID);
        this.$slides = this.$slideshow.find(".slides");
        this.$slides_panel = this.$slideshow.find(".slides_panel");
        this.$pointersIndex = this.$slideshow.find(".pointers_index");
        this.$pointersDot = this.$slideshow.find(".pointers_dot");
        this.$slides_content = this.$slideshow.find(".slides_content");
        this.panelNums = this.$slides_content.size();//赛程面板数量
        this.unitWidth = this.$slides.outerWidth();//默认滚动一次距离--取面板宽度
        this.unitHeight = this.$slides.outerHeight();//默认滚动一次距离--取面板宽度
        this.currentPanel = 0;//当前展示面板索引
        this.roundsStr = '';
        this.timer = null;//全局滚动计时器
        this.choose();
        //this.prev = this.$slideshow.find(".prev");
        //this.next = this.$slideshow.find(".next");
    }
    //初始化各个种类的轮播，如果有回调函数，初始化后根据种类添加回调
    //根据不同种类的轮播写入不同形式的指示器和初始化面板的滚动信息
    WidgetSlideshow.prototype.choose = function(){
        this.func = null;
        if(this.mode){
            if(this.mode){
                this.func = this.gradient
            }
            else{
                this.func = this.horizontal
            }
        }
        else{
            this.func = this.horizontal
        }
        switch (this.slideShowType) {
            case "schedule"://赛程表1
                this.schedulePointerContent();
                this.isAutoScrollTime ? this.autoscroll(this.isAutoScrollTime ? this.isAutoScrollTime : 1000) : "";
                this.func();
                this.callBack ? this.callBack() : "";
                break;
            case "slideSmallImgHorizontal"://全屏轮播带图片横向指示器
                this.fullscreenPointerContent();
                this.isAutoScrollTime ? this.autoscroll(this.isAutoScrollTime ? this.isAutoScrollTime : 1000) : "";
                this.func();
                this.callBack ? this.callBack() : "";
                break;
            case "slideDotHorizontal"://全屏轮播带圆点横向指示器
                this.fullscreenDotContent();
                this.isAutoScrollTime ? this.autoscroll(this.isAutoScrollTime ? this.isAutoScrollTime : 1000) : "";
                this.func();
                this.callBack ? this.callBack() : "";
                break;
            default :
                this.fullscreenPointerContent();
                this.isAutoScrollTime ? this.autoscroll(this.isAutoScrollTime ? this.isAutoScrollTime : 1000) : "";
                this.func();
                this.callBack ? this.callBack() : "";
                break;
        }
    };
    //mode：horizontal横向滚动模式
    WidgetSlideshow.prototype.horizontal = function(){
        this.$slides_panel.width(this.unitWidth * this.panelNums + 'px');
        //点击左右按钮的行为
        var self = this;
        this.$slideshow.on("click", "a", function () {
            self.isAutoScrollTime ? clearInterval(self.timer) : "";
            if (this.className == "next" || this.className == "next_span") {
                if (self.currentPanel < self.panelNums - 1) {
                    //以下left取值算法来源
                    //-unitWidth(1+currentPanel) = - unitWidth - currentPanel*unitWidth = $slides_panel.position().left - unitWidth - (currentPanel*unitWidth + slides_panel.position().left)
                    //schedule_panel距父容器距离 = 当前slides_panel距父元素left - 默认一次点击移动距离 - 【修正上次动画没完成计算距离误差】（上次没移动完的距离 = 正常一次动画完成情况下应该移动的距离 - （【实际上是负值，所以变为了加号】当前已经移动的距离） ）
                    self.$slides_panel.animate({left: -self.unitWidth * (1 + self.currentPanel) + "px"}, "fast");
                }
                else {
                    return
                }
                self.currentPanel++;
                if (self.$pointersIndex.size() == 0) {}
                else {
                    self.roundsFollow();
                }
            }
            else if (this.className == "prev" || this.className == "prev_span") {
                if (self.currentPanel > 0) {
                    //$slides_panel.position().left + unitWidth + (-$slides_panel.position().left - currentPanel*unitWidth )
                    self.$slides_panel.animate({left: self.unitWidth * (1 - self.currentPanel) + "px"}, "fast");
                }
                else {
                    return
                }
                self.currentPanel--;
                if (self.$pointersIndex.size() == 0) {}
                else {
                    self.roundsFollow();
                }
            }
            self.pointShow(self.panelNums);
            self.isAutoScrollTime ? self.autoscroll(self.isAutoScrollTime ? self.isAutoScrollTime : 1000) : "";
        });
        //点击指示器的动作
        this.$pointersIndex.on("click", "a", function () {
            self.isAutoScrollTime ? clearInterval(self.timer) : "";
            for (var i = 0; i < self.panelNums; i++) {
                self.arraryRoundsPointers[i].className = '';
            }
            this.className = 'active';
            var schedulePanelOffsetLeft = self.$slides_panel.position().left;//当前赛程面板的offsetleft；
            var RoundsDiffer = Math.abs(parseInt(this.name) - self.currentPanel);
            if (parseInt(this.name) > self.currentPanel) {
                self.$slides_panel.animate({left: schedulePanelOffsetLeft - self.unitWidth * RoundsDiffer + "px"}, "fast");
            }
            else if (parseInt(this.name) < self.currentPanel) {
                self.$slides_panel.animate({left: schedulePanelOffsetLeft + self.unitWidth * RoundsDiffer + "px"}, "fast");
            }
            else {
            }
            self.currentPanel = parseInt(this.name);
            self.isAutoScrollTime ? self.autoscroll(self.isAutoScrollTime ? self.isAutoScrollTime : 1000) : "";
        });
    };
    //mode：gradient渐隐渐现模式
    WidgetSlideshow.prototype.gradient = function() {
        var self = this;
        //设置赛程面板总宽度
        this.$slides_panel.width(this.unitWidth + 'px');
        this.arrowJude();
        window.onresize = function(){
            self.unitWidth = self.$slides.outerWidth();//默认滚动一次距离--取面板宽度
            self.$slides_panel.width(self.unitWidth + 'px');
            //$.each(self.$slides_panel.find("img"), function (i, n) {
            //    self.unitWidth = self.$slides.outerWidth();//默认滚动一次距离--取面板宽度
            //    self.unitHeight = self.$slides.outerHeight();//默认滚动一次距离--取面板宽度
            //    $(n).css({"width": self.unitWidth + "px", "height": self.unitHeight + "px"});
            //});
            //$.each(this.$slides_panel.find(".title-carousel"), function (i, n) {
            //    self.unitWidth = self.$slides.outerWidth();//默认滚动一次距离--取面板宽度
            //    $(n).css({"width": self.unitWidth + "px"});
            //});
            //$.each(this.$slides_panel.find(".title-carousel-bg"), function (i, n) {
            //    self.unitWidth = self.$slides.outerWidth();//默认滚动一次距离--取面板宽度
            //    $(n).css({"width": self.unitWidth + "px"});
            //});
        }
        //修改排列方式
        this.$slides_content.each(function (i, ele) {
            $(ele).css({"position": "absolute", "display": "none"});
        });
        //初始化第一张显示
        $(this.$slides_content[0]).css({"position": "absolute", "display": "block"});
        $(this.$slides_content[0]).find(".title-carousel").show();
        //点击左右按钮的行为

        this.$slideshow.on("click", "a", function () {
            self.isAutoScrollTime ? clearInterval(self.timer) : "";
            if (this.className == "next" || this.className == "next_span") {
                if (self.currentPanel < self.panelNums - 1) {
                    for (var i = 0; i < self.panelNums; i++) {
                        $(self.$slides_content[i]).find(".title-carousel").hide();
                        $(self.$slides_content[i]).fadeOut(750);
                    }
                    $(self.$slides_content[self.currentPanel + 1]).fadeIn(750);
                    $(self.$slides_content[self.currentPanel + 1]).find(".title-carousel").show();
                }
                else {
                    return
                }
                self.currentPanel++;
            }
            else if(this.className == "prev" || this.className == "prev_span")  {
                if (self.currentPanel > 0 && self.currentPanel <= self.panelNums - 1) {
                    for (var i = 0; i < self.panelNums; i++) {
                        $(self.$slides_content[i]).find(".title-carousel").hide();
                        $(self.$slides_content[i]).fadeOut(750);
                    }
                    $(self.$slides_content[self.currentPanel - 1]).fadeIn(750);
                    $(self.$slides_content[self.currentPanel - 1]).find(".title-carousel").show();
                }
                else {
                    return
                }
                self.currentPanel--;
            }

            if (self.$pointersIndex) {
                if (self.$pointersIndex.size() == 0) {
                }
                else {
                    self.roundsFollow();
                }
            }
            if (self.$pointersDot) {
                if (self.$pointersDot.size() == 0) {
                }
                else {
                    self.roundsFollow();
                }
            }


            self.pointShow(self.panelNums);
            self.isAutoScrollTime ? self.autoscroll(self.isAutoScrollTime ? self.isAutoScrollTime : 1000) : "";
        });
        //点击指示器的动作

        if (this.$pointersIndex.length > 0) {
            this.$pointersIndex.on("mouseenter", "a", function () {
                self.isAutoScrollTime ? clearInterval(self.timer) : "";
                for (var i = 0; i < self.panelNums; i++) {
                    self.arraryRoundsPointers[i].className = '';
                    $(self.$slides_content[i]).find(".title-carousel").hide();
                    $(self.$slides_content[i]).fadeOut(750);
                }
                this.className = 'active';
                var pointIndex = $(this).index();
                $(self.$slides_content[pointIndex]).find(".title-carousel").show();
                $(self.$slides_content[pointIndex]).fadeIn(750);
                self.currentPanel = parseInt(pointIndex);
                self.isAutoScrollTime ? self.autoscroll(self.isAutoScrollTime ? self.isAutoScrollTime : 1000) : "";
            });
        }
        else if (this.$pointersDot.length > 0) {
            this.$pointersDot.on("mouseenter", "a", function () {
                self.isAutoScrollTime ? clearInterval(self.timer) : "";
                for (var i = 0; i < self.panelNums; i++) {
                    self.arraryRoundsPointers[i].className = '';
                    $(self.$slides_content[i]).find(".title-carousel").hide();
                    $(self.$slides_content[i]).fadeOut(750);
                }
                this.className = 'active';
                var pointIndex = $(this).index();
                $(self.$slides_content[pointIndex]).find(".title-carousel").show();
                $(self.$slides_content[pointIndex]).fadeIn(750);
                self.currentPanel = parseInt(pointIndex);
                self.isAutoScrollTime ? self.autoscroll(self.isAutoScrollTime ? self.isAutoScrollTime : 1000) : "";
            });
        }


    };
    //自动滚动
    WidgetSlideshow.prototype.autoscroll = function (time) {
        if(this.panelNums <= 1){
            var prev = this.$slideshow.find(".prev");
            var next = this.$slideshow.find(".next");
            prev.css({"visibility":"hidden"});
            next.css({"visibility":"hidden"});
        }
        else{
            var self = this;
            if (this.mode && this.mode !== undefined && this.mode !== "") {
                switch (this.mode) {
                    case "horizontal":
                        this.timer = setInterval(function () {
                            (self.currentPanel + 1 == self.panelNums) ? self.currentPanel = -1 : "";
                            self.$slides_panel.animate({left: -self.unitWidth * (1 + self.currentPanel) + "px"}, "fast");
                            self.currentPanel++;
                            if (self.$pointersIndex.size() == 0) {
                            }
                            else {
                                self.roundsFollow();
                            }
                            self.pointShow(self.panelNums);
                        }, time);
                        break;
                    case "gradient":
                        this.timer = setInterval(function () {
                            (self.currentPanel + 1 == self.panelNums) ? self.currentPanel = -1 : "";
                            for (var i = 0; i < self.panelNums; i++) {
                                $(self.$slides_content[i]).find(".title-carousel").hide();
                                $(self.$slides_content[i]).fadeOut(750);
                            }
                            $(self.$slides_content[self.currentPanel + 1]).find(".title-carousel").show();
                            $(self.$slides_content[self.currentPanel + 1]).fadeIn(750);
                            self.currentPanel++;

                            if (self.$pointersIndex) {
                                if (self.$pointersIndex.size() == 0) {
                                }
                                else {
                                    self.roundsFollow();
                                }
                            }
                            if (self.$pointersDot) {
                                if (self.$pointersDot.size() == 0) {
                                }
                                else {
                                    self.roundsFollow();
                                }
                            }
                            self.pointShow(self.panelNums);
                        }, time);
                        break;
                }
            }
            else {
            }
        }
    };
    //赛程表，指示器内容或更多能容
    WidgetSlideshow.prototype.schedulePointerContent = function () {
        for (var i = 1; i <= this.panelNums; i++) {
            var rounds = $(this.$slides_content[i-1]).find(".rounds").attr("data-round");
            if (rounds < 10) {
                this.roundsStr += '<a href="javascript:void(\'0' + rounds + '\');" title = "第' + rounds + '轮" id="r'+ rounds +'" name = "' + (i - 1) + '">0' + rounds + '</a>';
            }
            else {
                this.roundsStr += '<a href="javascript:void(\'' + rounds + '\');" title = "第' + rounds + '轮" id="r'+ rounds +'" name = "' + (i - 1) + '">' + rounds + '</a>';
            }
        }
        this.$pointersIndex.html(this.roundsStr);
        this.intPoint();
    };
    //全屏轮播，图片指示器内容或更多能容
    WidgetSlideshow.prototype.fullscreenPointerContent = function() {
        var self = this;
        //$.each(this.$pointersIndex.find("img"), function (i, n) {
        //    self.roundsStr += '<a href="javascript:void(\'0' + n.parentNode.title + '\');" title = "' + n.parentNode.title + '" name = "' + (i) + '"><img src="' + n.src + '"></a>';
        //});
        //this.$pointersIndex.html(self.roundsStr);

        //初始化大图大小
        //$.each(this.$slides_panel.find("img"), function (i, n) {
        //    $(n).css({"width": self.unitWidth + "px", "height": self.unitHeight + "px"});
        //});
        //$.each(this.$slides_panel.find(".title-carousel"), function (i, n) {
        //    $(n).css({"width": self.unitWidth + "px"});
        //});
        //$.each(this.$slides_panel.find(".title-carousel-bg"), function (i, n) {
        //    $(n).css({"width": self.unitWidth + "px"});
        //});
        self.intPoint();
    };

    //全屏轮播，圆点指示器内容或更多能容
    WidgetSlideshow.prototype.fullscreenDotContent = function () {
        var self = this;
        $.each(this.$slides_content, function (i, n) {
            self.roundsStr += '<a href="javascript:void("' + i + '");" name = "' + (i) + '"></a>';
        });
        this.$pointersDot.html(self.roundsStr);
        self.intPoint();
    };

    //点击箭头的时候，轮次联动
    WidgetSlideshow.prototype.roundsFollow = function() {
        for (var i = 0; i < this.panelNums; i++) {
            this.arraryRoundsPointers[i].className = '';
            //self.$slides_content[pointIndex]
            if(this.arraryRoundsPointers[i].name){
                if (this.arraryRoundsPointers[i].name == this.currentPanel) {
                    this.arraryRoundsPointers[i].className = 'active';
                }
            }
            else{
                if ($(this.arraryRoundsPointers[i]).index() == this.currentPanel) {
                    this.arraryRoundsPointers[i].className = 'active';
                }
            }
        }
    };
    //指示器初始化首屏的选中状态和左右箭头
    WidgetSlideshow.prototype.intPoint = function(){
        var prev = this.$slideshow.find(".prev");
        var next = this.$slideshow.find(".next");
        if(this.edge == "true"){}
        else{
            if(prev){
                prev.css({"visibility":"hidden"});
            }
            else{}
        }

        if (this.$pointersIndex) {
            if (this.$pointersIndex.size() == 0 || this.$pointersIndex.find("a").size() == 0) {
            }
            else {
                this.arraryRoundsPointers = this.$pointersIndex.find("a");
                this.arraryRoundsPointers[0].className = 'active';
            }
        }
        if (this.$pointersDot) {
            if (this.$pointersDot.size() == 0 || this.$pointersDot.find("a").size() == 0) {
            }
            else {
                this.arraryRoundsPointers = this.$pointersDot.find("a");
                this.arraryRoundsPointers[0].className = 'active';
            }
        }


    };
    //鼠标滑动到焦点图区域箭头显示,否则隐藏
    WidgetSlideshow.prototype.arrowJude = function () {
        var prev = this.$slideshow.find(".prev");
        var next = this.$slideshow.find(".next");
        this.$slideshow.on("mouseenter", function () {
            prev.fadeIn();
            next.fadeIn();
        });
        this.$slideshow.on("mouseleave", function () {
            prev.fadeOut();
            next.fadeOut();
        });
    }

    //左右箭头自动动作，当无下一张或上一张的时候，箭头消失
    WidgetSlideshow.prototype.pointShow = function(panelNums){
        var prev = this.$slideshow.find(".prev");
        var next = this.$slideshow.find(".next");
        if(this.edge == "true"){}
        else{
            if(this.currentPanel == 0){
                prev.css({"visibility":"hidden"});
            }
            else{
                prev.css({"visibility":"visible"});
            }
            if(this.currentPanel >= panelNums-1){
                next.css({"visibility":"hidden"});
            }
            else{
                next.css({"visibility":"visible"});
            }
        }
    };
    return WidgetSlideshow;
});
