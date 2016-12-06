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
    <link rel="stylesheet" href="/static/dist/css/bootstrap-switch.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
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
            <h1>新闻管理
                <small>News</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">新闻列表</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="user-list">
                    <div class="box">
                        <div class="box-body">
                            <div class="col-md-3">
                                <form id="search-form" method="get">
                                    <div class="input-group">
                                        <input id="keyword" value="<?= $keyword ?>" class="form-control"
                                               placeholder="请输入关键词或新闻ID" type="text" name="keyword"/>
                                        <span class="input-group-btn"><button class="btn bg-purple btn-flat"
                                                                              type="submit"><i class="fa fa-search"></i> 搜索</button></span>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <?php if ($type == 'search'): ?>
                                    <a class="btn btn-flat btn-default" role="button"
                                       href="<?= base_url('/news/index') ?>"><i class="fa fa-reply"></i> 结束搜索</a>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-7 text-right">
                                <a class="btn btn-success btn-flat" role="button"
                                   href="<?= base_url('/news/edit/add') ?>"><i class="fa fa-plus"></i> 添加新闻</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="user-list">
                    <div class="box">
                        <div class="box-header with-border">
                            <?php if ($type == 'news'): ?>
                                <h3 class="box-title">新闻列表</h3>
                            <?php elseif (is_numeric($keyword)): ?>
                                <h3 class="box-title">共为您找到 <strong class="text-info"><?= $total ?></strong> 条,ID为
                                    <strong class="text-danger"><?= $keyword ?></strong> 的新闻</h3>
                            <?php else: ?>
                                <h3 class="box-title">共为您找到 <strong class="text-info"><?= $total ?></strong> 条,标题包含
                                    <strong class="text-danger"><?= $keyword ?></strong> 的新闻</h3>
                            <?php endif; ?>
                        </div>
                        <div class="box-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
<!--                                    <th style="min-width: 40px;">#</th>-->
                                    <th style="min-width: 86px;">新闻ID</th>
                                    <th style="width: 50%;">标题</th>
                                    <th style="min-width: 100px;">封面</th>
                                    <th style="min-width: 100px;">大图</th>
                                    <th style="width: 50%;">关联比赛</th>
                                    <th style="min-width: 100px;">来源</th>
                                    <th style="min-width: 86px;">下线</th>
                                    <th style="min-width: 86px;">发布时间</th>
                                    <th style="min-width: 150px;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($total > 0): ?>
                                    <?php foreach ($allNews as $key => $news): ?>
                                        <tr>
