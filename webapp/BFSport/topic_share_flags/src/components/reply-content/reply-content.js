import Data, {Utils, API} from '../../data';
import './style.css';
import * as React from 'react';
import BaiduHMT from 'baidu-hmt';
import $ from 'zepto';

/**
 * 话题回复内容组件
 */
export default class ReplyContent extends React.Component {
    static propTypes = {
        liked: React.PropTypes.array.isRequired,
        data: React.PropTypes.object.isRequired,
        first: React.PropTypes.bool,
        end: React.PropTypes.bool
    }

    constructor(props) {
        super(props);

        //初始化从本地数据判断是否已经点赞
        let likedInit = (this.props.liked && this.props.liked.indexOf(this.props.data.id) !== -1);

        this.plusMinus = 0;
        this.plusMinusFun(likedInit);

        this.state = {
            liked: likedInit,
            likedPrompt: false
        };

    }

    render() {

        //已点赞提示
        let likedPromptComp = null;
        if (this.state.likedPrompt) {
            likedPromptComp = <div className="likedPrompt">你已点赞</div>;
        }

        let data = this.props.data;

        //头像
        let avatarUrl = Utils.getUserAvatar(data.user_id);

        /*
         当小于1分钟时，显示“刚刚”
         1-59分钟内：XX分钟前
         1小时-24小时内：XX小时前
         1天-7天内：X天前
         7天以上-今年内：月-日 12:00；
         今年以前：年-月-日 12:00
         */
        let timeTxt = Utils.dateFormat(new Date(data.created_at * 1000), 'yyyy-MM-dd hh:mm');
        let elapsed = (Date.now() / 1000) - data.created_at; // 运行时间
        if (elapsed < 60) {
            timeTxt = '刚刚';
        } else if (elapsed >= 60 && elapsed < 3600) {
            timeTxt = Math.floor(elapsed / 60) + '分钟前';
        } else if (elapsed >= 3600 && elapsed < 86400) {
            timeTxt = Math.floor(elapsed / 3600) + '小时前';
        } else if (elapsed >= 86400 && elapsed < 604800) {
            timeTxt = Math.floor(elapsed / 86400) + '天前';
        } else if (elapsed >= 604800 && elapsed < 31536000) {
            timeTxt = Utils.dateFormat(new Date(data.created_at * 1000), 'MM-dd hh:mm');
        }

        //是否有图片
        let imgComp1 = data.image ?
            <img ref='contentIMG' className='img1' src={data.image} onError={this.imageError}/> : null;

        //是否有文章
        let txtComp1 = data.content ? <p className='txt1'>{decodeURI(data.content)}</p> : null;

        //实际点赞数
        let actualLikes = data.likes + this.plusMinus;

        let boxClassName = 'replyContent';
        if (this.props.first) boxClassName += ' replyContentFirst';
        if (this.props.end) boxClassName += ' replyContentEnd';

        return (
            <div className={boxClassName}>
                <div className='row1'>
                    <img className='avatar' src={avatarUrl}/>
                    <h1 className='name left1'>{decodeURI(data.nickname)}</h1>
                    <span className='time left1 top1 font1'>{timeTxt}</span>
                    <span className='ordinal top1 font1'>{data.seq}楼</span>
                </div>
                {imgComp1}
                {txtComp1}
                <div className='row2'>
                    <button className='like1' onClick={this.likeHandler}>
                        <img src={require('./img/praise' + Number(this.state.liked) + '.png')}/>
                    </button>
                    <span className='like2 font1'>{actualLikes}</span>
                    {likedPromptComp}
                </div>

            </div>
        );
    }

    /**
     * 点赞事件
     * @param event
     */
    likeHandler = (event) => {
        event.preventDefault();

        //如果已经点赞,则弹提示
        //否则处理点赞业务
        if (this.state.liked) {
            //如果提示框不存在,则提示
            if (!this.state.likedPrompt) {
                this.setState({likedPrompt: true});
                setTimeout(()=> {
                    this.setState({likedPrompt: false});
                }, 1000);
            }
        } else {
            $.ajax({
                type: 'POST',
                timeout: 500,
                url: API.topicLike,
                data: {
                    id: this.props.data.id,
                    user_id: Data.bfuid,
                    nickname: Data.bfuname
                },
                success: function (data) {
                }.bind(this),
                error: function (xhr, type) {
                    console.log(API.topicLike);
                }.bind(this)
            });

            this.plusMinusFun(!this.state.liked);

            Utils.setLiked(this.props.data.id, !this.state.liked);

            this.setState({liked: !this.state.liked});

            BaiduHMT.push(['_trackEvent', 'H5国旗话题分享', '点击点赞按钮', '点赞']);
        }
    }

    /**
     * 图片加载错误,使用默认图
     */
    imageError = () => {
        let imgDOMNode = this.refs.contentIMG;
        imgDOMNode.onerror = null;
        imgDOMNode.src = require('./img/default_image.png');
    }

    /**
     * 点赞的增量处理
     * @param liked 0 是未点赞 1是已点赞
     */
    plusMinusFun = (liked) => {
        //如果已点赞,则加1,否则不加
        if (liked) {
            this.plusMinus = 1;
        } else {
            this.plusMinus = 0;
        }
    }

    /**
     * 关闭对话框
     */
    closeDialogHandler = () => {
        this.setState({likedPrompt: false});
    }
}

