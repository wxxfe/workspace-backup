function Quiz() {
	return this;
}

Quiz.fn = Quiz.prototype;

Quiz.fn.init = function(options) {
	this.element = options.element;
	this.onAnswerSubmit = options.onAnswerSubmit;
	
	this.Parts = {};
	this._initParts();
	this._registerEvents();
	return this;
}

Quiz.fn._initParts = function() {
	var _self = this, quesitems = this.element.querySelectorAll('.question-item');
	for(var i = 0, len = quesitems.length;i < len;i++) {
		QuestionItemFactory({element: quesitems[i], onAnswer: _self.onAnswerSubmit })
	}
}

Quiz.fn._registerEvents = function() {
	var _self = this, itemNodes = this.element.querySelectorAll('.item-content');
	for(var i = 0, len = itemNodes.length;i < len;i++) {
		itemNodes[i].addEventListener('click', function() {
			var index;
			
			for(var i = 0, len = this.parentNode.children.length; i < len;i++) {
				if(this.parentNode.children[i] === this) {
					index = i;
				}
			}
			
			_self.element.querySelector('.tab').component.switchTab(index);
		})
	}
}

function QuizFactory(options) {
	return new Quiz().init(options);
}