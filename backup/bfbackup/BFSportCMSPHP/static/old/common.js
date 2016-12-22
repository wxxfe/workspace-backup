//var base_path = "/cms/";
var base_path = "/";
var static_path = base_path + "static/";
var max_k = 200;
var img_max = 1024 * max_k;
var host = document.location.hostname;
//var url_path = "http://" + host + base_path + "index.php/";
var url_path = base_path + "index.php/";

var yes_gif = static_path + "images/yes.gif";
var no_gif = static_path + "images/no.gif";


////////////////////////////////////////////////////////////////////////////////////
// 改变下一个标签的显示状态
function nextToggle(obj) {
	if ($(obj).next().is(":visible")) {
		$(obj).children("img").attr("src", static_path + "images/close_1.gif");
	} else {
		$(obj).children("img").attr("src", static_path + "images/open_1.gif");
	}
	$(obj).next().toggle();
}
function childToggle(obj) {
	
}

// 加载进度条
function loadProcess(){
	$("#process").OpenDiv();
}
//关闭进度条
function loadClose(){
	$("#process").CloseDiv();
}

/////////////////////////////////////////////////////////////////////////////
// 改变状态
function changeState(obj) {
	
	if ($(obj).parent().siblings(".th_old").children(".modify").attr("stat") == "input") {
		if ($(obj).attr("src") != yes_gif) {
			$(obj).attr("src", yes_gif);
		} else {
			$(obj).attr("src", no_gif);
		}	
	}
}

function changeStateForce(obj){
	if ($(obj).attr("src") != yes_gif) {
		$(obj).attr("src", yes_gif);
	} else {
		$(obj).attr("src", no_gif);
	}	
}

function changeStateForceRsync(obj){
	if ($(obj).attr("src") != yes_gif) {
		//$(obj).attr("src", yes_gif);
		$(obj).attr("alt", "操作中");
		return yes_gif;
	} else {
		//$(obj).attr("src", no_gif);
		$(obj).attr("alt", "操作中");
		return no_gif;
	}	
}

//定时功能
function changeTimeState(obj) {
	
	if ($(obj).parent().siblings(".th_old").children(".modify").attr("stat") == "input") {
		if ($(obj).attr("src") != yes_gif) {
			$(obj).attr("src", yes_gif);
			timeToInput($(obj).parent().siblings(".th_online_at"), $("#time").val());
			timeToInput($(obj).parent().siblings(".th_offline_at"), $("#time").val());
		} else {
			$(obj).attr("src", no_gif);
			inputToText($(obj).parent().siblings(".th_online_at"));
			inputToText($(obj).parent().siblings(".th_offline_at"));
		}	
	}
}

// 返回状态值
function getState(obj) {
	if (static_path + "images/yes.gif" == $(obj).attr("src")) {
		return 1;
	} else {
		return 0;
	}
}

// 转化成可修改
function textToInput(obj, size) {
	var size = arguments[1] ? arguments[1] : 18;
	var date = arguments[2] ? arguments[2] : false;
	var old_value = $.trim($(obj).html());
	if (date) {
		$(obj).html("<input type='text' onClick=\"SelectDate(this,'yyyy-MM-dd',0,0)\" readonly size='" + size + "' value='" + old_value + "' />"); 
	} else {
		$(obj).html("<input type='text' size='" + size + "' value='" + old_value + "' />");
	}
}
function timeToInput(obj, time) {
	var old_value = $(obj).html();
	$(obj).html("<input type='text' onClick='"+time+"' value='"+old_value+"' size='18' readonly />");
}
function dateToInput(obj) {
	var old_value = $(obj).html();
	$(obj).html("<input type='text' onClick='WdatePicker()' value='"+old_value+"' size='10' readonly />");
}
function textareaToInput(obj) {
	$(obj).children().removeAttr("readonly");
	$(obj).children().attr("style", "background-color:#FFF;");
}

// 转化成不可修改
function inputToText(obj) {
	var new_value = $.trim($(obj).children('input').val());
	$(obj).html(new_value);
	return new_value;
}
function textareaToText(obj) {
	$(obj).children("textarea").attr("readonly", "readonly");
	$(obj).children("textarea").attr("style", "background-color:#AAA;");
	return $(obj).children("textarea").val();
}
function selectToText(obj) {
	var name = $(obj).children("select").children("option[selected='selected']").text();
	$(obj).children("span").html(name);
	$(obj).children("select").hide();
	return $(obj).children("select").val();
}

