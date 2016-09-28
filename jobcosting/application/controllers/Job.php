<?php 
class Job extends CI_Controller 
{
	private $limit = 10;
	
	function __construct() 
	{
		parent::__construct();
		$this->load->model('job_model','',TRUE);
        $this->load->library(array('table','form_validation', 'session'));  
		$this->load->helper(array('form', 'url'));
		$this->load->library('Pdf');
		
		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user'))
		{	
			redirect($redirect_login_page,'refresh'); 		 
		}	
	}
        
	public function index()
	{
		$data['title'] = 'Job';
		
		if($this->input->post('next'))
		{
			if($this->input->post('job_action')=='2'){
				redirect('job/job_select');
			}else{
				redirect('job/job_create');
			}
		}
		
		$data['maincontent'] = $this->load->view('job/job',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function construction_job_unit($job_id)
	{
		$user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;
        
		$this->db->select("parent_unit");
        $this->db->where('id', $job_id);
        $this->db->where('wp_company_id', $wp_company_id);
  		$parent_unit = $this->db->get('construction_development')->row()->parent_unit;
  		
  		$this->db->select("development_name");
        $this->db->where('id', $parent_unit);
  		$development_name = $this->db->get('construction_development')->row()->development_name;
  		if($development_name){
			echo '<input type="hidden" name="construction_job_parent_id" value="'.$parent_unit.'" />Parent Job: '.$development_name;
		}else{
			echo '0';
		}		
	}
	
	public function check_construction_job_id($job_id)
	{
		$user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;
        
		$this->db->select("id");
        $this->db->where('construction_job_id', $job_id);
  		$row = $this->db->get('jobcosting_jobs')->row();
  		if($row){
			echo '1';
		}else{
			echo '0';
		}	
	}
	
	public function construction_job($job_id)
	{
		$user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;
        
		$this->db->select("id,job_number,development_name");
        $this->db->where('id', $job_id);
        $this->db->where('wp_company_id', $wp_company_id);
  		$row = $this->db->get('construction_development')->row();
  		echo json_encode((array)$row);	
	}
	
	public function construction_job_client($job_id)
	{
		$user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;
        
        $this->db->select("purchaser");
        $this->db->where('id', $job_id);
        $this->db->where('wp_company_id', $wp_company_id);
  		$purchaser1 = $this->db->get('construction_development')->row()->purchaser;
		$purchaser = explode(',',$purchaser1);
		
  		$this->db->select("id,contact_first_name,contact_last_name");
        $this->db->where('wp_company_id', $wp_company_id);
  		$result = $this->db->get('contact_contact_list')->result();
  		
  		$r = '<label for="client_name">Client Name</label>';
  		$r .= '<select id="client_name" multiple="" class="multiselectbox form-control" data-live-search="true" name="client_name">';
  		//$r .= '<option value="">Select a Client</option>';
  		foreach($result as $row){
  			if(in_array($row->id,$purchaser)){
				$selected = 'selected=""';
			}else{
				$selected = '';
			}
			$r .= '<option '.$selected.' value="'.$row->id.'">'.$row->contact_first_name.' '.$row->contact_last_name.'</option>';
		}
		$r .= '</select>';
		
		echo $r;
	}
	
	public function upload_xero()
	{		
		$post = $this->input->post();
				
		if($this->input->post('submit')){
			
			$wp_company_id = $this->session->userdata('user')->company_id;
			
			$contact_id = $post['contact_id'];
			$job_id = $post['job_id'];
			$items_id = $post['item_id'];
		
			if(!$contact_id || !$job_id || !$items_id) return;		
			
			// xero items session data
			$send_xero['send_xero_job'] = $job_id;
			$send_xero['send_xero_item'] = $items_id;
			$this->session->set_userdata($send_xero);
			
			$this->db->select('contact_contact_list.contact_email, contact_contact_list.contact_first_name, contact_contact_list.contact_last_name, contact_company.company_name');
			$this->db->join('contact_company', 'contact_company.id = contact_contact_list.company_id', 'left'); 
			$cons_contact = $this->db->get_where('contact_contact_list',array('contact_contact_list.id'=>$contact_id))->row();
						
			/* creating PO info */
			
			$this->db->where('id', $job_id);
			$job = $this->db->get('jobcosting_jobs',1,0)->row();
			
			$this->db->select("jobcosting_jobs_costing.*");  
	    	$this->db->where('job_id', $job_id);
			$this->db->where('id in ('.implode(',',$items_id).')');
			$items = $this->db->get('jobcosting_jobs_costing')->result();

			$items_arr = array();
			
			foreach($items as $item){
					$items_arr[] = array(
						'Description' => $item->item_name,
						'Quantity' => $item->units_actual,
						'UnitAmount' => number_format($item->price_unit_actual,2,'.',''),
						'AccountCode' => 429
					);
			}
			
			/*$params = array(
				array(
					'Contact' => array(
						'ContactNumber' => $cons_contact->xero_id
					),
					'Date' => date('Y-m-d\TH:i:s'),
					'DeliveryDate' => $job->job_costing_date,
					'PurchaseOrderNumber' => $job->job_number.'-'.count($items),
					'CurrencyCode' => 'NZD',
					'DeliveryInstructions' => $job->information,
					'LineAmountTypes' => 'Exclusive',
					'Status' => 'AUTHORISED',
					'LineItems' => array(
						'LineItem' => $items_arr
					)
					//'BrandingThemeID' => '92f08561-bd65-469b-93fb-65dd1552c678'
				)
			); */
			$po = array(
				'Contact' => array(
					'ContactNumber' => 'Williams-'.$contact_id,
					'Name' => $cons_contact->company_name,
					'ContactStatus' => 'ACTIVE',
					'EmailAddress' => $cons_contact->contact_email
				),
				'PurchaseOrder' => array(
					'Contact' => array(
						'ContactNumber' => 'Williams-'.$contact_id
						
					),
					'Date' => date('Y-m-d\TH:i:s'),
					'DeliveryDate' => $job->job_costing_date,
					//'PurchaseOrderNumber' => $job->job_number.'-'.count($items),
					'PurchaseOrderNumber' => "w-".time(),
					'CurrencyCode' => 'NZD',
					'DeliveryInstructions' => $job->information,
					'LineAmountTypes' => 'Exclusive',
					'Status' => 'AUTHORISED',
					'LineItems' => array(
						'LineItem' => $items_arr
					)
				),
				'redirect_url' => site_url("job/upload_xero_redirect")
			);
			$file = time().rand(100,999);
			$file_name = $_SERVER['DOCUMENT_ROOT']."../xero_files/".$file;
			file_put_contents($file_name, serialize($po));
					
			redirect(site_url('xero/public.php?file='.$file.'&authenticate=1')); exit;					
		}	 		
	}
	
	public function upload_xero_redirect(){
		
		if($this->session->userdata('send_xero_item') && $this->session->userdata('send_xero_job')){			
			// Xero Status Update
			$job_id = $this->session->userdata('send_xero_job');
			$items_id = $this->session->userdata('send_xero_item');
			for($i=0; $i<count($items_id); $i++){
				$this->db->where('id',$items_id[$i]);
				$this->db->update('jobcosting_jobs_costing',array('xero_status'=>1));
			}			
			
			// xero success session data
			$send_xero['send_xero'] = '1';
			$this->session->set_userdata($send_xero);
			
			$this->session->unset_userdata('send_xero_job');
			$this->session->unset_userdata('send_xero_item');
			
			redirect("job/job_costing_create/{$job_id}/actual"); exit;
		}
	}
	
	public function get_po_data(){
		
		if($this->session->userdata('po')){
			
			echo json_encode($this->session->userdata('po')); exit;
		}
	}

	
	public function send_email_contact($item_id,$job_id,$contact_id)
	{	
                
		// Email Status Update
		$this->db->where('id',$item_id);
		$this->db->update('jobcosting_jobs_costing',array('email_status'=>1));
		
		// email success session data
		$send_email['send_email'] = '1';
		$this->session->set_userdata($send_email);
			
  		$this->db->select('contact_contact_list.contact_email, contact_contact_list.contact_first_name, contact_contact_list.contact_last_name, contact_company.company_name, contact_company.company_address');
		$this->db->join('contact_company', 'contact_company.id = contact_contact_list.company_id', 'left'); 
		$cons_contact = $this->db->get_where('contact_contact_list',array('contact_contact_list.id'=>$contact_id))->row();
		
		$company_address = $cons_contact->company_address;
		$company_name = $cons_contact->company_name;
		$contact_email = $cons_contact->contact_email;
		$contact_name = $cons_contact->contact_first_name.' '.$cons_contact->contact_last_name;
		
		$wp_company_id = $this->session->userdata('user')->company_id;
		$wp_company = $this->db->get_where('wp_company',array('id'=>$wp_company_id))->row()->client_name;
		
		$this->db->select("jobcosting_jobs_costing.*");  
    	$this->db->where('job_id', $job_id);
		$items = $this->db->get('jobcosting_jobs_costing')->result(); 
		
		$this->db->select("jobcosting_jobs_costing.*");  
    	$this->db->where('id', $item_id);
		$item = $this->db->get('jobcosting_jobs_costing')->row();  
		 
    	$this->db->where('id', $job_id);
		$job = $this->db->get('jobcosting_jobs')->row(); 
		
		$this->db->select("wp_company.*,wp_file.*");
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
	 	$this->db->where('wp_company.id', $wp_company_id);	
		$wpdata = $this->db->get('wp_company')->row();
		$logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;
  		

                $text = $this->input->post('message');
                $message = str_replace( "\n", '<br />', $text );
  		//$message = ''; 		
  		//$message .= 'Hi '.$company_name.' - '.$contact_name.',<br><br>';
      	//$message .= 'Here is purchase order for '.$job->job_number.' - '.$job->jobname.' - '.$item->item_name.' - '.$item->item_id.' / '.count($items).'.<br><br>';
      	//$message .= 'If you have any questions, please let us know.<br><br>';
		//$message .= 'Thank you,<br>'.$wp_company;
		$address = str_replace( "\n", '<br />', $this->input->post('address') );
		$instructions = str_replace( "\n", '<br />', $this->input->post('instructions') );
		$gst = $this->input->post('gst');
		$telephone = $this->input->post('telephone');
		$attention = $this->input->post('pic');

		$html = '';
		/*$html .= '<table border="0" cellspacing="0" cellpadding="4" width="100%">';
		$html .= '<tbody>';
			$html .= '<tr>';
				$html .= '<td style="width:50%;"></td>';
				$html .= '<td style="width:50%;text-align:center;">';
					$html .= '<img style="width:100px;height:50px;" src="'.$logo.'" />';
				$html .= '</td>';
			$html .= '</tr>';
		$html .= '</tbody>';
		$html .= '</table>';*/
		
		$html .= '<table border="0" cellspacing="0" cellpadding="4" width="100%">';
		$html .= '<tbody>';
			$html .= '<tr>';
				$html .= '<td valign="top" width="50%">';
					$html .= '<h2 style="margin-bottom:0px;" align="center">PURCHASE ORDER</h2>';
					$html .= '<h5 style="margin-top:0px;" align="center">'.$company_name.'</h5>';
				$html .= '</td>';
				$html .= '<td valign="top" width="50%">';
					$html .= '<table border="0" cellspacing="0" cellpadding="4" width="100%">';
					$html .= '<tbody>';
						$html .= '<tr>';
							$html .= '<td valign="top">';
								$html .= '<p><strong>Purchase Order Date</strong><br>'.date('d M Y').'</p>';
							$html .= '</td>';
							$html .= '<td valign="top" ROWSPAN="5">';
								$html .= '<p>'.$company_name.'<br>'.$company_address.'</p>';
							$html .= '</td>';
						$html .= '</tr>';
						$html .= '<tr>';
							$html .= '<td>';
								$html .= '<p><strong>Delivery Date</strong><br>'.date('d M Y',strtotime($job->job_costing_date)).'</p>';
							$html .= '</td>';
						$html .= '</tr>';
						$html .= '<tr>';
							$html .= '<td>';
								$html .= '<p><strong>Purchase Order Number</strong><br>'.$job->job_number.' - '.$item->item_id.'/'.count($items).'</p>';
							$html .= '</td>';
						$html .= '</tr>';
						$html .= '<tr>';
							$html .= '<td>';
								$html .= '<p><strong>Reference</strong><br>'.$job->job_number.' - '.$job->jobname.' - '.$item->item_id.'/'.count($items).'</p>';
							$html .= '</td>';
						$html .= '</tr>';
						$html .= '<tr>';
							$html .= '<td>';
								$html .= '<p><strong>GST Number</strong><br>'.$gst.'</p>';
							$html .= '</td>';
						$html .= '</tr>';
					$html .= '</tbody>';
					$html .= '</table>';
				$html .= '</td>';
			$html .= '</tr>';		
		$html .= '</tbody>';
		$html .= '</table>';
		
		
		$html .= '<table border="0" cellspacing="0" cellpadding="4" width="100%">';
		$html .= '<tbody>';
			$html .= '<tr>';
				$html .= '<td style="width:50%;font-weight: bold;">Description</td>';
				$html .= '<td style="width:15%;font-weight: bold;">Quantity</td>';
				$html .= '<td align="right" style="width:15%;font-weight: bold;">Unit Price</td>';
				$html .= '<td align="right" style="width:20%;font-weight: bold;">Amount NZD</td>';
			$html .= '</tr>';
			$html .= '<tr>';
				$html .= '<td style="border-top:1px solid #000;border-bottom:1px solid #000;">'.$item->item_name.'</td>';
				$html .= '<td style="border-top:1px solid #000;border-bottom:1px solid #000;">'.$item->units_actual.'</td>';
				$html .= '<td align="right" style="border-top:1px solid #000;border-bottom:1px solid #000;">'.round($item->price_unit_actual,2).'</td>';
				$subtotal = $item->units_actual*$item->price_unit_actual;
				$html .= '<td align="right" style="border-top:1px solid #000;border-bottom:1px solid #000;">'.round($subtotal,2).'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
				$html .= '<td align="right" colspan="3">Subtotal</td>';
				$html .= '<td align="right">'.round($subtotal,2).'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
				$html .= '<td align="right" colspan="3">TOTAL GST 15%</td>';
				$per = $subtotal/100*15;
				$html .= '<td align="right">'.round($per,2).'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
				$html .= '<td align="right"></td>';
				$html .= '<td align="right" colspan="2" style="font-weight: bold;border-top:1px solid #000;border-bottom:1px solid #000;">TOTAL NZD</td>';
				$total = $subtotal+$per;
				$html .= '<td align="right" style="font-weight: bold;border-top:1px solid #000;border-bottom:1px solid #000;">'.round($total,2).'</td>';
			$html .= '</tr>';
		$html .= '</tbody>';
		$html .= '</table>';
		
		$html .= '<br><br><br><br><br><h1>DELIVERY DETAILS</h1>';
		$html .= '<table border="0" cellspacing="0" cellpadding="4" width="100%">';
		$html .= '<tbody>';
			$html .= '<tr>';
				$html .= '<td style="width:25%;"><p>Delivery Address<br>'.$address.'</p></td>';
				$html .= '<td style="width:25%;"><p>Attention<br>'.$attention.'<br><br>Telephone<br>'.$telephone.'</p></td>';
				$html .= '<td style="width:50%;"><p>Delivery Instructions<br>'.$instructions.'</p></td>';
			$html .= '</tr>';
		$html .= '</tbody>';
		$html .= '</table>';
		
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('Purchase Order');
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(0);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('Author');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.'', PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		 
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		
		// a random hash will be necessary to send mixed content
		$separator = md5(time());

		// carriage return type (we use a PHP end of line constant)
		$eol = PHP_EOL;

		// attachment name
		$filename = "purchase-order.pdf";

		// encode data (puts attachment in proper format)
		$pdfdoc = $pdf->Output("", "S");
		$attachment = chunk_split(base64_encode($pdfdoc));

		$from = 'tendering@williamsproperty.co.nz';
		$subject = 'Purchase Order - '.$company_name.' - '.$job->job_number.' - '.$job->jobname;
		
		// main header
		$headers  = "From: ".$from.$eol;
		$headers .= "MIME-Version: 1.0".$eol; 	
		$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

		// message
		$body = "--".$separator.$eol;
		$body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
		$body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
		$body .= "$message".$eol;

		// attachment
		$body .= "--".$separator.$eol;
		$body .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol; 
		$body .= "Content-Transfer-Encoding: base64".$eol;
		$body .= "Content-Disposition: attachment".$eol.$eol;
		$body .= $attachment.$eol;
		$body .= "--".$separator."--";
		
		mail($contact_email, $subject, $body, $headers);
		
		redirect('job/job_costing_create/'.$job_id.'/actual');
	}
	
	public function job_create()
	{
		$data['title'] = 'Create Job';
  
		$_post = $this->input->post();
		
		if($this->input->post('submit') && strcasecmp($this->input->post('category_name'), 'tendering system') != 0)
		{
			if($_post['job_costing_date']){
				$job_costing_date = date('Y-m-d', strtotime($_post['job_costing_date']));
			}else{
				$job_costing_date = '0000-00-00';
			}
			if($_post['construction_job_id']){
				$construction_job_id = $_post['construction_job_id'];
			}else{
				$construction_job_id = '0';
			}
			$add = array(
				'construction_job_id' 		=> $construction_job_id,
				'jobname' 		=> $_post['jobname'],
				'template_id' 		=> $_post['template_id'],
				//'client_name' 		=> $_post['client_name'],
				'job_costing_date' 		=> $job_costing_date,
				'job_number' 		=> $_post['job_number'],
				//'order_of_number' 		=> $_post['order_of_number'],
				//'size' 		=> $_post['size'],
				'information' 		=> $_post['information'],
				'created' 		=> date('Y-m-d H:i:s')
			);
			$id = $this->job_model->insert_jobs($add);
			
			/*--Insert Category--*/
	    	$this->db->where('template_id', $_post['template_id']);
			$categorys = $this->db->get('jobcosting_templates_category')->result();
			foreach($categorys as $category)
			{
				$template_category_id = $category->id;
				$cadd = array(
					'job_id' => $id,
					'ordering' => $category->ordering,
					'category_name' => $category->category_name
				);
				$this->db->insert('jobcosting_jobs_category',$cadd);
				$category_id = $this->db->insert_id();
				
				/*--Insert Item--*/
		    	$this->db->where('template_id', $_post['template_id']);
		    	$this->db->where('template_category_id', $template_category_id);
				$items = $this->db->get('jobcosting_templates_items')->result();
				foreach($items as $item)
				{
					$add = array(
						'ordering' => $item->ordering,
						'item_id' => $item->item_id,
						'job_id' => $id,
						'job_category_id' => $category_id,
						'construction_job_id' => $construction_job_id,
						'key_task_id' => $item->key_task_id,
						'item_name'	  => $item->item_name,
						'item_unit'	  => $item->item_unit,
						'price_unit'	  => $item->item_price,
						'price_unit_actual'	  => $item->item_price
					);
					$this->job_model->insert_jobs_costing($add);
				}
			}
			
			/* Add items from tendering system */
			if($_post['construction_job_id']){
				
				$template_id = $this->db->get_where('construction_development',array('id'=>$construction_job_id))->row()->tendering_template_id;
				$this->db->select('id,name,construction_template_task_id');
				$this->db->where('template_id',$template_id);
				$this->db->where('(group_id != 4 or group_id IS NULL) ');
				$this->db->where('job_id IS NULL');
				$tendering = $this->db->get('construction_tendering_template_items')->result();

				if($tendering){
					$tadd = array(
						'job_id' => $id,
						'category_name' => "Tendering System"
					);
					$this->db->insert('jobcosting_jobs_category',$tadd);
					$category_t_id = $this->db->insert_id();
					
					for($i=0;$i<count($tendering); $i++){
						$tiadd = array(
							'job_id' => $id,
							'job_category_id' => $category_t_id,
							'construction_job_id' => $construction_job_id,
							'key_task_id' => $tendering[$i]->construction_template_task_id,
							'item_name'	  => $tendering[$i]->name,
							'from_tendering' => 1
						);
						$this->job_model->insert_jobs_costing($tiadd);
					}	
				}			
			}

			/* Add Variation Category if the job has variation task */
			$this->db->select('id,task_name');
			$this->db->where('development_id',$construction_job_id);
			$this->db->where('type_of_task','variation');
			$res = $this->db->get('construction_development_task')->result();

			if($res){
				//echo "has variation task";
				$cadd = array(
					'job_id' => $id,
					'category_name' => "Variation"
				);
				$this->db->insert('jobcosting_jobs_category',$cadd);
				$category_var_id = $this->db->insert_id();
				for($i=0;$i<count($res); $i++){
					$add = array(
							'job_id' => $id,
							'job_category_id' => $category_var_id,
							'construction_job_id' => $construction_job_id,
							'item_name'	  => $res[$i]->task_name,
						);
						$this->job_model->insert_jobs_costing($add);
				}

			}else{
				//echo "Has no Variation Task";
			}
			
			redirect('job/job_costing_create/'.$id.'/planned');
		}
		else{
			$this->session->set_flashdata('failed', "Category name cannot be \'Tendering System\'");
		}
		
		$data['maincontent'] = $this->load->view('job/job_create',$data,true);
		
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function add_item_company()
	{	
		$_post = $this->input->post();
		
		if($this->input->post('submit'))
		{	
			$item_id = $_post['item_id'];
			if($item_id!=''){
				$config['upload_path'] = UPLOAD_FILE;
	            $config['allowed_types'] = '*';			
	            $this->load->library('upload', $config);
	            $this->upload->initialize($config);

				$filename = '';
	            if ($this->upload->do_upload('file')){
	                $upload_data = $this->upload->data();
	                $filename = $upload_data['file_name'];                  
	            }
	            
				$iadd = array(
					'contact_company_id' => $_post['company_id'],
					'contact_contact_id'	  => $_post['contact_id'],
					'filename'	  => $filename
				);
				$this->db->where('id',$item_id);
				$this->db->update('jobcosting_jobs_costing',$iadd);		
			}		
		}
		redirect($_post['url']);
	}
	
	/*public function add_item($job_id)
	{	
		$_post = $this->input->post();
		
		if($this->input->post('submit'))
		{	
			$construction_job_id = $this->db->get_where('jobcosting_jobs',array('id'=>$job_id))->row()->construction_job_id;			
			$iadd = array(
				'job_id' => $job_id,
				'job_category_id' => $_post['category_id'],
				'construction_job_id' => $construction_job_id,
				'item_name'	  => $_post['item_name'],
				'item_unit'	  => $_post['item_unit'],
				'units'	  => $_post['item_price'],
				'units_actual'	  => $_post['item_price']
			);
			$this->job_model->insert_jobs_costing($iadd);
			
			redirect($_post['url']);
		}
	}*/

        public function add_item($job_id)
	{
		$user = $this->session->userdata('user'); 
		
		$_post = $this->input->post();
		
		$url = $_post['url'];
		
		if($job_id!=''){
			if($this->input->post('submit'))
			{
				$construction_job_id = $this->db->get_where('jobcosting_jobs',array('id'=>$job_id))->row()->construction_job_id;
				$job_category_id = $_post['category_id'];
				
				$items = $_post['items'];
				for($i = 0; $i < count($items); $i++){
					
					$item = $this->db->get_where('jobcosting_items',array('id'=>$items[$i]))->row();
					$key_task_id = $item->key_task_id;
					$item_name = $item->item_name;
					$item_unit = $item->item_unit;
					$item_price = $item->item_price;
					
					$i_add = array(
						'job_id' => $job_id,
						'job_category_id' => $job_category_id,
						'construction_job_id' => $construction_job_id,
						'item_id' => $items[$i],
						'key_task_id'	  => $key_task_id,
						'item_name'	  => $item_name,
						'item_unit'	  => $item_unit,
						'price_unit'	  => $item_price,
						'price_unit_actual'	  => $item_price,
					);
					$this->job_model->insert_jobs_costing($i_add);
			
					
				}
				redirect($_post['url']);
			}
		}	
	}
	
	public function item_drag($category_id,$item_id)
	{	
		$iadd = array(
			'job_category_id' => $category_id
		);
		$item = explode('_',$item_id);
		$item_id = $item[1];
		$this->db->where('id',$item_id);
		$this->db->update('jobcosting_jobs_costing',$iadd);
	}
	
	public function add_category($job_id)
	{	
		$_post = $this->input->post();
		
		if($this->input->post('submit') && strcasecmp($this->input->post('category_name'), 'tendering system') != 0)
		{				
			$cadd = array(
				'job_id' => $job_id,
				'category_name' => $_post['category_name']
			);
			$this->db->insert('jobcosting_jobs_category',$cadd);
			
			redirect('job/job_costing_create/'.$job_id.'/actual');
		}
else{
			$this->session->set_flashdata('failed', "Category name cannot be \"Tendering System\"");
redirect('job/job_costing_create/'.$job_id.'/actual');
		}
	}
	
	public function edit_category()
	{	
		$_post = $this->input->post();
		
		if($this->input->post('submit'))
		{
			$cat_id = $_post['category_id'];			
			$cadd = array(
				'category_name' => $_post['category_name']
			);
			$this->db->where('id',$cat_id);
			$this->db->update('jobcosting_jobs_category',$cadd);
			
			redirect($_post['url']);
		}
	}
	
	public function job_category_item_delete($category_id,$job_id,$type)
	{	
		$this->db->where('id',$category_id);
		$this->db->delete('jobcosting_jobs_category');
		
		$this->db->where('job_category_id',$category_id);
		$this->db->delete('jobcosting_jobs_costing');
		
		redirect('job/job_costing_create/'.$job_id.'/'.$type);
	}
	
	public function ajax_load_category_item($category_id)
	{			
		$this->db->where('job_category_id',$category_id);
		$items = $this->db->get('jobcosting_jobs_costing')->result();
		$row = '<option value="">--Select a Item--</option>';
		foreach($items as $item){
			$row .= '<option value="'.$item->id.'">'.$item->item_name.'</option>';
		}
		echo $row;
	}
	
	public function ajax_load_company_contact($company_id)
	{			
		$this->db->where('company_id',$company_id);
		$contacts = $this->db->get('contact_contact_list')->result();
		$row = '<option value="">--Select a Contact--</option>';
		foreach($contacts as $contact){
			$row .= '<option value="'.$contact->id.'">'.$contact->contact_first_name.' '.$contact->contact_last_name.'</option>';
		}
		echo $row;
	}
	
	public function clean_val($a){
		$a = str_replace( ',', '', $a );
     	return $a;
	}

	public function job_costing_create($id,$type='')
	{	
		$job = $this->job_model->get_job_id($id)->row();
		$data['company'] = $this->db->get_where('wp_company',array('id'=>$this->session->userdata('user')->company_id))->row();
		
		$data['type'] = $type;
		
		if($type=='planned'){
			$type = 'Planned';
		}else if($type=='actual'){
			$type = 'Actual';
		}

		
		$data['title'] = 'Create Job Costing - '.$job->job_name.' ('.$job->job_number.') - '. $type;
		
		$data['template_id'] = $job->template_id;
		
		$data['job'] = $job;
		
		$_post = $this->input->post();	
		
		if($this->input->post('submit'))
		{
		
			//$this->job_model->delete_jobs_costing($id);


				
			$items = $_post['id'];
			$type = $_post['type'];
			
			if($type=='planned'){
				
				$up = array(
					'sale_price'	  => $_post['sale_price']
				);
				$this->job_model->update_jobs($id,$up);
				
				for($i = 0; $i < count($items); $i++){
					$i_id = $_post['id'][$i];
					$add = array(
						'units'	  => $_post['units'][$i],
						'price_unit'	  => $this->clean_val($_post['price_unit'][$i]),
						'total'	  => $this->clean_val($_post['total'][$i])
					);
					$this->job_model->insert_jobs_costing_update($i_id,$add);
				}
			}else{
				$up = array(
					'sale_price'	  => $_post['sale_price']
				);
				$this->job_model->update_jobs($id,$up);
				
				for($i = 0; $i < count($items); $i++){
					$i_id = $_post['id'][$i];
					$add = array(
						'units_actual'	  => $_post['units_actual'][$i],
						'price_unit_actual'	  => $this->clean_val($_post['actual_price_unit'][$i]),
						'total_actual'	  => $this->clean_val($_post['actual_total'][$i])
					);

					$this->job_model->insert_jobs_costing_update($i_id,$add);
				}
			}
			
			redirect('job/job_costing_create/'.$id.'/'.$type);
		}
		
		$data['maincontent'] = $this->load->view('job/job_costing_create',$data,true);
		
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function job_item_ordering(){
		//$list $_GET['listItem_'.$phase_id];
		foreach ($_POST['listItem'] as $position => $item){
            $sql = "UPDATE jobcosting_jobs_costing SET ordering=$position WHERE id=$item";
            $res = $this->db->query($sql);
        }
        return $res;
	}

	public function job_category_ordering(){
		foreach ($_POST['listJobCategory'] as $position => $item){
            $sql = "UPDATE jobcosting_jobs_category SET ordering=$position WHERE id=$item";
            $res = $this->db->query($sql);
        }
        return $res;
	}

	
	public function job_select()
	{		
		$data['title'] = 'View';
		
		$_post = $this->input->post();
		
		if($this->input->post('next'))
		{
			$template_id = $_post['template_id'];
			redirect("job/job_view/$template_id");
		}
		
		$data['maincontent'] = $this->load->view('job/job_select',$data,true);
		
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function job_delete($tem_id,$job_id)
	{	
		$this->db->where('id',$job_id);
		$this->db->delete('jobcosting_jobs');
		
		$this->db->where('job_id',$job_id);
		$this->db->delete('jobcosting_jobs_category');
		
		$this->db->where('job_id',$job_id);
		$this->db->delete('jobcosting_jobs_costing');
		
		redirect("job/job_view/$tem_id");
	}
	
	public function job_view($tem_id='')
	{	
		$template = $this->job_model->get_template_id($tem_id)->row();	
		if($tem_id==''){
			$data['title'] = 'View';
		}else{
			$data['title'] = 'View - '.$template->job_name;
		}		
		
		$data['tem_id'] = $tem_id;
		
		$data['jobs'] = $this->job_model->get_jobs($tem_id)->result();	
		
		$data['maincontent'] = $this->load->view('job/job_view',$data,true);
		
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function job_update($job_id,$type='')
	{

		$data['title'] = 'Edit Job';
		
		$data['type'] = $type;
		
		$_post = $this->input->post();
		
		if($this->input->post('submit'))
		{
			$type = $_post['type'];
                        
            

			
			if($_post['job_costing_date']){
				$job_costing_date = date('Y-m-d', strtotime($_post['job_costing_date']));
			}else{
				$job_costing_date = '0000-00-00';
			}
			
			if($_post['construction_job_id']){
				$construction_job_id = $_post['construction_job_id'];
			}else{
				$construction_job_id = $this->db->get_where('jobcosting_jobs',array('id'=>$job_id))->row()->construction_job_id;
			}
			
			$j_tem_id = $this->db->get_where('jobcosting_jobs',array('id'=>$job_id))->row()->template_id;
			
			$add = array(
				'construction_job_id' 		=> $construction_job_id,
				'jobname' 		=> $_post['jobname'],
				'template_id' 		=> $_post['template_id'],
				//'client_name' 		=> $_post['client_name'],
				'job_costing_date' 		=> $job_costing_date,
				'job_number' 		=> $_post['job_number'],
				//'order_of_number' 		=> $_post['order_of_number'],
				//'size' 		=> $_post['size'],
				'information' 		=> $_post['information'],
                             
			);
			$this->job_model->update_jobs($job_id,$add);
			
			if($j_tem_id!=$_post['template_id']){
				
				$this->db->where('job_id',$job_id);
				$this->db->delete('jobcosting_jobs_category');
				
				$this->db->where('job_id',$job_id);
				$this->db->delete('jobcosting_jobs_costing');
                                
                                
				
				/*--Insert Category--*/
		    	$this->db->where('template_id', $_post['template_id']);
				$categorys = $this->db->get('jobcosting_templates_category')->result();
				foreach($categorys as $category)
				{
					$template_category_id = $category->id;
					$cadd = array(
						'job_id' => $job_id,
						'category_name' => $category->category_name
					);
					$this->db->insert('jobcosting_jobs_category',$cadd);
					$category_id = $this->db->insert_id();
				
					/*--Insert Item--*/
			    	$this->db->where('template_id', $_post['template_id']);
			    	$this->db->where('template_category_id', $template_category_id);
					$items = $this->db->get('jobcosting_templates_items')->result();
					foreach($items as $item)
					{
						$add = array(
							'item_id' => $item->item_id,
							'job_id' => $job_id,
							'job_category_id' => $category_id,
							'construction_job_id' => $construction_job_id,
							'key_task_id' => $item->key_task_id,
							'item_name'	  => $item->item_name,
							'item_unit'	  => $item->item_unit,
							'price_unit'	  => $item->price_unit,
							'price_unit_actual'	  => $item->price_unit_actual
                                                        
						);
						$this->job_model->insert_jobs_costing($add);
					}
				}
			}
			
			redirect('job/job_costing_create/'.$job_id.'/'.$type);
		}
		
		$data['job'] = $this->job_model->get_job_id($job_id)->row();
		
		$data['maincontent'] = $this->load->view('job/job_create',$data,true);
		
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function job_pdf($job_id,$type='')
	{
		$data['title'] = 'View Job';
		
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('View Job');
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(0);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('Author');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.'', PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('helvetica', '', 10);

		
		// add a page
		$pdf->AddPage();
		
		$this->db->select("jobcosting_jobs.*, contact_contact_list.contact_first_name, contact_contact_list.contact_last_name");
		$this->db->join('contact_contact_list', 'contact_contact_list.id = jobcosting_jobs.client_name', 'left');
		$this->db->where('jobcosting_jobs.id',$job_id);
		$job = $this->db->get('jobcosting_jobs')->row();
		
		$html = '<h1 style="color:#cc1618;" align="center">QUOTE FOR:</h1>';
		
		$html .= '<table border="0" cellspacing="0" cellpadding="4" width="100%">
				<tbody>
					<tr>
						<td colspan="2" style="width:100%"><strong>Job Name: </strong>'.$job->jobname.'</td>
					</tr>
					<tr>
						<td style="width:50%"><strong>Job Number: </strong>'.$job->job_number.'</td>
						<td style="width:50%"><strong>Client Name: </strong>'.$job->contact_first_name.' '.$job->contact_last_name.'</td>
					</tr>
				</tbody>
			</table><br>';
			
		$html .= '<table style="border:3px solid #cc1618;" cellspacing="0" cellpadding="0" width="100%"></table>';
		
		$html .= '<p style="padding:0px;margin:0px;font-size:15px;" align="center">Hi '.$job->contact_first_name.' '.$job->contact_last_name.',  Here is the quote for '.$job->jobname.' '.$job->job_number.'. We value your work and would appreciate your feedback regarding this quote. Please do not hesitate to contact us should you have any queries.</p>';
		
		$html .= '<table style="border:3px solid #cc1618;" cellspacing="0" cellpadding="0" width="100%"></table><br><br>';	
		
		// Item
		$html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
            <tbody>
              <tr bgcolor="#ebebeb">
                <td>Items</td>
                <td>Mesurement</td>
                <td>Units</td>
                <td>Price/Unit (exc. GST)</td>
                <td>Total (exc. GST)</td>
              </tr>';
              
	            $this->db->select("jobcosting_jobs_costing.*");
		    	$this->db->where('job_id', $job->id);
				$items = $this->db->get('jobcosting_jobs_costing')->result();   
				if($items){
					$sub_total = '';
					foreach($items as $item)
					{
						if($type=='planned'){
					   		$margin = $job->margin;
					   	}else{
					   		$margin = $job->margin_actual;
					   	}
						$item_unit = array( '1'=>'Days', '2'=>'Hours', '3'=>'m2', '4'=>'Units');
						if($type=='planned'){
							$ma_price = $item->price_unit/100*$margin; 
							$ma_price = $item->price_unit+$ma_price;
							$ma_price_total = $item->units*$ma_price;

							$html .= '<tr> 
						       <td>'.$item->item_name.'</td>
						       <td>'.$item_unit[$item->item_unit].'</td>
						       <td>'.$item->units.'</td>
						       <td>'.$ma_price.'</td>
						       <td>'.$ma_price_total.'</td>
						   </tr>';
						   //$sub_total += $item->total;
							$sub_total += $ma_price_total;
						}else{
							$ma_price = $item->price_unit_actual/100*$margin; 
							$ma_price = $item->price_unit_actual+$ma_price;
							$ma_price_total = $item->units_actual*$ma_price;

							$html .= '<tr> 
						       <td>'.$item->item_name.'</td>
						       <td>'.$item_unit[$item->item_unit].'</td>
						       <td>'.$item->units_actual.'</td>
						       <td>'.$ma_price.'</td>
						       <td>'.$ma_price_total.'</td>
						   </tr>';
						   //$sub_total += $item->total;
							$sub_total += $ma_price_total;
						}
					}
				}
            $html .= '</tbody>';
         $html .= '</table>';
         
         $html .= '<br><br><table border="0" cellspacing="0" cellpadding="0" width="100%" style="color:#cc1618">
            <tbody>';
				
				
				
				if($sub_total){ 
					$total = $sub_total/100*15; 
					$total = $total+$sub_total;
				}
				$html .= '<tr>
					<td colspan="5" align="right"><strong>Total exc. GST: $'.$sub_total.'</strong></td>
				</tr>';
				$html .= '<tr>
					<td align="right" colspan="5"><strong>Total inc. GST: $'.round($total,2).'</strong></td>
				</tr>';
            $html .= '</tbody>';
         $html .= '</table>';
         
		
		$html .= '<br><br><table style="border:3px solid #cc1618;min-height:100px;" cellspacing="0" cellpadding="0" width="100%"></table>';
		
		// Infomation
		$html .= '<br><br><table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>'.$job->information.'</td>
			</tr>
		</table>';
		
		
			
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		//Close and output PDF document
		$pdf->Output('view_job.pdf', 'I');
	}
	
}
