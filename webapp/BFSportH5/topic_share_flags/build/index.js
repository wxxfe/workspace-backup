webpackJsonp([1],{

/***/ 0:
/*!*******************!*\
  !*** multi index ***!
  \*******************/
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(/*! babel-polyfill */1);
	module.exports = __webpack_require__(/*! ./src/index.js */299);


/***/ },

/***/ 299:
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	__webpack_require__(/*! babel-polyfill */ 1);
	
	__webpack_require__(/*! ./common.css */ 300);
	
	__webpack_require__(/*! ./index.css */ 326);
	
	var _data = __webpack_require__(/*! ./data */ 306);
	
	var _data2 = _interopRequireDefault(_data);
	
	var _baiduHmt = __webpack_require__(/*! baidu-hmt */ 328);
	
	var _baiduHmt2 = _interopRequireDefault(_baiduHmt);
	
	var _zepto = __webpack_require__(/*! zepto */ 329);
	
	var _zepto2 = _interopRequireDefault(_zepto);
	
	var _lrz = __webpack_require__(/*! lrz */ 315);
	
	var _lrz2 = _interopRequireDefault(_lrz);
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	var _reactDom = __webpack_require__(/*! react-dom */ 308);
	
	var ReactDOM = _interopRequireWildcard(_reactDom);
	
	var _adFixed = __webpack_require__(/*! ./components/ad-fixed/ad-fixed */ 330);
	
	var _adFixed2 = _interopRequireDefault(_adFixed);
	
	var _adInterlude = __webpack_require__(/*! ./components/ad-interlude/ad-interlude */ 334);
	
	var _adInterlude2 = _interopRequireDefault(_adInterlude);
	
	var _refreshLoadmore = __webpack_require__(/*! ./components/refresh-loadmore/refresh-loadmore */ 337);
	
	var _refreshLoadmore2 = _interopRequireDefault(_refreshLoadmore);
	
	var _replyContent = __webpack_require__(/*! ./components/reply-content/reply-content */ 341);
	
	var _replyContent2 = _interopRequireDefault(_replyContent);
	
	var _replyList = __webpack_require__(/*! ./components/reply-list/reply-list */ 348);
	
	var _replyList2 = _interopRequireDefault(_replyList);
	
	var _sharePrompt = __webpack_require__(/*! ./components/share-prompt/share-prompt */ 351);
	
	var _sharePrompt2 = _interopRequireDefault(_sharePrompt);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 首页
	 */
	var Index = function (_React$Component) {
	    _inherits(Index, _React$Component);
	
	    function Index(props) {
	        _classCallCheck(this, Index);
	
	        // Utils.docCookiesSetItem('bfuid','135601920077074447');//测试数据
	        // DataConfig.bfuid = Utils.docCookiesGetItem('bfuid');//测试数据
	        // Data.topicReplyId = '1';//测试数据
	
	        //清除本功能浏览器历史存储数据
	        var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(Index).call(this, props));
	
	        _this.repliesListGet = function (id, refresher, more) {
	            if (id) {
	                var key = null;
	                if (more && _this.state.repliesList) {
	                    var current = _this.state.repliesList[_data.DataIndex.listOrderTypeIndex];
	                    if (current.length) {
	                        key = current[current.length - 1].key;
	                    }
	                }
	
	                var dataObj = {
	                    id: id,
	                    order_type: _data.DataIndex.listOrderType[_data.DataIndex.listOrderTypeIndex],
	                    key: key
	                };
	
	                _zepto2.default.ajax({
	                    type: 'GET',
	                    url: _data.API.topicList,
	                    data: dataObj,
	                    dataType: 'json',
	                    timeout: 500,
	                    success: function (data) {
	                        var _this2 = this;
	
	                        var list = null;
	                        //如果有有效数据,是不为空的数组
	                        if (data && data.data && data.data.list && Array.isArray(data.data.list.posts) && data.data.list.posts.length) {
	                            (function () {
	
	                                //浏览器数据记录已经点赞的,点赞数减1。
	                                var allLiked = _data.Utils.getLiked();
	                                var newList = data.data.list.posts.map(function (item) {
	                                    if (allLiked && allLiked.indexOf(item.id) !== -1) {
	                                        item.likes--;
	                                    }
	                                    return item;
	                                });
	
	                                list = _this2.state.repliesList;
	                                //如果没有初始数据,则初始化
	                                if (!list) list = [[], []];
	
	                                //如果是更多,则加到已有数组后面
	                                //否则替换更新数组
	                                if (more) {
	                                    list[_data.DataIndex.listOrderTypeIndex] = list[_data.DataIndex.listOrderTypeIndex].concat(newList);
	                                } else {
	                                    list[_data.DataIndex.listOrderTypeIndex] = newList;
	                                }
	                            })();
	                        }
	                        //如果是加载更多,但是没有数据的情况,则不改变state数据
	                        if (!(more && list === null)) {
	                            this.setState({ repliesList: list });
	                        }
	                        if (refresher) _refreshLoadmore.Refresher.refresh(refresher);
	                    }.bind(_this),
	                    error: function (xhr, type) {
	                        console.log(_data.API.topicList + '?id=' + id + ' ' + more);
	                        //如果是加载更多的错误不改变state数据
	                        if (!more) {
	                            this.setState({ repliesList: null });
	                        }
	                        if (refresher) _refreshLoadmore.Refresher.refresh(refresher);
	                    }.bind(_this)
	                });
	            } else {
	                _this.setState({ repliesList: null });
	                if (refresher) _refreshLoadmore.Refresher.refresh(refresher);
	            }
	        };
	
	        _this.replyTopGet = function (id) {
	            if (id) {
	                _zepto2.default.ajax({
	                    type: 'GET',
	                    url: _data.API.topicOne,
	                    data: { id: id },
	                    dataType: 'json',
	                    timeout: 500,
	                    success: function (data) {
	                        if (data.data && data.data.body) {
	                            this.setState({ replyTop: data.data.body });
	                        } else {
	                            this.setState({ replyTop: null });
	                        }
	                    }.bind(_this),
	                    error: function (xhr, type) {
	                        console.log(_data.API.topicOne + '?id=' + id);
	                        this.setState({ replyTop: null });
	                    }.bind(_this)
	                });
	            } else {
	                _this.setState({ replyTop: null });
	            }
	        };
	
	        _this.userGet = function (id) {
	            if (id) {
	                _zepto2.default.ajax({
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
	            _zepto2.default.ajax({
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
	
	        _this.editHandler = function (evnet) {
	            evnet.preventDefault();
	
	            (0, _lrz2.default)(_this.refs.uploadimg.files[0], { width: 750 }).then(function (rst) {
	                _data.Utils.storageSetObj(_data.STORAGE_KEY.uploadImg, rst.base64);
	                window.location.href = _data.Utils.getUrlPrefix() + '/edit.html' + window.location.search;
	                return rst;
	            }.bind(_this)).catch(function (err) {
	                // 万一出错了，这里可以捕捉到错误信息 而且以上的then都不会执行
	            }).always(function () {
	                // 不管是成功失败，这里都会执行
	            });
	
	            //window.location.href = '/edit.html' + window.location.search;
	            _baiduHmt2.default.push(['_trackEvent', 'H5国旗话题分享', '点击参加按钮', '参加']);
	        };
	
	        _this.closeShareHandler = function (evnet) {
	            evnet.preventDefault();
	            _this.setState({ share: false });
	        };
	
	        _data.Utils.setInitDataStorage(true);
	
	        //请求浏览器存储数据
	        _data.Utils.getInitDataCookie();
	
	        //话题标题
	        document.title = _data2.default.topicTitle;
	
	        /*
	         * 初始值undefined 如果是异步回调没有得到有效数据,则赋值为null, undefined和null表示两种状态,null状态有文字提示
	         * repliesList 回复列表数组,
	         * replyTop 置顶回复,数据和列表中单个一样
	         * share 是否显示分享提示,如果是发送作品跳回首页,则显示
	         */
	        _this.state = {
	            repliesList: undefined,
	            replyTop: undefined,
	            share: _data.Utils.storageGetObj(_data.STORAGE_KEY.share)
	        };
	
	        //清除是否显示分享提示的数据
	        window.localStorage.removeItem(_data.STORAGE_KEY.share);
	        return _this;
	    }
	
	    _createClass(Index, [{
	        key: 'componentDidMount',
	        value: function componentDidMount() {
	            //请求服务器的初始数据
	            this.userGet(_data2.default.bfuid);
	            this.replyTopGet(_data2.default.topicReplyId);
	            this.repliesListGet(_data2.default.topicId);
	
	            //下拉刷新和上拉加载更多
	            _refreshLoadmore.Refresher.init({
	                scrollBox: this.refs.scrollBox,
	                downPromptBox: ReactDOM.findDOMNode(this.refs.pullDown),
	                downCallback: function (refresher) {
	                    console.log("Refresh");
	                    this.repliesListGet(_data2.default.topicId, refresher);
	                }.bind(this),
	                upPromptBox: ReactDOM.findDOMNode(this.refs.pullUp),
	                upCallback: function (refresher) {
	                    console.log("Load More");
	                    this.repliesListGet(_data2.default.topicId, refresher, true);
	                }.bind(this)
	            });
	        }
	    }, {
	        key: 'render',
	        value: function render() {
	            //标题
	            var titleComp = React.createElement(
	                'h1',
	                { className: 'indexTitle indexTitleOne' },
	                _data2.default.topicTitle
	            );
	
	            //置顶话题介绍
	            var topInfoComp = React.createElement(
	                'p',
	                { className: 'indexTopInfo' },
	                _data.DataIndex.topicInfo
	            );
	
	            //上拉下拉的提示
	            var refreshInfoComp = React.createElement(_refreshLoadmore2.default, { ref: 'pullDown', c1: 'pullDown', c2: 'pullDownLabel', t1: '下拉刷新...' });
	            var loadMoreInfoComp = React.createElement(_refreshLoadmore2.default, { ref: 'pullUp', c1: 'pullUp', c2: 'pullUpLabel', t1: '上拉加载更多...' });
	
	            //置顶和列表的异步回调没有得到有效数据的提示
	            var infoComp = null;
	            if (this.state.replyTop === null && this.state.repliesList === null) {
	                infoComp = React.createElement(
	                    'h2',
	                    { className: 'indexInfo' },
	                    '该话题还没有作品'
	                );
	            } else if (!this.state.repliesList === null) {
	                infoComp = React.createElement(
	                    'h2',
	                    { className: 'indexInfo' },
	                    '该话题还没有其他作品'
	                );
	            }
	
	            //如果没有列表则直接显示广告条
	            var adInterludeComp = null;
	            if (!this.state.repliesList) {
	                adInterludeComp = React.createElement(_adInterlude2.default, null);
	            }
	
	            /**
	             * 如果有置顶的话题回复数据,则置顶此回复组件后再显示回复列表
	             * 否则只显示回复列表
	             */
	            var replyTopComp = null;
	            var repliesListTitleComp = null;
	            if (this.state.replyTop) {
	                //liked 用户已点赞的回复ID数组
	                //liked =[1];//测试数据
	                //this.state.replyTop.likes+=1;//测试数据
	                replyTopComp = React.createElement(_replyContent2.default, { data: this.state.replyTop, first: true, liked: _data.Utils.getLiked() });
	                repliesListTitleComp = React.createElement(
	                    'h1',
	                    { className: 'indexTitle indexTitleTwo' },
	                    '其他作品'
	                );
	            }
	            var replyListComp = null;
	            if (this.state.repliesList) {
	                replyListComp = React.createElement(_replyList2.default, { data: this.state.repliesList, repliesListGet: this.repliesListGet });
	            }
	
	            //编辑按钮
	            var editBTNComp = React.createElement(
	                'div',
	                { className: 'editBTNBox' },
	                React.createElement(
	                    'div',
	                    { className: 'editBTN' },
	                    React.createElement(
	                        'div',
	                        { className: 'centering' },
	                        '开始创作'
	                    ),
	                    React.createElement('input', { ref: 'uploadimg', className: 'uploadInput', type: 'file', capture: 'camera', accept: 'image/*',
	                        id: 'file',
	                        onChange: this.editHandler })
	                )
	            );
	
	            //分享提示
	            var shareComp = null;
	            if (this.state.share) {
	                shareComp = React.createElement(_sharePrompt2.default, { closeShareHandler: this.closeShareHandler });
	            }
	
	            return React.createElement(
	                'div',
	                { className: 'pwh' },
	                React.createElement(
	                    'div',
	                    { ref: 'scrollBox', className: 'scrollBox' },
	                    React.createElement(
	                        'div',
	                        { className: 'scrollContentBox', style: { 'min-height': document.documentElement.clientHeight } },
	                        refreshInfoComp,
	                        titleComp,
	                        topInfoComp,
	                        replyTopComp,
	                        repliesListTitleComp,
	                        adInterludeComp,
	                        replyListComp,
	                        infoComp,
	                        loadMoreInfoComp
	                    )
	                ),
	                editBTNComp,
	                React.createElement(_adFixed2.default, null),
	                shareComp
	            );
	        }
	    }]);
	
	    return Index;
	}(React.Component);
	
	ReactDOM.render(React.createElement(Index, null), document.getElementById('mountNode'));

/***/ },

/***/ 326:
/*!***********************!*\
  !*** ./src/index.css ***!
  \***********************/
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ },

/***/ 328:
/*!******************************!*\
  !*** external "window._hmt" ***!
  \******************************/
/***/ function(module, exports) {

	module.exports = window._hmt;

/***/ },

/***/ 329:
/*!************************!*\
  !*** external "Zepto" ***!
  \************************/
/***/ function(module, exports) {

	module.exports = Zepto;

/***/ },

/***/ 330:
/*!*********************************************!*\
  !*** ./src/components/ad-fixed/ad-fixed.js ***!
  \*********************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	var _data = __webpack_require__(/*! ../../data */ 306);
	
	__webpack_require__(/*! ./style.css */ 331);
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	var _baiduHmt = __webpack_require__(/*! baidu-hmt */ 328);
	
	var _baiduHmt2 = _interopRequireDefault(_baiduHmt);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 广告组件,固定位置
	 */
	var ADFixed = function (_React$Component) {
	    _inherits(ADFixed, _React$Component);
	
	    function ADFixed() {
	        var _Object$getPrototypeO;
	
	        var _temp, _this, _ret;
	
	        _classCallCheck(this, ADFixed);
	
	        for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
	            args[_key] = arguments[_key];
	        }
	
	        return _ret = (_temp = (_this = _possibleConstructorReturn(this, (_Object$getPrototypeO = Object.getPrototypeOf(ADFixed)).call.apply(_Object$getPrototypeO, [this].concat(args))), _this), _this.downloadHandler = function (event) {
	            event.preventDefault();
	
	            _baiduHmt2.default.push(['_trackEvent', 'H5国旗话题分享', '点击下载APP按钮', 'app']);
	
	            window.open(_data.CONFIG.downloadAppUrl, '_blank');
	        }, _temp), _possibleConstructorReturn(_this, _ret);
	    }
	
	    _createClass(ADFixed, [{
	        key: 'render',
	        value: function render() {
	            return React.createElement(
	                'div',
	                { className: 'adFixed', onClick: this.downloadHandler },
	                React.createElement('img', { src: __webpack_require__(/*! ./img/logo.png */ 333) }),
	                React.createElement(
	                    'h1',
	                    null,
	                    '暴风体育'
	                ),
	                React.createElement(
	                    'p',
	                    null,
	                    '有趣的话题，有趣的球迷'
	                ),
	                React.createElement(
	                    'button',
	                    null,
	                    '立即下载'
	                )
	            );
	        }
	    }]);
	
	    return ADFixed;
	}(React.Component);
	
	exports.default = ADFixed;

/***/ },

/***/ 331:
/*!*******************************************!*\
  !*** ./src/components/ad-fixed/style.css ***!
  \*******************************************/
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ },

