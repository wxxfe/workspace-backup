/**
 * @des: 比赛直播限时聊天
 * @author: minghui
 * @example: object.init()
 */
require.config({
  baseUrl: 'src/scripts'
});

define(['components/bflogin','components/socket','components/localStorage','lib/base64.min','lib/xss'], function (bflogin,socket,localStorage,Base64,xss){

    return {

        objs : {
            //比赛开始时间
            matchStartTime : $('#match-start-time'),
            //比分
            matchScore : $('#match-score'),
            //输入框
            sendBox : $('#send-msg-box'),
            //发送按钮
            sendButton : $('#send-msg-button'),
            //主队信息容器
            homeBlock : $('#home-block'),
            //客队信息容器
            guestBlock : $('#guest-block'),
            //快捷消息按钮
            quickMsgBtn : $('#quick-msg-button'),
            //表情按钮
            quickEmojiBtn : $('#quick-emoji-button'),
            //选择支持球队层
            supportPopup : $('#select-popup'),
            //遮罩层
            shadowLayer : $('#shadow-layer'),
            //主队隐藏域
            homeTeamBox : $('#home-team'),
            //客队隐藏域
            guestTeamBox : $('#guest-team'),

            //选择支持的球队logo
            teamLogoButton : $('#current-team-logo'),

            //主队赞的数量
            homeSupportNum : $('#home-zan'),
            //客队赞的数量
            guestSupportNum : $('#guest-zan'),
            //主队赞的进度条
            homeProgress : $('#home-progress'),
            //客队赞的进度条
            guestProgress : $('#guest-progress'),
            //主队赞
            homeSupportBtn : $('#home-zan-btn'),
            //客队赞
            guestSupportBtn : $('#guest-zan-btn'),

            //比赛ID隐藏域
            matchInfoBox : $('#match-info'),

            //快捷消息按钮
            quickMsgBtn : $('#quick-msg-button'),
            //快捷短消息列表
            quickMsgList : $('#quick-msg-list-wrap'),
            //表情按钮
            emojiBtn : $('#quick-emoji-button'),
            //表情列表
            emojiList : $('#quick-msg-emoji'),
            //弹幕按钮
            danmakuBtn : $('#danmaku-button')
        },
        
        //球队信息
        //{232 : 'home',2344 : 'guest'}
        team : {},

        //当前选择的支持的球队ID
        selectedTeam : '',

        //当前比赛的ID
        matchId : null,

        //已登录用户的用户信息
        //{id : 2323,name : 'xxx',avatar : 'http://xxx.com/xx.jpg'}
        user : null,

        ws : null,

        heartbeatInterval : null,

        missedHeartbeats : 0,

        showScroll : false,

        init : function(showScroll){

            this.showScroll = showScroll || false;

            this.debug(false);

            //登陆判断
            bflogin.loginJudge();
            //点击登陆弹层上的x
            window.ssoNoticeMessage = function(p){
                var a = decodeURI(p).split("&");
                if(a.shift() == "closeLoginWin"){
                    bflogin.closeLoginWin();
                }

            }

            if(!this.checkWebSocket()){
                this.showComment();
                return false;
            }


            this.team[this.objs.homeTeamBox.val()] = 'home';
            this.team[this.objs.guestTeamBox.val()] = 'guest';

            this.matchId = this.objs.matchInfoBox.val();

            this.setScrollPosition();
            this.bindEvents();
            this.connect();
            this.getHistoryMessages(this.matchId);

        },

        debug : function(d){
            if(!d){
                window.console = {
                    log : function(){}
                };
            }
        },

        /**
         * 获取服务器列表并连接WebSocket
         */
        connect : function(){

            var that = this;

            var url = 'http://rt.sports.baofeng.com/api/v1/rtservers';

            $.ajax({
                method : 'GET',
                url : url,
                data : {transport : 'websocket'}
            }).done(function(serverList){
                var jsonObj = typeof serverList === 'object' ? serverList : jQuery.parseJSON(serverList);
                var servers = jsonObj.data.servers;
                //var servers = ['192.168.0.1','192.168.0.2','192.168.0.3'];
                var i = 0,st = servers.length,tryTotal = 0;
                var connectError = false;

                var connectTry = function(index){
                    if(i < st){
                        var sUrl = 'ws://' + servers[index] + '/api/v1/subscribe?id=' + that.matchId;
                        that.ws = new socket({
                            url : sUrl,
                            onOpen : function(e){
                                console.log('open');
                                that.heartbeat();
                            },
                            onMessage : function(e){
                                that.showMessage(e.data);
                            },
                            onError : function(e){
                               i++;
                               connectError = true;
                               connectTry(i);
                                console.log('error');
                            },
                            onClose : function(e){
                                console.log('close');
                                if(!connectError) connectTry(i);
                            }
                        });
                    }else{
                        if(tryTotal < 2){
                            i = 0;
                            tryTotal++;
                            setTimeout(function(){
                                connectTry(i);
                            },5000);
                        }else{
                            alert('服务器连接失败！');
                        }
                    }
                }

                connectTry(i);

            });

        },

        /**
         * 每隔60妙发送心跳检测
         * Ping
         */
        heartbeat : function(){
            var that = this;
            if(this.heartbeatInterval === null){
                this.missedHeartbeats = 0;
                this.heartbeatInterval = setInterval(function(){
                    try{
                        that.missedHeartbeats++;
                        if(that.missedHeartbeats >= 3){
                            throw new Error('Too many missed heartbeats.');
                        }
                        that.ws.send('Ping');
                    }catch(e){
                        clearInterval(that.heartbeatInterval);
                        that.heartbeatInterval = null;
                        that.ws.close();
                    }
                },1000 * 60);
            }
        },

        /**
         * Pong
         */
        heartbestMessage : function(msg){
            this.missedHeartbeats = 0;
        },

        /**
         * 检测浏览器是否支持WebSocket
         */
        checkWebSocket : function(){

            return window.WebSocket;

        },

        /**
         * 检测用户是否登录
         */
        checkLogin : function(){

            var userName = this.getCookie('bfuname');
            var st = this.getCookie('st');
            var csid = this.getCookie('bfcsid');

            return (userName == '' && st == '' && csid == '');

        },

        /**
         * 检测是否选择了支持的球队
         */
        checkSupport : function(){

            if(this.selectedTeam == ''){
                this.objs.supportPopup.show();
                this.objs.shadowLayer.show();
                return false;
            }else{
                return true;
            }

        },

        /**
         * 根据用户ID获取用户头像
         */
        getUserAvatar : function(uid){

            return 'http://img.baofeng.net/head/' + uid.substr(-4,4) + '/' + uid.substr(-8,4) + '/' + uid.substr(-12,4) + '/' + uid + '/100_80_80.jpg?t=' + new Date().getTime();

        },

        /**
         * 检测当前状态，用于判断是否可以发消息
         */
        checkStatus : function(){

            var isLogin = this.checkLogin();

            if(isLogin){
                bflogin.login();
                return false;
            }else{
                this.user = {
                    id : this.getCookie('bfuid'),
                    name : this.getCookie('bfuname'),
                    avatar : this.getUserAvatar(this.getCookie('bfuid'))
                };
                if(this.checkSupport()){
                    return true;
                }else{
                    return false;
                }
            }

        },

        /**
         * 浏览器不支持WebSocket的情况下，显示评论
         */
        showComment : function(){

            $('#match-score').hide().next().css('line-height','60px');
            $('.quick-msg:not(:first)').remove();
            $('.send-box').removeClass('col-6').addClass('col-10').html('<span style="color: red;">您的浏览器版本太低！请升级您的浏览器，边看比赛边支持你的球队！推荐您使用 <a style="color: yellow;" href="http://rj.baidu.com/soft/detail/14744.html?ald" target="_blank">Chrome浏览器</a>，以获得最佳体验！</span>');
        },

        /**
         * 绑定事件
         */
        bindEvents : function(){

            this.objs.sendBox.on('focus',this,this._onFocusActive);

            this.objs.sendButton.on('click',this,this._onSendActive);

            this.objs.homeSupportBtn.on('click',this,this._onSupportActive);

            this.objs.guestSupportBtn.on('click',this,this._onSupportActive);

            this.objs.teamLogoButton.on('click',this,this._onToggleSelectTeamLayer);

            this.objs.supportPopup.on('click','a',this,this._onSelectTeamActive);

            this.objs.quickMsgBtn.on('click',this,this._onToggleLayerActive);

            this.objs.emojiBtn.on('click',this,this._onToggleLayerActive);

            this.objs.quickMsgList.on('click','li a',this,this._onSendQuickActive);

            this.objs.emojiList.on('click','li a',this,this._onSendEmojiActive);

            this.objs.danmakuBtn.on('click',this,this._onToggleDanmakuActive);

            $(document).on('keyup',this,this._onEnterActive);

        },

        getHistoryMessages : function(matchId){
            var that = this;
            $.get('http://r.rt.sports.baofeng.com/api/v1/history',{id : matchId},function(history){
                var messages = history.data.history;
                var l = messages.length;
                for(var i=0; i<l; i++){
                    that.chatMessage({data : messages[i]});
                }
            });

        },

        /**
         * 根据推送的数据类型，显示不同的数据
         * @param string data
         */
        showMessage : function(data){
            var message = jQuery.parseJSON(data);
            console.log(message);
            var type = message.type;
            switch(type){

                case 100 : 
                    this.chatMessage(message);
                    break;

                case 101 : 
                    this.supportMessage(message);
                    break;

                case 102 :
                    this.scoreMessage(message);
                    break;

                case 200 :
                    this.heartbestMessage(message);
                    break;

            }
        },

        /**
         * 显示即时聊天消息
         * @param object msg
         */
        chatMessage : function(msg){
            var msgBody = msg.data;
            var teamBlock = $('#' + this.team[msgBody.user.favor] + '-block');
            var messageHtml = '';
            if(this.team[msgBody.user.favor] == 'home'){
                messageHtml += '<p class="message">';

                if(msgBody.emoji > 0){
                    messageHtml += '            <img src="http://static.sports.baofeng.com/emoji/' + msgBody.emoji + '.gif" alt="" />';
                }else{
                    messageHtml += '            ' + msgBody.text;
                }

                messageHtml += '</p>';
            }else{
                messageHtml += '<p class="message message-blue">';
                if(msgBody.emoji > 0){
                    messageHtml += '            <img src="http://static.sports.baofeng.com/emoji/' + msgBody.emoji + '.gif" alt="" />';
                }else{
                    messageHtml += '            ' + msgBody.text;
                }

                messageHtml += '</p>';
            }
            $(messageHtml).appendTo(teamBlock);
            this.setScrollPosition();
        },

        /**
         * 显示点赞消息
         * @param object msg
         */
        supportMessage : function(msg){

            var supportData = msg.data.likes;
            var homeNum = supportData[this.objs.homeTeamBox.val()];
            var guestNum = supportData[this.objs.guestTeamBox.val()];
            this.objs.homeSupportNum.text(homeNum);
            this.objs.guestSupportNum.text(guestNum);

            var homeProgressNum = (homeNum / (homeNum + guestNum) * 100) + '%';
            var guestProgressNum = (guestNum / (homeNum + guestNum) * 100) + '%';

            this.objs.homeProgress.css({width : homeProgressNum});
            this.objs.guestProgress.css({width : guestProgressNum});

        },

        /**
         * 显示比分消息
         * @param object msg
         */
        scoreMessage : function(msg){
            var scoreData = msg.data.score;

            var homeScore = scoreData[this.objs.homeTeamBox.val()]; 
            var guestScore = scoreData[this.objs.guestTeamBox.val()]; 

            this.objs.matchScore.text(homeScore + ' : ' + guestScore);

            if(this.objs.matchScore.css('display') == 'none'){
                this.objs.matchStartTime.hide();
                this.objs.matchScore.show();
            }
        },

        /**
         * 发送信息的公共方法
         * @param object data
         */
        sendMessage : function(data,login){

            if(login){
                var token = localStorage.get('stoken');
                var uToken = Base64.encode(token + ':' + this.user.id);
                var url = 'http://w.rt.sports.baofeng.com/api/v1/commit?user=' + window.encodeURIComponent(uToken);
            }else{
                var url = 'http://w.rt.sports.baofeng.com/api/v1/commit';
            }
            $.ajax({
                method : 'POST',
                url : url,
                data : this.stringify(data)
            }).done(function(d){
                console.log(d);
            });

        },

        /**
         * 设置滚动条始终在底部
         */
        setScrollPosition : function(){

            if(!this.showScroll){
                if(this.objs.homeBlock.height() + 30 < this.objs.homeBlock[0].scrollHeight){
                    this.objs.homeBlock.find('p:first-child').fadeOut('slow',function(){$(this).remove()});
                }
                if(this.objs.guestBlock.height() + 30 < this.objs.guestBlock[0].scrollHeight){
                    this.objs.guestBlock.children('p:first-child').fadeOut('slow',function(){$(this).remove()});
                }
            }

            this.objs.homeBlock.scrollTop(this.objs.homeBlock[0].scrollHeight);

            this.objs.guestBlock.scrollTop(this.objs.guestBlock[0].scrollHeight);

        },

        /**
         * 获取浏览器Cookie信息
         */
        getCookie : function(b) {
            var a, c = new RegExp("(^| )" + b + "=([^;]*)(;|$)");
            if (a = document.cookie.match(c)) {
                return decodeURIComponent(a[2])
            } else {
                return ""
            }
        },

        /**
         * JSON to String
         */
        stringify : function stringify(obj) {         
            if ("JSON" in window) {
                return JSON.stringify(obj);
            }

            var t = typeof (obj);
            if (t != "object" || obj === null) {
                // simple data type
                if (t == "string") obj = '"' + obj + '"';

                return String(obj);
            } else {
                // recurse array or object
                var n, v, json = [], arr = (obj && obj.constructor == Array);

                for (n in obj) {
                    v = obj[n];
                    t = typeof(v);
                    if (obj.hasOwnProperty(n)) {
                        if (t == "string") {
                            v = '"' + v + '"';
                        } else if (t == "object" && v !== null){
                            v = jQuery.stringify(v);
                        }

                        json.push((arr ? "" : '"' + n + '":') + String(v));
                    }
                }

                return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
            }
        },

        /**
         * 消息框获得焦点后的行为
         */
        _onFocusActive : function(event){
            var that = event.data;
            var status = that.checkStatus();
            if(!status) return false;
        },

        /**
         * 选择支持的球队的行为
         */
        _onSelectTeamActive : function(event){
            var that = event.data;
            var teamId = $(this).data('tid');
            var teamType = $(this).data('type');
            that.selectedTeam = teamId;
            that.selectedTeamType = teamType;
            that.objs.supportPopup.hide();
            that.objs.shadowLayer.hide();
            $('.support-mark').hide();
            //$('.mark-' + teamType).show();

            that.objs.teamLogoButton.attr('src',$('#logo-' + teamId).attr('src')).css('visibility', 'visible');

        },

        /**
         * 发送聊天消息的行为
         */
        _onSendActive : function(event){
            var that = event.data;
            var emoji = event.emoji ? event.emoji : 0;
            var status = that.checkStatus();
            if(!status) return false;

            var message = that.objs.sendBox.val();
            if(message == '' && emoji == 0){
                alert('请输入信息！');
                return false;
            }
            var msgData = {
                type : 2,
                version : 1,
                data : {
                    match : that.matchId,
                    text : filterXSS(message),
                    emoji : emoji,
                    user : {
                        id : that.user.id,
                        name : that.user.name,
                        avatar : that.user.avatar,
                        favor : that.selectedTeam
                    }
                }
            };

            that.sendMessage(msgData,true);
            that.objs.sendBox.val('');
        },

        /**
         * 点赞的行为
         */
        _onSupportActive : function(event){

            var that = event.data;

            var teamId = $(this).data('tid');

            var isSupport = that.getSupport(that.matchId);

            if(isSupport){
                var oldValue = isSupport.split(',');

                if($.inArray('' + teamId,oldValue) > -1){
                    alert('您已经支持过了!');
                    return false;
                }else{
                    oldValue.push(teamId);
                    that.setSupport(that.matchId,oldValue.join(','));
                }
            }else{
                that.setSupport(that.matchId,teamId);
            }

            var homeNum = that.objs.homeSupportNum.text();
            var guestNum = that.objs.guestSupportNum.text();
            var home = that.objs.homeTeamBox.val();
            var guest = that.objs.guestTeamBox.val();

            var teams = [];
            for(t in that.team){
                teams.push(t);
            }

            var showData = {
                type : 101,
                version : 1,
                data : {
                    likes : {
                    }
                }
            };
            showData.data.likes[home] = parseInt(homeNum);
            showData.data.likes[guest] = parseInt(guestNum);

            if(teamId == home){
                showData.data.likes[home] = parseInt(homeNum) + 1;
            }else{
                showData.data.likes[guest] = parseInt(guestNum) + 1;
            }

            that.supportMessage(showData);

            var msgData = {
                type : 6,
                version : 1,
                data : {
                    match : that.matchId,
                    likes : teamId,
                }
            };

            that.sendMessage(msgData,false);

        },

        /**
         * 点击快捷消息按钮和表情按钮的行为
         */
        _onToggleLayerActive : function(event){
            var that = event.data;
            if($(this).next().css('display') == 'block'){
                $(this).next().hide();
            }else{
                $('.quick-msg div').hide();
                $(this).next().show();
            }
            var layer = $(this).next().find('ul');
            if($('li',layer).size() == 0){
                if($(this).hasClass('qe-btn')){
                    $.get('http://static.sports.baofeng.com/emoji/list',function(data){
                        var emojis = '';
                        $(data).each(function(){
                            emojis += '<li><a href="javascript:void(0)" id="'+ this.id +'"><img src="'+ this.url +'" alt=""></a></li>';
                        });
                        layer.html(emojis);
                    });
                }else{
                    $.get('http://static.sports.baofeng.com/words/list',function(data){
                        var words = '';
                        $(data).each(function(){
                            words += '<li><a href="javascript:void(0)">'+ this +'</a></li>';
                        });
                        layer.html(words);
                    });
                }
            }
        },

        /**
         * 发送快捷消息的行为
         */
        _onSendQuickActive : function(event){
            var that = event.data;
            var message = $(this).text();
            that.objs.sendBox.val(message);
            that._onSendActive({data : that});
            that.objs.quickMsgList.hide();
        },

        /**
         * 发送表情的行为
         */
        _onSendEmojiActive : function(event){
            var that = event.data;
            var index = this.id;
            that._onSendActive({data : that,emoji : index});
            that.objs.emojiList.hide();
        },

        _onEnterActive : function(event){
            var that = event.data;
            var code = event.keyCode;
            if(code == 13){
                that._onSendActive({data : that});
            }
        },
        _onToggleSelectTeamLayer : function(event){
            var that = event.data;
            that.objs.supportPopup.show();
            that.objs.shadowLayer.show();
            return false;
        },
        _onToggleDanmakuActive : function(event){
            var that = event.data;
            var isShow = $('.chat-container').css('display');

            if(isShow == 'block'){
                $(this).removeClass().addClass('dan-btn-gray');
                $('.chat-container').hide();
                $(this).parent().prevAll().hide();
                $(this).parent().siblings('.foot-logo').show();
                $(this).parent().addClass('col-offset-9');
            }else{
                $(this).removeClass().addClass('dan-btn');
                $('.chat-container').show();
                $(this).parent().removeClass('col-offset-9');
                $(this).parent().prevAll().show();
            }
        },

        getSupport : function(matchID){

            return localStorage.get('match_' + matchID);

        },

        setSupport : function(matchID,newValue){

            localStorage.set('match_' + matchID,newValue);

        }

    }

});
