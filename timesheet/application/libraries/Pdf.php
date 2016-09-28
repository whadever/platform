<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }

    public function Header() {
        if(isset($this->headerHtml)){

            $this->writeHTML($this->headerHtml, true, 0, true, 0);

        }else{
            parent::header();
        }

    }

}