/***/ 333:
/*!**********************************************!*\
  !*** ./src/components/ad-fixed/img/logo.png ***!
  \**********************************************/
/***/ function(module, exports) {

	module.exports = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAIAAAABc2X6AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3FpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDowYThjNDdjZi1lMTYzLTAzNDMtOTdiMy04MTc5YTlmZjE5YmYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NDg2RTM2QjEyNzIwMTFFNkI5RThFMUI1RkU0RUNEODIiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NDg2RTM2QjAyNzIwMTFFNkI5RThFMUI1RkU0RUNEODIiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjM0YmE2YmRhLTk5MWYtZDc0Ny05MTkxLTZlZmI4OTA2MWIxMyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDowYThjNDdjZi1lMTYzLTAzNDMtOTdiMy04MTc5YTlmZjE5YmYiLz4gPC9y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"

/***/ },

/***/ 334:
/*!*****************************************************!*\
  !*** ./src/components/ad-interlude/ad-interlude.js ***!
  \*****************************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	var _data = __webpack_require__(/*! ../../data */ 306);
	
	__webpack_require__(/*! ./style.css */ 335);
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	var _baiduHmt = __webpack_require__(/*! baidu-hmt */ 328);
	
	var _baiduHmt2 = _interopRequireDefault(_baiduHmt);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 广告组件,混在回复列表中
	 */
	var ADInterlude = function (_React$Component) {
	    _inherits(ADInterlude, _React$Component);
	
	    function ADInterlude() {
	        var _Object$getPrototypeO;
	
	        var _temp, _this, _ret;
	
	        _classCallCheck(this, ADInterlude);
	
	        for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
	            args[_key] = arguments[_key];
	        }
	
	        return _ret = (_temp = (_this = _possibleConstructorReturn(this, (_Object$getPrototypeO = Object.getPrototypeOf(ADInterlude)).call.apply(_Object$getPrototypeO, [this].concat(args))), _this), _this.downloadHandler = function (event) {
	            event.preventDefault();
	
	            _baiduHmt2.default.push(['_trackEvent', 'H5国旗话题分享', '点击下载APP按钮', 'app']);
	
	            window.open(_data.CONFIG.downloadAppUrl, '_blank');
	        }, _temp), _possibleConstructorReturn(_this, _ret);
	    }
	
	    _createClass(ADInterlude, [{
	        key: 'render',
	        value: function render() {
	            return React.createElement(
	                'div',
	                { className: 'adInterlude', onClick: this.downloadHandler },
	                React.createElement(
	                    'h1',
	                    null,
	                    '想看看大家还在热议什么？快来下载暴风体育'
	                ),
	                React.createElement(
	                    'span',
	                    null,
	                    '>'
	                )
	            );
	        }
	    }]);
	
	    return ADInterlude;
	}(React.Component);
	
	exports.default = ADInterlude;

