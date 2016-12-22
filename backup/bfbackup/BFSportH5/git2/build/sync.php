<?php

if($argc < 2){
    echo "请输入生成的环境参数：\n开发环境 - development\n测试环境 - testing\n生产环境 - production";
    exit;
}

$envs = array('development','testing','production');

$env = $argv[1];

if(!in_array($env,$envs)){
    echo "输入的参数不正确";
    exit;
}

/**
 * 发送get请求
 * @param string $url 请求地址
 * @return string
 */
function send_get($url) {
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_HEADER, false );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
    $result = curl_exec ( $ch );
    if (!$result) var_dump(curl_error($ch));
    curl_close ( $ch );
    return $result;
}


$config = send_get('http://static.sports.baofeng.com/msports/resource.json');

$resource = json_decode($config,true);

$context = "<?php\n\$config['template_resource'] = array(\n";

foreach($resource[$env] as $key => $page){

    $context .= "'".$page['page'] . "' => array(\n";
    $context .= "    'css' => array(\n";
    foreach($page['css'] as $i => $c){
        $context .= "        '". $c ."'";
        if($i != count($page['css']) - 1){
            $context .= ",\n";
        }else{
            $context .= "\n";
        }
    }
    $context .= "    ),\n";

    $context .= "    'js' => array(\n";
    foreach($page['js'] as $s => $j){
        $context .= "        '". $j ."'";
        if($s != count($page['js']) - 1){
            $context .= ",\n";
        }else{
            $context .= "\n";
        }
    }
    $context .= "    )\n";
    $context .= "),\n";

}

$context .= ");\n?>";

$base_path = dirname(dirname(__FILE__));
$rePath = $base_path . '/application/config/resource.php';
$resFile = fopen($rePath,'w');

fwrite($resFile,$context);
fclose($resFile);

//download css file to location
$css_config = array();
foreach($resource[$env] as $key => $page) {
    foreach($page['css'] as $file) {
        $css_config[$page['page']]['css'][] = $file;
    }
}
foreach ($css_config as $key => $val) {
    $css_path = $base_path . '/static/css/' . $env . '/' . $key . '/';
    if (is_dir($css_path)) {//clear old css file
        $css_files = @glob($css_path . "*");
        foreach($css_files as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }
    if (isset($val['css']) && !empty($val['css'])) {
        foreach ($val['css'] as $css_file) {
            $css_content = @file_get_contents($css_file);
            if ($css_content) {
                if (!is_dir($css_path)) {
                    @mkdir($css_path, 0755, true);
                }
                $css_name = basename($css_file);
                @file_put_contents($css_path . $css_name, $css_content);
            }
        }
    }
}

?>
