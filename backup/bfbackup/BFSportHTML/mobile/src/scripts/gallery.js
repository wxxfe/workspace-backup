require.config({
    baseUrl: 'src/scss'
});

require([
    'components/libs/fastclick',
    'components/libs/zepto.min',
    'components/abase/util',
    'components/abase/index',
    'components/deviceapi/index',
    'components/listitem/index',
    'components/list/index',
    'components/article/index',
    'components/button/index',
    'components/channel/index',
    'components/sharepage/index',
    'components/page/index'
], function (FastClick) {
    SharePageFactory();

    FastClick.attach(document.body);

    

    ChannelShareFactory();

    ArticleFactory({element: document.querySelector('.article'), enableShowMore: true, firstShowParagraphNums: 1});

    var buttons = document.querySelectorAll('.button');
    for (var i = 0, len = buttons.length; i < len; i++) {
        ButtonFactory({element: buttons[i]})
    }

    var list = document.querySelector('.list');
    list && ListFactory({element: list});

});
