<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//云视频的相关配置
$config['vr'] = array(//vr视频账户
    'uid'=>'48853043',
    'accesskey'=>'79542R-n1OE2z210a2GycA-ctfNkIjK4Qigoniwr',
    'secretkey'=>'E2f42R-n16L3z21FH8-mdQRF14LcSkyFq0K46sFP',
);
$config['sports'] = array(//体育普通视频账户
    'uid'=>'48842737',
    'accesskey'=>'=aarVQ-n19U5y21Yh90n8DweKYyYAv1uJgNNhc98',
    'secretkey'=>'vxrVQ-n1Xqfy21J6fJB8ITDtFMaVFdjX0DejFxIQ',
);
$config['cloudUrl'] = "http://api.baofengcloud.com/live/createmultichannel?token=";
//p2p视频的相关配置
$config['uid'] = 18;
$config['ukey'] = "812edb699b337fc26a627650749154bd";
$config['p2pUrl'] = "http://admin.live.baofeng.net/createchannel";
