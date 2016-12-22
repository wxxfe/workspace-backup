function BaseComp() {
	return this;
}

BaseComp.prototype.init = function(options) {
	this.Top = options.top || options.Top;
}

/**
 * 事件注册
 * @param {Object} name
 * @param {Object} callback
 */
BaseComp.prototype.on = function(name, callback) {
	name = name.toLowerCase();
    this._events || (this._events = {});
    var events = this._events[name] || (this._events[name] = []);
    events.push({
        callback: callback
    })
    return this;
}

/**
 * 事件触发
 * @param {Object} name
 */
BaseComp.prototype.trigger = function(name) {
	var args = Array.prototype.slice.call(arguments, 1);
	name = name.toLowerCase()
	var triggerFunc = function() {
	    if (this._events && this._events[name]) {
		    var events = this._events[name];
		    for (var i = 0, count = events.length; i < count; i++) {
		        events[i].callback.apply(this, args);
		    }
	    }
	    if(this.Top) {
	    		// 向父传递，使得父能监听到子的事件
	    		triggerFunc.apply(this.Top, arguments);
	    }
	};
	triggerFunc.apply(this, arguments);
}

/**
 * 父组件调用方法时，父组件有这个方法，直接调用；父组件没有，调用子组件的方法
 * @param {Object} methodName
 */
BaseComp.prototype.invoke = function(methodName) {
	var args = Array.prototype.slice.call(arguments, 1);
	var invokeFunc = function() {
		if(this[methodName]) {
			this[methodName].apply(this, args);
		} else {
			if(this.Parts) {
				for(var key in this.Parts) {
					invokeFunc.apply(this.Parts[key], arguments)
				}
			}
		}
	}
	invokeFunc.apply(this, arguments);
}

BaseComp.prototype.show = function() {
	this.element.classList.remove('hide');
	return this;
}

BaseComp.prototype.hide = function() {
	this.element.classList.add('hide');
	return this;
}

BaseComp.prototype.addClass = function(className) {
	this.element.classList.add(className);
	return this;
}

BaseComp.prototype.removeClass = function(className) {
	this.element.classList.remove(className);
	return this;
}

BaseComp.prototype.hasClass = function(className) {
	return this.element.classList.contains(className);
	return this;
}

BaseComp.prototype.isHiding = function() {
	return this.element.classList.contains('hide');
}




