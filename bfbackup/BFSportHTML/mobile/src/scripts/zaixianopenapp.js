require.config({
    baseUrl: 'src/scss/'
});

require(['components/libs/zepto.min', 
			'components/abase/util',
			'components/deviceapi/index'], function () {
    /* ua检测 */
    var ua = navigator.userAgent.toLowerCase();
    var isWeiXin = Utils.browserInfo.isWeiXin;
    var isIos = Utils.browserInfo.isIos;
    var isAndroid = Utils.browserInfo.isAndroid;
	
	DeviceApi.openApp(DeviceApi.APP_SCHEMA);
    if(isWeiXin && isIos) {
    		document.querySelector('#weixinguide').style.display = 'block';
    } 
    else if(!isWeiXin && isAndroid) {
    		setTimeout(function() {
    			var r = window.confirm('您还没有下载APP，是否下载APP？');
    			if(r) {
    				DeviceApi.downloadApp();
    			}
    		}, 1500)
    } 
    else {
    		DeviceApi.redirectToDownloadApp();
    }
	
	
});
