define(function(){
	/*browser  version*/
	var getBrowser = function () {
		var browserVer = '',
			ua = navigator.userAgent.toLowerCase();
		if (ua.indexOf("msie 6.0") > -1) {
			browserVer = "IE6";
		} else if (ua.indexOf("msie 7.0") > -1) {
			browserVer = "IE7";
		} else if (ua.indexOf("msie 8.0") > -1) {
			browserVer = "IE8";
		} else if (ua.indexOf("msie 9.0") > -1) {
			browserVer = "IE9";
		} else if (ua.indexOf("msie 10.0") > -1) {
			browserVer = "IE10";
		} else if (ua.indexOf("trident") > -1 && ua.indexOf("rv") > -1) {
			browserVer = "IE11";
		} else if (ua.indexOf("firefox") > -1) {
			browserVer = "firefox";
		} else if (ua.indexOf("chrome") > -1) {
			browserVer = "chrome";
		} else {
			browserVer = "other";
		}
		return browserVer;
	};
	/*get domain channel*/
	function getChannel() {
		var channel = "",
			dm = "sports.baofeng.com/channel";
		var url = window.location.href;
		url = url.replace("http://", "");
		if (url.indexOf(dm) > -1) {
			var ulist = url.split("/");
			channel = ulist[2];
		} else {
			url = url.replace("/", "");
			if (url == "sports.baofeng.com") {
				channel = "index";
			}
		}
		return channel;
	}

	/*sent log*/
	function sentLog(json) {
		var browser = getBrowser();
		var adtype = json.adtype;
		var channel = getChannel();
		var msg = '{';
		msg += '"browser":"' + browser + '",';
		msg += '"adtype":"' + adtype + '",';
		msg += '"channel":"' + channel + '",';
		msg += '"ecode":"0",';
		msg += '"type":"1"';
		msg += '}';
		var url = 'http://log.houyi.baofeng.net/logger.php?ltype=sports_ad&json=' + msg;
		var d = new Image(1, 1);
		d.src = url + "&r=" + Math.random();
		d.onload = function () {
			d.onload = null;
		}
	}

	/*create script*/
	function createScript(url, json) {
		var adsript = document.createElement("script");
		adsript.src = url;
		var s = document.getElementsByTagName("script")[0];
		s.parentNode.insertBefore(adsript, s);
		sentLog(json);
	}

	/*
	 ad place id to number log adtype!
	 sports_schedulename :3
	 sports_listname:4
	 sports_banner:1
	 http://web.houyi.baofeng.net/Consultation/web.php?id=sports_schedulename&sports_channel=csl
	 */
    return function(){
		var channel = currentChannel,
			url1 = "http://web.houyi.baofeng.net/Consultation/web.php?id=sports_schedulename&sports_channel=" + channel,
			url2 = "http://web.houyi.baofeng.net/Consultation/web.php?id=sports_listname&sports_channel=" + channel,
			url3 = "http://web.houyi.baofeng.net/Consultation/web.php?id=sports_banner&sports_channel=" + channel;
		createScript(url1, {
			adtype: "3"
		});
		createScript(url2, {
			adtype: "4"
		});
		createScript(url3, {
			adtype: "1"
		});
	}

});
