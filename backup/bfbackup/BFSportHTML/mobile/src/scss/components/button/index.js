function Button(options) {
	this.element = options.element;
	this.init();
	return this;
}

Button.fn = Button.prototype;

Button.fn.init = function() {
	this._registerEvents();
}

Button.fn._registerEvents = function() {
	this.element.addEventListener('touchstart', this._touchHandler.bind(this));
	this.element.addEventListener('touchend', this._touchHandler.bind(this));
}

Button.fn._touchHandler = function(e) {
	this.element.classList.toggle('is-tapping');
}

function ButtonFactory(options) {
	return new Button(options)
}