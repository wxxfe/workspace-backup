require.config({
  baseUrl: 'src/scripts'
});

require(['components/chatMobile'],function(chat){

    chat.init();

    //点击登陆弹层上的x
    window.ssoNoticeMessage = function(p){
        var a = decodeURI(p).split("&");
        if(a.shift() == "closeLoginWin"){
            bflogin.closeLoginWin();
        }

    }
});