/***/ },

/***/ 335:
/*!***********************************************!*\
  !*** ./src/components/ad-interlude/style.css ***!
  \***********************************************/
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ },

/***/ 337:
/*!*************************************************************!*\
  !*** ./src/components/refresh-loadmore/refresh-loadmore.js ***!
  \*************************************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	exports.Refresher = undefined;
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	__webpack_require__(/*! ./style.css */ 338);
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	var _iscroll = __webpack_require__(/*! iscroll */ 340);
	
	var _iscroll2 = _interopRequireDefault(_iscroll);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 下拉刷新和上拉加载更多提示组件
	 */
	var RefreshLoadMore = function (_React$Component) {
	    _inherits(RefreshLoadMore, _React$Component);
	
	    function RefreshLoadMore() {
	        _classCallCheck(this, RefreshLoadMore);
	
	        return _possibleConstructorReturn(this, Object.getPrototypeOf(RefreshLoadMore).apply(this, arguments));
	    }
	
	    _createClass(RefreshLoadMore, [{
	        key: 'render',
	        value: function render() {
	            return React.createElement(
	                'div',
	                { className: this.props.c1 },
	                React.createElement(
	                    'div',
	                    { className: this.props.c2 },
	                    this.props.t1
	                )
	            );
	        }
	    }]);
	
	    return RefreshLoadMore;
	}(React.Component);
	
	/**
	 * 下拉上拉实例化类,基于IScroll
	 * 这个功能对元素样式,class名,嵌套结构要求很多,注意细节,不然会有很多小问题
	 * iScroll实例的refresh()很关键
	 * 有小问题主要从拉动容器DOM节点开始,检查每个元素的样式高度, 拉动后是否调用了refresh()方法等
	 * 如果要改结构或类名等记得关联的地方都改了。
	 * 上拉下拉的提示容器的表现形式和内容都可以修改,但是比较麻烦
	 */
	
	RefreshLoadMore.propTypes = {
	    c1: React.PropTypes.string.isRequired,
	    c2: React.PropTypes.string.isRequired,
	    t1: React.PropTypes.string.isRequired
	};
	exports.default = RefreshLoadMore;
	var Refresher = exports.Refresher = {
	    info: {
	        "pullDownLable": "下拉刷新...",
	        "pullingDownLable": "释放刷新...",
	        "pullUpLable": "上拉加载更多...",
	        "pullingUpLable": "释放加载...",
	        "loadingLable": "加载中..."
	    },
	    init: function init(config) {
	        var pullDownEle = config.downPromptBox;
	        var pullUpEle = config.upPromptBox;
	
	        document.addEventListener('touchmove', function (event) {
	            event.preventDefault();
	        }, false);
	
	        var instance = new _iscroll2.default(config.scrollBox, {
	            resize: true,
	            click: true
	        });
	
	        instance.on('scrollStart', function () {
	            if (this.distY > 0 && this.y === 0 && !pullUpEle.className.match('pullLoading') && !pullDownEle.className.match('pullLoading')) {
	                //开始下拉,并且是在顶部的时候,并且不在加载状态
	                pullDownEle.classList.add("pullFlip");
	                pullDownEle.querySelector('.pullDownLabel').innerHTML = Refresher.info.pullingDownLable;
	                pullDownEle.style.display = "block";
	            } else if (this.distY < 0 && this.y === this.maxScrollY && !pullUpEle.className.match('pullLoading') && !pullDownEle.className.match('pullLoading')) {
	                //开始上拉,并且是在底部的时候
	                pullUpEle.classList.add("pullFlip");
	                pullUpEle.querySelector('.pullUpLabel').innerHTML = Refresher.info.pullingUpLable;
	                pullUpEle.style.display = "block";
	            }
	        });
	        instance.on('scrollEnd', function () {
	            if (pullDownEle.className.match('pullFlip') && !pullDownEle.className.match('pullLoading')) {
	                //下拉结束恢复位置,如果有回调函数,执行回调函数,一般是开始请求数据
	                pullDownEle.classList.add("pullLoading");
	                pullDownEle.classList.remove("pullFlip");
	                pullDownEle.querySelector('.pullDownLabel').innerHTML = Refresher.info.loadingLable;
	                if (config.downCallback) config.downCallback(instance);
	            } else if (pullUpEle.className.match('pullFlip') && !pullUpEle.className.match('pullLoading')) {
	                //上拉结束恢复位置,如果有回调函数,执行回调函数,一般是开始请求数据
	                pullUpEle.classList.add("pullLoading");
	                pullUpEle.classList.remove("pullFlip");
	                pullUpEle.querySelector('.pullUpLabel').innerHTML = Refresher.info.loadingLable;
	                if (config.upCallback) config.upCallback(instance);
	            }
	        });
	        instance.on('refresh', function () {
	            if (pullDownEle.className.match('pullLoading')) {
	                //滚动实例刷新,下拉回调函数执行完毕,通常是请求数据结束
	                pullDownEle.classList.remove("pullLoading");
	                pullDownEle.querySelector('.pullDownLabel').innerHTML = Refresher.info.pullDownLable;
	                pullDownEle.style.display = "none";
	            } else if (pullUpEle.className.match('pullLoading')) {
	                //滚动实例刷新,上拉回调函数执行完毕,通常是请求数据结束
	                pullUpEle.classList.remove("pullLoading");
	                pullUpEle.querySelector('.pullUpLabel').innerHTML = Refresher.info.pullUpLable;
	                pullUpEle.style.display = "none";
	            }
	        });
	
	        //下拉上拉hack
	        Refresher.refresh(instance, 200);
	        document.addEventListener("visibilitychange", function () {
	            if (!document.hidden && this) this.refresh();
	        }.bind(instance), false);
	
	        return instance;
	    },
	    refresh: function refresh(instance) {
	        var time = arguments.length <= 1 || arguments[1] === undefined ? 100 : arguments[1];
	
	        setTimeout(function () {
	            if (this) this.refresh();
	        }.bind(instance), time);
	    }
	};

