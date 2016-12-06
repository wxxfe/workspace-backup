define(function ($) {
	function metaActive(pixel){
		var web_width = pixel;
		var inner_width = window.innerWidth;
		var scale = inner_width/web_width;
		var $viewportMate = document.getElementById("viewport");
		var mate_value;
		if (/android/i.test(navigator.userAgent)) {
			var userAgent = navigator.userAgent;
			var index = userAgent.indexOf("AppleWebKit");
			if(index >= 0){
				var androidAppleWebKitVersionAll = parseFloat(userAgent.slice(index+11,index+17)).toFixed(2);
			}
			else{}
			var androidAppleWebKitVersion = navigator.userAgent.match(/Android[\S\s]+AppleWebkit\/?(\d{3})/i);
			if(androidAppleWebKitVersionAll <= 534.30 || androidAppleWebKitVersion[1] <= 534){
				mate_value = 'width='+web_width+', minimum-scale = '+scale+', maximum-scale = '+scale+', target-densitydpi=device-dpi';
				$viewportMate.setAttribute('content', mate_value);
			}
			else{
				mate_value = 'width='+web_width+',user-scalable=no,target-densitydpi=device-dpi';
				$viewportMate.setAttribute('content', mate_value);
			}
		}
		else if(/ipad|iphone|mac/i.test(navigator.userAgent)){
			//alert("1:"+web_width);
			mate_value = 'width='+web_width+',user-scalable=no,target-densitydpi=device-dpi';
			$viewportMate.setAttribute('content', mate_value);
			//alert($viewportMate.getAttribute('content'))
			if(window.innerWidth < 750){
				mate_value = 'width='+web_width+', minimum-scale = '+scale+', maximum-scale = '+scale+',user-scalable=no,target-densitydpi=device-dpi';
				$viewportMate.setAttribute('content', mate_value);
			}
		}
		else{
			mate_value = 'width='+web_width+',user-scalable=no,target-densitydpi=device-dpi';
			$viewportMate.setAttribute('content', mate_value);
		}
	}
	return metaActive
});


