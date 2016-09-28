<?php
// main ajax back end
class Category extends CI_Controller {
  
	function __construct(){
        parent::__construct();
        $this->load->model('category_model','',TRUE);
        $this->load->library('breadcrumbs');
		$this->load->helper(array('form', 'url', 'file'));
        date_default_timezone_set("NZ");

		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		
		}
	}

	public function category_delete($id)
    {

        // delete company
        $this->category_model->category_delete($id);
        $this->session->set_flashdata('warning-message', 'Category Successfully Removed');
        // redirect to company list page
        redirect('category/category_list');
    }

	public function index($category_id='')
	{
		$data['title'] = 'Categories'; 
		$data['category_id']= $category_id;  
		$data['company_info']= $this->company_notes_model->getCompanyInfo($company_id);  
		$data['prev_notes'] = $this->notes_image_tmpl($prev_notes);
		$data['maincontent'] = $this->load->view('company_notes_view',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	} 
	public function category_list(){
		$data['title'] = 'Categories'; 
        $categories = $this->category_model->get_category_list()->result();
 		$data['categories']=  $categories; 

		$data['maincontent'] = $this->load->view('category/category_list',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);       
	}

	public function category_add()
	{
		$user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;

		$post = $this->input->post();
		$category_data = array(
			'wp_company_id' => $wp_company_id,
			'category_name' => $post['category_name'],
			'created' => date("Y-m-d H:i:s"),
			'status'    => 1
		);
		$this->category_model->category_save($category_data);
		redirect('category/category_list');
	}
	
	public function category_update($category_id)
	{
		$data['title'] = 'Category Update'; 
		$data['category'] = $this->category_model->get_category_details($category_id);

		$post = $this->input->post('submit');
		if(isset($post))
		{
			$category_data = array(
				'category_name' => $this->input->post('category_name')
			);
			$this->category_model->category_update($category_data,$category_id);
			redirect('category/category_list');
		}
	
		$data['maincontent'] = $this->load->view('category/category_update',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data); 

	}
	

}
