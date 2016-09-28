<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
require_once dirname(__FILE__) . '/FPDI/fpdi.php';

class Pdf extends FPDI
{
    function __construct()
    {
        parent::__construct();
    }
	
    public function Header() {
    	$CI = & get_instance();  //get instance, access the CI superobject
  		$user = $CI->session->userdata('user');
		$wp_company_id = $user->company_id;
		
		$this->ums = $CI->load->database('ums', TRUE);
		
		$this->ums->select("wp_company.*,wp_file.*");
		$this->ums->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
	 	$this->ums->where('wp_company.id', $wp_company_id);	
		$wpdata = $this->ums->get('wp_company')->row();

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
    	$CI = & get_instance();  //get instance, access the CI superobject
  		$user = $CI->session->userdata('user');
		$wp_company_id = $user->company_id;
		
		$this->ums = $CI->load->database('ums', TRUE);
		
		$this->ums->select("wp_company.*,wp_file.*");
		$this->ums->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
	 	$this->ums->where('wp_company.id', $wp_company_id);	
		$wpdata = $this->ums->get('wp_company')->row();
		
		$colour_two = $wpdata->colour_one;
		
        // Position at 15 mm from bottom
        $this->SetY(-20);
		//$this->SetX(0);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
		$html='<table width="100%" cellpadding="4" cellspacing="3" bgcolor="'.$colour_two.'">
			<tr><td align="left"><span style="font-size:12px; color:#fff;font-weight:bold;"><i>We call Canterbury home</i></span></td></tr>
			<tr><td align="left"><span style="color:#fff;">38 Lowe St, Addington, PO Box 8255, Riccarton, Christchurch, New Zealand. Ph: (03) 348 8905 0800 NEW HOME <br>
			info@horncastle.co.nz <strong>www.horncastle.co.nz</strong> Proud to be Naming Partner for <strong>Horncastle Arena</strong></span></td>
			
			</tr>
		</table>';
		$this->writeHTMLCell(0, 0, '', '', $html, 0, 0, false, "L", true);
		
        // Page number
        $this->Cell(23, 30, $footer_html.' Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}



/*Author:Abdullah Al Mamun */  
/*Company:Xprocoders */  
/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */

