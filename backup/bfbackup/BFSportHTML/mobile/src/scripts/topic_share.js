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
    'components/tab/index',
    'components/toast/index',
    'components/channel/index',
    'components/sharepage/index',
    'components/page/index'
], function (FastClick) {

    SharePageFactory();

    FastClick.attach(document.body);

    ChannelShareFactory();

    var tabs = document.querySelectorAll('.tab');
    for (var i = 0, len = tabs.length; i < len; i++) {
        var ta = tabs[i],
            type = 'default';
        if (ta.classList.contains('slide')) {
            type = 'slide'
        }
        TabFactory({element: tabs[i], type: type})
    }

    var downloadtoast = document.querySelector('.downloadtoast'),
        downloadtoastComp;
    downloadtoast && ( downloadtoastComp = ToastFactory({element: downloadtoast}) );

    var buttons = document.querySelectorAll('.downloadtoastshow');
    for (var i = 0, len = buttons.length; i < len; i++) {
        buttons[i].addEventListener('touchend', function () {
            downloadtoastComp.show();
        });
    }

    var list = document.querySelector('.list');
    list && ListFactory({element: list});

});
