import * as React from 'react';
import Slider from 'react-slick';

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
        //组件配置
        var settings = {
            infinite: false,
            slidesToShow: 5,
            slidesToScroll: 5,
            arrows: false
        };

        let list = this.props.data.map((item, index) => {
            //给当前选中的国旗小图添加选中样式
            let selected = '';
            if (index === this.props.index) selected = 'slick-selected'
            return <div className={selected} onClick={this.props.selectedHandler}>
                <img src={require(item.img + '.png')}/>
                <div>{item.title}</div>
            </div>;
        });

        return (
            <div className='editImageBar'>
                <Slider {...settings}>
                    {list}
                </Slider>
            </div>
        );
    }

}