function modify(table, where, info) {
	var module = arguments[3] ? arguments[3] : '';
	var url = url_path + "util/modify";
	if (module != '') {
		if (module.indexOf("http") >= 0) {
			url = module;
		} else {
			url = url_path + module + "/util/modify";
		}
		
	}
	
	$.ajax({
		type : "POST",
		async : false,
		url : url,
		data : {
			'table' : table,
			'where' : JSON.stringify(where),
			'update' : JSON.stringify(info)
		},
		success : function(data) {
			var obj = JSON.parse(data);
			if (1 != obj.status) {
				alert(obj.msg);
			}
		}
	});
}

function modify_new(table, where, info, callback) {
	var module = arguments[4] ? arguments[4] : '';
	var url = url_path + "util/modify";
    var _callback = callback || function(){};
	if (module != '') {
		if (module.indexOf("http") >= 0) {
			url = module;
		} else {
			url = url_path + module + "/util/modify";
		}
		
	}
	
	$.ajax({
		type : "POST",
		async : false,
		url : url,
		data : {
			'table' : table,
			'where' : JSON.stringify(where),
			'update' : JSON.stringify(info)
		},
		success : function(data) {
			var obj = JSON.parse(data);
            _callback(obj);
			if (1 != obj.status) {
				alert(obj.msg);
			}
		}
	});
}

////////////////////////////////////////////////////////////////////////
//删除按钮
function deleteById_new(obj,callback){
	var module = arguments[2] ? arguments[2] : '';
	var url = url_path + module + "util/delete_by_id";
    var _callback = callback || function(){};
    $.ajax({
        type : "POST",
        url : url,
        data : obj,
        success : function(data) {
            var result = JSON.parse(data);
            if (1 == result.status) {
                _callback(result);
            } else {
                alert(result.msg);
            }
        }
    });
}
function deleteById(obj, table) {
	var cls = arguments[3] ? arguments[3] : '';
	var listItems = '<p>是否要删除该内容</p><br><input type="button" id="btnOk" value="是">&nbsp;&nbsp;<input type="button" id="btnNo" value="否">';
	$("#delete_div").html(listItems);
	$("#delete_div").OpenDiv();
	$("#btnNo").click(function(){
		$("#delete_div").CloseDiv();
	});
	
	var module = arguments[2] ? arguments[2] : '';
	var url = url_path + "util/delete_by_id";
	if (module != '') {
		if (module.indexOf("http") >= 0) {
			url = module;
		} else {
			url = url_path + module + "/util/delete_by_id";
		}
	}
	if (table != 'danmaku.comment') {
		var id = $(obj).parent().siblings(".th_id").html();
	} else {
		var id = $(obj).parent().siblings(".th_id").text();
	}
	var $delRow = $(obj).parent().parent();
	$("#btnOk").click(function(){
		$.ajax({
			type : "POST",
			url : url,
			data : {
				'table' : table,
				'id' : id
			},
			success : function(data) {
				var result = JSON.parse(data);
				if (1 == result.status) {
					alert(result.msg);
					$delRow.remove();
					
					// 删除后重新排序
					if(cls != ''){
						$('.'+cls).each(function(){
							var ind = $(this).parent().index();
							$(this).html(ind);
						});
					}
				} else {
					alert(result.msg);
				}
			}
		});
		$("#delete_div").CloseDiv();
	});
}
function deleteColumnhas(obj) {
	var listItems = '<p>是否要删除该内容</p><br><input type="button" id="btnOk" value="是">&nbsp;&nbsp;<input type="button" id="btnNo" value="否">';
	$("#delete_div").html(listItems);
	$("#delete_div").OpenDiv();
	$("#btnNo").click(function(){
		$("#delete_div").CloseDiv();
	});
	
	var module = arguments[1] ? arguments[1] : '';
	var seq = arguments[2] ? arguments[2] : 'th_seq';
	var url = url_path + "columns/column_has_delete";
	if (module != '') {
		url = url_path + module + "/columns/column_has_delete";
	}
	
	var cid = $(obj).parent().siblings(".th_columnid").html();
	var type = $(obj).parent().siblings(".th_type").html();
	var relid = $(obj).parent().siblings(".th_relid").html();
	var old_seq = $(obj).parent().siblings("." + seq).html();
	var $delRow = $(obj).parent().parent();
	$("#btnOk").click(function(){
		$.ajax({
			type : "POST",
			url : url,
			data : {
				'column_id' : cid,
				'type' : type,
				'rel_id' : relid
			},
			success : function(data) {
				old_seq = Number(old_seq);
				var result = JSON.parse(data);
				if (1 == result.status) {
					alert(result.msg);
					$delRow.remove();
					if (seq != '') {
						$("." + seq).each(function(){
							//var ind = $(this).parent().index();
							var this_seq = Number($(this).html());
							if (this_seq > old_seq) {
								$(this).html(this_seq - 1);
							}
						});
					}
				} else {
					alert(result.msg);
				}
			}
		});
		$("#delete_div").CloseDiv();
	});
}
function deleteColumnhasNew(obj) {
	var listItems = '<p>是否要删除该内容</p><br><input type="button" id="btnOk" value="是">&nbsp;&nbsp;<input type="button" id="btnNo" value="否">';
	$("#delete_div").html(listItems);
	$("#delete_div").OpenDiv();
	$("#btnNo").click(function(){
		$("#delete_div").CloseDiv();
	});
	
	var module = arguments[1] ? arguments[1] : '';
	var seq = arguments[2] ? arguments[2] : 'th_seq';
	var url = url_path + "columns/column_has_delete_new";
	if (module != '') {
		url = url_path + module + "/columns/column_has_delete_new";
	}
	
	var cid = $(obj).parent().siblings(".th_columnid").html();
	var type = $(obj).parent().siblings(".th_type").html();
	var relid = $(obj).parent().siblings(".th_relid").html();
	var old_seq = $(obj).parent().siblings("." + seq).html();
	var $delRow = $(obj).parent().parent();
	$("#btnOk").click(function(){
		$.ajax({
			type : "POST",
			url : url,
			data : {
				'column_id' : cid,
				'type' : type,
				'rel_id' : relid
			},
			success : function(data) {
				old_seq = Number(old_seq);
				var result = JSON.parse(data);
				if (1 == result.status) {
					alert(result.msg);
					$delRow.remove();
					if (seq != '') {
						$("." + seq).each(function(){
							//var ind = $(this).parent().index();
							var this_seq = Number($(this).html());
							if (this_seq > old_seq) {
								$(this).html(this_seq - 1);
							}
						});
					}
				} else {
					alert(result.msg);
				}
			}
		});
		$("#delete_div").CloseDiv();
	});
}

