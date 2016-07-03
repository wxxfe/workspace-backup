import './css/style.css';

import React from 'react';
import ReactDOM from 'react-dom';

import HelloMessage from './components/hello-message/HelloMessage';

ReactDOM.render(<HelloMessage name="World" />, document.getElementById("mountNode"));
