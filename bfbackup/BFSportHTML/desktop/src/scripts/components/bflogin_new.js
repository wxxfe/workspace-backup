require.config({
  baseUrl: 'src/scripts'
});
define(['components/localStorage'], function (localStorage) {

    var BfLogin =  {

        popupUrl : 'http://static.sso.baofeng.net/login/sso_login_popup.js',

        head : $('#head'),

        userInfoLayer : $('#user-manage'),

        userName : $('#username'),

        logoutBtn : $('#exit'),

        init : function(){
            this.loadJS(this.popupUrl,this.onCreateLoginPopup);
        },

		loadJS : function(src, callback){
			var _self = this;
			var script = document.createElement('script');
			var head = document.getElementsByTagName('head')[0];
			var loaded;
			script.src = src;
			if(typeof callback === 'function'){
				script.onload = script.onreadystatechange = function(){
					if(!loaded && (!script.readyState || /loaded|complete/.test(script.readyState))){
						script.onload = script.onreadystatechange = null;
						loaded = true;
						callback(_self);
					}
				}
			}
			head.appendChild(script);
		},

        onCreateLoginPopup : function(thisObj){
			var _self = thisObj;
            var isLogin = miniSSOLogin.isLogin(); 
            var uid = miniSSOLogin.getCookie('bfuid');
            if(!isLogin.status){
                _self.head.off('click').on('click',_self,_self.login);
            }else{
                _self.showInfo(uid,isLogin.info.username);
            }
        },

        showInfo : function(uid,username){
            var _self = this;
            var avatar = _self.getUserAvatar(uid);
            _self.head.html('<img src="'+ avatar +'" alt="'+ username +'" />').off('click').on('click',_self,_self.userInfo);
            _self.userName.html(username);
            _self.logoutBtn.attr('href','http://sso.baofeng.net/api/server/logout?from=sports_pcweb&next_action=' + window.location.href);
            $(document).on('click',function(e){
                var target = $(e.target);
                if(target.closest('.user-info,.login-btn').length == 0){
                    _self.userInfoLayer.hide();
                }
            });
        },

        getUserAvatar : function(uid){
            return 'http://img.baofeng.net/head/' + uid.substr(-4,4) + '/' + uid.substr(-8,4) + '/' + uid.substr(-12,4) + '/' + uid + '/100_80_80.jpg?t=' + new Date().getTime();
        },

        login : function(){
            miniSSOLogin.login();
        },

        userInfo : function(event){
            var _self = event.data;
            _self.userInfoLayer.toggle();
        },

        isLogin : function(){
            return window.miniSSOLogin ? miniSSOLogin.isLogin() : false;
        },

        loginCallback : function(status,username,userid){
            BfLogin.showInfo(userid,username);
            miniSSOLogin.closeLoginWin();
            $.get('http://sports.baofeng.com/login/sso/' + d.info.uid,function(d){
                var obj = jQuery.parseJSON(d);
                localStorage.set('stoken',obj.token);
            });
        }

    };

	window.ssoNoticeMessage = function(args){
		args = decodeURI(args).split("&"); 
		if(args && args.length){
			switch(args.shift()){
				case "loginSuccessCallback"://登录成功回调
				  BfLogin.loginCallback.apply(this,args);
				  break;
				case "closeLoginWin"://关闭窗口
				  miniSSOLogin.closeLoginWin();
				  break;
				default:
				  alert("没有调用");
			}
		}
	}

    window.ssologinfrom = 'sports_pcweb';

	return BfLogin;

});
