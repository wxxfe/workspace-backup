require.config({
    baseUrl: 'src/scripts'
});

require(['components/bflogin'], function (bflogin) {

    var isLogin = bflogin.isLogin();

    if (!isLogin.status) {
        var iframeAddC = document.createElement("iframe");
        iframeAddC.setAttribute("src", "http://changyan.sohu.com/api/2/logout?client_id=6fd388e82ab57af24b52b801fa559995&callback=C66A5BAD9ED000011E5A1F685821111F");
        iframeAddC.setAttribute("id", "ssoLoginFrameC");
        iframeAddC.style.display = 'none';
        document.body.appendChild(iframeAddC);
    }

    //登陆判断
    bflogin.loginJudge();

    window.miniSSOLogin = bflogin;

    //点击登陆弹层上的x
    window.ssoNoticeMessage = function (p) {
        var a = decodeURI(p).split("&");
        if (a.shift() == "closeLoginWin") {
            bflogin.closeLoginWin();
        }

    }
});