///////////////////////////////////////////////////////////////////////////////////
//上传新图片
function uploadNewCover(obj){
	var cid = arguments[2] ? arguments[2] : "cover";
	if ($(obj).attr("hidden_id")) {
		cid = $(obj).attr("hidden_id");
	}
	
	var text_name = "选择图片";
	if ($(obj).attr("text_name")) {
		text_name = $(obj).attr("text_name");
	}
	
	var module = arguments[1] ? arguments[1] : '';
	var url = url_path + "util/upload_new_cover";
	if (module != '') {
		url = url_path + module + "/util/upload_new_cover";
	}
	
	$(obj).uploadify({
    	'buttonText': text_name,
        'height'    : '20px',
		'swf'      : static_path + 'scripts/uploadify.swf',
		'uploader' : url,
		'onUploadStart' : function(file) {
			if (file.size > img_max) {
				$("#" + this.settings.button_placeholder_id).uploadify('cancel');
				alert("图片不能大于" + max_k + "K");
			}
		},
		'onUploadSuccess' : function(file, data, response) {
            console.log(data);
			var robj = JSON.parse(data);
			if (1 == robj.status) {
                console.log('22222' + robj.msg);
				$("#" + cid).val(robj.msg);
			} else {
				alert(robj.msg);
			}
		}
    });
}

//上传新star图片
function uploadNewStarCover(obj){
	var cid = "cover";
	if ($(obj).attr("hidden_id")) {
		cid = $(obj).attr("hidden_id");
	}
	
	var text_name = "选择图片";
	if ($(obj).attr("text_name")) {
		text_name = $(obj).attr("text_name");
	}
	
	var url = url_path + "util/upload_new_cover_mongo";
	var module = arguments[1] ? arguments[1] : '';
	if (module != '') {
		url = url_path + module + "/util/upload_new_cover";
	}
	
	$(obj).uploadify({
    	'buttonText': text_name,
        'height'    : '20px',
		'swf'      : static_path + 'scripts/uploadify.swf',
		'uploader' : url,
		'onUploadStart' : function(file) {
			if (file.size > img_max) {
				$("#" + this.settings.button_placeholder_id).uploadify('cancel');
				alert("图片不能大于" + max_k + "K");
			}
		},
		'onUploadSuccess' : function(file, data, response) {
			var robj = JSON.parse(data);
			if (1 == robj.status) {
				$("#" + cid).val(robj.msg);
			} else {
				alert(robj.msg);
			}
		}
    });
}

