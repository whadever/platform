<?php
class Permission extends CI_Controller {
	public function __construct() {
		parent::__construct();		
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation'));
		$this->load->model('permission_model','',TRUE);
		$this->load->model('user_model','',TRUE);
                $this->load->library('wbs_helper');
                
	}  
        
        public function access_denied(){
            $data = NULL;
            $data['maincontent'] = $this->load->view('access_denied',$data,true);
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);              
        }       
        
        
	public function permission_add($uid=0){	
                        
            $user_obj = $this->user_model->user_load($uid); 
            if (empty($user_obj)) redirect ('employee_profile/employee_list');
            

            // $user_obj = $this->user_model->user_load($uid); 
            //print_r($user_obj);
            
            $rid = $user_obj->rid;
            //if (!$this->permission_model->permission_has_permission($this->uri->uri_string())) redirect ('permission/access_denied');
            //$this->mbs_helper->check_permission($this->uri->uri_string());
            
            // $this->mbs_helper->pp($this->uri->uri_string());
            
//          $user_roles = $this->permission_model->permission_load($rid);
//          $user_roles2 = $this->permission_model->permission_load_permission_only($rid);
            
            //$this->mbs_helper->pp($user_roles);
           // $this->mbs_helper->pretty_print($user_roles2);

            $data['rid'] = $user_obj->rid;
            $data['title'] = 'Manage Permission';
            $data['action'] = 'permission/permission_add/' . $rid;                        
                        
            $data['controllers'] =  $this->config->item('mbs_controllers');
            $data['operations'] =  $this->config->item('mbs_operations');
            
            $data['db_controllers'] = $this->permission_model->permission_load_permission_only($rid);
                       
            // print_r($data['controllers']);
            // print_r($data['operations']);
            
            if ($this->input->post('submit')){                
                // print_r($this->input->post()); exit;
                $post = $this->input->post();
                $rid = $post['rid'];
                
                foreach ($data['controllers'] as $controller) {  
                    // print_r($post[$controller]);
                    if (!empty($post[$controller])){
                        foreach ($data['operations'] as $op) {                             
                            if (array_key_exists($op, $post[$controller])){
                                $insert[] = array(
                                    'rid'=>$rid,
                                    'perm'=> $controller . ' ' . $controller .'_'.$op,
                                    'perm_url'=> "$controller/$controller" .'_'. $op
                                ); 
                            }                                
                         }
                   }
                }
                
                $this->permission_model->permission_insert_batch($insert); 
                $data['message'] = 'Permission for this role has been saved successfully.';      
            } 
            
            $data['maincontent'] = $this->load->view('permission_add',$data,true);
            $this->load->view('includes/header',$data);
            $this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);                                
	}   
}