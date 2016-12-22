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
    <link rel="stylesheet" href="/static/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
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

        th, td {
            vertical-align: middle !important;
            text-align: center;
            word-warp: break-word;
            word-break: break-all;
        }

        .priority {
            margin-left: 10px;
        }

        .fileuploadBox {
            overflow: hidden;
            width: 50px;
            height: 22px;
            position: relative;
        }

        .fileuploadS {
            width: 100%;
            height: 100%;
            opacity: .01;
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            cursor: pointer;
            z-index: 1;
        }

        td .btn {
            margin-right: 4px;
        }

        .editable-tip {
            float: left;
            margin-right: 10px;
        }

        .hide-input {
            position: absolute;
            height: 0;
            width: 0;
            pointer-events: none;
            visibility: hidden;
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gallery_title" class="col-sm-3 control-label">
                                            <?= $module_title ?>标题</label>
                                        <div class="col-sm-7 bvalidator-bs3form-msg">
                                            <input class="form-control" name="gallery_title"
                                                   placeholder="必填"
                                                   value="<?php echo htmlspecialchars($title) ?>"
                                                   type="text" required maxlength="128"/>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="gallery_origin" class="col-sm-3 control-label">
                                            <?= $module_title ?>来源</label>
                                        <div class="col-sm-7 bvalidator-bs3form-msg">
                                            <select class="form-control" name="gallery_origin">
                                                <?php foreach ($sites as $s_item): ?>
                                                    <option value="<?= $s_item['origin'] ?>"
                                                        <?php if ($s_item['origin'] == $origin): ?>
                                                            selected
                                                        <?php endif; ?>
                                                    ><?= $s_item['origin'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image" class="col-sm-3 control-label">
                                            <?= $module_title ?>封面</label>
                                        <div class="col-sm-7 bvalidator-bs3form-msg">

                                            <div id="image-view">
                                                <?php if ($image): ?>
                                                    <a href="<?= getImageUrl($image) ?>" target="_blank"><img
                                                            src="<?= getImageUrl($image) ?>"></a>
                                                <?php endif; ?>
                                            </div>

                                            <div class="input-group">
											<span class="btn btn-warning fileinput-button">
												<i class="glyphicon glyphicon-plus"></i>
												<span>上传图片</span>
												<input id="gallery_fileupload" type="file" name="files[]" multiple
                                                       data-url="<?= IMG_UPLOAD ?>"/>
											</span>
                                            </div>

                                            <input type="text" class="hide-input" id="image-id" name="image"
                                                   value="<?= $image ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="publish_tm" class="control-label col-sm-3">发布时间</label>
                                        <div class="col-sm-7">
                                            <div id="datetimepicker" class="input-append date">
                                                <input value="<?= $publish_tm ?>" data-format="yyyy-MM-dd hh:mm:ss"
                                                       class="form-control" style="display: inline-block; width: 146px;"
                                                       name="publish_tm" placeholder="" type="text">
                                                <span class="add-on" style="display: inline-block; margin-left: 5px;">
                                                    <i data-time-icon="fa fa-clock-o"
                                                       data-date-icon="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="match_id" class="col-sm-3 control-label">关联比赛</label>
                                        <div class="col-sm-7">
                                            <?php $this->load->view('common/linkMatch', array('current' => $match_id)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="gallery_brief" class="col-sm-3 control-label">摘要</label>
                                        <div class="col-sm-7 bvalidator-bs3form-msg">
                                            <textarea class="form-control" name="gallery_brief"
                                                      placeholder="必填" style="height: 140px"
                                                      required maxlength="256"><?= $brief ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="gallery_visible" class="col-sm-3 control-label">上下线</label>
                                        <div class="col-sm-7">
                                            <input class="release" type="checkbox" name="gallery_visible"
                                                <?php if ($visible == 1): ?>
                                                    checked
                                                <?php endif; ?>
                                            >
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="tags" class="col-sm-2 control-label">标签</label>
                                        <div class="col-sm-10">
                                            <?php $this->load->view('common/tagSelect', array('selected' => $tags)); ?>
                                        </div>
                                    </div>

                                </div>

                            </form>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box">
                                        <div class="box-header with-border">
                                            <h3 class="box-title" style="margin-bottom: 10px">包含图片</h3>
                                            <div class="box-tools text-warning pull-right">
                                                <div class="editable-tip">
                                                        <span style="color: #f90; line-height: 30px;"><i
                                                                class="fa fa-info"></i> 提示：</span>
                                                    <span>下划虚线项目可点击编辑</span>
                                                </div>
                                                <div class="input-group">
											<span class="btn btn-warning fileinput-button">
												<i class="glyphicon glyphicon-plus"></i>
												<span>上传图片</span>
												<input required id="fileupload" type="file" name="files[]" multiple
                                                       data-url="<?= IMG_UPLOAD ?>"/>
											</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th style="min-width: 86px;">图片ID</th>
                                                    <th style="width: 35%;">标题 <button class="sync-img-title btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i>批量同步标题</button></th>
                                                    <th style="width: 45%;">图说 <button class="sync-img-brief btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i>批量同步图说</button></th>
                                                    <th style="width: 100px;">预览</th>
                                                    <th style="min-width: 250px;">操作(编辑数字排序)</th>
                                                </tr>
                                                </thead>
                                                <tbody id="img_list">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <button id="add-btn" class="btn btn-success"><?= $submit_btn ?></button>
                                    <a id="cancel-btn" style="margin-left: 20px" href=""
                                       class="btn btn-warning">取消</a>
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
<script src="/static/dist/js/jstree.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script>

    //---------------------------------------------------------
    //图集管理

    //图集id
    var gallery_id = 0;

    //当前页面状态
    var state = 'add';

    //保存图集方法异步回调完成
    var saveGalleryEnd = true;
    //保存图集方法
    function saveGallery(go) {
        if (saveGalleryEnd) {
            saveGalleryEnd = false;
            var url = "<?= $add_url ?>";
            //如果有图集ID,则url为编辑api
            if (gallery_id) {
                url = "<?= $edit_url ?>";
                url += gallery_id;
            }
            var visible = parseInt(Number($('input[name="gallery_visible"]').prop("checked")));
            $.ajax({
                method: "POST",
                url: url,
                data: {
                    title: $.trim($('input[name="gallery_title"]').val()),
                    brief: $.trim($('textarea[name="gallery_brief"]').val()),
                    image: $.trim($('#image-id').val()),
                    visible: visible,
                    tags: $.trim($('input[name="tags"]').val()),
                    match_id: $.trim($('input[name="match_id"]').val()),
                    origin: $.trim($('select[name="gallery_origin"]').val()),
                    publish_tm: $.trim($('input[name="publish_tm"]').val())
                }
            }).done(function (data) {
                gallery_id = parseInt(Number(data));
                //如果go为真,即点击提交按钮,则跳回列表页
                if (go) {
                    window.location.href = '<?= $redirect ?>';
                }
            }).always(function () {
                saveGalleryEnd = true;
            });
        }

    }

    //图集表单
    var formJQ = $('#form');

    //验证插件
    formJQ.bValidator({validateOn: 'blur'});

    var formBvalidator = formJQ.data("bValidators").bvalidator;

    //如果有图集id则是编辑状态
    //否则在添加状态进行表单输入完成后特殊行为,如果还没有图集ID,并且验证通过则添加图集
    <?php if (isset($id)): ?>

    gallery_id = Number(<?= $id ?>);
    state = 'edit';

    <?php else: ?>

    formJQ.on('afterFieldValidation.bvalidator', function (event) {
        if (event.bValidator.instance.isValid() && gallery_id == 0) saveGallery();
    });

    <?php endif; ?>

    //图集发布时间控件
    $('#datetimepicker').datetimepicker();

    //添加保存按钮
    $('#add-btn').on('click', function (e) {
        e.preventDefault();
        if (formBvalidator.validate()) {
            //如果图片少于3张则提示
            //否则提交数据
            if (listBox.find('tr').length < 3) {
                swal(
                    {
                        title: "包含图片不能少于3张!",
                        type: "warning",
                        text: "1秒后自动关闭",
                        timer: 1000,
                        allowOutsideClick: true,
                        animation: false
                    }
                );
            } else {
                //如果封面图还没有，并且上传图片有数据，则用第一张图作为默认封面.
                if (galleryCoverNot) {
                    galleryCoverView(images[0].image, '#image-view', '#image-id');
                }
                saveGallery(true);
            }
        }
    });

    $('#cancel-btn').on('click', function (e) {
        e.preventDefault();
        //如果有图集ID,并且为添加状态,则取消按钮执行删除行为
        if (gallery_id && state == 'add') {
            $.ajax({
                method: "POST",
                url: "<?= $del_url ?>" + gallery_id
            }).done(function (data) {
                window.location.href = '<?= $redirect ?>';
            });
        } else {
            window.location.href = '<?= $redirect ?>';
        }
    });

    //切换开关
    $("input[name='gallery_visible']").bootstrapSwitch({
        size: 'mini',
        onText: '上线',
        offText: '下线'
    });

    //上传图片
    var imgUrl = '<?= IMG_DOMAIN ?>';
    // add 检查上传文件类型和大小
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

    //封面图片上传完成
    function fileuploadDone(data, id1, id2) {
        var result = data.result.errno;
        if (result !== 10000) {
            alert('上传失败,请重试！');
        } else {
            galleryCoverView(data.result.data.pid, id1, id2);
        }
    }
    //封面图片上传
    $('#gallery_fileupload').fileupload({
        add: fileuploadAdd,
        submit: fileuploadSubmit,
        dataType: 'json',
        autoUpload: false,
        done: function (e, data) {
            fileuploadDone(data, '#image-view', '#image-id');
        },
        fail: function (e, data) {
            alert(data.files[0].name + '上传失败,请重试！');
        }
    });

    //没有封面
    var galleryCoverNot = true;
    <?php if ($image): ?>
    galleryCoverNot = false;
    <?php endif; ?>

    //封面图预览
    function galleryCoverView(pid, id1, id2) {
        galleryCoverNot = false;
        var fullImgUrl = imgUrl + pid;
        $(id1).html('<a href="' + fullImgUrl + '" target="_blank"><img src="' + fullImgUrl + '">');
        var imageIdJQ = $(id2);
        imageIdJQ.val(pid);
        imageIdJQ.parent().find('.help-block').remove();
        imageIdJQ.parents('.form-group').removeClass('has-error');
    }

    //---------------------------------------------------------
    //图片管理

    //图片列表容器
    var listBox = $('#img_list');

    var imgUrlPrefix = "<?= IMG_DOMAIN ?>";

    //图片数据，编辑页面可能会有初始数据
    /*[
     {
     id:'',
     gallery_id:'',
     title:'',
     image:'',
     brief:'',
     priority:''
     }
     ]
     */
    <?php if (empty($img_list)):?>
    var images = $.parseJSON("<?php echo json_encode($img_list)?>");
    <?php else:?>
    var images = $.parseJSON("<?php echo $img_list_json ?>");
    <?php endif;?>

    imagesList();

    //上传成功的图片数据，等待关联图集
    var imagesReady = [];

    var fileInputJQ = $('#fileupload');

    //点击上传图片按钮,如果图集表单的必填项目已填 -- (如果还没有图集ID,则添加图集)
    //否则提示填数据
    fileInputJQ.on('click', function () {
        if (formBvalidator.validate()) {
            if (gallery_id == 0) saveGallery();
        } else {
            return false;
        }

    });

    /*
     上传图片
     done 单个图片上传成功把图片数据加入准备发送的数组
     stop 全部上传成功后,把所有图片数据加入图集图片数据库,然后返回所有数据生成显示列表
     */
    fileInputJQ.fileupload({
        add: fileuploadAdd,
        submit: fileuploadSubmit,
        dataType: 'json',
        autoUpload: false,
        done: function (e, data) {
            imagesReady.push(
                {
                    gallery_id: gallery_id,
                    image: data.result.data.pid,
                    /*
                    title: String(data.files[0].name).replace(/\.(gif|jpe?g|png)$/ig, ''),
                    */
                    title: '',
                    brief: ''
                }
            );
        },
        fail: function (e, data) {
            alert(data.files[0].name + '上传失败,请重试！');
        },
        stop: function (e) {
            if (gallery_id == 0) {
                setTimeout(sendImages, 1000);
            } else {
                sendImages();
            }
            function sendImages() {
                if (imagesReady.length) {
                    $.ajax({
                        method: "POST",
                        url: "<?= $add_images_url ?>",
                        data: {images: imagesReady, gallery_id: gallery_id}
                    }).done(function (data) {
                        images = JSON.parse(data);
                        imagesList();
                    });
                    imagesReady = [];
                }
            }
        }
    });

    //生成图片列表,并且插入显示位置
    function imagesList() {
        var tmpl = '';
        var len = images.length;
        var img;

        for (var i = 0; i < len; i++) {

            img = images[i];

            tmpl +=
                '<tr>'
                + '<td>' + img.id + '</td>'
                + '<td>'
                + '<a class="editable"'
                + 'data-validation-maxlen="128"'
                + 'data-type="text"'
                + 'data-url="<?= $edit_image_url ?>"'
                + 'data-pk="' + img.id + '"'
                + 'data-name="title">' + $.trim(img.title) + '</a>'
                + '</td>'
                + '<td>'
                + '<a class="editable"'
                + 'data-validation-maxlen="512"'
                + 'data-type="textarea"'
                + 'data-url="<?= $edit_image_url ?>"'
                + 'data-pk="' + img.id + '"'
                + 'data-name="brief">' + $.trim(img.brief) + '</a>'
                + '</td>'
                + '<td>'
                + '<a class="preview-a" href="' + imgUrlPrefix + img.image + '" target="_blank">'
                + '<img class="preview-img" style="width: 100px;" src="' + imgUrlPrefix + img.image + '">'
                + '</a>'
                + '</td>'
                + '<td>'
                + '<button class="sync-data btn btn-info btn-xs"'
                + 'data-pk="' + img.id + '"'
                + '><i class="fa fa-pencil-square-o"></i>同步</button>'
                + '<span class="fileuploadBox btn btn-primary btn-xs">'
                + '<i class="fa fa-pencil-square-o"></i>换图'
                + '<input '
                + 'data-pk="' + img.id + '"'
                + 'class="fileuploadS" type="file"'
                + 'name="image" multiple data-url="<?= IMG_UPLOAD ?>"/></span>'
                + '<a <a href="<?= $del_image_url ?>' + img.id + '" class="btn btn-xs btn-danger btn-del"><i class="fa fa-times"></i>删除</a>'
                + '<a class="editable priority"'
                + 'data-validation="number"'
                + 'data-type="text"'
                + 'data-url="<?= $edit_image_url ?>"'
                + 'data-pk="' + img.id + '"'
                + 'data-name="priority">' + (i + 1) + '</a>'
                + '</td>'
                + '</tr>';
        }
        listBox.html(tmpl);
        initImages();
    }

    function initImages() {
        //就地编辑
        $('.editable').editable({
            emptytext: '点这编辑',
            validate: function (value) {
                //验证是否为空，是空则提示
                if (!bValidator.validators.required(value)) {
                    return bValidator.defaultOptions.messages.zh.required;
                }
                var tJQ = $(this);
                //验证是否为数字，不是则提示
                if (tJQ.data('validation') == 'number' && !bValidator.validators.number(value)) {
                    return bValidator.defaultOptions.messages.zh.number;
                }
                //验证是否超出最大字符数,超出则提示
                var maxlen = Number(tJQ.data('validation-maxlen'));
                if (maxlen && !bValidator.validators.maxlen(value, maxlen)) {
                    return bValidator.defaultOptions.messages.zh.maxlen.replace('{0}', maxlen);
                }
            },
            success: function (response, newValue) {
                //如果有返回数据，则是包含图片的数组，需要重新生成图片列表
                if (response && response != 'null') {
                    try {
                        images = JSON.parse(response);
                        imagesList();
                    }
                    catch (e) {

                    }
                }
            }
        });

        //替换图片
        $('.fileuploadS').fileupload({
            add: fileuploadAdd,
            submit: fileuploadSubmit,
            dataType: 'json',
            autoUpload: false,
            done: function (e, data) {
                var result = data.result.errno;
                if (result !== 10000) {
                    alert('替换失败,请重试！')
                } else {

                    var pid = data.result.data.pid;
                    var tJQ = $(this);

                    $.ajax({
                        method: "POST",
                        url: "<?= $edit_image_url ?>",
                        data: {name: "image", value: pid, pk: tJQ.data('pk')}
                    }).done(function (data) {
                        tJQ.parents('tr').find('.preview-a').prop('href', imgUrlPrefix + pid);
                        tJQ.parents('tr').find('.preview-img').prop('src', imgUrlPrefix + pid);
                        alert('替换成功!');
                    });
                }
            }
        });

        //删除图片
        $('.btn-del').on('click', function (e) {
            e.preventDefault();
            var url = $(this).prop('href');
            var trJQ = $(this).parents('tr');
            swal(
                {
                    title: "你确定要删除吗?",
                    text: "删除后不可恢复，请谨慎操作!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dd4b39",
                    confirmButtonText: "确定删除",
                    cancelButtonText: "取消",
                    closeOnConfirm: false
                },
                function () {
                    $.ajax({
                        method: "POST",
                        url: url
                    }).done(function (data) {
                        swal(
                            {
                                title: "删除成功!",
                                type: "success",
                                text: "1秒后自动关闭",
                                timer: 1000,
                                showConfirmButton: false,
                                allowOutsideClick: true,
                                animation: false
                            }
                        );
                        trJQ.remove();
                    });
                }
            );
        });

        // 批量同步图集标题
        $('.sync-img-title').on('click', function (e) {
            var title = $.trim($('input[name="gallery_title"]').val());
            if (title) {
                $.ajax({
                    method: "POST",
                    url: "<?php echo site_url('gallery/batchSync')?>",
                    data: {name: "title", value: title, gallery_id: gallery_id}
                }).done(function (data) {
                    $('#img_list').children("tr").each(function() {
                        $(this).find('a[data-name="title"]').editable('setValue', title);
                    })
                });
            }
        });
        // 批量同步图集图说
        $('.sync-img-brief').on('click', function (e) {
            var brief = $.trim($('textarea[name="gallery_brief"]').val());
            if (brief) {
                $.ajax({
                    method: "POST",
                    url: "<?php echo site_url('gallery/batchSync')?>",
                    data: {name: "brief", value: brief, gallery_id: gallery_id}
                }).done(function (data) {
                    $('#img_list').children("tr").each(function() {
                        $(this).find('a[data-name="brief"]').editable('setValue', brief);
                    })
                });
            }
        });
        
        //使用图集的标题和介绍作为图片的标题和介绍
        $('.sync-data').on('click', function (e) {
            e.preventDefault();
            var thisJQ = $(this);
            var trJQ = thisJQ.parents('tr');
            var title = $.trim($('input[name="gallery_title"]').val());
            var brief = $.trim($('textarea[name="gallery_brief"]').val());
            var pk = thisJQ.data('pk');
            if (title) {
                $.ajax({
                    method: "POST",
                    url: "<?= $edit_image_url ?>",
                    data: {name: "title", value: title, pk: pk}
                }).done(function (data) {
                    trJQ.find('a[data-name="title"]').editable('setValue', title);
                });
            }
            if (brief) {
                $.ajax({
                    method: "POST",
                    url: "<?= $edit_image_url ?>",
                    data: {name: "brief", value: brief, pk: pk}
                }).done(function (data) {
                    trJQ.find('a[data-name="brief"]').editable('setValue', brief);
                });
            }

        });
    }

</script>
</body>
</html>
