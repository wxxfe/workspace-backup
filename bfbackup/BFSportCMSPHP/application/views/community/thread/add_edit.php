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

        .communities {
            width: 32%;
            margin-right: 2%;
            float: left;
        }

        .communities:last-child {
            margin-right: 0;
        }

        .default_images {
            height: 56px;
            overflow-y: scroll;
        }

        .default_images img {
            object-fit: cover;
            border: none;
            max-width: 100px;
            max-height: 50px;
            border: solid 2px #dfdfdf;
            margin: auto;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .default_images button {
            display: block;
            width: 104px;
            height: 54px;
            float: left;
            position: relative;
            margin-right: 15px;
        }

        .default_images .active img {
            border: solid 2px #FF6C00;
        }
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content-header">
            <h1><?= $module_title ?>
                <small><?= $module_title_en ?></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?= $list_url ?>"><i class="fa"></i> <?= $module_title ?>列表</a></li>
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
                                    <label for="title" class="col-sm-2 control-label"><?= $module_title ?>标题</label>
                                    <div class="col-sm-8 bvalidator-bs3form-msg">
                                        <input class="form-control" name="title"
                                               placeholder="必填"
                                               value="<?= $title ?>"
                                               type="text" required maxlength="128"/>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="user_id" class="col-sm-2 control-label"><?= $module_title ?>主</label>
                                    <div class="col-sm-8 bvalidator-bs3form-msg">
                                        <select class="form-control" name="user_id"
                                                required min="1" data-bvalidator-msg="请选择一项!">
                                            <option value="0">请选择</option>
                                            <?php foreach ($users as $u_id => $u_name): ?>
                                                <option value="<?= $u_id ?>"
                                                    <?php if ($u_id == $user_id): ?>
                                                        selected
                                                    <?php endif; ?>
                                                ><?= $u_name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">选择图片</label>
                                    <div class="col-sm-8 default_images">
                                        <?php foreach ($default_images as $d_img): ?>
                                            <button
                                                <?php if ($d_img == $icon): ?>
                                                    class="active"
                                                <?php endif; ?>
                                                data-pid="<?= $d_img ?>"><img src="<?= getImageUrl($d_img) ?>">
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="icon" class="col-sm-2 control-label">图片</label>
                                    <div class="col-sm-8 bvalidator-bs3form-msg">
                                        <div id="image-view">
                                            <?php if ($icon): ?>
                                                <a href="<?= getImageUrl($icon) ?>" target="_blank"><img
                                                        src="<?= getImageUrl($icon) ?>"></a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="input-group">
											<span class="btn btn-warning fileinput-button">
												<i class="glyphicon glyphicon-plus"></i>
												<span>上传图片</span>
												<input id="fileupload" type="file" name="icon" multiple
                                                       data-url="<?= IMG_UPLOAD ?>"/>
											</span>
                                            <span style="margin-left: 10px;">尺寸750*360</span>
                                        </div>
                                        <input type="text" class="hide-input" id="image-id" name="icon"
                                               value="<?= $icon ?>"
                                               required data-bvalidator-msg="请上传图片!"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="descrip" class="col-sm-2 control-label">介绍</label>
                                    <div class="col-sm-8 bvalidator-bs3form-msg">
                                            <textarea class="form-control" name="descrip"
                                                      style="height: 140px"
                                                      maxlength="3000"><?= $descrip ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="visible" class="col-sm-2 control-label">上下线</label>
                                    <div class="col-sm-8">
                                        <input class="release" type="checkbox" name="visible"
                                            <?php if ($visible == 1): ?>
                                                checked
                                            <?php endif; ?>
                                        >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="communities" class="col-sm-2 control-label">分类</label>
                                    <div class="col-sm-8 bvalidator-bs3form-msg">
                                        <?php foreach ($communities_selected as $c_selected_id): ?>
                                            <select class="form-control communities" name="communities[]">
                                                <option value="0">请选择</option>
                                                <?php foreach ($communities as $c_id => $c_name): ?>
                                                    <option value="<?= $c_id ?>"
                                                        <?php if ($c_id == $c_selected_id): ?>
                                                            selected
                                                        <?php endif; ?>
                                                    ><?= $c_name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endforeach; ?>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="">标签</label>
                                    <div class="col-sm-8">
                                        <?php $this->load->view('common/tagSelect',array('selected' => $tags)); ?>
                                    </div>
                                </div>

                                <div class="form-group text-center">
                                    <?php if ($id): ?>
                                        <input type="hidden" name="id" value="<?= $id ?>"/>
                                    <?php endif; ?>
                                    <button class="btn btn-success"><?= $submit_btn ?></button>
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

    //验证
    $('#form').bValidator({validateOn: 'blur'});

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
            addImageView(data.result.data.pid, id1, id2);
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

    //图预览
    function addImageView(pid, id1, id2) {
        var fullImgUrl = imgUrl + pid;
        $(id1).html('<a href="' + fullImgUrl + '" target="_blank"><img src="' + fullImgUrl + '">');
        var imageIdJQ = $(id2);
        imageIdJQ.val(pid);
        imageIdJQ.parent().find('.help-block').remove();
        imageIdJQ.parents('.form-group').removeClass('has-error');
    }

    $('.default_images').on('click', 'button', function (e) {
        e.preventDefault();
        var btnImgJQ = $(this);
        addImageView(btnImgJQ.data('pid'), '#image-view', '#image-id');
        $('.default_images button').removeClass('active');
        btnImgJQ.addClass('active');
    });

</script>
</body>
</html>