// 图片修改
function nextShow(obj) {
	$(obj).next().show();
}
function parentHide(obj) {
	$(obj).parent().hide();
}
function updateCoverById(obj, table){
	var tid = $(obj).siblings(".t_id").val();
	var field = $(obj).siblings(".t_field").val();
	var src = $(obj).parent().siblings("img").attr("src");
	var where = JSON.stringify({'id': tid});
	
	var module = arguments[2] ? arguments[2] : '';
	var url = url_path + "util/update_cover";
	if (module != '') {
		url = url_path + module + "/util/update_cover";
	}
	
	$(obj).uploadify({
    	'buttonText': '选择文件',
        'width'     : '100%',
        'height'    : '20px',
    	'formData'  : {
			'tid'	: tid,
			'table' : table,
			'field' : field,
			'where' : where,
			'src'   : src
		},
		'swf'      : static_path + 'scripts/uploadify.swf',
		'uploader' : url,
		/*
		'onBeforeInit' : function(bobj, settings) {
			var tid = bobj.siblings(".t_id").val();
			var field = bobj.siblings(".t_field").val();
			var where = '{"id": ' + tid + '}';
			
			settings.post_params['field'] = field;
			settings.post_params['where'] = where;
		},*/
		'onUploadStart' : function(file) {
			if (file.size > img_max) {
				$("#" + this.settings.button_placeholder_id).uploadify('cancel');
				alert("图片不能大于" + max_k + "K,请重新添加");
			}
		},
		'onUploadSuccess' : function(file, data, response) {
			var sobj = JSON.parse(data);
			if (1 == sobj.status) {
				$("#" + this.settings.button_placeholder_id).parent().siblings("img").attr("src", sobj.msg);
				$("#" + this.settings.button_placeholder_id).parent().hide();
			} else {
				alert(sobj.msg);
			}
		}
    });
}
function updateStarCoverById(obj, table){
	var tid = $(obj).siblings(".t_id").val();
	var child = $(obj).siblings(".child").val();
	var parent = $(obj).siblings(".parent").val();
	var name = $(obj).siblings(".name").val();
	var src = $(obj).parent().siblings("img").attr("src");
	
	var module = arguments[2] ? arguments[2] : '';
	var url = url_path + "util/update_cover_mongo";
	if (module != '') {
		url = url_path + module + "/util/update_cover";
	}
	
	$(obj).uploadify({
    	'buttonText': '选择文件',
        'width'     : '100%',
        'height'    : '20px',
    	'formData'  : {
			'table' : table,
			'tid'	: tid,
			'child' : child,
			'parent': parent,
			'name'	: name
		},
		'swf'      : static_path + 'scripts/uploadify.swf',
		'uploader' : url,
		'onUploadStart' : function(file) {
			if (file.size > img_max) {
				$("#" + this.settings.button_placeholder_id).uploadify('cancel');
				alert("图片不能大于" + max_k + "K,请重新添加");
			}
		},
		'onUploadSuccess' : function(file, data, response) {
			var sobj = JSON.parse(data);
			if (1 == sobj.status) {
				$("#" + this.settings.button_placeholder_id).parent().siblings("img").attr("src", sobj.msg);
				$("#" + this.settings.button_placeholder_id).parent().hide();
			} else {
				alert(sobj.msg);
			}
		}
    });
}

function updateColumnhasCover(obj){
	var module = arguments[1] ? arguments[1] : '';
	var url = url_path + "util/update_cover";
	if (module != '') {
		url = url_path + module + "/util/update_cover";
	}
	
	var cid = $(obj).siblings(".t_columnid").val();
	var type = $(obj).siblings(".t_type").val();
	var relid = $(obj).siblings(".t_relid").val();
	var field = $(obj).siblings(".t_field").val();
    var ctype = $(obj).siblings(".t_ctype").val();
	var src = $(obj).parent().siblings("img").attr("src");
	var where = JSON.stringify({'column_id': cid, 'type': type, 'rel_id': relid});
	
	$(obj).uploadify({
    	'buttonText': '选择文件',
        'width'     : '100%',
        'height'    : '20px',
    	'formData'  : {
			'table' : 'column_has',
			'field' : field,
			'where' : where,
			'src'   : src
		},
		'swf'      : static_path + 'scripts/uploadify.swf',
		'uploader' : url,
		/*
		'onBeforeInit' : function(bobj, settings) {
			var cid = bobj.siblings(".t_columnid").val();
			var type = bobj.siblings(".t_type").val();
			var relid = bobj.siblings(".t_relid").val();
			var field = bobj.siblings(".t_field").val();
			var where = {'column_id': cid, 'type': type, 'rel_id': relid};
			
			settings.post_params['field'] = field;
			settings.post_params['where'] = JSON.stringify(where);
		},
		*/
		'onUploadStart' : function(file) {
			if (file.size > img_max) {
				$("#" + this.settings.button_placeholder_id).uploadify('cancel');
				alert("图片不能大于" + max_k + "K");
			}
		},
		'onUploadSuccess' : function(file, data, response) {
			var sobj = JSON.parse(data);
			if (1 == sobj.status) {
				$("#" + this.settings.button_placeholder_id).parent().siblings("img").attr("src", sobj.msg);
				$("#" + this.settings.button_placeholder_id).parent().hide();
				// ralbum类型的，不修改albums表图片信息
				if (type != 'ralbum') {
                    $.post(url_path + 'columns/syncCoverH',{
                        'col_id' : cid,
                        'rel_id' : relid
                    },function(d){
                    });
				}
			} else {
				alert(sobj.msg);
			}
		}
    });
}

