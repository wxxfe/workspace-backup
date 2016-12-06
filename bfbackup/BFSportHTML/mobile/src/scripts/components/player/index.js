function Player(options){
	this.options = options;
	this.player = options.player;
    this.cid = options.cid;
    this.size = options.size;
    
    this.options.autoplay = false;
    this.options.controls = false;
    
	this.init();
	return this;
}

Player.fn = Player.prototype;

Player.fn.init = function(){
    var _self = this;
	var callbackName = 'playerCallback';
	var url = 'http://rd.p2p.baofeng.net/queryvp.php?type=3&gcid='+ this.cid +'&callback=' + callbackName;
	window[callbackName] = function(data){
		var videoSrc = _self.getMp4(data);
		var ap = _self.options.autoplay ? ' autoplay="autoplay" ' : '',
			ct = _self.options.controls ? ' controls ' : '';
		var v = _self.player.querySelector('video');
		if(v) {
			v.setAttribute('src', videoSrc);
		} else {
			var p = '<video ' + ap + ct + ' width="100%" height="100%" style="background:#000;" src="' + videoSrc + '" webkit-playsinline></video>'
        		_self.player.insertAdjacentHTML('beforeEnd', p);
		}
        
		try {
			delete window[callbackName];
		} catch (e) {}
		window[callbackName] = null;
		
		_self.options.afterVideoCreated && _self.options.afterVideoCreated.call(_self.player);
		
	}
    this.loadData(url);
}

Player.fn.loadData = function(url){
	var script = document.createElement('script'),head;
	var done = false;
	script.src = url;
	script.async = true;

	script.onload = script.onreadystatechange = function () {
		if (!done && (!this.readyState || this.readyState === "loaded" || this.readyState === "complete")) {
			done = true;
			script.onload = script.onreadystatechange = null;
			if (script && script.parentNode) {
				script.parentNode.removeChild(script);
			}
		}
	};
	if (!head) {
		head = document.getElementsByTagName('head')[0];
	}
	head.appendChild(script);
}

Player.fn.getMp4 = function(res){
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
    for (var i = 0; i < iplist.length; i++) {
        temp = iplist[i];
        url = '';
        leng = temp.length;
        for (var j = 0; j < leng; j++) {
            url += _p2pmap[temp.substr(j, 1)];
        }
        https.push('http://' + url + ':' + port + '/' + path + '?key=' + key);
    }
    return https[0] + '&filelen=' + this.size;
}

function PlayerFactory(options) {
	return new Player(options)
}
