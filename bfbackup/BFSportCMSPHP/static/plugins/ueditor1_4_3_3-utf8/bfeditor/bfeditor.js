(function() {
	var BFEditor = window.BFEditor = {
		init: function(options) {
			this.ueditor = options.ueditor;
			this.editorFrame = document.querySelector('#container iframe');
			this.toolbar = document.querySelector('.bfeditor-toolbar');
			this.currSelComp;
			this.registerUEditorEvents();
			this.registerEvents();
		},
		registerUEditorEvents: function() {
			var _self = this;
			var editor = this.ueditor;
			editor.addListener('mousedown', function (t, evt) {
                var el = evt.target || evt.srcElement;
                makeCompUnSelect();
                var pComp = findParentComp(el);
                if(!pComp) return ;
                makeCompSelect(pComp);
                _self.currSelComp = pComp;
            });
            
            editor.addListener('keydown', debounce(function (t, evt) {
                var keyCode = evt.keyCode || evt.which;
                var curComp = getSelectedComp();
                if(curComp) {
                		window.compOrigin = curComp.cloneNode(true);
                }
            }, 200));
            
            editor.addListener('keyup', debounce(function (t, evt) {
                var keyCode = evt.keyCode || evt.which;
                var curComp = getSelectedComp();
                
				var rsl = isDomStructSame(window.compOrigin, curComp);
				if(!rsl) {
					editor.execCommand('undo');
				}
				window.compOrigin = null;
            }, 200));
            
            editor.addListener('contentChange', function (t, evt) {
            		window.Previewer.reload(this.getContent());
            })

            editor.addListener('scroll', function () {
            	_self.currSelComp && BFEditor.reLocateToolBar(_self.currSelComp.getBoundingClientRect());
            })
            
            window.addEventListener('scroll', function() {
            	_self.currSelComp && BFEditor.reLocateToolBar(_self.currSelComp.getBoundingClientRect());
            });
            function findParentComp(el) {
            		if(el.tagName == 'HTML' || el.tagName == 'BODY') return null;
            		if(el.classList.contains('bfeditor-comp')) {
            			return el;
            		}
            		return findParentComp(el.parentNode);
            }
            
            function makeCompUnSelect(el) {
            		var selComp = getSelectedComp();
            		selComp && selComp.classList.remove('bfeditor-comp-select');
            		hideToolBar();
            }
            
            function getSelectedComp() {
            		return editor.document.querySelector('.bfeditor-comp-select');
            }
            
            function makeCompSelect(el) {
            		el.classList.add('bfeditor-comp-select');
            		showToolBar(el);
            }
            
            function hideToolBar() {
            		BFEditor.hideToolBar();
            }
            
            function showToolBar(el) {
            		BFEditor.showToolBar(el.getBoundingClientRect());
            }
            
            // 这里是必要条件
            function isDomStructSame(dom1, dom2) {
                    var cdesArr1 = [], cdesArr2 = [];
                    
                    var struct1 = getNodeStruct(dom1);
                    var struct2 = getNodeStruct(dom2);

                    if(struct1 && struct2) {
                        return struct1.sort().toString() == struct2.sort().toString();
                    }
                    if(!struct1 && !struct2) {
                    		return true;
                    }
                    return false;

            }
            
            function getNodeStruct(node) {
                    if(!node || !node.children.length) {
                        return null;
                    }

                    var cdesArr = [];
                    for(var i = 0, len = node.children.length;i < len;i++) {
                        var nodeDesc = node.children[i].nodeType + ':' + node.children[i].tagName;
                        cdesArr.push(nodeDesc);
                        var childDesArr = getNodeStruct(node.children[i]);
                        if(childDesArr) {
                            cdesArr.concat(childDesArr);
                        }
                    }
                    return cdesArr;
            }
            
            function throttle(fn,delay, immediate, debounce) {
			   var curr = +new Date(),//当前事件
			       last_call = 0,
			       last_exec = 0,
			       timer = null,
			       diff, //时间差
			       context,//上下文
			       args,
			       exec = function () {
			           last_exec = curr;
			           fn.apply(context, args);
			       };
			   return function () {
			       curr= +new Date();
			       context = this,
			       args = arguments,
			       diff = curr - (debounce ? last_call : last_exec) - delay;
			       clearTimeout(timer);
			       if (debounce) {
			           if (immediate) {
			               timer = setTimeout(exec, delay);
			           } else if (diff >= 0) {
			               exec();
			           }
			       } else {
			           if (diff >= 0) {
			               exec();
			           } else if (immediate) {
			               timer = setTimeout(exec, -diff);
			           }
			       }
			       last_call = curr;
			   }
			};
			 
			function debounce(fn, delay, immediate) {
			   return throttle(fn, delay, immediate, true);
			};
		},
		showToolBar: function(info) {
			this.reLocateToolBar(info);
			this.toolbar.style.display = 'block';
		},
		reLocateToolBar: function(info) {
			this.toolbar.style.top = this.editorFrame.getBoundingClientRect().top + info.top + info.height + 10 + 'px';
			this.toolbar.style.left = this.editorFrame.getBoundingClientRect().left + info.left + 10 + 'px';
		},
		hideToolBar: function() {
			this.toolbar.style.display = 'none';
		},
		registerEvents: function() {
			var opNodes = document.querySelectorAll('.bfeditor-toolbar [data-op]')
    		for(var i = 0, len = opNodes.length;i < len;i++) {
    			var op = opNodes[i].getAttribute('data-op')
    			if(op == 'delete') {
    				opNodes[i].addEventListener('click', this._onDeleteComp.bind(this))
    			} else if(op == 'insertBefore') {
    				opNodes[i].addEventListener('click', this._onInsertBeforeComp.bind(this))
    			} else if(op == 'insertAfter') {
    				opNodes[i].addEventListener('click', this._onInsertAfterComp.bind(this))
    			} else if(op == 'up') {
    				opNodes[i].addEventListener('click', this._onUpComp.bind(this))
    			} else if(op == 'down') {
    				opNodes[i].addEventListener('click', this._onDownComp.bind(this))
    			}
    		}
		},
		_onDeleteComp: function() {
			var selNode = this.editorFrame.contentWindow.document.querySelector('.bfeditor-comp-select')
			if(selNode) {
				selNode.remove();
				BFEditor.hideToolBar();
				this.ueditor.fireEvent('contentchange');
			}
		},
		_onInsertBeforeComp: function() {
			var selNode = this.editorFrame.contentWindow.document.querySelector('.bfeditor-comp-select')
			if(selNode) {
				selNode.insertAdjacentHTML('beforeBegin', '<p></p>')
				this.ueditor.fireEvent('contentchange');
			}
		},
		_onInsertAfterComp: function() {
			var selNode = this.editorFrame.contentWindow.document.querySelector('.bfeditor-comp-select')
			if(selNode) {
				selNode.insertAdjacentHTML('afterEnd', '<p></p>')
				this.ueditor.fireEvent('contentchange');
			}
		},
		_onUpComp: function() {
			var selNode = this.editorFrame.contentWindow.document.querySelector('.bfeditor-comp-select')
			if(selNode) {
				if(selNode.previousElementSibling) {
					selNode.parentNode.insertBefore(selNode, selNode.previousElementSibling);
					this.ueditor.fireEvent('contentchange');
				}
			}
		},
		_onDownComp: function() {
			var selNode = this.editorFrame.contentWindow.document.querySelector('.bfeditor-comp-select')
			if(selNode.nextElementSibling) {
				selNode.nextElementSibling.parentNode.insertBefore(selNode.nextElementSibling, selNode);
				this.ueditor.fireEvent('contentchange');
			}
		},
		getContent: function() {
			var cont = this.ueditor.getContent();
			return cont.replace(/bfeditor-comp/gm, '');
    		}
	}
})();

var ue = UE.getEditor('container');
ue.addListener('ready', function() {
	BFEditor.init({ueditor: ue});
	ue.fireEvent('contentchange');
});
