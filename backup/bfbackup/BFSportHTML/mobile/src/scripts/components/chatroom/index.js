function ChatRoom() {
	return this;
}

ChatRoom.fn = ChatRoom.prototype;

ChatRoom.fn.init = function(options) {
	this.options = options;
	this.element = options.element;
	this.onSendMessage = options.onSendMessage;
	
	this._registerEvents();
	return this;
}

ChatRoom.fn._registerEvents = function() {
	var _self = this, inputs = this.element.querySelectorAll('.message-input');
	for(var i = 0, len = inputs.length;i < len;i++) {
		inputs[i].addEventListener('click', function() {
			_self.onSendMessage && _self.onSendMessage.call(null, {});
		})
	}
}

function ChatRoomFactory(options) {
	return new ChatRoom().init(options);
}