function updateCoverUrl(obj, table){
	var id = $(obj).siblings(".t_id").val();
	var field = $(obj).siblings(".t_field").val();
	var cover_url = $(obj).siblings(".cover_url").val();
	var info = {};
	info[field] = cover_url;
	modify(table, {'id': id}, info);
	$(obj).parent().siblings("img").attr("src", cover_url);
}
/////////////////////////////////////////////////////////////////////////////////////////////
/*
 * 渠道
 * id：id
 * table：表名
 * method：选择的方法，或者执行方法的接口
 * 	1、add：添加，需要有隐藏id=addMarketId的标签
 * 	2、modify： 修改
 */
function modify_channel(id, table, method)
{
	loadProcess();
	var url = url_path + "util/apk_market_view";
	var module = arguments[3] ? arguments[3] : '';
	if (module != '') {
		url = url_path + module + "/util/apk_market_view";
	}
	$.post(url, {'id':id, 'table': table}, function(data){
		loadClose();
		$(".marketDiv").html(data);
		$(".marketDiv").OpenDiv();
		//全选或者全不选
	    $("#marketSelAll").unbind().bind("change", function (){
	        if ("all" == $(this).val()) {
		        $(".div_enable").children("img").attr("src", yes_gif);
	        } else if("none" == $(this).val()) {
	        	$(".div_enable").children("img").attr("src", no_gif);
	        } 
	    });

		//取消
		$(".btn_close").click(function(){
			$(".marketDiv").CloseDiv();
		});
		//确定
		$(".btn_confirm").click(function(){
			$(".marketDiv").CloseDiv();

			var ids = Array();
			var names = Array();
			$(".div_enable").each(function(){
				if($(this).children("img").attr("src") == yes_gif)
				{
					var div_id = $(this).siblings(".div_id").html();
					var div_name = $(this).siblings(".div_name").html();
					ids.push(parseInt(div_id)); 
					names.push(div_name	); 
				}
			});
			ids = JSON.stringify(ids);
			names = JSON.stringify(names);
			if (method == 'add') {
				$("#addMarketId").val(ids);
				$("#addMarketName").val(names);
			}
			else if (method == 'modify') {
				var where = {
					'id': id
				};
				var info = {
					'market_id': ids
				};
				modify(table, where, info);
			}else {
				$.post(method, {
					"id": id,
					"names": names
				});
			}
		});
	});
}
/*
 * 地区组
 * id：id
 * table：表名
 * method：
 * 	1、add：添加，需要有隐藏id=addZgid的标签
 * 	2、modify： 修改
 */
function modify_zgid(id, table, method)
{
	loadProcess();
	var url = url_path + "util/apk_zgid_view";
	$.post(url, {'id':id, 'table': table}, function(data){
		loadClose();
		$(".zgidDiv").html(data);
		$(".zgidDiv").OpenDiv();
		//全选或者全不选
	    $("#zgidSelAll").unbind().bind("change", function (){
	        if ("all" == $(this).val()) {
		        $(".th_checked").children("img").attr("src", yes_gif);
	        } else if("none" == $(this).val()) {
	        	$(".th_checked").children("img").attr("src", no_gif);
	        } 
	    });

		//取消
		$(".btn_close").click(function(){
			$(".zgidDiv").CloseDiv();
		});
		//确定
		$(".btn_confirm").click(function(){
			$(".zgidDiv").CloseDiv();

			var ids = Array();
			$(".th_checked").each(function(){
				if($(this).children("img").attr("src") == yes_gif)
				{
					var div_id = $(this).siblings(".th_id").html();
					ids.push(parseInt(div_id)); 
				}
			});
			ids = JSON.stringify(ids);
			if(method == 'add')
				$("#addZgid").val(ids);
			else if(method == 'modify')
			{
				var where = {'id': id};
				var info = {
					'zgids': ids
				};
				modify(table, where, info);
			}
		});
	});
}
/////////////////////////////////////////////////////////////////////////////////////////////

