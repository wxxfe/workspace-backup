<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=HEAD_TITLE ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/static/bootstrap/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/static/bootstrap/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/static/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/static/dist/css/skins/_all-skins.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .one-level-menu a{
            color: #fff;
            background: rgba(0,0,0,.1);
        }
        .one-level-menu a:visited,
        .one-level-menu a:hover,
        .one-level-menu a:active,
        .one-level-menu a.active{
            font-weight: bold;
            color: #fff;
            text-decoration: none;
            background: rgba(0,0,0,.4);
        }
        
    </style>
</head>
<body class="hold-transition skin-purple sidebar-mini">
    <header class="main-header">
        <!-- Logo -->
        <a href="/main/dashboard" target="rightframe" class="logo" id="logo">
          <span class="logo-lg"><?=HEAD_TITLE ?></span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation" id="navbar">
            <!-- Sidebar toggle button-->
            <a href="#" id="sidebar-toggle" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="btn-group one-level-menu" style="margin-top: 8px; margin-left: 1%;">
                <?php foreach($menu as $key => $item): ?>
                <?php if($item['route'] != '#cms'): ?>
                <a class="btn btn-link btn-m" role="button" href="<?=site_url('/main/aside/'.$item['id'])?>" target="leftframe"><i class="<?=$item['icon']?>"></i> <?=$item['name']?></a>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div style="display: none;">
                <ul class="nav navbar-nav">
                <?php foreach ($menu as $one): ?>
                    <li class="dropdown user user-menu">
                        <a class="btn" href="<?=site_url('/main/aside/'.$one['id']) ?>" target="leftframe">
                            <i class="<?=$one['icon']?>"></i> <?=$one['name']?>
                        </a>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="<?=site_url('/main/dashboard')?>" target="rightframe">
                            <?php if(empty($user['avatar'])): ?>
                            <img class="img-circle user-image" src="/static/dist/img/avatar_default.png" alt="">
                            <?php else: ?>
                            <img class="img-circle user-image" src="http://image.sports.baofeng.com/<?=$user['avatar']?>" alt="">
                            <?php endif; ?>
                            <span class="hidden-xs"><?=!empty($user['nickname']) ? $user['nickname'] : $user['id']?></span>
                        </a>
                    </li>
                    <?php foreach($menu as $key => $item): ?>
                    <?php if($item['route'] == '#cms'): ?>
                    <li>
                        <a href="<?=site_url('/main/aside/'.$item['id'])?>" target="leftframe"><i class="fa fa-gears"></i> 设置</a>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <li>
                        <a href="javascript:void(0);" id="logout"><i class="fa fa-power-off"></i> 退出</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
<!-- jQuery 2.1.4 -->
<script src="/static/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script>
$('#logout').on('click',function(){
    $.get('<?=site_url('/cms/acl/logout')?>',function(){
        window.parent.location.reload();
    });
});
var st = document.getElementById('sidebar-toggle');
var logo = document.getElementById('logo');
var navbar = document.getElementById('navbar');
var isOpen = true;
st.onclick = function(){
    if(isOpen){
        window.parent.document.getElementsByTagName("frameset")[1].cols="0,*";
        logo.style.display = 'none';
        navbar.style.marginLeft = '0px';
        isOpen = false;
    }else{
        window.parent.document.getElementsByTagName("frameset")[1].cols="230,*";
        logo.style.display = 'block';
        navbar.style = '';
        isOpen = true;
    }
}

$('.btn-m').on('click',function(){
    $(this).siblings().removeClass('active');
    $(this).addClass('active');
});

</script>
</body>
</html>
