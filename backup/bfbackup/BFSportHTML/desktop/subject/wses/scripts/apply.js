//jQuery placeholder, fix for IE6,7,8,9
var JPlaceHolder = {
	//检测
	_check : function(){
		return 'placeholder' in document.createElement('input');
	},
	//初始化
	init : function(){
		if(!this._check()){
			this.fix();
		}
	},
	//修复
	fix : function(){
		jQuery(':input[placeholder]').each(function(index, element) {
			var self = $(this), txt = self.attr('placeholder');
			self.wrap($('<div></div>').css({position:'relative', zoom:'1', border:'none', background:'none', padding:'none', margin:'none'}));
			var pos = self.position(), h = self.outerHeight(true), paddingleft = self.css('padding-left');
			var holder = $('<span></span>').text(txt).css({position:'absolute', left:pos.left, top:pos.top, height:h, lienHeight:h, paddingLeft:paddingleft, color:'#aaa'}).appendTo(self.parent());
			self.focusin(function(e) {
				holder.hide();
			}).focusout(function(e) {
				if(!self.val()){
					holder.show();
				}
			});
			holder.click(function(e) {
				holder.hide();
				self.focus();
			});
		});
	}
};
//判断浏览器版本
function myBrowser(){
	var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
	var isOpera = userAgent.indexOf("Opera") > -1; //判断是否Opera浏览器
	var isIE = userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1 && !isOpera; //判断是否IE浏览器
	var isFF = userAgent.indexOf("Firefox") > -1; //判断是否Firefox浏览器
	var isSafari = userAgent.indexOf("Safari") > -1; //判断是否Safari浏览器
	if (isIE) {
		var IE5 = IE55 = IE6 = IE7 = IE8 = false;
		var reIE = new RegExp("MSIE (\\d+\\.\\d+);");
		reIE.test(userAgent);
		var fIEVersion = parseFloat(RegExp["$1"]);
		IE55 = fIEVersion == 5.5;
		IE6 = fIEVersion == 6.0;
		IE7 = fIEVersion == 7.0;
		IE8 = fIEVersion == 8.0;
		if (IE55) {
			return "IE55";
		}
		if (IE6) {
			return "IE6";
		}
		if (IE7) {
			return "IE7";
		}
		if (IE8) {
			return "IE8";
		}
	}//isIE end
	if (isFF) {
		return "FF";
	}
	if (isOpera) {
		return "Opera";
	}
}
var js_status = false;
//动态加载js
function include_js(file) {
	var _doc = document.getelementsbytagname('head')[0];
	var js = document.createelement('script');
	js.setattribute('type', 'text/javascript');
	js.setattribute('src', file);
	_doc.appendchild(js);
	if (!/*@cc_on!@*/0) { //if not ie
		//firefox2、firefox3、safari3.1+、opera9.6+ support js.onload
		js.onload = function () {
			js_status = true;
			//alert('firefox2、firefox3、safari3.1+、opera9.6+ support js.onload');
		}
	} else {
		//ie6、ie7 support js.onreadystatechange
		js.onreadystatechange = function () {
			if (js.readystate == 'loaded' || js.readystate == 'complete') {
				js_status = true;
				//alert('ie6、ie7 support js.onreadystatechange');
			}
		}
	}
	return false;
}

//验证表单
function check(obj){
	var data = {};
	if(obj.parent().find("input:eq(0)").val() == ''){
		try{
			swal("请填写您的真实姓名!");
		}
		catch (e){
			alert("请填写您的真实姓名!")
		}
	}
	else if(obj.parent().find("input:eq(1)").val() == ''){
		try {
			swal("请填写您的联系电话!");
		}
		catch (e){
			alert("请填写您的联系电话!")
		}
	}
	else{
		data.name = obj.parent().find("input:eq(0)").val();
		data.phone = obj.parent().find("input:eq(1)").val();
		if(obj.attr("id") == "apply_submit"){
			data.introduce = obj.parent().find("textarea:eq(0)").val();
			data.type = "personal";
                $.post("http://sports.baofeng.com/api/enroll", data,
                    function(dd){
                        var value = typeof dd === 'string' ? (new Function('return ' + dd))() : JSON.parse(dd);
						if(value.code == 0){
							try {
								swal(value.msg);
							}
							catch (e){
								alert(value.msg)
							}
						}
						else if(value.code == 1){
							try {
								swal("提交成功");
							}
							catch (e){
								alert("提交成功")
							}
                            $('input,textarea').val('');
	                        JPlaceHolder.init();
						}
                });
		}
		else if(obj.attr("id") == "subscribe"){
			data.introduce = obj.parent().find("textarea:eq(0)").val();
			data.content = obj.parent().find("textarea:eq(1)").val();
			data.type = "business";
                $.post("http://sports.baofeng.com/api/enroll",data,
                    function(dd){
                        var value = typeof dd === 'string' ? (new Function('return ' + dd))() : JSON.parse(dd);
						if(value.code == 0){
							try {
								swal(value.msg);
							}
							catch (e){
								alert(value.msg)
							}
						}
						else if(value.code == 1){
							try {
								swal("提交成功");
							}
							catch (e){
								alert("提交成功");
							}
                            $('input,textarea').val('');
	                        JPlaceHolder.init();
						}
                });
		}
	}
}

//执行
jQuery(function(){
	JPlaceHolder.init();

	$("#apply_submit").on("click",function(e){
		check($(this));
		return false;
	});
	$("#subscribe").on("click",function(e){
		check($(this));
		return false;
	});
});

