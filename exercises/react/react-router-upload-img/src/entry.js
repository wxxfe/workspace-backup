import 'babel-polyfill';
import * as React from 'react';
import * as ReactDOM from 'react-dom';
import {Router, browserHistory} from 'react-router';
import './lib/css/normalize.css';
import './common.css';

const rootRoute = {
    childRoutes: [
        {
            path: '/',
            component: require('./components/edit-image/EditImage').default,
            childRoutes: [
                //同步加载EditTxtSend组件
                // {
                //     path: 'edit-txt-send',
                //     component: require('./components/edit-txt-send/EditTxtSend').default
                // }
                //异步加载EditTxtSend组件
                require('./components/edit-txt-send/route').default
            ]
        }
    ]
};

ReactDOM.render(
    (
        <Router
            history={browserHistory}
            routes={rootRoute}
        />
    ),
    document.getElementById('mountNode')
);