define(function () {
    function RankingList(boxJQ) {
        if (!boxJQ) throw "boxJQ 容器的jquery对象没有值";

        //操作需要的相关容器对象
        this.boxJQ = boxJQ;
        this.contentJQ = this.boxJQ.find(".replace-content");

        //点击的榜单类型索引 0 开始
        this.topIndex = 0;

        //点击榜单类型A标签
        this.boxJQ.find(".tab-menu-two").on("click", "a", function (event) {
            event.preventDefault();

            var topAJQ = $(event.currentTarget);

            //先去掉所有A的样式,然后添加当前A的选中样式
            $('.tab-menu-two a').removeClass();
            topAJQ.addClass('active');

            //点击的榜单类型索引
            this.topIndex = topAJQ.parent().index();

            //先把榜单全部隐藏,然后显示对应的榜单内容
            var listJQ = this.contentJQ.find(".top-list");
            listJQ.hide();
            listJQ.eq(this.topIndex).show();
        }.bind(this));


        //缓存数据 { 赛事id:[ [obj, ...], ...], ... }
        this.cacheData = {};

        //一次点击的业务逻辑代码执行状态
        this.processing = false;

        //点击赛事A标签
        this.boxJQ.find(".tab-menu").on("click", "a", function (event) {
            event.preventDefault();

            var matchAJQ = $(event.currentTarget);

            //先去掉所有A的样式,添加当前A的选中样式
            $('.tab-menu a').removeClass();
            matchAJQ.addClass('active');

            //点击的赛事ID
            this.matchID = matchAJQ.data('eid');


            //如果不在处理中,隐藏内容并添加加载提示,防止连续点击重复执行。
            if (!this.processing) {
                this.processing = true;
                //先把现有内容隐藏，只是隐藏，如果特殊情况不更新，则直接恢复显示
                this.contentJQ.children().hide();
                //加载提示
                this.contentJQ.append('<i class="loading run"></i>');

            }

            //请求数据
            this.getData(this.matchID);


        }.bind(this));
    }

    //还原处理,去掉加载提示，恢复显示内容
    RankingList.prototype.reset = function () {
        this.contentJQ.find(".loading").remove();
        this.contentJQ.children().not(".hide-display").show();
        this.processing = false;
    };

    //渲染模板
    RankingList.prototype.render = function (data) {
        this.contentJQ.empty();

        var temp = '';
        var i = 0;
        var len = data.length;
        var key;
        var iArr;
        var j;
        var jLen;
        var jObj;

        var moreS;
        var top1S;
        var highlightS;
        var tableClassS;
        var th1S;
        var th2S;
        var th3S;
        var th4S;
        var td1S;
        var td2S;
        var td3S;
        var td4S;

        for (i; i < len; i++) {

            tableClassS = '';

            //第一个和第二个榜单显示内容有点区别
            if (i == 0) {
                th1S = '<th class="t1">名次</th>';
                th2S = '<th class="t2">球队</th>';
                th3S = '<th class="t3">胜/平/负</th>';
                th4S = '<th class="t4">积分</th>';

                moreS = 'http://sports.baofeng.com/match/ranking_integral/' + this.matchID;
            } else {
                tableClassS += ' tow';
                th1S = '<th class="t1">名次</th>';
                th2S = '<th class="t2">球员</th>';
                th3S = '<th class="t3">进球</th>';
                th4S = '';

                moreS = 'http://sports.baofeng.com/match/ranking_topscorer/' + this.matchID;
            }

            //如果不等于当前选中榜单类型索引,则隐藏
            if (i !== this.topIndex) {
                tableClassS += ' hide-display';
            }


            temp += '<div class="top-list' + tableClassS + '">';
            temp += '<table>';
            temp += '<thead>';
            temp += '<tr>';
            temp += th1S;
            temp += th2S;
            temp += th3S;
            temp += th4S;
            temp += '</tr>';
            temp += '</thead>';
            temp += '<tbody>';

            //榜单列表数据
            iArr = data[i];
            jLen = iArr.length;

            for (j = 0; j < jLen; j++) {
                jObj = iArr[j];

                //如果第一名加特殊样式
                top1S = '';
                if (j === 0) top1S = ' class="top-1"';

                //如果前三名加高亮样式
                highlightS = '';
                if (j < 3) highlightS = ' top-highlight';


                // 积分榜:
                //     - team_id - 球队ID
                //     - team_name - 球队名称
                //     - wins - 赢球比赛数
                //     - draws - 平局比赛数
                //     - loses - 输球比赛数
                //     - goals - 总进球数
                //     - goals_conceded - 总输球数
                //     - goals_differential - 净胜球
                //     - points - 积分
                //
                // 射手榜:
                //     - event_id - 赛事ID
                //     - player_id - 球员ID
                //     - player_name - 球员名称
                //     - goals - 进球数

                //第一个和第二个榜单显示内容有点区别
                if (i == 0) {
                    td1S = '<td class="t1' + highlightS + '">' + (j + 1) + '</td>';
                    if (j == 0) {
                        td2S = '<td class="t2"><img src="' + jObj.img + '" alt="' + jObj.team_name + '"><div>' + jObj.team_name + '</div></td>';
                    } else {
                        td2S = '<td class="t2">' + jObj.team_name + '</td>';
                    }
                    td3S = '<td class="t3">' + jObj.wins + '/' + jObj.draws + '/' + jObj.loses + '</td>';
                    td4S = '<td class="t4">' + jObj.points + '</td>';
                } else {
                    td1S = '<td class="t1' + highlightS + '">' + (j + 1) + '</td>';
                    if (j == 0) {
                        td2S = '<td class="t2"><img src="' + jObj.img + '" alt="' + jObj.player_name + '"><div>' + jObj.player_name + '</div></td>';
                    } else {
                        td2S = '<td class="t2">' + jObj.player_name + '</td>';
                    }
                    td3S = '<td class="t3">' + jObj.goals + '</td>';
                    td4S = '';
                }

                temp += '<tr' + top1S + '>';
                temp += td1S;
                temp += td2S;
                temp += td3S;
                temp += td4S;
                temp += '</tr>';
            }
            temp += '</tbody>';
            temp += '</table>';
            temp += '<a href="' + moreS + '" target="_blank">查看更多 》</a>';
            temp += '</div>';
        }

        this.contentJQ.append(temp);
        this.reset();
    };

    //获得数据
    RankingList.prototype.getData = function (id) {
        var data = this.cacheData[id];

        //如果有数据直接渲染模板,否则请求新数据
        if (data && !$.isEmptyObject(data)) {
            this.render(data);
        } else {
            (function (cid, cxt) {

                $.ajax({
                    type: "GET",
                    timeout: 500,
                    url: "http://sports.baofeng.com/api/getTopList/" + cid + "/" + 7,
                    success: function (msg) {

                        var data = $.parseJSON(msg);

                        //                    - 地址： `/api/getTopList`
                        //                    - 方法：`GET`
                        //                    - 参数：
                        // 	- 赛事ID - int 默认为7,中超
                        //                    - 条数 - int 默认为10条
                        //                    - 示例： $event_id=7,$count=10
                        //                        - 示例： `http://sports.baofeng.com/api/getTopList/7/10`
                        //                    - 响应：
                        //
                        // - **status**
                        // - 值：0 - 失败，1 - 成功
                        // - **result**
                        // 两个数据,第一个为积分榜,第二个为射手榜


                        if (data.status == 1 && $.isArray(data.result) && data.result.length) {
                            //如果请求成功并且数据有效,则去除错误处理计时器
                            if (this.errorTimeoutID) {
                                clearTimeout(this.errorTimeoutID);
                                this.errorTimeoutID = undefined;
                            }

                            //存入缓存数组
                            cxt.cacheData[cid] = data.result;

                            //执行回调
                            this.ajaxCallback(data.result);
                        }


                    }.bind(this)
                });


                //异常处理,保证ajax的success没有执行的情况下也执行回调函数,也就是错误情况下的回调函数。没用ajax的error回调是因为它不能包括所有错误情况
                this.errorTimeoutID = setTimeout(function () {
                    if (this && this.errorTimeoutID) this.ajaxCallback(null);
                }.bind(this), 600);

                this.ajaxCallback = function (data) {
                    //如果异步回调函数的赛事id等于当前点击的赛事ID才执行,否则不执行,因为用户已经点击了其他赛事A标签。
                    if (cid === cxt.matchID) {
                        //如果回调函数没有数据，则清空内容
                        //否则用新数据更新内容
                        if (data === null) {
                            cxt.render([[], []]);
                        } else {
                            cxt.render(data);
                        }
                    }
                }.bind(this);

            })(id, this);
        }
    };


    return RankingList;
});