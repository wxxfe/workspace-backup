<?php
/**
 * 发送get请求
 * @param string $url 请求地址
 * @return string
 */
function send_get($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $result = curl_exec($ch);
    if (!$result) var_dump(curl_error($ch));
    curl_close($ch);
    return $result;
}

/**
 * 发送post请求
 * @param string $url 请求地址
 * @param NULL $post_data post键值对数据 或者 json字符串
 * @return string
 */
function send_post($url, $post_data = NULL) {
    $post_str = '';

    if (is_array($post_data)) {
        foreach ($post_data as $k => $v) {
            $post_str .= $k . "=" . $v . "&";
        }
        $post_str = rtrim($post_str, '&');
        $header = false;
    } else {
        $post_str = $post_data;
        $header = array('Content-Type: application/json');
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if(is_array($header))
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_str);

    $result = curl_exec($ch);
    if ($result === false) {
        //echo 'Curl error: ' . curl_error($ch);
        curl_close($ch);
        return false;
    }

    curl_close($ch);

    return $result;
}

/**
 * 获取目录下的所有文件
 * @param string $path
 * @param string $fid
 * @return array
 */
function get_files($dir, $fid) {
    $fileArray = array();
    if (false != ($handle = opendir($dir))) {
        while (false !== ($file = readdir($handle))) {
            //去掉"“.”、“..”以及带“.xxx”后缀的文件
            if ($file != "." && $file != ".." && strpos($file, ".")) {
                $fileArray[] = base_url('static/screenshot/' . $fid) . '/' . $file;
            }
        }
        //关闭句柄
        closedir($handle);
    }
    asort($fileArray, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);
    return $fileArray;
}

/**
 * 二维数组排序 降序
 * myarsort($data['result'], 'seq');
 */

function myarsort(&$array, $key, $s = 'a') {
    $sorter = array();
    $ret = array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii] = $va[$key];
    }
    if ($s == 'a') {
        //降序排序
        arsort($sorter);
    } else {
        //升序排序
        krsort($sorter);
    }

    foreach ($sorter as $ii => $va) {
        $ret[$ii] = $array[$ii];
    }
    $array = $ret;
}

/**
 * 二维数组排序 升序
 * myarsort($data['result'], 'seq');
 */
function myasort(&$array, $key) {
    $sorter = array();
    $ret = array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii] = $va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii] = $array[$ii];
    }
    $array = $ret;
}

/**
 * 返回一个$length长度的密码
 */
function create_password($length = 8) {
    //密码字符集，可任意添加你需要的字符
    $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
        'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!',
        '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_',
        '[', ']', '{', '}', '<', '>', '~', '`', '+', '=', ',',
        '.', ';', ':', '/', '?', '|');

    //在 $chars 中随机取 $length 个数组元素键名
    $keys = array_rand($chars, $length);
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        //将 $length 个数组元素连接成字符串
        $password .= $chars[$keys[$i]];
    }
    return $password;
}

function utf8_str_extend_space($str) {
    return preg_replace('/\s+/', ' ', implode(' ', utf8_str_split($str)));
}

/**
 * 分割utf8字符串成数组
 */
function utf8_str_split($str) {
    $split = 1;
    $array = array();
    for ($i = 0; $i < strlen($str);) {
        $value = ord($str[$i]);
        if ($value < 128) {
            $split = 1;
        } else {
            if ($value >= 192 && $value <= 223) {
                $split = 2;
            } elseif ($value >= 224 && $value <= 239) {
                $split = 3;
            } elseif ($value >= 240 && $value <= 247) {
                $split = 4;
            }
        }
        $key = null;
        for ($j = 0; $j < $split; $j++, $i++) {
            $key .= $str[$i];
        }
        $array[] = $key;
    }
    return $array;
}

?>
