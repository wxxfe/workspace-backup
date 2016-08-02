import './style.css';
import * as React from 'react';

/**
 * 编辑页面顶部横栏
 */
export default class EditImageTopBar extends React.Component {
    static propTypes = {
        title: React.PropTypes.string.isRequired,
        goIndexHandler: React.PropTypes.func.isRequired,
        uploadHandler: React.PropTypes.func.isRequired
    }

    render() {
        return (
            <div className='topBar'>
                <button className='btn1' onClick={this.props.goIndexHandler}>取消</button>
                <h1 >{this.props.title}</h1>
                <div className='btn2'>重拍
                    <input className='uploadInput' type='file' capture='camera' accept='image/*' id='file'
                           onChange={this.props.uploadHandler}/>
                </div>
            </div>
        );
    }

}

