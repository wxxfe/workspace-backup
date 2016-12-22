<?php
/**
 * video、news表match_id>0的数据关联到match_related表 (video数据排除打了集锦和录像标签)
 * example:
 * php -f ./import_match_relate.php video init  初始化
 * php -f ./import_match_relate.php video 默认增量
 * php -f ./import_match_relate.php news init  初始化
 * php -f ./import_match_relate.php news 默认增量
 */
if(!isset($argv[1])){
    echo "参数错误";die;
}

define('BASEPATH',dirname(__FILE__));
define('ENVIRONMENT','production');//会影响数据库配置文件加载

//加载数据库文件
require_once BASEPATH . '/DB.php';

//初始化数据库
$database = 'sports';
$db = new CronMysqlPdo($database);

$table_name = $type = $argv[1];//news|video
$operation = isset($argv[2]) ? $argv[2] : 'incr';//init|incr 默认增量

//获取上次处理的news表或video表最大id
$last_id_file = '/tmp/import_match_related_'.$table_name.'.txt';
if($operation == 'init'){
    $last_id = 0;
}else{
    $last_id = @file_get_contents($last_id_file);
    $last_id = $last_id ? $last_id : 0;
}

//查询video或news表数据
if($table_name == 'video'){
    $sql = "SELECT DISTINCT(video.id),match_id FROM `$table_name`  WHERE video.id>$last_id
 AND match_id>0 AND video.visible=1 AND video.id not in(select DISTINCT(video_id) from video_tag where tag_id in(1000004,1000005) and video_id>$last_id) ORDER BY video.id ASC";
}else{
    $sql = "SELECT id,match_id FROM `$table_name` WHERE id>$last_id AND match_id>0 AND visible=1 ORDER BY id ASC";
}
$query = $db->query($sql);
$rows = $query ? $query->fetchAll(PDO::FETCH_ASSOC) : array();
$i=0;
foreach($rows as $row){
    $last_id_record = $id = $row['id'];
    $match_id = $row['match_id'];

    $match_sql = "SELECT id FROM match_related WHERE ref_id='$id' AND `type`='$type' LIMIT 1;";
    $match_query = $db->query($match_sql);
    $match_row = $match_query ? $match_query->fetch(PDO::FETCH_ASSOC) : '';
    if($match_row){
        continue;
        //$mid = $match_row['id'];
        //$sql_update = "UPDATE match_related SET match_id='$match_id' WHERE id='$mid'";
        //$db->exec($sql_update);
    }else{
        $sql_insert = "INSERT INTO match_related SET ref_id='$id',match_id='$match_id',`type`='$type';";
        echo $sql_insert."\n";
        $db->exec($sql_insert);
    }
    echo $last_id_record."\n";
    $i++;
    echo "$table_name:".$i."\n";
}
@file_put_contents($last_id_file, $last_id_record);
echo "OK\n";



?>