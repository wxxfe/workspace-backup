require.config({
  baseUrl: 'src/scripts'
});

require(['components/chat'],function(chat){

    chat.init();

    var initHeight = function(){
        var wh = $(window).height();
        var vh = wh - 245;
        if(vh < 320) vh = 370;
        var vw = parseInt((16/9) * vh);
        //if(vw > 1200) vw = 1200;
        $('.live-b object,embed').prop('height',vh);
        $('.live-b object,embed').prop('width',vw);
        if(vw >= 700){
            $('.match-live-bar,.pk-progress-wrap').css('width',vw);
        }else{
            $('.match-live-bar,.pk-progress-wrap').css('width',850);
        }
        $('.live').css('width',vw);
        $('#home-block,#guest-block').height(vh-70);
        $('.chat-container').height(vh-70);
        $('.chat-container,.tv-bar').width(vw);
    };
    $(window).on('resize',function(){
        initHeight();
    });

    $(function(){
        setTimeout(function(){
            initHeight();
        },500);
    })


});
