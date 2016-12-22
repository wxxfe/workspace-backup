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
            <h1><?php if (isset($cp_id) && $cp_id) { echo '编辑';} else { echo '添加'; } ?>社区达人<small><?php if (isset($cp_id) && $cp_id) { echo 'Edit';} else { echo 'Create'; } ?> CommunityPeople </small></h1>
            <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><?php if (isset($cp_id) && $cp_id) { echo '编辑';} else { echo '添加'; } ?>社区达人</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="user-list">
                    <div class="box">
                        <div class="box-body">
                            <form class="form-horizontal"  method="post" id="news-form" data-toggle="validator" active="" >
                                <input type="hidden" name="cp_id" value="<?php if (isset($cp_id) && $cp_id) { echo $cp_id; } ?>" />
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="data">用户ID</label>
                                            <div class="col-sm-4">
                                                <input required class="form-control" type="text" id="use_id" name="id" <?php if (isset($cp_id) && $cp_id) {?> disabled="true" <?php }?> value="<?php if (isset($community_people) && $community_people) { echo $community_people[0]['user_id'];} ?>"  placeholder="请输入用户id" maxlength="128" data-bvalidator-msg="请输入用户id!" />
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="site">用户昵称</label>
                                            <div class="col-sm-4">
                                                <span <?php if (empty($cp_id)) { ?> id="user_name_sp" <?php }?> ><input class="form-control" type="text" id="user_name" name="username"   disabled="true" placeholder="" value="<?php if (isset($community_people) && $community_people) { echo $community_people[0]['name'];} ?>" /></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="type" class="control-label col-sm-2">达人类型</label>
                                            <div class="col-sm-4 bvalidator-bs3form-msg">
                                                <?php $types = array(30=>'优秀话题王', 29=>'懂球帝', 28=>'美图王'); ?>
                                                <select data-bvalidator-msg="请选择达人类型!" required="" name="type" class="form-control">
                                                    <option value="">请选择</option>
                                                    <?php foreach ($cp_type as $k=>$v) { ?>
                                                        <option value="<?=$k ?>" <?php if (isset($community_people) && $community_people && $community_people[0]['topfinger_id'] == $k) { echo "selected"; }?>><?=$v ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">



                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group text-center">
                                        <button class="btn btn-success btn-md" type="submit">提交</button>
                                        <button class="btn btn-info btn-md goback" type="button">返回</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="box box-solid">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2">
                                </div>
                            </div>
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
<script src="/static/fileupload/scripts/jquery.fileupload.js"></script>
<!-- AdminLTE App -->
<script src="/static/dist/js/app.min.js"></script>

<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>

<script>
    $('#news-form').bValidator({validateOn: 'blur,change'});

    $("#use_id").blur(function() {
        var user_id = $("#use_id").val();
        if(user_id != "" && user_id != null)
        {
            $.post('<?=base_url("/CommunityPeople/getNickById")?>',{id : user_id},function(d){
                if(d){
                    if(d == "exist")
                    {
                        alert("该用户已经是达人！");
                        $("#use_id").val("");
                        $("#user_name").val("");
                    }else{
                        $("#user_name").val(d);
                    }

                }else{
                    alert("用户id不存在！");
                    $("#use_id").val("");
                    $("#user_name").val("");
                }
            });
        }
    })

    $(".goback").click(function() {
        var referer = "<?=(!@empty($referer)? $referer : site_url('CommunityPeople/index')) ?>";
        if (referer) {
            window.location.href = referer;
        }
        return true;
    });
</script>
</body>
</html>
