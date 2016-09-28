<?php 
class Templates extends CI_Controller {
	
	private $limit = 10;
	
	function __construct() 
	{
		parent::__construct();
		$this->load->model('templates_model','',TRUE);
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

	public function template()
	{
		$data['title'] = 'Templates';
		
		if($this->input->post('next'))
		{
			if($this->input->post('templates')=='2'){
				redirect('templates/template_view');
			}else{
				redirect('templates/template_create');
			}
		}
		
		$data['maincontent'] = $this->load->view('templates/templates',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function ajax_template_save($template_name,$tem_id='')
	{
		$user = $this->session->userdata('user'); 
		$add = array(
			'job_name' 		=> urldecode($template_name),
			'company_id'	=> $user->company_id
		);
		if($tem_id==''){
			$this->db->insert('jobcosting_templates', $add);
			echo $this->db->insert_id();
		}else{
			$this->db->where('id',$tem_id);
			$this->db->update('jobcosting_templates', $add);
		}
	}

        public function template_delete($template_id){
		$this->db->where('id',$template_id);
		$this->db->delete('jobcosting_templates');

		redirect('templates/template_view');
	}
	
	public function add_category($tem_id='')
	{
		$user = $this->session->userdata('user'); 
		
		$_post = $this->input->post();
		
		if($tem_id==''){
			$template_id = $_post['template_id'];
		}else{
			$template_id = $tem_id;
		}
		if($template_id!=''){
			if($this->input->post('submit'))
			{

                                if(strcasecmp($_post['category_name'], "Tendering System") == 0)	
	                                {			
	                                    $this->session->set_flashdata('Failed', 'Cannot Make Category Name "Tendering System"');			
	                                    redirect('templates/template_view/' . $template_id);			
	                                }


				$url = $_post['url'];
				$c_add = array(
					'category_name' => $_post['category_name'],
					'template_id'	=> $template_id
				);
				$this->db->insert('jobcosting_templates_category', $c_add);
				redirect('templates/'.$url);
			}
		}
	}
	
	public function edit_category($cat_id)
	{
		$_post = $this->input->post();
		
		if($this->input->post('submit'))
		{
			$url = $_post['url'];
			$c_add = array(
				'category_name' => $_post['category_name']
			);
			$this->db->where('id',$cat_id);
			$this->db->update('jobcosting_templates_category', $c_add);
			redirect('templates/'.$url);
		}
	}
	
	public function template_category_delete($category_id,$tem_id,$status)
	{
		$this->db->where('template_category_id',$category_id);
		$this->db->delete('jobcosting_templates_items');
		
		$this->db->where('id',$category_id);
		$this->db->delete('jobcosting_templates_category');
		
		if($status=='1'){
			redirect('templates/template_create/'.$tem_id);
		}else{
			redirect('templates/template_view/'.$tem_id);
		}
	}
	
	public function template_item_delete($item_id,$tem_id,$status)
	{
		$this->db->where('id',$item_id);
		$this->db->delete('jobcosting_templates_items');
		
		if($status=='1'){
			redirect('templates/template_create/'.$tem_id);
		}else{
			redirect('templates/template_view/'.$tem_id);
		}
	}
	
	public function template_create($tem_id='')
	{
		$user = $this->session->userdata('user'); 
		$data['title'] = 'Create Template';
		
		$_post = $this->input->post();
		
		if($this->input->post('submit'))
		{
			redirect('templates/template');
		}
		
		$data['template'] = $this->db->get_where('jobcosting_templates',array('id'=>$tem_id))->row();

		$data['categorys'] = $this->templates_model->get_template_category($tem_id)->result();
		
		$data['maincontent'] = $this->load->view('templates/templates_create',$data,true);
		
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function add_item($template_id='')
	{
		$user = $this->session->userdata('user'); 
		
		$_post = $this->input->post();
		
		$url = $_post['url'];
		
		if($template_id!=''){
			if($this->input->post('add_item'))
			{
				$tem_category_id = $_post['category_id'];
				
				$items = $_post['items'];
				for($i = 0; $i < count($items); $i++){
					
					$item = $this->db->get_where('jobcosting_items',array('id'=>$items[$i]))->row();
					$key_task_id = $item->key_task_id;
					$item_name = $item->item_name;
					$item_unit = $item->item_unit;
					$item_price = $item->item_price;
					
					$i_add = array(
						'item_id' => $items[$i],
						'template_id' => $template_id,
						'template_category_id' => $tem_category_id,
						'key_task_id'	  => $key_task_id,
						'item_name'	  => $item_name,
						'item_unit'	  => $item_unit,
						'item_price'	  => $item_price
					);
					$this->templates_model->insert_template_item($i_add);
				}
				redirect('templates/'.$url.'/'.$template_id);
			}
		}	
	}
	
	public function template_view($tem_id = '')
	{
		$user = $this->session->userdata('user'); 
		$data['title'] = 'View / Edit';
		
		$_post = $this->input->post();
		
		if($this->input->post('submit'))
		{
			redirect('templates/template');
		}
		
		$data['categorys'] = $this->templates_model->get_template_category($tem_id)->result();
		
		$data['templates'] = $this->templates_model->get_template()->result();
		
		$data['maincontent'] = $this->load->view('templates/templates_view',$data,true);
		
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_note($tem_id)
	{
		$user = $this->session->userdata('user'); 
		$data['title'] = 'View / Edit';
		
		$_post = $this->input->post();
		
		if($this->input->post('submit'))
		{
			$t_add = array(
				'notes' 		=> $_post['notes']
			);
			$id = $this->templates_model->update_template($tem_id,$t_add);
			
			redirect('job');
		}
		
		$data['templates'] = $this->templates_model->get_template_id($tem_id)->row();
		
		$data['maincontent'] = $this->load->view('templates/templates_notes',$data,true);
		
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_category_ordering(){
		//$list $_GET['listItem_'.$phase_id];
		foreach ($_POST['listItemCategory'] as $position => $item){
            $sql = "UPDATE jobcosting_templates_category SET ordering=$position WHERE id=$item";
            $res = $this->db->query($sql);
        }
        return $res;
	}
	
	public function template_item_ordering(){
		//$list $_GET['listItem_'.$phase_id];
		foreach ($_POST['listItem'] as $position => $item){
            $sql = "UPDATE jobcosting_templates_items SET ordering=$position WHERE id=$item";
            $res = $this->db->query($sql);
        }
        return $res;
	}
	
	public function template_item_drag($category_id,$item_id)
	{	
		$iadd = array(
			'template_category_id' => $category_id
		);
		$item = explode('_',$item_id);
		$item_id = $item[1];
		$this->db->where('id',$item_id);
		$this->db->update('jobcosting_templates_items',$iadd);
	}
	
}
