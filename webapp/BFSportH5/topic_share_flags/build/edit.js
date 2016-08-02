webpackJsonp([0],{

/***/ 0:
/*!******************!*\
  !*** multi edit ***!
  \******************/
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(/*! babel-polyfill */1);
	module.exports = __webpack_require__(/*! ./src/edit.js */298);


/***/ },

/***/ 298:
/*!*********************!*\
  !*** ./src/edit.js ***!
  \*********************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	__webpack_require__(/*! babel-polyfill */ 1);
	
	__webpack_require__(/*! ./common.css */ 300);
	
	__webpack_require__(/*! ./edit.css */ 304);
	
	var _data = __webpack_require__(/*! ./data */ 306);
	
	var _data2 = _interopRequireDefault(_data);
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	var _reactDom = __webpack_require__(/*! react-dom */ 308);
	
	var ReactDOM = _interopRequireWildcard(_reactDom);
	
	var _editImage = __webpack_require__(/*! ./components/edit-image/edit-image */ 309);
	
	var _editImage2 = _interopRequireDefault(_editImage);
	
	var _editTxtSend = __webpack_require__(/*! ./components/edit-txt-send/edit-txt-send */ 320);
	
	var _editTxtSend2 = _interopRequireDefault(_editTxtSend);
	
	var _dialogBox = __webpack_require__(/*! ./components/dialog-box/dialog-box */ 323);
	
	var _dialogBox2 = _interopRequireDefault(_dialogBox);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 编辑页面
	 */
	var Edit = function (_React$Component) {
	    _inherits(Edit, _React$Component);
	
	    function Edit(props) {
	        _classCallCheck(this, Edit);
	
	        //请求浏览器存储数据
	        var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(Edit).call(this, props));
	
	        _this.editStatusHandler = function (id, img) {
	            if (img) _data.DataEdit.canvasImg = img;
	            _this.setState({ editStatus: id });
	        };
	
	        _this.closeDialogHandler = function () {
	            _this.setState({ cookiePrompt: false });
	        };
	
	        _this.userGet = function (id) {
	            if (id) {
	                $.ajax({
	                    type: 'GET',
	                    url: _data.API.sportsGetUser + id,
	                    //data: id,
	                    dataType: 'json',
	                    timeout: 500,
	                    success: function (data) {
	                        var s = data.token + ':' + id;
	                        _data2.default.base64TokenUserId = _data.Utils.b64btoa(s);
	                        _data2.default.userToken = _data.Utils.b64btoa(data.token);
	                        this.userLogin();
	                    }.bind(_this),
	                    error: function (xhr, type) {
	                        console.log(_data.API.sportsGetUser + id);
	                    }.bind(_this)
	                });
	            }
	        };
	
	        _this.userLogin = function () {
	            $.ajax({
	                type: 'POST',
	                timeout: 500,
	                url: _data.API.sportsLogin,
	                data: {
	                    id: _data2.default.bfuid,
	                    nickname: _data.Utils.b64btoa(_data2.default.bfuname),
	                    token: _data2.default.userToken
	                },
	                error: function (xhr, type) {
	                    console.log(_data.API.sportsLogin);
	                }.bind(_this)
	            });
	        };
	
	        _data.Utils.getInitDataCookie();
	
	        //请求浏览器存储数据,主要是url传参或者跳到第三方页面前保存的数据,跳回来后需要恢复跳前页面状态。
	        _data.Utils.getInitDataStorage();
	
	        //话题标题
	        document.title = _data2.default.topicTitle;
	
	        /**
	         * editStatus: number 编辑状态 0 编辑图片 1编辑文字
	         * cookiePrompt:Boolean 是否显示设置cookie的提示,如果是登录页跳转回来后,依然没有获得uid,则就是cookie设置问题
	         */
	        _this.state = {
	            editStatus: _data.DataEdit.editStatus,
	            cookiePrompt: Boolean(_data.DataEdit.editStatus == 1 && !_data2.default.bfuid)
	        };
	        return _this;
	    }
	
	    _createClass(Edit, [{
	        key: 'componentDidMount',
	        value: function componentDidMount() {
	            //请求服务器的初始数据
	            this.userGet(_data2.default.bfuid);
	        }
	    }, {
	        key: 'render',
	        value: function render() {
	
	            //设置cookie的提示
	            var cookiePromptComp = null;
	            if (this.state.cookiePrompt) {
	                var dialogProps = {
	                    animate: 'scale',
	                    txtc: '获取登录信息失败,请点击设置-Safari-阻止Cookie,修改为始终允许,然后再登录一次。',
	                    styleb1: 'btn1',
	                    txtb1: '关闭',
	                    closeHandler: this.closeDialogHandler
	                };
	                cookiePromptComp = React.createElement(_dialogBox2.default, dialogProps);
	            }
	
	            return React.createElement(
	                'div',
	                { className: 'edit pwh' },
	                React.createElement(_editImage2.default, { editStatus: this.state.editStatus, editStatusHandler: this.editStatusHandler }),
	                React.createElement(_editTxtSend2.default, { editStatus: this.state.editStatus,
	                    canvasImg: _data.DataEdit.canvasImg,
	                    text: _data.DataEdit.text,
	                    editStatusHandler: this.editStatusHandler }),
	                cookiePromptComp
	            );
	        }
	
	        /**
	         * 改变编辑状态
	         * @param id 状态id
	         * @param img 编辑完的图片
	         */
	
	
	        /**
	         * 关闭对话框
	         */
	
	    }]);
	
	    return Edit;
	}(React.Component);
	
	ReactDOM.render(React.createElement(Edit, null), document.getElementById('mountNode'));

