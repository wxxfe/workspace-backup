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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .skin-purple .treeview-menu>li.active>a, .skin-purple .treeview-menu>li>a:hover{
            color: #f39c12;
        }
    </style>
</head>

<body class="hold-transition skin-purple sidebar-mini">
    <div class="main-sidebar" style="padding-top: 0; transform: none; -webkit-transform: none;">

        <!-- Inner sidebar -->
        <div class="sidebar">

            <!-- Search Form (Optional) -->
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                        <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
            <!-- /.sidebar-form -->

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <li class="header">CHILDREN NAVIGATION</li>

                <!-- Optionally, you can add icons to the links -->
                <?php
                function iterator_menu($menu) {
                    foreach ($menu as $row) {
                        $has_children = (isset($row['children'])? true : false);
                        
                        $i1_icon = trim($row['icon']);
                        $i2_icon = '';
                        if (empty($i1_icon)) {
                            $i1_icon = 'fa fa-circle-o';
                        }
                        if ($has_children) {
                            $i2_icon = 'fa fa-angle-left pull-right';
                        }
                        
                        echo '<li class="treeview">';
                        if ($has_children) {
                            echo "<a href='javascript:void(0)'><i class='{$i1_icon}'></i> <span>{$row['name']}</span><i class='{$i2_icon}'></i></a>";
                        }else{
                            echo "<a href='" . base_url($row['route']) . "' target='rightframe'><i class='{$i1_icon}'></i> <span>{$row['name']}</span><i class='{$i2_icon}'></i></a>";
                        }
                        if ($has_children) {
                            echo '<ul class="treeview-menu" style="display: none;">';
                            iterator_menu($row['children']);
                            echo '</ul>';
                        }
                        echo '</li>';
                    }
                }
                iterator_menu($menu);
                ?>
            </ul><!-- /.sidebar-menu -->

        </div><!-- /.sidebar -->

    </div><!-- /.main-sidebar -->

    <!-- jQuery 2.1.4 -->
    <script src="/static/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="/static/bootstrap/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/static/dist/js/app.min.js"></script>
    <script>
        $('.treeview').on('click',function(event){
            var hasParentUl = $(this).parent('.treeview-menu').length > 0;
            var hasChildrenUl = $(this).find('ul.treeview-menu').length > 0;
            if(!hasChildrenUl && !hasParentUl){
                $('.treeview-menu').hide();
                $('.treeview').removeClass('active');
                $(this).addClass('active');
            }else if(!hasChildrenUl && hasParentUl){
                if(hasParentUl) event.stopPropagation();
                $('.treeview-menu .treeview').removeClass('active');
                $(this).addClass('active');
            }
        });
    </script>
</body>
</html>