/*
 * create    2013-11-29
 * author    zhangsai
 * 
 * 自定义频道的添加和修改
 */
function modify_custom(method){
	loadProcess();
	var url = url_path + "util/custom_view";
	var $span = $(arguments[1]).siblings("span");
	var table = arguments[2] ? arguments[2] : "";
	var id = arguments[3] ? arguments[3] : 0;
	$.ajax({
		"type" : "POST",
		"url"  : url,
		"data" : {'method':method, 'table':table, 'id':id},
		"success" : function(data){
			loadClose();
			$("#custom_div").html(data);
			$("#custom_div").OpenDiv();

			$("#custom_div #close_custom_div").click(function(){
				$("#custom_div").CloseDiv();
			});

			$("#custom_div #type_select").unbind().bind("change", typeChange);   
			function typeChange(){		
				var type = $(this).val();
				$("#custom_div .type_change").hide();
				$("#custom_div span[type_id='" + type + "']").show();
			}

			$("#custom_div #save").click(function(){
				var type_val = $("#custom_div #type_select").val();
				if (type_val == 0) {
					alert("宏观分类必须选择");
				} else {
					var type = new Array();
					type.push(parseInt(type_val));
					var type_name = $("#custom_div #type_select").children("option[selected='selected']").text();
					
					var styles = new Array();
					var style_names = new Array();
					$("#custom_div #style_box_div span[type_id='" + type + "'] input:checked").each(function(){
						styles.push(parseInt($(this).val()));
						style_names.push($(this).next("span").html());
					});
					if (styles.length == 0) {
						styles.push(0);
					}
					
					var areas = new Array();
					var area_names = new Array();
					$("#custom_div #area_box_div span[type_id='" + type + "'] input:checked").each(function(){
						areas.push(parseInt($(this).val()));
						area_names.push($(this).next("span").html());
					});
					if (areas.length == 0) {
						areas.push(0);
					}

					var years = new Array();
					var year_names = new Array();
					$("#custom_div #year_box_div input:checked").each(function(){
						years.push(parseInt($(this).val()));
						year_names.push($(this).next("span").html());
					});
					if (years.length == 0) {
						years.push(0);
					}

					var factors = {'type':type, 'style':styles, 'area':areas, 'year':years};
					factors = JSON.stringify(factors);
					if ("add" == method) {
						$("#factors").val(factors);
					} else if ("modify" == method) {
						factors_name = type_name 
							+ "<br />" + "类型：" + style_names.join("，") 
							+ "<br />" + "地区：" + area_names.join("，") 
							+ "<br />" + "年份：" + year_names.join(",");
						$span.html(factors_name);
						modify(table, {'id':id}, {'factors':factors});
					}
					$("#custom_div").CloseDiv();
				}
			});
		}
	});
}

/////////////////////////////////////////////////////////////////////////////////////////////
/*
 * 排序
 * obj : this
 * cls : 排序的class
 * table : sql 表名：
 */
function setOrder(obj, cls, table)
{
	var $tr = $(obj).parent().parent();
	var p_index = $(obj).parent().siblings("." + cls).text();
	var n_index = $(obj).siblings(".new_order").val();
	
	// 输入验证
	var re = /^[1-9]+[0-9]*]*$/;
	if (!re.test(n_index)) {  
        alert("请输入正整数"); 
        $(obj).siblings(".new_order").val("").focus();  
        return false; 
    }
	
	var rows = $tr.parent().find("tr").length - 1;
    if (n_index > rows) {
        alert("超过最大行数 " + rows);
        $(obj).siblings(".new_order").val("").focus(); 
        return false;
    }
    
    $tr.siblings("tr:eq(" + (n_index - 1) + ")").after($tr);
    $(obj).siblings(".new_order").val("");
	$("." + cls).each(function(){
		var ind = $(this).parent().index();
		$(this).html(ind);
	});
	
	var id = $(obj).parent().siblings(".th_id").html();
	var info = {
				'id':id, 
				'table':table, 
				'p_index':p_index,
				'n_index':n_index
				};
	
	var module = arguments[3] ? (arguments[3] + "/") : "";
	var url = url_path + module + "util/modify_order";
	if(table != 'self')
		$.post(url, info);
}

