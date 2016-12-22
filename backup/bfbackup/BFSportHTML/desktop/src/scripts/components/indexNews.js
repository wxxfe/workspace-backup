/**
 * @des: 今日要闻
 * @author: minghui
 * @example: obj.init();
 */

define(function() {

    return {

        tabs : null,

        oldIndex : 0,

        news : [],

        init : function(){

            this.tabs = $('#tab-menu-football a');

            this.tabs.on('click',this,this.active);

        },

        active : function(event){

            var that = event.data;
            var index = that.tabs.index(this);
            var mid = $(this).data('mid');
            var panel = $('#tnc-' + index);
            var oldPanel = $('#tnc-' + that.oldIndex);
            that.tabs.eq(that.oldIndex).removeClass('active');
            $(this).addClass('active');

            var meid = mid == 0 ? 1 : mid;
            $('#n-more').attr('href','http://sports.baofeng.com/news/nlist/event/' + meid);

            if($.inArray(mid,that.news) == -1){
                that.loadNews(mid,panel);
            }

            oldPanel.hide();
            panel.show();
            that.oldIndex = index;

        },

        loadNews : function(mid,panel){

            var url = mid > 0 ? 'http://sports.baofeng.com/api/getFootballList/one/14/' + mid : 'http://sports.baofeng.com/api/getFootballList/all/14/';
            var wrap = panel;

            var that = this;

            $.ajax({
                type: "GET",
                url: url,
                success: function (msg) {
                    var obj = jQuery.parseJSON(msg);

                    if(obj.status == 1){

                        var news = that.show(obj.result);

                        wrap.html(news);

                        that.news.push(mid);

                    }

                }
            });

        },

        show : function(data){

            var news = data;

            var html = '';

            var tmp = [];

            var newslength = news.length;

            for(var i = 0; i < newslength; i++){

                if(i < 14){
                    tmp.push(news[i]);
                    if((i+1) % 7 == 0){

                        html += this.getFragment(tmp);

                        tmp = [];

                    }
                }

            }

            return html;

        },

        getFragment : function(data){

            var news = data;
            var picNews = data.splice(0,1)[0];

            var item = '';
            item += '<div class="one-row" >';
            item += '    <div class="col-6">';
            item += '        <div class="media medium photo fade">';
            item += '            <a href="http://sports.baofeng.com/news/detail/event/'+ picNews.id +'" target="_blank">';
            item += '                <img src="'+ picNews.image +'" alt="'+ picNews.title +'">';
            item += '                <p class="title">'+ picNews.title +'</p>';
            item += '            </a>';
            item += '        </div>';
            item += '    </div>';
            item += '    <div class="col-10">';
            item += '        <ul class="news-text-list">';
            for(var i = 0; i < news.length; i++){
                item += '            <li>';
                item += '                <a href="http://sports.baofeng.com/news/detail/event/'+ news[i].id +'" target="_blank">'+ news[i].title+'</a>';
                item += '                <span class="source">'+ news[i].publish_tm+'</span>';
                item += '            </li>';
            }
            item += '        </ul>';
            item += '    </div>';
            item += '</div>';

            return item;

        }

    }

});