/***/ },

/***/ 304:
/*!**********************!*\
  !*** ./src/edit.css ***!
  \**********************/
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ },

/***/ 309:
/*!*************************************************!*\
  !*** ./src/components/edit-image/edit-image.js ***!
  \*************************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	__webpack_require__(/*! ./slick.css */ 310);
	
	__webpack_require__(/*! ./style.css */ 312);
	
	var _data = __webpack_require__(/*! ../../data */ 306);
	
	var _data2 = _interopRequireDefault(_data);
	
	var _fabric = __webpack_require__(/*! fabric */ 314);
	
	var _fabric2 = _interopRequireDefault(_fabric);
	
	var _lrz = __webpack_require__(/*! lrz */ 315);
	
	var _lrz2 = _interopRequireDefault(_lrz);
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	var _editImageTopBar = __webpack_require__(/*! ./edit-image-top-bar */ 316);
	
	var _editImageTopBar2 = _interopRequireDefault(_editImageTopBar);
	
	var _editImageMain = __webpack_require__(/*! ./edit-image-main */ 317);
	
	var _editImageMain2 = _interopRequireDefault(_editImageMain);
	
	var _editImageBar = __webpack_require__(/*! ./edit-image-bar */ 319);
	
	var _editImageBar2 = _interopRequireDefault(_editImageBar);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 画布图片物体初始化配置
	 * @type {{borderColor: string, cornerColor: string, cornerSize: number, rotatingPointOffset: number, lockUniScaling: boolean}}
	 */
	var canvasImgInitOptions = {
	    cornerSize: 50,
	    transparentCorners: false,
	    padding: 10,
	    borderColor: 'rgba(33,118,255,1.0)',
	    cornerColor: 'rgba(255,255,255,1.0)',
	    cornerStrokeColor: 'rgba(33,118,255,1.0)',
	    cornerStyle: 'circle',
	    borderOpacityWhenMoving: 0.6,
	    borderScaleFactor: 0.2,
	    rotatingPointOffset: 56,
	    lockUniScaling: true
	};
	
	/**
	 * 编辑国旗组件
	 */
	
	var EditImage = function (_React$Component) {
	    _inherits(EditImage, _React$Component);
	
	    function EditImage(props) {
	        _classCallCheck(this, EditImage);
	
	        var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(EditImage).call(this, props));
	
	        _this.uploadHandler = function (evnet) {
	            evnet.preventDefault();
	
	            (0, _lrz2.default)(document.querySelector('.uploadInput').files[0], { width: 750 }).then(function (rst) {
	                this.setState({ bgImg: rst.base64 });
	                return rst;
	            }.bind(_this)).catch(function (err) {
	                // 万一出错了，这里可以捕捉到错误信息 而且以上的then都不会执行
	            }).always(function () {
	                // 不管是成功失败，这里都会执行
	            });
	        };
	
	        _this.goIndexHandler = function (evnet) {
	            evnet.preventDefault();
	            window.location.href = _data.Utils.getUrlPrefix() + '/index.html' + window.location.search;
	        };
	
	        _this.selectedHandler = function (evnet) {
	            evnet.preventDefault();
	            //取得选中元素的索引值,如果与之前选中的索引不一样,则存下来,并且替换画布国旗为选中的国旗
	            var index = Number(evnet.currentTarget.getAttribute('data-index'));
	            if (index !== _this.state.selectedFlagIndex) {
	                _this.setState({ selectedFlagIndex: index });
	                _this.replaceCanvasImg(index);
	            }
	        };
	
	        _this.nextHandler = function (evnet) {
	            //下一步，合成图片后编辑文字内容
	            evnet.preventDefault();
	
	            //去掉选中状态,不然线框控制块也会渲染成图
	            _this.editCanvas.deactivateAll();
	
	            //把背景图和遮罩图合并进来
	            var bgObj = new _fabric2.default.Image(document.querySelector('.editImageMain .bgImg'));
	            //this.editCanvas.insertAt(bgObj, 0);
	            _this.editCanvas.setBackgroundImage(bgObj);
	            var maskImage = document.querySelector('.maskImage .slick-active img');
	            var maskObj = null;
	            if (maskImage) {
	                maskObj = new _fabric2.default.Image(maskImage);
	                //this.editCanvas.add(maskObj);
	                _this.editCanvas.setOverlayImage(maskObj);
	            }
	            _this.editCanvas.backgroundColor = 'rgba(255,255,255,1.0)';
	
	            //把画布转换为data:image/png;base64,
	            var dataURL = _this.editCanvas.toDataURL({ format: 'jpeg', width: 750, height: 750, quality: 0.8, multiplier: 1 });
	
	            //保存图片数据
	            // Utils.storageSetObj(STORAGE_KEY.canvasImg, dataURL);
	
	            //window.open(dataURL);
	
	            //改变编辑状态为1,即前往编辑文字
	            _this.props.editStatusHandler(1, dataURL);
	
	            _this.editCanvas.remove(bgObj);
	            _this.editCanvas.remove(maskObj);
	            _this.editCanvas.backgroundColor = '';
	        };
	
	        _this.initCanvas = function () {
	            //画布初始化
	            _this.editCanvas = new _fabric2.default.Canvas('editCanvas', { width: 750, height: 750, selection: false, allowTouchScrolling: true, controlsAboveOverlay: true });
	
	            //画布物体移动,防止出边界的处理
	            var goodtop = void 0,
	                goodleft = void 0;
	            _this.editCanvas.on('object:moving', function (o) {
	                var obj = o.target;
	                obj.setCoords();
	                if (obj.isContainedWithinRect(new _fabric2.default.Point(0, 0), new _fabric2.default.Point(750, 750))) {
	                    goodtop = obj.top;
	                    goodleft = obj.left;
	                } else {
	                    obj.setTop(goodtop);
	                    obj.setLeft(goodleft);
	                }
	            });
	
	            //选中画布物体,则禁用触摸滚动事件
	            _this.editCanvas.on('object:selected', function (o) {
	                _this.editCanvas.allowTouchScrolling = false;
	            });
	            //取消选中画布物体,则启用触摸滚动事件
	            _this.editCanvas.on('selection:cleared', function (o) {
	                _this.editCanvas.allowTouchScrolling = true;
	            });
	
	            //初始化画布物体,默认用第一个图
	            var imgUrl1 = _data.DataEdit.flagThumbnailImages[0].img + 'left.png';
	            var imgUrl2 = _data.DataEdit.flagThumbnailImages[0].img + 'right.png';
	            _this.creatCanvasImg(imgUrl1, { left: 200, top: 400 }, 0);
	            _this.creatCanvasImg(imgUrl2, { left: 400, top: 400 }, 1);
	        };
	
	        _this.creatCanvasImg = function (url, options, index) {
	            _fabric2.default.Image.fromURL(url, function (oImg) {
	                oImg.set(Object.assign({}, canvasImgInitOptions, options));
	                _this.editCanvas.insertAt(oImg, index);
	            });
	        };
	
	        _this.replaceCanvasImg = function (index) {
	            /**
	             * 获得原图相关属性,用来赋值给替换后的新图
	             * @param obj
	             * @returns {{}}
	             */
	            function getOptions(obj) {
	                var options = {};
	                options.left = obj.getLeft();
	                options.top = obj.getTop();
	                options.angle = obj.getAngle();
	                options.scaleX = obj.getScaleX();
	                options.scaleY = obj.getScaleY();
	                options.flipX = obj.getFlipX();
	                options.flipY = obj.getFlipY();
	                return options;
	            }
	
	            var item0Options = getOptions(_this.editCanvas.item(0));
	            var item1Options = getOptions(_this.editCanvas.item(1));
	            _this.editCanvas.clear();
	            var imgUrl1 = _data.DataEdit.flagThumbnailImages[index].img + 'left.png';
	            var imgUrl2 = _data.DataEdit.flagThumbnailImages[index].img + 'right.png';
	            _this.creatCanvasImg(imgUrl1, item0Options, 0);
	            _this.creatCanvasImg(imgUrl2, item1Options, 1);
	        };
	
	        _this.state = {
	            selectedFlagIndex: 0,
	            bgImg: _data.Utils.storageGetObj(_data.STORAGE_KEY.uploadImg)
	        };
	        return _this;
	    }
	
	    _createClass(EditImage, [{
	        key: 'componentDidMount',
	        value: function componentDidMount() {
	            this.initCanvas();
	        }
	    }, {
	        key: 'render',
	        value: function render() {
	            //默认不显示
	            //如果组件索引和当前状态索引一致,则显示
	            var styleObj = { display: 'none' };
	            if (this.props.editStatus === 0) styleObj = { display: 'block' };
	
	            return React.createElement(
	                'div',
	                { className: 'editImage', style: styleObj },
	                React.createElement(_editImageTopBar2.default, { title: _data2.default.topicTitle,
	                    goIndexHandler: this.goIndexHandler,
	                    uploadHandler: this.uploadHandler }),
	                React.createElement(_editImageMain2.default, { bg: this.state.bgImg,
	                    data: _data.DataEdit.maskImages }),
	                React.createElement(_editImageBar2.default, { index: this.state.selectedFlagIndex,
	                    data: _data.DataEdit.flagThumbnailImages,
	                    selectedHandler: this.selectedHandler }),
	                React.createElement(
	                    'button',
	                    { className: 'nextBtn', onClick: this.nextHandler },
	                    '下一步'
	                ),
	                React.createElement(
	                    'div',
	                    { className: 'tips' },
	                    '小提示，脸上的国旗是可以扭动的哦'
	                )
	            );
	        }
	
	        /**
	         * 上传图片
	         * @param evnet
	         */
	
	
	        /**
	         * 去首页
	         * @param evnet
	         */
	
	
	        /**
	         * 选中国旗缩略图事件
	         * @param evnet
	         */
	
	
	        /**
	         * 初始化画布相关
	         */
	
	
	        /**
	         * 创建画布图片物体
	         * @param url
	         * @param options
	         * @param index
	         */
	
	
	        /**
	         * 替换画布图片物体
	         */
	
	    }]);
	
	    return EditImage;
	}(React.Component);
	
	EditImage.propTypes = {
	    editStatusHandler: React.PropTypes.func.isRequired, //改变编辑状态
	    editStatus: React.PropTypes.number.isRequired //编辑状态
	};
	exports.default = EditImage;

