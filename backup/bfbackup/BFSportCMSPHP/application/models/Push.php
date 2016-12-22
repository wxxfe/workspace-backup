<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//for umeng
require_once(dirname(BASEPATH).'/application/libraries/umeng' . '/' . 'notification/android/AndroidBroadcast.php');
require_once(dirname(BASEPATH).'/application/libraries/umeng' . '/' . 'notification/android/AndroidFilecast.php');
require_once(dirname(BASEPATH).'/application/libraries/umeng' . '/' . 'notification/android/AndroidGroupcast.php');
require_once(dirname(BASEPATH).'/application/libraries/umeng' . '/' . 'notification/android/AndroidUnicast.php');
require_once(dirname(BASEPATH).'/application/libraries/umeng' . '/' . 'notification/android/AndroidCustomizedcast.php');
require_once(dirname(BASEPATH).'/application/libraries/umeng' . '/' . 'notification/ios/IOSBroadcast.php');
require_once(dirname(BASEPATH).'/application/libraries/umeng' . '/' . 'notification/ios/IOSFilecast.php');
require_once(dirname(BASEPATH).'/application/libraries/umeng' . '/' . 'notification/ios/IOSGroupcast.php');
require_once(dirname(BASEPATH).'/application/libraries/umeng' . '/' . 'notification/ios/IOSUnicast.php');
require_once(dirname(BASEPATH).'/application/libraries/umeng' . '/' . 'notification/ios/IOSCustomizedcast.php');

class Push {
    
    public function __construct() {
        $this->appkey = "56d6af9667e58e0a6e000e7d";
        $this->iosappkey = "56d6b06ee0f55ad8bb001ca6";
        $this->appMasterSecret = "njsbqyjwfbriu3vnjqrtdqvfgk9ck0w3";
        $this->iosappMasterSecret = "l9ltu3ha7qhsgjp9fuqsdwyeymvcqbgq";
        $this->timestamp = strval(time());
        
    }
    
    function umengSendAndroidBroadcast($send_data) {
        try {
            $brocast = new AndroidBroadcast();
            $brocast->setAppMasterSecret($this->appMasterSecret);
            $brocast->setPredefinedKeyValue("appkey",           $this->appkey);
            $brocast->setPredefinedKeyValue("timestamp",        $this->timestamp);
            
            $brocast->setPredefinedKeyValue("display_type",     "message");
            $brocast->setPredefinedKeyValue("custom",           $send_data);
            $brocast->setPredefinedKeyValue("description",      "CMS推送");
            
            
//             $brocast->setPredefinedKeyValue("ticker",           "Android broadcast ticker");
//             $brocast->setPredefinedKeyValue("title",            "中文的title");
//             $brocast->setPredefinedKeyValue("text",             "Android broadcast text");
//             $brocast->setPredefinedKeyValue("after_open",       "go_app");
            // Set 'production_mode' to 'false' if it's a test device.
            // For how to register a test device, please see the developer doc.
            if (ENVIRONMENT != 'production') {
                $brocast->setPredefinedKeyValue("production_mode", "false");
            } else {
                $brocast->setPredefinedKeyValue("production_mode", "true");
            }
            // [optional]Set extra fields
            // $brocast->setExtraField("test", "helloworld");
            
            // print("Sending broadcast notification, please wait...\r\n");
            $result = json_decode($brocast->send(), true);
            if($result["ret"] == "SUCCESS") {
                return true;
            } else {
                return false;
            }
            // print("Sent SUCCESS\r\n");
        } catch (Exception $e) {
            print("Caught exception: " . $e->getMessage());
        }
    }

    
    function umengSendIOSBroadcast($send_data) {
        try {
            $send_data_arr = json_decode($send_data, true);
            
            $brocast = new IOSBroadcast();
            $brocast->setAppMasterSecret($this->iosappMasterSecret);
            $brocast->setPredefinedKeyValue("appkey",           $this->iosappkey);
            $brocast->setPredefinedKeyValue("timestamp",        $this->timestamp);
            $brocast->setPredefinedKeyValue("description",      "CMS推送");
            
            $brocast->setPredefinedKeyValue("alert", $send_data_arr['desc']);
            $brocast->setPredefinedKeyValue("badge", 0);
            $brocast->setPredefinedKeyValue("sound", "default");
            
            // Set 'production_mode' to 'true' if your app is under production mode
            if (ENVIRONMENT != 'production') {
                $brocast->setPredefinedKeyValue("production_mode", "false");
            } else {
                $brocast->setPredefinedKeyValue("production_mode", "true");
            }
            // Set customized fields
            $brocast->setCustomizedField('type', $send_data_arr['type']);
            $brocast->setCustomizedField('data', json_encode($send_data_arr['data']));
            // print("Sending broadcast notification, please wait...\r\n");
            $result = json_decode($brocast->send(), true);
            if($result["ret"] == "SUCCESS") {
                return true;
            } else {
                return false;
            }
            // print("Sent SUCCESS\r\n");
        } catch (Exception $e) {
            print("Caught exception: " . $e->getMessage());
        }
    }
}
