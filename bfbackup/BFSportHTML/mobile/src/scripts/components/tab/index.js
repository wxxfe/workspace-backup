function Tab() {
    return this;
}

Tab.fn = Tab.prototype;

Tab.fn.init = function (options) {
    this.element = options.element;
    this.element.component = this;

    this.titleNode = this.element.querySelector('.title');
    this.contentNode = this.element.querySelector('.content');
    this.indicatorNode = this.element.querySelector('.indicator-wrapper');
    this.titleNodes = this.element.querySelectorAll('.item-title');
    this.contentNodes = this.element.querySelectorAll('.item-content');
    this.indicatorNodes = this.element.querySelectorAll('.indicator');

    this.hasTitle = this.titleNodes && this.titleNodes.length > 0;
    this.hasIndicator = this.indicatorNodes && this.indicatorNodes.length > 0;

    this.currentIndex = 0;
    this.contentX = 0;
    this.contentY = 0;
    this.touchStartPos;
    this.contentStartPos;
    this.moveThreshold = 100; // 滑动距离超过这个的阀值，松开手之后会切换到下一项
    this.reachedThreshold = false;
    this.reachedThresholdAuto = options.reachedThresholdAuto == undefined ? true : options.reachedThresholdAuto;
    this.isTouchMove = false;

    this.itemsLength = this.contentNodes.length;
    this._registerEvents();

    if (this.contentNodes && this.contentNodes.length) {
        this.element.style.height = (this.contentNodes[0].getBoundingClientRect().height + this.titleNode.getBoundingClientRect().height) + 'px';
    }
    this.setWholeHeight(0);

    return this;
}

Tab.fn._registerEvents = function () {
	var _self = this;
    this.titleNode.addEventListener('click', this._switchTabHandler.bind(this));
    this.contentNode.addEventListener('touchstart', this._touchStartHandler.bind(this));

    this.contentNode.addEventListener('touchmove', this._touchMoveHandler.bind(this), false);
    this.contentNode.addEventListener('touchend', this._touchEndHandler.bind(this), false);
    
    window.addEventListener('load', this.setWholeHeight.bind(this, 0))
}

/**
 * 根据页签项，重设element的整体高度
 * @param {Object} index
 */
Tab.fn.setWholeHeight = function (index) {
	if (this.contentNodes && this.contentNodes.length) {
        this.element.style.height = (this.contentNodes[index].getBoundingClientRect().height + this.titleNode.getBoundingClientRect().height) + 'px';
    }
}

Tab.fn._switchTabHandler = function (event) {
    event.preventDefault();
    var targetNode = event.target,
        index = 0;
    if (targetNode.classList.contains('current')) return;

    for (var i = 0, len = this.titleNodes.length; i < len; i++) {
        var node = this.titleNodes[i];
        if (node === targetNode) {
            index = i;
            break;
        }
    }

    this.switchTab(index);
}

Tab.fn.switchTab = function (index) {
    var curs = this.element.querySelectorAll('.current'),

        contentWidth = this.element.getBoundingClientRect().width;

    for (var i = 0, len = curs.length; i < len; i++) {
        curs[i].classList.remove('current');
    }

    this.currentIndex = index;

    this.hasTitle && this.titleNodes[this.currentIndex].classList.add('current');
    this.contentNodes[this.currentIndex].classList.add('current');

    this.contentNode.style.left = -contentWidth * this.currentIndex + 'px';
    this._switchIndicator(this.currentIndex);

    //
    this.element.style.height = (this.contentNodes[index].getBoundingClientRect().height + this.titleNode.getBoundingClientRect().height) + 'px';
}

Tab.fn._switchIndicator = function (index) {
    var itemWidth = this.element.getBoundingClientRect().width / 3;
    this.indicatorNode.style.left = itemWidth * index + 'px';
}

Tab.fn._touchStartHandler = function (event) {
//	event.preventDefault();                 //阻止浏览器的默认事件
    var touch = event.targetTouches[0];     //touches数组对象获得屏幕上所有的touch，取第一个touch
    this.touchStartPos = {x: touch.clientX, y: touch.clientY};    //取第一个touch的坐标值
    this.contentStartPos = {x: parseInt(this.contentNode.style.left)};
    this.contentX = parseInt(this.contentNode.style.left);            //获取触摸时滑动块的初始位置
}

Tab.fn._touchMoveHandler = function (event) {
    //当屏幕有多个touch或者页面被缩放过，就不执行move操作
    if (event.targetTouches.length > 1 || event.scale && event.scale !== 1) return;
    var touch = event.targetTouches[0];
    endPos = {x: touch.clientX - this.touchStartPos.x, y: touch.clientY - this.touchStartPos.y};                 //获取所移动的距离
//	event.preventDefault();      //阻止触摸事件的默认行为，即阻止滚屏

    if (Math.abs(endPos.x) > this.moveThreshold) {
        this.reachedThreshold = true;
    }

    this.isTouchMove = true;

    // 判断滑动的方向
    if (Math.abs(endPos.x) > Math.abs(endPos.y) && endPos.x > 0) {
        this.moveDirection = 'left';
    } else if (Math.abs(endPos.x) > Math.abs(endPos.y) && endPos.x < 0) {
        this.moveDirection = 'right';
    } else if (Math.abs(endPos.y) > Math.abs(endPos.x) && endPos.y > 0) {
        this.moveDirection = 'down';
    } else if (Math.abs(endPos.y) > Math.abs(endPos.x) && endPos.y < 0) {
        this.moveDirection = 'up';
    } else {
        this.isTouchMove = false;
    }

//	if(this.moveDirection === 'left' || this.moveDirection === 'right') {
//		this.contentNode.style.left = (this.contentX + endPos.x ) + 'px';
//	}

}

Tab.fn._touchEndHandler = function (event) {
    if (!this.isTouchMove) return;
    if (this.reachedThresholdAuto) {
        if (this.reachedThreshold) {
            if (this.moveDirection === 'left') {
                if (this.currentIndex - 1 < 0) {
                    // 切到最后一项
                    this.switchTab(this.itemsLength - 1);
                } else {
                    this.switchTab(this.currentIndex - 1);
                }
            } else if (this.moveDirection === 'right') {
                if (this.currentIndex + 1 == this.itemsLength) {
                    // 切到第一项
                    this.switchTab(0);
                } else {
                    this.switchTab(this.currentIndex + 1);
                }
            }
        } else {
            this.contentNode.style.left = this.contentStartPos.x + 'px';
        }
    } else {

    }

    this.reachedThreshold = false;
    this.isTouchMove = false;
}

function SlideTab() {
}
SlideTab.prototype = new Tab();

SlideTab.prototype._switchIndicator = function (index) {
    this.hasIndicator && this.indicatorNodes[index].classList.add('current');
}

function TabFactory(options) {
    if (options.type == 'slide') {
        return new SlideTab().init(options);
    }
    return new Tab().init(options);
}
