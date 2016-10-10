import * as React from 'react';
import EditImage from './edit-image/EditImage';

export default class App extends React.Component {
    render() {
        return (
            <div>
                {this.props.children || <EditImage />}
            </div>
        )
    }
}

