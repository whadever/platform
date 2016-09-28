<?php 
class Overview extends CI_Controller {
	
	private $limit = 10;
    private $user_app_role = '';
    private $user_id = '';
    private $wp_company_id = '';
	
	function __construct() {
		
		parent::__construct();
		
		
		
		$this->load->model('overview_model','',TRUE);
		$this->load->model('user_model','',TRUE);
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->library('Wbs_helper');
		$this->load->helper(array('form', 'url'));
        $this->load->helper('email');

		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
			redirect($redirect_login_page,'refresh'); 		 		
		}
        /*getting user's application role*/
        $user = $this->session->userdata('user');
        $sql = "select LOWER(ar.application_role_name) role
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                where ua.user_id = {$user->uid} and a.id = 3 limit 0, 1";
        $this->user_app_role = $this->db->query($sql)->row()->role;
        $this->user_id = $user->uid;
        $this->wp_company_id = $user->company_id;
		              
	}
        
    public function index(){

            $data['title'] = 'Overview';
               
           $user=  $this->session->userdata('user'); 
                    
           $data['user']=$user;
           $user_id =$user->uid; 
           //$role_id = $user->rid;
           $role_id = $this->user_app_role;
           //$user_name= $user->name;
           
           //$corrosponts_requests= $this->overview_model->get_overview_corrosponds_requests($user_id, $role_id);
           //$data['new_request']= $corrosponts_requests;

		/******************getting the today's tasks***********************/
        // before getting today's task list we have to reset the list
        // all the today's tasks more than 24 hours old will be reset to 0
        $this->overview_model->reset_todays_tasks();
        $todays_task_list = $this->overview_model->get_overview_todays_task_list($user_id, $role_id);
        $data['todays_task_list'] = $todays_task_list->result();
        //print_r($data['new_requests_list']);
        $data['todays_task'] = $todays_task_list->num_rows;

        /***************************/
           
            $new_requests_list= $this->overview_model->get_overview_new_requests_list($user_id, $role_id);
           $data['new_requests_list'] = $new_requests_list->result();
           //print_r($data['new_requests_list']);
           $data['new_request']= $new_requests_list->num_rows;
           
           
           
           $overview_requests= $this->overview_model->get_overview_requests($user_id, $role_id);
           $data['open_requests'] = $overview_requests->result();
           //print_r($open_requests);
           $data['open_request']= $overview_requests->num_rows;
           
           
           
           $overdue_requests_overview= $this->overview_model->get_overview_overdue_requests($user_id, $role_id);
           $data['overdue_requests']= $overdue_requests_overview;
           
           
           $overview_overdue_requests_list= $this->overview_model->get_overview_overdue_requests_list($user_id, $role_id);
           $data['overview_overdue_requests_list']= $overview_overdue_requests_list;
           
           $data['user_app_role'] = $this->user_app_role;
           $data['view'] = $this->input->post('view');

            $data['maincontent'] = $this->load->view('overview',$data,true); 
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        }
        
        public function new_request(){
            $data['title'] = 'New Request';
           
            
           $user=  $this->session->userdata('user'); 
           
           $data['user']=$user;
           $user_id =$user->uid; 
           $role_id = $user->rid;
           //$user_name= $user->name;             
           
           $new_requests_list= $this->overview_model->get_overview_new_requests_list($user_id, $role_id)->result();
           
           //print_r($data['new_requests_list']);
           $sl=1;
           $title = array('data' => 'Title', 'class' => 'title', 'width' =>'20%');
           $description = array('data' => 'Description', 'class' => 'description', 'width' =>'30%');
           
           $this->table->set_heading(
                '',
         //'Sl.',
		'Date',
                'Request No',
		$title,
		//$description,
                'Project Name',
		'Status',
		'Contact',
                //'Developer',
		'Priority'
		);
		foreach ($new_requests_list as $request){
                    
                   $assign_manager= $this->overview_model->get_assign_manager($request->assign_manager_id);
                   //print_r($assign_manager);
                   $priority = $request->priority;
                   if($priority==1){$show_priority='High';}
                   elseif($priority==2){$show_priority='Normal';}
                   elseif($priority==3){$show_priority='Low';}
                    

		$this->table->add_row(
                        anchor('request/request_detail/'.$request->id, ' <span class="request_button_plus">+</span> ',array('title'=>'View Request detail')),
                       // $sl,
                        date("d-m-Y", strtotime($request->request_date)),
                        sprintf('%07d', $request->request_no),
			//$request->request_title,
			anchor(base_url().'request/request_detail/'.$request->id, $request->request_title ,array('title'=>$request->request_description)),
			//$request->request_description,
                        $request->project_name,
			$request->request_status==2? 'Closed': 'Open',
			implode(", ", $assign_manager),
                        //$request->assign_developer_id,
			$show_priority			
			//anchor('request/request_update/'.$request->id,'update',array('class'=>'update','title'=>'Update')).' '.
                        //anchor('request/request_delete/'.$request->id,'delete',array('title'=>'Delete','class'=>'delete','onclick'=>"return confirm('Are you sure you want to remove this Company?')"))
                    ); $sl++;
		}
                $tmpl = array ('table_open' => '<table border="0" cellpadding="2" cellspacing="1" class="table-hover">' );

                $this->table->set_template($tmpl);
		$data['table'] = $this->table->generate();
           
		
            $data['maincontent'] = $this->load->view('request_list',$data,true); 
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        }
        public function overdue_request(){
            $data['title'] = 'Overdue Request';
           
            
           $user=  $this->session->userdata('user'); 
           
           $data['user']=$user;
           $user_id =$user->uid; 
           $role_id = $user->rid;
           //$user_name= $user->name; 
          
           
           
           $overdue_requests_list= $this->overview_model->get_overview_overdue_requests_list($user_id, $role_id);
           //$overdue_requests_list = $overdue_requests_list;
           //print_r($data['new_requests_list']);
           
           $sl=1;
           $title = array('data' => 'Title', 'class' => 'title', 'width' =>'20%');
           $description = array('data' => 'Description', 'class' => 'description', 'width' =>'30%');
           $this->table->set_heading(
                        '',
         //'Sl.',
		'Date',
                'Request No',
		$title,
		$description,
		'Status',
		'Contact',
		'Priority'
		);
		foreach ($overdue_requests_list as $request){
                   $priority = $request->priority;
                   if($priority==1){$show_priority='High';}
                   elseif($priority==2){$show_priority='Normal';}
                   elseif($priority==3){$show_priority='Low';}
                   $assign_manager= $this->overview_model->get_assign_manager($request->assign_manager_id);
                    

		$this->table->add_row(
                        anchor('request/request_detail/'.$request->id, ' <span class="request_button_plus">+</span> ',array('title'=>'View Request detail')),
                       // $sl,
                        date("d-m-Y", strtotime($request->request_date)),
                        sprintf('%07d', $request->request_no),
			$request->request_title,
			
			$request->request_description,			
			$request->request_status==2? 'Closed': 'Open',
			
                        implode(", ", $assign_manager),
			$show_priority			
			//anchor('request/request_update/'.$request->id,'update',array('class'=>'update','title'=>'Update')).' '.
                        //anchor('request/request_delete/'.$request->id,'delete',array('title'=>'Delete','class'=>'delete','onclick'=>"return confirm('Are you sure you want to remove this Company?')"))
                    ); $sl++;
		}
                $tmpl = array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="table-hover">' );

                $this->table->set_template($tmpl);
		$data['table'] = $this->table->generate();
           
		
            $data['maincontent'] = $this->load->view('request_list',$data,true); 
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        }

	/*drag and drop to add to today's task*/
    function add_to_todays_task($rid){
        $time = time();
        $data = array(
            'is_in_todays_list' => 1,
            'added_to_todays_list_at'=>"$time"
        );
        $this->db->where('id',$rid);
        $this->db->update('request',$data);
        exit;
    }

	function remove_from_todays_task($tid){
        $user = $this->session->userdata('user');
        $uid = $user->uid;
        $rid = $user->rid;
        $data = array(
            'is_in_todays_list'=>0
        );
        if ($rid == 3) {
            $this->db->where("FIND_IN_SET ($uid, request.assign_developer_id)");
        } else {
            $this->db->where("FIND_IN_SET ($uid, request.assign_manager_id)");
        }
        $this->db->where('id',$tid);
        $this->db->update('request',$data);

        redirect(site_url('overview?uid='.$uid));
    }

	/*user clears the today's task list*/
    function clear_todays_task_list(){
        $user = $this->session->userdata('user');
        $uid = $user->uid;
        $rid = $user->rid;
        $data = array(
            'is_in_todays_list'=>0
        );
        if ($rid == 3) {
            $this->db->where("FIND_IN_SET ($uid, request.assign_developer_id)");
        } else {
            $this->db->where("FIND_IN_SET ($uid, request.assign_manager_id)");
        }
        $this->db->update('request', $data);

        redirect(site_url('overview?uid='.$uid));

    }
        	
}