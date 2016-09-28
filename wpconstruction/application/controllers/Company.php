<?php 
class Company extends CI_Controller {
	
	private $limit = 10;
	private $user_app_role = '';
	private $wp_company_id = '';
	
	function __construct() {
		
		parent::__construct();
		
		$this->load->model('company_model','',TRUE);
        $this->load->model('company_notes_model','',TRUE);
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->library('Wbs_helper');
        $this->load->library('breadcrumbs');
        $this->load->library('Pdf');
		$this->load->helper(array('form', 'url'));
        $this->load->helper('email');
        date_default_timezone_set("NZ");

		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		
		}

		/*getting user's application role*/
		$user = $this->session->userdata('user');
		$sql = "select LOWER(ar.application_role_name) role
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                where ua.user_id = {$user->uid} and a.id = 5 limit 0, 1";
		$this->user_app_role = $this->db->query($sql)->row()->role;
		$this->wp_company_id = $user->company_id;
                
	}
        
	public function index(){
		$data['title'] = 'Company';
            
 		//$companys = $this->company_model->company_list_search_count($sort_by,$order_by,$offset,$this->limit,$get)->result();
		$companys = $this->company_model->get_company()->result();
		$data['companys']=  $companys;
		$data['maincontent'] = $this->load->view('company',$data,true);		
		$this->load->view('includes/header',$data);           	
		$this->load->view('contact/contact_home',$data);
		$this->load->view('includes/footer',$data);
  
	}
        
	public function company_list($sort_by = 'id', $order_by = 'desc', $offset = 0){
         
 		$company_name =  $this->input->post('company_name');
		if($this->input->post('company_sort')==''){
			$sort_by = 'company_name' ;
 			$order_by = 'asc';
		}
		else if($this->input->post('company_sort')=='id'){
			$sort_by = 'id' ;
			$order_by = 'desc';
		}
		else{
			$sort_by = $this->input->post('company_sort') ;
			$order_by = 'asc';
		}
           
		$data['title'] = 'Companies';
		$data['sort_by']=$this->input->post('company_sort');
        $data['user_app_role'] = $this->user_app_role;

		$get = $_GET;
		$this->limit = 50;
            
		$companies = $this->company_model->get_company_list_all()->result();
 		$data['companies']=  $companies; 

		$data['maincontent'] = $this->load->view('company/company_list',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('contact/contact_home',$data);
		$this->load->view('includes/footer',$data);

		/*log*/
		$this->wbs_helper->log('Company list',"Viewed company list");
	}
	
	public function company_add() {

		if($this->user_app_role == 'contractor') return;

		$user=  $this->session->userdata('user');          
		$user_id =$user->uid; 
            
		$data['title'] = 'Add New Company';
		$data['action'] = site_url('company/company_add');
         		  
		$this->_set_rules();
		
		if( $this->form_validation->run() === FALSE ) {
			$data['maincontent'] = $this->load->view('company_add',$data,true);		
			$this->load->view('includes/header',$data);
			$this->load->view('contact/contact_home',$data);
			$this->load->view('includes/footer',$data);
			
		}else {

			$post = $this->input->post();
            $config['upload_path'] = UPLOAD_FILE_PATH_REQUEST_DOCUMENT;
            $config['allowed_types'] = '*';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $document_insert_id = 0;
            if ($this->upload->do_upload('upload_document')){
                $upload_data = $this->upload->data();
                //print_r($upload_data); 
                // insert data to file table
                // get latest id from frim table and insert it to loan table
                $document = array(
                    'filename'=>$upload_data['file_name'],
                    'filetype'=>$upload_data['file_type'],
                    'filesize'=>$upload_data['file_size'],
                    'filepath'=>$upload_data['full_path'],
                    //'filename_custom'=>$post['upload_document'],
                    'created'=>date("Y-m-d H:i:s"),
                    'uid'=>$user_id
                );
                $document_insert_id = $this->company_model->file_insert($document);                        
            }else{
                // print 'error in file uploading...'; 
                // print $this->upload->display_errors() ;  
            } 

            $image_insert_id = 0;
            if ($this->upload->do_upload('upload_image')){
                $upload_data = $this->upload->data();
                //print_r($upload_data); 
                // insert data to file table
                // get latest id from frim table and insert it to loan table
                $image = array(
                    'filename'=>$upload_data['file_name'],
                    'filetype'=>$upload_data['file_type'],
                    'filesize'=>$upload_data['file_size'],
                    'filepath'=>$upload_data['full_path'],
                    //'filename_custom'=>$post['upload_image'],
                    'created'=>date("Y-m-d H:i:s"),
                    'uid'=>$user_id
                );
                $image_insert_id = $this->company_model->file_insert($image);                        
            }else{
                // print 'error in image uploading...'; 
                // print $this->upload->display_errors() ;  
            } 

			$company_data = array(                              
				'company_name' => $this->input->post('company_name'),
				'category_id' => $this->input->post('category_id'),
				'company_image_id' =>$image_insert_id,				
				'company_notes' => $this->input->post('company_notes'),
				'created'=>date("Y-m-d H:i:s"),
				'created_by' => $user_id
			);	
				
			$id = $this->company_model->company_save($company_data);
			$this->session->set_flashdata('success-message', 'Company Successfully Added');
			redirect('company/company_list');
			
		} 


	}
	
	public function company_delete($pid){

		if($this->user_app_role == 'contractor') return;

		// delete company
		$this->company_model->delete_company_with_requests_notes($pid);
                 $this->session->set_flashdata('warning-message', 'Company Successfully Removed');
		// redirect to company list page
		redirect('company/company_list');
	}
	
	
	function company_update($pid){

		if($this->user_app_role == 'contractor') return;

		$user=  $this->session->userdata('user');          
		$user_id =$user->uid; 
		
		$data['title'] = 'Update Company';
		$data['action'] = site_url('company/company_update/'.$pid);
		$this->_set_rules();	
		
		if ($this->form_validation->run() === FALSE){
			$data['company'] = $this->company_model->get_company_detail($pid)->row();
		}
		else
		{
    
			$post = $this->input->post();
            $config['upload_path'] = UPLOAD_FILE_PATH_REQUEST_DOCUMENT;
            $config['allowed_types'] = '*';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

			/*log*/
			$company = $this->company_model->get_company_detail($pid)->row();
			$this->wbs_helper->log('Company update',"Updated  <b>{$company->company_name}</b>");

            $image_insert_id = 0;
            if ($this->upload->do_upload('upload_image'))
			{
                $upload_data = $this->upload->data();
                $image = array(
                    'filename'=>$upload_data['file_name'],
                    'filetype'=>$upload_data['file_type'],
                    'filesize'=>$upload_data['file_size'],
                    'filepath'=>$upload_data['full_path'],
                    //'filename_custom'=>$post['upload_image'],
                    'created'=>date("Y-m-d H:i:s"),
                    'uid'=>$user_id
                );
                $image_insert_id = $this->company_model->file_insert($image);                        
            }
			else
			{
                // print 'error in image uploading...'; 
                // print $this->upload->display_errors() ;  
            } 
	
			if($image_insert_id==0)
			{
				$image_insert_id = $this->input->post('image_id');
			}
			
			$company_data = array(                              
				'company_name' => $this->input->post('company_name'),
				'company_email'=> $this->input->post('company_email'),
				'company_address'=> $this->input->post('company_address'),
				'contact_number' => $this->input->post('contact_number'),
				'category_id' => $this->input->post('category_id'),
				'company_image_id' => $image_insert_id,				
				'company_notes' => $this->input->post('company_notes'),
				'company_city' => $this->input->post('company_city'),
				'company_country' => $this->input->post('company_country'),
				'company_website' => $this->input->post('company_website'),
				'created'=>date("Y-m-d H:i:s"),
				'created_by' => $user_id
			);

			$this->company_model->update($pid,$company_data);
			$this->session->set_flashdata('success-message', 'Company Successfully Updated');
			redirect('company/company_list');
		}
		
		
		$data['maincontent'] = $this->load->view('company/company_add',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('contact/contact_home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	function _set_rules(){
            //$this->form_validation->set_rules('compname', 'compname', 'trim|required|is_unique[company_profile.compname]');
            //$this->form_validation->set_rules('company_id', 'Company Id', 'callback_company_id');
            $this->form_validation->set_rules('company_name', 'Company Name');
           //$this->form_validation->set_rules('company_name', 'Company Name', 'required|min_length[5]|max_length[12]');
           // $this->form_validation->set_rules('email_addr_1', 'Email', 'required|valid_email|is_unique[company_profile.email_addr_1]');
	}
		
   	public function auto_load_company()
	{

		
		$page				=	intval( $_POST['p'] );
		$current_page		=	$page - 1;
		$records_per_page	=	20; 
		$start				=	$current_page * $records_per_page;

		$result = $this->company_model->get_limited_company($start,$records_per_page);
		$html	=	"";	
		
		foreach ( $result as $company ) 
		{
			$update_link = ($this->user_app_role != 'contractor') ? '<a href="'.base_url().'company/company_update/'.$company->id.'"><img class="edit_icon" src="'.base_url().'images/icon/icon_edit.png" /></a>' : '';
			if($company->status == 1){ $stat = "ACTIVE"; }else{ $stat= "INACTIVE";}
			$html	.='<tr><td width="5%">'.$company->id.'</td>
						<td width="20%"><a href="' . base_url() . 'company/company_details/' . $company->id . '">' . $company->company_name . '</a></td>
						<td width="20%">'.$company->company_address.'</td>
						<td width="15%">'.$company->contact_number.'</td>
						<td width="15%">'.$company->company_city.'</td>
						<td width="15%">'.$company->company_country.'</td>
						<td width="10%">'.$stat.$update_link.'</td></tr>';
		}
	
		$data	=	array(
						'html'			=>	$html
					);
		echo json_encode($data);

	}

        
   
   function company_details($pid=0){
		
		if ($pid <=0){
             redirect('company/company_list');
        }
        
        
        
        $company = $this->company_model->get_company_detail($pid)->row();
        

		$data['title'] = 'Company Detail for : '  . $company->company_name;
		$data['company_id']=$pid;
		$data['company_title']=$company->company_name;
		$data['company'] = $company;
		$data['user_app_role']  = $this->user_app_role;
	   $data['contacts'] = array();

	   $contact_list = $this->company_model->get_contact_list($pid);
	   foreach ($contact_list as $contact){
		   $data['contacts'][] = array(
			   'name'=>$contact->contact_first_name.' '.$contact->contact_last_name,
			   'title'=>$contact->contact_title,
			   'file_name'=>$contact->file_name,
			   'id'=>$contact->id
		   );
	   }
		  
 		$data['maincontent']=$this->load->view('company/company_detail',$data,true);
		$this->load->view('includes/header', $data);
		$this->load->view('contact/contact_home',$data);
		$this->load->view('includes/footer', $data);

		/*log*/
		$this->wbs_helper->log('Company details',"Viewed details of company <b>{$company->company_name}</b>");
    }

	public function notes_image_tmpl($prev_notes){
      $user=  $this->session->userdata('user');          
      $user_id =$user->uid; 
      
      $align_class='';
      $tmpl='';
      if(empty($prev_notes)){$tmpl= "<p>No Notes Found</a>";}
      foreach ($prev_notes as $notes) {
           
           
           
        if($notes->notes_by==$user_id)
        {
           $showuser= 'Me'; 
           $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
           $notified_user= $this->company_notes_model->getNotifiedUserName($notes->notify_user_id);
           $align_class = 'right';
           if(!$notes->notes_image_id == null){
               $show_file= $this->company_notes_model->getNotesImage($notes->notes_image_id);
               
               $tmpl .= '<div class="'.$align_class.'"><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></div> </div>';
           }else{
               $tmpl .= '<div class="'.$align_class.'"><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body">'.$notes->notes_body.'</div><div style=""><span class="time-left1">'.$creation_time.'</span>'.$notified_user.'</div> </div>';
           }
           
           
           $tmpl .= '<div class="clear"></div>';

        }else{
            $showuser= $notes->username; 
            $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
            $notified_user= $this->company_notes_model->getNotifiedUserName($notes->notify_user_id);
            $align_class ='left';
            if(!$notes->notes_image_id == null){
                $show_file= $this->company_notes_model->getNotesImage($notes->notes_image_id);
                $tmpl .= '<div class="'.$align_class.'"><div class="useranother">'.$showuser.':</div> <div style="float: left;" class="notes_body"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></div> </div>';
            }
            else{
                $tmpl .= '<div class="'.$align_class.'"><div class="useranother">'.$showuser.':</div>  <div style="float: left;" class="notes_body">'.$notes->notes_body.'</div><div style=""><span class="time-right1">'.$creation_time.'</span>'.$notified_user.'</div></div>'; 
            }
            
            $tmpl .= '<div class="clear"></div>';
        }
       } 
       return $tmpl;
      
  }
	function edit_note($cid){
		if($this->user_app_role == 'contractor') return;

		/*log*/
		$company = $this->company_model->get_company_detail($cid)->row();
		if($company->company_notes != $this->input->post('note')){

			$this->wbs_helper->log('Company notes',"Updated notes of company <b>{$company->company_name}</b>");
		}

		$note =  $this->input->post('note');
		$query = "update contact_company set company_notes = '{$note}' where id = {$cid}";
		$res = $this->db->simple_query($query);


		echo $res; exit;
	}

	public function competency_register_pdf($company_id){

		$sql = "SELECT cr.*, contact.contact_first_name, contact.contact_last_name, company.company_name
 				FROM contact_company company
				JOIN contact_contact_list contact on company.id = contact.company_id
				JOIN contact_competency_register cr ON cr.contact_id = contact.id
				WHERE company.id = {$company_id} AND company.wp_company_id = {$this->wp_company_id}";
		$cr = $this->db->query($sql)->result();
		$tbl = "";
		foreach($cr as $c){
			$dt1 = ($c->passport_expiry_date == '0000-00-00') ? '' : date_create_from_format('Y-m-d',$c->passport_expiry_date)->format("d M <br>Y");
			$dt2 = ($c->d_l_expiry_date == '0000-00-00') ? '' : date_create_from_format('Y-m-d',$c->d_l_expiry_date)->format("d M <br>Y");
			$tbl .= <<<EOT
				<tr>
					<td>{$c->contact_first_name} {$c->contact_last_name}</td>
					<td>{$c->company_induction}</td>
					<td>{$c->passport_id_number}</td>
					<td>{$dt1}</td>
					<td>{$c->first_aid_course}</td>
					<td>{$c->working_at_heights}</td>
					<td>{$c->confined_spaces}</td>
					<td>{$c->driver_licence_details}</td>
					<td>{$dt2}</td>
					<td>{$c->other}</td>
					<td>{$c->no_of_years_in_job}</td>
					<td>{$c->determined_competency_rating}</td>
				</tr>
EOT;
;
		}
		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('Competency Register');
		$pdf->SetTopMargin(2);
		$pdf->setPrintHeader(false);
		$pdf->setMargins(8,8);
		$pdf->AddPage();
		$pdf->setFontSize(9);
		$html = <<<EOT
		<style>
		table{
			font-size: 8pt;
		}
		th{
			text-align: center;
			background-color: #EEE;
			border: 1px solid #EEEEE;
		}
		td{
			border: 1px solid #EEEEE;
			padding: 2px;
		}
		</style>
		<div style="width:100%;font-weight:bold;text-align:center;padding:10px 0;font-size:18pt;color:#FFFFFF;background-color:#000077">
				SAFETY TRAINING AND COMPETENCY REGISTER
		</div>
		<h2>{$c->company_name}</h2>


EOT;
		$html .= "<br><table>
					<thead>
					<tr>
					<th><br><br><br>Employee Name</th>
					<th><br><br><br>Company Induction</th>
					<th><br><br>Site Safe Passport ID Number</th>
					<th><br><br>Site Safety Passport (expiry date)</th>
					<th><br><br><br>First Aid Course</th>
					<th><br><br><br>Working at Heights</th>
					<th><br><br><br>Confined Spaces</th>
					<th><br><br><br>Driver's Licence Details</th>
					<th><br><br><br>D.L. Expiry Date</th>
					<th><br><br><br>Other</th>
					<th><br><br><br>No of Years in Job</th>
					<th style=\"font-size: 7pt;\">Determined Competency Rating<br>(Trainer, Competent, Require Supervision)</th>
					</tr>
					</thead>
					<tbody>
					{$tbl}
					</tbody>
					</table>";
		$pdf->writeHTML($html);
		$pdf->Output('safety-training-and-competency-register.pdf');

	}

	function competency_register($company_id){

		if ($company_id <=0){
			redirect('contact/contact_list');
		}
		$company = $this->company_model->get_company_detail($company_id)->row();
		      
		$data['title'] = 'Competency register for : '  . $company->company_name;
		$data['company_id']=$company_id;
		$data['company_title']=$company->company_name;
		$data['company'] = $company;
		$data['user_app_role']  = $this->user_app_role;
	   	$data['contacts'] = array();
	
	   	$contact_list = $this->company_model->get_contact_list($company_id);
	   	foreach ($contact_list as $contact){
	   		$data['contacts'][] = array(
		   		'name'=>$contact->contact_first_name.' '.$contact->contact_last_name,
		   		'title'=>$contact->contact_title,
		   		'file_name'=>$contact->file_name,
		   		'id'=>$contact->id
	   		);
		}
		  
		$data['maincontent']=$this->load->view('company/competency_register',$data,true);
		$this->load->view('includes/header', $data);
		$this->load->view('contact/contact_home',$data);
		$this->load->view('includes/footer', $data);	
	}

	function save_competency_registration($contact_id){
		$post = $this->input->post();	
		$company_id = $post['company_id'];	
		$has_cr_values = false;
		foreach($post['cr'] as $cr){
 			if(!empty($cr)){
				$has_cr_values = true;
				break;
			}
 		}

		if($post['cr_id'] && $has_cr_values){
			$this->db->where('id', $post['cr_id']);
			$this->db->update('contact_competency_register', $post['cr']);
 		}elseif($has_cr_values){
			$post['cr']['contact_id'] = $contact_id;
			$this->db->insert('contact_competency_register', $post['cr']);
		}

		redirect('company/competency_register/'.$company_id);
		
	}
	
   
	
         
        
   
    
	
}
