function WidgetSlideshow(config) {
    this.type = config.type;
    this.photoID = config.id;
    this.defaultNums = config.defaultNums;
    this.$photoOut = $("#" + this.photoID);
    this.$photo = this.$photoOut.find("#photo");
    this.$photo_panel = this.$photo.find("#photo_panel");
    this.$photo_li = this.$photo.find("li");
    this.panelLiNums = this.$photo_li.size();//li数量
    this.panelFakeNums = Math.ceil(this.panelLiNums / this.defaultNums)
    this.panelNums = 1;
    this.unitWidth = this.$photo.outerWidth();//默认滚动一次距离-取面板宽度
    this.currentPanel = 0;//当前展示面板索引
    this.elePosition = 0;//初始化滚动面板位置
    this.photo();
}

//获取参数
WidgetSlideshow.prototype.getUrlParam = function (name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) {
        return unescape(r[2]);
    }
    else {
        return null; //返回参数值
    }
}

//点击具体刷新页面后的回调方法
WidgetSlideshow.prototype.echo = function () {
    this.showId = this.getUrlParam("date");
    this.currentTime = new Date();
    this.currentYear = this.currentTime.getFullYear();
    this.currentMonth = this.currentTime.getMonth() + 1;
    this.currentDate = this.currentTime.getDate();

    if (this.currentMonth < 10) {
        this.currentMonth = "0" + this.currentMonth;
    }
    else {
    }
    if (this.currentDate < 10) {
        this.currentDate = "0" + this.currentDate;
    }
    else {
    }

    this.currentTimeSearch = this.currentYear + "-" + this.currentMonth + "-" + this.currentDate;
    if (this.showId) {
        this.showEle = document.getElementById(this.showId);
    }
    //else if($($("#hot_list").find("td")[1]).text().split(" ")[0]){
    //    this.showEle = document.getElementById($($("#hot_list").find("td")[1]).text().split(" ")[0]);
    //    this.showId = $($("#hot_list").find("td")[1]).text().split(" ")[0];
    //}
    else if (document.getElementById(this.currentTimeSearch)) {
        console.log(1)
        this.showEle = document.getElementById(this.currentTimeSearch);
        this.showId = this.currentTimeSearch;
        this.showEle.click();
    }
    else {
        this.liNums = $("#photo_panel li").size();
        this.findNums = (new Date($("#photo_panel li a")[this.liNums - 1].id).getTime() - new Date($("#photo_panel li a")[0].id).getTime()) / (1000 * 60 * 60 * 24);
        if (this.findNums == 0) {
            if($("#photo_panel li a")[0]){
                $("#photo_panel li a")[0].click()
            }
        }
        else {
            this.myDate = new Date();
            this.currentTimeStamp = this.myDate.getTime();
            this.findBase = 1000 * 60 * 60 * 24;
            for (i = 0; i < this.findNums; i++) {
                this.monthForward = new Date(this.currentTimeStamp + this.findBase * i).getMonth() + 1;
                this.dateForward = new Date(this.currentTimeStamp + this.findBase * i).getDate()
                if (this.monthForward < 10) {
                    this.monthForward = "0" + this.monthForward;
                }
                else {
                }
                if (this.dateForward < 10) {
                    this.dateForward = "0" + this.monthForward;
                }
                else {
                }

                this.findDateForward = new Date(this.currentTimeStamp + this.findBase * i).getFullYear() + "-" + this.monthForward + "-" + this.dateForward;


                this.monthBack = new Date(this.currentTimeStamp - this.findBase * i).getMonth() + 1;
                this.dateBack = new Date(this.currentTimeStamp - this.findBase * i).getDate();
                if (this.monthBack < 10) {
                    this.monthBack = "0" + this.monthBack
                }
                else {
                }
                if (this.dateBack < 10) {
                    this.dateBack = "0" + this.monthBack
                }
                else {
                }

                this.findDateBack = new Date(this.currentTimeStamp - this.findBase * i).getFullYear() + "-" + this.monthBack + "-" + this.dateBack;

                //console.log(this.findDateForward,this.findDateBack)

                if (document.getElementById(this.findDateForward)) {
                    this.showEle = document.getElementById(this.findDateForward);
                    this.showId = this.findDateForward;
                    this.showEle.click();
                    break;
                }
                else if (document.getElementById(this.findDateBack)) {
                    this.showEle = document.getElementById(this.findDateBack);
                    this.showId = this.findDateBack;
                    this.showEle.click();
                    break;
                }
                else {
                }
            }
        }


    }
    if (this.showEle) {
        this.elePosition = $(this.showEle).position().left;

        $.each($("#photo_panel a"), function (i, data) {
            data.className = '';
        });
        this.showEle.className = "active";

        $("#photo_panel").css({"left": -this.elePosition + "px"});

        this.currentPanel = Math.floor(this.elePosition / this.unitWidth + 1);

        $("#curreDate").text(this.showId.split("-")[1] + "月" + this.showId.split("-")[2] + "日")
    }
    else {

    }
}

//照片滚动
WidgetSlideshow.prototype.photo = function () {
    //初始化滚动panel宽度
    this.$photo_panel.width(this.$photo_li.outerWidth() * this.panelLiNums + 'px');
    //如果需要回复显示到当前,调用虾面这个方法
    this.echo();
    //点击左右按钮的行为
    var self = this;
    this.$photoOut.on("click", "a", function () {
        if (this.className == "next" || this.className == "next_span") {
            if (self.currentPanel < self.panelFakeNums) {
                var offset = -self.elePosition - self.unitWidth;
                self.$photo_panel.animate({left: offset + "px"}, "fast");
            }
            else {
                return
            }
            self.currentPanel++;
            self.elePosition = self.elePosition + self.unitWidth;
        }
        else if (this.className == "prev" || this.className == "prev_span") {
            if (self.currentPanel > 0) {
                var offset = -self.elePosition + self.unitWidth;
                if (offset > 0) {
                    offset = 0
                }
                else {
                }
                self.$photo_panel.animate({left: offset + "px"}, "fast");
            }
            else {
                return
            }
            self.currentPanel--;
            self.elePosition = self.elePosition - self.unitWidth;
        }
    });
}

