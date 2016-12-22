function Toast() {
	return this;
}

Toast.fn = Toast.prototype;

Toast.fn.init = function(options) {
	this.element = options.element;
	this.onAnswerSubmit = options.onAnswerSubmit;
	
	this._registerEvents();
	return this;
}

Toast.fn._registerEvents = function() {
	var _self = this;
	this.element.querySelector('.close').addEventListener('click', function() {
		_self.element.classList.add('hide');
	})
}

Toast.fn.show = function() {
	this.element.classList.remove('hide');
}

Toast.fn.hide = function() {
	this.element.classList.add('hide');
}

function ToastFactory(options) {
	return new Toast().init(options);
}