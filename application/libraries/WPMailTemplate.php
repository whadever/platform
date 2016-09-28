<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: User
 * Date: 2/25/2016
 * Time: 11:34 AM
 */
class WPMailTemplate
{
    public function __construct(){

    }
    public function get_mail_body($template, $tokens = array(), $token_values = array()){
        $path = __DIR__."/../mail_templates/".$template.".html";
        $html = file_get_contents($path);
        return str_replace($tokens,$token_values,$html);
    }
}