require.config({
  baseUrl: 'src/scripts'
});

require(['components/bflogin'],function(bflogin){

    //视频选择播放
    var matchvideoList = $("#match-video-list");
    var video = $("#video");

    matchvideoList.on("click","a",function(event){
      var pointers = matchvideoList.find("a");
      var pointersLength =  pointers.size();
      for(var i = 0;i < pointersLength; i++){
          pointers[i].className = '';
      }
      this.className = "active";
      video.html(this.name);
    });
    //登陆判断
    bflogin.loginJudge();

    //点击登陆弹层上的x
    window.ssoNoticeMessage = function(p){
        var a = decodeURI(p).split("&");
        if(a.shift() == "closeLoginWin"){
            bflogin.closeLoginWin();
        }

    }
});