/***/ },

/***/ 338:
/*!***************************************************!*\
  !*** ./src/components/refresh-loadmore/style.css ***!
  \***************************************************/
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ },

/***/ 340:
/*!**************************!*\
  !*** external "IScroll" ***!
  \**************************/
/***/ function(module, exports) {

	module.exports = IScroll;

/***/ },

/***/ 341:
/*!*******************************************************!*\
  !*** ./src/components/reply-content/reply-content.js ***!
  \*******************************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	var _data = __webpack_require__(/*! ../../data */ 306);
	
	var _data2 = _interopRequireDefault(_data);
	
	__webpack_require__(/*! ./style.css */ 342);
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	var _baiduHmt = __webpack_require__(/*! baidu-hmt */ 328);
	
	var _baiduHmt2 = _interopRequireDefault(_baiduHmt);
	
	var _zepto = __webpack_require__(/*! zepto */ 329);
	
	var _zepto2 = _interopRequireDefault(_zepto);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 话题回复内容组件
	 */
	var ReplyContent = function (_React$Component) {
	    _inherits(ReplyContent, _React$Component);
	
	    function ReplyContent(props) {
	        _classCallCheck(this, ReplyContent);
	
	        //初始化从本地数据判断是否已经点赞
	        var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(ReplyContent).call(this, props));
	
	        _this.likeHandler = function (event) {
	            event.preventDefault();
	
	            //如果已经点赞,则弹提示
	            //否则处理点赞业务
	            if (_this.state.liked) {
	                //如果提示框不存在,则提示
	                if (!_this.state.likedPrompt) {
	                    _this.setState({ likedPrompt: true });
	                    setTimeout(function () {
	                        _this.setState({ likedPrompt: false });
	                    }, 1000);
	                }
	            } else {
	                _zepto2.default.ajax({
	                    type: 'POST',
	                    timeout: 500,
	                    url: _data.API.topicLike,
	                    data: {
	                        id: _this.props.data.id,
	                        user_id: _data2.default.bfuid,
	                        nickname: _data2.default.bfuname
	                    },
	                    success: function (data) {}.bind(_this),
	                    error: function (xhr, type) {
	                        console.log(_data.API.topicLike);
	                    }.bind(_this)
	                });
	
	                _this.plusMinusFun(!_this.state.liked);
	
	                _data.Utils.setLiked(_this.props.data.id, !_this.state.liked);
	
	                _this.setState({ liked: !_this.state.liked });
	
	                _baiduHmt2.default.push(['_trackEvent', 'H5国旗话题分享', '点击点赞按钮', '点赞']);
	            }
	        };
	
	        _this.imageError = function () {
	            var imgDOMNode = _this.refs.contentIMG;
	            imgDOMNode.onerror = null;
	            imgDOMNode.src = __webpack_require__(/*! ./img/default_image.png */ 347);
	        };
	
	        _this.plusMinusFun = function (liked) {
	            //如果已点赞,则加1,否则不加
	            if (liked) {
	                _this.plusMinus = 1;
	            } else {
	                _this.plusMinus = 0;
	            }
	        };
	
	        _this.closeDialogHandler = function () {
	            _this.setState({ likedPrompt: false });
	        };
	
	        var likedInit = _this.props.liked && _this.props.liked.indexOf(_this.props.data.id) !== -1;
	
	        _this.plusMinus = 0;
	        _this.plusMinusFun(likedInit);
	
	        _this.state = {
	            liked: likedInit,
	            likedPrompt: false
	        };
	
	        return _this;
	    }
	
	    _createClass(ReplyContent, [{
	        key: 'render',
	        value: function render() {
	
	            //已点赞提示
	            var likedPromptComp = null;
	            if (this.state.likedPrompt) {
	                likedPromptComp = React.createElement(
	                    'div',
	                    { className: 'likedPrompt' },
	                    '你已点赞'
	                );
	            }
	
	            var data = this.props.data;
	
	            //头像
	            var avatarUrl = _data.Utils.getUserAvatar(data.user_id);
	
	            /*
	             当小于1分钟时，显示“刚刚”
	             1-59分钟内：XX分钟前
	             1小时-24小时内：XX小时前
	             1天-7天内：X天前
	             7天以上-今年内：月-日 12:00；
	             今年以前：年-月-日 12:00
	             */
	            var timeTxt = _data.Utils.dateFormat(new Date(data.created_at * 1000), 'yyyy-MM-dd hh:mm');
	            var elapsed = Date.now() / 1000 - data.created_at; // 运行时间
	            if (elapsed < 60) {
	                timeTxt = '刚刚';
	            } else if (elapsed >= 60 && elapsed < 3600) {
	                timeTxt = Math.floor(elapsed / 60) + '分钟前';
	            } else if (elapsed >= 3600 && elapsed < 86400) {
	                timeTxt = Math.floor(elapsed / 3600) + '小时前';
	            } else if (elapsed >= 86400 && elapsed < 604800) {
	                timeTxt = Math.floor(elapsed / 86400) + '天前';
	            } else if (elapsed >= 604800 && elapsed < 31536000) {
	                timeTxt = _data.Utils.dateFormat(new Date(data.created_at * 1000), 'MM-dd hh:mm');
	            }
	
	            //是否有图片
	            var imgComp1 = data.image ? React.createElement('img', { ref: 'contentIMG', className: 'img1', src: data.image, onError: this.imageError }) : null;
	
	            //是否有文章
	            var txtComp1 = data.content ? React.createElement(
	                'p',
	                { className: 'txt1' },
	                decodeURI(data.content)
	            ) : null;
	
	            //实际点赞数
	            var actualLikes = data.likes + this.plusMinus;
	
	            var boxClassName = 'replyContent';
	            if (this.props.first) boxClassName += ' replyContentFirst';
	            if (this.props.end) boxClassName += ' replyContentEnd';
	
	            return React.createElement(
	                'div',
	                { className: boxClassName },
	                React.createElement(
	                    'div',
	                    { className: 'row1' },
	                    React.createElement('img', { className: 'avatar', src: avatarUrl }),
	                    React.createElement(
	                        'h1',
	                        { className: 'name left1' },
	                        decodeURI(data.nickname)
	                    ),
	                    React.createElement(
	                        'span',
	                        { className: 'time left1 top1 font1' },
	                        timeTxt
	                    ),
	                    React.createElement(
	                        'span',
	                        { className: 'ordinal top1 font1' },
	                        data.seq,
	                        '楼'
	                    )
	                ),
	                imgComp1,
	                txtComp1,
	                React.createElement(
	                    'div',
	                    { className: 'row2' },
	                    React.createElement(
	                        'button',
	                        { className: 'like1', onClick: this.likeHandler },
	                        React.createElement('img', { src: __webpack_require__(/*! ./img */ 344)("./praise" + Number(this.state.liked) + '.png') })
	                    ),
	                    React.createElement(
	                        'span',
	                        { className: 'like2 font1' },
	                        actualLikes
	                    ),
	                    likedPromptComp
	                )
	            );
	        }
	
	        /**
	         * 点赞事件
	         * @param event
	         */
	
	
	        /**
	         * 图片加载错误,使用默认图
	         */
	
	
	        /**
	         * 点赞的增量处理
	         * @param liked 0 是未点赞 1是已点赞
	         */
	
	
	        /**
	         * 关闭对话框
	         */
	
	    }]);
	
	    return ReplyContent;
	}(React.Component);
	
	ReplyContent.propTypes = {
	    liked: React.PropTypes.array.isRequired,
	    data: React.PropTypes.object.isRequired,
	    first: React.PropTypes.bool,
	    end: React.PropTypes.bool
	};
	exports.default = ReplyContent;

