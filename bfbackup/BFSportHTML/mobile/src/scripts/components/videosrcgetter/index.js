VideoSrcGetter = {
	getSrc: function(videoInfo, isLive) {
		var defer = $.Deferred();
		if(Utils.browserInfo.isMobileDevice) {
			if(videoInfo.play_code2) {
	//			videoInfo.play_code2 = "servicetype=2&uid=48842737&fid=3EDECD6B2A405805B5144F25342586C7672FDB24&auto=1&si=1&vr=0"; // live
	//			videoInfo.play_code2 = "servicetype=1&uid=48842737&fid=08FE0E33E457BEFDE68C4EF30A11063D";
	//			videoInfo.play_code2 = "servicetype=1&uid=22588069&fid=2750F5BE870B9E7CF782CFFFEB9812DF";
	//			isLive = false;
				if(isLive) {
					CloudLiveSrcGetter.getVideoUrlFromBFCloud(videoInfo.play_code2).then(function(videoUrl) {
						defer.resolve(videoUrl);
					})
				} else {
					CloudVODSrcGetter.getVideoUrlFromBFCloud(videoInfo.play_code2).then(function(videoUrl) {
						defer.resolve(videoUrl);
					})
				}
				
			} else if(videoInfo.play_code) {
				VideoSrcGetter._getVideoUrl(videoInfo.play_code.cid, videoInfo.play_code.size).then(function(videoUrl) {
					defer.resolve(videoUrl);
				});
			} else if(videoInfo.id && !isLive) {
				VideoSrcGetter.getByVideoId(videoInfo.id).then(function(videoUrl) {
					defer.resolve(videoUrl);
				});
			}
		} else {
			var playUrl = VideoSrcGetter.getPlayUrl(videoInfo);
			if(playUrl) {
				defer.resolve(playUrl);
			} else if(!isLive) {
				VideoSrcGetter.getByVideoId(videoInfo.id).then(function(videoUrl) {
					defer.resolve(videoUrl);
				});
			}
			
		}
		return defer.promise();
	},
	getByVideoId: function(videoId) {
		var _self = this;
		var defer = $.Deferred();
		$.ajax({
			type: "get",
			url: "http://m.sports.baofeng.com/api/getVideoInfo/" + videoId,
			async: true,
			dataType: 'json',
			success: function(data) {
				if(data.status === 1) {
					if(Utils.browserInfo.isMobileDevice) {
						_self._getVideoUrl(data.data.box_cid, data.data.file_size).then(function(videoUrl) {
							defer.resolve(videoUrl);
						});
					} else {
						var playUrl = VideoSrcGetter.getPlayUrl(data.data);
						defer.resolve(playUrl);
					}
					
				} else {
					alert('获取视频信息失败');
					defer.reject();
				}
			},
			error: function(xhr, type) {
				alert('获取视频信息失败');
				defer.reject();
			}
		});
		return defer.promise();
	},
	getPlayUrl: function(videoInfo) {
		var rsl;
		if(videoInfo.play_url) {
			rsl = videoInfo.play_url;
		} else if(videoInfo.play_code2){
			var url;
			if(videoInfo.isvr) {
				rsl = 'http://live.baofengcloud.com/48853043/player/cloud.swf?' + videoInfo.play_code2 + '&auto=1&si=1';
			} else {
				rsl = 'http://live.baofengcloud.com/48842737/player/cloud.swf?' + videoInfo.play_code2 + '&auto=1&si=1';
			}
		}
		return rsl; 
	},
	getByVideoCidAndSize: function(cid, size) {
		var defer = $.Deferred();
		this._getVideoUrl(cid, size).then(function(videoUrl) {
			defer.resolve(videoUrl);
		});
		return defer.promise();
	},
	_getVideoUrl: function(cid, size) {
		var _self = this;
		var defer = $.Deferred();
		var callbackName = 'playerCallback';
		var url = 'http://rd.p2p.baofeng.net/queryvp.php?type=3&gcid=' + cid + '&callback=' + callbackName;
		window[callbackName] = function(data) {
			var videoSrc = _self._convertToMp4Url(data, size);
//			callbackFunc.call(_self, videoSrc);
			defer.resolve(videoSrc);
			try {
				delete window[callbackName];
			} catch(e) {}
			window[callbackName] = null;

		}
		this._loadData(url);
		return defer.promise();
	},
	_loadData: function(url) {
		var script = document.createElement('script'),
			head;
		var done = false;
		script.src = url;
		script.async = true;

		script.onload = script.onreadystatechange = function() {
			if(!done && (!this.readyState || this.readyState === "loaded" || this.readyState === "complete")) {
				done = true;
				script.onload = script.onreadystatechange = null;
				if(script && script.parentNode) {
					script.parentNode.removeChild(script);
				}
			}
		};
		if(!head) {
			head = document.getElementsByTagName('head')[0];
		}
		head.appendChild(script);
	},
	_convertToMp4Url: function(res, size) {
		var _p2pmap = {
			'b': '0',
			'a': '1',
			'o': '2',
			'f': '3',
			'e': '4',
			'n': '5',
			'g': '6',
			'h': '7',
			't': '8',
			'm': '9',
			'l': '.',
			'c': 'A',
			'p': 'B',
			'z': 'C',
			'r': 'D',
			'y': 'E',
			's': 'F'
		};
		var iplist = res["ip"].split(','),
			port = res["port"],
			path = res["path"],
			key = res["key"];
		var url, temp, leng, https = [];
		for(var i = 0; i < iplist.length; i++) {
			temp = iplist[i];
			url = '';
			leng = temp.length;
			for(var j = 0; j < leng; j++) {
				url += _p2pmap[temp.substr(j, 1)];
			}
			https.push('http://' + url + ':' + port + '/' + path + '?key=' + key);
		}
		return https[0] + '&filelen=' + size;
	}

}