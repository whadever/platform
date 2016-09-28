<?php 
class Contact extends CI_Controller {
	
	private $limit = 10;
	
	function __construct() {
		parent::__construct();
		$this->load->model('contact_model','',TRUE);
        $this->load->model('project_notes_model','',TRUE);
        $this->load->library(array('table','form_validation', 'session'));  
		$this->load->library('Wbs_helper');
        $this->load->library('breadcrumbs');
		$this->load->helper(array('form', 'url'));
        $this->load->helper('email');
        date_default_timezone_set("NZ");

		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		
		}
        
	}
        
	public function index(){
		$data['title'] = 'Contact';
		$data['maincontent'] = $this->load->view('contact/contact_list',$data,true);	
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
        
	public function contact_list($sort_by = 'id', $order_by = 'desc', $offset = 0){
		$project_name =  $this->input->post('project_name');
            if($this->input->post('project_sort')==''){
               $sort_by = 'project_name' ;
               $order_by = 'asc';
            }
            else if($this->input->post('project_sort')=='id'){
                $sort_by = 'id' ;
               $order_by = 'desc';
            }
            else{
                $sort_by = $this->input->post('project_sort') ;
                $order_by = 'asc';
            };
           
            $data['title'] = 'Contact';
            $data['sort_by']=$this->input->post('project_sort');
            $data['project_search_name']=$this->input->post('project_name');

            $user = $this->session->userdata('user');
            $user_id = $user->uid;
            $data['application_role']  = $this->getUserRole(4,$user_id)->application_role_id;

            
            $get = $_GET;
            $this->limit = 50;
            
            $contacts = $this->contact_model->get_contact_list($project_name, 'contact_first_name','asc',$offset,0,$get)->result();
            $data['contacts']=  $contacts;
            
            $data['maincontent'] = $this->load->view('contact/contact_list',$data,true);
		
            $this->load->view('includes/header',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
	}
	
	public function add_bulk_contact()
    {
        $user = $this->session->userdata('user');
        $user_id = $user->uid;
		$wp_company_id = $user->company_id;

        $data['title'] = 'Add Bulk Contact';
        $data['action'] = site_url('contact/add_bulk_contact');

        $this->_set_rules();

        if ($this->form_validation->run() === FALSE) {
        	
            $data['maincontent'] = $this->load->view('contact/add_bulk_contact', $data, true);
            $this->load->view('includes/header', $data);
            $this->load->view('home', $data);
            $this->load->view('includes/footer', $data);

        } else {

            $post = $this->input->post();
            
            $config['upload_path'] = UPLOAD_EXCEL;
            $config['allowed_types'] = '*';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('upload_excel')) {
                $upload_data = $this->upload->data();
                $excel_file = $upload_data['file_name'];
            }
            //print_r($excel_file); exit;
            $category_id = $this->input->post('category_id');
            $company_id = $this->input->post('company_id');
            
            //  Include PHPExcel_IOFactory
            include "application/libraries/third_party/PHPExcel/IOFactory.php";
			//include 'PHPExcel/IOFactory.php';

			$inputFileName = './uploads/excel/'.$excel_file;

			//  Read your Excel workbook
			try {
			    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
			    $objPHPExcel = $objReader->load($inputFileName);
			} catch(Exception $e) {
			    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
			}

			//  Get worksheet dimensions
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();

			//print_r($highestRow); exit;
			//  Loop through each row of the worksheet in turn
			//$rowData[] = array();
			for ($row = 2; $row <= $highestRow; $row++){ 
			    //  Read a row of data into an array
			    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
			                                    NULL,
			                                    TRUE,
			                                    FALSE);
			    
			    //print_r($rowData);
				for($i=0; $i<count($rowData); $i++){
					//print_r($rowData);exit;
				    //  Insert row data array into your database of choice here
				    //echo $rowData[$i][0];exit;
				    $data1['contact_title']		=	$rowData[$i][0];
					$data1['contact_first_name']		=	$rowData[$i][1];
					$data1['contact_last_name']	=	$rowData[$i][2];
					$data1['contact_phone_number']		=	$rowData[$i][3];
					$data1['contact_mobile_number']		=	$rowData[$i][4];
					$data1['contact_email']	=	$rowData[$i][5];
					$data1['contact_address']	=	$rowData[$i][6];
					$data1['contact_lbp_no']		=	$rowData[$i][7];					
					$data1['contact_city']		=	$rowData[$i][8];
					$data1['contact_country']		=	$rowData[$i][9];
					$data1['contact_website']		=	$rowData[$i][10];
					$data1['contact_notes']		=	$rowData[$i][11];
					
					$data1['category_id']	=	$category_id;
					$data1['company_id']	=	$company_id;
					$data1['wp_company_id']	=	$wp_company_id;
					$data1['status']	=	'1';
					
					$this->db->insert('contact_contact_list' , $data1);
				}
			}

            $this->session->set_flashdata('success-message', 'Contact Successfully Added');
            redirect('contact/contact_list');
        }
    }
	
	// company export to excel
	function contact_export_to_excel(){
		$user = $this->session->userdata('user');
		$user_id = $user->uid;
        $wp_company_id = $user->company_id;
		/*getting the data*/
		$this->db->select("contact_contact_list.*, contact_company.company_name, contact_company.company_website, contact_category.category_name");
		$this->db->join('contact_company', 'contact_contact_list.company_id = contact_company.id', 'left');  
		$this->db->join('contact_category', 'contact_contact_list.category_id = contact_category.id', 'left');             
		$this->db->where("contact_contact_list.status = 1");     
		$this->db->where("contact_contact_list.wp_company_id", $wp_company_id);
				
		$contacts = $this->db->get('contact_contact_list')->result();

		//load our new PHPExcel library
		$this->load->library('excel');
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);

		foreach(range('A','G') as $columnID) {
			$this->excel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		$active_sheet = $this->excel->getActiveSheet();
		//name the worksheet
		$active_sheet->setTitle('Company List');
		//set cell A1 content with some text
		$active_sheet->setCellValue('A1', 'Name(s)');
		$active_sheet->setCellValue('B1', 'Company Name');
		$active_sheet->setCellValue('C1', 'Title');
		$active_sheet->setCellValue('D1', 'Contact Number');
		$active_sheet->setCellValue('E1', 'Mobile Number');
		$active_sheet->setCellValue('F1', 'City');
		$active_sheet->setCellValue('G1', 'Email');
		//change the font size
		$active_sheet->getStyle('A1:G1')->getFont()->setSize(14);
		//make the font become bold
		$active_sheet->getStyle('A1:G1')->getFont()->setBold(true);

		$i = 2;
		foreach($contacts as $contact){            
			$active_sheet->setCellValue('A'.$i, $contact->contact_first_name.' '.$contact->contact_last_name);
			$active_sheet->setCellValue('B'.$i, $contact->company_name);
			$active_sheet->setCellValue('C'.$i, $contact->contact_title);
			$active_sheet->setCellValue('D'.$i, $contact->contact_phone_number);
			$active_sheet->setCellValue('E'.$i, $contact->contact_mobile_number);
			$active_sheet->setCellValue('F'.$i, $contact->contact_city);
			$active_sheet->setCellValue('G'.$i, $contact->contact_email);
			$i++;
		}
		$filename = 'contact_list.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache

		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');

	}
	
	public function contact_add($contact_id=0){

        $user=  $this->session->userdata('user');
        $user_id =$user->uid;
		$wp_company_id = $user->company_id;  

        if($contact_id != 0)
		{
            $appRole = $this->getUserRole(4,$user_id)->application_role_id;
            /*only admin can update*/
            if($appRole != 1){
                show_error('You are not allowed to edit this company.', 403);
            }

            $contact = $this->contact_model->get_contact_details($contact_id);
            $competency_register = $this->db->query("select * from contact_competency_register where contact_id = {$contact_id} limit 0,1")->row();
            $data['contact'] = $contact;
            $data['cr'] = (array)$competency_register;

        }
		$data['title'] = 'Add New Contact';
		$data['action'] = site_url('contact/contact_add/'.$contact_id);
        $this->_set_rules();   
 
		if($this->form_validation->run() === FALSE )
		{
			$data['maincontent'] = $this->load->view('contact/contact_add',$data,true);		
			$this->load->view('includes/header',$data);
			$this->load->view('home',$data);
			$this->load->view('includes/footer',$data);
		}
		else
		{

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
                $document_insert_id = $this->contact_model->file_insert($document);                        
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
                $image_insert_id = $this->contact_model->file_insert($image);                        
            }else{
                // print 'error in image uploading...'; 
                // print $this->upload->display_errors() ;  
            } 

			if( $image_insert_id == 0)
			{
				$image_insert_id = $this->input->post('image_id');
			}
              
                $contact_data = array(
					'wp_company_id' => $wp_company_id,
                    'contact_first_name' => $this->input->post('contact_first_name'),
                    'contact_last_name' => $this->input->post('contact_last_name'),
                    'company_id' =>$this->input->post('company_id'),	
                    'contact_phone_number' => $this->input->post('contact_phone_number'),
                    'contact_mobile_number' => $this->input->post('contact_mobile_number'),
                    'contact_email' => $this->input->post('contact_email'),
                    'category_id' => $this->input->post('category_id'),
                    'contact_title' => $this->input->post('contact_title'),                          
                    'contact_address' => $this->input->post('contact_address'),
                    'contact_city' => $this->input->post('contact_city'),  
                    'contact_country' => $this->input->post('contact_country'),  
					'contact_website' => $this->input->post('contact_website'),
                    'contact_image_id' =>$image_insert_id,  
					'status'  => '1',
					'contact_notes' => $this->input->post('contact_notes'),                
                    'created' => date("Y-m-d H:i:s"),
                    'created_by' =>$user_id
                );	
                $inserted_contact_id = $this->contact_model->contact_save($contact_data,$contact_id);

                /*adding / updating competency register*/
                $has_cr_values = false;
                foreach($post['cr'] as $cr){
                    if(!empty($cr)){
                        $has_cr_values = true;
                        break;
                    }
                }
                /*if in edit form all cr values are cleared, we will delete this*/
                if($post['cr_id'] && !$has_cr_values){

                    $this->db->delete('contact_competency_register', array('id' => $post['cr_id']));

                }elseif($post['cr_id'] && $has_cr_values){

                    $this->db->where('id', $post['cr_id']);
                    $this->db->update('contact_competency_register', $post['cr']);

                }elseif($has_cr_values){

                    $post['cr']['contact_id'] = ($contact_id) ? $contact_id : $inserted_contact_id;
                    $this->db->insert('contact_competency_register', $post['cr']);
                }
                $this->session->set_flashdata('success-message', 'Contact Successfully Added.');
                
				redirect('contact/contact_list');
                	
		} 
    }
	
	public function contact_list_delete($id){
		$this->contact_model->contact_list_delete($id);
		$this->session->set_flashdata('warning-message', 'Contact Successfully Removed.');
		redirect('contact/contact_list');
	}
	
	function _set_rules(){
            //$this->form_validation->set_rules('compname', 'compname', 'trim|required|is_unique[project_profile.compname]');
            //$this->form_validation->set_rules('project_id', 'Project Id', 'callback_project_id');
            $this->form_validation->set_rules('contact_position', 'Position');
           //$this->form_validation->set_rules('project_name', 'Project Name', 'required|min_length[5]|max_length[12]');
           // $this->form_validation->set_rules('email_addr_1', 'Email', 'required|valid_email|is_unique[project_profile.email_addr_1]');
	}
		
	function contact_details($contact_id=0){
		if ($contact_id <=0){
             redirect('contact/contact_list');
        }
        
        $contact = $this->contact_model->get_contact_details($contact_id);
		
        $data['title'] = 'Contact details for : '  . $contact->contact_first_name.' '.$contact->contact_last_name;
        $data['contact'] = $contact;
             
        $data['maincontent'] = $this->load->view('contact/contact_detail',$data,true);
        $this->load->view('includes/header', $data);
        $this->load->view('home',$data);
        $this->load->view('includes/footer', $data);
    }

	function get_contact_details($id){
		if($id){
			$data =  $this->db->query('SELECT category_id FROM contact_company where id = '.$id)->row();
			//$this->output->set_content_type('application/json')->set_output(json_encode($data));
			echo $data->category_id;
		}
	}

    function edit_note($cid){
        $note =  $this->input->post('note');
        $query = "update contact_contact_list set contact_notes = '{$note}' where id = {$cid}";
        $res = $this->db->simple_query($query);
        echo $res; exit;
    }

    private function getUserRole($appId, $uid){
        $query = "select application_role_id from users_application where user_id = {$uid} and application_id = {$appId} limit 0,1";
        return $this->db->query($query)->row();
    }
    
}
