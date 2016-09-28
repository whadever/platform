<?php 
class Template extends CI_Controller {
	private $user_id, $wp_company_id, $user_app_role;
	function __construct() {		
		parent::__construct();		
		$this->load->helper(array('form', 'url', 'file'));
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->model('template_model','',TRUE);
		$this->load->library('Wbs_helper');
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
		$this->user_id = $user->uid;
		$this->wp_company_id = $user->company_id;

		/*only manager and admin will be able to access this controller's actions*/
		if($this->user_app_role != 'manager' && $this->user_app_role != 'admin'){
			exit;
		}
	}
	
	public function template_list() {
                
		$data['title'] = 'Templates';
		$get = $_GET;
		$data['templates'] = $this->template_model->template_list($get)->result();

				
		$data['template_content'] = $this->load->view('template/template_list',$data,true);
		//$this->load->view('includes/header',$data);
		//$this->load->view('template/template_sidebar',$data);
		$this->load->view('template/template_home',$data);
		//$this->load->view('includes/footer',$data);
	}
	public function tendering_template_list() {

		$data['title'] = 'Tendering Templates';
		$query = $this->db->query("SELECT temp.*, users.username FROM construction_tendering_templates temp LEFT JOIN users ON temp.created_by = users.uid WHERE wp_company_id = {$this->wp_company_id} ORDER BY temp.`id` DESC");

		$data['templates'] = $query->result();
		$data['template_sub_sidebar']=$this->load->view('template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('template/tendering_template_list',$data,true);
		//$this->load->view('includes/header',$data);
		$this->load->view('template/template_home',$data);
	}
	public function milestone_template_list() {

		$data['title'] = 'Milestone Templates';
		$query = $this->db->query("SELECT temp.*, users.username FROM construction_milestone_templates temp LEFT JOIN users ON temp.created_by = users.uid WHERE wp_company_id = {$this->wp_company_id} AND deleted = 0 ORDER BY temp.`id` DESC");

		$data['templates'] = $query->result();
		$data['template_sub_sidebar']=$this->load->view('template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('template/milestone_template_list',$data,true);
		//$this->load->view('includes/header',$data);
		$this->load->view('template/template_home',$data);
	}
	public function template_start() {
                
		$data['title'] = 'Add Template';
				
		$data['template_sub_sidebar']=$this->load->view('template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('template/template_start',$data,true);		
		//$this->load->view('includes/header',$data);
		//$this->load->view('template/template_sidebar',$data);
		$this->load->view('template/template_home',$data);
		//$this->load->view('includes/footer',$data);
	}
	public function tendering_template_start() {

		$data['title'] = 'Add Tendering Template';

		$data['template_sub_sidebar']= "";

		$data['template_content'] = $this->load->view('template/tendering_template_start',$data,true);

		$this->load->view('template/template_home',$data);
	}
	
	public function template_basic_info() { 
        $user=  $this->session->userdata('user');   
    
        $user_id =$user->uid;
		$wp_company_id =$user->company_id;
        
        $data['title'] = 'Add Template';
        $data['action'] = site_url('template/template_basic_info');
        $post = $this->input->post();
        $no_phases = $post['no_phases'];
        $no_tasks = $post['no_tasks'];
         if ( $this->input->post('submit')) {

			$template_basic_info = array(
				'template_name' => $post['template_name'],
				'wp_company_id' => $wp_company_id,

				'created'=>date("Y-m-d H:i:s"),
				'created_by' => $user_id
		    );	
			
		    $id = $this->template_model->template_basic_info($template_basic_info);
		    
		    $this->template_model->save_phase_and_task($id,$no_phases,$no_tasks);

			/*log*/
			$this->wbs_helper->log('Create Template','Created Template: <b>'.$post['template_name'].'</b>');

			redirect('template/template_design/'.$id);
			
		}
        
        	 
		$data['template_sub_sidebar']=$this->load->view('template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('template/template_basic_info',$data,true);		
		//$this->load->view('includes/header',$data);
		//$this->load->view('template/template_sidebar',$data);
		$this->load->view('template/template_home',$data);
		//$this->load->view('includes/footer',$data);
	}

	public function tendering_template_basic_info($template_id = null) {

        $data['title'] = 'Add Tendering Template';
        $data['action'] = site_url('template/tendering_template_basic_info');
        $post = $this->input->post();

		if ( $this->input->post('submit')) {

			if(empty($post['id'])){
				/*log*/
				$this->wbs_helper->log('Tendering template add',"Added tendering template <b>{$post['name']}</b>");
				/*****/
				$template_basic_info = array(
					'name' => $post['name'],
					'wp_company_id' => $this->wp_company_id,
					'created'=>date("Y-m-d H:i:s"),
					'created_by' => $this->user_id
				);
				$this->db->insert('construction_tendering_templates', $template_basic_info);
				$id = $this->db->insert_id();
				/*creating three default items*/
				$data = array();
				for($i = 0; $i < 3; $i++){
					$data[] = array(
						'template_id' => $id, 'name' => 'item '.($i+1), 'order' => $i
					);
				}
				$this->db->insert_batch('construction_tendering_template_items', $data);
			}else{
				/*log*/
				$info = $this->db->get_where('construction_tendering_templates',array('id'=>$post['id']),1,0)->row();
				if($info->name != $post['name']){

					$this->wbs_helper->log('Tendering template edit',"Renamed tendering template <b>{$info->name}</b> to <b>{$post['name']}</b>");
				}
				/*****/
				$template_basic_info = array(
					'name' => $post['name']
				);
				$this->db->where(array('id' => $post['id'], 'wp_company_id'=>$this->wp_company_id));
				$this->db->update('construction_tendering_templates', $template_basic_info);
				$id = $post['id'];
			}

		redirect('template/tendering_template_design/'.$id);

		}
		$data['template'] = array();
		if($template_id){
			$data['template'] = $this->db->get_where('construction_tendering_templates', array('id' => $template_id, 'wp_company_id' => $this->wp_company_id), 1, 0)->row();
		}

		$data['template_sub_sidebar'] = $this->load->view('template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('template/tendering_template_basic_info',$data,true);

		$this->load->view('template/template_home',$data);
	}
	
	public function template_basic_info_update($template_id) { 
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid;
        
        $data['title'] = 'Edit Template';
        $data['action'] = site_url('template/template_basic_info_update/'.$template_id);
        $post = $this->input->post();
        
         if ( $this->input->post('submit')) {

			$template_basic_info_update = array(
				'template_name' => $post['template_name'],
				//'template_type' => $post['template_type'],

				'updated_by' => $user_id
		    );	
			
		    $id = $this->template_model->template_basic_info_update($template_id,$template_basic_info_update);

			 /*log*/
			 $this->wbs_helper->log('Update Template','Updated Template: <b>'.$post['template_name'].'</b>');

			redirect('template/template_design_update/'.$template_id);
			
		} else {
						
			$data['template'] = $this->template_model->template_id($template_id)->row();
					
		}
        
        	 
		$data['template_sub_sidebar']=$this->load->view('template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('template/template_basic_info',$data,true);		
		//$this->load->view('includes/header',$data);
		//$this->load->view('template/template_sidebar',$data);
		$this->load->view('template/template_home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_design() {
        
        $data['title'] = 'Add Template';
     	
		$data['template_id'] = $this->uri->segment(3);	 
		$data['template_content'] = $this->load->view('template/template_design',$data,true);
		//$this->load->view('includes/header',$data);
		//$this->load->view('template/template_sidebar',$data);
		$this->load->view('template/template_home',$data);
		//$this->load->view('includes/footer',$data);
	}

	public function tendering_template_design($id) {

		$data['title'] = 'Add Tendering Template';

		/*getting all items and contacts*/
		$this->db->select('items.id, items.name, items.construction_template_task_id, construction_template.id construction_template_id, items.group_id, contacts.id contact_id, contacts.item_id, CONCAT(contact2.contact_first_name," ",contact2.contact_last_name) contact_name, company.company_name, company2.company_name contact_company_name', false);
		$this->db->join('construction_tendering_template_items items','items.template_id = template.id', 'left');
		$this->db->join('construction_tendering_item_contacts contacts','contacts.item_id = items.id', 'left');
		$this->db->join('contact_contact_list contact2','contacts.contact_contact_list_id = contact2.id', 'left');
		$this->db->join('contact_company company','contacts.contact_company_id = company.id', 'left');
		$this->db->join('construction_template_task','construction_template_task.id = items.construction_template_task_id', 'left');
		$this->db->join('construction_template','construction_template.id = construction_template_task.template_id', 'left');
		//task #4166
		$this->db->join('contact_company company2','contact2.company_id = company2.id', 'left');
		$this->db->where(array('template.id' => $id, 'template.wp_company_id' => $this->wp_company_id));
		$this->db->where('items.job_id IS NULL'); //task #4580
		$this->db->where('contacts.job_id IS NULL'); //task #4580

		$this->db->order_by("order", "asc");
		$info = $this->db->get('construction_tendering_templates template')->result();

		$items = array();
		foreach($info as $inf){
			$items[$inf->id]['name'] = $inf->name;
			$items[$inf->id]['construction_template_id'] = $inf->construction_template_id;
			$items[$inf->id]['construction_template_task_id'] = $inf->construction_template_task_id;
			$items[$inf->id]['group_id'] = $inf->group_id;
			if($inf->contact_id){

				$items[$inf->id]['contacts'][$inf->contact_id] = ($inf->contact_name) ? $inf->contact_company_name." - ".$inf->contact_name : $inf->company_name;
			}
		}
		$data['items'] = $items;
		$data['template_id'] = $id;
		$data['groups'] = $this->db->get('construction_tendering_groups')->result();

		/*getting all the categories and companies of this category. task #4556*/
		$this->db->select('contact_category.id category_id, contact_category.category_name', false);
		$this->db->join('contact_company company','company.category_id = contact_category.id','left');
		$this->db->order_by("category_name");
		$categories = $this->db->get_where('contact_category',array('contact_category.wp_company_id'=>$this->wp_company_id))->result();

		$data['categories'] = array();
		foreach($categories as $category){
			$data['categories'][$category->category_id] = array(
				'id' => $category->category_id,
				'name' => $category->category_name,
				'companies' => array()
			);
		}

		foreach($categories as $category){
			$this->db->where("category_id like '%|{$category->category_id}|%'");
			$companies = $this->db->get('contact_company')->result();
			foreach($companies as $c){
				$data['categories'][$category->category_id]['companies'][] = array(
					'id' => $c->id,
					'name' => $c->company_name
				);
			}
		};

		/*************************************************/

		/*getting all the program templates and key tasks under this template. task #4558*/
		$this->db->select('construction_template.id template_id, construction_template.template_name, construction_template_task.id task_id, construction_template_task.task_name');
		$this->db->join('construction_template_task','construction_template_task.template_id = construction_template.id','left');
		$templates = $this->db->get_where('construction_template',array(
			'construction_template.wp_company_id'=>$this->wp_company_id,
			'construction_template_task.type_of_task'=>'key_task'))->result();

		$data['templates'] = array();
		foreach($templates as $template){
			$data['templates'][$template->template_id] = array(
				'id' => $template->template_id,
				'name' => $template->template_name,
				'tasks' => array()
			);
		}

		foreach($templates as $template){
			if($template->task_id){
				$data['templates'][$template->template_id]['tasks'][] = array(
					'id' => $template->task_id,
					'name' => $template->task_name
				);
			}
		};

		/*************************************************/

		/*getting all the companies and contacts of this company*/
		$this->db->select('company.id company_id, contact.id contact_id, company_name, CONCAT(contact_first_name," ",contact_last_name) contact_name', false);
		$this->db->join('contact_contact_list contact','contact.company_id = company.id','left');
		$this->db->order_by("company_name");
		$this->db->order_by("contact_name");
		$companies = $this->db->get_where('contact_company company',array('company.wp_company_id'=>$this->wp_company_id))->result();

		$data['companies'] = array();
		foreach($companies as $company){
			$data['companies'][$company->company_id] = array(
				'id' => $company->company_id,
				'name' => $company->company_name,
				'contacts' => array()
			);
		}

		foreach($companies as $company){
			if($company->contact_id){
				$data['companies'][$company->company_id]['contacts'][] = array(
					'id' => $company->contact_id,
					'name' => $company->contact_name
				);
			}
		};

		$data['template_sub_sidebar']=$this->load->view('template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('template/tendering_template_design',$data,true);
		//$this->load->view('includes/header',$data);
		$this->load->view('template/template_home',$data);
	}
	
	public function template_design_update($template_id) {
        
        $data['title'] = 'Edit Template';
		$data['template_id'] = $this->uri->segment(3);
     	$data['template_design_update'] = $this->template_model->template_id($template_id)->row();
		$data['template_sub_sidebar']=$this->load->view('template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('template/template_design',$data,true);		
		//$this->load->view('includes/header',$data);
		//$this->load->view('template/template_sidebar',$data);
		$this->load->view('template/template_home',$data);
		//$this->load->view('includes/footer',$data);
	}
	
	public function template_phase_add() {

		if ( $this->input->post('submit')) {

			$post = $this->input->post();
			$template_id = $post['template_id'];
			$url = $post['url'];
			$person_responsible = $post['phase_person_responsible'];

			$template_phase_add = array(
				'phase_name' => $post['phase_name'],
				'phase_length' => $post['phase_length'],
				'ordering' => $post['phase_ordering'],
				'template_id' => $template_id,
				'phase_no' => $post['phase_no'],
				'phase_person_responsible' => $person_responsible,
				'dependency' => $post['dependency'],
				'task_dependency' => $post['task_dependency']

			);

			$phase_id = $this->template_model->template_phase_add($template_phase_add);

			/*log*/
			$template_name = $this->db->get_where('construction_template',array('id'=>$template_id),1,0)->row()->template_name;
			$this->wbs_helper->log('Add Phase',"Added phase: <b>{$post['phase_name']}</b> in template: <b>{$template_name}</b>");

			redirect('template/'.$url.'/'.$template_id.'/'.$phase_id);

		}
	}
	public function tendering_template_item_add() {

		if ( $this->input->post('submit')) {

			$post = $this->input->post();
			$template_id = $post['template_id'];
			$group_id = $post['group_id'];
			$template = $this->db->get_where('construction_tendering_templates',array('id'=>$template_id),0,1)->row();
			if(!$template) exit;
			//task #4558
			if(!$post['key_task_id']){
				redirect('template/tendering_template_design/'.$template_id);
			}
			$this->db->select('construction_template_task.*');
			$this->db->join('construction_template','construction_template.id = construction_template_task.template_id');
			$this->db->where('construction_template.wp_company_id',$this->wp_company_id);
			$this->db->where('construction_template_task.id',$post['key_task_id']);
			$this->db->where('construction_template_task.type_of_task','key_task');
			$key_task = $this->db->get('construction_template_task',1,0)->row();
			if(!$key_task){
				redirect('template/tendering_template_design/'.$template_id);
			}

			$sql = "select max(`order`) o from construction_tendering_template_items where template_id = {$template_id}";
			$order = $this->db->query($sql)->row()->o;
			$data = array(
				//'name' => $post['name'],
				'name' => $key_task->task_name,
				'template_id' => $template->id,
				'order' => $order + 1,
				'group_id' => $group_id,
				'construction_template_task_id' => $key_task->id
			);

			/*log*/
			$this->wbs_helper->log('Tendering item add',"Added item <b>{$post['name']}</b> in tendering template <b>{$template->name}</b>");
			/*****/

			$this->db->insert('construction_tendering_template_items', $data);

			redirect('template/tendering_template_design/'.$template_id);

		}
	}
	
	public function template_phase_update($phase_id) {        
        
        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$template_id = $post['template_id'];
			$url = $post['url'];
			$phase = $this->db->query("select * from construction_template_phase where id = $phase_id")->row();
			$dependent_phase_id = "";
			if($post['dependency']){
				$dependent_phase = $this->db->query("select dependency from construction_template_phase where id = " . $post['dependency'])->row();
				$dependent_phase_id = $dependent_phase->dependency;
			}

			$dependency = ($phase->ordering == 0 || $dependent_phase_id == $phase_id) ? NULL : $post['dependency'];
			$template_phase_update = array(
				'phase_name' => $post['phase_name'],
				'phase_length' => $post['phase_length'],
				'phase_person_responsible' => $post['phase_person_responsible'],
				'dependency' => $dependency,
				'task_dependency' => ($dependency) ? $post['task_dependency'] : null

		    );

			/*log*/
			$this->db->select('template_name, phase_name');
			$this->db->join('construction_template','construction_template.id = construction_template_phase.template_id');
			$info = $this->db->get_where('construction_template_phase',array('construction_template_phase.id'=>$phase_id),1,0)->row();
			$txt = ($info->phase_name != $post['phase_name']) ? " (renamed to {$post['phase_name']}) " : "";
			$this->wbs_helper->log('Update Phase',"Updated phase <b>{$info->phase_name}</b> {$txt} in template <b>{$info->template_name}</b>");
			/*******/
			
		    $this->template_model->template_phase_update($phase_id,$template_phase_update);

			redirect('template/'.$url.'/'.$template_id.'/'.$phase_id);
			
		} 
		
	}
	public function tendering_template_item_update($item_id) {

        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$template_id = $post['template_id'];
			$this->db->select('item.*, template.name template_name');
			$this->db->join('construction_tendering_templates template','template.id = item.template_id', 'left');
			$this->db->where(array('item.id'=>$item_id,'template.wp_company_id'=>$this->wp_company_id));
			$item = $this->db->get('construction_tendering_template_items item',0,1)->row();
			//task #4558
			if(!$post['key_task_id']){
				redirect('template/tendering_template_design/'.$template_id);
			}
			$this->db->select('construction_template_task.*');
			$this->db->join('construction_template','construction_template.id = construction_template_task.template_id');
			$this->db->where('construction_template.wp_company_id',$this->wp_company_id);
			$this->db->where('construction_template_task.id',$post['key_task_id']);
			$this->db->where('construction_template_task.type_of_task','key_task');
			$key_task = $this->db->get('construction_template_task',1,0)->row();
			if(!$key_task){
				redirect('template/tendering_template_design/'.$template_id);
			}
			$template_item_update = array(
				//'name' => $post['name'],
				'name' => $key_task->task_name,
				'construction_template_task_id' => $key_task->id,
				'group_id' => $post['group_id']
		    );

			$this->db->update('construction_tendering_template_items', $template_item_update, array('id' => $item->id));

			/*log*/
			$txt = ($post['name'] != $item->name) ? " (renamed to {$post['name']}) ":"";
			$this->wbs_helper->log('Tendering item update',"Updated item <b>{$item->name}</b>{$txt} in tendering template <b>{$item->template_name}</b>");$this->wbs_helper->log('Tendering item update',"Updated item <b>{$item->name}</b>{$txt} in tendering template <b>{$item->template_name}</b>");
			/*****/

			redirect('template/tendering_template_design/'.$template_id);

		}

	}
	public function tendering_template_item_delete($item_id) {

        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$template_id = $post['template_id'];
			$this->db->select('item.*, template.name template_name');
			$this->db->join('construction_tendering_templates template','template.id = item.template_id', 'left');
			$this->db->where(array('item.id'=>$item_id,'template.wp_company_id'=>$this->wp_company_id));
			$item = $this->db->get('construction_tendering_template_items item',0,1)->row();

			$this->db->delete('construction_tendering_template_items', array('id' => $item->id));

			/*log*/
			$this->wbs_helper->log('Tendering item delete',"deleted item <b>{$item->name}</b> in tendering template <b>{$item->template_name}</b>");
			/*****/

			redirect('template/tendering_template_design/'.$template_id);

		}

	}

	public function template_phase_delete($phase_id){
				
		$post = $this->input->post();
		$template_id = $post['template_id'];
		$url = $post['url'];
		/*log*/
		$this->db->select("template_name, phase_name");
		$this->db->join("construction_template","construction_template.id = p.template_id");
		$info = $this->db->get('construction_template_phase p',array('p.id'=>$phase_id),1,0)->row();
		$this->wbs_helper->log('Phase delete',"Deleted phase <b>{$info->phase_name}</b> in template <b>{$info->template_name}</b>");
		/*****/
		$this->template_model->template_phase_delete($phase_id,$template_id);
		redirect('template/'.$url.'/'.$template_id);
	}
	
	public function template_task_add() {        
        
        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$template_id = $post['template_id'];
			$phase_id = $post['phase_id'];
			$url = $post['url'];
			
			$template_task_add = array(
				'task_name' => $post['task_name'],
				'task_length' => $post['task_length'],
				'start_day' => $post['start_day'],
				'phase_id' => $post['phase_id'],
				'phase_no' => $post['phase_no'],
				'task_no' => $post['task_no'],				
				'template_id' => $template_id,
				'task_person_responsible' => $post['task_person_responsible'],
				'ordering' => $post['task_ordering']
		    );	
			
		    $this->template_model->template_task_add($template_task_add);

			redirect('template/'.$url.'/'.$template_id.'/'.$phase_id);
			
		} 
		
	}
	/*task #4033*/
	public function template_task_add_new() {

		if ( $this->input->post('submit')) {

			$post = $this->input->post();
			$phase_id = $post['phase_id'];
			$this->db->select("template.id template_id, phase.*, max(task_no)+1 task_no, max(task.ordering)+1 task_ordering, template_name, phase_name");
			$this->db->join('construction_template template','template.id = phase.template_id');
			$this->db->join('construction_template_task task','task.phase_id = phase.id','left');
			$this->db->where(array(
				'phase.id' => $phase_id, 'template.wp_company_id' => $this->wp_company_id
			));
			$phase_info = $this->db->get('construction_template_phase phase',0,1)->row();

			$template_task_add = array(
				'task_name' => $post['task_name'],
				'task_length' => $post['task_length'],
				'start_day' => $post['start_day'],
				'phase_id' => $phase_id,
				'phase_no' => $phase_info->phase_no,
				'task_no' => ($phase_info->task_no) ? $phase_info->task_no : 1,
				'template_id' => $phase_info->template_id,
				'task_person_responsible' => $post['task_person_responsible'],
				'type_of_task' => $post['type_of_task'],
				'ordering' => ($phase_info->task_ordering) ? $phase_info->task_ordering : 0,
				'task_company' => $post['contact_company'],
				'task_category' => $post['contact_category']
			);

			$this->template_model->template_task_add($template_task_add);

			/*log*/
			$this->wbs_helper->log('Add Task',"Added task <b>{$post['task_name']}</b> in phase: <b>{$phase_info->phase_name}</b> in template: <b>{$phase_info->template_name}</b>");

			redirect(site_url('template/template_design_update/'.$phase_info->template_id.'/'.$phase_id));

		}

	}

	public function tendering_template_contact_add() {

        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$item_id = $post['item_id'];
			$contact_id = $post['contact_contact_list_id'];
			$company_id = $post['contact_company_id'];

			$this->db->select('item.*, template.name template_name');
			$this->db->join('construction_tendering_templates template','template.id = item.template_id');
			$this->db->where(array('wp_company_id'=>$this->wp_company_id, 'item.id'=>$item_id));
			$item = $this->db->get('construction_tendering_template_items item',0,1)->row();

			if(!$contact_id && !$company_id){
				redirect('template/tendering_template_design/'.$item->template_id.'?iid='.$item->id);
			}

			/*if no contact is chosen we will insert the company name and email*/
			if($contact_id){

				$contact = $this->db->get_where('contact_contact_list',array('id'=>$contact_id, 'wp_company_id' => $this->wp_company_id), 0, 1)->row();
				if(!$item || !$contact) exit;
				$data = array(
					'contact_contact_list_id' => $contact->id,
					'item_id' => $item->id,
					'category_id' => $post['contact_category_id']
				);
			}else{
				$company = $this->db->get_where('contact_company',array('id'=>$company_id, 'wp_company_id' => $this->wp_company_id), 0, 1)->row();
				if(!$item || !$company) exit;
				$data = array(
					'contact_company_id' => $company->id,
					'item_id' => $item->id,
					'category_id' => $post['contact_category_id']
				);
			}

			/*log*/
			$contact = ($contact_id)? "contact <b>{$contact->contact_first_name} {$contact->contact_last_name}</b>" : "company <b>{$company->company_name}</b>";
			$this->wbs_helper->log('Tendering contact add',"Added {$contact} under item <b>{$item->name}</b> in tendering template <b>{$item->template_name}</b>");
			/*****/

		    $this->db->insert('construction_tendering_item_contacts', $data);

			redirect('template/tendering_template_design/'.$item->template_id.'?iid='.$item->id);

		}

	}
	
	public function template_task_update($task_id) {        
        
        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$template_id = $post['template_id'];
			$phase_id = $post['phase_id'];
			$url = $post['url'];

			/*log*/
			$this->db->select('template_name, phase_name, task_name');
			$this->db->join('construction_template','construction_template.id = construction_template_task.template_id');
			$this->db->join('construction_template_phase','construction_template_phase.id = construction_template_task.phase_id');
			$info = $this->db->get_where('construction_template_task',array('construction_template_task.id'=>$task_id),1,0)->row();
			$txt = ($info->task_name != $post['task_name']) ? " (renamed to {$post['task_name']}) " : "";
			$this->wbs_helper->log('Update Task',"Updated task <b>{$info->task_name}</b> {$txt} under phase <b>{$info->phase_name}</b> in template <b>{$info->template_name}</b>");
			/*******/

			$template_task_update = array(
				'task_name' => $post['task_name'],
				'task_length' => $post['task_length'],
				'task_person_responsible' => $post['task_person_responsible'],
				'start_day' => $post['start_day'],
				'type_of_task' => $post['type_of_task'],
				'task_category' => $post['contact_category'],
				'task_company' => $post['contact_company']

		    );	
			
		    $this->template_model->template_task_update($task_id,$template_task_update);

			redirect('template/'.$url.'/'.$template_id.'/'.$phase_id);
			
		} 
		
	}
	
	public function template_task_delete($task_id){

		/*log*/
		$this->db->select('template_name, phase_name, task_name');
		$this->db->join('construction_template','construction_template.id = construction_template_task.template_id');
		$this->db->join('construction_template_phase','construction_template_phase.id = construction_template_task.phase_id');
		$info = $this->db->get_where('construction_template_task',array('construction_template_task.id'=>$task_id),1,0)->row();
		$this->wbs_helper->log('Delete Task',"Deleted task <b>{$info->task_name}</b> under phase <b>{$info->phase_name}</b> in template <b>{$info->template_name}</b>");
		/*******/

		$this->template_model->template_task_delete($task_id);
		$post = $this->input->post();
		$template_id = $post['template_id'];
		$phase_id = $post['phase_id'];
		$url = $post['url'];

		redirect('template/'.$url.'/'.$template_id.'/'.$phase_id);
	}
	public function tendering_template_contact_delete($cid){

		$post = $this->input->post();
		$item_id = $post['item_id'];
		$this->db->select('item.*, template.name template_name');
		$this->db->join('construction_tendering_templates template','template.id = item.template_id');
		$this->db->where(array('wp_company_id'=>$this->wp_company_id, 'item.id'=>$item_id));
		$item = $this->db->get('construction_tendering_template_items item',0,1)->row();

		/*log*/
		$this->db->select('CONCAT(contact.contact_first_name," ", contact.contact_last_name) contact_name, company.company_name',false);
		$this->db->join('contact_contact_list contact','contact.id = item_contact.contact_contact_list_id','left');
		$this->db->join('contact_company company','company.id = item_contact.contact_company_id','left');
		$info = $this->db->get_where('construction_tendering_item_contacts item_contact',array('item_contact.id'=>$cid),1,0)->row();
		$contact = ($info->company_name) ? "company <b>{$info->company_name}</b>" : "contact <b>{$info->contact_name}</b>";
		$this->wbs_helper->log('Tendering contact delete',"deleted {$contact} from item <b>{$item->name}</b> in tendering template <b>{$item->template_name}</b>");
		/*****/

		$this->db->delete('construction_tendering_item_contacts',array('item_id' => $item_id, 'id' => $cid));


		redirect('template/tendering_template_design/'.$item->template_id.'?iid='.$item->id);
	}
	
	public function template_review() {
        
        $data['title'] = 'Add Template';
     
		$data['template_id'] = $this->uri->segment(3);
		$data['template_sub_sidebar']=$this->load->view('template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('template/template_review',$data,true);		
		$this->load->view('includes/header',$data);
		//$this->load->view('template/template_sidebar',$data);
		$this->load->view('template/template_home',$data);
		//$this->load->view('includes/footer',$data);
	}
	
	public function template_review_update($template_id) {
        
        $data['title'] = 'Edit Template';
		$data['template_id'] = $template_id;
     	$data['template_review_update'] = $this->template_model->template_id($template_id)->row();
			 
		$data['template_sub_sidebar']=$this->load->view('template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('template/template_review',$data,true);		
		$this->load->view('includes/header',$data);
		//$this->load->view('template/template_sidebar',$data);
		$this->load->view('template/template_home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_detail() {
        
        $data['title'] = 'Template Info';
     
		$data['template_id'] = $this->uri->segment(3);	 
		$data['template_sub_sidebar']=$this->load->view('template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('template/template_detail',$data,true);		
		$this->load->view('includes/header',$data);
		//$this->load->view('template/template_sidebar',$data);
		$this->load->view('template/template_home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_delete($template_id){

		/*log*/
		$template_name = $this->db->get_where('construction_template',array('id'=>$template_id),1,0)->row()->template_name;
		$this->wbs_helper->log('Template delete',"Deleted template <b>{$template_name}</b>.");
		/*****/
		
		$this->template_model->template_delete($template_id);
		redirect('template/template_list');
	}

	public function tendering_template_delete($template_id){

		if($template = $this->db->get_where('construction_tendering_templates',array('id'=>$template_id,'wp_company_id'=>$this->wp_company_id), 0, 1)->row()){
			$this->db->delete('construction_tendering_templates',array('id'=>$template->id));
			$this->db->where('item_id in (select id from construction_tendering_template_items where template_id = '.$template->id.')');
			$this->db->delete('construction_tendering_item_contacts');
			$this->db->delete('construction_tendering_template_items',array('template_id' => $template->id));

			/*log*/
			$this->wbs_helper->log('Tendering template delete',"Deleted tendering template <b>{$template->name}</b>.");
			/*****/
		}
		redirect('template/tendering_template_list');
	}
	public function clone_tendering_template($template_id){

		if($template = $this->db->get_where('construction_tendering_templates',array('id'=>$template_id,'wp_company_id'=>$this->wp_company_id), 0, 1)->row()){
			/*copying template*/
			$template_basic_info = array(
				'name' => $template->name,
				'wp_company_id' => $this->wp_company_id,
				'created'=>date("Y-m-d H:i:s"),
				'created_by' => $this->user_id
			);
			$this->db->insert('construction_tendering_templates', $template_basic_info);
			$id = $this->db->insert_id();

			/*copying items*/
			$items = $this->db->get_where('construction_tendering_template_items',array('template_id' => $template->id))->result();
			$order = 0;
			foreach($items as $item){
				$data = array(
					'name' => $item->name,
					'template_id' => $id,
					'order' => $order++,
					'group_id' => $item->group_id

				);

				$this->db->insert('construction_tendering_template_items', $data);
				$item_id = $this->db->insert_id();

				/*copying contacts*/
				$contacts = $this->db->get_where('construction_tendering_item_contacts',array('item_id' => $item->id))->result();

				foreach($contacts as $contact){
					$data = array(
						'contact_contact_list_id' => $contact->contact_contact_list_id,
						'item_id' => $item_id
					);

					$this->db->insert('construction_tendering_item_contacts', $data);
				}

			}

		}
		redirect('template/tendering_template_basic_info/'.$id);
	}
	
	public function template_phase_ordering(){

    	$this->template_model->template_phase_ordering();
    }

	public function tendering_template_item_ordering(){
		foreach ($_POST['listItemPhase'] as $position => $item){
			$sql = "UPDATE construction_tendering_template_items SET `order` = {$position} WHERE template_id IN (SELECT id FROM construction_tendering_templates WHERE wp_company_id = {$this->wp_company_id}) AND id={$item}";
			$this->db->simple_query($sql);
		}
	}


	public function template_task_ordering(){
    	$this->template_model->template_task_ordering();
    }

	public function milestone_template($op = 'add'){

		if ($this->input->post('submit')) {
			$post = $this->input->post();
			if($op == 'add'){
				$data = array(
					'name' => $post['name'],
					'wp_company_id' => $this->wp_company_id,
					'created'=>date("Y-m-d H:i:s"),
					'created_by' => $this->user_id
				);
				$this->db->insert('construction_milestone_templates', $data);
				/*log*/
				$this->wbs_helper->log('Milestone template add',"Added milestone template <b>{$post['name']}</b>.");
				/*****/
			}elseif($op == 'edit'){
				$data = array(
					'name' => $post['name']
				);
				/*log*/
				$info = $this->db->get_where('construction_milestone_templates',array('id'=>$post['id']),1,0)->row();
				if($info->name != $post['name']){
					$this->wbs_helper->log('Milestone template edit',"Renamed milestone template <b>{$info->name}</b> to <b>{$post['name']}</b>.");
				}
				/*****/
				$this->db->where(array('id'=>$post['id'], 'wp_company_id' => $this->wp_company_id));
				$this->db->update('construction_milestone_templates', $data);
			}else{
				/*delete*/
				$data = array(
					'deleted' => 1
				);
				$this->db->where(array('id'=>$post['id'], 'wp_company_id' => $this->wp_company_id));
				$this->db->update('construction_milestone_templates', $data);
				/*log*/
				$info = $this->db->get_where('construction_milestone_templates',array('id'=>$post['id']),1,0)->row();
				$this->wbs_helper->log('Milestone template delete',"deleted milestone template <b>{$info->name}</b>.");
				/*****/

			}
			redirect('template/milestone_template_list');

		}
	}

	/*task #4564*/
	public function tendering_template_load_key_tasks(){

		if ( $this->input->post('submit')) {

			$post = $this->input->post();
			$template_id = $post['template_id'];
			$template = $this->db->get_where('construction_tendering_templates',array('id'=>$template_id, 'wp_company_id'=>$this->wp_company_id),0,1)->row();

			if(!$template || empty($post['key_task_id'])) {
				redirect('template/tendering_template_design/'.$template_id); exit;
			}
			$this->db->select('construction_template_task.*');
			$this->db->join('construction_template','construction_template.id = construction_template_task.template_id');
			$this->db->where('construction_template_task.type_of_task','key_task');
			$this->db->where('construction_template_task.template_id',$this->input->post('program_template_id'));
			/*task #4666*/
			$this->db->where("construction_template_task.id in (".implode(',',$post['key_task_id']).")");

			$key_tasks = $this->db->get('construction_template_task')->result();

			$sql = "select max(`order`) o from construction_tendering_template_items where template_id = {$template_id}";
			$order = $this->db->query($sql)->row()->o;
			$data = array();
			foreach($key_tasks as $key_task){
				$data[] = array(
					//'name' => $post['name'],
					'name' => $key_task->task_name,
					'template_id' => $template->id,
					'order' => $order++,
					/*'group_id' => $group_id,*/
					'construction_template_task_id' => $key_task->id
				);
			}

			/*log*/
			$this->wbs_helper->log('Tendering item add',"Loaded key tasks in tendering template <b>{$template->name}</b>");
			/*****/

			$this->db->insert_batch('construction_tendering_template_items', $data);

			redirect('template/tendering_template_design/'.$template_id);

		}
	}

	/*task #4565*/
	public function update_item_group($item_id, $group_id){
		$this->db->select('item.*');
		$this->db->join('construction_tendering_templates template','template.id = item.template_id');
		$this->db->where('item.id', $item_id);
		$this->db->where('template.wp_company_id', $this->wp_company_id);
		$item = $this->db->get('construction_tendering_template_items item',1,0)->row();
		if($item){
			$this->db->where('id',$item->id);
			$this->db->update('construction_tendering_template_items',array('group_id' => $group_id));
		}
	}

}