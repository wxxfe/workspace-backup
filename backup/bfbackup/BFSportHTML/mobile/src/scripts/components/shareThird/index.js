function ShareThird() {
	return this;
}

ShareThird.fn = ShareThird.prototype;

ShareThird.fn.init = function(options) {
	this.element = options.element;
	
	this._registerEvents();
	return this;
}

ShareThird.fn._registerEvents = function() {
	var thirds = this.element.querySelectorAll('.third-img');
	for(var i = 0, len = thirds.length;i < len;i++) {
		thirds[i].addEventListener('click', function() {
			var pf = this.getAttribute('data-platform');
			DeviceApi.shareTo(pf);
		})
	}
}

function ShareThirdFactory(options) {
	return new ShareThird().init(options);
}