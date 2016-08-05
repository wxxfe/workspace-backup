import 'babel-polyfill';
import './common.css';
import './index.css';
import Data, {DataIndex, Utils, STORAGE_KEY, API} from './data';
import BaiduHMT from 'baidu-hmt';
import $ from 'zepto';
import lrz from 'lrz';
import * as React from 'react';
import * as ReactDOM from 'react-dom';
import ADFixed from './components/ad-fixed/ad-fixed';
import ADInterlude from './components/ad-interlude/ad-interlude';
import RefreshLoadMore, {Refresher} from './components/refresh-loadmore/refresh-loadmore';
import ReplyContent from './components/reply-content/reply-content';
import ReplyList from './components/reply-list/reply-list';
import SharePrompt from './components/share-prompt/share-prompt';

/**
 * 首页
 */
class Index extends React.Component {
    constructor(props) {
        super(props);

        // Utils.docCookiesSetItem('bfuid','135601920077074447');//测试数据
        // DataConfig.bfuid = Utils.docCookiesGetItem('bfuid');//测试数据
        // Data.topicReplyId = '1';//测试数据

        //清除本功能浏览器历史存储数据
        Utils.setInitDataStorage(true);

        //请求浏览器存储数据
        Utils.getInitDataCookie();

        //话题标题
        document.title = Data.topicTitle;

        /*
         * 初始值undefined 如果是异步回调没有得到有效数据,则赋值为null, undefined和null表示两种状态,null状态有文字提示
         * repliesList 回复列表数组,
         * replyTop 置顶回复,数据和列表中单个一样
         * share 是否显示分享提示,如果是发送作品跳回首页,则显示
         */
        this.state = {
            repliesList: undefined,
            replyTop: undefined,
            share: Utils.storageGetObj(STORAGE_KEY.share),
        };

        //清除是否显示分享提示的数据
        window.localStorage.removeItem(STORAGE_KEY.share);
    }

    componentDidMount() {
        //请求服务器的初始数据
        this.userGet(Data.bfuid);
        this.replyTopGet(Data.topicReplyId);
        this.repliesListGet(Data.topicId);

        //下拉刷新和上拉加载更多
        Refresher.init({
            scrollBox: this.refs.scrollBox,
            downPromptBox: ReactDOM.findDOMNode(this.refs.pullDown),
            downCallback: function (refresher) {
                console.log("Refresh");
                this.repliesListGet(Data.topicId, refresher);
            }.bind(this),
            upPromptBox: ReactDOM.findDOMNode(this.refs.pullUp),
            upCallback: function (refresher) {
                console.log("Load More");
                this.repliesListGet(Data.topicId, refresher, true);
            }.bind(this)
        });

    }

    render() {
        //标题
        let titleComp = <h1 className='indexTitle indexTitleOne'>{Data.topicTitle}</h1>;

        //置顶话题介绍
        let topInfoComp = <p className='indexTopInfo'>{DataIndex.topicInfo}</p>;

        //上拉下拉的提示
        let refreshInfoComp = <RefreshLoadMore ref='pullDown' c1='pullDown' c2='pullDownLabel' t1='下拉刷新...'/>;
        let loadMoreInfoComp = <RefreshLoadMore ref='pullUp' c1='pullUp' c2='pullUpLabel' t1='上拉加载更多...'/>;

        //置顶和列表的异步回调没有得到有效数据的提示
        let infoComp = null;
        if (this.state.replyTop === null && this.state.repliesList === null) {
            infoComp = <h2 className='indexInfo'>该话题还没有作品</h2>;
        } else if (!this.state.repliesList === null) {
            infoComp = <h2 className='indexInfo'>该话题还没有其他作品</h2>;
        }

        //如果没有列表则直接显示广告条
        let adInterludeComp = null;
        if (!(this.state.repliesList)) {
            adInterludeComp = <ADInterlude />;
        }

        /**
         * 如果有置顶的话题回复数据,则置顶此回复组件后再显示回复列表
         * 否则只显示回复列表
         */
        let replyTopComp = null;
        let repliesListTitleComp = null;
        if (this.state.replyTop) {
            //liked 用户已点赞的回复ID数组
            //liked =[1];//测试数据
            //this.state.replyTop.likes+=1;//测试数据
            replyTopComp = <ReplyContent data={this.state.replyTop} first={true} liked={Utils.getLiked()}/>;
            repliesListTitleComp = <h1 className='indexTitle indexTitleTwo'>其他作品</h1>;
        }
        let replyListComp = null;
        if (this.state.repliesList) {
            replyListComp = <ReplyList data={this.state.repliesList} repliesListGet={this.repliesListGet}/>;
        }

        //编辑按钮
        let editBTNComp = <div className='editBTNBox'>
            <div className='editBTN'>
                <div className='centering'>开始创作</div>
                <input ref='uploadimg' className='uploadInput' type='file' capture='camera' accept='image/*'
                       id='file'
                       onChange={this.editHandler}/>
            </div>
        </div>;

        //分享提示
        let shareComp = null;
        if (this.state.share) {
            shareComp = <SharePrompt closeShareHandler={this.closeShareHandler}/>
        }

        return (
            <div className='pwh'>
                <div ref='scrollBox' className='scrollBox'>
                    <div className='scrollContentBox' style={{'min-height': document.documentElement.clientHeight}}>
                        {refreshInfoComp}
                        {titleComp}
                        {topInfoComp}
                        {replyTopComp}
                        {repliesListTitleComp}
                        {adInterludeComp}
                        {replyListComp}
                        {infoComp}
                        {loadMoreInfoComp}
                    </div>
                </div>
                {editBTNComp}
                <ADFixed />
                {shareComp}
            </div>
        );
    }

