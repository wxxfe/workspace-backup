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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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


    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content-header">
            <h1>话题轮播图
                <small>Thread</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?= $list_url ?>"><i class="fa"></i> 话题轮播图列表</a></li>
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
                                    <label for="image_small" class="col-sm-3 control-label">轮播图</label>
                                    <div class="col-sm-7 bvalidator-bs3form-msg">
                                        <div id="image-view">
                                            <?php if ($image_small): ?>
                                                <a href="<?= getImageUrl($image_small) ?>" target="_blank"><img
                                                        src="<?= getImageUrl($image_small) ?>"></a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="input-group">
											<span class="btn btn-warning fileinput-button">
												<i class="glyphicon glyphicon-plus"></i>
												<span>上传图片</span>
												<input id="fileupload" type="file" name="image_small" multiple
                                                       data-url="<?= IMG_UPLOAD ?>"/>
											</span>
                                            <span style="margin-left: 10px;">尺寸750*260</span>
                                        </div>
                                        <input type="text" class="hide-input" id="image-id" name="image_small"
                                               value="<?= $image_small ?>"
                                               required data-bvalidator-msg="请上传图片!"/>
                                    </div>
                                </div>

                                <div class="form-group">

                                    <label for="threads_add" class="col-sm-3 control-label">话题ID</label>
                                    <div class="col-sm-7 bvalidator-bs3form-msg">
                                        <div class="clearfix">
                                        <input style="width: 60px;" class="form-control pull-left" name="threads_add"
                                               value="<?= $threads[0]['id'] ?>"
                                               type="text" data-bvalidator="ajax,required"
                                               data-bvalidator-msg="请输入有效话题ID！"/>
                                        <button onclick="return false;" class="btn bg-purple btn-flat pull-left" style="width: 60px; margin-left: 10px;">确认
                                        </button>
                                        <input name="name" value="<?= $threads[0]['title'] ?>" readonly
                                               style="position: absolute;top: 5px; left: 160px; border: none; width: 100%;
                                               <?php if (!$threads[0]['title']): ?>
                                                   display: none;
                                               <?php endif; ?>
                                                   " class="thread_title"/>
                                        </div>
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
                                    <?php if ($id): ?>
                                        <input type="hidden" name="id" value="<?= $id ?>"/>
                                    <?php endif; ?>
                                    <input type="hidden" name="threads" value=""/>
                                    <input type="hidden" name="type" value="<?= $type ?>"/>
                                    <button id="add-btn" class="btn btn-success"><?= $submit_btn ?></button>
                                    <a style="margin-left: 20px" href="<?= $redirect ?>"
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
<script>
    //验证配置
    bValidator.defaultOptions.ajaxResponseMsg = true;
    bValidator.defaultOptions.ajaxCache = false;
    bValidator.defaultOptions.ajaxUrl = "<?= $relate_threads_url ?>";
    var thread_title = $("input[name='name']");
    var threads_add = $("input[name='threads_add']");
    var threads_v = $("input[name='threads']");
    //重写bValidator的ajax回调函数
    bValidator.validators.ajax = function (value, ajaxResponse, postName) {

        var validationResult;

        // check if response from server is JSON
        try {
            var results = $.parseJSON(ajaxResponse);
            if (results[postName])
                validationResult = results[postName];
            //如果验证结果ok，则显示话题数据
            if (validationResult == bValidator.defaultOptions.ajaxValid) {
                thread_title.show();
                var thread_id = results['data'][0]['id'];
                thread_title.val(results['data'][0]['title']);
                threads_add.val(thread_id);
                threads_v.val(thread_id);
            } else {
                thread_title.hide();
            }
        }
            // ajaxResponse is not json
        catch (err) {
            validationResult = ajaxResponse;
        }

        return validationResult;
    };

    var formJQ = $('#form');
    var addClick = false;

    //接管提交按钮
    $('#add-btn').click(function () {
        addClick = true;
        var v = formJQ.data("bValidators").bvalidator.validate();
        //如果验证结果不是true，则拦截，因为用了ajax验证，会返回withAjax,这种情况后面afterAjax事件处理
        if (v !== true) {
            return false;
        }

    });

    //添加表单验证，blur表示input失去焦点就验证
    formJQ.bValidator({validateOn: 'blur'});

    //添加ajax验证完成的回调事件
    formJQ.on('afterAjax.bvalidator', function (event) {
        //如果表单验证通过，并且是点击提交按钮后的事件，则执行提交行为
        if (event.bValidator.instance.isValid() && addClick) {
            formJQ.submit();
        }
        addClick = false;
    });

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
