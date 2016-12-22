import {CONFIG} from '../../data';
import './style.css';
import * as React from 'react';
import BaiduHMT from 'baidu-hmt';

/**
 * 广告组件,混在回复列表中
 */
export default class ADInterlude extends React.Component {
    render() {
        return (
            <div className='adInterlude' onClick={this.downloadHandler}>
                <h1>想看看大家还在热议什么？快来下载暴风体育</h1>
                <span>></span>
            </div>
        );
    }

    downloadHandler = (event) => {
        event.preventDefault();

        BaiduHMT.push(['_trackEvent', 'H5国旗话题分享', '点击下载APP按钮', 'app']);

        window.open(CONFIG.downloadAppUrl, '_blank');
    }
}