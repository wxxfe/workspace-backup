/**
 * Created by yangbolun on 16/5/19.
 */
/**
 * 修改表格中的行内数据
 * @author Minghui
 * @date 2015-10-13
 * 使用方法：
 * var editRow = new EditRow({
 *     column : [
 *         {name : 'room_id',type : 'input',style : 'width:80px'},
 *         {name : 'sort',type : 'input',style : 'width:50px'},
 *         {name : 'status',type : 'select',option : [
 *             {name : '直播未开始',value : 0},
 *             {name : '直播中',value : 1},
 *             {name : '录像',value : 2},
 *             {name : '直播结束',value : 3}
 *         ],style : 'width:130px'}
 *     ],
 *     normalText : '编辑',
 *     saveText : '更新'
 * });
 * $('.btn-edit').on('click',function(){
 *     var state = $(this).data('status');
 *     var rid = $(this).data('rid');
 *     if(state === ''){
 *         liveEdit.edit(rid);
 *         liveEdit.setBtnStatus($(this),'edit');
 *     }else{
 *         liveEdit.setBtnStatus($(this),'save');
 *         var data = liveEdit.getValue(rid);
 *         liveEdit.updateRow(rid,data);
 *         console.log(data);
 *     }
 * });
 * 参考：/application/views/sort/list.php
 */
var EditRow = (function(){

    function EditRow(option){
        this.options = option || {};
    }

    /**
     * 将配置项转成可编辑状态
     * @param string id
     */
    EditRow.prototype.edit = function(id){
        var row = $('#' + id);
        $.each(this.options.column,function(index,item){
            var col = row.find('td[data-name="'+ item.name +'"]');
            var currentValue = col.text();
            var tag = '';
            var style = item.style || '';
            if(item.type === 'input'){
                tag = '<input type="text" name="'+ item.name +'" class="form-control" style="'+ style +'" value="'+ currentValue +'" />';
            }else if(item.type === 'select'){
                tag = '<select class="form-control" name="'+ item.name +'" style="'+ style +'">';
                $.each(item.option,function(i,o){
                    if(currentValue === o.name){
                        tag += '<option selected="selected" value="'+ o.value +'">'+ o.name +'</option>';
                    }else{
                        tag += '<option value="'+ o.value +'">'+ o.name +'</option>';
                    }
                });
                tag += '</select>';
            }
            col.html(tag);
        });
    }

    /**
     * 获取修改后的数据
     * @param string id
     * @return object
     */
    EditRow.prototype.getValue = function(id){
        var row = $('#' + id);
        var inputItem = row.find('input:not(:checkbox),select');
        var data = {};
        $.each(inputItem,function(index,item){
            var key = item.name;
            var value = item.value;
            data[key] = value;
        });
        console.log(data)
        return data;
    }

    /**
     * 将修改后的数据更新到行
     * @param string id
     * @param object data
     */
    EditRow.prototype.updateRow = function(id,data){
        var row = $('#' + id);
        var col = row.children();
        var newData = data;
        var columnOpt = this.options.column;
        col.each(function(){
            var name = $(this).data('name') || '';
            if(newData[name] != undefined){
                for(var i=0; i<columnOpt.length; i++){
                    if(columnOpt[i].name === name && columnOpt[i].type === 'select'){
                        var selectItem = columnOpt[i].option;
                        for(var j=0; j<selectItem.length; j++){
                            if(selectItem[j].value == newData[name]){
                                $(this).html(selectItem[j].name);
                            }
                        }
                    }else{
                        $(this).html(newData[name]);
                    }
                }
            }
        });
    }

    /**
     * 修改按钮显示状态
     * @param object button
     * @param string status
     */
    EditRow.prototype.setBtnStatus = function(button,status){
        var s = status,b = button;
        var normalText = this.options.normalText;
        var saveText = this.options.saveText;
        if(s === 'edit'){
            b.data('status','edit');
            b.html(saveText);
        }else{
            b.data('status','');
            b.html(normalText);
        }
    }

    return EditRow;
})();