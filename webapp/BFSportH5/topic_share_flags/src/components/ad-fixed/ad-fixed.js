import {CONFIG} from '../../data';
import './style.css';
import * as React from 'react';
import BaiduHMT from 'baidu-hmt';

/**
 * 广告组件,固定位置
 */
export default class ADFixed extends React.Component {
    render() {
        return (
            <div className='adFixed' onClick={this.downloadHandler}>
                <img src={require('./img/logo.png')}/>
                <h1>暴风体育</h1>
                <p>有趣的话题，有趣的球迷</p>
                <button>立即下载</button>
            </div>
        );
    }

    downloadHandler = (event) => {
        event.preventDefault();

        BaiduHMT.push(['_trackEvent', 'H5国旗话题分享', '点击下载APP按钮', 'app']);

        window.open(CONFIG.downloadAppUrl, '_blank');
    }
}