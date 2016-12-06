function MatchInfo() {
	return this;
}

MatchInfo.fn = MatchInfo.prototype;

MatchInfo.fn.init = function(options) {
	this.element = options.element;
	this.onApointment = options.onApointment;;
	
	this._registerEvents();
	return this;
}

MatchInfo.fn._registerEvents = function() {
	var _self = this, opBtn = this.element.querySelector('.operation .button');
	opBtn.addEventListener('click', function() {
		_self.onApointment(null, {})
	})
}

function MatchInfoFactory(options) {
	return new MatchInfo().init(options);
}