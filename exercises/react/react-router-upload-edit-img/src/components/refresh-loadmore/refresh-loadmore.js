import './style.css';
import * as React from 'react';
import IScroll from 'iscroll';

/**
 * 下拉刷新和上拉加载更多提示组件
 */
export default class RefreshLoadMore extends React.Component {
    static propTypes = {
        c1: React.PropTypes.string.isRequired,
        c2: React.PropTypes.string.isRequired,
        t1: React.PropTypes.string.isRequired
    }

    render() {
        return (
            <div className={this.props.c1}>
                <div className={this.props.c2}>{this.props.t1}</div>
            </div>
        );
    }
}



/**
 * 下拉上拉实例化类,基于IScroll
 * 这个功能对元素样式,class名,嵌套结构要求很多,注意细节,不然会有很多小问题
 * iScroll实例的refresh()很关键
 * 有小问题主要从拉动容器DOM节点开始,检查每个元素的样式高度, 拉动后是否调用了refresh()方法等
 * 如果要改结构或类名等记得关联的地方都改了。
 * 上拉下拉的提示容器的表现形式和内容都可以修改,但是比较麻烦
 */

export let Refresher = {
    info: {
        "pullDownLable": "下拉刷新...",
        "pullingDownLable": "释放刷新...",
        "pullUpLable": "上拉加载更多...",
        "pullingUpLable": "释放加载...",
        "loadingLable": "加载中..."
    },
    init (config) {
        var pullDownEle = config.downPromptBox;
        var pullUpEle = config.upPromptBox;

        document.addEventListener('touchmove', function (event) {
            event.preventDefault();
        }, false);

        var instance = new IScroll(
            config.scrollBox,
            {
                resize: true,
                click: true
            }
        );

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

    refresh (instance, time = 100){
        setTimeout(function () {
            if (this) this.refresh();
        }.bind(instance), time);
    }
};