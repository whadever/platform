<?php 
class Template extends CI_Controller {  

    public function __construct()
    {
        parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation'));
		$this->load->model('Template_model','',TRUE);
                $this->load->library('session');
    }
	
	public function template_list()
	{	
		$data['title'] = 'Template';
        $data['template_list'] = $this->Template_model->get_template_list()->result();
              
		$data['maincontent'] = $this->load->view('template/template', $data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('includes/home',$data);
		$this->load->view('includes/footer',$data);
	}

    public function template_add() {
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
        $wp_company_id = $user->company_id;

        $data['title'] = 'Add New Template';
        $data['action'] = site_url('template/template_new/');

        if ( $this->input->post('submit') ) {
                     
            $post = $this->input->post();	
            //print_r($select_developer_id); exit;
            	$template_data = array(
                    'wp_company_id' => $wp_company_id,         
                    'template_name' => $post['template_name'],    
                    'created' => date("Y-m-d H:i:s"),
                    'created_by' =>$user_id
                ); 
                
                $id = $this->Template_model->template_save($template_data);
                
			
                if(isset($post['product_id']))
                {
						
                        $product_ids = $post['product_id'];
                        
                        for($i = 0; $i < count($product_ids); $i++)
                        {
								$product_id = explode("#",$product_ids[$i]);
								$product_id = $product_id[2];
                                $template_product_add = array(
                                        'template_id' => $id,                                       
                                        'product_id' => $product_id,
                                        'ordering' => $i
                                );
                                $this->Template_model->template_product_add($template_product_add); 
                        }
                }

                redirect("template/template_list"); 
                   
           } 
        }
    public function template_update($id)
	{	
		$data['title'] = 'Edit Template';
		$user =  $this->session->userdata('user');
		if ( $this->input->post('submit')) 
		{
			$post = $this->input->post();	
			
			$template_update = array(
				
				'template_name' => $post['template_name'],
				
				'updated_by' => $user->uid
			);
			
			$this->Template_model->template_update($id,$template_update);			
			$this->Template_model->template_product_delete($id);
			
			if(isset($post['product_id']))
			{
				$product_ids = $post['product_id'];
				//print_r($product_ids); exit();
				for($i = 0; $i < count($product_ids); $i++)
				{
					$product_id = explode("#",$product_ids[$i]);
					$product_id = $product_id[2];
					$template_product_add = array(
						'template_id' => $id,						
						'product_id' => $product_id,
						'ordering' => $i
					);
					$this->Template_model->template_product_add($template_product_add); 
				}
			}   
			
			redirect("template/template_list"); 
	    }
	    
	}

    public function template_delete($id)
	{	
		$this->Template_model->template_delete($id);   
        redirect("template/template_list"); 
	}
    function _set_rules(){
        //$this->form_validation->set_rules('compname', 'compname', 'trim|required|is_unique[request_profile.compname]');
        $this->form_validation->set_rules('template_name', 'Template Name', 'required');
        //$this->form_validation->set_rules('assign_manager_id', 'Assign Manager', 'callback_assign_manager_id_check');
        //$this->form_validation->set_rules('request_no', 'Request No', 'required|min_length[5]|max_length[12]');
        //$this->form_validation->set_rules('email_addr_1', 'Email', 'required|valid_email|is_unique[request_profile.email_addr_1]');
    }
}