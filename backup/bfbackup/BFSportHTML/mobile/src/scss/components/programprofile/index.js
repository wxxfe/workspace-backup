function ProgramProfile(options) {
	return this;
}

ProgramProfile.fn = ProgramProfile.prototype;

ProgramProfile.fn.init = function(options) {
	this.element = options.element;
	this.toggleButton = this.element.querySelector('.toggle-btn');
	this._registerEvents();
}

ProgramProfile.fn._registerEvents = function() {
	// 解决滑动同时触发点击问题
	this.toggleButton.addEventListener('touchstart', function() {
		e.preventDefault();
	});
	this.toggleButton.addEventListener('click', this._toggleHandler.bind(this));
}

ProgramProfile.fn._toggleHandler = function(e) {
	var faNode = this.toggleButton.querySelector('.fa'),
		isClosed = faNode.classList.contains('fa-angle-right') ? true : false;
	if(isClosed) {
		this.element.style.height = 'auto';
		faNode.classList.remove('fa-angle-right');
		faNode.classList.add('fa-angle-down');
	} else {
		this.element.style.height = '64px';
		faNode.classList.remove('fa-angle-down');
		faNode.classList.add('fa-angle-right');
	}
}

function ProgramProfileFactory(options) {
	return new ProgramProfile().init(options);
}