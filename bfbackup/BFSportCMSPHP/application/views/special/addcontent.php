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
    <link rel="stylesheet" href="/static/dist/css/themes/default/style.min.css">
    <link rel="stylesheet" href="/static/dist/css/bootstrap-editable.css">
	<link rel="stylesheet" href="/static/fileupload/css/fileupload.css">
	<link rel="stylesheet" href="/static/fileupload/css/fileupload-ui.css">
    <link rel="stylesheet" href="/static/plugins/sweetalert-master/dist/sweetalert.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .table>tbody>tr>td{vertical-align: middle;}
        
        .user-list th, .user-list td {
            text-align: center;
        }
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
    <div class="wrapper">
        <div class="content-wrapper" style="margin-left: 0;">
            <section class="content-header">
              <h1>添加内容<small>Create Content </small></h1>
              <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">添加内容</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-body">
                                <form class="form-horizontal" method="post" id="news-form" data-toggle="validator">
                                    <input type="hidden" name="id" value="<?=$id ?>" />
                                    <input type="hidden" name="modid" value="<?=$modid ?>" />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="title">类型</label>
                                                <div class="col-sm-8">
                                                    <select name="type" class="form-control" required data-bvalidator-msg="请选择内容类型!">
                                                        <option value="news">新闻</option>
                                                        <option value="gallery">图集</option>
                                                        <option value="video">视频</option>
                                                    </select>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="">内容id</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" name="content_id" value="" required data-bvalidator-msg="请输入内容id!" />
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                            <!-- 
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="">人工排序</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" name="sort" value="1" />
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                            -->

            
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-success">添加</button>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
<!-- jQuery 2.1.4 -->
<script src="/static/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<!-- Jquery UI -->
<script src="/static/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/static/fileupload/scripts/jquery.ui.widget.js"></script>
<!-- AdminLTE App -->
<script src="/static/dist/js/app.min.js"></script>

<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>


<script>
$('#news-form').bValidator({validateOn: 'blur,change'});

</script>
</body>
</html>
