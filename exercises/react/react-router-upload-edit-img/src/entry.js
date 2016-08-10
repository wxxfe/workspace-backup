import 'babel-polyfill';
import * as React from 'react';
import * as ReactDOM from 'react-dom';
import {Router, Route, browserHistory} from 'react-router';
// import EditImage from './components/edit-image/edit-image';
import EditTxtSend from './components/edit-txt-send/edit-txt-send';
import './lib/css/neat.min.css';
import './common.css';

ReactDOM.render(
    (
        <Router history={browserHistory}>
            <Route path="/" component={EditTxtSend}/>
        </Router>
    ),
    document.getElementById('mountNode')
);