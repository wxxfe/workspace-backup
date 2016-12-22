<?php
class UrlRequest {
    
    protected $_requestHandler = null;
    
    public function __construct() {
        $this->init();
    }
    
    public function init() {
        if (!$this->_requestHandler) {
            $this->_requestHandler = curl_init();
            curl_setopt($this->_requestHandler, CURLOPT_TIMEOUT, 10);
            curl_setopt($this->_requestHandler, CURLOPT_HEADER, false);
            curl_setopt($this->_requestHandler, CURLOPT_FORBID_REUSE, false);
            curl_setopt($this->_requestHandler, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->_requestHandler, CURLOPT_NOSIGNAL, true);
            curl_setopt($this->_requestHandler, CURLOPT_HTTPHEADER, array(
                'Connection: keep-alive', 'Keep-Alive: 300',
            ));
        }
    }
    
    public function reset() {
        if ($this->_requestHandler) {
            curl_close($this->_requestHandler);
        }
        $this->_requestHandler = null;
    }
    
    /**
     * 封装curl请求方法
     * 目前仅用到get方式，只使用第一个参数，后面的使用默认值即可
     * 
     * @param string $url       - request url
     * @param string $method    - request method (default `get`)
     * @param string $body      - request body (default empty)
     * 
     * @return string | false   - response body or `false` on failure
     */
    public function request($url, $method='get', $body='') {
        curl_setopt($this->_requestHandler, CURLOPT_URL, $url);
        
        $result = curl_exec($this->_requestHandler);
        if (!$result) {
            error_log(curl_error($this->_requestHandler));
        };
        // curl_close($this->_requestHandler);
        
        return $result;
    }
    
}