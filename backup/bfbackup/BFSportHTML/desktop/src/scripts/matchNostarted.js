require.config({
  baseUrl: 'src/scripts'
});

require(['components/chat'],function(chat){

    chat.init(true);

    var initHeight = function(){
        var wh = $(window).height();
        var vh = wh - 185;
        $('#home-block,#guest-block').height(vh-50);
        $('.chat-container').height(vh-50);
    };

    initHeight();

    $(window).on('resize',function(){
        initHeight();
    });

});
