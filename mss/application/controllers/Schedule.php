<?php 
class Schedule extends CI_Controller {  

    public function __construct()
    {
        parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation','pagination'));
		$this->load->library('Pdf');
		$this->load->model('Schedule_model','',TRUE);
		$this->load->model('client_model','',TRUE);
    }

	public function load_product_template_by_all_product($tem_id){
		$user =  $this->session->userdata('user');
		$company_id = $user->company_id;

		$tem_product = $this->Schedule_model->load_product_id_by_template($tem_id)->result();

		$template_p_id = '';
		$i = 0;
		foreach($tem_product as $rows){
			if($i==count($tem_product)-1){
				$template_p_id .= $rows->product_id;
			}else{
				$template_p_id .= $rows->product_id.',';
			}
		$i++; 
		}
		$tem_product_id1 = explode(',',$template_p_id);

		$all_product = $this->Schedule_model->load_all_product($company_id)->result();

		$html = '<label for="">Additional Product(s):</label>';		
		$html .= '<select name="product_id[]" multiple="multiple" placeholder="--Select Product(s)--" class="form-control fSelect">';
		foreach($all_product as $row)
		{
			if(in_array($row->id,$tem_product_id1)){
				$disabled = 'disabled="disabled"'; 
			}else{
				$disabled = ''; 
			}
			$html .= '<option '.$disabled.' value="#'.$row->product_type_id.'#'.$row->id.'#'.$row->product_specifications.'">'.$row->product_name.'</option>';
		}
		$html .= '</select>';
	
		echo $html;
	
	}

	public function load_property_legal_description($id){
		$user =  $this->session->userdata('user');
		$company_id = $user->company_id;

		$this->db->where('job_number', $id);
		$this->db->where('wp_company_id', $company_id);
		$row = $this->db->get('clients')->row();
		echo json_encode((array)$row);		
	}

	public function load_product_template($tem_id){
		$this->Schedule_model->load_product_template($tem_id);			
	}

	public function duplicate()
	{
		$user =  $this->session->userdata('user');

		$post = $this->input->post();
		$sch_id = $post['sch_id'];
		$client_id = $post['client_id'];

		$row = $this->Schedule_model->schedule_load($sch_id); 
		$row_c = $this->Schedule_model->client_load($client_id); 

		$schedule_add = array(
			'template_id' => $row->template_id,
			'remove_products' => $row->remove_products,
			'client_id' => $client_id,
			'legal_description' => $row_c->legal_description,

			'corrosion_zone' => $row_c->corrosion_zone,
			'wind_zone' => $row_c->wind_zone,

			'designer_company_name' => $row->designer_company_name,
			'designer_phone_number' => $row->designer_phone_number,
			'designer_email_address' => $row->designer_email_address,

			//'internal_colours' => $row->internal_colours,
			//'external_colours' => $row->external_colours,
			//'plans' => $row->plans,

			'code_compliance_certificate' => $row->code_compliance_certificate,

			'created' => date('Y-m-d H:i:s'),
			'created_by' => $user->uid
		);
		
		$id = $this->Schedule_model->schedule_add($schedule_add); 

		$results = $this->Schedule_model->schedule_product_load($sch_id)->result();
                       
        foreach($results as $result)
        {
            $schedule_product_add = array(
               	'schedule_id' => $id,                                       
               	'product_id' => $result->product_id
            );
           	$this->Schedule_model->schedule_product_add($schedule_product_add); 
        }
		
		redirect("schedule/schedule_list"); 

	}

	public function archive_update(){
		$get = $_GET;
		$id = $get['id'];
		$job_id = $get['job_id'];
		$archive = array(
			'archive' => $get['value']
		);	
		$this->Schedule_model->schedule_update($id,$archive);
		$this->client_model->client_archive_update($job_id,$archive);			
	}
	
