define(function() {
    return {

        tabs : null,

        oldIndex : 0,

        videos : [],

        type : {'replay' : '录像', 'highlight' : '集锦', 'forecast' : '前瞻'},

        init : function(){

            this.tabs = $('#tab-menu-moment a');

            this.tabs.on('click',this,this.active);

        },

        active : function(event){

            var that = event.data;
            var index = that.tabs.index(this);
            var mid = $(this).data('mid');
            var panel = $('#mv-' + index);
            var oldPanel = $('#mv-' + that.oldIndex);
            that.tabs.eq(that.oldIndex).removeClass('active');
            $(this).addClass('active');

            var meid = mid == 0 ? 7 : mid;

            $('#m-more').attr('href','http://sports.baofeng.com/video/vlist/' + meid);


            if($.inArray(mid,that.videos) == -1){
                that.loadVideos(mid,panel);
            }

            oldPanel.hide();
            panel.show();
            that.oldIndex = index;

        },

        loadVideos : function(mid,panel){
            
            var url = 'http://sports.baofeng.com/api/getVideosList/'+ mid +'/8';
            var wrap = panel;

            var that = this;

            $.ajax({
                type: "GET",
                url: url,
                success: function (msg) {
                    var obj = jQuery.parseJSON(msg);

                    if(obj.status == 1){

                        var videos = that.show(obj.result);

                        wrap.html(videos);

                        that.videos.push(mid);

                    }

                }
            });

        },

        show : function(data){

            var videos = data;

            var html = '';

            var videoLength = videos.length;

            for(var i = 0; i < videoLength; i++){

                html += this.getFragment(videos[i]);

            }

            return html;

        },

        getFragment : function(data){

            var video = data;

            var rel = video.type != 'bfonline' ? ' rel="nofollow"' : '';

            var item = '<li class="col-4">';
            item += '<div class="media small photo fade">';
            if(video.type == 'forecast'){
            item += '   <span class="tag b-red">'+ this.type[video.type] +'</span>';
            }else{
            item += '   <span class="tag b-blue">'+ this.type[video.type] +'</span>';
            }
            item += '   <a href="'+ video.url +'" target="_blank"'+ rel +'>';
            item += '       <img src="'+ video.image +'" alt="'+ video.title +'" />';
            item += '       <p class="title">'+ video.title +'</p>';
            item += '   </a>';
            item += '</div>';
            item += '</li>';

            return item;

        }

    }
});