/***/ },

/***/ 310:
/*!*********************************************!*\
  !*** ./src/components/edit-image/slick.css ***!
  \*********************************************/
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ },

/***/ 312:
/*!*********************************************!*\
  !*** ./src/components/edit-image/style.css ***!
  \*********************************************/
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ },

/***/ 314:
/*!*************************!*\
  !*** external "fabric" ***!
  \*************************/
/***/ function(module, exports) {

	module.exports = fabric;

/***/ },

/***/ 316:
/*!*********************************************************!*\
  !*** ./src/components/edit-image/edit-image-top-bar.js ***!
  \*********************************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	__webpack_require__(/*! ./style.css */ 312);
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 编辑页面顶部横栏
	 */
	var EditImageTopBar = function (_React$Component) {
	    _inherits(EditImageTopBar, _React$Component);
	
	    function EditImageTopBar() {
	        _classCallCheck(this, EditImageTopBar);
	
	        return _possibleConstructorReturn(this, Object.getPrototypeOf(EditImageTopBar).apply(this, arguments));
	    }
	
	    _createClass(EditImageTopBar, [{
	        key: 'render',
	        value: function render() {
	            return React.createElement(
	                'div',
	                { className: 'topBar' },
	                React.createElement(
	                    'button',
	                    { className: 'btn1', onClick: this.props.goIndexHandler },
	                    '取消'
	                ),
	                React.createElement(
	                    'h1',
	                    null,
	                    this.props.title
	                ),
	                React.createElement(
	                    'div',
	                    { className: 'btn2' },
	                    '重拍',
	                    React.createElement('input', { className: 'uploadInput', type: 'file', capture: 'camera', accept: 'image/*', id: 'file',
	                        onChange: this.props.uploadHandler })
	                )
	            );
	        }
	    }]);
	
	    return EditImageTopBar;
	}(React.Component);
	
	EditImageTopBar.propTypes = {
	    title: React.PropTypes.string.isRequired,
	    goIndexHandler: React.PropTypes.func.isRequired,
	    uploadHandler: React.PropTypes.func.isRequired
	};
	exports.default = EditImageTopBar;

