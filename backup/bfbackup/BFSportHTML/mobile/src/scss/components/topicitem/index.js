function Topic() {
	return this;
}

Topic.fn = Topic.prototype;

Topic.fn.init = function(options) {
	this.element = options.element;
	
	this._registerEvents();
	
	return this;
}

Topic.fn._registerEvents = function() {
	this.element.addEventListener('click', function() {
		DeviceApi.jumpTo(this);
	});
}

function TopicItemFactory(options) {
	return new Topic().init(options);
}