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
    <link rel="stylesheet" href="/static/fileupload/css/fileupload.css">
    <link rel="stylesheet" href="/static/fileupload/css/fileupload-ui.css">
    <link rel="stylesheet" href="/static/plugins/select2/select2.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        #image-view img {
            margin: 10px 0;
            max-height: 200px;
        }

        .table > tbody > tr > td {
            vertical-align: middle;
        }

        .hide-input {
            position: absolute;
            height: 0;
            width: 0;
            pointer-events: none;
            visibility: hidden;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #000;
        }

        .select2-container {
            display: block;
        }

        .label {
            display: inline-block;
        }
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content-header">
            <h1>推送管理
                <small>添加推送</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">推送列表</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">系统消息管理>>添加消息</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" method="post" id="form" role="form">
                                <div class="form-group">
                                    <label for="user_ids" class="control-label col-md-2">发送用户</label>
                                    <div class="col-md-8">
                                        <select id="tag-search" class="form-control" multiple="multiple"></select>

                                        <div id="selected-box">
                                            <?php if (isset($sid) && $info['users']): ?>

                                                <?php foreach ($info['users'] as $uid => $uname): ?>
                                                    <span style="margin-right: 5px;"
                                                          class="label label-success label-tag"
                                                          data-tid="<?= $uid ?>">
                                                        <?= $uname ?>
                                                        <i class="fa fa-close remove-tag"
                                                           style="cursor: pointer;"></i>
                                                    </span>
                                                <?php endforeach; ?>

                                            <?php else: ?>

                                                <span style="margin-right: 5px;"
                                                      class="label label-success label-tag"
                                                      data-tid="all">
                                                    全部用户
                                                </span>

                                            <?php endif; ?>
                                        </div>

                                        <input name="user_ids" id="tags"
                                               type="hidden"
                                            <?php if (isset($sid)): ?>
                                                value="<?= $info['user_ids'] ?>"
                                            <?php else: ?>
                                                value="all"
                                            <?php endif; ?>
                                        />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="content" class="control-label col-md-2"><strong
                                            class="text-danger">*</strong>消息内容</label>
                                    <div class="col-md-8 bvalidator-bs3form-msg">
                                        <textarea placeholder="请输入摘要"
                                                  required
                                                  id="content" name="content" rows="3"
                                                  class="form-control"><?php if (!empty($sid) && !empty($info['content'])): ?><?= $info['content'] ?><?php endif; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="image" class="col-sm-2 control-label">封面</label>
                                    <div class="col-sm-8">

                                        <div class="input-group">
											<span class="btn btn-warning fileinput-button">
												<i class="glyphicon glyphicon-plus"></i>
												<span>上传图片</span>
												<input id="fileupload" type="file" name="image" multiple
                                                       data-url="<?= IMG_UPLOAD ?>"/>
											</span>
                                        </div>

                                        <div id="image-view">
                                            <?php if (!empty($sid) && !empty($info['image'])): ?>
                                                <img src="<?= getImageUrl($info['image']) ?>" style="width:150px;"/>
                                            <?php endif; ?>
                                        </div>

                                        <input type="text" class="hide-input" id="image-id" name="cover"
                                            <?php if (!empty($sid) && !empty($info['image'])): ?>
                                                value="<?= $info['image'] ?>"
                                            <?php endif; ?>
                                        />
                                    </div>
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit" id="form_submit" class="btn btn-primary">提交</button>
                                    &nbsp;&nbsp;
                                    <button type="cancel" onclick="window.history.go(-1)" class="btn btn-default">取消
                                    </button>
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

<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>

<script src="/static/plugins/select2/select2.full.min.js"></script>
<script>
    //验证
    $('#form').bValidator({validateOn: 'blur'});

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

    //=======================
    //搜索用户和选择用户

    var tagsHidden = $('#tags');
    var _port = window.location.port ? ':' + window.location.port : '';

    var $selected = $('#selected-box');

    //-----------------
    //已选tag处理

    //已选tag id
    function resetTags() {
        var tags = $('.label-tag'), _t = [];
        $.each(tags, function (index) {
            _t.push($(this).data('tid'));
        });
        if (_t.length == 0) {
            tagsHidden.val('');
            addTag('all', '全部用户');
        } else {
            tagsHidden.val(_t.join(','));
        }
    }

    //选择tag
    function addTag(val, text) {

        if (val == 0) return false;

        if (tagsHidden.val().indexOf(val) > -1) return false;

        //如果选择的值不是all,但是已经选中all，则删除all
        if (val != 'all' && tagsHidden.val().indexOf('all') > -1) {
            $('.label-tag[data-tid="all"]').remove();
        }

        if (val == 'all') {
            $('#selected-box').append('<span style="margin-right: 5px;" class="label label-success label-tag" data-tid="' + val + '">' + text + '</span>');
        } else {
            $('#selected-box').append('<span style="margin-right: 5px;" class="label label-success label-tag" data-tid="' + val + '">' + text + ' <i class="fa fa-close remove-tag" style="cursor: pointer;"></i></span>');
        }

        resetTags();
    }

    function tagFun(e) {
        var data = e.params.data;
        if (data.id == 0) return false;
        if (tagsHidden.val().indexOf(data.id) > -1) {
            $('.label-tag[data-tid="' + data.id + '"]').remove();
            resetTags();
        } else {
            addTag(data.id, data.name);
        }
        $('.select2-selection__rendered li.select2-selection__choice').empty().hide();
    }

    //删除已选tag
    $selected.on('click', '.remove-tag', function () {
        $(this).parent().remove();
        resetTags();
    });

    var $eventSelect = $('#tag-search');

    var selectUrl = 'http://' + document.domain + _port + '/search/query/user/name';

    $eventSelect.select2({
        placeholder: '搜索用户',
        tag: false,
        ajax: {
            url: selectUrl,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                }
            },
            processResults: function (data, params) {
                var result = [];
                $.each(data, function (key, value) {
                    $.merge(result, value);
                });
                return {
                    results: result
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        minimumInputLength: 1,
        templateResult: function (data) {
            if (!data.loading) {
                var item = '';
                item += '<div>';
                item += '	<img class="avatar" src="' + data.avatar + '" alt="' + data.name + '">';
                item += '	<span>  名字:' + data.name + '</span>';
                item += '	<span>  ID:' + data.id + '</span>';
                item += '</div>';
                return item;
            } else {
                return '';
            }
        }
    });

    $eventSelect.on("select2:select", function (e) {
        tagFun(e);
    });
    $eventSelect.on("select2:unselect", function (e) {
        tagFun(e);
    });

</script>
</body>
</html>
