<?php

/**
 * 返回图片地址
 * @param string $imageCode
 * @return string
 */
function getImageUrl($imageCode){
    if(strpos($imageCode,'http://').'' == ''){
        return IMG_DOMAIN . $imageCode;
    }else{
        return $imageCode;
    }
}

?>