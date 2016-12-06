function SharePage() {
    this.init();
    return this;
}

SharePage.fn = SharePage.prototype;

SharePage.fn.init = function () {
	var _self = this;
    var sps, appurl;
    appurl = (sps = window.location.href.split('__appurl=') ) && sps[1]; // 注意以后分享后的页面加别的参数可能会有问题
    	if(appurl) {
    		DeviceApi.openApp(appurl);
    	}
}

function SharePageFactory() {
    return new SharePage();
}
