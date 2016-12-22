function Article() {
	return this;
}

Article.fn = Article.prototype;

Article.fn.init = function(options) {
	this.options = options;
	this.element = options.element;
	
	this.firstShowParagraphNums = options.firstShowParagraphNums || 6;
	
	this.options.enableShowMore && this._initShowMore();
	
	this._registerEvents();
	
	var topicNodes = this.element.querySelectorAll('.bfsports-topic-box');
	if(topicNodes){
		for(var i = 0, len = topicNodes.length;i < len;i++) {
			var topic = topicNodes[i].querySelector('.bfsports-topic');
			if(topic){
				var tid = $(topic).data('tid'),
					title = topic.innerText;
				$(topic).attr('data-info', JSON.stringify( {
					id: tid,
					title: title,
					type: 'topic'
				})).attr('data-url', '/topic/detail/' + tid);
			}
		}
	}

	//  样式的一些临时适配修改
	var imgs = this.element.querySelectorAll('.paragraphs p img');
	for(var i = 0;i < imgs.length;i++) {
		var parent = imgs[i].parentNode;
		if(parent.children.length === 1  && parent.childNodes.length === 1) {
			parent.style.lineHeight = 0;
		}
	}
	
	// 适配文章编辑器未使用之前的文章
	var videoNode = this.element.querySelector('.paragraphs .embed-video');
	if(videoNode && !videoNode.classList.contains('bfeditor-comp')) {
		var fragment = document.createDocumentFragment();

		//  将所有的文字节点移到videoNode外面
		var processTextNode = function(node) {
			if(!node) return;
			if(node.nodeType === 3) {
				var cnode = node.cloneNode();
				var wrap = document.createElement('p');
				wrap.style="text-align: center";
				wrap.appendChild(cnode);
				fragment.appendChild(wrap);
				node.remove();
				return;
			}

			for(var i = 0, len = node.childNodes.length;i < len;i++) {
				processTextNode(node.childNodes[i]);
			}
			return;
		}

		processTextNode(videoNode);
		videoNode.parentNode.insertBefore(fragment, videoNode.nextSibling);
	}


	return this;
}

Article.fn._initShowMore = function() {
	this.showMoreNode = this.element.querySelector('.article-showmore');
	if(!this.showMoreNode) return;
	this.paragraphNodes = this.element.querySelector('.paragraphs').children;
	// 隐藏不显示的段落
	if(this.paragraphNodes.length > this.firstShowParagraphNums) {
		for(var i = this.firstShowParagraphNums,len = this.paragraphNodes.length; i < len;i++) {
			this.paragraphNodes[i].classList.add('hide');
		}
		this.showMoreNode.classList.remove('hide');
	}
	this.showMoreNode.addEventListener('click', this._showMoreHandler.bind(this));
}

Article.fn._registerEvents = function() {
	var topicNodes = this.element.querySelectorAll('.bfsports-topic-box');
	if(topicNodes){
		for(var i = 0, len = topicNodes.length;i < len;i++) {
			var topic = topicNodes[i].querySelector('.bfsports-topic');
			if(topic){
				topic.addEventListener('click', function() {
					DeviceApi.jumpTo(this);
				})
			}
		}
	}
	var galleryBoxs = this.element.querySelectorAll('.bfsports-gallery-box');
	if(galleryBoxs) {
		for(var i = 0, len = galleryBoxs.length;i < len;i++) {
			galleryBoxs[i].addEventListener('click', function() {
				DeviceApi.jumpTo(this);
			})
		}
	}
}

Article.fn._showMoreHandler = function(event) {
	event.preventDefault();
	
	var paragraphHideNodes = this.element.querySelectorAll('.paragraphs .hide');
	for(var i = 0, len = paragraphHideNodes.length;i < len;i++) {
		paragraphHideNodes[i].classList.remove('hide');
	}
	this.showMoreNode.classList.add('hide');
}

function ArticleFactory(options) {
	return new Article().init(options);
}

