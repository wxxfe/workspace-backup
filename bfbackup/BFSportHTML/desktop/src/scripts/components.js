require.config({
  baseUrl: '../src/scripts'
});

require(['components/navigation'],function(navigation){

    navigation.init();

    $('.news-mixins-list a').hover(function () {
        $('.news-mixins-list a').removeClass();
        $(this).addClass('active');
    });

});
