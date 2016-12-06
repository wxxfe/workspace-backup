require.config({
  baseUrl: 'src/scripts'
});
define(['components/localStorage'], function (localStorage) {
    $("body").on("click", function (e) {
        //console.log($("#user-manage").css("display"))
        var userManage = $("#user-manage");
        if (userManage) {
            if (userManage.css("display") != "none" && e.target.id !== "avatar_head") {
                userManage.css("display", "none")
            }
        }
    })
    checkLoginInfo_Interval = null ;
    var miniSSOLogin = {
        layer_id: "sso_login_layer",
        login_id: "sso_login_mini",
        showid: function(h) {
            var b = (document.all) ? true : false;
            var d = b && ([/MSIE (\d)+\.0/i.exec(navigator.userAgent)][0][1] == 6);
            var e = document.getElementById(h);
            e.style.zIndex = "9999";
            e.style.display = "block";
            e.style.position = !d ? "fixed" : "absolute";
            e.style.top = e.style.left = "50%";
            e.style.marginTop = -e.offsetHeight / 2 + "px";
            e.style.marginLeft = -e.offsetWidth / 2 + "px";
            e.style.overflow = "hidden";
            var g = document.createElement("div");
            g.id = this.layer_id;
            g.style.width = g.style.height = "100%";
            g.style.position = !d ? "fixed" : "absolute";
            g.style.top = g.style.left = 0;
            g.style.backgroundColor = "#000";
            g.style.zIndex = "9998";
            g.style.opacity = "0.35";
            document.body.appendChild(g);
            var a = document.getElementsByTagName("select");
            for (var f = 0; f < a.length; f++) {
                a[f].style.visibility = "hidden"
            }
            function j() {
                g.style.width = Math.max(document.documentElement.scrollWidth, document.documentElement.clientWidth) + "px";
                g.style.height = Math.max(document.documentElement.scrollHeight, document.documentElement.clientHeight) + "px"
            }
            function c() {
                e.style.marginTop = document.documentElement.scrollTop - e.offsetHeight / 2 + "px";
                e.style.marginLeft = document.documentElement.scrollLeft - e.offsetWidth / 2 + "px"
            }
            if (b) {
                g.style.filter = "alpha(opacity=60)"
            }
            if (d) {
                j();
                c();
                window.attachEvent("onscroll", function() {
                    c()
                });
                window.attachEvent("onresize", j)
            }
        },
        login: function() {
            var c = location.href;
            var e = c.indexOf("#");
            if (e > -1) {
                c = c.substring(0, e)
            }
            var a = document.createElement("DIV");
            a.id = this.login_id;
            a.style.cssText = "width: 543px; height: 336px; ";
            var b = "http://sso.baofeng.net/api/minilogin/pcweb?from=hd&parent_url=" + encodeURIComponent(c) + "&random=" + Math.random();
            a.innerHTML = '<iframe scrolling="no" width="100%" height="100%"  frameborder="0" src="' + b + '"></iframe>';
            document.body.appendChild(a);
            miniSSOLogin.showid(this.login_id);
            if (checkLoginInfo_Interval) {
                try {
                    window.clearInterval(checkLoginInfo_Interval);
                    checkLoginInfo_Interval = null 
                } catch (d) {
                    checkLoginInfo_Interval = null 
                }
            }
            checkLoginInfo_Interval = window.setInterval(miniSSOLogin.checkLoginInfo, 100);
        },
        register: function() {
            var c = location.href;
            var e = c.indexOf("#");
            if (e > -1) {
                c = c.substring(0, e)
            }
            var a = document.createElement("DIV");
            a.id = this.login_id;
            a.style.cssText = "width: 543px; height: 336px; ";
            var b = "http://sso.baofeng.net/api/minireg/pcweb?from=hd&parent_url=" + encodeURIComponent(c) + "&random=" + Math.random();
            a.innerHTML = '<iframe scrolling="no" width="100%" height="100%"  frameborder="0" src="' + b + '"></iframe>';
            document.body.appendChild(a);
            miniSSOLogin.showid(this.login_id);
            if (checkLoginInfo_Interval) {
                try {
                    window.clearInterval(checkLoginInfo_Interval);
                    checkLoginInfo_Interval = null 
                } catch (d) {
                    checkLoginInfo_Interval = null 
                }
            }
            checkLoginInfo_Interval = window.setInterval(miniSSOLogin.checkLoginInfo, 100)
        },
        checkLoginInfo: function() {
            var b = miniSSOLogin.getCookie("bfuname");
            var c = miniSSOLogin.getCookie("st");
            var a = miniSSOLogin.getCookie("bfcsid");
            var d = miniSSOLogin.getCookie("bfuid");
            if (b != "" && c != "" && a != "") {
                res = {};
                res.status = 1;
                res.info = {};
                res.info.username = b;
                res.info.uid = d;
                miniSSOLogin.successCallback(res);
                miniSSOLogin.closeLoginWin();
                try {
                    window.clearInterval(checkLoginInfo_Interval);
                    checkLoginInfo_Interval = null 
                } catch (d) {
                    checkLoginInfo_Interval = null 
                }
            }
        },
        isLogin: function() {
            var b = miniSSOLogin.getCookie("bfuname");
            var c = miniSSOLogin.getCookie("st");
            var a = miniSSOLogin.getCookie("bfcsid");
            var d = miniSSOLogin.getCookie("bfuid");
            if (b != "" && c != "" && a != "") {
                res = {};
                res.status = 1;
                res.info = {};
                res.info.username = b;
                res.info.uid = d;
            } else {
                res = {};
                res.status = 0;
                res.info = {};
                res.info.username = ""
            }
            return res
        },
        closeLoginWin: function() {
            if (checkLoginInfo_Interval) {
                try {
                    window.clearInterval(checkLoginInfo_Interval);
                    checkLoginInfo_Interval = null 
                } catch (b) {
                    checkLoginInfo_Interval = null 
                }
            }
            if (document.getElementById(this.login_id)) {
                document.body.removeChild(document.getElementById(this.login_id))
            }
            if (document.getElementById(this.layer_id)) {
                document.body.removeChild(document.getElementById(this.layer_id))
            }
            var c = document.getElementsByTagName("select");
            for (var a = 0; a < c.length; a++) {
                c[a].style.visibility = "visible"
            }
        },
        getCookie: function(b) {
            var a, c = new RegExp("(^| )" + b + "=([^;]*)(;|$)");
            if (a = document.cookie.match(c)) {
                return decodeURIComponent(a[2])
            } else {
                return ""
            }
        },
        loginJudge:function(){
            var loginObj = miniSSOLogin.isLogin();
            var loginStatus = miniSSOLogin.isLogin().status;
            if (loginStatus == 0) {
                miniSSOLogin.loginBegin();
            }
            else {
                miniSSOLogin.loginAfter(loginObj);
            }
        },
        loginBegin:function(){
            var loginbtn = $("#head");
            var loginAfter = $("#user-manage");
            loginAfter.hide();
            loginbtn.html('<img id="avatar_head" src="http://static.sports.baofeng.com/production/images/head_deafult.png">');
            loginbtn.off('click').on("click", function (e) {
                miniSSOLogin.login();
            });
        },
        loginAfter:function(info){
            var d = info;
            var loginbtn = $("#head");
            var loginAfter = $("#user-manage");
            loginbtn.off('click').on("click", function (e) {
                loginAfter.toggle();
            });
            $.get('http://sports.baofeng.com/login/sso/' + d.info.uid,function(d){
                var obj = jQuery.parseJSON(d);
                localStorage.set('stoken',obj.token);
            });
            var head = 'http://img.baofeng.net/head/' + d.info.uid.substr(-4,4) + '/' + d.info.uid.substr(-8,4) + '/' + d.info.uid.substr(-12,4) + '/' + d.info.uid + '/100_80_80.jpg?t=' + new Date().getTime();
            $("#username").text(d.info.username);
            $("#head").html('<img id="avatar_head" src="' + head + '">');
            if(document.getElementById("comments")){
                document.getElementById("comments").contentWindow.location.reload();
            }
            //发请求获取登陆后的结果，传参userid；
            $("#exit").on("click",function(e){
                e.preventDefault();
                var iframeAdd = document.createElement("iframe");
                iframeAdd.setAttribute("src", "http://sso.baofeng.net/api/server/logout?from=sanguo&version=1.0");
                iframeAdd.setAttribute("id", "ssoLoginFrame");
                iframeAdd.style.display = 'none';
                document.body.appendChild(iframeAdd);
                if (iframeAdd.attachEvent){
                    iframeAdd.attachEvent("onload", function(){
                        var loginObj = miniSSOLogin.isLogin();
                        var loginStatus = miniSSOLogin.isLogin().status;
                        if (loginStatus == 0) {
                            miniSSOLogin.loginBegin();
                            $("#ssoLoginFrame").remove();
                            if(document.getElementById("comments")){
                                document.getElementById("comments").src = 'comments.html'
                            }
                        }
                        localStorage.remove('isReload');
                        window.location.reload();
                    });
                } else {
                    iframeAdd.onload = function(){
                        var loginObj = miniSSOLogin.isLogin();
                        var loginStatus = miniSSOLogin.isLogin().status;
                        if (loginStatus == 0) {
                            miniSSOLogin.loginBegin();
                            $("#ssoLoginFrame").remove();
                            if(document.getElementById("comments")){
                                document.getElementById("comments").src = 'comments.html'
                            }
                        }
                        localStorage.remove('isReload');
                        window.location.reload();
                    };
                }
            });
            var isReload = localStorage.get('isReload');
            if(isReload){
            }
            else{
                localStorage.set('isReload',"true");
                window.location.reload();
            }
        },
        //在评论页面需重写下面方法，以便同步登陆
        successCallback : function(info){
            miniSSOLogin.loginAfter(info);
        },
        //评论页面退出登陆方式
        comments:function(){
            var iframeAddC = document.createElement("iframe");
            iframeAddC.setAttribute("src", "http://changyan.sohu.com/api/2/logout?client_id=6fd388e82ab57af24b52b801fa559995&callback=C66A5BAD9ED000011E5A1F685821111F");
            iframeAddC.setAttribute("id", "ssoLoginFrameC");
            iframeAddC.style.display = 'none';
            document.body.appendChild(iframeAddC);
            if (iframeAddC.attachEvent){
                iframeAddC.attachEvent("onload", function(){
                    var loginObj = miniSSOLogin.isLogin();
                    var loginStatus = miniSSOLogin.isLogin().status;
                    if (loginStatus == 0) {
                        miniSSOLogin.loginBegin();
                        $("#ssoLoginFrameC").remove();
                        localStorage.remove('isReload');
                        window.location.reload();
                    }
                });
            } else {
                iframeAddC.onload = function(){
                    var loginObj = miniSSOLogin.isLogin();
                    var loginStatus = miniSSOLogin.isLogin().status;
                    if (loginStatus == 0) {
                        miniSSOLogin.loginBegin();
                        $("#ssoLoginFrameC").remove();
                        localStorage.remove('isReload');
                        window.location.reload();
                    }
                }
            }
        }
    };
    //刷新并尽量修改常言的
    window.onload = function(){
        var loginStatus = miniSSOLogin.isLogin().status;
        if(0 !== loginStatus){
            $(".post-login-w").css("display","none");
            $(".user-wrap-w").css("display","block");
            $(".user-wrap-w").find(".wrap-name-w").text(miniSSOLogin.getCookie("bfuname"))
        }
        $(".menu-box-w").find("a:eq(1)").on("click",function(e){
            $("#exit").click();
        });
    }
    return miniSSOLogin;
});