/***/ },

/***/ 342:
/*!************************************************!*\
  !*** ./src/components/reply-content/style.css ***!
  \************************************************/
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ },

/***/ 344:
/*!**************************************************************!*\
  !*** ./src/components/reply-content/img ^\.\/praise.*\.png$ ***!
  \**************************************************************/
/***/ function(module, exports, __webpack_require__) {

	var map = {
		"./praise0.png": 345,
		"./praise1.png": 346
	};
	function webpackContext(req) {
		return __webpack_require__(webpackContextResolve(req));
	};
	function webpackContextResolve(req) {
		return map[req] || (function() { throw new Error("Cannot find module '" + req + "'.") }());
	};
	webpackContext.keys = function webpackContextKeys() {
		return Object.keys(map);
	};
	webpackContext.resolve = webpackContextResolve;
	module.exports = webpackContext;
	webpackContext.id = 344;


/***/ },

/***/ 345:
/*!******************************************************!*\
  !*** ./src/components/reply-content/img/praise0.png ***!
  \******************************************************/
/***/ function(module, exports) {

	module.exports = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACIAAAAkCAYAAADsHujfAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoxQUEwODdFNzI3QTgxMUU2QjRFRENFQjk1Nzc0QTU5OSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDoxQUEwODdFODI3QTgxMUU2QjRFRENFQjk1Nzc0QTU5OSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjFBQTA4N0U1MjdBODExRTZCNEVEQ0VCOTU3NzRBNTk5IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjFBQTA4N0U2MjdBODExRTZCNEVEQ0VCOTU3NzRBNTk5Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+xw2PPAAAAT1JREFUeNpiXL9+PQMFwA2Iq4G4FogPkWNAQEAAmGaiwBEsQNwPxHZAfBCIGynxESUOyQBiLSR+HRDb0NshgkDcgEXclt4OqQdiYSzi3+npEA0gzsIht4eeDukFYlYs4qBcc4VeDnEHYi8ccg30yjWg7NqHQw4UGvvp5RD07IqeeBno4RAhPEF/AogP0MshuLIrCCxjoAJgIjK7ZuKRv0svh+DKrjAQBy1pKXbIfxwYBrwImBEOxO/wmIMPP92wYYM7pZUeNYAUEM8fDA4BgV+DxSEHB4tD9g8WhxwYDA55CGyzPhgMDjlIjTYr1dLHYHDIgcHgkAdQPOAOOUStfg3V0sdAO+TAYHAISvoYSIccpGbfl2rRMuIdcg49fQyEQ77gaojTyyGfgXgDEJsD8Slc3UhGAoYw0sOlAAEGAHXkRrgPyUhyAAAAAElFTkSuQmCC"

/***/ },

