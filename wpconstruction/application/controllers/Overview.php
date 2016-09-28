<?php 
class Overview extends CI_Controller {
	private $limit = 10;

	function __construct() {
		parent::__construct();
		$this->load->model('overview_model','',TRUE);
		$this->load->model('user_model','',TRUE);
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->library('Wbs_helper');
		$this->load->helper(array('form', 'url'));
        $this->load->helper('email');
        date_default_timezone_set("NZ");

		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		
		}
		              
	}
        
    public function index(){
		if(isset($_GET['uid'])){
			$result = $this->user_model->user_new_load($_GET['uid']);
			if($result){
				$sesData['user'] = $result;
				$this->session->set_userdata($sesData);
				$user = $this->session->userdata('user');					
			}else{
				redirect("https://williamscorporation.co.nz/wp");
			}				
		}
 
		if(!$this->session->userdata('user')){redirect("https://williamscorporation.co.nz/wp"); }

		$data['title'] = 'Overview';  
		$user=  $this->session->userdata('user');               
		$data['user']=$user;
		$user_id =$user->uid; 
		$role_id = $user->rid;
     
		$data['new_company_list'] = $this->overview_model->get_overview_new_company_list()->result();
		$data['new_contact_list'] = $this->overview_model->get_overview_new_contact_list()->result();
        $data['new_category_list'] = $this->overview_model->get_overview_new_category_list()->result();   
 
		$data['maincontent'] = $this->load->view('overview',$data,true); 
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}

	function my_tasks(){
		$data['title'] = 'My Tasks';
		$user=  $this->session->userdata('user');
		$data['user']=$user;
		$dates = array();
		/*starting from today to calculate next 5 working days*/
		$i = 0;
		$date = new DateTime();
		while($i < 5){
			if(date('w',$date->getTimestamp()) == 0 || date('w',$date->getTimestamp()) == 6){
				$date->add(new DateInterval('P1D'));
				continue;
			}
			$dates[] = date('Y-m-d',$date->getTimestamp());
			$date->add(new DateInterval('P1D'));
			$i++;
		}
		/*$query = "SELECT task.task_name,
                         task.id                        task_id,
                         task.development_id            dev_id,
                         task.task_start_date           start_date,
                         task.actual_completion_date    end_date,
                         task.development_task_status,
                         task.note,
                         dev.development_name
                  FROM   construction_development_task task LEFT JOIN construction_development dev ON task.development_id = dev.id
                  		 LEFT JOIN contact_contact_list ON task.task_person_responsible = contact_contact_list.id
                  WHERE  contact_contact_list.system_user_id = $user->uid AND task.development_task_status != 1 AND task.actual_completion_date != '0000-00-00'";*/
                  
        $query = "SELECT task.task_name,
                         task.id                        task_id,
                         task.development_id            dev_id,
                         task.task_start_date           start_date,
                         task.actual_completion_date    end_date,
                         task.development_task_status,
                         task.note,
                         dev.development_name
                  FROM   construction_development_task task LEFT JOIN construction_development dev ON task.development_id = dev.id
                  		 LEFT JOIN contact_contact_list ON task.task_person_responsible = contact_contact_list.id
                  WHERE  contact_contact_list.system_user_id = $user->uid AND task.development_task_status != 1";
                  
		$tasks = $this->db->query($query)->result();
		$data['my_tasks'] = array();
		foreach($dates as $d){
			$data['my_tasks'][$d] = array();
			foreach($tasks as $task){
				$status = "";
				if($task->start_date == '0000-00-00'){
					$status = "pending";
				}elseif($task->start_date != '0000-00-00' && $task->start_date <= $d && $task->end_date >= $d){
					$status = "underway";
				}elseif($task->end_date < $d){
					$status = "overdue";
				}
				if($status){

					$data['my_tasks'][$d][] = array(
						'job_name' => $task->development_name,
						'task_name' => $task->task_name,
						'start_date' => date_create_from_format('Y-m-d',$task->start_date)->format('d-m-Y'),
						'finish_date' => $task->end_date=='0000-00-00'? '00-00-0000':date_create_from_format('Y-m-d',$task->end_date)->format('d-m-Y'),
						'status' => $status,
						'task_id' => $task->task_id
					);
				}

			}
		}
		$this->load->view('includes/header',$data);
		$this->load->view('overview/my_tasks',$data);
		$this->load->view('includes/footer',$data);

		/*log*/
		$this->wbs_helper->log('Task list',"Viewed <b>My Tasks</b>");
	}
           	
}