/*
 * 按平台及分类排序
 * obj : this
 * cls : 排序的class
 * table : sql 表名：
 */
function setOrderByPlatf(obj, cls, table, platf, type)
{
	var $tr = $(obj).parent().parent();
	var p_index = $(obj).parent().siblings("." + cls).text();
	var n_index = $(obj).siblings(".new_order").val();
	
	// 输入验证
	var re = /^[1-9]+[0-9]*]*$/;
	if (!re.test(n_index)) {  
        alert("请输入正整数"); 
        $(obj).siblings(".new_order").val("").focus();  
        return false; 
    }
	
	var rows = $tr.parent().find("tr").length - 1;
    if (n_index > rows) {
        alert("超过最大行数 " + rows);
        $(obj).siblings(".new_order").val("").focus(); 
        return false;
    }
    
    $tr.siblings("tr:eq(" + (n_index - 1) + ")").after($tr);
    $(obj).siblings(".new_order").val("");
	$("." + cls).each(function(){
		var ind = $(this).parent().index();
		$(this).html(ind);
	});
	
	var id = $(obj).parent().siblings(".th_id").html();
	var info = {
				'id':id, 
				'table':table, 
				'p_index':p_index,
				'n_index':n_index,
				'platf':platf,
				'type':type
				};

	var url = url_path + "util/modify_order_by_platf";
	if(table != 'self')
		$.post(url, info);
}

/////////////////////////////////////////////////////////////////////////////////////////////
/*
 * 不唯一排序 setOrder的简化方法，不保证排序唯一
 * obj : this
 * cls : 排序的class
 * table : sql 表名：
 */
function setOrderNoUnique(obj, cls, table)
{
	var $tr = $(obj).parent().parent();
	var p_index = $(obj).parent().siblings("." + cls).text();
	var n_index = $(obj).siblings(".new_order").val();
	
	// 输入验证
	var re = /^[1-9]+[0-9]*]*$/;
	if (!re.test(n_index)) {  
        alert("请输入正整数"); 
        $(obj).siblings(".new_order").val("").focus();  
        return false; 
    }
	
	var rows = $tr.parent().find("tr").length - 1;
    if (n_index > rows) {
        alert("超过最大行数 " + rows);
        $(obj).siblings(".new_order").val("").focus(); 
        return false;
    }
    
	var id = $(obj).parent().siblings(".th_id").html();
	var info = {
				'id':id, 
				'table':table,
				'n_index':n_index
				};
	
	var module = arguments[3] ? (arguments[3] + "/") : "";
	var url = url_path + module + "util/modify_order_no_unique";
	if(table != 'self')
		$.post(url, info, function(data){
			location.reload();
		});
}

/**
 * 点“全部确定”效果
 * @param string table 操作表名
 * @param string platf 平台
 * @param string versions 版本(非android)
 * @param array ids 要操作的id
 * @param array order 变更后的顺序
 * 
 */
function setOrdersOneByOne(table, platf, versions, ids, orders) {
	var url = url_path + "search_key/sort_onebyone";
	loadProcess();
	$.ajax({
		type : "POST",
		data : {"table" : table, "platf" : platf, "versions" : versions, "ids" : ids, "orders" : orders},
		url  : url,
		success : function(data) {
			loadClose();
			alert("排序成功！");
			window.location.href = window.location.href;
		}
	});
	
	return true;
}

/////////////////////////////////////////////////////////////////////////////////////////////
/*
 * column_has的排序
 * obj : this
 */
function setColumnHasSeq(obj){
	var $tr = $(obj).parent().parent();
	//var old_seq = $(obj).parent().siblings(".th_seq").text();
	var new_seq = $(obj).siblings(".new_seq").val();
	
	// 输入验证
	var re = /^[1-9]+[0-9]*]*$/;
	if (!re.test(new_seq)) {  
        alert("请输入正整数"); 
        $(obj).siblings(".new_seq").val("").focus();  
        return false; 
    }
    
    var rows = $tr.parent().find("tr").length - 1;
    if (new_seq > rows) {
        alert("超过最大行数 " + rows);
        $(obj).siblings(".new_seq").val("").focus(); 
        return false;
    }

	var where = {};
	where['column_id'] = $(obj).parent().siblings(".th_columnid").text();
	where['type'] = $(obj).parent().siblings(".th_type").text();
	where['rel_id'] = $(obj).parent().siblings(".th_relid").text();
	var info = {};
	info['new_seq'] = new_seq;
	
	var module = arguments[1] ? arguments[1] : '';
	var url = url_path + "columns/column_has_modify_seq";
	if (module != '') url = url_path + module + "/columns/column_has_modify_seq";
	loadProcess();
	$.ajax({
		type : "POST",
		data : {"where" : where, "info" : info},
		url  : url,
		success : function(data) {
			loadClose();
			var back = JSON.parse(data);
			if (1 == back.status) {
				$tr.siblings("tr:eq(" + (new_seq - 1) + ")").after($tr);
			    $(obj).siblings(".new_seq").val("");
				$(".th_seq").each(function(){
					var ind = $(this).parent().index();
					$(this).html(ind);
				});
			} else {
				alert(back.msg);
			}
		}
	});
}
/////////////////////////////////////////////////////////////////////////////////////////////
/*
 * 获取当前时间
 */