/***/ 346:
/*!******************************************************!*\
  !*** ./src/components/reply-content/img/praise1.png ***!
  \******************************************************/
/***/ function(module, exports) {

	module.exports = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACIAAAAkCAYAAADsHujfAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpDOEM4RENDRjI3QTcxMUU2QkNDREQyRUY2MDEzNzhCOCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpDOEM4RENEMDI3QTcxMUU2QkNDREQyRUY2MDEzNzhCOCI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkM4QzhEQ0NEMjdBNzExRTZCQ0NERDJFRjYwMTM3OEI4IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkM4QzhEQ0NFMjdBNzExRTZCQ0NERDJFRjYwMTM3OEI4Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+HqcF4gAAATpJREFUeNpi/J/DQAlwA+JqIK4F4kNkmTD5P5hiosARLEDcD8R2QHwQiBsp8RElDskAYi0kfh0Q29DbIYJA3IBF3JbeDqkHYmEs4t/p6RANIM7CIbeHng7pBWJWLOKgXHOFXg5xB2IvHHIN9Mo1oOzah0MOFBr76eUQ9OyKnngZ6OEQITxBfwKID9DLIbiyKwgsY6ACYCIyu2bikb9LL4fgyq4wEActaSl2yH8cGAa8CJgRDsTv8JiDDz9lyGV0p7TSowaQAuL5g8EhIPBrsDjk4GBxyP7B4pADg8EhD4Ft1geDwSEHqdFmpVr6GAwOOTAYHPIAigfcIYeo1a+hWvoYaIccGAwOQUkfA+mQg9Ts+1ItWka8Q86hp4+BcMgXXA1xejnkMxBvAGJzID6FqxvJSMAQRnq4FCDAANbmRbja6lIsAAAAAElFTkSuQmCC"

/***/ },

