import Data, {DataIndex, Utils} from '../../data';
import './style.css';
import * as React from 'react';
import ADInterlude from '../ad-interlude/ad-interlude';
import ReplyContent from '../reply-content/reply-content';

/**
 * 话题回复列表
 */
export default class ReplyList extends React.Component {
    static propTypes = {
        data: React.PropTypes.array.isRequired,
        repliesListGet: React.PropTypes.func.isRequired
    }

    constructor(props) {
        super(props);

        this.state = {tabIndex: 0};
    }

    render() {
        return (
            <div className='replyList'>
                <div className='tab'>
                    <button data-index='0'
                            disabled={this.state.tabIndex === 0}
                            onClick={this.switchHandler}>最新
                    </button>
                    <button data-index='1'
                            disabled={this.state.tabIndex === 1}
                            onClick={this.switchHandler}>最热
                    </button>
                </div>
                {this.initList(this.props.data[0], 0, this.state.tabIndex)}
                {this.initList(this.props.data[1], 1, this.state.tabIndex)}
            </div>
        );
    }

    initList = (data, index, currentTabIndex) => {
        //第一个和最后一个要加特殊样式
        let end = data.length - 1;
        let list = data.map((item, index)=> {
            return <ReplyContent data={item} liked={Utils.getLiked()} first={Boolean(0 === index)}
                                 end={Boolean(end === index)}/>;
        });

        //如果数据超过5条,广告条插在第四条后面
        //否则插在最后
        if (list.length > 5) {
            list.splice(4, 0, <ADInterlude />);
        } else {
            list.push(<ADInterlude />);
        }

        //默认不显示
        //如果list组件索引和当前Tab索引一致,则显示
        let styleObj = {display: 'none'};
        if (index === currentTabIndex) styleObj = {display: 'block'};

        return (
            <div style={styleObj}>
                {list}
            </div>
        );
    }

    switchHandler = (event) => {
        event.preventDefault();
        DataIndex.listOrderTypeIndex = Number(event.currentTarget.dataset.index);
        this.setState({tabIndex: DataIndex.listOrderTypeIndex});
        this.props.repliesListGet(Data.topicId);
    }
}

