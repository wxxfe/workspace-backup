import * as React from 'react';

/**
 * 编辑国旗栏
 */
export default class EditImageBar extends React.Component {
    static propTypes = {
        data: React.PropTypes.array.isRequired,
        index: React.PropTypes.number.isRequired,
        selectedHandler: React.PropTypes.func.isRequired
    }

    render() {

        let list = this.props.data.map((item, index) => {
            //给当前选中的国旗小图添加选中样式
            let selected = '';
            if (index === this.props.index) selected = 'slick-selected'
            return <div key={index} data-index={index} className={selected} onClick={this.props.selectedHandler}>
                <img src={require(item.img + '.png')}/>
                <div>{item.title}</div>
            </div>;
        });

        return (
            <div className='editImageBar'>
                {list}
            </div>
        );
    }

}