/***/ },

/***/ 317:
/*!******************************************************!*\
  !*** ./src/components/edit-image/edit-image-main.js ***!
  \******************************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	var _reactSlick = __webpack_require__(/*! react-slick */ 318);
	
	var _reactSlick2 = _interopRequireDefault(_reactSlick);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 编辑图片区域
	 */
	var EditImageMain = function (_React$Component) {
	    _inherits(EditImageMain, _React$Component);
	
	    function EditImageMain() {
	        _classCallCheck(this, EditImageMain);
	
	        return _possibleConstructorReturn(this, Object.getPrototypeOf(EditImageMain).apply(this, arguments));
	    }
	
	    _createClass(EditImageMain, [{
	        key: 'render',
	        value: function render() {
	            //组件配置
	            var settings = {
	                infinite: true,
	                slidesToShow: 1,
	                slidesToScroll: 1,
	                draggable: false
	            };
	
	            var list = this.props.data.map(function (item) {
	                return React.createElement(
	                    'div',
	                    null,
	                    React.createElement('img', { src: item })
	                );
	            });
	
	            //按需求,第一个无图
	            list.unshift(React.createElement('div', null));
	
	            return React.createElement(
	                'div',
	                { className: 'editImageMain' },
	                React.createElement('img', { className: 'bgImg', src: this.props.bg }),
	                React.createElement('canvas', { id: 'editCanvas' }),
	                React.createElement(
	                    _reactSlick2.default,
	                    _extends({ className: 'maskImage' }, settings),
	                    list
	                )
	            );
	        }
	    }]);
	
	    return EditImageMain;
	}(React.Component);
	
	EditImageMain.propTypes = {
	    bg: React.PropTypes.string.isRequired,
	    data: React.PropTypes.array.isRequired
	
	};
	exports.default = EditImageMain;

