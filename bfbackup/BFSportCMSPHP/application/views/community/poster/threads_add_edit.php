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

        .image-view-style img {
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

        th, td {
            vertical-align: middle !important;
            text-align: center;
            word-warp: break-word;
            word-break: break-all;
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
                                        <div id="image-view" class="image-view-style">
                                            <?php if ($image_small): ?>
                                                <a href="<?= getImageUrl($image_small) ?>" target="_blank"><img
                                                        src="<?= getImageUrl($image_small) ?>"></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="image_large" class="col-sm-3 control-label">专题图</label>
                                    <div class="col-sm-7 bvalidator-bs3form-msg">
                                        <div class="input-group">
											<span class="btn btn-warning fileinput-button">
												<i class="glyphicon glyphicon-plus"></i>
												<span>上传图片</span>
												<input id="fileupload-1" type="file" name="image_large" multiple
                                                       data-url="<?= IMG_UPLOAD ?>"/>
											</span>
                                            <span style="margin-left: 10px;">尺寸750*高度不限</span>
                                        </div>
                                        <input type="text" class="hide-input" id="image-1-id" name="image_large"
                                               value="<?= $image_large ?>"
                                               required data-bvalidator-msg="请上传图片!"/>
                                        <div id="image-1-view" class="image-view-style">
                                            <?php if ($image_large): ?>
                                                <a href="<?= getImageUrl($image_large) ?>" target="_blank"><img
                                                        src="<?= getImageUrl($image_large) ?>"></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">专题标题</label>
                                    <div class="col-sm-7 bvalidator-bs3form-msg">
                                        <input class="form-control" name="name" value="<?= $name ?>"
                                               type="text" required maxlength="128"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="content" class="col-sm-3 control-label">专题介绍</label>
                                    <div class="col-sm-7 bvalidator-bs3form-msg">
                                            <textarea class="form-control" name="content"
                                                      style="height: 140px"
                                                      required maxlength="3000"><?= $content ?></textarea>
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

                                <div class="form-group">

                                    <label for="threads_add" class="col-sm-3 control-label">添加话题</label>
                                    <div class="col-sm-7 bvalidator-bs3form-msg">
                                        <div class="clearfix">
                                            <input class="form-control pull-left" style="width: calc(100% - 80px);"
                                                   name="threads_add"
                                                   value="" placeholder="输入话题ID，可用,号分隔输入多个话题ID，比如1,2,3"
                                                   type="text" data-bvalidator="ajax"
                                                   data-bvalidator-msg="请输入有效话题ID！"/>
                                            <button onclick="return false;" class="btn bg-purple btn-flat pull-right"
                                                    style="width: 60px;">确认
                                            </button>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">

                                    <label for="threads_list" class="col-sm-3 control-label">包含话题列表</label>
                                    <div class="col-sm-7">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th style="min-width: 86px">话题ID</th>
                                                <th style="width: 100%">标题</th>
                                                <th style="min-width: 86px">排序</th>
                                                <th style="min-width: 86px">操作</th>
                                            </tr>
                                            </thead>
                                            <tbody id="threads_list">
                                            <?php foreach ($threads as $index => $item): ?>
                                                <tr>
                                                    <td><?= $item['id'] ?></td>
                                                    <td><?= $item['title'] ?></td>
                                                    <td>
                                                        <a class="editable"
                                                           data-validation="number"
                                                           data-type="text"
                                                        ><?= $item['display_order'] ?></a>
                                                    </td>
                                                    <td>
                                                        <a data-index="<?= $index ?>" href=""
                                                           class="btn btn-xs btn-danger btn-del">
                                                            <i class="fa fa-times"></i>删除
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
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

    //包含话题列表数组，编辑页面可能会有初始数据
    var threads = $.parseJSON('<?= addslashes(json_encode($threads)) ?>');

    var threads_add = $("input[name='threads_add']");
    //验证配置
    bValidator.defaultOptions.ajaxResponseMsg = true;
    bValidator.defaultOptions.ajaxCache = false;
    bValidator.defaultOptions.ajaxUrl = "<?= $relate_threads_url ?>";
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
                var resultsData = results['data'];
                var len = resultsData.length;
                var itemData;
                for (var i = 0; i < len; i++) {
                    itemData = resultsData[i];
                    threads.push(
                        {
                            id: itemData['id'],
                            title: itemData['title'],
                            display_order: 0
                        }
                    );
                }
                creatList();
                threads_add.val('');
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
        if (v !== true) {
            return false;
        }

    });

    //添加表单验证，blur表示input失去焦点就验证
    formJQ.bValidator({validateOn: 'blur'});

    //添加ajax验证完成的回调事件
    formJQ.on('afterAjax.bvalidator', function (event) {
        if (event.bValidator.instance.isValid() && addClick) {
            formJQ.submit();
        }
        addClick = false;
    });

    //列表容器
    var listBox = $('#threads_list');
    var threads_v = $("input[name='threads']");
    //生成列表,并且插入显示位置
    //同时生成提交表单里的threads数据，也就是把threads数组转换成字符串形式，结构为id|display_order,id|display_order,...
    function creatList() {
        var threadsString = '';
        var tmpl = '';
        var len = threads.length;
        var jData;
        for (var j = 0; j < len; j++) {
            jData = threads[j];
            tmpl +=
                '<tr>'
                + '<td>' + jData.id + '</td>'
                + '<td>' + jData.title + '</td>'
                + '<td>'
                + '<a class="editable" '
                + 'data-index="' + j + '"'
                + 'data-validation="number"'
                + 'data-type="text"'
                + '>' + jData.display_order + '</a>'
                + '</td>'
                + '<td>'
                + '<a data-index="' + j + '" href=""'
                + 'class="btn btn-xs btn-danger btn-del">'
                + '<i class="fa fa-times"></i>删除'
                + '</a>'
                + '</td>'
                + '</tr>';

            if (j)threadsString += ',';
            threadsString += (jData.id + '|' + jData.display_order);
        }
        listBox.html(tmpl);
        initThreads();
        threads_v.val(threadsString);
    }

    //初始化话题JS交互
    function initThreads() {
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
            },
            success: function (response, newValue) {
                //改变排序，重新生成列表
                var tJQ = $(this);
                threads[$(this).data('index')]['display_order'] = newValue;
                threads.sort(compareNumbers);
                creatList();
            }
        });
        //删除操作
        $('.btn-del').on('click', function (e) {
            e.preventDefault();
            threads.splice($(this).data('index'), 1);
            creatList();
        });
    }

    initThreads();

    //排序
    function compareNumbers(a, b) {
        return a.display_order - b.display_order;
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

    $('#fileupload-1').fileupload({
        add: fileuploadAdd,
        submit: fileuploadSubmit,
        dataType: 'json',
        autoUpload: false,
        done: function (e, data) {
            fileuploadDone(data, '#image-1-view', '#image-1-id');
        }
    });
</script>
</body>
</html>
