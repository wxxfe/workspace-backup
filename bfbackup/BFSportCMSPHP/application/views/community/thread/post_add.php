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
    <link rel="stylesheet" href="/static/dist/css/bootstrap-switch.min.css">
    <link rel="stylesheet" href="/static/fileupload/css/fileupload.css">
    <link rel="stylesheet" href="/static/fileupload/css/fileupload-ui.css">
    <link rel="stylesheet" href="/static/plugins/sweetalert-master/dist/sweetalert.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Begin emoji-picker Stylesheets -->
    <link href="/static/plugins/emoji-picker/lib/css/nanoscroller.css" rel="stylesheet">
    <link href="/static/plugins/emoji-picker/lib/css/emoji.css" rel="stylesheet">
    <!-- End emoji-picker Stylesheets -->
    <style type="text/css">
        .bootstrap-switch {
            margin-top: 5px;
        }

        #image-view img {
            margin: 10px 0;
            max-height: 200px;
        }

        .hide-input {
            position: absolute;
            height: 0;
            width: 0;
            pointer-events: none;
            visibility: hidden;
        }

        td {
            vertical-align: middle !important;
        }

        .emoji-wysiwyg-editor {
            height: 140px;
        }

        .ui-tooltip {
            display: none !important;
        }

    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content-header">
            <h1><?= $module_post_title ?>
                <small><?= $module_post_title_en ?></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?= $list_url ?>"><i class="fa"></i> <?= $module_title ?>列表</a></li>
                <li><a href="<?= $post_list_url . $thread_id ?>"><i class="fa"></i> <?= $module_post_title ?>列表</a></li>
                <li class="active"><?= $main_title ?></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= $main_title ?></h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" method="post" id="form" role="form">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">所属<?= $module_title ?></label>
                                    <div class="col-sm-7 form-control-static">
                                        <?= $thread_title ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="content" class="col-sm-3 control-label"><?= $module_post_title ?>
                                        内容</label>
                                    <div class="col-sm-7 bvalidator-bs3form-msg">
                                        <div class="lead emoji-picker-container">
                                            <textarea class="form-control" name="content" data-emojiable="true"
                                                      maxlength="1000"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image" class="col-sm-3 control-label">图片</label>
                                    <div class="col-sm-7 bvalidator-bs3form-msg">
                                        <div id="image-view">
                                        </div>
                                        <div class="input-group">
											<span class="btn btn-warning fileinput-button">
												<i class="glyphicon glyphicon-plus"></i>
												<span>上传图片</span>
												<input id="fileupload" type="file" name="image" multiple
                                                       data-url="<?= IMG_UPLOAD ?>"/>
											</span>
                                        </div>
                                        <input type="text" class="hide-input" id="image-id" name="image"
                                               value=""
                                               data-bvalidator="myValidationAction,valempty"
                                               data-bvalidator-msg="请输入内容或者上传图片!"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="user_id" class="col-sm-3 control-label">所属用户</label>
                                    <div class="col-sm-7 bvalidator-bs3form-msg">
                                        <select class="form-control" name="user_id"
                                                required min="1" data-bvalidator-msg="请选择一项!">
                                            <option value="0">请选择</option>
                                            <?php foreach ($users as $u_id => $u_name): ?>
                                                <option value="<?= $u_id ?>"><?= $u_name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="visible" class="col-sm-3 control-label">上下线</label>
                                    <div class="col-sm-7">
                                        <input class="release" type="checkbox" name="visible"
                                            <?php if ($visible == 1): ?>
                                                checked
                                            <?php endif; ?>
                                        >
                                    </div>
                                </div>

                                <div class="form-group text-center">
                                    <input type="hidden" name="thread_id" value="<?= $thread_id ?>"/>
                                    <button class="btn btn-success"><?= $submit_btn ?></button>
                                    <a style="margin-left: 20px" href="<?= $post_list_url . $thread_id ?>"
                                       class="btn btn-warning">取消</a>
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
<script src="/static/dist/js/jstree.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>
<!-- Begin emoji-picker JavaScript -->
<script src="/static/plugins/emoji-picker/lib/js/nanoscroller.min.js"></script>
<script src="/static/plugins/emoji-picker/lib/js/tether.min.js"></script>
<script src="/static/plugins/emoji-picker/lib/js/config.js"></script>
<script src="/static/plugins/emoji-picker/lib/js/util.js"></script>
<script src="/static/plugins/emoji-picker/lib/js/jquery.emojiarea.js"></script>
<script src="/static/plugins/emoji-picker/lib/js/emoji-picker.js"></script>
<!-- End emoji-picker JavaScript -->
<script>

    // emoji-picker
    // Initializes and creates emoji set from sprite sheet
    window.emojiPicker = new EmojiPicker({
        emojiable_selector: '[data-emojiable=true]',
        assetsPath: '/static/plugins/emoji-picker/lib/img/',
        popupButtonClasses: 'fa fa-smile-o'
    });
    // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
    // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
    // It can be called as many times as necessary; previously converted input fields will not be converted again
    window.emojiPicker.discover();

    //验证
    $('#form').bValidator({validateOn: 'blur'});

    //验证帖子内容和图片二选一
    function myValidationAction(fieldValue) {

        var contentJQ = $(".emoji-wysiwyg-editor");

        if ($.trim(contentJQ.text()) || fieldValue) {
            contentJQ.parents('.form-group').removeClass('has-error');
            var help = $(this).parent().find('.help-block');
            help.parents('.form-group').removeClass('has-error');
            help.remove();
            return true;
        }

        contentJQ.parents('.form-group').addClass('has-error');
        return false;
    }

    //切换开关
    $("input[name='visible']").bootstrapSwitch({
        size: 'mini',
        onText: '上线',
        offText: '下线'
    });

    //---------------------------
    //上传图片
    var imgUrl = '<?= IMG_DOMAIN ?>';

    //add 检查上传文件类型和大小
    function fileuploadAdd(e, data) {
        var uploadErrors = [];
        var acceptFileTypes = /^image\/(gif|jpe?g|png)$/i;
        if (data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
            uploadErrors.push('只能上传gif|jpe?g|png');
        }
        if (data.originalFiles[0]['size'].length && data.originalFiles[0]['size'] > 1800000) {
            uploadErrors.push('文件太大,不能超过1.8M');
        }
        if (uploadErrors.length > 0) {
            alert(uploadErrors.join("\n"));
        } else {
            data.submit();
        }
    }

    //submit 提交数据修改为接口要求的格式
    function fileuploadSubmit(e, data) {
        data.formData = {image: data.files[0]};
    }

    //done 单个图片上传成功
    function fileuploadDone(data, id1, id2) {
        var result = data.result.errno;
        if (result !== 10000) {
            alert('上传失败,请重试！')
        } else {
            var fullImgUrl = imgUrl + data.result.data.pid;
            $(id1).html('<a href="' + fullImgUrl + '" target="_blank"><img src="' + fullImgUrl + '">');
            var imageIdJQ = $(id2);
            imageIdJQ.val(data.result.data.pid);
            imageIdJQ.parent().find('.help-block').remove();
            imageIdJQ.parents('.form-group').removeClass('has-error');
        }
    }

    //上传input
    $('#fileupload').fileupload({
        add: fileuploadAdd,
        submit: fileuploadSubmit,
        dataType: 'json',
        autoUpload: false,
        done: function (e, data) {
            fileuploadDone(data, '#image-view', '#image-id');
        }
    });

</script>
</body>
</html>
