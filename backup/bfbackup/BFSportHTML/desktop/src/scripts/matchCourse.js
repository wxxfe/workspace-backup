require.config({
	baseUrl: "/src/scripts"
});
require(['components/datePicker','components/bflogin'], function (datePicker,bflogin) {
    window.miniSSOLogin = bflogin;
	//登陆判断
    bflogin.loginJudge();
	//点击登陆弹层上的x
	window.ssoNoticeMessage = function(p){
		var a = decodeURI(p).split("&");
		if(a.shift() == "closeLoginWin"){
			bflogin.closeLoginWin();
		}
	}
	//注册日期控件&模拟placeholder
	var date = $("#date");
	var dateTimer;
	$("#date_select").on("click", function () {
		if(date.val() == '按日期查询比赛'){
			date.val("");
		}
		datePicker.setmonth(document.getElementById("date"),'yyyy-MM-dd','2010-01-01','2030-12-31',1,'d',dataChanged);
	});
	$(document).on("click",function(){
		if(date.val() == ''){
			date.val('按日期查询比赛');
		}
	});
	var $selectDateEleOffsetHeight = 0;//初始化滚动条位置
	var scollTimer;//初始化滚动框定时器
	//选择日期后获取具体时间回调函数
	var dataChanged = function(selectedDate) {
		$selectDateEleOffsetHeight = Math.abs($(".schedule_scoll").position().top);//每次点击后重新获取滚动条位置；
		var $selectDateEle = $("#" + selectedDate);//对应日期对应的赛程表格
		if($selectDateEle.size() == 0){
			alert("您选择的日期没有相应的赛程,请重新选择");
		}
		else{
			var scollContentHeight = $(".schedule_scoll").height();//所有赛程表总高度
			var scollMaxHeight = scollContentHeight - 800;//最大可滚动高度
			var olderOffset = $selectDateEleOffsetHeight;//上一次滚动的高度
			var currentSchedulePosition = $selectDateEle.position().top;//当前要滚动到日期的赛程表的绝对定位
			$selectDateEleOffsetHeight += currentSchedulePosition;//累计计算滚动条应该滚动到的位置
			if ($selectDateEleOffsetHeight > scollMaxHeight) {//超出滚动条滚动最大范围重设滚动到的位置
				$selectDateEleOffsetHeight = scollMaxHeight;
			}
			else if($selectDateEleOffsetHeight < 0){//超出滚动条滚动最小范围重设滚动到的位置
				$selectDateEleOffsetHeight = 0;
			}
			else {
			}
			if(currentSchedulePosition >= olderOffset){//如果挡前要滚动到的位置大于上一次滚动到的位置
				scollTimer = setInterval(function () {
					olderOffset += 550;
					if (olderOffset > $selectDateEleOffsetHeight) {
						olderOffset = $selectDateEleOffsetHeight;
						$("#schedule_all").scrollTop(olderOffset);
						clearInterval(scollTimer)
					}
					else {
						$("#schedule_all").scrollTop(olderOffset);
					}
				}, 10);
			}
			else if(currentSchedulePosition <= olderOffset){//如果挡前要滚动到的位置小雨上一次滚动到的位置
				scollTimer = setInterval(function () {
					olderOffset -= 550;
					if (olderOffset < $selectDateEleOffsetHeight) {
						olderOffset = $selectDateEleOffsetHeight;
						$("#schedule_all").scrollTop(olderOffset);
						clearInterval(scollTimer)
					}
					else {
						$("#schedule_all").scrollTop(olderOffset);
					}
				}, 10);
			}
		}
	};
	function dateScroll(date){//日期格式yyyy-mm-dd
		var tableNums = $(".schedule_scoll .schedule_data").size();
		var findNums = (new Date($(".schedule_scoll .schedule_data")[tableNums-1].id).getTime() - new Date($(".schedule_scoll table")[0].id).getTime())/(1000*60*60*24);
		var showTable;
		if(date){
			showTable = document.getElementById(date);
			dataChanged(date);
		}
		else{
			var myDate = new Date();
			var currentTimeStamp = myDate.getTime();
			var findBase = 1000*60*60*24;
			for(i=0;i<findNums;i++){
				var monthBack = new Date(currentTimeStamp - findBase*i).getMonth() + 1;
				var dateBack = new Date(currentTimeStamp - findBase*i).getDate()
				if(monthBack < 10){
					monthBack = "0" + monthBack
				}
				else{}
				if(dateBack < 10){
					dateBack = "0" + monthBack
				}
				else{}
				var findDateBack = new Date(currentTimeStamp - findBase*i).getFullYear() + "-" + monthBack + "-"  + dateBack;
				var monthForward = new Date(currentTimeStamp + findBase*i).getMonth() + 1;
				var dateForward = new Date(currentTimeStamp + findBase*i).getDate()
				if(monthForward < 10){
					monthForward = "0" + monthForward;
				}
				else{}
				if(dateForward < 10){
					dateForward = "0" +monthForward;
				}
				else{}
				var findDateForward = new Date(currentTimeStamp + findBase*i).getFullYear() + "-" + monthForward + "-"  + dateForward;
				if(document.getElementById(findDateBack)){
					//showTable = document.getElementById(findDateBack);
					//console.log(showTable);
					dataChanged(findDateBack);
					//showId = findDateBack;
					break;
				}
				else if(document.getElementById(findDateForward)){
					//showTable = document.getElementById(findDateForward);
					//console.log(showTable);
					dataChanged(findDateForward);
					//showId = findDateForward;
					break;
				}
				else{}
			}
		}
	}
	dateScroll();
});
