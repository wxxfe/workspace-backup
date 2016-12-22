CloudVODSrcGetter = {
	getVideoUrlFromBFCloud: function(ajson) {
		var defer = $.Deferred();
		var USER_AGENT = navigator.userAgent;
		var IS_IPHONE = (/iPhone/i).test(USER_AGENT);
		var IS_IPAD = (/iPad/i).test(USER_AGENT);
		var IS_IPOD = (/iPod/i).test(USER_AGENT);
		var IS_WINDOWSWECHAT=(/WindowsWechat|(OS X (\d+)_)/).test(USER_AGENT);
		var IS_IOS = IS_IPHONE || IS_IPAD || IS_IPOD;
		
		var data, json, search, arrs;
		search = ajson;
		if(search) {
			json = {};
			data = search.split("&");
			for(var i = 0, l = data.length; i < l; i++) {
				arrs = data[i].split("=");
				json[arrs[0]] = arrs[1];
			}
		}
		if(json['vk']) {
			var b = new Base64();
			var jmi = b.encode(decodeURIComponent(json['vk']));
		} else {
			var b = new Base64();
			json['vk'] = 'servicetype=' + json['servicetype'] + '&uid=' + json['uid'] + '&fid=' + json['fid'];
			var jmi = b.encode(json['vk']);
		}
		if(json['tk']) {
			jmi += '?tk=' + json['tk'] + "&ifhtml5=true";
		} else {
			jmi += "?ifhtml5=true";
		}

//		var posterUrl = this.posterDeal(ajson['poster']);
//		json['width'] = ajson.width;
//		json['height'] = ajson.height;
//		json['isautosize'] = ajson.isautosize;

		if(json['servicetype'] == 1) {
			$.ajax({
				type: "GET",
				url: "http://cdnqueryex.baofengcloud.com/" + jmi,
				data: '',
				async: false,
				dataType: "json",
				success: function(ajson) {
//					if(ajson.covurlflag == 0 && ajson.defcovurl.length > 0) {
//						posterurl = ajson.defcovurl;
//					} else if(ajson.covurlflag == 1 && ajson.usrcovurl.length > 0) {
//						posterurl = ajson.usrcovurl;
//					} else {
//						posterurl = "http://filepry.baofengcloud.com/" + ((parseInt(json['uid']) % 256).toString(16)).toUpperCase() + "/" + json['uid'] + "/1/common/" + json['fid'] + '.cov.0.jpg';
//					}
//					if(posterUrl) {
//						posterurl = posterUrl;
//					}
					if(json['fmatid'] && json['fmatid'] >= 0) {
						for(var x in ajson.gcids) {
							if(ajson.gcids[x]['definition'] == json['fmatid']) {
								var comcdnflag = ajson.gcids[x]['usecomcdnflag'];
								if(comcdnflag == 0) {
									var videourl = ajson.gcids[x]['urllist'][0];
								} else {
									var videourl = ajson.gcids[x]['comcdnurl2'];
								}
								break;
							}
						}
						if(!videourl || typeof videourl == 'undefined') {
							var comcdnflag = ajson.gcids[0]['usecomcdnflag'];
							if(comcdnflag == 0) {
								var videourl = ajson.gcids[0]['urllist'][0];
							} else {
								var videourl = ajson.gcids[0]['comcdnurl2'];
							}
						};
					} else {
						var comcdnflag = ajson.gcids[0]['usecomcdnflag'];
						if(comcdnflag == 0) {
							var videourl = ajson.gcids[0]['urllist'][0];
						} else {
							var videourl = ajson.gcids[0]['comcdnurl2'];
						}
					}

					if(IS_IOS) {
						//var mtype='mp4';
						//var mmime='video/mp4';
						//if (comcdnflag==1){
						//videourl = ajson.gcids[0]['comcdnurl2'];
						var mtype = 'm3u8';
						var mmimetype = 'application/x-mpegURL';
						videourl = videourl.replace(/\.mp4/g, '.m3u8');
						if(comcdnflag == 0) {
							videourl = videourl.replace(/\:443/g, ':8088');
						}
						var videoTemp = '';
						var videoMatch = videourl.match(/^((http:|https)\/\/[a-zA-Z0-9\.:]+\/)/i)[0];
						videoTemp += videoMatch + json["uid"] + '/' + json["servicetype"] + '/' + videourl.split(videoMatch)[1];
						videourl = videoTemp;
					} else if(IS_WINDOWSWECHAT) {
						var mtype = 'mp4';
						var mmime = 'video/mp4';
					} else {
						/*var mtype='mp4';
						 var mmimetype='video/mp4';*/
						var mtype = 'm3u8';
						var mmimetype = 'application/x-mpegURL';
						videourl = videourl.replace(/\.mp4/g, '.m3u8');
						if(comcdnflag == 0) {
							videourl = videourl.replace(/\:443/g, ':8088');
						}
						var videoTemp = '';
						var videoMatch = videourl.match(/^((http:|https)\/\/[a-zA-Z0-9\.:]+\/)/i)[0];
						videoTemp += videoMatch + json["uid"] + '/' + json["servicetype"] + '/' + videourl.split(videoMatch)[1];
						videourl = videoTemp;
					}
					//<source id="sourcevideo"  type="'+mmimetype+' src="'+videourl+'"">
//					$('#' + videoareaname).html('<video  id="mainvideo"   webkit-playsinline  src="' + videourl + '"  controls="controls" ' + sisautoplay + ' preload="auto" poster="' + posterurl + '" width="100%" height="100%" 			 data-setup="{}"><p>' + 'decodeURI("%E4%BD%A0%E7%9A%84%E6%B5%8F%E8%A7%88%E5%99%A8%E4%B8%8D%E6%94%AF%E6%8C%81HTML5%E7%9A%84video%E6%A0%87%E7%AD%BE")' + '</p></video>');
					defer.resolve(videourl);
				},
				error: function(data) {
					alert(decodeURI("%E8%8E%B7%E5%8F%96%E8%A7%86%E9%A2%91%E5%A4%B1%E8%B4%A5"));
					defer.reject();
				}
			});
		} else {
			$.ajax({
				type: "GET",
				url: "http://cdnqueryex.baofengcloud.com/" + jmi,
				data: '',
				async: false,
				dataType: "json",
				success: function(ajson) {
					var videourl = ajson.urllist[0];
					var comcdnflag = ajson['usecomcdnflag'];

					if(IS_IOS) {
						var mtype = 'mp4';
						var mmime = 'video/mp4';
						if(comcdnflag == 1) {
							videourl = ajson['comcdnurl2'];
						}
						/*var mtype='m3u8';
						var mmime='application/x-mpegURL';
						if (comcdnflag==0){
							videourl = videourl.replace(/\:443/g,':8088');
						}else{
							videourl = ajson['comcdnurl2'];
						}
						videourl = videourl.replace(/\.mp4/g,'.m3u8');*/
					} else if(IS_WINDOWSWECHAT) {
						var mtype = 'mp4';
						var mmime = 'video/mp4';
					} else {
						var mtype = 'mp4';
						var mmime = 'video/mp4';
						if(comcdnflag == 1) {
							videourl = ajson['comcdnurl2'];
						}
						/*var mtype='m3u8';
						 var mmime='application/x-mpegURL';
						if (comcdnflag==1){
							videourl = ajson['comcdnurl2'];
						}
						videourl = videourl.replace(/\.mp4/g,'.m3u8');*/
					}

					//$('#videoarea').attr('style',"margin:0px auto;background-color:#000;");
//					$("#" + videoareaname).html('<video id="mainvideo"  controls="controls" preload="none" width="100%" height="100%" ' + sisautoplay + ' type="' + mmime + '" src="' + videourl + '"  webkit-playsinline   ><p class="vjs-no-js">' + decodeURI("%E4%BD%A0%E7%9A%84%E6%B5%8F%E8%A7%88%E5%99%A8%E4%B8%8D%E6%94%AF%E6%8C%81HTML5%E7%9A%84video%E6%A0%87%E7%AD%BE") + '</p></video>');
					defer.resolve(videourl);
				},
				error: function(data) {
					alert(decodeURI("%E8%8E%B7%E5%8F%96%E8%A7%86%E9%A2%91%E5%A4%B1%E8%B4%A5"));
					defer.reject();
				}
			});
		}
		
		function Base64() {
			_keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
			this.encode = function (input) {
				var output = "";
				var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
				var i = 0;
				input = _utf8_encode(input);
				while (i < input.length) {
					chr1 = input.charCodeAt(i++);
					chr2 = input.charCodeAt(i++);
					chr3 = input.charCodeAt(i++);
					enc1 = chr1 >> 2;
					enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
					enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
					enc4 = chr3 & 63;
					if (isNaN(chr2)) {
						enc3 = enc4 = 64;
					} else if (isNaN(chr3)) {
						enc4 = 64;
					}
					output = output +
					_keyStr.charAt(enc1) + _keyStr.charAt(enc2) +
					_keyStr.charAt(enc3) + _keyStr.charAt(enc4);
				}
				return output;
			}  ;
			this.decode = function (input) {
				var output = "";
				var chr1, chr2, chr3;
				var enc1, enc2, enc3, enc4;
				var i = 0;
				input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
				while (i < input.length) {
					enc1 = _keyStr.indexOf(input.charAt(i++));
					enc2 = _keyStr.indexOf(input.charAt(i++));
					enc3 = _keyStr.indexOf(input.charAt(i++));
					enc4 = _keyStr.indexOf(input.charAt(i++));
					chr1 = (enc1 << 2) | (enc2 >> 4);
					chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
					chr3 = ((enc3 & 3) << 6) | enc4;
					output = output + String.fromCharCode(chr1);
					if (enc3 != 64) {
						output = output + String.fromCharCode(chr2);
					}
					if (enc4 != 64) {
						output = output + String.fromCharCode(chr3);
					}
				}
				output = _utf8_decode(output);
				return output;
			}  ;
		
			_utf8_encode = function (string) {
				string = string.replace(/\r\n/g,"\n");
				var utftext = "";
				for (var n = 0; n < string.length; n++) {
					var c = string.charCodeAt(n);
					if (c < 128) {
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
			}  ;
			_utf8_decode = function (utftext) {
				var string = "";
				var i = 0;
				var c = c1 = c2 = 0;
				while ( i < utftext.length ) {
					c = utftext.charCodeAt(i);
					if (c < 128) {
						string += String.fromCharCode(c);
						i++;
					} else if((c > 191) && (c < 224)) {
						c2 = utftext.charCodeAt(i+1);
						string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
						i += 2;
					} else {
						c2 = utftext.charCodeAt(i+1);
						c3 = utftext.charCodeAt(i+2);
						string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
						i += 3;
					}
				}
				return string;
			}  ;
		}
		
		
		return defer.promise();
	}
}