<!--                                            <td><input type="checkbox" value="--><?//= $news['id'] ?><!--" class="more-box"/></td>-->
                                            <td><?= $news['id'] ?></td>
                                            <td><?= $news['title'] ?></td>
                                            <td><img src="<?= getImageUrl($news['image']) ?>" style="width: 100px;"
                                                     alt=""/></td>
                                            <td>
                                                <?php if (!empty($news['large_image'])): ?>
                                                    <img src="<?= getImageUrl($news['large_image']) ?>"
                                                         style="width: 100px;" alt=""/>
                                                <?php else: ?>
                                                    <span>暂无图片</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($news['match_info'])): ?>
                                                    <?= $news['match_info'] ?>
                                                <?php else: ?>
                                                    无
                                                <?php endif; ?>
                                            </td>
                                            <td><?= !empty($news['site_name']) ? $news['site_name'] : $news['site'] ?></td>
                                            <td>
                                                <input data-pk="<?= $news['id'] ?>" name="visible"
                                                       class="release" type="checkbox"
                                                    <?php if ($news['visible']): ?>
                                                        checked
                                                    <?php endif; ?>
                                                >
                                            </td>
                                            <td><?= $news['publish_tm'] ?></td>
                                            <td>
                                                <!--
                                                <a class="btn btn-flat btn-info btn-xs" role="button" href="#"><i class="fa fa-eye"></i> 预览</a>
                                                -->
                                                <a class="btn btn-flat btn-primary btn-xs" role="button"
                                                   href="<?= base_url('/news/related/' . $news['id']) ?>"><i
                                                        class="fa fa-link"></i> 相关推荐</a>
                                                <a class="btn btn-flat btn-warning btn-xs" role="button"
                                                   href="<?= base_url('/news/edit/update/' . $news['id'] . '?redirect=' . current_url()) ?>"><i
                                                        class="fa fa-edit"></i> 编辑</a>
                                                <!-- 
                                                <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-nid="<?= $news['id'] ?>"><i class="fa fa-remove"></i> 删除</a>
                                                -->
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center">无</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box box-solid">
                        <div class="box-body">
                            <div class="row">
                                <!--
                                <div class="col-md-2">
                                    <table>
                                        <tr>
                                            <td width="30" align="center"><input id="select-all" type="checkbox" name="" /></td>
                                            <td>
                                                <input id="batch-id" type="hidden" value="" />
                                                <button id="batch-remove" class="btn btn-flat btn-danger btn-xs">批量删除</button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                -->
                                <div class="col-md-10 text-right">
                                    <table width="100%">
                                        <tr>
                                            <td><?= $page ?></td>
                                            <td width="100" align="right">共 <strong
                                                    class="text-info"><?= $total ?></strong> 条
                                            </td>
                                        </tr>
                                    </table>
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
<!-- AdminLTE App -->
<script src="/static/dist/js/app.min.js"></script>

<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script>
    $('.item-select-edit-enable').editable({
        url: '<?=base_url("/news/updateField")?>',
        prepend: "请选择",
        source: [
            {value: 1, text: '否'},
            {value: 0, text: '是'}
        ]
    });
    $("input[name='visible']").bootstrapSwitch({
        size: 'mini',
        onText: '上线',
        offText: '下线',
        onSwitchChange: function (event, state) {
            $.ajax({
                method: "POST",
                url: '<?=base_url("/news/updateField")?>',
                data: {name: "visible", value: parseInt(Number(state)), pk: $(this).data('pk')},
                success: function () {
                    window.location.reload();
                }
            });
        }
    });
    var alertConfig = {
        title: "你确定要删除吗?",
        text: "删除后不可恢复，请谨慎操作!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dd4b39",
        confirmButtonText: "确定删除",
        cancelButtonText: "取消",
        closeOnConfirm: false
    };
    $('#batch-remove,.btn-remove').on('click', function () {
        var newsId = $(this).data('nid');
        var tr = $(this).parents('tr');
        var target = this;
        if (this.id == 'batch-remove') {
            newsId = $('#batch-id').val();
            if (newsId == '') {
                swal("操作失败!", "请先选择要删除的新闻！", "error");
                return false;
            }
        }

        swal(alertConfig, function () {
            $.post('<?=base_url("/news/remove")?>', {id: newsId}, function (d) {
                if (d == 'success') {
                    swal({title: "删除成功!", text: "新闻已被删除！", type: "success"}, function () {
                        if (target.id == 'batch-remove') window.location.reload();
                        tr.fadeOut('fast', function () {
                            $(this).remove();
                        });
                    });
                } else {
                    swal("删除失败!", "新闻删除失败，请重试！", "error");
                }
            });
        });
    });

    function setBatchId() {
        var allBox = $('.more-box:checked'), idBox = $('#batch-id'), ids = [];
        allBox.each(function () {
            ids.push($(this).val());
        });
        idBox.val(ids.join(','));
    }

    $('#select-all,.more-box').on('change', function () {
        if (this.id == 'select-all') {
            if ($(this).prop('checked')) {
                $('.more-box').prop('checked', 'checked');
            } else {
                $('.more-box').removeAttr('checked');
            }
        }
        setBatchId();
    });

</script>
</body>
</html>