/***/ },

/***/ 318:
/*!*************************!*\
  !*** external "Slider" ***!
  \*************************/
/***/ function(module, exports) {

	module.exports = Slider;

/***/ },

/***/ 319:
/*!*****************************************************!*\
  !*** ./src/components/edit-image/edit-image-bar.js ***!
  \*****************************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	var _reactSlick = __webpack_require__(/*! react-slick */ 318);
	
	var _reactSlick2 = _interopRequireDefault(_reactSlick);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 编辑国旗栏
	 */
	var EditImageBar = function (_React$Component) {
	    _inherits(EditImageBar, _React$Component);
	
	    function EditImageBar() {
	        _classCallCheck(this, EditImageBar);
	
	        return _possibleConstructorReturn(this, Object.getPrototypeOf(EditImageBar).apply(this, arguments));
	    }
	
	    _createClass(EditImageBar, [{
	        key: 'render',
	        value: function render() {
	            var _this2 = this;
	
	            //组件配置
	            var settings = {
	                infinite: false,
	                slidesToShow: 5,
	                slidesToScroll: 5,
	                arrows: false
	            };
	
	            var list = this.props.data.map(function (item, index) {
	                //给当前选中的国旗小图添加选中样式
	                var selected = '';
	                if (index === _this2.props.index) selected = 'slick-selected';
	                return React.createElement(
	                    'div',
	                    { className: selected, onClick: _this2.props.selectedHandler },
	                    React.createElement('img', { src: item.img + '.png' }),
	                    React.createElement(
	                        'div',
	                        null,
	                        item.title
	                    )
	                );
	            });
	
	            return React.createElement(
	                'div',
	                { className: 'editImageBar' },
	                React.createElement(
	                    _reactSlick2.default,
	                    settings,
	                    list
	                )
	            );
	        }
	    }]);
	
	    return EditImageBar;
	}(React.Component);
	
	EditImageBar.propTypes = {
	    data: React.PropTypes.array.isRequired,
	    index: React.PropTypes.number.isRequired,
	    selectedHandler: React.PropTypes.func.isRequired
	};
	exports.default = EditImageBar;

/***/ },

