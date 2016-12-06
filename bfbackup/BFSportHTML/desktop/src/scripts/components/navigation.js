/**
 * @des: 导航
 * @author: minghui
 * @example: 
 */

define(function() {

    return {

        wrap : $('#navigation'),

        nav : $('#nav'),

        bar : $('li.nav-active',this.nav),

        init : function(){

            this.active = $('li a.active',this.nav);

            this.menus = $('li a',this.nav);

            this.initLeft = this.getPosition(this.active);

            this.run(this.initLeft);

            this.menus.on('mouseenter',this,this.enterHandle);

            this.menus.on('mouseleave',this,this.leaveHandle);

            $(window).on('scroll',this,this.scrollHandle);

        },

        getPosition : function(menu){

            return menu.position().left;

        },

        run : function(position,callback){

            this.bar.stop().animate({'left' : position},200,'easeInOutCirc',function(){
                callback ? callback() : function(){};
            });

        },

        enterHandle : function(event){

            var _self = event.data;
            var left = _self.getPosition($(this));
            _self.active.removeClass();
            _self.run(left);

        },

        leaveHandle : function(event){

            var _self = event.data;
            var left = _self.initLeft;
            _self.run(left,function(){
                _self.active.addClass('active');
            });

        },

        scrollHandle : function(event){

            var _self = event.data;
            var isDark = _self.wrap.hasClass('navigation-dark');
            if($(this).scrollTop() > 0){
                if(!isDark){
                    _self.wrap.removeClass('navigation').hide().addClass('top-fixed').addClass('navigation-dark').fadeIn('fast');
                    $(document.body).css('padding-top','75px');
                }
            }else{
                $(document.body).removeAttr('style');
                _self.wrap.removeClass().addClass('navigation');
            }

        }


    }

});
