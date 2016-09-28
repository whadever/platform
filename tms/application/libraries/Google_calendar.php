<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
//set_include_path(APPPATH . 'third_party/' . PATH_SEPARATOR . get_include_path());
require_once APPPATH . 'libraries/third_party/Google/autoload.php';

class Google_calendar extends Google_Service_Calendar{
    function __construct($params = array()) {
        parent::__construct();

    }
}