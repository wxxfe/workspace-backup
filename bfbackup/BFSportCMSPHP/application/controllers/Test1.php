<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test1 extends MY_Controller {

    public function __construct() {
        parent::__construct();
        var_dump(uri_string());
    }

    public function index() {
    }

    public function test2($a) {
        echo $a;
    }

}
