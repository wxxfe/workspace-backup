/**
 * @des: 轮播
 * @author: minghui
 * @example: 
 */

define(function() {

    function Slider(options){

        this.opts = {
            wrap : null,
            auto : false,
            prev : null,
            next : null,
            loop : false,
            animate : 'slide',
            step : 0,
            speed : 300,
            start : 0,
            intervals : 4500,
            onEnd : function(){}
        };

        this.index = 0;

        this.slider = null;

        this.sliderItems = null;

        this.total = 0;

        this.timer = null;

        this.activeIndex = 0;

        this.active = null;

        this.init(options);

    }

    Slider.prototype.direction = 'left';

    Slider.prototype.init = function(options){
        $.extend(this.opts,options || {});
        this.initData();
        if(this.opts.start > 0){
            this.go(this.opts.start);
        }

        if(this.opts.auto && this.opts.animate == 'fade'){
            var _self = this;
            this.timer = setInterval(function(){
                if(_self.activeIndex >= _self.total-1){
                    _self.activeIndex = 0;
                    _self.index = _self.total - 1;
                }else{
                    _self.index = _self.activeIndex;
                    _self.activeIndex++;
                }
                _self.go(_self.activeIndex);
            },this.opts.intervals);
        }

        if(this.opts.prev) this.opts.prev.on('click',this,this.prevHandle);
        if(this.opts.next) this.opts.next.on('click',this,this.nextHandle);
    };

    Slider.prototype.initData = function(){
        if(this.opts.animate == 'slide'){
            this.initSlide();
        }else if(this.opts.animate == 'fade'){
            this.initFade();
        }
    };

    Slider.prototype.initFade = function(){
        var ul = $('ul',this.opts.wrap);
        var li = $('li',ul);

        this.slider = ul;
        this.sliderItems = li;
        this.total = li.size();
        this.activeIndex = 0;
        this.active = li.eq(0);
        this.timer = null;

        ul.css({
            'position' : 'relative',
            'display' : 'block',
            'width' : this.opts.wrap.width(),
            'height' : this.opts.wrap.height(),
            'overflow' : 'hidden'
        });
        var i = this.total,s = 0;
        while(i > 0 ){
            li.eq(s).css({
                'position' : 'absolute',
                'left' : 0,
                'top' : 0,
                'z-index' : i
            });
            i--;
            s++;
        }
    };

    Slider.prototype.initSlide = function(){
        var ul = $('ul',this.opts.wrap);
        var li = $('li',ul);
        var itemWidth = li.width();

        if(this.opts.step == 0){
            this.opts.step = itemWidth;
        }
        this.slider = ul;
        this.sliderItems = li;
        this.total = li.size();
        this.activeIndex = 0;
        this.active = li.eq(0);
        this.timer = null;

        ul.css({
            'position' : 'absolute',
            'left' : 0,
            'top' : 0,
            'width' : this.total * itemWidth
        });
        this.opts.wrap.css({
            'position' : 'relative',
            'overflow' : 'hidden',
            'width' : li.width(),
            'height' : li.height()
        });
    };

    Slider.prototype.runSlide = function(position){
        var p = position;
        this.slider.stop().animate({'left' : p},this.opts.speed);
    };

    Slider.prototype.runFade = function(index){
        var speed = this.opts.speed,_self = this;
        this.sliderItems.eq(this.index).fadeOut(speed);
        this.sliderItems.eq(index).delay(speed/2).fadeIn(speed,function(){
            if(index == 0){
                $(this).nextAll().show();
            }
            _self.opts.onEnd(index);
        });
    };

    Slider.prototype.go = function(index){
        if(this.opts.animate == 'slide'){
            var p = this.getPosition(index);
            this.runSlide(p);
            this.activeIndex = index;
            this.active = this.sliderItems.eq(index);
        }else if(this.opts.animate == 'fade'){
            this.activeIndex = index;
            this.index = index == 0 ? this.total - 1 : index - 1;
            this.sliderItems.hide().eq(this.index).show();
            this.runFade(index);
        }
    };

    Slider.prototype.getPosition = function(index){
        return (index * this.opts.step) * -1;
    };

    Slider.prototype.prevHandle = function(event){
        var _self = event.data;
        _self.direction = 'left';
        if(_self.opts.loop){
            if(_self.activeIndex <= 0){
                _self.activeIndex = _self.total-1;
            }else if(_self.activeIndex > 0){
                _self.activeIndex--;
            }
        }else{
            if(_self.activeIndex > 0){
                _self.activeIndex--;
            }else{
                _self.activeIndex = 0;
            }
        }
        _self.go(_self.activeIndex);
    };

    Slider.prototype.nextHandle = function(event){
        var _self = event.data;
        _self.direction = 'right';
        if(_self.opts.loop){
            if(_self.activeIndex >= _self.total){
                _self.activeIndex = 0;
            }else{
                _self.activeIndex++;
            }
        }else{
            if(_self.activeIndex < _self.total-1){
                _self.activeIndex++;
            }else{
                _self.activeIndex = _self.total-1;
            }
        }
        _self.go(_self.activeIndex);
    };


    return Slider;

});
