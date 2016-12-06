require.config({
    baseUrl: 'src/scripts'
});
require(['lib/base64.min'], function (Base64) {

    var debug = function (d) {
        if (!d) {
            window.console = {
                log: function () {
                }
            };
        }
    };

    debug(true);

	var swiper = new Swiper('.swiper-container', {
		pagination: '.swiper-pagination',
		paginationType: 'fraction'
	});

	$('body').on('click','a',function(e){
		var params = $(this).data('param');
        console.log(params);
		if(params === undefined) return false;
		if(typeof params === 'string'){
			params = JSON.parse(params);
		}
		show(params);
	});

	function show(params){
		var type = params.type;
		switch(type){
			case 'news':
				var token = Base64.encode('olympics-/m/newsDetail/'+ params.id +'-/m/newsshare/' + params.id);
				openApp('jumpToNews',params.id,params.parent,params.title,params.image,token);
				break;
			case 'video':
				var token = Base64.encode('olympics-/m/videoshare/'+ params.id +'-/m/videoshare/' + params.id);
                var title = params.title,
                    site = params.site,
                    id = params.id,
                    image = params.image,
                    play_url = params.play_url,
                    play_code = params.play_code,
                    isvr = params.isvr;
				openApp('jumpToVideo',title,site,id,image,play_url,play_code,isvr,token);
				break;
			case 'page':
                openApp('jumpToH5',params.url);
				break;
			case 'program':
                openApp('jumpToProgram',params.id,params.title);
				break;
		}
	}

	function openApp(type,args){
		var _args = Array.prototype.slice.call(arguments);
		var type = _args.shift();
		if(getPlatform() == 'android'){
            switch(type){
                case 'jumpToNews':
                    window.webplay.jumpToNews(parseInt(_args[0]),_args[1],_args[2],_args[3],_args[4]);
                    break;
                case 'jumpToVideo':
                    window.webplay.jumpToVideo(_args[0],_args[1],_args[2],_args[3],_args[4],_args[5],_args[6],_args[7]);
                    break;
                case 'jumpToProgram':
                    window.webplay.jumpToProgram(_args[0],_args[1]);
                    break;
                default :
                    window.webplay.jumpToH5(_args[0]);
            }
			//window.webplay[type].apply(null,_args);
		}else if(getPlatform() == 'ios'){
            switch(type){
                case 'jumpToNews':
                    window.jumpToNews(parseInt(_args[0]),_args[1],_args[2],_args[3],_args[4]);
                    break;
                case 'jumpToVideo':
                    window.jumpToVideo(_args[0],_args[1],_args[2],_args[3],_args[4],_args[5],_args[6],_args[7]);
                    break;
                case 'jumpToProgram':
                    window.jumpToProgram(_args[0],_args[1]);
                    break;
                default :
                    window.jumpToH5(_args[0]);
            }
			//window[type].apply(null,_args);
		}
	}

	function getPlatform(){
		if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
			return 'ios';
		} else if (/(Android)/i.test(navigator.userAgent)) {
            return 'android';
		} else {
			return 'pc';
		}
	}

    //金牌时刻
    var p =  $('.person a');
    var pn = $('.j-moment');
    var wrap = $('#j-m');

    var menus = p.length;
    var ww = $(window).width();
    if(menus >= 6){
        $('.person').width(ww + (p.parent().outerWidth() * (menus - 5)));
    }else{
        $('.person').width(ww);
    }

    p.addSwipeEvents().bind('tap',function(){
        var pid = $(this).data('jid');
        var pc = $('#moment-' + pid);
        p.removeClass();
        $(this).addClass('active');
        if(pc.length > 0){
            pn.hide();
            pc.show();
        }else{
            $.get('http://2016.sports.baofeng.com/api/getChampionNews/'+ pid +'/5',function(d){
                var news = typeof d === 'string' ? JSON.parse(d) : d;
                if(news.status == 1){
                    addNewsFragment(pid,news.result);
                }
            });
        }
    });

    function getImageUrl(img){
        return new RegExp(/http:\/\//).test(img) ? img : 'http://image.sports.baofeng.com/' + img + '/152/114';
    }


    function getParam(obj){
        var item = {};
        if(obj.type == 'news'){
            item = {"type" : "news","id" : parseInt(obj.data.id),"parent" : "olympics","title" : obj.data.title,"image" : getImageUrl(obj.data.image)};
        }else{
            item = {
                "type" : "video",
                "title" : obj.data.title,
                "site" : obj.data.site,
                "id" : parseInt(obj.data.id),
                "image" : obj.data.image,
                "play_url" : obj.data.play_url,
                "play_code" : obj.data.play_code,
                "isvr" : obj.data.isvr
            };
        }
        return JSON.stringify(item);
    }

    function addNewsFragment(id,data){
        var news = data;
        var html = '<ul class="mixins-list j-moment" id="moment-'+ id +'">';
        for(var i=0; i<news.length; i++){
            html += '<li>';
            html += '   <a href="javascript:void(0)" data-param=\''+ getParam(news[i]) +'\'>';
            html += '       <div class="title">';
            html += '           <h3>'+ news[i].data.title +'</h3>';
            html += '           <p class="sub">'+ news[i].data.site +'</p>';
            html += '       </div>';
            html += '       <div class="pic">';
            html += '           <img src="'+ getImageUrl(news[i].data.image) +'" alt="'+ news[i].data.title +'" />';
            html += '       </div>';
            html += '   </a>';
            html += '</li>';
        }
        html += '</ul>';
        $('.j-moment').hide();
        wrap.append(html);        
    }


});
