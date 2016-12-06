function TopicList() {
	return this;
}

TopicList.fn = TopicList.prototype;

TopicList.fn.init = function(options) {
	this.element = options.element;
	
	this._initParts();
	this._registerEvents();
	
	return this;
}

TopicList.fn._initParts = function() {
	var _self = this, items = this.element.querySelectorAll('[data-info]');
	for(var i = 0, len = items.length;i < len;i++) {
		TopicItemFactory({element: items[i] })
	}
}

TopicList.fn._registerEvents = function() {
}

function TopicListFactory(options) {
	return new TopicList().init(options);
}