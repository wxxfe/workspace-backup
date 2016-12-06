import './style.css';
import * as React from 'react';

/**
 * 弹出提示组件
 */
export default class SharePrompt extends React.Component {
    static propTypes = {
        closeShareHandler: React.PropTypes.func.isRequired
    }

    componentDidMount() {
        setTimeout(this.props.closeShareHandler, 2000);
    }

    render() {
        return (
            <div className='sharePrompt' onClick={this.props.closeShareHandler}>
                <img src={require('./img/share-prompt.png')}/>
            </div>
        );
    }
}

