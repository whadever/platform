<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
	
    public function Header() {
    	$CI = & get_instance();  //get instance, access the CI superobject
  		$user = $CI->session->userdata('user');
		$wp_company_id = $user->company_id;
		
		$this->db = $CI->load->database('default', TRUE);
		
		$this->db->select("wp_company.*,wp_file.*");
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
	 	$this->db->where('wp_company.id', $wp_company_id);	
		$wpdata = $this->db->get('wp_company')->row();

		//print_r($wpdata);
		$logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;
        // Logo
        //$image_file = K_PATH_IMAGES.'report_logo.png';
        $image_file = $logo;
        $this->Image($image_file, 10, 10, 50, '', 'PNG', '', 'T', false, 300, 'R', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

   
    public function Footer() {
    	
    }
}