/***/ 347:
/*!************************************************************!*\
  !*** ./src/components/reply-content/img/default_image.png ***!
  \************************************************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = __webpack_require__.p + "img/default_image.png";

/***/ },

/***/ 348:
/*!*************************************************!*\
  !*** ./src/components/reply-list/reply-list.js ***!
  \*************************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	var _data = __webpack_require__(/*! ../../data */ 306);
	
	var _data2 = _interopRequireDefault(_data);
	
	__webpack_require__(/*! ./style.css */ 349);
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	var _adInterlude = __webpack_require__(/*! ../ad-interlude/ad-interlude */ 334);
	
	var _adInterlude2 = _interopRequireDefault(_adInterlude);
	
	var _replyContent = __webpack_require__(/*! ../reply-content/reply-content */ 341);
	
	var _replyContent2 = _interopRequireDefault(_replyContent);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 话题回复列表
	 */
	var ReplyList = function (_React$Component) {
	    _inherits(ReplyList, _React$Component);
	
	    function ReplyList(props) {
	        _classCallCheck(this, ReplyList);
	
	        var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(ReplyList).call(this, props));
	
	        _this.initList = function (data, index, currentTabIndex) {
	            //第一个和最后一个要加特殊样式
	            var end = data.length - 1;
	            var list = data.map(function (item, index) {
	                return React.createElement(_replyContent2.default, { data: item, liked: _data.Utils.getLiked(), first: Boolean(0 === index),
	                    end: Boolean(end === index) });
	            });
	
	            //如果数据超过5条,广告条插在第四条后面
	            //否则插在最后
	            if (list.length > 5) {
	                list.splice(4, 0, React.createElement(_adInterlude2.default, null));
	            } else {
	                list.push(React.createElement(_adInterlude2.default, null));
	            }
	
	            //默认不显示
	            //如果list组件索引和当前Tab索引一致,则显示
	            var styleObj = { display: 'none' };
	            if (index === currentTabIndex) styleObj = { display: 'block' };
	
	            return React.createElement(
	                'div',
	                { style: styleObj },
	                list
	            );
	        };
	
	        _this.switchHandler = function (event) {
	            event.preventDefault();
	            _data.DataIndex.listOrderTypeIndex = Number(event.currentTarget.dataset.index);
	            _this.setState({ tabIndex: _data.DataIndex.listOrderTypeIndex });
	            _this.props.repliesListGet(_data2.default.topicId);
	        };
	
	        _this.state = { tabIndex: 0 };
	        return _this;
	    }
	
	    _createClass(ReplyList, [{
	        key: 'render',
	        value: function render() {
	            return React.createElement(
	                'div',
	                { className: 'replyList' },
	                React.createElement(
	                    'div',
	                    { className: 'tab' },
	                    React.createElement(
	                        'button',
	                        { 'data-index': '0',
	                            disabled: this.state.tabIndex === 0,
	                            onClick: this.switchHandler },
	                        '最新'
	                    ),
	                    React.createElement(
	                        'button',
	                        { 'data-index': '1',
	                            disabled: this.state.tabIndex === 1,
	                            onClick: this.switchHandler },
	                        '最热'
	                    )
	                ),
	                this.initList(this.props.data[0], 0, this.state.tabIndex),
	                this.initList(this.props.data[1], 1, this.state.tabIndex)
	            );
	        }
	    }]);
	
	    return ReplyList;
	}(React.Component);
	
	ReplyList.propTypes = {
	    data: React.PropTypes.array.isRequired,
	    repliesListGet: React.PropTypes.func.isRequired
	};
	exports.default = ReplyList;

