<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=HEAD_TITLE ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
</head>
<frameset rows="50,*" border="0" framespacing="0">
    <frame src="<?php echo $headerUrl;?>" name="top" scrolling="auto" noresize>
    <frameset cols="230,*" frameborder="0" border="0" framespacing="0">
        <frame src="<?php echo $asideUrl;?>" name="leftframe" scrolling="auto" noresize />
        <frame src="<?php echo $dashboardUrl;?>" name="rightframe" scrolling="auto" noresize />
    </frameset>
</frameset>
<noframes>
    <body>
    </body>
</noframes>
</html>