function getCurrent() {
    var date = new Date();
    var current_date = date.getFullYear()
    	+ "-" + getFull(date.getMonth() + 1)
    	+ "-" + getFull(date.getDate())
    	+ " " + getFull(date.getHours())
    	+ ":" + getFull(date.getMinutes())
    	+ ":" + getFull(date.getSeconds());
    return current_date;
}

function getFull(one) {
	return one > 9 ? one : "0" + one;
}

//分地域批量选择
function conf_select(obj){
    var tmp = $(obj).attr("aid");
    tmp = "." + tmp;

	if($(obj).attr("checked") == "checked")
	    $(tmp).each(function(){
		    $(this).children("img").attr("src", yes_gif);});
	else
	    $(tmp).each(function(){
			$(this).children("img").attr("src", no_gif);});
};

/*禁用backspace键的后退功能，但是可以删除文本内容*/  
document.onkeydown = check;  
function check(e) {  
    var code;  
    if (!e) var e = window.event;  
    if (e.keyCode) code = e.keyCode;  
    else if (e.which) code = e.which;  
    if (((event.keyCode == 8) &&                                                      
         ((event.srcElement.type != "text" &&   
         event.srcElement.type != "textarea" &&   
         event.srcElement.type != "password") ||   
         event.srcElement.readOnly == true)) ||   
        ((event.ctrlKey) && ((event.keyCode == 78) || (event.keyCode == 82)) ) ||    //CtrlN,CtrlR   
        (event.keyCode == 116) ) {                                                   //F5   
        event.keyCode = 0;   
        event.returnValue = false;   
    }  
    return true;  
}  

//统一逗号

function getStandardComma(str){
	str = str.replace(/(^\s*)|(\s*$)/g, "");
	return str.replace(/(，\s*)|(,\s*)/g, ", ");
}

/**
 * column_has数据的导出
 */

function columnhasExport(id){
	loadProcess();
	$.ajax({
		type : "POST",
		data : {'cid' : id},
		url : url_path + "columns/column_has_export",
		success : function(data) {
			loadClose();
			var down_url = static_path + "csvs/" + data;
			window.open(down_url);
		}
	});
}

/**
 * column_has的重置排序
 * @param id
 */
function columnhasResetSeq(id) {
	loadProcess();
	$.ajax({
		type : "POST",
		data : {'id' : id},
		url  : url_path + "columns/column_has_reset_seq",
		success : function(data){
			loadClose();
			if (1 == data) {
				alert("重置成功");
				location.reload();
			} else {
				alert(data);
			}
		}
	});
}

/**
 * 普通表的重置排序
 */
function resetSort(table, where){
	loadProcess();
	var sort = arguments[2] ? arguments[2] : "sort";
	$.ajax({
		type : "POST",
		data : {'table':table, 'sort':sort, 'where':where},
		url  : url_path + "util/reset_sort",
		success : function(data){
			loadClose();
			if (1 == data) {
				alert("重置成功");
				location.reload();
			} else {
				alert(data);
			}
		}
	});
}


/////////////////////////////////////////////////////////////////////////////

// 根据返回值判断ajax是否提交成功
function ajax_check_succ(data) {
    if (data == "forbidden") {
        alert("你没有修改权限！");
        return false;
    }
    return true;
}

// ajax提交成功后刷新页面
function ajax_succ_reload(data) {
    if (!ajax_check_succ(data)) {
        return;
    }
    location.reload();
}

// ajax出错时提示
function ajax_error(data) {
    $("#ajax_result").css("color","red").text("出错了~: "+data.status+", "+data.statusText);
}

// 简单的提示信息
function simple_tips(obj, tips) {
    var ori_color = obj.css("color");
    var ori_text  = obj.text();
    obj.hover(function(){
        $(this).css("color", "#1d953f").text(tips);
    },function(){
        $(this).css("color", ori_color).text(ori_text);
    });
}