/***/ },

/***/ 349:
/*!*********************************************!*\
  !*** ./src/components/reply-list/style.css ***!
  \*********************************************/
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ },

/***/ 351:
/*!*****************************************************!*\
  !*** ./src/components/share-prompt/share-prompt.js ***!
  \*****************************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	__webpack_require__(/*! ./style.css */ 352);
	
	var _react = __webpack_require__(/*! react */ 307);
	
	var React = _interopRequireWildcard(_react);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	/**
	 * 弹出提示组件
	 */
	var SharePrompt = function (_React$Component) {
	    _inherits(SharePrompt, _React$Component);
	
	    function SharePrompt() {
	        _classCallCheck(this, SharePrompt);
	
	        return _possibleConstructorReturn(this, Object.getPrototypeOf(SharePrompt).apply(this, arguments));
	    }
	
	    _createClass(SharePrompt, [{
	        key: 'render',
	        value: function render() {
	            return React.createElement(
	                'div',
	                { className: 'sharePrompt', onClick: this.props.closeShareHandler },
	                React.createElement('img', { src: __webpack_require__(/*! ./img/share-prompt.png */ 354) })
	            );
	        }
	    }]);
	
	    return SharePrompt;
	}(React.Component);
	
	SharePrompt.propTypes = {
	    closeShareHandler: React.PropTypes.func.isRequired
	};
	exports.default = SharePrompt;

/***/ },

/***/ 352:
/*!***********************************************!*\
  !*** ./src/components/share-prompt/style.css ***!
  \***********************************************/
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ },

/***/ 354:
/*!**********************************************************!*\
  !*** ./src/components/share-prompt/img/share-prompt.png ***!
  \**********************************************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = __webpack_require__.p + "img/share-prompt.png";

/***/ }

});
//# sourceMappingURL=index.js.map