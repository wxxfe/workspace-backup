function QuestionItem() {
	return this;
}

QuestionItem.fn = QuestionItem.prototype;

QuestionItem.fn.init = function(options) {
	this.options = options;
	this.element = options.element;
	
	this._registerEvents();
	return this;
}

QuestionItem.fn._registerEvents = function() {
	var _self = this, 
		answers = this.element.querySelectorAll('.answer');
	for(var i = 0,len = answers.length; i < len; i++) {
		answers[i].addEventListener('click', function() {
			_self.options.onAnswer && _self.options.onAnswer.call(null, {});
		})
	}
}

function QuestionItemFactory(options) {
	return new QuestionItem().init(options);
}