	public function schedule_list($sort_by='id',$order_by='desc',$offset='0')
	{
		$limit = 20;
			
		$user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;

		$schedule_search = $this->session->userdata('schedule_search');
		$data['title'] = 'Schedule';
		if($this->input->post('submit')){
			$schedule_name = $this->input->post('schedule_name');
			$sesData['schedule_search']=$schedule_name;
	        $this->session->set_userdata($sesData);
		}elseif(!empty($schedule_search)){
			$schedule_name = $schedule_search;			
		}else{
			$schedule_name = '';
		}

		$this->db->select("schedule.id,schedule.client_id,schedule.template_id,schedule.legal_description,schedule.corrosion_zone,schedule.wind_zone,schedule.designer_company_name,schedule.designer_phone_number,schedule.designer_email_address,schedule.remove_products,schedule.code_compliance_certificate_pdf,schedule.internal_colours,schedule.external_colours,schedule.plans,schedule.kitchen_plans,schedule.factory_order,schedule.job_specific_warranties,clients.note,clients.job_number,clients.address, d.filename as internal, e.filename as external, f.filename as plan, g.filename as kitchen, h.filename as factory, i.filename as job_specific, j.filename as code_compliance");
		
		$this->db->join('clients', 'clients.id = schedule.client_id', 'left');
		$this->db->join('file d', 'd.id = schedule.internal_colours', 'left');
		$this->db->join('file e', 'e.id = schedule.external_colours', 'left');
		$this->db->join('file f', 'f.id = schedule.plans', 'left');
		$this->db->join('file g', 'g.id = schedule.kitchen_plans', 'left');
		$this->db->join('file h', 'h.id = schedule.factory_order', 'left');
		$this->db->join('file i', 'i.id = schedule.job_specific_warranties', 'left');
		$this->db->join('file j', 'j.id = schedule.code_compliance_certificate_pdf', 'left');
		$this->db->where('schedule.archive', '0');
		$this->db->where('wp_company_id', $wp_company_id);

		//$this->db->like('job_number', $schedule_name);
		//$this->db->or_like('schedule_name', $schedule_name);
		//$this->db->or_like('full_address', $schedule_name);

		$where = "(job_number LIKE '%$schedule_name%' OR schedule_name LIKE '%$schedule_name%' OR address LIKE '%$schedule_name%')";
   		$this->db->where($where);

		$this->db->order_by("schedule.$sort_by", $order_by);
		$this->db->limit($limit,$offset);
		$data['rows'] = $this->db->get('schedule')->result();
		
		$config['base_url'] = site_url("schedule/schedule_list/$sort_by/$order_by");
		
		$this->db->select("schedule.id");
		$this->db->join('clients', 'clients.id = schedule.client_id', 'left');
		$this->db->where('schedule.archive', '0');
		$this->db->where('wp_company_id', $wp_company_id);
		$where = "(job_number LIKE '%$schedule_name%' OR schedule_name LIKE '%$schedule_name%' OR address LIKE '%$schedule_name%')";
   		$this->db->where($where);
		$rows_count = $this->db->get('schedule')->result();
		$config['total_rows'] = count($rows_count);

        $config['per_page'] = $limit;
		$config['uri_segment'] = 5;
        $config['num_links'] = 3;

		//$config['use_page_numbers'] = TRUE;
		$config['page_query_string'] = FALSE;

		$config['full_tag_open'] = '<ul class="pagination no-margin-top">';
	    $config['full_tag_close'] = '</ul><!--pagination-->';
	
	    $config['first_link'] = '&laquo; First';
	    $config['first_tag_open'] = '<li class="prev page">';
	    $config['first_tag_close'] = '</li>';
	
	    $config['last_link'] = 'Last &raquo;';
	    $config['last_tag_open'] = '<li class="next page">';
	    $config['last_tag_close'] = '</li>';
	
	    $config['next_link'] = 'Next &rarr;';
	    $config['next_tag_open'] = '<li class="next page">';
	    $config['next_tag_close'] = '</li>';
	
	    $config['prev_link'] = '&larr; Previous';
	    $config['prev_tag_open'] = '<li class="prev page">';
	    $config['prev_tag_close'] = '</li>';
	
	    $config['cur_tag_open'] = '<li class="active"><a href="">';
	    $config['cur_tag_close'] = '</a></li>';
	
	    $config['num_tag_open'] = '<li class="page">';
	    $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
 
		$data['maincontent'] = $this->load->view('schedule/schedule', $data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('includes/home',$data);
		$this->load->view('includes/footer',$data);
	}

	public function archive_list($sort_by='id',$order_by='desc',$offset='0')
	{
		$limit = 20;
		
		$user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;
	
		$schedule_search = $this->session->userdata('schedule_search1');
		$data['title'] = 'Schedule';
		if($this->input->post('submit')){
			$schedule_name = $this->input->post('schedule_name');
			$sesData['schedule_search1']=$schedule_name;
	        $this->session->set_userdata($sesData);
		}elseif(!empty($schedule_search)){
			$schedule_name = $schedule_search;			
		}else{
			$schedule_name = '';
		}

		$this->db->select("schedule.id,schedule.client_id,schedule.template_id,schedule.legal_description,schedule.corrosion_zone,schedule.wind_zone,schedule.designer_company_name,schedule.designer_phone_number,schedule.designer_email_address,schedule.remove_products,schedule.code_compliance_certificate_pdf,schedule.internal_colours,schedule.external_colours,schedule.plans,schedule.kitchen_plans,schedule.factory_order,schedule.job_specific_warranties,clients.job_number,clients.address, d.filename as internal, e.filename as external, f.filename as plan, g.filename as kitchen, h.filename as factory, i.filename as job_specific, j.filename as code_compliance");
		
		$this->db->join('clients', 'clients.id = schedule.client_id', 'left');
		$this->db->join('file d', 'd.id = schedule.internal_colours', 'left');
		$this->db->join('file e', 'e.id = schedule.external_colours', 'left');
		$this->db->join('file f', 'f.id = schedule.plans', 'left');
		$this->db->join('file g', 'g.id = schedule.kitchen_plans', 'left');
		$this->db->join('file h', 'h.id = schedule.factory_order', 'left');
		$this->db->join('file i', 'i.id = schedule.job_specific_warranties', 'left');
		$this->db->join('file j', 'j.id = schedule.code_compliance_certificate_pdf', 'left');
		$this->db->where('schedule.archive', '1');
		$this->db->where('wp_company_id', $wp_company_id);

		//$this->db->like('job_number', $schedule_name);
		//$this->db->or_like('schedule_name', $schedule_name);
		//$this->db->or_like('full_address', $schedule_name);

		$where = "(job_number LIKE '%$schedule_name%' OR schedule_name LIKE '%$schedule_name%' OR address LIKE '%$schedule_name%')";
   		$this->db->where($where);

		$this->db->order_by("schedule.$sort_by", $order_by);
		$this->db->limit($limit,$offset);
		$data['rows'] = $this->db->get('schedule')->result();
		
		$config['base_url'] = site_url("schedule/archive_list/$sort_by/$order_by");
		
		$this->db->select("schedule.id");
		$this->db->join('clients', 'clients.id = schedule.client_id', 'left');
		$this->db->where('schedule.archive', '1');
		$this->db->where('wp_company_id', $wp_company_id);
		$where = "(job_number LIKE '%$schedule_name%' OR schedule_name LIKE '%$schedule_name%' OR address LIKE '%$schedule_name%')";
   		$this->db->where($where);
		$rows_count = $this->db->get('schedule')->result();
		$config['total_rows'] = count($rows_count);

        $config['per_page'] = $limit;
		$config['uri_segment'] = 5;
        $config['num_links'] = 3;

		//$config['use_page_numbers'] = TRUE;
		$config['page_query_string'] = FALSE;

		$config['full_tag_open'] = '<ul class="pagination no-margin-top">';
	    $config['full_tag_close'] = '</ul><!--pagination-->';
	
	    $config['first_link'] = '&laquo; First';
	    $config['first_tag_open'] = '<li class="prev page">';
	    $config['first_tag_close'] = '</li>';
	
	    $config['last_link'] = 'Last &raquo;';
	    $config['last_tag_open'] = '<li class="next page">';
	    $config['last_tag_close'] = '</li>';
	
	    $config['next_link'] = 'Next &rarr;';
	    $config['next_tag_open'] = '<li class="next page">';
	    $config['next_tag_close'] = '</li>';
	
	    $config['prev_link'] = '&larr; Previous';
	    $config['prev_tag_open'] = '<li class="prev page">';
	    $config['prev_tag_close'] = '</li>';
	
	    $config['cur_tag_open'] = '<li class="active"><a href="">';
	    $config['cur_tag_close'] = '</a></li>';
	
	    $config['num_tag_open'] = '<li class="page">';
	    $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
 
		$data['maincontent'] = $this->load->view('schedule/archive', $data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('includes/home',$data);
		$this->load->view('includes/footer',$data);
	}

	public function archive_clear_search()
    {
        $this->session->unset_userdata('schedule_search1');
    }

	public function clear_search()
    {
        $this->session->unset_userdata('schedule_search');
    }

	public function generate_schedule($id)
	{	
		$user =  $this->session->userdata('user');
		$user_id =$user->uid; 
		if ( $this->input->post('submit')) 
		{
			$post = $this->input->post();

			$config['upload_path'] = UPLOAD_FILE_PATH_DOCUMENT;
            $config['allowed_types'] = '*';
			
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

			$code_compliance_certificate_pdf = $this->input->post('code_compliance_certificate_pdf_id');
            if ($this->upload->do_upload('code_compliance_certificate_pdf')){
                $upload_data = $this->upload->data();
                //print_r($upload_data); 
                // insert data to file table
                // get latest id from frim table and insert it to loan table
                $document = array(
                    'filename'=>$upload_data['file_name'],
                    'filetype'=>$upload_data['file_type'],
                    'filesize'=>$upload_data['file_size'],
                    'filepath'=>$upload_data['full_path'],
                    'created'=>date("Y-m-d H:i:s"),
                    'uid'=>$user_id
                );
                $code_compliance_certificate_pdf = $this->Schedule_model->schedule_document_insert($document);                        
            }

			$internal_colours = $this->input->post('internal_colours_id');
            if ($this->upload->do_upload('internal_colours')){
                $upload_data = $this->upload->data();
                //print_r($upload_data); 
                // insert data to file table
                // get latest id from frim table and insert it to loan table
                $document = array(
                    'filename'=>$upload_data['file_name'],
                    'filetype'=>$upload_data['file_type'],
                    'filesize'=>$upload_data['file_size'],
                    'filepath'=>$upload_data['full_path'],
                    'created'=>date("Y-m-d H:i:s"),
                    'uid'=>$user_id
                );
                $internal_colours = $this->Schedule_model->schedule_document_insert($document);                        
            }

			$external_colours = $this->input->post('external_colours_id');
            if ($this->upload->do_upload('external_colours')){
                $upload_data = $this->upload->data();
                //print_r($upload_data); 
                // insert data to file table
                // get latest id from frim table and insert it to loan table
                $document = array(
                    'filename'=>$upload_data['file_name'],
                    'filetype'=>$upload_data['file_type'],
                    'filesize'=>$upload_data['file_size'],
                    'filepath'=>$upload_data['full_path'],
                    'created'=>date("Y-m-d H:i:s"),
                    'uid'=>$user_id
                );
                $external_colours = $this->Schedule_model->schedule_document_insert($document);                        
            }

			$plans = $this->input->post('plans_id');
            if ($this->upload->do_upload('plans')){
                $upload_data = $this->upload->data();
                //print_r($upload_data); 
                // insert data to file table
                // get latest id from frim table and insert it to loan table
                $document = array(
                    'filename'=>$upload_data['file_name'],
                    'filetype'=>$upload_data['file_type'],
                    'filesize'=>$upload_data['file_size'],
                    'filepath'=>$upload_data['full_path'],
                    'created'=>date("Y-m-d H:i:s"),
                    'uid'=>$user_id
                );
                $plans = $this->Schedule_model->schedule_document_insert($document);                        
            }

			$job_specific_warranties = $this->input->post('job_specific_warranties_id');
            if ($this->upload->do_upload('job_specific_warranties')){
                $upload_data = $this->upload->data();
                //print_r($upload_data); 
                // insert data to file table
                // get latest id from frim table and insert it to loan table
                $document = array(
                    'filename'=>$upload_data['file_name'],
                    'filetype'=>$upload_data['file_type'],
                    'filesize'=>$upload_data['file_size'],
                    'filepath'=>$upload_data['full_path'],
                    'created'=>date("Y-m-d H:i:s"),
                    'uid'=>$user_id
                );
                $job_specific_warranties = $this->Schedule_model->schedule_document_insert($document);                        
            }

			$kitchen_plans = $this->input->post('kitchen_plans_id');
            if ($this->upload->do_upload('kitchen_plans')){
                $upload_data = $this->upload->data();
                $document = array(
                    'filename'=>$upload_data['file_name'],
                    'filetype'=>$upload_data['file_type'],
                    'filesize'=>$upload_data['file_size'],
                    'filepath'=>$upload_data['full_path'],
                    'created'=>date("Y-m-d H:i:s"),
                    'uid'=>$user_id
                );
                $kitchen_plans = $this->Schedule_model->schedule_document_insert($document);                        
            }

			$factory_order = $this->input->post('factory_order_id');
            if ($this->upload->do_upload('factory_order')){
                $upload_data = $this->upload->data();
                $document = array(
                    'filename'=>$upload_data['file_name'],
                    'filetype'=>$upload_data['file_type'],
                    'filesize'=>$upload_data['file_size'],
                    'filepath'=>$upload_data['full_path'],
                    'created'=>date("Y-m-d H:i:s"),
                    'uid'=>$user_id
                );
                $factory_order = $this->Schedule_model->schedule_document_insert($document);                        
            }

			$schedule_update = array(
				'code_compliance_certificate_pdf' =>$code_compliance_certificate_pdf,
				'internal_colours' =>$internal_colours,
				'external_colours' =>$external_colours,
				'plans' =>$plans,
				'kitchen_plans' =>$kitchen_plans,
				'factory_order' =>$factory_order,
				'job_specific_warranties' =>$job_specific_warranties
			);
			
			$this->Schedule_model->schedule_update($id,$schedule_update);
			if($internal_colours=='0' && $external_colours=='0' && $plans=='0' && $job_specific_warranties=='0'){
				echo 'Insert pdf file!';
			}else{
				echo 'File upload successfully.';
			}
			//echo $id; 
			//redirect("schedule/schedule_pdf/".$id); 
	    }

	}
	
	public function schedule_add()
	{	
		$data['title'] = 'New Schedule';
		$user =  $this->session->userdata('user');
		if ( $this->input->post('submit')) 
		{
			$post = $this->input->post();

			if($post['code_compliance_certificate']=='Issued'){
				$date_issued = date("Y-m-d", strtotime($post['date_issued']));
			}else{
				$date_issued = '0000-00-00';
			}
			$full_address = $post['number'].' '.$post['street'].' '.$post['suburb'].' '.$post['city'];
	
			if(isset($post['tem_product_id'])){
				$remove_products = implode(",", $post['tem_product_id']);
			}else{
				$remove_products = '';
			}
			$schedule_add = array(
				'template_id' => $post['template_id'],
				//'schedule_name' => $post['schedule_name'],
				'client_id' => $post['client_id'],
				'legal_description' => $post['legal_description'],
				//'number' => $post['number'],
				//'street' => $post['street'],
				//'suburb' => $post['suburb'],
				//'city' => $post['city'],
				//'full_address' => $full_address,

				'corrosion_zone' => $post['corrosion_zone'],
				'wind_zone' => $post['wind_zone'],

				'designer_company_name' => 'Horncastle Homes',
				'designer_phone_number' => $post['designer_phone_number'],
				'designer_email_address' => 'info@horncastle.co.nz',
				
				//'duilder_company_name' => $post['duilder_company_name'],
				//'duilder_email_address' => $post['duilder_email_address'],
				//'duilder_phone_number' => $post['duilder_phone_number'],

				//'licenced_building_practitioner' => $post['licenced_building_practitioner'],
				//'licence_class' => $post['licence_class'],
				//'licence_number' => $post['licence_number'],

				'code_compliance_certificate' => $post['code_compliance_certificate'],
				'date_issued' => $date_issued,

				'created' => date('Y-m-d H:i:s'),
				'created_by' => $user->uid,
				'remove_products' => $remove_products
			);
			
			$id = $this->Schedule_model->schedule_add($schedule_add);

			if(isset($post['product_id']))
            {
				$product_ids = $post['product_id'];      
                for($i = 0; $i < count($product_ids); $i++)
                {
                    $product_id = explode("#",$product_ids[$i]);
					$product_id = $product_id[2];

                    $schedule_product_add = array(
	                    'schedule_id' => $id,                                       
	                    'product_id' => $product_id
                    );
                    
                	$this->Schedule_model->schedule_product_add($schedule_product_add); 
                }
            }
			
			redirect("schedule/schedule_list"); 
	    }

	}

	public function schedule_update($id)
	{	
		$data['title'] = 'Edit Schedule';
		$user =  $this->session->userdata('user');
		if ( $this->input->post('submit')) 
		{
			$post = $this->input->post();

			if($post['code_compliance_certificate']=='Issued'){
				$date_issued = date("Y-m-d", strtotime($post['date_issued']));
			}else{
				$date_issued = '0000-00-00';
			}
			$full_address = $post['number'].' '.$post['street'].' '.$post['suburb'].' '.$post['city'];

			$schedule_update = array(
				'template_id' => $post['template_id'],
				//'schedule_name' => $post['schedule_name'],
				'client_id' => $post['client_id'],
				'legal_description' => $post['legal_description'],
				//'number' => $post['number'],
				//'street' => $post['street'],
				//'suburb' => $post['suburb'],
				//'city' => $post['city'],
				//'full_address' => $full_address,

				'corrosion_zone' => $post['corrosion_zone'],
				'wind_zone' => $post['wind_zone'],

				//'designer_company_name' => $post['designer_company_name'],
				'designer_phone_number' => $post['designer_phone_number'],
				//'designer_email_address' => $post['designer_email_address'],
				
				//'duilder_company_name' => $post['duilder_company_name'],
				//'duilder_email_address' => $post['duilder_email_address'],
				//'duilder_phone_number' => $post['duilder_phone_number'],

				//'licenced_building_practitioner' => $post['licenced_building_practitioner'],
				//'licence_class' => $post['licence_class'],
				//'licence_number' => $post['licence_number'],
				
				'code_compliance_certificate' => $post['code_compliance_certificate'],
				'date_issued' => $date_issued,

				'updated_by' => $user->uid,
				'remove_products' => implode(",", $post['tem_product_id'])
			);
			
			$this->Schedule_model->schedule_update($id,$schedule_update);

			$this->Schedule_model->schedule_product_delete($id);
			 
			if(isset($post['product_id']))
            {
				$product_ids = $post['product_id'];       
                for($i = 0; $i < count($product_ids); $i++)
                {
                    $product_id = explode("#",$product_ids[$i]);
					$product_id = $product_id[2];

                    $schedule_product_add = array(
	                    'schedule_id' => $id,                                       
	                    'product_id' => $product_id
                    );
                	$this->Schedule_model->schedule_product_add($schedule_product_add); 
                }
            }

			$url = $post['url'];

			redirect("schedule/".$url); 
	    }
	    
	}
	
	public function template_product_add()
	{

		$post = $_GET;	
		$data['title'] = 'Product Add';
		$user =  $this->session->userdata('user');
			
		$product_add = array(
			'product_name' => $post['product_name'],
			'product_type_id' => $post['product_type_id'],
			'product_warranty_year' => $post['product_warranty_year'],
			'product_maintenance_year' => $post['product_maintenance_period'],
			'description_of_maintenance' => $post['description'],
			'file_id' => $post['file_id'],
			'created' => date('Y-m-d H:i:s'),
			'created_by' => $user->uid
		);

		$id = $this->Schedule_model->product_add($product_add); 

		$product = $this->Schedule_model->single_product_load($id); 

		$ppp = '';
		$ppp .= '<tr id="'.$id.'" class="'.$id.'">';
		$ppp .= '<td class="res-hidden"><img src="'.base_url().'images/drag_drop.png" /><input type="hidden" name="product_id[]" value="'.$id.'"></td>';
		$ppp .= '<td>'.$product->product_name.'</td>';
		$ppp .= '<td>'.$product->product_type_name.'</td>';
		$ppp .= '<td>'.$product->product_warranty_year.' Year<br>'.$product->product_warranty_month.' Month</td>';
		$ppp .= '<td class="res-hidden">'.$product->product_maintenance_year.' Year<br>'.$product->product_maintenance_month.' Month</td>';
		$ppp .= '<td class="res-hidden">'.$product->description_of_maintenance.'</td>';
		if($product->file_id != '0')
		{
			$ppp .= '<td class="res-hidden"><img src="'.base_url().'images/output_file.png" /></td>';
		}
		else
		{
			$ppp .= '<td class="res-hidden">No<br>Document</td>';
		}
		$ppp .= '</tr>';

		echo $ppp;

	}
	
	public function product_load($template_id)
	{	
		$this->Schedule_model->product_load($template_id);    
	}
	
	public function product_load_drag($product_id)
	{	
		$this->Schedule_model->product_load_drag($product_id);    
	}
	
	public function schedule_delete($id)
	{	
		$this->Schedule_model->schedule_delete($id);    
	}
	public function ajax_product_file_upload()
	{	
		$status = "";
		$msg = "";
		$file_element_name = 'userfile';
     
		     
		if ($status != "error")
		{
			//$config['upload_path'] = './files/';
			$config['upload_path'] = UPLOAD_FILE_PATH_DOCUMENT;
			$config['allowed_types'] = 'gif|jpg|png|doc|txt';
			$config['max_size'] = 1024 * 8;
			$config['encrypt_name'] = TRUE;
	 
			$this->load->library('upload', $config);
	 
			if (!$this->upload->do_upload($file_element_name))
			{
				$status = 'error';
				$msg = $this->upload->display_errors('', '');
			}
			else
			{
				$data = $this->upload->data();
				$file_id = $this->schedule_model->insert_ajax_product_file($data['file_name']);
				if($file_id)
				{
					$status = "success";
					$msg = "File successfully uploaded";
				}
				else
				{
					unlink($data['full_path']);
					$status = "error";
					$msg = "Something went wrong when saving the file, please try again.";
				}
			}
			@unlink($_FILES[$file_element_name]);
		}
		echo json_encode(array('status' => $status, 'msg' => $msg)); 
	}
	
	public function ajax_existing_product_load()
	{
		$get = $_GET;	
		$this->Schedule_model->ajax_existing_product_load($get);    
	}

	public function schedule_pdf_delete($id,$fid,$field_name)
	{	
		if($field_name==1){
			$field_name = 'code_compliance_certificate_pdf';
		}elseif($field_name==2){
			$field_name = 'internal_colours';
		}elseif($field_name==3){
			$field_name = 'external_colours';
		}elseif($field_name==4){
			$field_name = 'plans';
		}elseif($field_name==5){
			$field_name = 'kitchen_plans';
		}elseif($field_name==6){
			$field_name = 'factory_order';
		}elseif($field_name==7){
			$field_name = 'job_specific_warranties';
		}
		$add = array(
			$field_name => '0'
		);  
		$this->Schedule_model->schedule_update($id,$add);   
	}

	public function schedule_report($id)
	{	
		$data['title'] = 'Schedule Report';
		$data['report'] = $this->Schedule_model->schedule_report($id);
		$this->load->view('schedule/schedule_report', $data);    
	}

	function schedule_pdf($id)
	{
		$report = $this->Schedule_model->schedule_report($id);
		$tem_id = $report->template_id;
		$remove_products = explode(',',$report->remove_products);
		
		$internal = $report->internal;
		$external = $report->external;
		$plan = $report->plan;
		$job_specific = $report->job_specific;
		$code_compliance = $report->code_compliance;
		$code_compliance_certificate_pdf = $report->code_compliance_certificate_pdf;

		if($report->date_issued=='0000-00-00')
		{
			$issued = 'No';
		}else{
			$issued = 'Issued '.date('d/m/Y', strtotime($report->date_issued));
		}
					
					
		
		
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('Schedule Report');
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

	// create some HTML content

	$html = '<h4>Overview</h4>
			<table border="0" cellspacing="3" cellpadding="4" width="100%">
				<tbody>
					<tr bgcolor="#0d446e">
						<td colspan="2" style="color:#fff;font-weight: bold;">Schedule Details</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4" style="width:40%">Job Number</td><td bgcolor="#e5ecf0" style="width:60%">'.$report->job_number.'</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4">Property Address</td><td bgcolor="#e5ecf0">'.$report->address.'</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4">Legal Description</td><td bgcolor="#e5ecf0">'.$report->legal_description.'</td>
					</tr>
					
					<tr>
						<td bgcolor="#b2c6d4">Code of compliance Certificate</td><td bgcolor="#e5ecf0">'.$issued.' </td>
					</tr>
				</tbody>
			</table>
			<br><br><table border="0" cellspacing="3" cellpadding="4" width="100%">
				<tbody>
					<tr bgcolor="#0d446e">
						<td colspan="2" style="color:#fff;font-weight: bold;">Zone Details</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4" style="width:40%">Corrosion Zone</td><td bgcolor="#e5ecf0" style="width:60%">'.$report->corrosion_zone.'</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4" style="width:40%">Wind Zone</td><td bgcolor="#e5ecf0">'.$report->wind_zone.'</td>
					</tr>
				</tbody>
			</table>
			<br><br><table border="0" cellspacing="3" cellpadding="4" width="100%">
				<tbody>
					<tr bgcolor="#0d446e">
						<td colspan="2" style="color:#fff;font-weight: bold;">Architect/Builder Details</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4" style="width:40%">Company Name</td><td bgcolor="#e5ecf0" style="width:60%">'.$report->designer_company_name.'</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4" style="width:40%">Phone number</td><td bgcolor="#e5ecf0">'.$report->designer_phone_number.'</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4" style="width:40%">Email address</td><td bgcolor="#e5ecf0">'.$report->designer_email_address.'</td>
					</tr>
				</tbody>
			</table>';

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');

		// reset pointer to the last page
		$pdf->lastPage();

		
		$reports = $this->Schedule_model->product_report($tem_id,$remove_products)->result();
		$reports_sp = $this->Schedule_model->schedule_product_report($id)->result();
		
		// add a page
		$pdf->AddPage();
		$html = '<h2>Products and Warranties</h2>';

		if($reports){

			foreach($reports as $report) {
				$p_id = $report->id;
				$file = $report->filename==''? 'No document': '<a href="#_'.$report->product_document_id.'">'.$report->filename.'</a>';
				$file_1 = $report->filename1==''? 'No document': '<a href="#_'.$report->product_document_id_1.'">'.$report->filename1.'</a>';
				$paint_file = $report->paint==''? 'No document': '<a href="#_'.$report->paint.'">'.$report->paint.'</a>';
				
				$product_warranty_year = $report->product_warranty_year=='0'? '': $report->product_warranty_year.' Years ';
				$product_warranty_month = $report->product_warranty_month=='0'? '': $report->product_warranty_month.' Months';
				
					
				$html .='<table style="padding:50%;" border="0" cellspacing="3" cellpadding="4" width="100%" nobr="true">
							<tbody>
								<tr bgcolor="#0d446e">
									<td colspan="2" style="color:#fff;font-weight: bold;">'.$report->product_name.'</td>
								</tr>
								<tr>
									<td bgcolor="#b2c6d4" style="width:40%">Product Type:</td><td bgcolor="#e5ecf0"  style="width:60%">'.$report->product_type_name.'</td>
								</tr>
								<tr>
									<td bgcolor="#b2c6d4" style="width:40%">Product Warranty:</td><td bgcolor="#e5ecf0">'.$product_warranty_year.''.$product_warranty_month.'</td>
								</tr>';
				if($report->look_while_maintaining!=''){
					$html .='<tr>
							<td bgcolor="#b2c6d4" style="width:40%">What to look for while maintaining:</td><td bgcolor="#e5ecf0">'.$report->look_while_maintaining.'</td>
						</tr>';	
				}
				
	
				$reports_m = $this->Schedule_model->product_report_maintenance($p_id)->result();
				
				foreach($reports_m as $report) {
	
						$product_maintenance_year = $report->product_maintenance_year=='0'? '': $report->product_maintenance_year.' Years ';
						$product_maintenance_month = $report->product_maintenance_month=='0'? '': $report->product_maintenance_month.' Months';
						$product_maintenance_week = $report->product_maintenance_week=='0'? '': $report->product_maintenance_week.' Weeks';
						if($product_maintenance_year!='' || $product_maintenance_month!='' || $product_maintenance_week!=''){
						 	$html .='<tr>
									<td bgcolor="#b2c6d4" style="width:40%">Maintenance Period:</td><td bgcolor="#e5ecf0">'.$product_maintenance_year.''.$product_maintenance_month.''.$product_maintenance_week.'</td>
									</tr>';
						}
						if($report->how_to_maintain!=''){
							$html .='<tr>
										<td bgcolor="#b2c6d4" style="width:40%">How to Maintain:</td><td bgcolor="#e5ecf0">'.$report->how_to_maintain.'</td>
									</tr>';	
						}	
					}
					
				if($file!='No document'){
					$html .='<tr>
								<td bgcolor="#b2c6d4" style="width:40%">Document Reference 1:</td><td bgcolor="#e5ecf0">'.$file.'</td>
							</tr>';
				}
				if($file_1!='No document'){
					$html .='<tr>
								<td bgcolor="#b2c6d4" style="width:40%">Document Reference 2:</td><td bgcolor="#e5ecf0">'.$file_1.'</td>
							</tr>';
				}
				if($paint_file!='No document'){
					$html .='<tr>
								<td bgcolor="#b2c6d4" style="width:40%">Document Reference 3:</td><td bgcolor="#e5ecf0">'.$paint_file.'</td>
							</tr>';
				}
				
				$html .='</tbody></table>';
				$html .='<br><br>';
			}
	
		}

		
		if($reports_sp){

			foreach($reports_sp as $report) {
				
				//$links[$report->id] = $pdf->AddLink();
				//$pdf->Write(2,$report->filename,$links[$report->id]);
				//$pdf->Ln();
				$p_id = $report->id;
				$file = $report->filename==''? 'No document': '<a href="#_'.$report->product_document_id.'">'.$report->filename.'</a>';
				$file_1 = $report->filename1==''? 'No document': '<a href="#_'.$report->product_document_id_1.'">'.$report->filename1.'</a>';
				$paint_file = $report->paint==''? 'No document': '<a href="#_'.$report->paint.'">'.$report->paint.'</a>';
				
				$product_warranty_year = $report->product_warranty_year=='0'? '': $report->product_warranty_year.' Years ';
				$product_warranty_month = $report->product_warranty_month=='0'? '': $report->product_warranty_month.' Months';
				
					
				$html .='<table style="padding:50%;" border="0" cellspacing="3" cellpadding="4" width="100%" nobr="true">
							<tbody>
								<tr bgcolor="#0d446e">
									<td colspan="2" style="color:#fff;font-weight: bold;">'.$report->product_name.'</td>
								</tr>
								<tr>
									<td bgcolor="#b2c6d4" style="width:40%">Product Type:</td><td bgcolor="#e5ecf0"  style="width:60%">'.$report->product_type_name.'</td>
								</tr>
								<tr>
									<td bgcolor="#b2c6d4" style="width:40%">Product Warranty:</td><td bgcolor="#e5ecf0">'.$product_warranty_year.''.$product_warranty_month.'</td>
								</tr>';
				if($report->look_while_maintaining!=''){
					$html .='<tr>
							<td bgcolor="#b2c6d4" style="width:40%">What to look for while maintaining:</td><td bgcolor="#e5ecf0">'.$report->look_while_maintaining.'</td>
						</tr>';	
				}
	
				$reports_m = $this->Schedule_model->product_report_maintenance($p_id)->result();
				
				foreach($reports_m as $report) {
	
					$product_maintenance_year = $report->product_maintenance_year=='0'? '': $report->product_maintenance_year.' Years ';
					$product_maintenance_month = $report->product_maintenance_month=='0'? '': $report->product_maintenance_month.' Months';
					$product_maintenance_week = $report->product_maintenance_week=='0'? '': $report->product_maintenance_week.' Weeks';
					if($product_maintenance_year!='' || $product_maintenance_month!='' || $product_maintenance_week!=''){
						 $html .='<tr>
									<td bgcolor="#b2c6d4" style="width:40%">Maintenance Period:</td><td bgcolor="#e5ecf0">'.$product_maintenance_year.''.$product_maintenance_month.''.$product_maintenance_week.'</td>
								</tr>';
						}
					if($report->how_to_maintain!=''){
						$html .='<tr>
									<td bgcolor="#b2c6d4" style="width:40%">How to Maintain:</td><td bgcolor="#e5ecf0">'.$report->how_to_maintain.'</td>
								</tr>';	
					}		
				}
				
				if($file!='No document'){
					$html .='<tr>
								<td bgcolor="#b2c6d4" style="width:40%">Document Reference 1:</td><td bgcolor="#e5ecf0">'.$file.'</td>
							</tr>';
				}
				if($file_1!='No document'){
					$html .='<tr>
								<td bgcolor="#b2c6d4" style="width:40%">Document Reference 2:</td><td bgcolor="#e5ecf0">'.$file_1.'</td>
							</tr>';
				}
				if($paint_file!='No document'){
					$html .='<tr>
								<td bgcolor="#b2c6d4" style="width:40%">Document Reference 3:</td><td bgcolor="#e5ecf0">'.$paint_file.'</td>
							</tr>';
				}		
				$html .='</tbody></table>';
				$html .='<br><br>';
			}
			
		}
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		
		// Image show
		$pdf->AddPage();
		//$pdf->setSourceFile('images/img_pdf.pdf');
		// import page 1
		//$tplIdx = $pdf->importPage(1);
		// use the imported page and place it at point 10,10 with a width of 100 mm
		//$pdf->useTemplate($tplIdx, 15, 0, 180);
		// now write some text above the imported page
		$text = '<h2 style="margin-top:-20px;" id="">Supporting Product Warranty <br>and Maintenance Documents </h2>';
		$pdf->SetFont('Helvetica');
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetXY(30, 12);
		$pdf->writeHTML($text);

		$pdf->lastPage();
		
		$total_page_number = '';
		$total_page_number_file = '';
		$total_page_number_paint = '';
		foreach($reports as $report) {
			if($report->filename!=''){
				try{

					$file = '<h2 style="margin-top:-20px;">'.$report->filename.'</h2>';
					// get external file content					
	 				$pageCount = $pdf->setSourceFile('uploads/document/'.$report->filename); 
	
					for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					    // import a page
					    $tplIdx = $pdf->importPage($pageNo);
					    // get the size of the imported page
					    $size = $pdf->getTemplateSize($tplIdx);
					
					    if ($size['w'] > $size['h']) {
							$pdf->AddPage('P',array('210', $size['w']));
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id);
								$page_number = $pdf->PageNo();
								$total_page_number .= $report->filename.'#'.$page_number.'##';
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 12);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->rotate(90);
							$pdf->useTemplate($tplIdx, -180, 25, 180);
							$pdf->rotate(270);
							
					    } else {
					        $pdf->AddPage('P',array('210', $size['h']));
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id);
								$page_number = $pdf->PageNo();
								$total_page_number .= $report->filename.'#'.$page_number.'##';
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 12);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->useTemplate($tplIdx, 15, 25, 180);
					    }
						
						$pdf->lastPage();
					}
				} catch (Exception $e) {
                     
                }				
			}

			if($report->filename1!=''){
				try{

					$file = '<h2 style="margin-top:-20px;">'.$report->filename1.'</h2>';
					// get external file content					
	 				$pageCount = $pdf->setSourceFile('uploads/document/'.$report->filename1); 
	
					for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					    // import a page
					    $tplIdx = $pdf->importPage($pageNo);
					    // get the size of the imported page
					    $size = $pdf->getTemplateSize($tplIdx);
					
					    if ($size['w'] > $size['h']) {
							$pdf->AddPage('P',array('210', $size['w']));
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id_1);
								$page_number = $pdf->PageNo();
								$total_page_number_file .= $report->filename1.'#'.$page_number.'##';
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 12);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->rotate(90);
							$pdf->useTemplate($tplIdx, -180, 25, 180);
							$pdf->rotate(270);
							
					    } else {
					        $pdf->AddPage('P',array('210', $size['h']));
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id_1);
								$page_number = $pdf->PageNo();
								$total_page_number_file .= $report->filename1.'#'.$page_number.'##';
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 12);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->useTemplate($tplIdx, 15, 25, 180);
					    }
						
						$pdf->lastPage();
					}
				} catch (Exception $e) {
                     
                }				
			}
			
			if($report->paint!=''){
				try{

					$file = '<h2 style="margin-top:-20px;">'.$report->paint.'</h2>';
					// get external file content					
	 				$pageCount = $pdf->setSourceFile('uploads/document/'.$report->paint); 
	
					for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					    // import a page
					    $tplIdx = $pdf->importPage($pageNo);
					    // get the size of the imported page
					    $size = $pdf->getTemplateSize($tplIdx);
					
					    if ($size['w'] > $size['h']) {
							$pdf->AddPage('P',array('210', $size['w']));
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_paint);
								$page_number = $pdf->PageNo();
								$total_page_number_paint .= $report->paint.'#'.$page_number.'##';
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 12);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->rotate(90);
							$pdf->useTemplate($tplIdx, -180, 25, 180);
							$pdf->rotate(270);
							
					    } else {
					        $pdf->AddPage('P',array('210', $size['h']));
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_paint);
								$page_number = $pdf->PageNo();
								$total_page_number_paint .= $report->paint.'#'.$page_number.'##';
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 12);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->useTemplate($tplIdx, 15, 25, 180);
					    }
						
						$pdf->lastPage();
					}
				} catch (Exception $e) {
                     
                }				
			}
		}
		
		$total_page_number1 = '';
		$total_page_number1_file = '';
		$total_page_number_paint1 = '';
		foreach($reports_sp as $report) {
			if($report->filename!=''){				
				try{
					//$html .= '<a href="'.base_url().'uploads/document/'.$report->filename.'">hare</a>';
					$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$report->filename.'</h2>';
										
					// get external file content
					$pageCount = $pdf->setSourceFile('uploads/document/'.$report->filename);
	                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					    // import a page
					    $tplIdx = $pdf->importPage($pageNo);
					    // get the size of the imported page
					    $size = $pdf->getTemplateSize($tplIdx);
					
					 	if ($size['w'] > $size['h']) {
						
							$pdf->AddPage('P',array('210', $size['w']));
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id);
								$page_number = $pdf->PageNo();
								$total_page_number1 .= $report->filename.'#'.$page_number.'##';
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 12);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->rotate(90);
							$pdf->useTemplate($tplIdx, -180, 25, 180);
							$pdf->rotate(270);	
	
					    } else {
					        $pdf->AddPage('P',array('210', $size['h']));
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id);
								$page_number = $pdf->PageNo();
								$total_page_number1 .= $report->filename.'#'.$page_number.'##';
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 12);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->useTemplate($tplIdx, 15, 25, 180);
					    }
						
						$pdf->lastPage();
					}
                } catch (Exception $e) {
                     
                }				
			}

			if($report->filename1!=''){				
				try{
					//$html .= '<a href="'.base_url().'uploads/document/'.$report->filename1.'">hare</a>';
					$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$report->filename1.'</h2>';
										
					// get external file content
					$pageCount = $pdf->setSourceFile('uploads/document/'.$report->filename1);
	                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					    // import a page
					    $tplIdx = $pdf->importPage($pageNo);
					    // get the size of the imported page
					    $size = $pdf->getTemplateSize($tplIdx);
					
					 	if ($size['w'] > $size['h']) {
						
							$pdf->AddPage('P',array('210', $size['w']));
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id_1);
								$page_number = $pdf->PageNo();
								$total_page_number1_file .= $report->filename1.'#'.$page_number.'##';
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 12);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->rotate(90);
							$pdf->useTemplate($tplIdx, -180, 25, 180);
							$pdf->rotate(270);	
	
					    } else {
					        $pdf->AddPage('P',array('210', $size['h']));
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id_1);
								$page_number = $pdf->PageNo();
								$total_page_number1_file .= $report->filename1.'#'.$page_number.'##';
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 12);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->useTemplate($tplIdx, 15, 25, 180);
					    }
						
						$pdf->lastPage();
					}
                } catch (Exception $e) {
                     
                }				
			}
			
			if($report->paint!=''){				
				try{
					//$html .= '<a href="'.base_url().'uploads/document/'.$report->filename1.'">hare</a>';
					$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$report->paint.'</h2>';
										
					// get external file content
					$pageCount = $pdf->setSourceFile('uploads/document/'.$report->paint);
	                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					    // import a page
					    $tplIdx = $pdf->importPage($pageNo);
					    // get the size of the imported page
					    $size = $pdf->getTemplateSize($tplIdx);
					
					 	if ($size['w'] > $size['h']) {
						
							$pdf->AddPage('P',array('210', $size['w']));
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_paint);
								$page_number = $pdf->PageNo();
								$total_page_number_paint1 .= $report->paint.'#'.$page_number.'##';
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 12);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->rotate(90);
							$pdf->useTemplate($tplIdx, -180, 25, 180);
							$pdf->rotate(270);	
	
					    } else {
					        $pdf->AddPage('P',array('210', $size['h']));
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_paint);
								$page_number = $pdf->PageNo();
								$total_page_number_paint1 .= $report->paint.'#'.$page_number.'##';
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 12);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->useTemplate($tplIdx, 15, 25, 180);
					    }
						
						$pdf->lastPage();
					}
                } catch (Exception $e) {
                     
                }				
			}
		}

		// Code of Compliance Certificate Document
		if($code_compliance!=''){
			try{
				//$html .= '<a href="'.base_url().'uploads/document/'.$internal.'">hare</a>';
				$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$code_compliance.'</h2>';
				
				// get external file content
				$pageCount = $pdf->setSourceFile('uploads/document/'.$code_compliance);
	
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
				    // import a page
				    $tplIdx = $pdf->importPage($pageNo);
				    // get the size of the imported page
				    $size = $pdf->getTemplateSize($tplIdx);
				
				   if ($size['w'] > $size['h']) {
						$pdf->AddPage('P',array('210', $size['w']));

						if($pageNo == 1){
							$pdf->setDestination("_".$code_compliance_certificate_pdf);
							$page_number = $pdf->PageNo();
							$total_page_code_compliance = $code_compliance.'#'.$page_number;
						}

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 12);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->rotate(90);
						$pdf->useTemplate($tplIdx, -180, 25, 180);
						$pdf->rotate(270);
				    } else {
				        $pdf->AddPage('P',array('210', $size['h']));

						if($pageNo == 1){
							$pdf->setDestination("_".$code_compliance_certificate_pdf);
							$page_number = $pdf->PageNo();
							$total_page_code_compliance = $code_compliance.'#'.$page_number;
						}

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 12);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->useTemplate($tplIdx, 15, 25, 180);
				    }
					
					$pdf->lastPage();
				}
			} catch (Exception $e) {
                     
            }
		}

		// Internal Color Document
		if($internal!=''){
			try{
				//$html .= '<a href="'.base_url().'uploads/document/'.$internal.'">hare</a>';
				$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$internal.'</h2>';
				
				// get external file content
				$pageCount = $pdf->setSourceFile('uploads/document/'.$internal);
	
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
				    // import a page
				    $tplIdx = $pdf->importPage($pageNo);
				    // get the size of the imported page
				    $size = $pdf->getTemplateSize($tplIdx);
				
				   if ($size['w'] > $size['h']) {
						$pdf->AddPage('P',array('210', $size['w']));

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 12);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->rotate(90);
						$pdf->useTemplate($tplIdx, -180, 25, 180);
						$pdf->rotate(270);
				    } else {
				        $pdf->AddPage('P',array('210', $size['h']));

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 12);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->useTemplate($tplIdx, 15, 25, 180);
				    }
					
					$pdf->lastPage();
				}
			} catch (Exception $e) {
                     
            }
		}

		// External Color Document
		if($external!=''){
			try{
				//$html .= '<a href="'.base_url().'uploads/document/'.$external.'">hare</a>';
				$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$external.'</h2>';
				
				// get external file content
				$pageCount = $pdf->setSourceFile('uploads/document/'.$external);
	
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
				    // import a page
				    $tplIdx = $pdf->importPage($pageNo);
				    // get the size of the imported page
				    $size = $pdf->getTemplateSize($tplIdx);
				
				    if ($size['w'] > $size['h']) {
						$pdf->AddPage('P',array('210', $size['w']));

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 12);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->rotate(90);
						$pdf->useTemplate($tplIdx, -180, 25, 180);
						$pdf->rotate(270);
				    } else {
				        $pdf->AddPage('P',array('210', $size['h']));

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 12);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->useTemplate($tplIdx, 15, 25, 180);
				    }
					
					$pdf->lastPage();
				}
			} catch (Exception $e) {
                     
            }
		}

		// Plan Document
		if($plan!=''){
			try{
				//$html .= '<a href="'.base_url().'uploads/document/'.$plan.'">hare</a>';
				$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$plan.'</h2>';
				
				// get external file content			
				$pageCount = $pdf->setSourceFile('uploads/document/'.$plan);
	
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

				    // import a page
				    $tplIdx = $pdf->importPage($pageNo);
				    // get the size of the imported page
				    $size = $pdf->getTemplateSize($tplIdx);
				
				    if ($size['w'] > $size['h']) {
						$pdf->AddPage('P',array('210', $size['w']));
						
						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 12);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->rotate(90);
						$pdf->useTemplate($tplIdx, -180, 25, 180);
						$pdf->rotate(270);
				    } else {
				        $pdf->AddPage('P',array('210', $size['h']));

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 12);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->useTemplate($tplIdx, 15, 25, 180);
				    }
									
					$pdf->lastPage();
				}
			} catch (Exception $e) {
                     
            }
		}

		// Job Specific Warranties Document
		if($job_specific!=''){
			try{
				//$html .= '<a href="'.base_url().'uploads/document/'.$plan.'">hare</a>';
				$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$job_specific.'</h2>';
	
				// get external file content			
				$pageCount = $pdf->setSourceFile('uploads/document/'.$job_specific);
	
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
				    // import a page
				    $tplIdx = $pdf->importPage($pageNo);
				    // get the size of the imported page
				    $size = $pdf->getTemplateSize($tplIdx);
				
				    // create a page (landscape or portrait depending on the imported page size)
				    if ($size['w'] > $size['h']) {
						$pdf->AddPage('P',array('210', $size['w']));

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 12);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->rotate(90);
						$pdf->useTemplate($tplIdx, -180, 25, 180);
						$pdf->rotate(270);
				    } else {
				        $pdf->AddPage('P',array('210', $size['h']));

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 12);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->useTemplate($tplIdx, 15, 25, 180);
				    }
					
					$pdf->lastPage();
				}
			} catch (Exception $e) {
                     
            }			
		}

		redirect("schedule/schedule_report_pdf?id=".$id.'&total_page_code_compliance='.urlencode($total_page_code_compliance).'&total_page_number='.urlencode($total_page_number).'&total_page_number_file='.urlencode($total_page_number_file).'&total_page_number_paint='.urlencode($total_page_number_paint).'&total_page_number1='.urlencode($total_page_number1).'&total_page_number1_file='.urlencode($total_page_number1_file).'&total_page_number_paint1='.urlencode($total_page_number_paint1)); 

		//Close and output PDF document
		$pdf->Output('Schedule_report.pdf', 'I');
	}

	function schedule_report_pdf()
	{
		$get = $_GET;
		$id = $get[id]; 
		$total_page_code_compliance = urldecode($get[total_page_code_compliance]);
		
		$total_page_number = urldecode($get[total_page_number]); $total_page_number1 = urldecode($get[total_page_number1]);
		$total_page_number_file = urldecode($get[total_page_number_file]); $total_page_number1_file = urldecode($get[total_page_number1_file]);
		$total_page_number_paint = urldecode($get[total_page_number_paint]); $total_page_number_paint1 = urldecode($get[total_page_number_paint1]);

		$report = $this->Schedule_model->schedule_report($id);
		$tem_id = $report->template_id;
		$remove_products = explode(',',$report->remove_products);

		$internal = $report->internal;
		$external = $report->external;
		$plan = $report->plan;
		$kitchen = $report->kitchen;
		$factory = $report->factory;
		$job_specific = $report->job_specific;
		$code_compliance = $report->code_compliance;
		$code_compliance_certificate_pdf = $report->code_compliance_certificate_pdf;

		$code_compliance_number = explode("#",$total_page_code_compliance);
		if($total_page_code_compliance!=''){
			if(in_array($code_compliance,$code_compliance_number)){
				$issued = 'Page # '.$code_compliance_number[1];
			}
		}else{
			$issued = '';
		}					
		
		
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('Schedule Report');
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

	// create some HTML content
	
	$user = $this->session->userdata('user');
	$wp_company_id = $user->company_id;
	
	$this->ums = $this->load->database('ums', TRUE);
	
	$this->ums->select("wp_company.*,wp_file.*");
	$this->ums->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
 	$this->ums->where('wp_company.id', $wp_company_id);	
	$wpdata = $this->ums->get('wp_company')->row();
	
	$colour_two = $wpdata->colour_one;

	$html = '<h4>Overview</h4>
			<table border="0" cellspacing="3" cellpadding="4" width="100%">
				<tbody>
					<tr bgcolor="'.$colour_two.'">
						<td colspan="2" style="color:#fff;font-weight: bold;">Schedule Details</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4" style="width:40%">Job Number</td><td bgcolor="#e5ecf0" style="width:60%">'.$report->job_number.'</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4">Property Address</td><td bgcolor="#e5ecf0">'.$report->address.'</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4">Legal Description</td><td bgcolor="#e5ecf0">'.$report->legal_description.'</td>
					</tr>
					
					<tr>
						<td bgcolor="#b2c6d4">Code of compliance Certificate</td><td bgcolor="#e5ecf0"><a href="#_'.$code_compliance_certificate_pdf.'">'.$issued.'</a></td>
					</tr>
				</tbody>
			</table>
			<br><br><table border="0" cellspacing="3" cellpadding="4" width="100%">
				<tbody>
					<tr bgcolor="'.$colour_two.'">
						<td colspan="2" style="color:#fff;font-weight: bold;">Zone Details</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4" style="width:40%">Corrosion Zone</td><td bgcolor="#e5ecf0" style="width:60%">'.$report->corrosion_zone.'</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4" style="width:40%">Wind Zone</td><td bgcolor="#e5ecf0">'.$report->wind_zone.'</td>
					</tr>
				</tbody>
			</table>
			<br><br><table border="0" cellspacing="3" cellpadding="4" width="100%">
				<tbody>
					<tr bgcolor="'.$colour_two.'">
						<td colspan="2" style="color:#fff;font-weight: bold;">Architect/Builder Details</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4" style="width:40%">Company Name</td><td bgcolor="#e5ecf0" style="width:60%">'.$report->designer_company_name.'</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4" style="width:40%">Phone number</td><td bgcolor="#e5ecf0">'.$report->designer_phone_number.'</td>
					</tr>
					<tr>
						<td bgcolor="#b2c6d4" style="width:40%">Email address</td><td bgcolor="#e5ecf0">'.$report->designer_email_address.'</td>
					</tr>
				</tbody>
			</table>';

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');

		// reset pointer to the last page
		$pdf->lastPage();

		
		$reports = $this->Schedule_model->product_report($tem_id,$remove_products)->result();
		$reports_sp = $this->Schedule_model->schedule_product_report($id)->result();
		
		// add a page
		$pdf->AddPage();
		$html = '<h2>Products and Warranties</h2>';

		if($reports){

			foreach($reports as $report) {

				$page_number = '';
				$total_page = explode("##",$total_page_number);
				for($i = 0; $i < count($total_page); $i++){
					$one_page = explode("#",$total_page[$i]);
					//print_r($one_page);exit;
					if(in_array($report->filename,$one_page)){
						$page_number = $one_page[1];
					}	
				}
				$page_number_file = '';
				$total_page_file = explode("##",$total_page_number_file);
				for($i = 0; $i < count($total_page_file); $i++){
					$one_page_file = explode("#",$total_page_file[$i]);
					//print_r($one_page);exit;
					if(in_array($report->filename1,$one_page_file)){
						$page_number_file = $one_page_file[1];
					}	
				}
				
				$total_page_paint = '';
				$total_number_paint = explode("##",$total_page_number_paint);
				for($i = 0; $i < count($total_number_paint); $i++){
					$one_page_file1 = explode("#",$total_number_paint[$i]);
					//print_r($one_page);exit;
					if(in_array($report->paint,$one_page_file1)){
						$total_page_paint = $one_page_file1[1];
					}	
				}

				$p_id = $report->id;
				$file = $report->filename==''? 'No document': '<a href="#_'.$report->product_document_id.'">Page # '.$page_number.'</a>';
				$file_1 = $report->filename1==''? 'No document': '<a href="#_'.$report->product_document_id_1.'">Page # '.$page_number_file.'</a>';
				$file_2 = $report->paint==''? 'No document': '<a href="#_'.$report->product_document_paint.'">Page # '.$total_page_paint.'</a>';
				
				$product_warranty_year = $report->product_warranty_year=='0'? '': $report->product_warranty_year.' Years ';
				$product_warranty_month = $report->product_warranty_month=='0'? '': $report->product_warranty_month.' Months';
				
					
				$html .='<table style="padding:50%;" border="0" cellspacing="3" cellpadding="4" width="100%" nobr="true">
							<tbody>
								<tr bgcolor="'.$colour_two.'">
									<td colspan="2" style="color:#fff;font-weight: bold;">'.$report->product_name.'</td>
								</tr>
								<tr>
									<td bgcolor="#b2c6d4" style="width:40%">Product Type:</td><td bgcolor="#e5ecf0"  style="width:60%">'.$report->product_type_name.'</td>
								</tr>
								<tr>
									<td bgcolor="#b2c6d4" style="width:40%">Product Warranty:</td><td bgcolor="#e5ecf0">'.$product_warranty_year.''.$product_warranty_month.'</td>
								</tr>';
				if($report->look_while_maintaining!=''){
					$html .='<tr>
							<td bgcolor="#b2c6d4" style="width:40%">What to look for while maintaining:</td><td bgcolor="#e5ecf0">'.$report->look_while_maintaining.'</td>
						</tr>';	
				}
	
					$reports_m = $this->Schedule_model->product_report_maintenance($p_id)->result();
					foreach($reports_m as $report) {
	
						$product_maintenance_year = $report->product_maintenance_year=='0'? '': $report->product_maintenance_year.' Years ';
						$product_maintenance_month = $report->product_maintenance_month=='0'? '': $report->product_maintenance_month.' Months';
						$product_maintenance_week = $report->product_maintenance_week=='0'? '': $report->product_maintenance_week.' Weeks';
						if($product_maintenance_year!='' || $product_maintenance_month!='' || $product_maintenance_week!=''){
						 	$html .='<tr>
									<td bgcolor="#b2c6d4" style="width:40%">Maintenance Period:</td><td bgcolor="#e5ecf0">'.$product_maintenance_year.''.$product_maintenance_month.''.$product_maintenance_week.'</td>
									</tr>';
						}
						if($report->how_to_maintain!=''){
							$html .='<tr>
										<td bgcolor="#b2c6d4" style="width:40%">How to Maintain:</td><td bgcolor="#e5ecf0">'.$report->how_to_maintain.'</td>
									</tr>';	
						}	
					}
				if($file!='No document'){
					$html .='<tr>
								<td bgcolor="#b2c6d4" style="width:40%">Document Reference 1:</td><td bgcolor="#e5ecf0">'.$file.'</td>
							</tr>';
				}
				if($file_1!='No document'){
					$html .='<tr>
								<td bgcolor="#b2c6d4" style="width:40%">Document Reference 2:</td><td bgcolor="#e5ecf0">'.$file_1.'</td>
							</tr>';
				}
				if($file_2!='No document'){
					$html .='<tr>
								<td bgcolor="#b2c6d4" style="width:40%">Document Reference 3:</td><td bgcolor="#e5ecf0">'.$file_2.'</td>
							</tr>';
				}
								
				$html .='</tbody></table>';
				$html .='<br><br>';
			}
	
		}

		

		if($reports_sp){

			foreach($reports_sp as $report) {
	
				$page_number = '';
				$total_page = explode("##",$total_page_number1);
				for($i = 0; $i < count($total_page); $i++){
					$one_page = explode("#",$total_page[$i]);
					//print_r($one_page);exit;
					if(in_array($report->filename,$one_page)){
						$page_number = $one_page[1];
					}	
				}

				$page_number_file = '';
				$total_page_file = explode("##",$total_page_number1_file);
				for($i = 0; $i < count($total_page_file); $i++){
					$one_page_file = explode("#",$total_page_file[$i]);
					//print_r($one_page);exit;
					if(in_array($report->filename1,$one_page_file)){
						$page_number_file = $one_page_file[1];
					}	
				}
				
				$total_page_paint1 = '';
				$total_number_paint1 = explode("##",$total_page_number_paint1);
				for($i = 0; $i < count($total_number_paint1); $i++){
					$one_page_file1 = explode("#",$total_number_paint1[$i]);
					//print_r($one_page);exit;
					if(in_array($report->paint,$one_page_file1)){
						$total_page_paint1 = $one_page_file1[1];
					}	
				}
				
				//$links[$report->id] = $pdf->AddLink();
				//$pdf->Write(2,$report->filename,$links[$report->id]);
				//$pdf->Ln();
				$p_id = $report->id;
				$file = $report->filename==''? 'No document': '<a href="#_'.$report->product_document_id.'">Page # '.$page_number.'</a>';
				$file_1 = $report->filename1==''? 'No document': '<a href="#_'.$report->product_document_id_1.'">Page # '.$page_number_file.'</a>';
				$file_2 = $report->paint==''? 'No document': '<a href="#_'.$report->product_document_paint.'">Page # '.$total_page_paint1.'</a>';
				
				$product_warranty_year = $report->product_warranty_year=='0'? '': $report->product_warranty_year.' Years ';
				$product_warranty_month = $report->product_warranty_month=='0'? '': $report->product_warranty_month.' Months';
				
					
				$html .='<table style="padding:50%;" border="0" cellspacing="3" cellpadding="4" width="100%" nobr="true">
							<tbody>
								<tr bgcolor="'.$colour_two.'">
									<td colspan="2" style="color:#fff;font-weight: bold;">'.$report->product_name.'</td>
								</tr>
								<tr>
									<td bgcolor="#b2c6d4" style="width:40%">Product Type:</td><td bgcolor="#e5ecf0"  style="width:60%">'.$report->product_type_name.'</td>
								</tr>
								<tr>
									<td bgcolor="#b2c6d4" style="width:40%">Product Warranty:</td><td bgcolor="#e5ecf0">'.$product_warranty_year.''.$product_warranty_month.'</td>
								</tr>';
				if($report->look_while_maintaining!=''){
					$html .='<tr>
							<td bgcolor="#b2c6d4" style="width:40%">What to look for while maintaining:</td><td bgcolor="#e5ecf0">'.$report->look_while_maintaining.'</td>
						</tr>';	
				}
	
				$reports_m = $this->Schedule_model->product_report_maintenance($p_id)->result();
				foreach($reports_m as $report) {
	
					$product_maintenance_year = $report->product_maintenance_year=='0'? '': $report->product_maintenance_year.' Years ';
					$product_maintenance_month = $report->product_maintenance_month=='0'? '': $report->product_maintenance_month.' Months';
					$product_maintenance_week = $report->product_maintenance_week=='0'? '': $report->product_maintenance_week.' Weeks';
					if($product_maintenance_year!='' || $product_maintenance_month!='' || $product_maintenance_week!=''){
						 $html .='<tr>
									<td bgcolor="#b2c6d4" style="width:40%">Maintenance Period:</td><td bgcolor="#e5ecf0">'.$product_maintenance_year.''.$product_maintenance_month.''.$product_maintenance_week.'</td>
								</tr>';
						}
					if($report->how_to_maintain!=''){
						$html .='<tr>
									<td bgcolor="#b2c6d4" style="width:40%">How to Maintain:</td><td bgcolor="#e5ecf0">'.$report->how_to_maintain.'</td>
								</tr>';	
					}		
				}
				if($file!='No document'){
					$html .='<tr>
								<td bgcolor="#b2c6d4" style="width:40%">Document Reference 1:</td><td bgcolor="#e5ecf0">'.$file.'</td>
							</tr>';
				}
				if($file_1!='No document'){
					$html .='<tr>
								<td bgcolor="#b2c6d4" style="width:40%">Document Reference 2:</td><td bgcolor="#e5ecf0">'.$file_1.'</td>
							</tr>';
				}
				if($file_2!='No document'){
					$html .='<tr>
								<td bgcolor="#b2c6d4" style="width:40%">Document Reference 3:</td><td bgcolor="#e5ecf0">'.$file_2.'</td>
							</tr>';
				}		
				
				$html .='</tbody></table>';
				$html .='<br><br>';
			}
			
		}
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		
		// Image show
		$pdf->AddPage();
		//$pdf->setSourceFile('images/img_pdf.pdf');
		// import page 1
		//$tplIdx = $pdf->importPage(1);
		// use the imported page and place it at point 10,10 with a width of 100 mm
		//$pdf->useTemplate($tplIdx, 15, 0, 180);
		// now write some text above the imported page
		$text = '<h2 style="margin-top:-20px;" id="">Supporting Product Warranty <br>and Maintenance Documents </h2>';
		$pdf->SetFont('Helvetica');
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetXY(30, 12);
		$pdf->writeHTML($text);

		$pdf->lastPage();
		
		foreach($reports as $report) {
			if($report->filename!=''){
				try{

					$file = '<h2 style="margin-top:-20px;">'.$report->filename.'</h2>';
					// get external file content					
	 				$pageCount = $pdf->setSourceFile('uploads/document/'.$report->filename); 
	
					for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					    // import a page
					    $tplIdx = $pdf->importPage($pageNo);
					    // get the size of the imported page
					    $size = $pdf->getTemplateSize($tplIdx);
					
					    if ($size['w'] > $size['h']) {
							$pdf->AddPage('P');
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id);
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 18);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							//$pdf->rotate(90);
							$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
							//$pdf->rotate(270);
							
					    } else {
					        $pdf->AddPage('P');
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id);
								$page_number = $pdf->PageNo();
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 18);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
					    }
						
						$pdf->lastPage();
					}
				} catch (Exception $e) {
                     
                }				
			}

			if($report->filename1!=''){
				try{

					$file = '<h2 style="margin-top:-20px;">'.$report->filename1.'</h2>';
					// get external file content					
	 				$pageCount = $pdf->setSourceFile('uploads/document/'.$report->filename1); 
	
					for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					    // import a page
					    $tplIdx = $pdf->importPage($pageNo);
					    // get the size of the imported page
					    $size = $pdf->getTemplateSize($tplIdx);
					
					    if ($size['w'] > $size['h']) {
							$pdf->AddPage('P');
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id_1);
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 18);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							//$pdf->rotate(90);
							$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
							//$pdf->rotate(270);
							
					    } else {
					        $pdf->AddPage('P');
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id_1);
								$page_number = $pdf->PageNo();
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 18);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
					    }
						
						$pdf->lastPage();
					}
				} catch (Exception $e) {
                     
                }				
			}
			
			if($report->paint!=''){
				try{

					$file = '<h2 style="margin-top:-20px;">'.$report->paint.'</h2>';
					// get external file content					
	 				$pageCount = $pdf->setSourceFile('uploads/document/'.$report->paint); 
	
					for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					    // import a page
					    $tplIdx = $pdf->importPage($pageNo);
					    // get the size of the imported page
					    $size = $pdf->getTemplateSize($tplIdx);
					
					    if ($size['w'] > $size['h']) {
							$pdf->AddPage('P');
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_paint);
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 18);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							//$pdf->rotate(90);
							$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
							//$pdf->rotate(270);
							
					    } else {
					        $pdf->AddPage('P');
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_paint);
								$page_number = $pdf->PageNo();
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 18);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
					    }
						
						$pdf->lastPage();
					}
				} catch (Exception $e) {
                     
                }				
			}
		}
		
		foreach($reports_sp as $report) {
			if($report->filename!=''){				
				try{
					//$html .= '<a href="'.base_url().'uploads/document/'.$report->filename.'">hare</a>';
					$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$report->filename.'</h2>';
										
					// get external file content
					$pageCount = $pdf->setSourceFile('uploads/document/'.$report->filename);
	                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					    // import a page
					    $tplIdx = $pdf->importPage($pageNo);
					    // get the size of the imported page
					    $size = $pdf->getTemplateSize($tplIdx);
					
					 	if ($size['w'] > $size['h']) {
						
							$pdf->AddPage('P');
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id);
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 18);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							//$pdf->rotate(90);
							$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
							//$pdf->rotate(270);	
	
					    } else {
					        $pdf->AddPage('P');
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id);
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 18);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
					    }
						
						$pdf->lastPage();
					}
                } catch (Exception $e) {
                     
                }				
			}

			if($report->filename1!=''){				
				try{
					//$html .= '<a href="'.base_url().'uploads/document/'.$report->filename.'">hare</a>';
					$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$report->filename1.'</h2>';
										
					// get external file content
					$pageCount = $pdf->setSourceFile('uploads/document/'.$report->filename1);
	                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					    // import a page
					    $tplIdx = $pdf->importPage($pageNo);
					    // get the size of the imported page
					    $size = $pdf->getTemplateSize($tplIdx);
					
					 	if ($size['w'] > $size['h']) {
						
							$pdf->AddPage('P');
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id_1);
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 18);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							//$pdf->rotate(90);
							$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
							//$pdf->rotate(270);	
	
					    } else {
					        $pdf->AddPage('P');
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_id_1);
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 18);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
					    }
						
						$pdf->lastPage();
					}
                } catch (Exception $e) {
                     
                }				
			}
			
			if($report->paint!=''){
				try{

					$file = '<h2 style="margin-top:-20px;">'.$report->paint.'</h2>';
					// get external file content					
	 				$pageCount = $pdf->setSourceFile('uploads/document/'.$report->paint); 
	
					for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					    // import a page
					    $tplIdx = $pdf->importPage($pageNo);
					    // get the size of the imported page
					    $size = $pdf->getTemplateSize($tplIdx);
					
					    if ($size['w'] > $size['h']) {
							$pdf->AddPage('P');
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_paint);
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 18);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							//$pdf->rotate(90);
							$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
							//$pdf->rotate(270);
							
					    } else {
					        $pdf->AddPage('P');
							if($pageNo == 1){
								$pdf->setDestination("_".$report->product_document_paint);
								$page_number = $pdf->PageNo();
							}
							// now write some text above the imported page
							$pdf->SetFont('Helvetica');
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetXY(30, 18);
							$pdf->writeHTML($file);
	
							// use the imported page and place it at point 10,10 with a width of 100 mm
							$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
					    }
						
						$pdf->lastPage();
					}
				} catch (Exception $e) {
                     
                }				
			}
		}

		// Code of Compliance Certificate Document
		if($code_compliance!=''){
			try{
				//$html .= '<a href="'.base_url().'uploads/document/'.$internal.'">hare</a>';
				$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$code_compliance.'</h2>';
				
				// get external file content
				$pageCount = $pdf->setSourceFile('uploads/document/'.$code_compliance);
	
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
				    // import a page
				    $tplIdx = $pdf->importPage($pageNo);
				    // get the size of the imported page
				    $size = $pdf->getTemplateSize($tplIdx);
				
				   if ($size['w'] > $size['h']) {
						$pdf->AddPage('P',array('210', $size['w']));
						if($pageNo == 1){
							$pdf->setDestination("_".$code_compliance_certificate_pdf);
						}
						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->rotate(90);
						$pdf->useTemplate($tplIdx, -180, 25, 180, 250);
						$pdf->rotate(270);
				    } else {
				        $pdf->AddPage('P',array('210', $size['h']));
						if($pageNo == 1){
							$pdf->setDestination("_".$code_compliance_certificate_pdf);
						}
						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
				    }
					
					$pdf->lastPage();
				}
			} catch (Exception $e) {
                     
            }
		}

		// Internal Color Document
		if($internal!=''){
			try{
				//$html .= '<a href="'.base_url().'uploads/document/'.$internal.'">hare</a>';
				$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$internal.'</h2>';
				
				// get external file content
				$pageCount = $pdf->setSourceFile('uploads/document/'.$internal);
	
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
				    // import a page
				    $tplIdx = $pdf->importPage($pageNo);
				    // get the size of the imported page
				    $size = $pdf->getTemplateSize($tplIdx);
				
				   if ($size['w'] > $size['h']) {
						$pdf->AddPage('P');

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						//$pdf->rotate(90);
						$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
						//$pdf->rotate(270);
				    } else {
				        $pdf->AddPage('P');

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
				    }
					
					$pdf->lastPage();
				}
			} catch (Exception $e) {
                     
            }
		}

		// External Color Document
		if($external!=''){
			try{
				//$html .= '<a href="'.base_url().'uploads/document/'.$external.'">hare</a>';
				$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$external.'</h2>';
				
				// get external file content
				$pageCount = $pdf->setSourceFile('uploads/document/'.$external);
	
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
				    // import a page
				    $tplIdx = $pdf->importPage($pageNo);
				    // get the size of the imported page
				    $size = $pdf->getTemplateSize($tplIdx);
				
				    if ($size['w'] > $size['h']) {
						$pdf->AddPage('P');

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						//$pdf->rotate(90);
						$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
						//$pdf->rotate(270);
				    } else {
				        $pdf->AddPage('P');

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
				    }
					
					$pdf->lastPage();
				}
			} catch (Exception $e) {
                     
            }
		}

		// Plan Document
		if($plan!=''){
			try{
				//$html .= '<a href="'.base_url().'uploads/document/'.$plan.'">hare</a>';
				$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$plan.'</h2>';
				
				// get external file content			
				$pageCount = $pdf->setSourceFile('uploads/document/'.$plan);
	
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

				    // import a page
				    $tplIdx = $pdf->importPage($pageNo);
				    // get the size of the imported page
				    $size = $pdf->getTemplateSize($tplIdx);
				
				    if ($size['w'] > $size['h']) {
						$pdf->AddPage('P');
						
						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						//$pdf->rotate(90);
						$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
						//$pdf->rotate(270);
				    } else {
				        $pdf->AddPage('P');

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
				    }
									
					$pdf->lastPage();
				}
			} catch (Exception $e) {
                     
            }
		}

		// Kitchen Plans Document
		if($kitchen!=''){
			try{
				//$html .= '<a href="'.base_url().'uploads/document/'.$plan.'">hare</a>';
				$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$kitchen.'</h2>';
				
				// get external file content			
				$pageCount = $pdf->setSourceFile('uploads/document/'.$kitchen);
	
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

				    // import a page
				    $tplIdx = $pdf->importPage($pageNo);
				    // get the size of the imported page
				    $size = $pdf->getTemplateSize($tplIdx);
				
				    if ($size['w'] > $size['h']) {
						$pdf->AddPage('P');
						
						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						//$pdf->rotate(90);
						$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
						//$pdf->rotate(270);
				    } else {
				        $pdf->AddPage('P');

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
				    }
									
					$pdf->lastPage();
				}
			} catch (Exception $e) {
                     
            }
		}

		// Factory Order Document
		if($factory!=''){
			try{
				//$html .= '<a href="'.base_url().'uploads/document/'.$plan.'">hare</a>';
				$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$factory.'</h2>';
				
				// get external file content			
				$pageCount = $pdf->setSourceFile('uploads/document/'.$factory);
	
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

				    // import a page
				    $tplIdx = $pdf->importPage($pageNo);
				    // get the size of the imported page
				    $size = $pdf->getTemplateSize($tplIdx);
				
				    if ($size['w'] > $size['h']) {
						$pdf->AddPage('P');
						
						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						//$pdf->rotate(90);
						$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
						//$pdf->rotate(270);
				    } else {
				        $pdf->AddPage('P');

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
				    }
									
					$pdf->lastPage();
				}
			} catch (Exception $e) {
                     
            }
		}

		// Job Specific Warranties Document
		if($job_specific!=''){
			try{
				//$html .= '<a href="'.base_url().'uploads/document/'.$plan.'">hare</a>';
				$file = '<h2 style="margin-top:-20px;" id="DocId_'.$report->id.'">'.$job_specific.'</h2>';
	
				// get external file content			
				$pageCount = $pdf->setSourceFile('uploads/document/'.$job_specific);
	
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
				    // import a page
				    $tplIdx = $pdf->importPage($pageNo);
				    // get the size of the imported page
				    $size = $pdf->getTemplateSize($tplIdx);
				
				    // create a page (landscape or portrait depending on the imported page size)
				    if ($size['w'] > $size['h']) {
						$pdf->AddPage('P');

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						//$pdf->rotate(90);
						$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
						//$pdf->rotate(270);
				    } else {
				        $pdf->AddPage('P');

						// now write some text above the imported page
						$pdf->SetFont('Helvetica');
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetXY(30, 18);
						$pdf->writeHTML($file);

						// use the imported page and place it at point 10,10 with a width of 100 mm
						$pdf->useTemplate($tplIdx, 15, 25, 180, 250);
				    }
					
					$pdf->lastPage();
				}
			} catch (Exception $e) {
                     
            }			
		}
		
		//Close and output PDF document
		$pdf->Output('Schedule_report.pdf', 'I');
	}
	
}