<?php 
class Potential_template extends CI_Controller {
	
	function __construct() {		
		parent::__construct();		
		$this->load->helper(array('form', 'url', 'file'));
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->model('potential_template_model','',TRUE);
		$this->load->library('Wbs_helper');
        $this->load->helper('email');
		date_default_timezone_set("NZ");
	}
	
	public function template_list() {
                
		$data['title'] = 'Templates';
		$get = $_GET;
		$data['templates'] = $this->potential_template_model->template_list($get)->result();

				
		$data['template_sub_sidebar']=$this->load->view('potential_template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('potential_template/template_list',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('potential_template/template_sidebar',$data);
		$this->load->view('potential_template/template_home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_start() {
                
		$data['title'] = 'Add Template';
				
		$data['template_sub_sidebar']=$this->load->view('potential_template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('potential_template/template_start',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('potential_template/template_sidebar',$data);
		$this->load->view('potential_template/template_home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_basic_info() { 
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid;
		$wp_company_id =$user->company_id;
        
        $data['title'] = 'Add Template';
        $data['action'] = site_url('potential_template/template_basic_info');
        $post = $this->input->post();
        $no_phases = $post['no_phases'];
        $no_tasks = $post['no_tasks'];
         if ( $this->input->post('submit')) {

			$template_basic_info = array(
				'wp_company_id' => $wp_company_id,
				'template_name' => $post['template_name'],
				'template_type' => $post['template_type'],

				'created'=>date("Y-m-d H:i:s"),
				'created_by' => $user_id
		    );	
			
		    $id = $this->potential_template_model->template_basic_info($template_basic_info);
		    
		    $this->potential_template_model->save_phase_and_task($id,$no_phases,$no_tasks);

			redirect('potential_template/template_design/'.$id);
			
		}
        
        	 
		$data['template_sub_sidebar']=$this->load->view('potential_template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('potential_template/template_basic_info',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('potential_template/template_sidebar',$data);
		$this->load->view('potential_template/template_home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_basic_info_update($template_id) { 
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid;
        
        $data['title'] = 'Edit Template';
        $data['action'] = site_url('potential_template/template_basic_info_update/'.$template_id);
        $post = $this->input->post();
        
         if ( $this->input->post('submit')) {

			$template_basic_info_update = array(
				'template_name' => $post['template_name'],
				'template_type' => $post['template_type'],

				'updated_by' => $user_id
		    );	
			
		    $id = $this->potential_template_model->template_basic_info_update($template_id,$template_basic_info_update);

			redirect('potential_template/template_design_update/'.$template_id);
			
		} else {
						
			$data['template'] = $this->potential_template_model->template_id($template_id)->row();
					
		}
        
        	 
		$data['template_sub_sidebar']=$this->load->view('potential_template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('potential_template/template_basic_info',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('potential_template/template_sidebar',$data);
		$this->load->view('potential_template/template_home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_design() {
        
        $data['title'] = 'Add Template';
     	
			 
		$data['template_sub_sidebar']=$this->load->view('potential_template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('potential_template/template_design',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('potential_template/template_sidebar',$data);
		$this->load->view('potential_template/template_home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_design_update($template_id) {
        
        $data['title'] = 'Edit Template';
     	$data['template_design_update'] = $this->potential_template_model->template_id($template_id)->row();
			 
		$data['template_sub_sidebar']=$this->load->view('potential_template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('potential_template/template_design',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('potential_template/template_sidebar',$data);
		$this->load->view('potential_template/template_home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_phase_add() {        
        
        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$template_id = $post['template_id'];
			$url = $post['url'];
			
			$template_phase_add = array(
				'phase_name' => $post['phase_name'],
				'phase_length' => $post['phase_length'],
				'ordering' => $post['phase_ordering'],
				'template_id' => $template_id,
				'phase_no' => $post['phase_no']

		    );	
			
		    $phase_id = $this->potential_template_model->template_phase_add($template_phase_add);

			redirect('potential_template/'.$url.'/'.$template_id.'/'.$phase_id);
			
		} 
		
	}
	
	public function template_phase_update($phase_id) {        
        
        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$template_id = $post['template_id'];
			$url = $post['url'];
			
			$template_phase_update = array(
				'phase_name' => $post['phase_name'],
				'phase_length' => $post['phase_length']

		    );	
			
		    $this->potential_template_model->template_phase_update($phase_id,$template_phase_update);

			redirect('potential_template/'.$url.'/'.$template_id.'/'.$phase_id);
			
		} 
		
	}

	public function template_phase_delete($phase_id){
				
		$post = $this->input->post();
		$template_id = $post['template_id'];
		$url = $post['url'];
		$this->potential_template_model->template_phase_delete($phase_id,$template_id);
		redirect('potential_template/'.$url.'/'.$template_id);
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
				'phase_id' => $post['phase_id'],
				'phase_no' => $post['phase_no'],
				'task_no' => $post['task_no'],				
				'template_id' => $template_id,
				'ordering' => $post['task_ordering']
		    );	
			
		    $this->potential_template_model->template_task_add($template_task_add);

			redirect('potential_template/'.$url.'/'.$template_id.'/'.$phase_id);
			
		} 
		
	}
	
	public function template_task_update($task_id) {        
        
        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$template_id = $post['template_id'];
			$phase_id = $post['phase_id'];
			$url = $post['url'];
			
			$template_task_update = array(
				'task_name' => $post['task_name'],
				'task_length' => $post['task_length']

		    );	
			
		    $this->potential_template_model->template_task_update($task_id,$template_task_update);

			redirect('potential_template/'.$url.'/'.$template_id.'/'.$phase_id);
			
		} 
		
	}
	
	public function template_task_delete($task_id){
		
		$this->potential_template_model->template_task_delete($task_id);
		$post = $this->input->post();
		$template_id = $post['template_id'];
		$phase_id = $post['phase_id'];
		$url = $post['url'];
		redirect('potential_template/'.$url.'/'.$template_id.'/'.$phase_id);
	}
	
	public function template_review() {
        
        $data['title'] = 'Add Template';
     
			 
		$data['template_sub_sidebar']=$this->load->view('potential_template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('potential_template/template_review',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('potential_template/template_sidebar',$data);
		$this->load->view('potential_template/template_home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_review_update($template_id) {
        
        $data['title'] = 'Edit Template';
     	$data['template_review_update'] = $this->potential_template_model->template_id($template_id)->row();
			 
		$data['template_sub_sidebar']=$this->load->view('potential_template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('potential_template/template_review',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('potential_template/template_sidebar',$data);
		$this->load->view('potential_template/template_home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_detail() {
        
        $data['title'] = 'Template Info';
     
			 
		$data['template_sub_sidebar']=$this->load->view('potential_template/template_sub_sidebar',$data,true);
		$data['template_content'] = $this->load->view('potential_template/template_detail',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('potential_template/template_sidebar',$data);
		$this->load->view('potential_template/template_home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function template_delete($template_id){
		
		$this->potential_template_model->template_delete($template_id);
		redirect('potential_template/template_list');
	}
	
	public function template_phase_ordering(){
    	$this->potential_template_model->template_phase_ordering();
    }
    
    public function template_task_ordering(){
    	$this->potential_template_model->template_task_ordering();
    }
	
		
}