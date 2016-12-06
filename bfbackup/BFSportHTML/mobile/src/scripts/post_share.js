require.config({
    baseUrl: 'src/scss'
});

require(['components/libs/fastclick', 
			'components/libs/zepto.min',
			'components/abase/util',
			'components/deviceapi/index',
			'components/toast/index', 
			'components/channel/index',
			'components/sharepage/index'], function (FastClick) {
	SharePageFactory();
	FastClick.attach(document.body);
	ChannelShareFactory();
	
    var downloadtoast = document.querySelector('.downloadtoast'),
        downloadtoastComp;
    downloadtoast && ( downloadtoastComp = ToastFactory({element: downloadtoast}) );

    var buttons = document.querySelectorAll('.downloadtoastshow');
    for (var i = 0, len = buttons.length; i < len; i++) {
        buttons[i].addEventListener('touchend', function () {
            downloadtoastComp.show();
        });
    }
});
