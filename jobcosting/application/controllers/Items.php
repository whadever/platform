<?php 
class Items extends CI_Controller {
	
	private $limit = 10;
	
	function __construct() 
	{
		parent::__construct();
		$this->load->model('items_model','',TRUE);
        $this->load->library(array('table','form_validation', 'session'));  
		$this->load->helper(array('form', 'url'));
        date_default_timezone_set("NZ");
		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user'))
		{	
			redirect($redirect_login_page,'refresh'); 		 
		}	
	}

	public function item()
	{
		$data['title'] = 'Items';
		
		if($this->input->post('next'))
		{
			if($this->input->post('items')=='2'){
				redirect('items/item_view');
			}else{
				redirect('items/item_create');
			}
		}
		$data['maincontent'] = $this->load->view('items/item',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function load_key_task()
	{
		$user = $this->session->userdata('user');		
		$_post = $this->input->post();
		
		if($this->input->post('submit'))
		{
			$template = $_post['template_id'];
			for($i=0; $i<count($template); $i++){
				$template_id = $template[$i];
				$this->db->where('template_id',$template_id);
				$this->db->where('type_of_task','key_task');
				$result = $this->db->get('construction_template_task')->result();
				foreach($result as $row)
	            {
					$add = array(
						'key_task_id' 		=> $row->id,
						'item_name' 		=> $row->task_name,
						'company_id'		=> $user->company_id			
					);
					$this->items_model->insert_item($add);
				}
			}
		}
		redirect('items/item_view');	
	}
	
	public function item_create()
	{
		$user = $this->session->userdata('user');
		$data['title'] = 'Create Item';
		
		$_post = $this->input->post();
		
		if($this->input->post('submit'))
		{
			$this->form_validation->set_rules('item_name','item name','trim|required');
            //$this->form_validation->set_rules('item_unit','item unit','trim|required');
			if($this->form_validation->run() == true)
            {
				//echo "su..re"; die;
				$add = array(
					'item_name' 		=> $_post['item_name'],
					'item_unit'			=> $_post['item_unit'],
					'item_price'			=> $_post['item_price'],
					'company_id'		=> $user->company_id			
				);
				$result = $this->items_model->insert_item($add);
				redirect('items/item_view');	
			}
		}
		
		$data['maincontent'] = $this->load->view('items/item_create',$data,true);
		
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function item_view()
	{
		$user = $this->session->userdata('user');
		
		$data['title'] = 'View / Edit Item';
		
		$data['items'] = $this->items_model->get_items()->result();
				
		$data['maincontent'] = $this->load->view('items/item_view',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function item_edit($id)
	{
		$data['title'] = 'Edit Item';
		
		if($this->input->post('submit')){
			$_post = $this->input->post();
			$this->form_validation->set_rules('item_name','item_name','trim|required');
			//$this->form_validation->set_rules('item_unit','item_unit','trim|required');
			if($this->form_validation->run() == true)
			{
				$add = array(
					'item_name'		=> $_post['item_name'],
					'item_unit'		=> $_post['item_unit'],
					'item_price'			=> $_post['item_price']
				);
				$this->items_model->item_update($id,$add);
                                $this->session->set_flashdata('success-message', 'Item Successfully Updated.');
				redirect('items/item_view');	
			}
		}
		
		$data['items'] = $this->items_model->get_items_id($id)->row();
	
		$data['maincontent'] = $this->load->view('items/item_create',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}

	public function item_delete($id){
                $this->session->set_flashdata('warning-message', 'Item Successfully Removed.');
		$this->items_model->delete_item($id);
		redirect('items/item_view');
	}
}
