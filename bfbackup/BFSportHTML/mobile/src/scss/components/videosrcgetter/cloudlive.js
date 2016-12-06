CloudLiveSrcGetter = {
	getVideoUrlFromBFCloud: function(sSrc) {
		var defer = $.Deferred();
		var _self = this;

		var data, json, search, arrs;
		//	search = filterXSS(sSrc);
		search = sSrc;

		if(search) {
			json = {};
			data = search.split("&");

			for(var i = 0, l = data.length; i < l; i++) {
				arrs = data[i].split("=");
				json[arrs[0]] = arrs[1];
			}
		}
		//	if(typeof(json['auto'])!='undefined' && json['auto']==1){
		//		var sisautoplay='autoplay="autoplay"';
		//		if(IS_QQBROWER){sisautoplay=''}
		//	}else{
		//		var sisautoplay='';
		//	}

		var b = new Base64();
		json['vk'] = 'servicetype=' + json['servicetype'] + '&uid=' + json['uid'] + '&fid=' + json['fid'];
		//	json['width']=ajson.width;
		//	json['height']=ajson.height;
		//	json['isautosize']=ajson.isautosize;
		var jmi = b.encode(json['vk']);
		if(json['tk']) {
			jmi += '?tk=' + json['tk'] + "&ifhtml5=true";
		} else {
			jmi += "?ifhtml5=true";
		}

		$.ajax({
			type: "GET",
			url: "http://livequery.baofengcloud.com/" + jmi,
			data: '',
			async: false,
			dataType: "json",
			success: function(ajson) {
				if(parseInt(ajson.status) != 0) {
					alert('获取云视频地址失败，代码：' + ajson.status);
					defer.reject();
				} else {
					var posterurl = '';
					if(ajson.headpicurl.length > 0) {
						posterurl = ajson.headpicurl;
					}
					var comcdnflag = ajson.usecomcdnflag;
					var videourl = ajson.gcids[0]['urllist'][0];
					if(comcdnflag == 1) {
						videourl = ajson.comcdnurl2;
					} else {
						videourl = videourl.replace(':8081', ':8080').replace('?key=', '.m3u8?key=');
					}
					var videoTemp='';
					 var videoMatch=videourl.match(/^((http:|https)\/\/[a-zA-Z0-9\.:]+\/)/i)[0];
					 videoTemp+=videoMatch+json["uid"]+'/'+json["servicetype"]+'/'+videourl.split(videoMatch)[1];
					 videourl=videoTemp;
					
					if(json['isautosize'] == 1) {

					} else {}
					
					defer.resolve(videourl);

//					callbackFunc.call(_self, videourl);

					//				var stringdom ='<video '+sisautoplay+' webkit-playsinline poster="'+posterurl+'"  controls="controls" id="baofeng-html5-player-video" onclick="playVideo(\''+videourl+'\');"  preload="auto" src="'+videourl+'" width="100%" height="100%"  x-webkit-airplay="allow"><source  src="'+videourl+'" type="application/x-mpegURL">Your browser does not support the video tag.</video>';

					$('video').on('play', function() {
						$('video').off('play');
						$.ajax({
							type: "GET",
							url: "http://hlsonline.baofengcloud.com/" + ajson.gcids[0]['gcid'],
							data: '',
							async: false,
							dataType: "json",
							success: function(datajson) {
								if(datajson.status == 0 && datajson.Ip.length > 0) {
									var dataserverhost = datajson.Ip;
									var peerid = getpeerid(32);
									var sparamjaon = '{"gcid":"' + ajson.gcids[0]['gcid'] + '", "peerid":"' + peerid + '"}';
									setInterval(function() {
										$.ajax({
											type: "POST",
											url: "http://" + dataserverhost + "/ping",
											data: sparamjaon,
											dataType: "json",
											success: function(xjson) {

											}
										});
									}, 30000);
								}
							}
						});
					});

				}
			}
		});

		function Base64() {
			_keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
			this.encode = function(input) {
				var output = "";
				var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
				var i = 0;
				input = _utf8_encode(input);
				while(i < input.length) {
					chr1 = input.charCodeAt(i++);
					chr2 = input.charCodeAt(i++);
					chr3 = input.charCodeAt(i++);
					enc1 = chr1 >> 2;
					enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
					enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
					enc4 = chr3 & 63;
					if(isNaN(chr2)) {
						enc3 = enc4 = 64;
					} else if(isNaN(chr3)) {
						enc4 = 64;
					}
					output = output +
						_keyStr.charAt(enc1) + _keyStr.charAt(enc2) +
						_keyStr.charAt(enc3) + _keyStr.charAt(enc4);
				}
				return output;
			};

			this.decode = function(input) {
				var output = "";
				var chr1, chr2, chr3;
				var enc1, enc2, enc3, enc4;
				var i = 0;
				input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
				while(i < input.length) {
					enc1 = _keyStr.indexOf(input.charAt(i++));
					enc2 = _keyStr.indexOf(input.charAt(i++));
					enc3 = _keyStr.indexOf(input.charAt(i++));
					enc4 = _keyStr.indexOf(input.charAt(i++));
					chr1 = (enc1 << 2) | (enc2 >> 4);
					chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
					chr3 = ((enc3 & 3) << 6) | enc4;
					output = output + String.fromCharCode(chr1);
					if(enc3 != 64) {
						output = output + String.fromCharCode(chr2);
					}
					if(enc4 != 64) {
						output = output + String.fromCharCode(chr3);
					}
				}
				output = _utf8_decode(output);
				return output;
			};

			_utf8_encode = function(string) {
				string = string.replace(/\r\n/g, "\n");
				var utftext = "";
				for(var n = 0; n < string.length; n++) {
					var c = string.charCodeAt(n);
					if(c < 128) {
						utftext += String.fromCharCode(c);
					} else if((c > 127) && (c < 2048)) {
						utftext += String.fromCharCode((c >> 6) | 192);
						utftext += String.fromCharCode((c & 63) | 128);
					} else {
						utftext += String.fromCharCode((c >> 12) | 224);
						utftext += String.fromCharCode(((c >> 6) & 63) | 128);
						utftext += String.fromCharCode((c & 63) | 128);
					}

				}
				return utftext;
			};

			_utf8_decode = function(utftext) {
				var string = "";
				var i = 0;
				var c = c1 = c2 = 0;
				while(i < utftext.length) {
					c = utftext.charCodeAt(i);
					if(c < 128) {
						string += String.fromCharCode(c);
						i++;
					} else if((c > 191) && (c < 224)) {
						c2 = utftext.charCodeAt(i + 1);
						string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
						i += 2;
					} else {
						c2 = utftext.charCodeAt(i + 1);
						c3 = utftext.charCodeAt(i + 2);
						string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
						i += 3;
					}
				}
				return string;
			};
		}

		function getpeerid(len) {
			len = len || 32;
			var schars = 'ABC1DEFGH2IJK3LMNOQP4RSTU5VWXYZab6cdef8ghij7kmlnopq9rest0uvwxyz';
			var maxPos = schars.length;
			var pwd = '';
			for(i = 0; i < len; i++) {
				pwd += schars.charAt(Math.floor(Math.random() * maxPos));
			}
			return pwd;
		}
		return defer.promise();

	}
}
