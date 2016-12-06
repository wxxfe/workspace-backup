/*
* 频率控制 返回函数连续调用时，fn 执行频率限定为每多少时间执行一次
* @param fn {function}  需要调用的函数
* @param delay  {number}    延迟时间，单位毫秒
* @param immediate  {bool} 给 immediate参数传递false 绑定的函数先执行，而不是delay后后执行。
* @return {function}实际调用函数
*/
function throttle(fn,delay, immediate, debounce) {
   var curr = +new Date(),//当前事件
       last_call = 0,
       last_exec = 0,
       timer = null,
       diff, //时间差
       context,//上下文
       args,
       exec = function () {
           last_exec = curr;
           fn.apply(context, args);
       };
   return function () {
       curr= +new Date();
       context = this,
       args = arguments,
       diff = curr - (debounce ? last_call : last_exec) - delay;
       clearTimeout(timer);
       if (debounce) {
           if (immediate) {
               timer = setTimeout(exec, delay);
           } else if (diff >= 0) {
               exec();
           }
       } else {
           if (diff >= 0) {
               exec();
           } else if (immediate) {
               timer = setTimeout(exec, -diff);
           }
       }
       last_call = curr;
   }
};
 
/*
* 空闲控制 返回函数连续调用时，空闲时间必须大于或等于 delay，fn 才会执行
* @param fn {function}  要调用的函数
* @param delay   {number}    空闲时间
* @param immediate  {bool} 给 immediate参数传递false 绑定的函数先执行，而不是delay后后执行。
* @return {function}实际调用函数
*/
 
function debounce(fn, delay, immediate) {
   return throttle(fn, delay, immediate, true);
};

/**
 * 百分数转化成小数，30% -> 0.3
 * @param {Object} percent
 */
function percentToDecimal(percent) {
	return parseFloat(percent.substring(0, percent.length-1))/100;
}

/**
 * 毫秒转化成小数， 300/1000 -> 0.3
 * @param {Object} milliseconds
 * @param {Object} totalMilliseconds
 */
function millisecondsToDecimal(milliseconds, totalMilliseconds) {
	return milliseconds / totalMilliseconds;
}

/**
 * 时长格式转化成毫秒数
 * '05:12' --> 312000
 * @param {Object} timespan  如'05:12', '02:03:12'
 */
function timespanToMilliseconds(timespan) {
	var splits = timespan.split(':'),
		fakeStartTime = new Date('2016/10/12 00:00:00'),
		fakeEndTime,
		result;
	if(splits.length == 2) {
		fakeEndTime = new Date('2016/10/12 00:' + timespan);
		result = fakeEndTime.getTime() - fakeStartTime.getTime(result);
	} else if(splits.length == 3) {
		fakeEndTime = new Date('2016/10/12 ' + timespan);
		result = fakeEndTime.getTime() - fakeStartTime.getTime();
	} else {
//		throw new Error('not supported timespan format')
		result = '00:00'
	}
	return result;
}

/**
 * 毫秒数转化成时长格式
 * @param {Object} milliseconds
 * @param {Object} needHour
 */
function millisecondsToTimespan(milliseconds, needHour) {
	var fakeStartTime = new Date('2016/10/12 00:00:00'),
		fakeEndTime = new Date(fakeStartTime.getTime() + milliseconds),
		timeStr = fakeEndTime.toTimeString().split(' ')[0],
		result;
	
	if(needHour) {
		result = timeStr;
	} else {
		result = timeStr.substring(timeStr.indexOf(':')+1, timeStr.length);
	}
	return result;
}

Utils = {
	convertJsonToUrlParams: function(json) {
		var rslArry = [], rsl = '';
		typeof json === 'string' && (json = JSON.parse(json));
		for(var key in json) {
			rslArry.push(key + '=' + encodeURIComponent(json[key]) + '&');
		}
		if(rslArry.length) {
			rsl = rslArry.join('');
			rsl = rsl.substr(0, rsl.length-1);
		}
		return rsl;
	},
	getUrlParam: function(url, name) {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var sps = url.split('?');
        var r = sps[1] && sps[1].match(reg);
        if(r) return  decodeURIComponent(r[2]); return '';
  },
  browserInfo: {
  	isIos: /(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent),
  	isAndroid: /(Android)/i.test(navigator.userAgent),
  	isWeiXin: /MicroMessenger/i.test(navigator.userAgent),
  	isQQ: /qq\//i.test(navigator.userAgent),
  	isQQWeibo: /txmicroblog/i.test(navigator.userAgent),
    isWeibo: /weibo/i.test(navigator.userAgent),
    iosVersion: (function() {
    		var r = navigator.userAgent.match(/iPhone os ([0-9]+)_/i);
    		if(r) {
    			return parseInt(r[1]);
    		}
    		return -1;
    }()),
    isMobileDevice: (function isMobileDevice(ua){
		if (/(iphone|ios|android|mini|mobile|mobi|Nokia|Symbian|iPod|iPad|Windows\s+Phone|MQQBrowser|wp7|wp8|UCBrowser7|UCWEB|360\s+Aphone\s+Browser|WindowsWechat)/i.test(navigator.userAgent))
		{ 
			return true;
		}
		return false;
	}())
  },
  pageType: (function() {
  	var typeNode = document.querySelector('#page-type'),
    		pageType = typeNode && typeNode.value;
    	return pageType;
  }()),
  isInApp: (function() {
  	var typeNode = document.querySelector('#page-type'),
    		pageType = typeNode && typeNode.value;
  	return pageType === 'app';
  })(),
  loadJs: function(src, callback) {
    var script = document.createElement('script'),
        head;
      var done = false;
      script.src = src;
      script.async = true;

      script.onload = script.onreadystatechange = function() {
        if(!done && (!this.readyState || this.readyState === "loaded" || this.readyState === "complete")) {
          done = true;
          script.onload = script.onreadystatechange = null;
          if(script && script.parentNode) {
            callback && callback()
            
          }
        }
      };
      if(!head) {
        head = document.getElementsByTagName('head')[0];
      }
      head.appendChild(script);
  },
  randomString: function randomString(len) { 
    len = len || 32;
    var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678'; 
    var maxPos = $chars.length;
    var pwd = '';
    for (i = 0; i < len; i++) {
      pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
  }

}

