require.config({
	baseUrl: 'src/scripts'
});

require(['components/bflogin'], function (bflogin) {
    //登陆判断
    if(parent.document.getElementById("login_after").style.display == 'none'){
        bflogin.comments();
    }
    window.onload = function() {
        $("#list_sohu").on("click",".click-reply-eg",function(){
            setTimeout(function(){
                $(".btn-fw-main").on("click",function(){
                    setTimeout(function(){
                        $(".sohucs-ui-dialog").css({"width":"200px","margin":"0 auto","left":"62px"})
                    },200)
                });
            },200)

        });
    };
});
