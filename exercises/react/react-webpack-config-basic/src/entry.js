import './css/style';

import * as React from "react";
import * as ReactDOM from "react-dom";

import HelloMessage from './components/hello-message/HelloMessage';

ReactDOM.render(<HelloMessage name="World" />, document.getElementById("mountNode"));
