<!DOCTYPE html>
<html>
<?php if ($user_id > 0): ?>
<script>parent.location.replace("<?=site_url() ?>");</script>
<?php endif; ?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=HEAD_TITLE ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap-switch.min.css">
    <!-- jQuery UI -->
    <link rel="stylesheet" href="/static/plugins/jQueryUI/jquery-ui.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/static/plugins/datatables/dataTables.bootstrap.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/static/bootstrap/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/static/bootstrap/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/static/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->

<!--    <link rel="stylesheet" href="/static/dist/css/skins/_all-skins.min.css">-->
    <link rel="stylesheet" href="/static/dist/css/skins/_all-skins.css?v=1">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="/static/plugins/html5shiv.min.js"></script>
    <script src="/static/plugins/respond.min.js"></script>
    <![endif]-->
    <script src="/static/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <style>
        .table tbody tr td{vertical-align: middle;}
        .pics{height: 500px; overflow-y: auto; overflow-x: hidden; list-style: none; margin: 0; padding: 0;}
        .pics li{float: left; width: 270px; margin: 5px; padding: 0;}
        .pics li img{width: 100%;}
        .record{height: 300px; overflow: hidden; margin-bottom: 15px;}
        .sort a{display: inline-block; margin: 0 10px; color: #666;}
        .sort a.active{color: darkred;}

        table{table-layout: fixed;}
        td(word-break: break-all; word-wrap:break-word;)
    </style>
</head><body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <?=HEAD_TITLE ?>
<!--            <img src="/static/logo.jpg" alt="">-->
        </div>
        <div class="login-box-body">
            <p class="login-box-msg text-danger">
            <?php if ($is_login_failed): ?>登录失败，请重试！<?php endif; ?>
            </p>
            <form method="post" id="login-form">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="username" id="username" placeholder="请输入用户ID或昵称">
                    <span class="fa fa-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" id="password" placeholder="请输入密码">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <button type="submit" class="btn bg-purple btn-block btn-flat">登 录</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<!-- jQuery 2.1.4 -->
<script src="/static/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<script>
$('input').on('blur',function(){
    if($(this).val() != ''){
        $(this).parent().removeClass('has-error');
    }
});
$('#login-form').on('submit',function(){
    var username = $('#username');
    var password = $('#password');
    if(username.val() == ''){
        username.parent().addClass('has-error');    
    }
    if(password.val() == ''){
        password.parent().addClass('has-error');    
    }
    var error = $('.has-error').size();
    if(error > 0) return false;
});
</script>
</body>
</html>
