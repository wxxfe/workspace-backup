/**
 * @des: 点赞 
 * @author: yangbolun
 * @param: object 配置 
 *  - button jquery对象
 *  - progress 是否有进度条
 *  - animate 是否显示动画
 * @example: new support({button:$('#button'), animate:true, progress: true});
 */
require.config({
  baseUrl: 'src/scripts'
});
define(['components/localStorage','components/countUp'], function (localData,countUp) {

    var Support = function(config){

        this.init(config);

    };

    Support.prototype.init = function(config){

        this.supportBtn = config.button; // jquery object

        this.isProgress = config.progress; // true | false

        this.isAnimate = config.animate; // true | false

        this.supportBtn.on('click',this,this.active);

    };

    Support.prototype.progress = function(){
        var pkProgressDiv = $('.pk-progress div');
        setTimeout(function(){

            var homeTeamNum = parseInt($('#zan-home').text());
            var guestTeamNum = parseInt($('#zan-guest').text());

            var homeTeamNewNum = (homeTeamNum / (homeTeamNum + guestTeamNum)) * 100;
            var guestTeamNewNum = (guestTeamNum / (homeTeamNum + guestTeamNum)) * 100;

            pkProgressDiv.eq(0).animate({width : homeTeamNewNum + '%'});
            pkProgressDiv.eq(1).animate({width : guestTeamNewNum + '%'});

        },1000);

    };

    Support.prototype.animate = function(obj,num){
        
        var button = obj;

        var praiseEle = null;

        if(button.id == "zan-home-button"){

            praiseEle = document.getElementById("zan-home");

        }else if(button.id == "zan-guest-button"){

            praiseEle = document.getElementById("zan-guest");

        }

        var options = {
            useEasing : true,
            useGrouping : true,
            separator : ',',
            decimal : '.',
            prefix : '',
            suffix : ''
        };

        var cu = new countUp(praiseEle, 0, num, 0, 0.4, options);
        cu.start();

        $(praiseEle).parent().find("em").css({"visibility":"visible"});

        $(praiseEle).parent().find("em").animate({
            top: "-55px",
            opacity:0
        }, "slow",function(){
            $(this).css({"top":0,"opacity":1,"visibility": "hidden"});
        });

    };

    Support.prototype.active = function(event){

        var that = event.data;

        var button = this;

		var matchID = $(this).data('mid');
		var teamID = $(this).data('tid');

        var isSupport = that.getSupport(matchID);

        if(isSupport){

            var oldValue = isSupport.split(',');

            if($.inArray(''+teamID,oldValue) > -1){

                alert('您已经支持过了!');

                return false;

            }else{

                oldValue.push(teamID);

                that.setSupport(matchID,oldValue.join(','));

            }

        }else{

            that.setSupport(matchID,teamID);

        }


        $.ajax({
            type: "GET",
            url: "http://sports.baofeng.com/api/like/" + matchID + '/' + teamID,
            success: function (msg) {
              var obj = jQuery.parseJSON(msg);
              //var obj = {status : 1, num : 23};
              if(obj.status == 1){
                  if(that.isAnimate){
                      that.animate(button,obj.num);
                  }else{
                      $(button).siblings('.zan-total').find('strong').text(obj.num);
                  }

                  if(that.isProgress){
                      that.progress();
                  }
              }
           }
        });

    };

    Support.prototype.getSupport = function(matchID){

        return localData.get('match_' + matchID);

    };

    Support.prototype.setSupport = function(matchID,newValue){

        localData.set('match_' + matchID,newValue);

    };

	return Support;

});