/***/ 320:
/*!*******************************************************!*\
  !*** ./src/components/edit-txt-send/edit-txt-send.js ***!
  \*******************************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	__webpack_require__(/*! ./style.css */ 321);
	
	var _data = __webpack_require__(/*! ../../data */ 306);
	
	var _data2 = _interopRequireDefault(_data);
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	var _dialogBox = __webpack_require__(/*! ../dialog-box/dialog-box */ 323);
	
	var _dialogBox2 = _interopRequireDefault(_dialogBox);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 弹出框内容
	 */
	var DialogContent = ['发送中...', '发送成功', '发送失败', '请输入内容', '确定退出?', '发送成功,前往首页'];
	
	/**
	 * 编辑文字发送组件
	 */
	
	var EditTxtSend = function (_React$Component) {
	    _inherits(EditTxtSend, _React$Component);
	
	    function EditTxtSend(props) {
	        _classCallCheck(this, EditTxtSend);
	
	        var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(EditTxtSend).call(this, props));
	
	        _this.closeHandler = function () {
	            /*
	             * 如果有输入文字,则弹出确认关闭的对话框
	             * 否则直接关闭编辑文字组件
	             */
	            if (_this.state.text) {
	                _this.setState({ dialogContentIndex: 4 });
	            } else {
	                _this.confirmCloseHandler();
	            }
	        };
	
	        _this.sendHandler = function () {
	            //如果已登录,直接发送数据
	            //否则跳转登录页
	            if (_data2.default.base64TokenUserId) {
	                _this.setState({ dialogContentIndex: 0 });
	                _this.sendImgData();
	            } else {
	                if (_this.state.text) {
	                    _data.DataEdit.text = _data.Utils.cutStrForNum(_this.state.text, 1000);
	                }
	
	                //保存数据,跳回来恢复页面用
	                _data.Utils.setInitDataStorage();
	                //var loginUrl = 'http://sso.baofeng.net/api/mlogin/default?from=sports_h5&version=1&did=&btncolor=blue&next_action=' + sportsH5Url + '&selfjumpurl=' + sportsH5Url;
	                var url = encodeURIComponent(window.location.href);
	                window.location.href = _data.CONFIG.loginUrl + url + '&selfjumpurl=' + url;
	            }
	        };
	
	        _this.sendImgData = function () {
	            //错误处理
	            var timeoutID = setTimeout(function () {
	                if (timeoutID && this) this.setState({ dialogContentIndex: 2 });
	            }.bind(_this), 800);
	
	            var xhr = new XMLHttpRequest();
	            xhr.timeout = 800;
	            xhr.open('POST', _data.API.uploadImage);
	            xhr.onload = function (e) {
	                if (xhr.status === 200) {
	                    console.log('上传成功');
	                    var data = JSON.parse(xhr.response);
	                    if (data.errno === null || data.errno === 10000) {
	                        this.sendAllData(data.data.pid);
	                        if (timeoutID) {
	                            clearTimeout(timeoutID);
	                            timeoutID = undefined;
	                        }
	                    }
	                }
	            }.bind(_this);
	
	            // 发送数据
	            var formData = new FormData();
	            formData.append('image', _data.Utils.dataURItoBlob(_data.DataEdit.canvasImg));
	            formData.append('user', _data2.default.base64TokenUserId);
	            xhr.send(formData);
	        };
	
	        _this.sendAllData = function (imgpid) {
	            $.ajax({
	                type: 'POST',
	                timeout: 500,
	                url: _data.API.topicPost,
	                data: {
	                    id: _data2.default.topicId,
	                    user_id: _data2.default.bfuid,
	                    content: _this.state.text,
	                    pid: imgpid
	                },
	                success: function (data) {
	                    if (data.errno === null || data.errno === 10000) {
	                        //跳回首页显示分享提示
	                        _data.Utils.storageSetObj(_data.STORAGE_KEY.share, true);
	                        //置顶当前发送成功的回复
	                        _data2.default.topicReplyId = Number(data.data.post.id);
	                        //提示发送成功并回首页
	                        this.setState({ dialogContentIndex: 5 });
	                        setTimeout(function () {
	                            this.goIndexHandler();
	                        }.bind(this), 1000);
	                    } else {
	                        this.setState({ dialogContentIndex: 2 });
	                    }
	                }.bind(_this),
	                error: function (xhr, type) {
	                    this.setState({ dialogContentIndex: 2 });
	                }.bind(_this)
	            });
	        };
	
	        _this.textChangeHandler = function (event) {
	            _this.setState({ text: event.target.value });
	        };
	
	        _this.closeDialogHandler = function () {
	            if (_this.state.dialogContentIndex === 1) {
	                _this.confirmCloseHandler();
	            } else {
	                _this.setState({ dialogContentIndex: -1 });
	            }
	        };
	
	        _this.confirmCloseHandler = function () {
	            _this.setState({ dialogContentIndex: -1 });
	            //改变编辑状态为0,即回到编辑图片
	            _this.props.editStatusHandler(0);
	        };
	
	        _this.goIndexHandler = function (event) {
	            if (event) evnet.preventDefault();
	            window.location.href = _data.Utils.getUrlPrefix() + '/index.html?' + _data.URL_PARAM_NAME.replyId + '=' + _data2.default.topicReplyId;
	        };
	
	        _this.state = {
	            text: _this.props.text,
	            dialogContentIndex: -1
	        };
	        return _this;
	    }
	
	    _createClass(EditTxtSend, [{
	        key: 'componentDidMount',
	        value: function componentDidMount() {
	            //延时才能聚焦成功
	            this.refs.txt.focus();
	            setTimeout(function () {
	                this.focus();
	            }.bind(this.refs.txt), 100);
	        }
	    }, {
	        key: 'render',
	        value: function render() {
	
	            /*
	             * 对话框,如果对话框内容索引大于-1,则弹出对话框
	             * 内部逻辑看DialogContent常量内容
	             */
	            var dialogComp = null;
	            if (this.state.dialogContentIndex > -1) {
	                var dialogProps = {
	                    animate: 'scale',
	                    txtc: DialogContent[this.state.dialogContentIndex]
	                };
	
	                switch (this.state.dialogContentIndex) {
	                    case 1:
	                    case 2:
	                    case 3:
	                        dialogProps.styleb1 = 'btn1';
	                        dialogProps.txtb1 = '关闭';
	                        dialogProps.closeHandler = this.closeDialogHandler;
	                        break;
	                    case 4:
	                        dialogProps.styleb1 = 'btn2';
	                        dialogProps.txtb1 = '取消';
	                        dialogProps.closeHandler = this.closeDialogHandler;
	                        dialogProps.styleb2 = 'btn3';
	                        dialogProps.txtb2 = '确认';
	                        dialogProps.confirmHandler = this.confirmCloseHandler;
	                        break;
	                    case 5:
	                        dialogProps.styleb1 = 'btn1';
	                        dialogProps.txtb1 = '确认';
	                        dialogProps.closeHandler = this.goIndexHandler;
	                        break;
	                }
	
	                dialogComp = React.createElement(_dialogBox2.default, dialogProps);
	            }
	
	            //没有输入内容时发送按钮的样式
	            var noContentStyle = 'btn2';
	            if (this.state.text) noContentStyle = 'btn2 btn3';
	
	            //默认不显示
	            //如果组件索引和当前状态索引一致,则显示
	            var styleObj = { display: 'none' };
	            if (this.props.editStatus === 1) styleObj = { display: 'block' };
	
	            return React.createElement(
	                'div',
	                { className: 'editTxtSend', style: styleObj },
	                React.createElement(
	                    'div',
	                    { ref: 'box' },
	                    React.createElement(
	                        'div',
	                        { className: 'topBar' },
	                        React.createElement(
	                            'button',
	                            { className: 'btn1', onClick: this.closeHandler },
	                            '取消'
	                        ),
	                        React.createElement(
	                            'h1',
	                            null,
	                            _data2.default.topicTitle
	                        ),
	                        React.createElement(
	                            'button',
	                            { className: noContentStyle, onClick: this.sendHandler },
	                            '发送'
	                        )
	                    ),
	                    React.createElement('textarea', { ref: 'txt', className: 'textarea', placeholder: '请输入回复内容,限制1000字', autofocus: true,
	                        value: _data.Utils.cutStrForNum(this.state.text, 1000),
	                        onChange: this.textChangeHandler }),
	                    React.createElement('img', { className: 'img1', src: this.props.canvasImg })
	                ),
	                dialogComp
	            );
	        }
	
	        /**
	         * 取消按钮点击事件,关闭编辑文字组件
	         */
	
	
	        /**
	         * 发送按钮点击事件
	         */
	
	
	        /**
	         * 发送图片数据
	         */
	
	
	        /**
	         * 发送图文数据
	         */
	
	
	        /**
	         * 保存输入的文字数据
	         * @param event
	         */
	
	
	        /**
	         * 关闭对话框
	         */
	
	
	        /**
	         * 确认关闭编辑文字组件
	         */
	
	
	        /**
	         * 去首页
	         */
	
	    }]);
	
	    return EditTxtSend;
	}(React.Component);
	
	EditTxtSend.propTypes = {
	    editStatusHandler: React.PropTypes.func.isRequired, //改变编辑状态
	    editStatus: React.PropTypes.number.isRequired, //编辑状态
	    canvasImg: React.PropTypes.string, //编辑完成的图片
	    text: React.PropTypes.string //编辑好的文字
	};
	exports.default = EditTxtSend;

