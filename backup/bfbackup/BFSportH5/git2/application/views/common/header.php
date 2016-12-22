<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
        <meta name="format-detection" content="telephone=no">

        <?php if(isset($keywords)): ?>
        <meta name="keywords" content="<?=$keywords?>">
        <?php else: ?>
        <meta name="keywords" content="体育直播,足球比赛,足球赛事,世界杯视频,中超,NBA">
        <?php endif; ?>

        <?php if(isset($description)): ?>
        <meta name="description" content="<?=$description?>" />
        <?php else: ?>
        <meta name="description" content="暴风体育是一个专业的足球直播平台,为您提供足球直播，中超直播，英超直播，欧洲杯直播等国内外重大体育赛事的现场直播；最全的、最及时的足球直播,尽在暴风体育。" />
        <?php endif; ?>

        <?php if(isset($title)): ?>
        <title><?=$title?></title>
        <?php else: ?>
        <title>体育直播_足球比赛_足球赛事_世界杯视频_暴风体育</title>
        <?php endif; ?>

		<style>html{color:#000;background:#fff;overflow-y:scroll;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}html *{outline:0;-webkit-text-size-adjust:none;-webkit-tap-highlight-color:rgba(0,0,0,0)}html,body{font-family:sans-serif}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td,hr,button,article,aside,details,figcaption,figure,footer,header,menu,nav,section{margin:0;padding:0}input,select,textarea{font-size:100%}table{border-collapse:collapse;border-spacing:0}fieldset,img{border:0}abbr,acronym{border:0;font-variant:normal}del{text-decoration:line-through}address,caption,cite,code,dfn,em,th,var{font-style:normal;font-weight:500}ol,ul{list-style:none}caption,th{text-align:left}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:500}q:before,q:after{content:''}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sup{top:-.5em}sub{bottom:-.25em}a:hover{text-decoration:none}ins,a{text-decoration:none}</style>

        <?php if(isset($page_type) && $page_type == 'app'): ?>
            <style>*{-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}textarea,input{-webkit-user-select:auto}</style>
        <?php endif;?>
        
        <?php foreach ($resource['css'] as $css): ?>
            <?php
            $css_local = str_replace('http://static.sports.baofeng.com/msports/'.ENVIRONMENT.'/css/', dirname(APPPATH) . '/static/css/' . ENVIRONMENT . '/' . $page_template . '/', $css);
            $css_content = @file_get_contents($css_local);
            ?>

            <?php if($css_content): ?>
                <style><?php echo $css_content;?></style>
            <?php endif;?>

        <?php endforeach; ?>

        <script>!function(a,b){function c(){var b=f.getBoundingClientRect().width;b/i>540&&(b=540*i);var c=b/10;f.style.fontSize=c+"px",k.rem=a.rem=c}var d,e=a.document,f=e.documentElement,g=e.querySelector('meta[name="viewport"]'),h=e.querySelector('meta[name="flexible"]'),i=0,j=0,k=b.flexible||(b.flexible={});if(g){console.warn("将根据已有的meta标签来设置缩放比例");var l=g.getAttribute("content").match(/initial\-scale=([\d\.]+)/);l&&(j=parseFloat(l[1]),i=parseInt(1/j))}else if(h){var m=h.getAttribute("content");if(m){var n=m.match(/initial\-dpr=([\d\.]+)/),o=m.match(/maximum\-dpr=([\d\.]+)/);n&&(i=parseFloat(n[1]),j=parseFloat((1/i).toFixed(2))),o&&(i=parseFloat(o[1]),j=parseFloat((1/i).toFixed(2)))}}if(!i&&!j){var p=(a.navigator.appVersion.match(/android/gi),a.navigator.appVersion.match(/iphone/gi)),q=a.devicePixelRatio;i=p?q>=3&&(!i||i>=3)?3:q>=2&&(!i||i>=2)?2:1:1,j=1/i}if(f.setAttribute("data-dpr",i),!g)if(g=e.createElement("meta"),g.setAttribute("name","viewport"),g.setAttribute("content","initial-scale="+j+", maximum-scale="+j+", minimum-scale="+j+", user-scalable=no"),f.firstElementChild)f.firstElementChild.appendChild(g);else{var r=e.createElement("div");r.appendChild(g),e.write(r.innerHTML)}a.addEventListener("resize",function(){clearTimeout(d),d=setTimeout(c,300)},!1),a.addEventListener("pageshow",function(a){a.persisted&&(clearTimeout(d),d=setTimeout(c,300))},!1),"complete"===e.readyState?e.body.style.fontSize=12*i+"px":e.addEventListener("DOMContentLoaded",function(){e.body.style.fontSize=12*i+"px"},!1),c(),k.dpr=a.dpr=i,k.refreshRem=c,k.rem2px=function(a){var b=parseFloat(a)*this.rem;return"string"==typeof a&&a.match(/rem$/)&&(b+="px"),b},k.px2rem=function(a){var b=parseFloat(a)/this.rem;return"string"==typeof a&&a.match(/px$/)&&(b+="rem"),b}}(window,window.lib||(window.lib={}));</script>

        <script>
            var _hmt = _hmt || [];
            (function() {
                  var hm = document.createElement("script");
                    hm.src = "//hm.baidu.com/hm.js?5fe3fa96d76722198e0d5f00f159fc54";
                    var s = document.getElementsByTagName("script")[0];
                      s.parentNode.insertBefore(hm, s);
            })();
        </script>

	</head>
	<body>