    repliesListGet = (id, refresher, more) => {
        if (id) {
            let key = null;
            if (more && this.state.repliesList) {
                let current = this.state.repliesList[DataIndex.listOrderTypeIndex];
                if (current.length) {
                    key = current[current.length - 1].key;
                }
            }

            let dataObj = {
                id: id,
                order_type: DataIndex.listOrderType[DataIndex.listOrderTypeIndex],
                key: key
            };

            $.ajax({
                type: 'GET',
                url: API.topicList,
                data: dataObj,
                dataType: 'json',
                timeout: 500,
                success: function (data) {
                    let list = null;
                    //如果有有效数据,是不为空的数组
                    if (data && data.data && data.data.list && Array.isArray(data.data.list.posts) && data.data.list.posts.length) {

                        //浏览器数据记录已经点赞的,点赞数减1。
                        let allLiked = Utils.getLiked();
                        let newList = data.data.list.posts.map(function (item) {
                            if (allLiked && allLiked.indexOf(item.id) !== -1) {
                                item.likes--;
                            }
                            return item;
                        });

                        list = this.state.repliesList;
                        //如果没有初始数据,则初始化
                        if (!list) list = [[], []];

                        //如果是更多,则加到已有数组后面
                        //否则替换更新数组
                        if (more) {
                            list[DataIndex.listOrderTypeIndex] = list[DataIndex.listOrderTypeIndex].concat(newList);
                        } else {
                            list[DataIndex.listOrderTypeIndex] = newList;
                        }
                    }
                    //如果是加载更多,但是没有数据的情况,则不改变state数据
                    if (!(more && list === null)) {
                        this.setState({repliesList: list});
                    }
                    if (refresher) Refresher.refresh(refresher);

                }.bind(this),
                error: function (xhr, type) {
                    console.log(API.topicList + '?id=' + id + ' ' + more);
                    //如果是加载更多的错误不改变state数据
                    if (!more) {
                        this.setState({repliesList: null});
                    }
                    if (refresher) Refresher.refresh(refresher);
                }.bind(this)
            });

        } else {
            this.setState({repliesList: null});
            if (refresher) Refresher.refresh(refresher);
        }
    }

    replyTopGet = (id) => {
        if (id) {
            $.ajax({
                type: 'GET',
                url: API.topicOne,
                data: {id: id},
                dataType: 'json',
                timeout: 500,
                success: function (data) {
                    if (data.data && data.data.body) {
                        this.setState({replyTop: data.data.body});
                    } else {
                        this.setState({replyTop: null});
                    }
                }.bind(this),
                error: function (xhr, type) {
                    console.log(API.topicOne + '?id=' + id);
                    this.setState({replyTop: null});
                }.bind(this)
            });
        } else {
            this.setState({replyTop: null});
        }
    }

    userGet = (id) => {
        if (id) {
            $.ajax({
                type: 'GET',
                url: API.sportsGetUser + id,
                //data: id,
                dataType: 'json',
                timeout: 500,
                success: function (data) {
                    var s = data.token + ':' + id;
                    Data.base64TokenUserId = Utils.b64btoa(s);
                    Data.userToken = Utils.b64btoa(data.token);
                    this.userLogin();
                }.bind(this),
                error: function (xhr, type) {
                    console.log(API.sportsGetUser + id);
                }.bind(this)
            });

        }
    }

    userLogin = () => {
        $.ajax({
            type: 'POST',
            timeout: 500,
            url: API.sportsLogin,
            data: {
                id: Data.bfuid,
                nickname: Utils.b64btoa(Data.bfuname),
                token: Data.userToken
            },
            error: function (xhr, type) {
                console.log(API.sportsLogin);
            }.bind(this)
        });

    }

    editHandler = (evnet) => {
        evnet.preventDefault();

        lrz(this.refs.uploadimg.files[0], {width: 750})
            .then(function (rst) {
                Utils.storageSetObj(STORAGE_KEY.uploadImg, rst.base64);
                window.location.href = Utils.getUrlPrefix() + '/edit.html' + window.location.search;
                return rst;
            }.bind(this))
            .catch(function (err) {
                // 万一出错了，这里可以捕捉到错误信息 而且以上的then都不会执行
            })
            .always(function () {
                // 不管是成功失败，这里都会执行
            });

        //window.location.href = '/edit.html' + window.location.search;
        BaiduHMT.push(['_trackEvent', 'H5国旗话题分享', '点击参加按钮', '参加']);
    }

    closeShareHandler = (evnet) => {
        if (evnet) evnet.preventDefault();
        if (this.state.share) this.setState({share: false});
    }

}

ReactDOM.render(<Index />, document.getElementById('mountNode'));
