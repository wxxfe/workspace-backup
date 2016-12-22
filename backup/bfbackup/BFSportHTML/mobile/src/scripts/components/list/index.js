function List() {
	return this;
}

List.fn = List.prototype;

List.fn.init = function(options) {
	this.element = options.element;
	
	this.Parts = {};
	this._initParts();
	this._registerEvents();
	return this;
}

List.fn._initParts = function() {
	var _self = this, items = this.element.querySelectorAll('.list-item');
	for(var i = 0, len = items.length;i < len;i++) {
		ListItemFactory({element: items[i] })
	}
}

List.fn._registerEvents = function() {
}

function ListFactory(options) {
	return new List().init(options);
}