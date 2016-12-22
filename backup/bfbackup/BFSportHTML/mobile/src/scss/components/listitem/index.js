function ListItem() {
	return this;
}

ListItem.fn = ListItem.prototype;

ListItem.fn.init = function(options) {
	this.element = options.element;
	this.onAnswerSubmit = options.onAnswerSubmit;
	
	this._registerEvents();
	return this;
}

ListItem.fn._registerEvents = function() {
	var _self = this;
	this.element.querySelector('.close') && this.element.querySelector('.close').addEventListener('click', function() {
		_self.element.classList.add('hide');
	});
	this.element.addEventListener('click', function() {
		DeviceApi.jumpTo(this.querySelector('a'));
	});
}

ListItem.fn.show = function() {
	this.element.classList.remove('hide');
}

ListItem.fn.hide = function() {
	this.element.classList.add('hide');
}

function ListItemFactory(options) {
	return new ListItem().init(options);
}