/***/ },

/***/ 321:
/*!************************************************!*\
  !*** ./src/components/edit-txt-send/style.css ***!
  \************************************************/
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ },

/***/ 323:
/*!*************************************************!*\
  !*** ./src/components/dialog-box/dialog-box.js ***!
  \*************************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	__webpack_require__(/*! ./style.css */ 324);
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 弹出提示组件
	 */
	var DialogBox = function (_React$Component) {
	    _inherits(DialogBox, _React$Component);
	
	    function DialogBox() {
	        _classCallCheck(this, DialogBox);
	
	        return _possibleConstructorReturn(this, Object.getPrototypeOf(DialogBox).apply(this, arguments));
	    }
	
	    _createClass(DialogBox, [{
	        key: 'componentDidMount',
	        value: function componentDidMount() {
	            //如果组件出现动画效果为放大
	            if (this.props.animate === 'scale') {
	                $(this.refs.contentBox).css({ transform: 'scale(0, 0) translateX(-50%)', transformOrigin: '0 50%' });
	                $(this.refs.contentBox).animate({
	                    transform: 'scale(1, 1) translateX(-50%)'
	                }, 100, 'ease-in');
	            }
	
	            //淡入
	            if (this.props.animate === 'fade') {
	                $(this.refs.contentBox).css({ opacity: 0.0 });
	                $(this.refs.contentBox).animate({
	                    opacity: 1.0
	                }, 100, 'ease-in');
	            }
	
	            //自动关闭
	            if (this.props.autoCloseTime && this.props.closeHandler) {
	                setTimeout(this.props.closeHandler, this.props.autoCloseTime);
	            }
	        }
	    }, {
	        key: 'render',
	        value: function render() {
	            //第一个按钮,通过为关闭,取消,no之类
	            var btn1Comp = null;
	            if (this.props.txtb1) {
	                btn1Comp = React.createElement(
	                    'button',
	                    { className: 'btn0 ' + this.props.styleb1,
	                        onClick: this.props.closeHandler },
	                    this.props.txtb1
	                );
	            }
	            //第二个按钮,通常为确定,yes之类
	            var btn2Comp = null;
	            if (this.props.txtb2) {
	                btn2Comp = React.createElement(
	                    'button',
	                    { className: 'btn0 ' + this.props.styleb2,
	                        onClick: this.props.confirmHandler },
	                    this.props.txtb2
	                );
	            }
	
	            //全部为关闭事件热区
	            var dialogBoxCloseHandler = null;
	            var overlayCloseHandler = this.props.closeHandler;
	            if (this.props.allHitClose) {
	                dialogBoxCloseHandler = this.props.closeHandler;
	                overlayCloseHandler = null;
	            }
	
	            return React.createElement(
	                'div',
	                { className: 'dialogBox', onClick: dialogBoxCloseHandler },
	                React.createElement('div', { className: this.props.styleo, onClick: overlayCloseHandler }),
	                React.createElement(
	                    'div',
	                    { ref: 'contentBox', className: this.props.stylec },
	                    React.createElement(
	                        'div',
	                        { className: this.props.stylect },
	                        this.props.txtc
	                    ),
	                    btn1Comp,
	                    btn2Comp
	                )
	            );
	        }
	    }]);
	
	    return DialogBox;
	}(React.Component);
	
	DialogBox.defaultProps = {
	    styleo: 'overlay1', //对话框遮罩背景样式
	    stylec: 'content1', //对话框内容box的样式
	    stylect: 'font1' //对话框内容文本的样式
	};
	DialogBox.propTypes = {
	    txtc: React.PropTypes.string.isRequired, // 对话框内容
	    stylec: React.PropTypes.string, // 对话框内容box的样式类名
	    stylect: React.PropTypes.string, // 对话框内容文本的样式类名
	    animate: React.PropTypes.string, // 对话框的动画效果
	    styleb1: React.PropTypes.string, // 对话框按钮1样式类名
	    txtb1: React.PropTypes.string, // 对话框按钮1文字
	    styleb2: React.PropTypes.string, // 对话框按钮2样式类名
	    txtb2: React.PropTypes.string, // 对话框按钮2文字
	    closeHandler: React.PropTypes.func, // 对话框关闭回调函数
	    confirmHandler: React.PropTypes.func, // 对话框确认回调函数
	    allHitClose: React.PropTypes.bool, // 是否全部为关闭事件热区
	    autoCloseTime: React.PropTypes.number, //自动关闭对话框时间
	    styleo: React.PropTypes.string // 对话框遮罩背景样式
	};
	exports.default = DialogBox;

/***/ },

/***/ 324:
/*!*********************************************!*\
  !*** ./src/components/dialog-box/style.css ***!
  \*********************************************/
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ }

});
//# sourceMappingURL=edit.js.map