<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= HEAD_TITLE ?></title>
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
        .table > tbody > tr > td {
            vertical-align: middle;
        }

        .user-list th, .user-list td {
            text-align: center;
        }
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content-header">
            <h1>
                <?=isset($id) ? '编辑' : '添加';?>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><?=isset($id) ? '编辑' . $this->page_name : '添加' . $this->page_name;?></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="user-list">
                    <div class="box">
                        <div class="box-body">
                            <form class="form-horizontal" method="post" id="news-form" data-toggle="validator"
                                  active="">
                                <input type="hidden" name="id" value="<?php if (isset($id) && $id) {
                                    echo $id;
                                } ?>"/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type" class="control-label col-sm-2">内容类型</label>
                                            <div class="col-sm-3 bvalidator-bs3form-msg">
                                                <?php $types = $this->EX_LM->types; ?>
                                                <select id="type-select" data-bvalidator-msg="请选择焦点图数据类型!" required name="type" class="form-control">
                                                    <option value="">请选择</option>
                                                    <?php foreach ($types as $k => $v) { ?>
                                                        <option
                                                            value="<?= $k ?>" <?php if (isset($info) && $info && $info['type'] == $k) {
                                                            echo "selected";
                                                        } ?>><?= $v ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="data">ID/URL</label>
                                            <div class="col-sm-8 bvalidator-bs3form-msg">
                                                <div class="clearfix">
                                                    <input data-bvalidator="checkData,required"
                                                           class="check-data-input form-control pull-left"
                                                           style="width: calc(100% - 80px);"
                                                           type="text" name="data"
                                                           value="<?php if (isset($info) && $info) {
                                                               if ($info['type'] == 'html') {
                                                                   echo $info['url'];
                                                               } else {
                                                                   echo $info['ref_id'];
                                                               }
                                                           } ?>" placeholder="请先选择类型，再输入ID/URL"
                                                           data-bvalidator-msg="请先选择类型，再输入ID/URL" maxlength="128"/>
                                                </div>
                                                <div class="dataInfo"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="title">标题</label>
                                            <div class="col-sm-8 bvalidator-bs3form-msg">
                                                <input required class="form-control" type="text" name="title"
                                                       value="<?php if (isset($info) && $info) {
                                                           echo $info['title'];
                                                       } ?>" placeholder="请输入标题，最多18个字" maxlength="54"
                                                       data-bvalidator-msg="请输入标题，最多18个字"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="title">推荐语</label>
                                            <div class="col-sm-8">
                                                <textarea required class="form-control" type="text" name="brief" value="<?php if (isset($info) && $info) { echo $info['brief'];} ?>" placeholder="请输入推荐语" data-bvalidator-msg="请输入推荐语!" /><?php if (isset($info) && $info) { echo $info['brief'];} ?></textarea>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="">上传图片</label>
                                            <div class="col-sm-8">
                                                <span class="btn btn-warning fileinput-button">
                                                        <i class="glyphicon glyphicon-plus"></i>
                                                        <span>上传图片</span>
                                                        <input id="fileupload" type="file" name="image" multiple
                                                               data-url="<?= IMG_UPLOAD ?>"/>
                                                    </span>
                                                <span style="margin-left:30px;">建议尺寸316*178</span>
                                                <input id="poster" type="hidden" name="poster" value="<?php if (isset($info) && $info['image']) {echo $info['image'];} ?>"/>
                                                <div id="image-view" style="padding: 10px 0; <?php if (isset($info) && !$info['image']) {echo 'display: none;';} ?>"><img style="max-height: 200px;" src="<?php if (isset($info) && $info['image']) {echo getImageUrl($info['image']);} ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="large_image" class="control-label col-md-2">上传大图</label>
                                            <div class="col-md-8">
                                                <span class="btn btn-warning fileinput-button">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                    <span>上传图片</span>
                                                    <input id="fileupload3" type="file" name="image" multiple data-url="<?= IMG_UPLOAD ?>" />
                                                </span>
                                                <span style="margin-left:30px;">建议尺寸699*263</span>
                                                <input id="large_image" type="hidden" name="large_image" value="<?php if (isset($info) && $info['large_image']) {echo $info['large_image'];} ?>"/>
                                                <div id="image-view-3" style="padding: 10px 0; <?php if (isset($info) && !$info['large_image']) {echo 'display: none;';} ?>">
                                                    <img style="max-height: 200px;" src="<?php if (isset($info) && $info['large_image']) {echo getImageUrl($info['large_image']);} ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="site">排序</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="priority" placeholder="" value="<?php if (isset($info)){echo $info['priority'];} else {echo 1;}?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="visible">上下线</label>
                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <label><input type="checkbox" name="visible"
                                                                  value="1" <?php if (isset($info) && $info['visible']) {
                                                            echo 'checked="checked"';
                                                        } ?>></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group text-center">
                                        <button class="btn btn-success" type="submit">保存</button>
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
<script src="/static/fileupload/scripts/jquery.fileupload.js"></script>
<!-- AdminLTE App -->
<script src="/static/dist/js/app.min.js"></script>

<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>

<script>
    var $dataInfo = $('.dataInfo');
    //check content type
    bValidator.validators.checkData = function (value) {
        var res = true;
        var type = $("select[name='type'] option:selected").val();
        if(!type) {
            return false;
        }
        $.ajax({
            url: '<?=base_url("app/lists/checkContent");?>',
            data: {id: value, type:type},
            type: 'post',
            async: false,
            success: function (data) {
                var resultData = $.parseJSON(data);
                if (resultData.status == 'yes') {
                    $dataInfo.html(resultData.data);
                } else {
                    res = resultData.data;
                    $dataInfo.html('');
                }
            }
        });
        return res;
    };
    $('#news-form').bValidator({validateOn: 'blur', errorValidateOn: 'blur'});

    var $checkDataInput = $(".check-data-input");
    $("#type-select").on('change', function(e){
        $checkDataInput.trigger('blur');
    });

    $('#fileupload').fileupload({
        add: function (e, data) {
            //data.context = $('<p/>').text('Uploading...').appendTo('#content');
            data.submit();
        },
        done: function (e, data) {
            //$('#cover').val(data);
            var result = data.result.errno;

            if (result !== 10000) {
                alert('上传失败,请重试！');
            }
            else {
                $("#image-view").html('<img style="width:100%;" src="http://image.sports.baofeng.com/' + data.result.data.pid + '">').show();
                $('#poster').val(data.result.data.pid);
            }
        }
    });

    $('#fileupload3').fileupload({
        add: function (e, data) {
            //data.context = $('<p/>').text('Uploading...').appendTo('#content');
            data.submit();
        },
        done: function (e, data) {
            //$('#cover').val(data);
            var result = data.result.errno

            if(result !== 10000){
                alert('上传失败,请重试！')
            }
            else{
                $("#image-view-3").html('<img src="http://image.sports.baofeng.com/' + data.result.data.pid + '" style="width:150px;">')
                $('#large_image').val(data.result.data.pid);
            }
        }
    });
</script>
</body>
</html>
