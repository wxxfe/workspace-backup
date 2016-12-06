/**
 * 搜索组件
 * minghui
 * ------------------
 */
(function(global){
    "use strict";
    
    function SearchBox(options){

        this.opt = {
            wrap : '',
            showAddButton : true,
            route : '',
            selected : function(){},
            templateResult : function(type,data){
                if(!data) return '';
                if (type == 'thread') {
                	var image = data.icon.indexOf('http://') > -1 ? data.icon : 'http://image.sports.baofeng.com/' + data.icon;
                } else {
                	var image = data.image.indexOf('http://') > -1 ? data.image : 'http://image.sports.baofeng.com/' + data.image;
                }
                var media = '';
				media += '<div class="media" style="margin-bottom: 10px;">';
				media += '	<div class="media-left">';
				media += '		<a href="javascript:void(0)">';
				media += '			<img width="100" class="media-object" src="'+ image +'" alt="'+ data.title +'">';
				media += '		</a>';
				media += '	</div>';
				media += '	<div class="media-body">';
				media += '		<h4 class="media-heading" style="font-size: 16px;">'+ data.title +'</h4>';
				media += '	</div>';
				media += '	<div class="media-right">';
				media += '		<a href="'+ this.route +'/'+ type +'/'+ data.id +'" role="button" class="btn btn-success btn-flat btn-add" data-rid="'+ data.id +'"><i class="fa fa-plus"></i> 添加</a>';
				media += '	</div>';
				media += '</div>';
                return media;
            }
        };

        this.opt.type = [
            {type : 'news', name : '新闻'},
            {type : 'video', name : '视频'},
            {type : 'gallery', name : '图集'}
        ];

        this.newsResult = '';
        this.videoResult = '';
        this.galleryResult = '';

        this.init(options);
    }

    SearchBox.prototype.init = function(config){

        var _self = this;

        $.extend(this.opt, config || {});

        this.createSearch();

        this.selectedName = $('#dropdown_value');
        this.select = $('#select_search');
        this.options = $('li',this.select);
        this.sbox = $('#sb_keyword');
        this.sbtn = $('#search_btn');
        this.searchResult = $('#search_result');

        $('a',this.options).on('click',function(){
            var value = $(this).data('type');
            var text = $(this).text();
            _self.selectedName.text(text);
            _self.select.attr('data-val',value);
        });

        this.sbtn.on('click',this,this.search);

    };

    SearchBox.prototype.createSearch = function(){
        var searchHtml = '';
        searchHtml += '<div class="input-group" style="position: relative;">';
        searchHtml += '    <div class="input-group-btn">';
        searchHtml += '        <button type="button" class="btn btn-default dropdown-toggle btn-flat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-right: none;"><span id="dropdown_value">'+ this.opt.type[0].name +'</span> <span class="caret"></span></button>';
        searchHtml += '        <ul id="select_search" class="dropdown-menu" style="border-radius: 0;" data-val="'+ this.opt.type[0].type +'">';

        for(var i=0; i<this.opt.type.length; i++){
            searchHtml += '            <li><a href="javascript:void(0)" data-type="'+ this.opt.type[i].type +'">'+ this.opt.type[i].name +'</a></li>';
        }

        searchHtml += '        </ul>';
        searchHtml += '    </div>';
        searchHtml += '    <input id="sb_keyword" class="form-control" placeholder="请输入关键词或ID" type="text" name="keyword" />';
        searchHtml += '    <span class="input-group-btn"><button id="search_btn" class="btn bg-purple btn-flat" type="button"><i class="fa fa-search"></i> 搜索并添加</button></span>';
        searchHtml += '    <div id="search_result" style="position: absolute; left: 63px; top: 33px; background: #fff; border: #ccc 1px solid; z-index: 5000; padding: 10px; display: none; width:500px; max-height: 500px; overflow-y: auto; overflow-x: hidden;"></div>';
        searchHtml += '</div>';

        this.opt.wrap.html(searchHtml);
    };

    SearchBox.prototype.search = function(event){
        var _self = event.data;
        var box = _self.sbox;
        var type = _self.select.attr('data-val');
        var keyword = box.val();
        if(keyword == ''){
            swal("错误!", "请输入关键词或ID！", "error");
            return false;
        }
        var _port = window.location.port ? ':' + window.location.port : '';
        var url = 'http://' + document.domain + _port + '/api/search/' + type + '/' + keyword;
        $.get(url,function(result){
            var data = typeof result === 'string' ? JSON.parse(result) : result;
            if(data.total > 0){
                var resultHtml = '<ul style="list-style: none; padding: 0; margin: 0;">';
                    console.log(data.result);
                for(var i=0; i<data.total; i++){
                    resultHtml += '<li>' + _self.opt.templateResult(type,data.result[i]) + '</li>';
                }
                resultHtml += '</ul>';
                _self.searchResult.html(resultHtml).show();
				//_self.searchResult.on('click','.btn-add',[_self,type,data.result],_self.selected);
            }else{
                _self.searchResult.html('<ul><li class="text-center">无</li></ul>').show();
            }
        });
    };

    SearchBox.prototype.selected = function(event){
        var _self = event.data[0];
        var type = event.data[1];
        var data = event.data[2];
        var index = _self.searchResult.find('.btn-add').index(this);
        _self.opt.selected(type,data[index]);
        _self.searchResult.hide();
    }

    global.SearchBox = SearchBox;


})(window);
