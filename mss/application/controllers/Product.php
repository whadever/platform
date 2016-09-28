<?php 
class Product extends CI_Controller {  

    public function __construct()
    {
        parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation'));
		$this->load->model('Product_model','',TRUE);
        $this->load->library('session');
    }
	
    public function product_list()
    {	
		$get = $_POST;
        $data['title'] = 'Product & Warranties';
        $data['product_list'] = $this->Product_model->get_product_list($get)->result();

        $data['maincontent'] = $this->load->view('product/product', $data,true);

        $this->load->view('includes/header',$data);
        $this->load->view('includes/home',$data);
        $this->load->view('includes/footer',$data);
    }

	public function clear_search()
    {
        $this->session->unset_userdata('product_search');
		$this->session->unset_userdata('product_type');
		$this->session->unset_userdata('product_specifications');
    }
	
    public function product_add() {
                        
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
        
        $data['title'] = 'New Product & Warranties';
        $data['action'] = site_url('product/product_add/');


        if ( $this->input->post('submit') ) {

            $post = $this->input->post();
            $config['upload_path'] = UPLOAD_FILE_PATH_DOCUMENT;
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
                $document_insert_id = $this->Product_model->product_document_insert($document);                        
            }
            
			$document_insert_id_1 = 0;
            if ($this->upload->do_upload('upload_document_1')){
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
                $document_insert_id_1 = $this->Product_model->product_document_insert($document);                        
            }else{
					print 'Error in file uploading...'; 
                    print $this->upload->display_errors() ; 
					
			}

			$document_insert_id_2 = 0;
            if ($this->upload->do_upload('upload_document_2')){
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
                $document_insert_id_2 = $this->Product_model->product_document_insert($document);                        
            }else{
					print 'Error in file uploading...'; 
                    print $this->upload->display_errors() ;		
			}
			
			$upload_document_paint = 0;
            if ($this->upload->do_upload('upload_document_paint')){
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
                $upload_document_paint = $this->Product_model->product_document_insert($document);                        
            }else{
					print 'Error in file uploading...'; 
                    print $this->upload->display_errors() ;		
			}

            $product_data = array(
                    
                    'product_name' => $this->input->post('product_name'),
                    'product_type_id' =>$this->input->post('product_type_id'),	
                    'product_warranty_year' => $this->input->post('product_warranty_year'),

                    'product_warranty_month' => $this->input->post('product_warranty_month'),
                    
                    'change_color' => $this->input->post('change_color'),
                    
                    //'product_maintenance_year' => $this->input->post('product_maintenance_year'),                            
                    //'product_maintenance_month' => $this->input->post('product_maintenance_month'),  
                   
                    //'product_category_id' => $this->input->post('product_category_id'),  

                    'product_document_id' =>$document_insert_id,
					'product_document_id_1' =>$document_insert_id_1,
					'product_document_id_2' =>$document_insert_id_2,
					'product_document_paint' =>$upload_document_paint,

                    'product_specifications' => $this->input->post('product_specifications'), 
                    'look_while_maintaining' => $this->input->post('look_while_maintaining'), 
                    //'estimated_serviceable_life' => $this->input->post('estimated_serviceable_life'), 
                    //'description_of_maintenance' => $this->input->post('description_of_maintenance'),  
                   
                    'created' => date("Y-m-d H:i:s"),
                    'created_by' =>$user_id
                ); 
				
				$product_maintenance_year = $this->input->post('product_maintenance_year');
				$product_maintenance_month = $this->input->post('product_maintenance_month');
				$product_maintenance_week = $this->input->post('product_maintenance_week');
				$how_to_maintain = $this->input->post('description_of_maintenance');
				
				$product_id = $this->Product_model->product_save($product_data);
				
				
				$insert=array();
				for($i=0; $i<count($product_maintenance_year); $i++) {  
                   
					$insert[] = array(
						'product_id'=>$product_id,
						'product_maintenance_year'=>$product_maintenance_year[$i],
						'product_maintenance_month'=> $product_maintenance_month[$i],
						'product_maintenance_week'=> $product_maintenance_week[$i],
						'how_to_maintain'=> $how_to_maintain[$i]
					);
			  
				
                  
                }
				
				$this->Product_model->product_maintenance_data($insert); 		
                    
                redirect('product/product_list');
        } 
    }
    
    public function product_update($pid) {
            
     
         $data['title'] = 'Update Product & Warranties';
		$data['action'] = site_url('product/product_update/'.$pid);  
        
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid; 

		if ($this->input->post('submit')){

            $post = $this->input->post();
            $config['upload_path'] = UPLOAD_FILE_PATH_DOCUMENT;
            $config['allowed_types'] = '*';
			
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            
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
                $document_insert_id = $this->Product_model->product_document_insert($document);                        
            }else{
					
                $document_insert_id = $this->input->post('product_file_id');  
				
            } 

			if ($this->upload->do_upload('upload_document_1')){
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
                $document_insert_id_1 = $this->Product_model->product_document_insert($document);                        
            }else{
					
                $document_insert_id_1 = $this->input->post('product_file_id_1');  
				
            } 

			if ($this->upload->do_upload('upload_document_2')){
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
                $document_insert_id_2 = $this->Product_model->product_document_insert($document);                        
            }else{
					
                $document_insert_id_2 = $this->input->post('product_file_id_2');  
				
            } 
            
            if ($this->upload->do_upload('upload_document_paint')){
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
                $upload_document_paint = $this->Product_model->product_document_insert($document);                        
            }else{
					
                $upload_document_paint = $this->input->post('upload_document_paint_id');  
				
            } 

            $product_data = array(
                    
                    'product_name' => $this->input->post('product_name'),
                    'product_type_id' =>$this->input->post('product_type_id'),	
                    'product_warranty_year' => $this->input->post('product_warranty_year'),

                    'product_warranty_month' => $this->input->post('product_warranty_month'),
                    
                    'change_color' => $this->input->post('change_color'),
                    
                    //'product_maintenance_year' => $this->input->post('product_maintenance_year'),                            
                    //'product_maintenance_month' => $this->input->post('product_maintenance_month'),  
                   
                    //'product_category_id' => $this->input->post('product_category_id'),  

                    'product_document_id' =>$document_insert_id,
					'product_document_id_1' =>$document_insert_id_1,
					'product_document_id_2' =>$document_insert_id_2,
					'product_document_paint' =>$upload_document_paint,

                    'product_specifications' => $this->input->post('product_specifications'), 
                    'look_while_maintaining' => $this->input->post('look_while_maintaining')
                    //'estimated_serviceable_life' => $this->input->post('estimated_serviceable_life'), 
                    //'description_of_maintenance' => $this->input->post('description_of_maintenance')
                ); 
				
				
				$this->Product_model->product_update($pid, $product_data);
			   
				$product_maintenance_year = $this->input->post('product_maintenance_year');
				$product_maintenance_month = $this->input->post('product_maintenance_month');
				$product_maintenance_week = $this->input->post('product_maintenance_week');
				$how_to_maintain = $this->input->post('description_of_maintenance');
				$insert=array();
				for($i=0; $i<count($product_maintenance_year); $i++) {  
                   
					$insert[] = array(
						'product_id'=>$pid,
						'product_maintenance_year'=>$product_maintenance_year[$i],
						'product_maintenance_month'=> $product_maintenance_month[$i],
						'product_maintenance_week'=> $product_maintenance_week[$i],
						'how_to_maintain'=> $how_to_maintain[$i]
					);
			  
				
                  
                }
				
				$this->Product_model->update_product_maintenance_data($insert); 
			   
			   
               redirect('product/product_list');
                   
           } 
    }

    public function product_delete($pid){
		
		$this->Product_model->product_delete($pid);		
		redirect('product/product_list');
	}
    function _set_rules(){
            //$this->form_validation->set_rules('compname', 'compname', 'trim|required|is_unique[request_profile.compname]');
            $this->form_validation->set_rules('product_name', 'Product Name', 'required');
            //$this->form_validation->set_rules('assign_manager_id', 'Assign Manager', 'callback_assign_manager_id_check');
            //$this->form_validation->set_rules('request_no', 'Request No', 'required|min_length[5]|max_length[12]');
            //$this->form_validation->set_rules('email_addr_1', 'Email', 'required|valid_email|is_unique[request_profile.email_addr_1]');
        }


	public function product_type()
    {	
        $data['title'] = 'Product Types';
        $data['product_type'] = $this->Product_model->product_type_list()->result();

        $data['maincontent'] = $this->load->view('product/product_type', $data,true);

        $this->load->view('includes/header',$data);
        $this->load->view('includes/home',$data);
        $this->load->view('includes/footer',$data);
    }

	public function produt_type_add() 
	{
        $user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;
		
		if ( $this->input->post('submit')) 
		{
			$post = $this->input->post();	
			$add = array(
				'wp_company_id' => $wp_company_id,
				'product_type_name' => $post['product_type_name']
			);
			
			$this->Product_model->produt_type_add($add);
			redirect('product/product_type');  
	    }
			
	}

	public function produt_type_update($id) 
	{
        		
		if ( $this->input->post('submit')) 
		{
			$post = $this->input->post();	
			$update = array(
				'product_type_name' => $post['product_type_name']
			);
			
			$this->Product_model->produt_type_update($id,$update);
			redirect('product/product_type');  
	    }
			
	}

	public function product_document_delete()
	{	
		$post = $this->input->post();
		$file_1_2_3 = $post['file_1_2_3'];
		$file_id = $post['file_id'];
		$product_id = $post['product_id'];

		if($file_1_2_3==1)
		{	
			$update = array(
				'product_document_id' => 0
			);	
		}
		else if($file_1_2_3==2)
		{	
			$update = array(
				'product_document_id_1' => 0
			);	
		}
		else if($file_1_2_3==3)
		{	
			$update = array(
				'product_document_id_2' => 0
			);	
		}	
		$this->Product_model->product_update($product_id, $update);
	
		$this->Product_model->product_document_delete($file_id);

		redirect('product/product_list');		
	}

	public function product_type_delete($id)
	{		
		$this->Product_model->product_type_delete($id);		
	}

	public function product_report()
	{	
		$data['title'] = 'Products and Warranties Report';
		$data['reports'] = $this->Product_model->product_report()->result();
		$this->load->view('product/product_report', $data);    
	}

	public function document_load_report($id)
	{	
		$data['title'] = 'Products and Warranties Document Report';
		$data['report'] = $this->Product_model->document_load_report($id)->row();
		$this->load->view('product/document_load_report', $data);    
	}
	
	
}