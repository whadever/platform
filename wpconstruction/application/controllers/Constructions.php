<?php

class Constructions extends CI_Controller {

    private $limit = 10;
    private $processed_phases = array();
    private $user_app_role = '';
    private $user_id = '';
    private $wp_company_id = '';
	private $email_arr = array();

    function __construct() {

        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'html', 'email'));
        $this->load->library(array('table', 'form_validation', 'session'));
        $this->load->model('developments_model', '', TRUE);
        $this->load->library('Wbs_helper');
        $this->load->library('user_agent');
        date_default_timezone_set("NZ");

        $redirect_login_page = base_url() . 'user';
        if (!$this->session->userdata('user')) {
            redirect($redirect_login_page, 'refresh');
        }
        /*getting user's application role*/
        $user = $this->session->userdata('user');
        $sql = "select LOWER(ar.application_role_name) role, ar.application_role_id
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                where ua.user_id = {$user->uid} and a.id = 5 limit 0, 1";
        $this->user_app_role = $this->db->query($sql)->row()->role;
		$this->user_app_role_id = $this->db->query($sql)->row()->application_role_id;
        $this->user_id = $user->uid;
        $this->wp_company_id = $user->company_id;
        //$this->_keep_current_url_in_session();

		// company name
		$cilent_sql = "SELECT client_name FROM wp_company WHERE id = ".$this->wp_company_id;
		$this->client_company_name = $this->db->query($cilent_sql)->row()->client_name;

    }
    
    // Task# 4289
        public function load_contact_by_company() {

        $cid = explode(',',urldecode($_GET['cid']));
        $field=$_GET['field'];
        $job_id = $_GET['job_id'];
        $this->db->select("id,contact_first_name,contact_last_name");
        $this->db->where_in('company_id', $cid);
        $results = $this->db->get('contact_contact_list')->result(); 

        $company_field = $field.'_company';
        $row = '';
        if($_GET['cid']!='null'){
            $row .= '<option value="">Please Select</option>';
            foreach($results as $result){
                $row .= '<option value="'.$result->id.'">'.$result->contact_first_name.' '.$result->contact_last_name.'</option>';
            }

            $this->db->select('id');
            $this->db->where('job_id',$job_id);
            $result_contact_company = $this->db->get('construction_development_contact_company')->row();

            if(!$result_contact_company){
                $insert_company_arr = array(
                    'job_id' => $job_id,
                    $company_field => $_GET['cid']
                );
                $this->db->insert('construction_development_contact_company',$insert_company_arr);
            }else{
                $update_company_arr = array(
                    $company_field => $_GET['cid']
                );
                $this->db->where('job_id',$job_id);
                $this->db->update('construction_development_contact_company',$update_company_arr);
            }
            
        }else{
            $update_query = "UPDATE construction_development SET $field = '' WHERE id = ".$job_id;
            $this->db->query($update_query);

            $update_company_arr = array(
                    $company_field => ''
                );
            $this->db->where('job_id',$job_id);
            $this->db->update('construction_development_contact_company',$update_company_arr);
            
        }
        echo $row;
    }

    private function _keep_current_url_in_session() {

        $method = $this->router->method;
        $domain = $_SERVER['SERVER_NAME'];

        if ($method == 'construction_overview') {
            if ($this->uri->segment(4) == 'pre-construction') {
                $_SESSION[$domain]['pre_construction_page'] = array('uri' => uri_string(),'jobid_position'=>3);
            } else {
                $_SESSION[$domain]['construction_page'] = array('uri' => uri_string(),'jobid_position'=>3);
            }
        }
        if($method == 'phases_underway'){
            if ($this->uri->segment(3) == 'pre-construction') {
                $_SESSION[$domain]['pre_construction_page'] = array('uri' => uri_string(),'jobid_position'=>4);
            } else {
                $_SESSION[$domain]['construction_page'] = array('uri' => uri_string(),'jobid_position'=>4);
            }
        }
        /*if($method == 'construction_detail'){
            $_SESSION[$domain]['pre_construction_page'] = array('uri' => uri_string(),'jobid_position'=>3);
        }*/
        if(in_array($method, array('construction_photos','notes','construction_documents'))){
            $_SESSION[$domain]['construction_page'] = array('uri' => uri_string(),'jobid_position'=>3);
        }
    }
    public function development_document_search($development_id, $search) {
		$this->wbs_helper->is_own_job($development_id);
        $this->developments_model->development_document_search($development_id, $search);
    }

    public function developments_list_contractor() {

        $data['title'] = 'Developments';
        $get = $_GET;
        $developments = $this->developments_model->get_developments_list_contractor();
        $data['developments'] = $developments;

        $this->load->view('developments/development_list_contractor', $data);
    }

    public function change_development_status_contractor($status, $development_city, $development_name) {

        if($this->user_app_role == 'contractor') return;

        $developments = $this->developments_model->change_development_status_contractor($status, $development_city, $development_name);
        //echo $developments;

        $data = '<table><tbody>';

        if ($status != 2) {

            for ($i = 1; $i <= count($developments); $i++) {
                $j = 1;

                if ($developments[$i][$j + 1] == $status) {
                    $data .= '<tr id="check_' . $developments[$i][$j] . '" onclick="setdevelopmentid(' . $developments[$i][$j] . ');"><td><span>' . $developments[$i][$j + 2] . '</span><a style="display: none;" href="development_detail/' . $developments[$i][$j] . '">' . $developments[$i][$j + 2] . '</a></td></tr>';
                }
            }
        } elseif ($status == 2 && $development_city != '0') {

            for ($i = 1; $i <= count($developments); $i++) {
                $j = 1;
                $data .= '<tr id="check_' . $developments[$i][$j] . '" onclick="setdevelopmentid(' . $developments[$i][$j] . ');"><td><span>' . $developments[$i][$j + 2] . '</span><a style="display: none;" href="development_detail/' . $developments[$i][$j] . '">' . $developments[$i][$j + 2] . '</a></td></tr>';
            }
        } elseif ($development_name == 'ZiaurRahman123') {
            foreach ($developments as $development) {
                $user = $this->session->userdata('user');
                $user_uid = $user->uid;
                $this->db->select('user_permission');
                $this->db->where('uid', $user_uid);
                $user = $this->db->get('users')->row();
                $user_permissions = $user->user_permission;
                $user_permission_arr = explode(",", $user_permissions);
                for ($a = 0; $a < count($user_permission_arr); $a++) {
                    if ($user_permission_arr[$a] == $development->id) {
                        $data .= '<tr id="check_' . $development->id . '" onclick="setdevelopmentid(' . $development->id . ');"><td><span>' . $development->development_name . '</span><a style="display: none;" href="development_detail/' . $development->id . '">' . $development->development_name . '</a></td></tr>';
                    } // if condition end;
                } // for loop end;
            }
        } else {
            foreach ($developments as $development) {
                $user = $this->session->userdata('user');
                $user_uid = $user->uid;
                $this->db->select('user_permission');
                $this->db->where('uid', $user_uid);
                $user = $this->db->get('users')->row();
                $user_permissions = $user->user_permission;
                $user_permission_arr = explode(",", $user_permissions);
                for ($a = 0; $a < count($user_permission_arr); $a++) {
                    if ($user_permission_arr[$a] == $development->id) {
                        $data .= '<tr id="check_' . $development->id . '" onclick="setdevelopmentid(' . $development->id . ');"><td><span>' . $development->development_name . '</span><a style="display: none;" href="development_detail/' . $development->id . '">' . $development->development_name . '</a></td></tr>';
                    } // if condition end;
                } // for loop end;
            }
        }


        $data .= '</tbody></table>';
        print_r($data);
    }

    public function index() {
        $data['title'] = 'Project';
        $data['maincontent'] = $this->load->view('developments/developments', $data, true);

        $this->load->view('includes/header', $data);
        //$this->load->view('includes/sidebar',$data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function developments_list($sort_by = 'cid', $order_by = 'desc', $offset = 0) {


        $data['title'] = 'Developments';
        $get = $_GET;
        $this->limit = 50;
        $developments = $this->developments_model->get_developments_list($sort_by, $order_by, $offset, $this->limit, $get)->result();
        $data['developments'] = $developments;

        $data['maincontent'] = $this->load->view('developments/developments', $data, true);
        //$data['maincontent'] = $this->load->view('project_list',$data,true);

        $this->load->view('includes/header', $data);
        $this->load->view('includes/project_home_sidebar', $data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function developments_list_overview() {


        $data['title'] = 'Developments';
        $get = $_GET;
        $developments = $this->developments_model->get_developments_list_overview();
        $data['developments'] = $developments;

        $this->load->view('developments/development_list_overview', $data);

        //$this->load->view('home',$data);
    }

    public function development_overview_area() {


        $data['title'] = 'Developments Overview';
        $developments = $this->developments_model->get_development_overview_area()->result();
        $data['developments'] = $developments;

        $data['development_content'] = $this->load->view('developments/development_overview_area', $data, true);

        $this->load->view('includes/header', $data);
        $this->load->view('developments/development_overview_home', $data);
        $this->load->view('developments/development_overview_footer', $data);
    }

    public function change_development_status($status, $development_city, $development_name) {

        if($this->user_app_role == 'contractor') return;

        $developments = $this->developments_model->change_development_status($status, $development_city, $development_name);
        //echo $developments;

        $data = '<table><tbody>';

        if ($status != 2) {

            for ($i = 1; $i <= count($developments); $i++) {
                $j = 1;

                if ($developments[$i][$j + 1] == $status) {
                    $data .= '<tr id="check_' . $developments[$i][$j] . '" onclick="setdevelopmentid(' . $developments[$i][$j] . ');"><td><span>' . $developments[$i][$j + 2] . '</span><a style="display: none;" href="development_detail/' . $developments[$i][$j] . '">' . $developments[$i][$j + 2] . '</a></td></tr>';
                }
            }
        } elseif ($status == 2 && $development_city != '0') {

            for ($i = 1; $i <= count($developments); $i++) {
                $j = 1;
                $data .= '<tr id="check_' . $developments[$i][$j] . '" onclick="setdevelopmentid(' . $developments[$i][$j] . ');"><td><span>' . $developments[$i][$j + 2] . '</span><a style="display: none;" href="development_detail/' . $developments[$i][$j] . '">' . $developments[$i][$j + 2] . '</a></td></tr>';
            }
        } elseif ($development_name == 'ZiaurRahman123') {
            foreach ($developments as $development) {
                $data .= '<tr id="check_' . $developments[$i][$j] . '" onclick="setdevelopmentid(' . $developments[$i][$j] . ');"><td><span>' . $developments[$i][$j + 2] . '</span><a style="display: none;" href="development_detail/' . $developments[$i][$j] . '">' . $developments[$i][$j + 2] . '</a></td></tr>';
            }
        } else {
            foreach ($developments as $development) {
                $data .= '<tr id="check_' . $developments[$i][$j] . '" onclick="setdevelopmentid(' . $developments[$i][$j] . ');"><td><span>' . $developments[$i][$j + 2] . '</span><a style="display: none;" href="development_detail/' . $developments[$i][$j] . '">' . $developments[$i][$j + 2] . '</a></td></tr>';
            }
        }


        $data .= '</tbody></table>';
        print_r($data);
    }

    public function project_add() {

        $user = $this->session->userdata('user');
        $user_id = $user->uid;
        $data['title'] = 'Add Development';
        $data['action'] = site_url('project/project_add');
        $set_project_no = $this->project_model->get_project_no();



        $this->_set_rules();

        if ($this->form_validation->run() === FALSE) {
            // print_r('error 1'); 
            $data['maincontent'] = $this->load->view('project_add', $data, true);
            $this->load->view('includes/header', $data);
            $this->load->view('includes/project_sidebar', $data);
            $this->load->view('home', $data);
            $this->load->view('includes/footer', $data);
        } else {

            $post = $this->input->post();
            $config['upload_path'] = UPLOAD_FILE_PATH_PROJECT;
            $config['allowed_types'] = '*';
            $config['max_size'] = '100000KB';
            $config['overwrite'] = TRUE;


            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $file_insert_id = 0;
            if ($this->upload->do_upload('upload_file')) {
                $upload_data = $this->upload->data();
                // print_r($upload_data); 
                // insert data to file table
                // get latest id from frim table and insert it to loan table
                $file = array(
                    'filename' => $upload_data['file_name'],
                    'filetype' => $upload_data['file_type'],
                    'filesize' => $upload_data['file_size'],
                    'filepath' => $upload_data['full_path'],
                    'filename_custom' => $post['upload_filename'],
                    'created' => date("Y-m-d H:i:s"),
                    'uid' => $user_id
                );
                $file_insert_id = $this->project_model->file_insert($file);
            } else {
                //print 'Error in file uploading...'; 
                //print $this->upload->display_errors() ; 
            }

            $profile = array(
                'project_id' => $set_project_no + 1,
                'project_name' => $this->input->post('project_name'),
                'project_description' => $this->input->post('project_description'),
                'project_status' => $this->input->post('project_status'),
                'created' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            );

            $id = $this->project_model->project_save($profile);
            // set form input name="id"
            $this->validation->id = $id;
            //print 'success'; exit;	
            redirect('project/project_list');
        }
    }

    public function project_delete($cid) {
        // delete project
        $this->project_model->delete($cid);
        // redirect to project list page
        redirect('project/project_list');
    }

    public function development_phase_delete($phase_id) {
        if($this->user_app_role == 'contractor') return;
        $post = $this->input->post();
        $development_id = $post['development_id'];
        /*log*/
        $job_name = $this->db->get_where('construction_development',array('id'=>$development_id),1,0)->row()->development_name;
        $phase_info = $this->db->get_where('construction_development_phase',array('id'=>$phase_id),1,0)->row();
        $cp = str_replace('_',' ',$phase_info->construction_phase);
        $this->wbs_helper->log('Delete phase',"Deleted phase <b>{$phase_info->phase_name}</b>  in <b>{$cp}</b> - <b>{$job_name}</b>");

        $this->developments_model->development_phase_delete($phase_id);

        redirect('constructions/phases_underway/' . $development_id . '/' . $phase_id, 'refresh');
    }

    public function development_task_delete($task_id) {
        if($this->user_app_role == 'contractor') return;
        $post = $this->input->post();
        $development_id = $post['development_id'];
        $phase_id = $post['phase_id'];

        /*log*/
        $job_name = $this->db->get_where('construction_development',array('id'=>$development_id),1,0)->row()->development_name;
        $task_info = $this->db->get_where('construction_development_task',array('id'=>$task_id),1,0)->row();
        $phase_info = $this->db->get_where('construction_development_phase',array('id'=>$phase_id),1,0)->row();
        $cp = str_replace('_',' ',$task_info->construction_phase);
        $this->wbs_helper->log('Delete task',"Deleted task <b>{$task_info->task_name}</b> under phase <b>{$phase_info->phase_name}</b> in <b>{$cp}</b> - <b>{$job_name}</b>");

        $this->developments_model->development_task_delete($task_id);
        redirect('constructions/phases_underway/' . $development_id . '/' . $phase_id, 'refresh');
    }

    function project_update($pid) {

        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $data['title'] = 'Update Project';
        $data['action'] = site_url('project/project_update/' . $pid);


        $this->_set_rules();
        // run validation
        if ($this->form_validation->run() === FALSE) {

            $data['project'] = $this->project_model->get_project_detail($pid)->row();
        } else {
            // save data
            $project_update = array(
                'project_id' => $this->input->post('project_id'),
                'project_name' => $this->input->post('project_name'),
                'project_description' => $this->input->post('project_description'),
                'project_status' => $this->input->post('project_status'),
                'updated' => date("Y-m-d H:i:s"),
                'updated_by' => $user_id
            );
            //var_dump($Student);
            $this->project_model->update($pid, $project_update);
            //$data['project'] = (array)$this->project_profile_model->get_by_cid($cid)->row();
            redirect('project/project_list');
        }

        // load view
        $data['maincontent'] = $this->load->view('project_add', $data, true);

        $this->load->view('includes/header', $data);
        //$this->load->view('includes/sidebar',$data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer', $data);
    }

    function _set_rules() {
        //$this->form_validation->set_rules('compname', 'compname', 'trim|required|is_unique[project_profile.compname]');
        $this->form_validation->set_rules('project_id', 'Project Id', 'callback_project_id');
        $this->form_validation->set_rules('project_name', 'Project Name');
        //$this->form_validation->set_rules('project_name', 'Project Name', 'required|min_length[5]|max_length[12]');
        // $this->form_validation->set_rules('email_addr_1', 'Email', 'required|valid_email|is_unique[project_profile.email_addr_1]');
    }

    public function milestone_delete($did, $mid) {
		$this->wbs_helper->is_own_job($did);
        $this->developments_model->milestone_delete($mid);
        // redirect to project list page
        redirect('constructions/development_detail/' . $did);
    }

    function construction_detail($pid = 0) {
        $user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;
		
		$this->wbs_helper->is_own_job($pid);

        if ($pid <= 0) {
            redirect('constructions/developments_list');
        }
		$domain = $_SERVER['SERVER_NAME'];
		$data['current_job'] = $pid;
		$_SESSION[$domain]['current_job'] = $pid;

        $data['development_id'] = $pid;

		$this->db->select("id,contact_first_name,contact_last_name");
        $this->db->where_in('wp_company_id', $wp_company_id);
		$data['contacts'] = $this->db->get('contact_contact_list')->result();

        $development = $this->developments_model->get_development_detail($pid)->row();
        $data['development_details'] = $development;

        $feature_photo_id = $development->fid;
        $data['feature_photo'] = $this->developments_model->get_feature_photo($feature_photo_id);

        $development_milestone = $this->developments_model->get_development_milestone_detail($development->id)->result();
        $data['milestone_details'] = $development_milestone;

        //$emp = $this->employee_profile_model->emp_load($salary->eid);

        $data['title'] = $development->development_name;
        $data['number_of_stages'] = $development->number_of_stages;
        $data['development_details'] = $development;
        $data['templates'] = $this->db->query("SELECT id, template_name FROM construction_template where wp_company_id='$wp_company_id'")->result_array();

        //$data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true);
        $jobs_query = "SELECT dev.*, construction_template.template_name FROM construction_development dev LEFT JOIN construction_template ON dev.tid = construction_template.id
						   WHERE dev.is_unit = 0 AND (dev.parent_unit IS NULL OR dev.parent_unit = {$pid}) AND dev.wp_company_id = {$wp_company_id}
						   ORDER BY dev.`id` DESC";
        $data['admindevelopments'] = $this->db->query($jobs_query)->result();
        $data['is_unit'] = $development->is_unit;
        $data['user_app_role'] = $this->user_app_role;

        /*getting tendering templates*/
        $data['tendering_templates'] = $this->db->get_where('construction_tendering_templates',array('wp_company_id'=>$this->wp_company_id))->result();

        $data['development_content'] = $this->load->view('developments/development_detail', $data, true);

        $this->load->view('includes/header', $data);
        //$this->load->view('developments/development_sidebar',$data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);

        /*log*/
        $this->wbs_helper->log('Job Information','Viewed details of job:<b>'.$development->development_name.'</b>');

    }

    public function construction_photos($pid = 0, $month = '') {
		
		$this->wbs_helper->is_own_job($pid);

        $cp = $_GET['cp'];

        /*task #4581*/
        $job_id_arr = array($pid);
        $this->db->select('id');
        $child_jobs = $this->db->get_where('construction_development',array('parent_unit' => $pid))->result();
        foreach($child_jobs as $child){
            $job_id_arr[] = $child->id;
        }
        /***********/

        if($month == ''){
            //$row = $this->db->query("select max(created) c from construction_development_photos where construction_phase = '{$cp}' AND project_id = ".$pid)->row();
            /*task #4581*/
            $row = $this->db->query("select max(created) c from construction_development_photos where construction_phase = '{$cp}' AND project_id  in (".implode(',',$job_id_arr).")")->row();
            if($row){

                $month = date('Y-n',$row->c);
            }else{

                $month = date('Y-n');
            }
        }

		if($month == 'all')
		{
			$data['month'] = date('Y-n');
		}
		else
		{
        	$data['month'] = $month;
		}
        $development = $this->developments_model->get_development_detail($pid)->row();
        $data['development_details'] = $development;
        $data['title'] = $development->development_name;
        $data['user_app_role']  = $this->user_app_role;
        $data['development_id'] = $pid;
        //$data['photos'] = $this->developments_model->getDevelopmentPhotos($pid, $month, $cp,$this->user_app_role)->result();
        /*task #4581*/
        $data['photos'] = $this->developments_model->getDevelopmentPhotos($job_id_arr, $month, $cp,$this->user_app_role)->result();
        /************/

        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($pid);

        //$data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true); 
        $data['development_content'] = $this->load->view('developments/development_photos', $data, true);

        $this->load->view('includes/header', $data);
        //$this->load->view('developments/development_sidebar',$data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);

        /*log*/
        $cp = str_replace('_','-',$_GET['cp']);
        $this->wbs_helper->log('Photos','Viewed '.$cp.' photos for job: <b>'.$development->development_name.'</b>.');
    }

    public function photo_notes($photo_id = '', $dev_id = '') {
		
		$this->wbs_helper->is_own_job($dev_id);
		
        $development = $this->developments_model->get_development_detail($dev_id)->row();
        $data['development_details'] = $development;
        $data['title'] = $development->development_name;

        $data['development_id'] = $dev_id;
        $data['photo'] = $this->developments_model->getDevelopmentPhoto($photo_id)->row();

        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($dev_id);
        $data['request_info'] = $this->developments_model->getDevelopmentsInfo($dev_id);

        $prev_notes = $this->developments_model->getPriviousDevelopmentphotoNotes($photo_id);
        $data['prev_notes'] = $this->notes_image_tmpl($prev_notes, $dev_id);

        $data['development_content'] = $this->load->view('developments/development_photo_notes_view', $data, true);

        $this->load->view('includes/header', $data);
        //$this->load->view('developments/development_sidebar',$data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function insert_photo_notes($fid, $notify_user_id) {
        $user = $this->session->userdata('user');

        $note = $_GET['notes'];
        $dev_id = $_GET['dev_id'];
		
		$this->wbs_helper->is_own_job($dev_id);

        $user_id = $user->uid;
        $user_email = $user->email;
        $user_name = $user->username;
        $user_role = $user->rid;
        $note0 = urldecode($note);
        $now = date('Y-m-d H:i:s');

        $note1 = str_replace("forward_slash", "/", $note0);
        $note2 = str_replace("sign_of_hash", "#", $note1);
        $note3 = str_replace("sign_of_intertogation", "?", $note2);
        $note4 = str_replace("sign_of_plus", "+", $note3);
        $note5 = str_replace("sign_of_exclamation", "!", $note4);
        $note6 = str_replace("percentage", "%", $note5);
        $note7 = str_replace("back_slash", "\\", $note6);

        $note_body = $note7;
        $insert_note = $this->developments_model->insertPhotoNote($fid, $note_body, $user_id, $notify_user_id, $now);
        $prev_notes = $this->developments_model->getPriviousDevelopmentphotoNotes($fid);
        echo $this->notes_image_tmpl($prev_notes);


        $request_info = $this->developments_model->getDevelopmentsInfo($dev_id);

        $job_number = $request_info->job_number;
        $request_title = $request_info->development_name;
        $request_created_by = $request_info->created_by;

        $from = $user_email;
        $notes_from = $user_name;
        $subject = 'You have a note from ' . $notes_from;

        $notify_user_info = $this->developments_model->get_user_info($notify_user_id);
        foreach ($notify_user_info as $user_info) {
            $user_name1[] = $user->username;
            $notify_user_email[] = $user_info->email;
        }
        $assign_user_name = implode(", ", $user_name1);
        $notify_user_to = implode(", ", $notify_user_email);


        $from2 = $user_email;
        $notes_from2 = $user_name;
        //$subject2 = 'You have a note from ' . $notes_from2;

        $subject2 = 'New Photo Note from Construction Management System: Job#' . $job_number . ' - ' . $request_title;

        $headers2 = "From: " . $from2 . "\r\n";
        $headers2 .= "Reply-To: " . $notify_user_to . "\r\n";
        //$headers .= "CC: ". $cc . "\r\n";
        $headers2 .= "MIME-Version: 1.0\r\n";
        $headers2 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $message2 = '';
        $message2 .= '<html><body>';
        $message2 .= '<table border="0" rules="all" style="border-color: #666;" cellpadding="10">';    
        $message2 .= "<tr><td><strong>Job Number:</strong></td><td> " . $job_number . "</td></tr>";
        $message2 .= "<tr><td><strong>Job Title:</strong></td><td> " . $request_title . "</td></tr>";
        $message2 .= "<tr><td><strong>Task Notes:</strong></td><td> " . $note_body . "</td></tr>";
        $message2 .= "<tr><td><strong>Notified:</strong></td><td> " . $assign_user_name . "</td></tr>";
        $message2 .= "<tr><td><strong>Notes Form:</strong></td><td> " . $notes_from2 . "</td></tr>";
        $message2 .= "<tr><td><strong>URL:</strong></td><td> " . base_url() . "constructions/photo_notes/" . $fid . "/" . $dev_id . "</td></tr>";
        $message2 .= "</table>";
        $message2 .= "</body></html>";
        //$msg_body='message body';
        $msg_body2 = $message2;
        mail($notify_user_to, $subject2, $msg_body2, $headers2);

        /*log*/
        $photo = $this->db->get_where('construction_development_photos',array('id'=>$fid),1,0)->row();
        $development = $this->db->get_where('construction_development',array('id' => $photo->project_id),1,0)->row();
        $cp = str_replace('_','-',$_GET['cp']);
        $this->wbs_helper->log('Photo note',"Added note for photo <b>{$photo->filename}</b> in job: <b>{$development->development_name}</b>");

    }

    public function photo_notes_delete($pid, $noteid) {

        $this->developments_model->deleteDevelopmentphotoNotes($noteid);
        $prev_notes = $this->developments_model->getPriviousDevelopmentphotoNotes($pid);
        echo $this->notes_image_tmpl($prev_notes);
    }

    public function upload_development_photo($pid = 0) {
		
		$this->wbs_helper->is_own_job($pid);

        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $config['upload_path'] = UPLOAD_DEVELOPMENT_IMAGE_PATH;
        $config['allowed_types'] = '*';
        $config['max_size'] = '100000KB';
        $config['overwrite'] = TRUE;
        $this->load->library('upload');

        $photo_insert_id = array();
        $preview = "";
        $files = $_FILES;
        for ($k = 0; $k < count($files['photoimg']['name']); $k++) {

            $this->upload->initialize($config);

            $_FILES['photoimg']['name']= $files['photoimg']['name'][$k];
            $_FILES['photoimg']['type']= $files['photoimg']['type'][$k];
            $_FILES['photoimg']['tmp_name']= $files['photoimg']['tmp_name'][$k];
            $_FILES['photoimg']['error']= $files['photoimg']['error'][$k];
            $_FILES['photoimg']['size']= $files['photoimg']['size'][$k];

            if ($this->upload->do_upload('photoimg')) {
                $upload_data = $this->upload->data();
                $preview .= '<img width="245" height="245" src="' . base_url() . 'uploads/development/' . $upload_data['file_name'] . '"/>';
                //print_r($upload_data);
                $document = array(
                    'project_id' => $pid,
                    'filename' => $upload_data['file_name'],
                    'filetype' => $upload_data['file_type'],
                    'filesize' => $upload_data['file_size'],
                    'filepath' => $upload_data['full_path'],
                    'created' => strtotime(date("Y-m-d H:i:s")),
                    'uid' => $user_id,
                    'construction_phase' => $_GET['cp']
                );
                $photo_insert_id[] = $this->developments_model->project_photo_insert($document);

                /*log*/
                $cp = str_replace('_','-',$_GET['cp']);
                $development = $this->db->get_where('construction_development',array('id' => $pid),1,0)->row();
                $this->wbs_helper->log('Photo Upload','Uploaded <b>'.$upload_data['file_name'].'</b>  for '.$cp.': <b>'.$development->development_name.'</b>.');

            } else {
                echo 'Error in file uploading...';
                print $this->upload->display_errors(); exit;
            }
        }
        echo $preview;
        echo '<input type="hidden" id="development_photo_id" value="' . implode(',',$photo_insert_id) . '" />';
    }

    public function save_development_photo($pid = 0) {

		$this->wbs_helper->is_own_job($pid);
		
        $data['title'] = 'Development Photos';
        $data['development_id'] = $pid;
        $post = $this->input->post();

        $photo_insert_id = $this->input->post('photo_insert_id');
        $photo_insert_id = explode(',',$photo_insert_id);

		if( $this->input->post('photo_permission') == ''){
			$photo_permission = 1;
		}
		
        $photo_info = array(
            'photo_caption' => $this->input->post('photo_caption'),
            'photo_permission' => $photo_permission
        );

        foreach($photo_insert_id as $photo_id){

            $this->developments_model->save_project_photo_info($photo_id, $photo_info);

            /*log*/
            $photo_name = $this->db->get_where('construction_development_photos',array('id'=>$photo_id),1,0)->row()->filename;
            $cp = str_replace('_','-',$_GET['cp']);
            $development = $this->db->get_where('construction_development',array('id' => $pid),1,0)->row();
            $this->wbs_helper->log('Photo caption',"Added caption <b>{$post['photo_caption']}</b> for photo <b>{$photo_name}</b> in {$cp}: {$development->development_name}");
        }


        redirect('constructions/construction_photos/' . $pid.'?cp='.$_GET['cp'], 'refresh');
    }

    public function print_developments_photo($photo_id = 0) {

        $data['photo_id'] = $photo_id;
        $photo = $this->developments_model->getDevelopmentPhotoDetail($photo_id);

        $data['title'] = $photo->filename;
        $data['photo'] = $photo;
        $this->load->library('table');
        $this->table->set_empty("");

        $this->table->add_row('Photo Name', $photo->filename);
        $this->table->add_row('Uploaded By', $photo->username);
        $this->table->add_row('Uploaded Date', date('d-m-Y', $photo->created));
        $this->table->add_row('Photo Caption', $photo->photo_caption);
        $this->table->add_row('', '');
        $data['table'] = $this->table->generate();
        $this->table->clear();

        $this->load->view('developments/development_photo_print', $data);
    }

    public function pdf_developments_photo($photo_id) {

        $a = define('PDF_HEADER_STRING1', '');
        $b = define('PDF_HEADER_TITLE1', 'Construction System');

        /*setting the logo*/
        $this->db->select("wp_company.*,wp_file.*");
        $this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
        $this->db->where('wp_company.id', $this->wp_company_id);
        $wpdata = $this->db->get('wp_company')->row();

        define('K_PATH_IMAGES','');
        $logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;
        define ('PDF_HEADER_LOGO', $logo);
        /******************/

        $photo = $this->developments_model->getDevelopmentPhotoDetail($photo_id);
        $photo_image = '<img width="" height="" src="' . base_url() . 'uploads/development/' . $photo->filename . '"/>';

        $this->load->library('table');
        $this->table->set_empty("");

        $this->table->add_row('Photo Name', $photo->filename);
        $this->table->add_row('Uploaded By', $photo->username);
        $this->table->add_row('Uploaded Date', date('d-m-Y', $photo->created));
        $this->table->add_row('Photo Caption', $photo->photo_caption);
        $this->table->add_row('', '');


        $photo_title = array('data' => '<h3>Photo Image</h3>', 'class' => '', 'colspan' => 2);
        $this->table->add_row($photo_title);
        $photo_row = array('data' => $photo_image, 'class' => '', 'colspan' => 2);
        $this->table->add_row($photo_row);

        $data = $this->table->generate();

        $this->wbs_helper->make_list_pdf($data, $a, $b);

        /*log*/
        $photo = $this->db->get_where('construction_development_photos',array('id'=>$photo_id),1,0)->row();
        $development = $this->db->get_where('construction_development',array('id' => $photo->project_id),1,0)->row();
        $this->wbs_helper->log('Photo save',"Saved photo <b>{$photo->filename}</b> in job: <b>{$development->development_name}</b>");
    }

    function mbs_item_list($variables) {
        $items = $variables['items'];
        $title = $variables['title'];
        $type = $variables['type'];
        $attributes = $variables['attributes'];

        // Only output the list container and title, if there are any list items.
        // Check to see whether the block title exists before adding a header.
        // Empty headers are not semantic and present accessibility challenges.
        $output = '<div class="item-list">';
        if (isset($title) && $title !== '') {
            $output .= '<h3>' . $title . '</h3>';
        }

        if (!empty($items)) {
            $output .= "<$type" . $this->mbs_attributes($attributes) . '>';
            $num_items = count($items);
            $i = 0;
            foreach ($items as $item) {
                $attributes = array();
                $children = array();
                $data = '';
                $i++;
                if (is_array($item)) {
                    foreach ($item as $key => $value) {
                        if ($key == 'data') {
                            $data = $value;
                        } elseif ($key == 'children') {
                            $children = $value;
                        } else {
                            $attributes[$key] = $value;
                        }
                    }
                } else {
                    $data = $item;
                }
                if (count($children) > 0) {
                    // Render nested list.
                    $data .= mbs_item_list(array('items' => $children, 'title' => NULL, 'type' => $type, 'attributes' => $attributes));
                }
                if ($i == 1) {
                    $attributes['class'][] = 'first';
                }
                if ($i == $num_items) {
                    $attributes['class'][] = 'last';
                }
                $output .= '<li' . $this->mbs_attributes($attributes) . '>' . $data . "</li>\n";
            }
            $output .= "</$type>";
        }
        $output .= '</div>';
        return $output;
    }

    public function development_overview_old($pid = 0) {
		
        $development = $this->developments_model->get_development_detail($pid)->row();

        $data['title'] = $development->development_name;
        $data['development_id'] = $pid;

        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($pid);
        $data['development_overview_info'] = $this->developments_model->get_development_phase_info($pid)->result();
        $data['stage_overview_info'] = $this->developments_model->get_development_stage_info($pid)->result();

        $data['devlopment_sub_sidebar'] = $this->load->view('developments/devlopment_sub_sidebar', $data, true);
        $data['development_content'] = $this->load->view('developments/development_overview', $data, true);

        $this->load->view('includes/header', $data);
        $this->load->view('developments/development_sidebar', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function construction_overview($pid = 0) {
		
		$this->wbs_helper->is_own_job($pid);

        $development = $this->developments_model->get_development_detail($pid)->row();
        $data['development_details'] = $development;
        $domain = $_SERVER['SERVER_NAME'];
        $construction_phase = $_GET['cp'];

        $_SESSION[$domain]['current_job'] = $pid;

        /*re building the pre construction and construction page in session*/
        /*disabling keeping the tab pages in session. task #3962*/
        /*if(array_key_exists($domain,$_SESSION) && array_key_exists('construction_page',$_SESSION[$domain])){

            $_SESSION[$domain]['construction_page'] = $this->_rebuild_url_in_session($_SESSION[$domain]['construction_page']);
        }
        if(array_key_exists($domain,$_SESSION) && array_key_exists('pre_construction_page',$_SESSION[$domain])){

            $_SESSION[$domain]['pre_construction_page'] = $this->_rebuild_url_in_session($_SESSION[$domain]['pre_construction_page']);
        }
        if(array_key_exists($domain,$_SESSION) && array_key_exists('post_construction_page',$_SESSION[$domain])){

            $_SESSION[$domain]['post_construction_page'] = $this->_rebuild_url_in_session($_SESSION[$domain]['post_construction_page']);
        }*/
        $data['title'] = $development->development_name;
        $data['development_id'] = $pid;
        $data['current_job'] = $pid;

        //$data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($pid);
        
        $data['development_overview_info'] = $this->developments_model->get_development_phase_info($pid, $construction_phase)->result();
        

        $data['stage_overview_info'] = $this->developments_model->get_development_stage_info($pid)->result();

        $data['development_milestone'] = $this->developments_model->get_development_milestone_detail($pid)->result();

        $this->db->select('template.name,template.id template_id, milestones.*');
        $this->db->join('construction_milestone_templates template','template.id = milestones.milestone_template_id');
        $data['milestones'] = $this->db->get_where('construction_development_milestones milestones',array('job_id'=>$pid, 'wp_company_id'=>$this->wp_company_id, 'construction_phase'=>$_GET['cp']))->result();

        $data['milestone_templates'] =  $this->db->get_where('construction_milestone_templates',array('deleted'=>0, 'wp_company_id'=>$this->wp_company_id))->result();


        $data['development_content'] = $this->load->view('developments/developments_overview', $data, true);

		

        $this->load->view('includes/header', $data);
        //$this->load->view('developments/development_sidebar',$data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);

       

        /*log*/
        $cp = str_replace('_','-',$_GET['cp']);
        $this->wbs_helper->log('Dasboard',"Viewed <b>{$cp}</b> dashboard in job <b>{$development->development_name}</b>");


    }
    
    /*show all jobs. task #4283*/
    public function construction_overview_all_jobs() {

		$data['title'] = 'View Jobs';

        /*#4437*/
        if($this->user_app_role == 'investor'){
            $contact =  $this->db->get_where('contact_contact_list',array('system_user_id'=>$this->user_id),1,0)->row();
        }

        $this->db->select("construction_development.development_name, construction_development.id, construction_development.parent_unit, max(construction_development_phase.planned_finished_date) finish_date, IF(MAX(construction_development_phase.planned_finished_date),MAX(construction_development_phase.planned_finished_date),'0000-00-00') finish_date2");

        $this->db->where('wp_company_id', $this->wp_company_id);

        /*#4437*/
        if($this->user_app_role == 'investor' && $contact){
            $this->db->where("CONCAT(',',investor,',')  LIKE '%,{$contact->id},%'");
        }
        $this->db->join('construction_development_phase',"construction_development_phase.development_id = construction_development.id");
        
        $this->db->group_by("construction_development.id");
        $this->db->order_by("finish_date2",'DESC');
        $this->db->order_by("development_name", 'ASC');
        //$this->db->limit(1);
        $data['development_overview_info_all_jobs'] = $this->db->get('construction_development')->result();
        
        $data['show_construction_phase_column'] = true;
        
        $data['development_content'] = $this->load->view('developments/all_developments_overview', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    /*show the combined dashboard for a job / unit. task #3909*/
    public function construction_overview_all($pid = 0) {
		
		$this->wbs_helper->is_own_job($pid);

        $job = $this->db->get_where('construction_development', array('id' => $pid, 'wp_company_id'=>$this->wp_company_id), 1, 0)->row();
		
        if(!$job){
            exit;
        }
        $data['development_details'] = $job;
        $domain = $_SERVER['SERVER_NAME'];
        $_SESSION[$domain]['current_job'] = $job->id;

        $data['title'] = $job->development_name;
        $data['development_id'] = $job->id;
        $data['current_job'] = $job->id;

        $this->db->select("phase.*, job.development_name, job.parent_unit");
        $this->db->join('construction_development job',"job.id = phase.development_id");
        $where = "development_id = {$job->id} ";
        /*if it is unit we will include the child jobs (and child jobs will not have pre-construction)*/
        $where .= "OR (job.parent_unit = {$job->id} AND construction_phase <> 'pre_construction')";
        /*if it is child job we will include the pre-construction of parent unit*/
        if($job->parent_unit){

            $where .= "OR (job.id = {$job->parent_unit} AND construction_phase = 'pre_construction') ";
        }

        $this->db->where($where);
        $this->db->order_by('is_unit','desc');
        $this->db->order_by('job.id','asc');
        $this->db->order_by("FIELD(construction_phase, 'pre_construction', 'construction', 'post_construction')");
        $this->db->order_by('phase.ordering', 'ASC');

        $data['development_overview_info'] = $this->db->get('construction_development_phase phase')->result();

        $this->db->select("templates.name, milestone.*");
        $this->db->join("construction_milestone_templates templates", "templates.id = milestone.milestone_template_id");
        $data['milestones'] = $this->db->get_where('construction_development_milestones milestone',array('job_id'=>$pid))->result();
        $data['show_construction_phase_column'] = true;
        $data['combined_overview'] = 1;
        $data['development_content'] = $this->load->view('developments/developments_overview', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);

        /*log*/
        $this->wbs_helper->log('Overview all',"Visited <b>view all</b> for <b>{$job->development_name}</b>");
    }


    public function development_notes($pid = 0) {
		
		$this->wbs_helper->is_own_job($pid);

        $development = $this->developments_model->get_development_detail($pid)->row();

        $data['title'] = $development->development_name;

        $data['development_id'] = $pid;
        $data['notes'] = $this->developments_model->get_project_notes($pid)->result();
        $data['developments_notes'] = $this->developments_model->get_developments_notes($pid)->result();
        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($pid);

        $data['devlopment_sub_sidebar'] = $this->load->view('developments/devlopment_sub_sidebar', $data, true);
        $data['development_content'] = $this->load->view('developments/development_notes', $data, true);

        $this->load->view('includes/header', $data);
        $this->load->view('developments/development_sidebar', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function search_development_notes($pid = 0) {
		
		$this->wbs_helper->is_own_job($pid);
		
        $data['title'] = 'Development Search Notes';
        $data['development_id'] = $pid;
        $search_notes = $this->input->post('search_notes');

        $data['notes'] = $this->developments_model->get_project_search_notes($pid, $search_notes)->result();

        $data['developments_notes'] = $this->developments_model->get_others_project_search_notes($pid, $search_notes)->result();
        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($pid);

        $data['devlopment_sub_sidebar'] = $this->load->view('developments/devlopment_sub_sidebar', $data, true);
        $data['development_content'] = $this->load->view('developments/development_notes', $data, true);

        $this->load->view('includes/header', $data);
        $this->load->view('developments/development_sidebar', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function development_notes_details($nid) {

        $note_detail = $this->developments_model->get_note_detail($nid)->row();
        //print_r($note_detail);
        echo '<p>Subject: ' . $note_detail->notes_title . '</p>';
        echo '<p>';
        echo date('d-m-Y', strtotime($note_detail->created));
        echo '&nbsp; &nbsp;&nbsp; ';
        echo date("h:i a", strtotime($note_detail->created));
        echo '<span style="float:right">Author :' . $note_detail->username . '</span>';
        echo '</p>';
        echo '<hr style="margin-top:0px;"/>';
        echo '<p>' . $note_detail->notes_body . '</p>';
    }

    public function email_development_notes($nid) {

        $note_detail = $this->developments_model->get_note_detail($nid)->row();
        $note = '<p>Subject: ' . $note_detail->notes_title . '</p>';
        $note .= '<p>';
        $note .= date('d-m-Y', strtotime($note_detail->created));
        echo '&nbsp; &nbsp;&nbsp; ';
        $note .= date("h:i a", strtotime($note_detail->created));
        $note .= '<span style="float:right">Author :' . $note_detail->username . '</span>';
        $note .= '</p>';
        $note .= '<hr style="margin-top:0px;"/>';
        $note .= '<p>' . $note_detail->notes_body . '</p>';

        $html = $note;

        $to = 'alimuls@gmail.com';
        $from = 'mamunjava@gmail.com';
        $cc = 'nurulku02@gmail.com';
        $subject = 'Developments Info';

        $headers = "From: " . $from . "\r\n";
        $headers .= "Reply-To: " . $from . "\r\n";
        $headers .= "CC:" . $cc . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $mail_status = mail($to, $subject, $html, $headers);


        if ($mail_status) {
            echo 'Mail Sent successfully.';
        } else {
            echo 'Mail did not Sent. Try again some later.';
        }
    }

    public function save_development_note($pid = 0) {
		
		$this->wbs_helper->is_own_job($pid);
		
        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $data['title'] = 'Development Photos';
        $data['development_id'] = $pid;
        $data['notes'] = $this->developments_model->get_project_notes($pid)->result();

        $post = $this->input->post();

        $note_data = array(
            'project_id' => $pid,
            'notes_title' => $this->input->post('notes_title'),
            'notes_body' => $this->input->post('notes_body'),
            'created' => date("Y-m-d H:i:s"),
            'notes_by' => $user_id
        );

        $this->developments_model->insert_development_note($note_data);

        redirect('constructions/development_notes/' . $pid, 'refresh');
    }

    public function phases_underway($pid = 0) {
		
		$this->wbs_helper->is_own_job($pid);

        $data['user_app_role'] = $this->user_app_role;
        $data['user_id'] = $this->user_id;

        $development = $this->developments_model->get_development_detail($pid)->row();
        $data['development_details'] = $development;
        $data['title'] = $development->development_name;

        $data['development_id'] = $pid;
        $data['stages_no'] = $this->developments_model->get_stage_list($pid)->result();
        //$data['number_of_stages'] = $this->developments_model->get_stage_list($pid)->result();
        //$data['phase_info'] = $this->project->model->get_phase_info($pid)->result();
        $construction_phase = $_GET['cp'];
        $data['development_phase_info'] = $this->developments_model->get_development_phase_info($pid, $construction_phase)->result();

        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($pid);

        //$data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true); 
        $data['development_content'] = $this->load->view('developments/development_phases_underway', $data, true);

        $this->load->view('includes/header', $data);
        //$this->load->view('developments/development_sidebar',$data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function development_phase_add() {

        if($this->user_app_role == 'contractor') return;

        if ($this->input->post('submit')) {
            $post = $this->input->post();
            $development_id = $post['development_id'];
			
			$this->wbs_helper->is_own_job($development_id);
            
			if ($post['planned_finished_date']) {
                $planned_finished_date = date("Y-m-d", strtotime($post['planned_finished_date']));
            } else {
                $planned_finished_date = '0000-00-00';
            }

            $add_phase = array(
                'development_id' => $development_id,
                'phase_name' => $post['phase_name'],
                'planned_start_date' => date("Y-m-d", strtotime($post['planned_start_date'])),
                'planned_finished_date' => $planned_finished_date,
                'phase_person_responsible' => $post['phase_person_responsible'],
                'note' => $post['note'],
                'construction_phase' => $post['construction_phase']
            );

            $id = $this->developments_model->development_phase_add($add_phase);

            /*log*/
            $job_name = $this->db->get_where('construction_development',array('id'=>$development_id),1,0)->row()->development_name;
            $cp = str_replace('_',' ',$post['construction_phase']);
            $this->wbs_helper->log('Add phase',"Added phase <b>{$post['phase_name']}</b> in <b>{$cp}</b> - <b>{$job_name}</b>");
        }
        redirect('constructions/phases_underway/' . $development_id . '/' . $id . '?cp=' . $post['construction_phase'], 'refresh');
    }

    public function development_task_update($task_id) {

        if($this->user_app_role == 'contractor') return;

        if ($this->input->post('submit')) {
            $post = $this->input->post();
            $development_id = $post['development_id'];
			$this->wbs_helper->is_own_job($development_id);
            $phase_id = $post['phase_id'];
            $phase_id_move = $post['phase_id_move'];

            if ($post['actual_completion_date']) {
                $actual_completion_date = date("Y-m-d", strtotime($post['actual_completion_date']));
            } else {
                $actual_completion_date = '0000-00-00';
            }
            
            /* if the finish date is greater than phase's finish date 
             * we have to update the phase's finish date as well
             */

            $pid = ($post['phase_id'] != "") ? $post['phase_id'] : $post['phase_id_move'];
            $sql = "SELECT * 
                FROM construction_development_phase
                WHERE id = {$pid}";
            $phase_info = $this->db->query($sql)->row();
            if($actual_completion_date > $phase_info->planned_finished_date){
                /*log*/
                $this->wbs_helper->log('Update phase',"Updated phase finish date: <b>{$phase_info->phase_name}</b> due to update of task - {$task_id}");

                $this->_set_phase_dates($pid, $phase_info->planned_start_date, $phase_info->development_id, $phase_info->construction_phase,$actual_completion_date, $post['update_task_dates']); // task #4046
            }

            /*log*/
            $job_name = $this->db->get_where('construction_development',array('id'=>$development_id),1,0)->row()->development_name;
            $task_info = $this->db->get_where('construction_development_task',array('id'=>$task_id),1,0)->row();
            $txt = ($task_info->task_name != $post['task_name'] ) ? "(rename to <b>{$post['task_name']}</b>)" : "";
            $cp = str_replace('_',' ',$task_info->construction_phase);
            $this->wbs_helper->log('Update task',"Updated task <b>{$task_info->task_name}</b> {$txt} in <b>{$cp}</b> - <b>{$job_name}</b>");

            /*task 4200*/
            /*if any phase is dependent on this task we will change that phase's information*/
            if($task_info->construction_template_task_id && $actual_completion_date != '0000-00-00' && $post['update_task_dates'] != -1){
                $start_date = date_create_from_format('Y-m-d',$actual_completion_date);
                $start_date->add(new DateInterval('P1D'));
                $week_day = $start_date->format('w');
                if ($week_day == 6) {
                    $start_date->add(new DateInterval("P2D"));
                } elseif ($week_day == 0) {
                    $start_date->add(new DateInterval("P1D"));
                }
                $start_date = $start_date->format('Y-m-d');
                $this->db->select('construction_development_phase.*');
                $this->db->join('construction_template_phase', 'construction_template_phase.phase_no = construction_development_phase.phase_no AND construction_template_phase.template_id = construction_development_phase.template_id');
                $this->db->where('construction_template_phase.task_dependency',$task_info->construction_template_task_id);
                $dependent_phases = $this->db->get('construction_development_phase')->result();

                $this->processed_phases = array();

                foreach($dependent_phases as $p){

                    /*log*/
                    $this->wbs_helper->log('Update task',"Updated phase <b>{$p->phase_name}</b> as dependent of task {$task_info->task_name},  <b>{$cp}</b> - <b>{$job_name}</b>");

                    $this->_set_phase_dates($p->id, $start_date, $development_id, $p->construction_phase,'', $post['update_task_dates']);
                }
            }

            /* updating the task */
            $update_task = array(
                'phase_id' => $phase_id_move,
                'task_name' => $post['task_name'],
                'task_start_date' => date("Y-m-d", strtotime($post['task_start_date'])),
                'actual_completion_date' => $actual_completion_date,
                'task_person_responsible' => $post['task_person_responsible'],
                'note' => $post['note'],
                'task_category' => $post['contact_category'],
                'task_company' => $post['contact_company']
            );
            $this->developments_model->development_task_update($task_id, $update_task);

            /*if task's start date is less than the phase's start date we will update the phase's start date and remove
              any dependency.
              task #3961
            */
            $this->db->where('id',$phase_id_move);
            $this->db->where('planned_start_date > "'.date("Y-m-d", strtotime($post['task_start_date'])).'"');
            $this->db->update('construction_development_phase',array('planned_start_date' => date("Y-m-d", strtotime($post['task_start_date'])), 'dont_use_dependency' => 1));

            /*log*/
            $p_name = $this->db->get_where('construction_development_phase',array('id'=>$phase_id_move),1,0)->row()->phase_name;
            $this->wbs_helper->log('Update phase',"Updated phase: {$p_name} to adjust with start date of <b>{$task_info->task_name}</b> in <b>{$cp}</b> - <b>{$job_name}</b>");

			// after update task it will send mail to person responsible
			// task #4291
			$user = $this->session->userdata('user');
            $notify_user = $post['task_person_responsible'];

			$form_email = $user->email;

			$this->db->select('contact_first_name,contact_last_name,contact_email');
			$this->db->where('id', $notify_user);
			$notify_user_info = $this->db->get('contact_contact_list')->row();
			$notify_user_email = $notify_user_info->contact_email;
			$notify_user_name = $notify_user_info->contact_first_name.' '.$notify_user_info->contact_last_name;

			$this->db->select('development_name');
			$this->db->where('id', $development_id);
			$dev_info = $this->db->get('construction_development')->row();
			$dev_name = $dev_info->development_name;
			
			$this->db->select('phase_name');
			$this->db->where('id',$phase_id_move);
            $phase_info = $this->db->get('construction_development_phase')->row();
            $phase_name = $phase_info->phase_name;
            
			if($post['task_start_date']){
				$start_date = ' - '.date('d/m/Y',strtotime($post['task_start_date']));
			}else{
				$start_date = '';
			}
			if($post['actual_completion_date']){
				$end_date = ' - '.date('d/m/Y',strtotime($post['actual_completion_date']));
			}else{
				$end_date = '';
			}

			$this->email_arr[] = $notify_user."# Phase Name - ".$phase_name." <br> Task Name - ".$post['task_name']."<br> Start Date - ".$start_date. " End Date - ".$end_date."<br>";


            
			if(!empty($notify_user))
			{
 
				for($i=0;$i<count($this->email_arr);$i++){

					$email_arr_split = explode('#',$this->email_arr[$i]);
					$not_user_id[] = $email_arr_split[0];
					$email_html = $email_arr_split[1];

					if(in_array($email_arr_split[0],$not_user_id)){
						$not_user_html[$email_arr_split[0]] = $not_user_html[$email_arr_split[0]].$email_html;
					}else{
						$not_user_html[$email_arr_split[0]] = $email_html;
					}

				}

				$arr_key = array_keys($not_user_html);

				$this->db->select("wp_company.*,wp_file.*");
				$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
			 	$this->db->where('wp_company.id', $this->wp_company_id);	
				$wpdata = $this->db->get('wp_company')->row();
				$logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;
				
				// start loop for all person responsible
 				for($j=0;$j<count($arr_key);$j++){

			      	$form_email = $user->email;
	
					$this->db->select('contact_first_name,contact_last_name,contact_email');
	                $this->db->where('id', $arr_key[$j]);
	                $notify_user_info = $this->db->get('contact_contact_list')->row();
					$notify_user_email = $notify_user_info->contact_email;
					$notify_user_name = $notify_user_info->contact_first_name.' '.$notify_user_info->contact_last_name;
	
					$this->db->select('development_name, job_number');
	                $this->db->where('id', $development_id);
	                $dev_info = $this->db->get('construction_development')->row();
					$dev_name = $dev_info->development_name;
					$job_number = $dev_info->job_number;
					if($post['planned_start_date']){
						$start_date = ' - '.date('d/m/Y',strtotime($post['planned_start_date']));
					}else{
						$start_date = '';
					}
					if($post['planned_finished_date']){
						$end_date = ' - '.date('d/m/Y',strtotime($post['planned_finished_date']));
					}else{
						$end_date = '';
					}
					
	
					$subject = 'New Updated from '.$this->client_company_name.' Construction System - Job Number: '.$job_number.' -  Job Name: '.$job_name;
				
					$headers = "From: ".$form_email . "\r\n";
					$headers .= "Reply-To: ". $form_email . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				
					$message= '';
			        $message .= '<html><body>';	
					$message .= '<table cellpadding="10" cellspacing="0" width="100%">';
					$message .= '<tr><td colspan="2" align="center" style="background-color:#d72235; height:50px; width:100%"></td></tr>';
					$message .= '<tr><td colspan="2" align="center" style="background-color:#fdb93a; height:5px; width:100%"></td></tr>';
					$message .= '<tr><td colspan="2" align="center"><img src="'.$logo.'" width="300" /></td></tr>';
					$message .= "Hi <strong>".$notify_user_name."</strong>,<br />";
			        $message .= 'Task / phase you are assigned to have changed. See it below:<br /><table>';
					
					$message .= '<tr><td style="border-top:1px solid #ccc">'.$not_user_html[$arr_key[$j]].'</td></tr>';
						
					$message .= "</table>";

					$message .= '<tr><td colspan="2" align="center" style="background-color:#fdb93a; height:5px; width:100%"></td></tr>';
					$message .= '<tr><td colspan="2" align="center" style="background-color:#d72235; height:50px; width:100%"></td></tr>';
		            $message .= "</table>";
					
					$message .= "</body></html>";	
					if($this->wp_company_id != 34){
			        	//mail($notify_user_email, $subject, $message, $headers);
					}
				} // end loop
			} // end if notify user is not empty
        } // end if sublit 
        redirect('constructions/phases_underway/' . $development_id . '/' . $phase_id, 'refresh');
    }

    public function get_working_days($start_date, $end_date, $phase_length, $type) {

        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $oneday = new DateInterval("P1D");
        $total_working_days = 0;
        $total_off_days = 0;
        foreach (new DatePeriod($start, $oneday, $end->add($oneday)) as $day) {
            $day_num = $day->format("N");
            if ($day_num < 6) {
                $total_working_days = $total_working_days + 1;
            } else {
                $total_off_days = $total_off_days + 1;
            }
        }

        $total_days = $phase_length + $total_off_days;

        if ($type == 'add') {
            $actual_finished_date = date('Y-m-d', strtotime($start_date . ' + ' . $total_days . ' days'));
        } else {
            $actual_finished_date = date('Y-m-d', strtotime($start_date . ' - ' . $total_days . ' days'));
        }


        return $actual_finished_date;
    }

	function number_of_working_days($from, $to) {
		$day_n = date('D', strtotime($to));
		if($day_n=='Sat'){
			$actual_finished_date = date('Y-m-d', strtotime($from . ' + 3 days'));			
		}else{
			$actual_finished_date = $to;	
		}
		return $actual_finished_date;
	}

    public function development_phase_update($phase_id, $construction_phase) {

        if($this->user_app_role == 'contractor') return;

        if ($this->input->post('submit')) {
            $post = $this->input->post();
            $development_id = $post['development_id'];
			$this->wbs_helper->is_own_job($development_id);
            $phase_info = $this->db->get_where('construction_development_phase',array('id'=>$phase_id),1,0)->row();

            $this->db->select('`phase_length`,`planned_finished_date`,`ordering`');
            $this->db->where('id', $phase_id);
            $row = $this->db->get('construction_development_phase')->row();
            $ordering = $row->ordering;
            $planned_finished_date = $row->planned_finished_date;
            $phase_length = $row->phase_length;
            $planned_finished_date_new = date('Y-m-d', strtotime($post['planned_finished_date']));


            if (!empty($post['planned_finished_date'])) {

                if ($planned_finished_date > $planned_finished_date_new) {
                    $datediff = strtotime($planned_finished_date) - strtotime($planned_finished_date_new);
                    $day = floor($datediff / (60 * 60 * 24));
                } else {

                    $datediff = strtotime($planned_finished_date_new) - strtotime($planned_finished_date);
                    $day = floor($datediff / (60 * 60 * 24));
                }

                if ($planned_finished_date != $planned_finished_date_new) {
                    $this->db->select('`id`,`ordering`');
                    $this->db->where('development_id', $development_id);
                    $results = $this->db->get('construction_development_phase')->result();

                    $job_phase_update = array(
                        'planned_finished_date' => $planned_finished_date_new
                    );
                    $this->developments_model->development_phase_update($phase_id, $job_phase_update);

                    foreach ($results as $result) {

                        if ($result->ordering >= $ordering) {

                            $this->db->select('`construction_job_phase_dependency.dependency`, `construction_development_phase.planned_finished_date`');
                            $this->db->join('construction_development_phase', 'construction_development_phase.id=construction_job_phase_dependency.dependency_phase_id', 'left');
                            $this->db->where('construction_job_phase_dependency.dependency_phase_id', $result->id);
                            $row = $this->db->get('construction_job_phase_dependency')->row();

                            $dependency_id = $row->dependency;
                            $planned_start_date_input = $row->planned_finished_date;
							

                            if (!empty($dependency_id)) {

                                $this->db->select('`construction_development_phase.planned_finished_date`,`construction_development_phase.phase_length`');
                                $this->db->where('id', $dependency_id);
                                $row1 = $this->db->get('construction_development_phase')->row();

                                if ($row1->planned_finished_date > '0000-00-00') {
                                    $date1 = $row1->planned_finished_date;
                                    if ($planned_finished_date > $planned_finished_date_new) {

                                        $planned_finished_date_input = date('Y-m-d', strtotime($date1 . ' - ' . $day . ' days'));
                                        $planned_finished_date_input = $this->get_working_days($date1, $planned_finished_date_input, $day, 'deduct');
                                    } else {
                                        $planned_finished_date_input = date('Y-m-d', strtotime($date1 . ' + ' . $day . ' days'));
                                        $planned_finished_date_input = $this->get_working_days($date1, $planned_finished_date_input, $day, 'add');
                                    }
									
                                } else {
                                    $phase_length1 = $row1->phase_length;

                                    if ($planned_finished_date > $planned_finished_date_new) {
                                        $date1 = $planned_start_date_input;
                                        $date2 = date('Y-m-d', strtotime($date1 . ' - ' . $day . ' days'));
                                        $planned_finished_date_input = date('Y-m-d', strtotime($date2 . ' + ' . $phase_length1 . ' days'));
                                        $planned_finished_date_input = $this->get_working_days($date1, $planned_finished_date_input, $phase_length, 'add');
                                    } else {
                                        $day1 = $day + $phase_length1;
                                        $date1 = $planned_start_date_input;
                                        $planned_finished_date_input = date('Y-m-d', strtotime($date1 . ' + ' . $day1 . ' days'));
                                        $planned_finished_date_input = $this->get_working_days($date1, $planned_finished_date_input, $phase_length1, 'add');
                                    }
                                }
								$planned_start_date_input1 = date('Y-m-d', strtotime($planned_start_date_input . ' + 1 days'));
								$planned_start_date_input2 = $this->number_of_working_days($planned_start_date_input, $planned_start_date_input1);
								

                                $stage_phase_update_dependency = array(
                                    'planned_start_date' => $planned_start_date_input2,
                                    'planned_finished_date' => $planned_finished_date_input
                                );
                                $this->developments_model->development_phase_update($dependency_id, $stage_phase_update_dependency);

                                $this->db->select('`construction_development_task.id`, `construction_development_task.task_start_date`, `construction_development_task.actual_completion_date`');
                                $this->db->where('phase_id', $dependency_id);
                                $results1 = $this->db->get('construction_development_task')->result();
                                foreach ($results1 as $result) {
                                    if ($result->task_start_date == '0000-00-00') {
                                        $task_start_date = '0000-00-00';
                                    } else {
                                        $date2 = $result->task_start_date;
                                        if ($planned_finished_date > $planned_finished_date_new) {
                                            $task_start_date = date('Y-m-d', strtotime($date2 . ' - ' . $day . ' days'));
                                        } else {
                                            $task_start_date = date('Y-m-d', strtotime($date2 . ' + ' . $day . ' days'));
                                        }
                                    }

                                    if ($result->actual_completion_date == '0000-00-00') {
                                        $planned_completion_date = '0000-00-00';
                                    } else {
                                        $date3 = $result->actual_completion_date;

                                        if ($planned_finished_date > $planned_finished_date_new) {
                                            $planned_completion_date = date('Y-m-d', strtotime($date3 . ' - ' . $day . ' days'));
                                        } else {
                                            $planned_completion_date = date('Y-m-d', strtotime($date3 . ' + ' . $day . ' days'));
                                        }
                                    }
                                    $task_id = $result->id;

                                    $job_task_update_dependency = array(
                                        'task_start_date' => $task_start_date,
                                        'actual_completion_date' => $planned_completion_date
                                    );
                                    $this->developments_model->development_task_update($task_id, $job_task_update_dependency);
                                } // end task loop
                            } // if dependency id end
                        } // greater than ordering 
                    } // ordering loop
                } // if planned start date changed end
            } // if planned_finished_date not empty end 



            if (!empty($post['planned_start_date']) && empty($post['planned_finished_date'])) {

                if ($phase_length == 0) {
                    $phase_length = 5;
                }

                $planned_start_date = $this->wbs_helper->to_mysql_date($post['planned_start_date']);
                $total_planned_finished_date = date('Y-m-d', strtotime($planned_start_date . ' + ' . $phase_length . ' days'));
                $planned_finished_date = $this->get_working_days($planned_start_date, $total_planned_finished_date, $phase_length, 'add');

                $update_phase = array(
                    'phase_name' => $post['phase_name'],
                    'planned_start_date' => $planned_start_date,
                    'planned_finished_date' => $planned_finished_date,
                    'phase_person_responsible' => $post['phase_person_responsible'],
                    'note' => $post['note']
                );
            }


            if (!empty($post['planned_start_date']) && !empty($post['planned_finished_date'])) {

                $planned_start_date = $this->wbs_helper->to_mysql_date($post['planned_start_date']);
                $planned_finished_date = $this->wbs_helper->to_mysql_date($post['planned_finished_date']);

                $update_phase = array(
                    'phase_name' => $post['phase_name'],
                    'planned_start_date' => $planned_start_date,
                    'planned_finished_date' => $planned_finished_date,
                    'phase_person_responsible' => $post['phase_person_responsible'],
                    'note' => $post['note']
                );
            }

            /*is the start date manually updated? than we have to set dont_use_dependency flag*/
            $current_start_date = $this->db->query("select planned_start_date from construction_development_phase where id = {$phase_id}")->row();
            if($current_start_date->planned_start_date != $planned_start_date){
                $update_phase['dont_use_dependency'] = 1;
            }

            $this->developments_model->development_phase_update($phase_id, $update_phase);

            if (!empty($post['dependency'])) {
                $this->developments_model->job_phase_dependency_delete($phase_id);

                $stage_phase_dependency = array(
                    'dependency' => $phase_id,
                    'dependency_phase_id' => $post['dependency'],
                    'dependency_phase_name' => $post['dependency_name']
                );
                $this->developments_model->job_phase_dependency($stage_phase_dependency);
            } else {
                $this->developments_model->job_phase_dependency_delete($phase_id);
            }
            /* by Jafor on 23 Sep 2015 */
            /* Setting the finish date of this phase, start and finish date of all jobs
             * under this phase. also setting the start and finish date of all phases dependent on
             * this phase and start and finish date of jobs under those phases.
             * This is a recursive function.
             * for details see task #1822
             */
            if(!empty($post['planned_start_date'])) {
                //$update_tasks = ($post['planned_finished_date']) ? FALSE : TRUE;
                $this->_set_phase_dates($phase_id, $post['planned_start_date'], $development_id, $_GET['cp'],$post['planned_finished_date'], $post['update_task_dates']);
            }

            /*log*/
            $job_name = $this->db->get_where('construction_development',array('id'=>$development_id),1,0)->row()->development_name;
            $cp = str_replace('_',' ',$phase_info->construction_phase);
            $txt = ($post['phase_name'] != $phase_info->phase_name) ? "(renamed to <b>{$post['phase_name']}</b>)" : "";
            $this->wbs_helper->log('Update phase',"Updated phase <b>{$phase_info->phase_name}</b> {$txt}  in <b>{$cp}</b> - <b>{$job_name}</b>");


			// send mail to person responsible with phase
			// task #4291

            $user = $this->session->userdata('user');
            $notify_user = $post['phase_person_responsible'];
			if(!empty($notify_user))
			{
				for($i=0;$i<count($this->email_arr);$i++){

					$email_arr_split = explode('#',$this->email_arr[$i]);
					$not_user_id[] = $email_arr_split[0];
					$email_html = $email_arr_split[1];

					if(in_array($email_arr_split[0],$not_user_id)){
						$not_user_html[$email_arr_split[0]] = $not_user_html[$email_arr_split[0]].$email_html;
					}else{
						$not_user_html[$email_arr_split[0]] = $email_html;
					}

				}

				$arr_key = array_keys($not_user_html);

				$this->db->select("wp_company.*,wp_file.*");
				$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
			 	$this->db->where('wp_company.id', $this->wp_company_id);	
				$wpdata = $this->db->get('wp_company')->row();
				$logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;
				
				// loop for all person responsible
 				for($j=0;$j<count($arr_key);$j++){

			      	$form_email = $user->email;
	
					$this->db->select('contact_first_name,contact_last_name,contact_email');
	                $this->db->where('id', $arr_key[$j]);
	                $notify_user_info = $this->db->get('contact_contact_list')->row();
					$notify_user_email = $notify_user_info->contact_email;
					$notify_user_name = $notify_user_info->contact_first_name.' '.$notify_user_info->contact_last_name;
	
					$this->db->select('development_name,job_number');
	                $this->db->where('id', $development_id);
	                $dev_info = $this->db->get('construction_development')->row();
					$job_number = $dev_info->job_number;
					$dev_name = $dev_info->development_name;
					if($post['planned_start_date']){
						$start_date = ' - '.date('d/m/Y',strtotime($post['planned_start_date']));
					}else{
						$start_date = '';
					}
					if($post['planned_finished_date']){
						$end_date = ' - '.date('d/m/Y',strtotime($post['planned_finished_date']));
					}else{
						$end_date = '';
					}
					
	
					$subject = 'New Updated from '.$this->client_company_name.' Construction System - Job Number: '.$job_number.' -  Job Name: '.$job_name;
				
					$headers = "From: ".$form_email . "\r\n";
					$headers .= "Reply-To: ". $form_email . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				
					$message= '';
			        $message .= '<html><body>';	
					$message .= '<table cellpadding="10" cellspacing="0" width="100%">';
					$message .= '<tr><td colspan="2" align="center" style="background-color:#d72235; height:50px; width:100%"></td></tr>';
					$message .= '<tr><td colspan="2" align="center" style="background-color:#fdb93a; height:5px; width:100%"></td></tr>';
					$message .= '<tr><td colspan="2" align="center"><img src="'.$logo.'" width="300" /></td></tr>';
					$message .= "Hi <strong>".$notify_user_name."</strong>,<br />";
			        $message .= 'Task / phase you are assigned to have changed. See it below:<br /><table>';
					
					$message .= '<tr><td>'.$not_user_html[$arr_key[$j]].'</td></tr>';
						
					$message .= "</table>";

					$message .= '<tr><td colspan="2" align="center" style="background-color:#fdb93a; height:5px; width:100%"></td></tr>';
					$message .= '<tr><td colspan="2" align="center" style="background-color:#d72235; height:50px; width:100%"></td></tr>';
		            $message .= "</table>";
					
					$message .= "</body></html>";	
					if($this->wp_company_id != 34){
			        	//mail($notify_user_email, $subject, $message, $headers);
					}
				} // end loop
			} // end if notify users is not null             
        } // if submit form 
        redirect('constructions/phases_underway/' . $development_id . '/' . $phase_id, 'refresh');
    }

    public function development_phase_dependency_name_load($id) {

        $this->developments_model->job_phase_dependency_name_load($id);
    }

    public function development_task_add() {

        if($this->user_app_role == 'contractor') return;

        if ($this->input->post('submit')) {
            $post = $this->input->post();
            $development_id = $post['development_id'];
			$this->wbs_helper->is_own_job($development_id);
            $phase_id = $post['phase_id'];
            if ($post['actual_completion_date']) {
                $actual_completion_date = date("Y-m-d", strtotime($post['actual_completion_date']));
            } else {
                $actual_completion_date = '0000-00-00';
            }

            /* if the finish date is greater than phase's finish date
             * we have to update the phase's finish date as well
             */

            $pid = ($post['phase_id'] != "") ? $post['phase_id'] : $post['phase_id_move'];
            $sql = "SELECT *
                FROM construction_development_phase
                WHERE id = {$pid}";
            $phase_info = $this->db->query($sql)->row();
            if($actual_completion_date > $phase_info->planned_finished_date){
                $construction_phase = ($phase_info->is_pre_construction) ? 'pre-construction' : 'construction';
                $this->_set_phase_dates($pid, $phase_info->planned_start_date, $phase_info->development_id, $construction_phase,$actual_completion_date,FALSE);
            }

            /*adding the task*/
            $add_task = array(
                'development_id' => $development_id,
                'phase_id' => $phase_id,
                'task_name' => $post['task_name'],
                'task_person_responsible' => $post['task_person_responsible'],
                'task_start_date' => date("Y-m-d", strtotime($post['task_start_date'])),
                'actual_completion_date' => $actual_completion_date,
                'note' => $post['note'],
                'type_of_task' => $post['type_of_task'],
                'task_category' => $post['contact_category'],
                'task_company' => $post['contact_company']
            );

            $this->developments_model->development_task_add($add_task);

            /*log*/
            $job_name = $this->db->get_where('construction_development',array('id'=>$development_id),1,0)->row()->development_name;
            $phase = $this->db->get_where('construction_development_phase',array('id'=>$phase_id),1,0)->row();
            $cp = str_replace('_',' ',$phase->construction_phase);
            $this->wbs_helper->log('Add task',"Added task <b>{$post['task_name']}</b> under phase <b>{$phase->phase_name}</b> in <b>{$cp}</b> - <b>{$job_name}</b>");

        }
        redirect('constructions/phases_underway/' . $development_id . '/' . $phase_id, 'refresh');
    }

    public function print_development($pid = 0) {

        if ($pid <= 0) {
            redirect('constructions/developments_list');
        }


        $data['development_id'] = $pid;
		
		$this->wbs_helper->is_own_job($pid);

        $development = $this->developments_model->get_development_detail($pid)->row();

        $feature_photo_id = $development->fid;
        $data['feature_photo'] = $this->developments_model->get_defelopment_feature_photo($feature_photo_id)->row();


        $data['title'] = $development->development_name;
        $data['development_details'] = $development;
        $this->load->library('table');
        $this->table->set_empty("");



        $this->table->add_row('Development Name', $development->development_name);

        $this->table->add_row('Development Status', 'Under development');
        $this->table->add_row('', '');

        $this->table->add_row('Development Location', $development->development_location);
        $this->table->add_row('Development Size', $development->development_size);
        $this->table->add_row('Development Land Zone', $development->land_zone);
        $this->table->add_row('Ground Condition', $development->ground_condition);
        $this->table->add_row('Number of Stages', $development->number_of_stages);
        $this->table->add_row('Number of Lots', $development->number_of_lots);

        $this->table->add_row('', '');
        $this->table->add_row('Project Manager', $development->project_manager);
        $this->table->add_row('Civil Manager', $development->civil_engineer);
        $this->table->add_row('Civil Engineer', $development->civil_manager);
        $this->table->add_row('Geo Tech Engineer', $development->geo_tech_engineer);



        $data['table'] = $this->table->generate();
        $this->table->clear();



        $this->load->view('developments/development_print', $data);
    }

    public function email_development($pid = 0) {
		
		$this->wbs_helper->is_own_job($pid);
        
		if ($pid <= 0) {
            redirect('constructions/developments_list');
        }

        $to = 'alimuls@gmail.com';
        $from = 'mamunjava@gmail.com';
        $cc = 'nurulku02@gmail.com';
        $subject = 'Developments Info';

        $headers = "From: " . $from . "\r\n";
        $headers .= "Reply-To: " . $from . "\r\n";
        $headers .= "CC:" . $cc . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


        $data['development_id'] = $pid;
        $development = $this->developments_infromation($pid);

        $html = $development;

        $mail_status = mail($to, $subject, $html, $headers);


        if ($mail_status) {
            echo 'Mail Sent successfully.';
        } else {
            echo 'Mail did not Sent. Try again some later.';
        }

        //redirect('constructions/development_detail/'.$pid);
    }

    public function pdf_developments($pid) {
		
		$this->wbs_helper->is_own_job($pid);

        $a = define('PDF_HEADER_STRING1', '');
        $b = define('PDF_HEADER_TITLE1', 'Horncastle Developments');
        //$all_employees = $this->employee_model->employee_list_print();
        $data = $this->developments_infromation($pid);
        $this->wbs_helper->make_list_pdf($data, $a, $b);
        //redirect('employee/employee_list');
    }

    public function email_dev_photo($photo_id) {

        $photo = $this->developments_model->getDevelopmentPhotoDetail($photo_id);
        $photo_image = '<img width="270" height="250" src="' . base_url() . 'uploads/development/' . $photo->filename . '"/>';

        $this->load->library('table');
        $this->table->set_empty("");

        $this->table->add_row('Photo File Name', $photo->filename);
        $this->table->add_row('Photo Cattion', $photo->photo_caption);
        $this->table->add_row('', '');

        $this->table->add_row('Photo Image', $photo_image);
        $this->table->add_row('Phote  Category', $photo->photo_category);


        $photo_data = $this->table->generate();
        $this->table->clear();



        $to = 'mamunjava@gmail.com';
        $from = 'nurulku02@gmail.com';
        $cc = 'alimuls@gmail.com';
        $subject = 'Developments Photo';

        $headers = "From: " . $from . "\r\n";
        $headers .= "Reply-To: " . $from . "\r\n";
        $headers .= "CC:" . $cc . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $html = '<h1>Developments Photos </h1>';
        $html .= $photo_data;

        $mail_status = mail($to, $subject, $html, $headers);


        if ($mail_status) {
            echo 'Mail Sent successfully.';
        } else {
            echo 'Mail did not Sent. Try again some later.';
        }

        //redirect('constructions/development_detail/'.$pid);
    }

    public function developments_infromation($pid) {

		$this->wbs_helper->is_own_job($pid);

        $development = $this->developments_model->get_development_detail($pid)->row();

        $feature_photo_id = $development->fid;
        $feature_photo = $this->developments_model->get_defelopment_feature_photo($feature_photo_id)->row();

        if (!empty($feature_photo)) {
            $photo = '<img width="" height="" src="' . base_url() . 'uploads/development/' . $feature_photo->filename . '"/>';
        } else {
            $photo = '<img width="" height="" src="' . base_url() . 'images/pms_home.png"/>';
        }


        $this->load->library('table');
        $this->table->set_empty("");
        $this->table->set_caption('<h1>Development Information</h1>');

        $this->table->add_row('Development Name', $development->development_name);
        $this->table->add_row('Development Status', 'Under development');
        $this->table->add_row('', '');

        $this->table->add_row('Development Location', $development->development_location);
        $this->table->add_row('Development Size', $development->development_size);
        $this->table->add_row('Development Land Zone', $development->land_zone);
        $this->table->add_row('Ground Condition', $development->ground_condition);
        $this->table->add_row('Number of Stages', $development->number_of_stages);
        $this->table->add_row('Number of Lots', $development->number_of_lots);

        $this->table->add_row('', '');
        $this->table->add_row('Project Manager', $development->project_manager);
        $this->table->add_row('Civil Manager', $development->civil_engineer);
        $this->table->add_row('Civil Engineer', $development->civil_manager);
        $this->table->add_row('Geo Tech Engineer', $development->geo_tech_engineer);

        $this->table->add_row('', '');
        $photo_title = array('data' => '<h3>Feature Photo</h3>', 'class' => '', 'colspan' => 2);
        $this->table->add_row($photo_title);
        $photo_row = array('data' => $photo, 'class' => '', 'colspan' => 2);
        $this->table->add_row($photo_row);


        $data = $this->table->generate();
        $this->table->clear();

        return $data;
    }

    public function update_phase_status($phase_id, $status) {

        if($this->user_app_role == 'contractor') return;

        $this->developments_model->update_status($phase_id, $status);
    }

    public function update_development_phase_task_status($task_id, $status, $phase_id) {

        if($this->user_app_role == 'contractor') return;

        $this->developments_model->update_development_phase_task_status($task_id, $status, $phase_id);

        /*log*/
        $phase_info = $this->db->get_where('construction_development_phase',array('id'=>$phase_id),1,0)->row();
        $task_name = $this->db->get_where('construction_development_task',array('id'=>$task_id),1,0)->row()->task_name;
        $job_name = $this->db->get_where('construction_development',array('id'=>$phase_info->development_id),1,0)->row()->development_name;
        $cp = str_replace('_',' ',$phase_info->construction_phase);
        $status = ($status) ? "complete":"incomplete";
        $this->wbs_helper->log('Update task status',"Marked task <b>{$task_name}</b> in  phase <b>{$phase_info->phase_name}</b>  in <b>{$cp}</b> - <b>{$job_name}</b> as <b>{$status}</b>");

        /*sending status color to front end*/
        $sql = "select * from construction_development_task where id = {$task_id} limit 0,1";
        $task = $this->db->query($sql)->row();
        $pc_time = strtotime($task->actual_completion_date);
        $start_date_time = strtotime($task->task_start_date);
        $now = date('Y-m-d');
        $today_time = strtotime($now);

        if ($task->development_task_status == '1')
        {
            $phase_bg_color = 'green';
        }
        else if( $task->task_start_date == '0000-00-00')
        {

            $phase_bg_color = 'grey';
        }
        elseif ( $today_time > $start_date_time && $today_time < $pc_time && $task->development_task_status == 0 )
        {

            $phase_bg_color = 'yellow';
        }
        elseif ( $today_time < $start_date_time && $today_time < $pc_time && $task->development_task_status == 0 )
        {

            $phase_bg_color = 'gray';
        }
        elseif($today_time > $pc_time && $task->development_task_status == 0)
        {
            $phase_bg_color = 'red';
        }
        elseif($today_time == $pc_time && $task->development_task_status == 0)
        {
            $phase_bg_color = 'yellow';
        }
        else
        {
            $phase_bg_color = 'yellow';
        }
        echo $phase_bg_color; exit;


    }

    public function phase_status_html($did, $pid)
    {
		$this->wbs_helper->is_own_job($did);
		
        $all_phase_task = $this->developments_model->get_all_development_phase_status($did,$pid)->result();
        $development_phase_info = $this->db->query("select * from construction_development_phase where id = {$pid} limit 0, 1")->row();
        if ($all_phase_task[0]->all_task_status == 0 && $development_phase_info->phase_status == 1) {
            echo '* <img style="margin:0 4px 0 0px" width="22" height="22" src="' . base_url() . 'images/icon/status_complate.png" />';
        } elseif (isset($all_phase_task[0]->all_task_status) && $all_phase_task[0]->all_task_status == 1) {
            echo '<img style="margin:0 4px 0 0px" width="22" height="22" src="' . base_url() . 'images/icon/status_complate.png" />';
        } else {
            echo "";
        }
        exit;

    }

    public function send_development_note_message($pid) {
        $note_id = $this->input->post('note_id');

		$this->wbs_helper->is_own_job($pid);

        $user = $this->session->userdata('user');
        ///$user_id =$user->uid; 
        $user_name = $user->name;
        $user_mail = $user->email;

        $dev_detail = $this->developments_model->get_development_detail($pid)->row();

        $note_detail = $this->developments_model->get_note_author($note_id)->row();
        $note_author_email = $note_detail->useremail;
        $notes_title = $note_detail->notes_title;

        $note_message = 'Name : ' . $user_name . '<br />';
        $note_message .= 'Development : ' . $dev_detail->development_name . '<br />';
        $note_message .= 'Notes : ' . $notes_title . '<br />';
        $note_message .= '<br /><br />';
        $note_message .= $user_name . ' wrote the following message :<br />';
        $note_message .= $this->input->post('notes_message');

        $this->load->library('email');
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);

        $this->email->from($user_mail, $user_name);
        $this->email->to($note_author_email);

        $this->email->subject($user_name . ' has written correspondance for ' . $notes_title);
        $this->email->message($note_message);

        //$this->email->send();
        if (!$this->email->send()) {
            $email_send = 0;
        } else {
            $email_send = 1;
        }

        //echo $this->email->print_debugger();
        redirect('constructions/development_notes/' . $pid . '?sent_email=' . $email_send);
    }

    public function send_development_photo_message() {
        $photo_id = $this->input->post('photo_id');
        $photo_dev_id = $this->input->post('photo_dev_id');

		$this->wbs_helper->is_own_job($photo_dev_id);

        $user = $this->session->userdata('user');
        ///$user_id =$user->uid; 
        $user_name = $user->name;
        $user_mail = $user->email;

        $photo_detail = $this->developments_model->get_photo_author($photo_id)->row();
        $photo_author_email = $photo_detail->useremail;
        $image = $photo_detail->filename;

        $mail_body = 'Your Photo : <img src="' . base_url() . 'uploads/development/' . $image . '" /><br />';
        $mail_body .= 'Photo Caption : ' . $photo_detail->photo_caption . '<br />';
        $mail_body .= 'User Reply <br />' . $this->input->post('photo_message');

        $this->load->library('email');
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);


        $this->email->from($user_mail, $user_name);
        $this->email->to($photo_author_email);

        $this->email->subject('Reply On ' . $image);

        $this->email->message($mail_body);

        if (!$this->email->send()) {
            $email_send = 0;
        } else {
            $email_send = 1;
        }

        //echo $this->email->print_debugger();
        redirect('constructions/construction_photos/' . $photo_dev_id . '?sent_email=' . $email_send);
    }

    public function phase_task_start_date_update($task_id) {

        if($this->user_app_role == 'contractor') return;

        $post = $this->input->post();
        $dev_id = $post['development_id'];

        $task_data = array(
            'task_start_date' => $this->wbs_helper->to_mysql_date($post['planned_start_date'])
        );

        $this->developments_model->development_phase_task_start_date_update($task_id, $task_data);
        redirect('constructions/phases_underway/' . $dev_id);
    }

    public function development_stage_task_start_date_update($task_id) {

        if($this->user_app_role == 'contractor') return;

        $post = $this->input->post();
        $dev_id = $post['development_id'];

        $task_data = array(
            'planned_start_date' => $this->wbs_helper->to_mysql_date($post['planned_start_date'])
        );

        $this->developments_model->development_stage_task_start_date_update($task_id, $task_data);
        redirect('constructions/phases_underway/' . $dev_id);
    }

    public function phase_task_actual_date_update($task_id) {

        if($this->user_app_role == 'contractor') return;

        $post = $this->input->post();
        $dev_id = $post['development_id'];

        $task_data = array(
            'actual_completion_date' => $this->wbs_helper->to_mysql_date($post['actual_completion_date'])
        );

        $this->developments_model->development_phase_task_actual_date_update($task_id, $task_data);
        redirect('constructions/phases_underway/' . $dev_id);
    }

    public function development_stage_task_actual_date_update($task_id) {

        if($this->user_app_role == 'contractor') return;

        $post = $this->input->post();
        $dev_id = $post['development_id'];

        $task_data = array(
            'actual_finished_date' => $this->wbs_helper->to_mysql_date($post['actual_finished_date'])
        );

        $this->developments_model->development_stage_task_actual_date_update($task_id, $task_data);
        redirect('constructions/phases_underway/' . $dev_id);
    }

    public function construction_documents($did, $target_page = 'documents') {
		
		$this->wbs_helper->is_own_job($did);

        $development = $this->developments_model->get_development_detail($did)->row();
        $data['title'] = $development->development_name;
        $data['development_details'] = $development;
        $data['number_of_stages'] = $development->number_of_stages;
        $data['development_id'] = $did;
        $data['user_app_role'] = $this->user_app_role;

        $data['documents'] = $this->developments_model->getDevelopmentDocuments($did, $target_page, $_GET['cp'])->result();

        $data['developments_documents'] = $this->developments_model->getOthersDevelopmentDocuments($did, $target_page, $_GET['cp'])->result();

        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($did);

        //$data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true);        
        $data['development_content'] = $this->load->view('developments/development_documents', $data, true);

        $this->load->view('includes/header', $data);
        //$this->load->view('developments/development_sidebar',$data);
        $this->load->view('developments/development_documents_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function development_documents_bycategory($did, $cid) {

		$this->wbs_helper->is_own_job($did);
		
        $development = $this->developments_model->get_development_detail($did)->row();
        $data['title'] = $development->development_name;
        $data['number_of_stages'] = $development->number_of_stages;
        $data['development_id'] = $did;
        $data['category_id'] = $cid;
        $data['documents'] = $this->developments_model->get_development_documents_bycategory($did, $cid)->result();

        $data['developments_documents'] = $this->developments_model->getOthersDevelopmentDocumentsBycategory($did, $cid)->result();

        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($did);

        $data['devlopment_sub_sidebar'] = $this->load->view('developments/devlopment_sub_sidebar', $data, true);
        $data['development_content'] = $this->load->view('developments/development_documents', $data, true);

        $this->load->view('includes/header', $data);
        $this->load->view('developments/development_sidebar', $data);
        $this->load->view('developments/development_documents_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function development_document_detail($document_id) {

        $document_detail = $this->developments_model->get_document_detail($document_id)->row();

        //print_r($note_detail);
        //echo '<p>Download Document : <a href="'.base_url().'uploads/development/documents/'.$document_detail->filename.'">'.$document_detail->filename_custom.'</a></p>';
        //echo '<p>';
        //echo date('d-m-Y', strtotime($note_detail->created)); 
        //echo '&nbsp; &nbsp;&nbsp; ';
        //echo date("h:i a", strtotime($note_detail->created));
        // echo '</p>';
        // echo '<hr style="margin-top:0px;"/>';
        //echo '<p> Download '.base_url().'uploads/stage/documents/'.$note_detail->filename.'</p>';
        if(preg_match('/^.*\.(jpg|jpeg|gif|png|bmp)$/i', strtolower($document_detail->filename))){
            echo "<a href='".base_url()."uploads/development/documents/".$document_detail->filename."'>Download</a><img src = '".base_url()."uploads/development/documents/".$document_detail->filename."' width='100%' height='100%' />";
        }else if(preg_match('/^.*\.(docx|xlsx)$/i', strtolower($document_detail->filename))){
            echo '<a href="'.base_url().'uploads/development/documents/'.$document_detail->filename.'">Download</a>';
        }else{
            echo '<object data="' . base_url() . 'uploads/development/documents/' . $document_detail->filename . '" type="application/pdf" width="100%" height="100%"><p>It appears you dont have a PDF plugin for this browser<br>You can <a href="' . base_url() . 'uploads/development/documents/' . $document_detail->filename . '">click here to download the PDF file.</a></p> </object>';
        }

        
    }

    public function development_document_detail_download($document_id) {
        $document_detail = $this->developments_model->get_document_detail($document_id)->row();
        echo $document_detail->filename;
    }

    public function save_development_document($did) {

        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $post = $this->input->post();
        $url = $post['url'];
        if (isset($post['file_category'])) {
            $file_category = '/' . $post['file_category'];
        }
        $config['upload_path'] = UPLOAD_FILE_PATH_DEVELOPMENT_DOCUMENT;

        $config['allowed_types'] = '*';
        $config['max_size'] = '100000KB';
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

		$pu = $post['permission_user'];
		$per_users = implode(",",$pu );

        if ($this->upload->do_upload('upload_document')) {
            $upload_data = $this->upload->data();
            // insert data to file table
            // get latest id from frim table and insert it to loan table
            $document = array(
                'development_id' => $did,
                'filename' => $upload_data['file_name'],
                'filetype' => $upload_data['file_type'],
                'filesize' => $upload_data['file_size'],
                'filepath' => $upload_data['full_path'],
                'filename_custom' => $post['file_title'],
                'created' => time(),
                'uid' => $user_id,
                'notify_user' => implode(',',$post['notify_user']), //task #4045
				//'permitted_users' => $per_users,
				'document_group_permission' => $post['document_group_permission'],
                'construction_phase' => $_GET['cp'],
				'target_page'	=> $post['file_category']
            );

            if(array_pop(explode('/',$this->agent->referrer())) == 'health_and_safety'){

                $document['target_page'] = 'health_and_safety';
            }

            $this->developments_model->development_document_insert($document);
            $job = $this->db->get_where('construction_development', array('id'=>$did),0,1)->row();
            /*sending notification mail*/
            if(!empty($post['notify_user'])){
                $subject = '#'.$job->job_number.' - '.$job->development_name.' Construction Document';
                $message = '
                        <html>
                            <body>
                                <p>
                                    Hi #to#,<br>
                                    '.$this->session->userdata('user')->username.' uploaded '.$post['file_title'].'.<br>
                                    <a href="'.site_url('constructions/construction_documents/'.$did.'/documents?cp='.$_GET['cp']).'">Click here</a> to see the document:<br><br>
                                    Thank You
                                </p>
                            </body>
                        </html>';
                $sql = "select username, email from users where uid in (".implode(',',$post['notify_user']).") and company_id = ".$this->wp_company_id;
                $users = $this->db->query($sql)->result();
                foreach($users as $user){
                    // To send HTML mail, the Content-type header must be set
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= 'To: '.$user->email . "\r\n";
                    $headers .= 'From: construction@wclp.co.nz'."\r\n";
                    $msg = str_replace("#to#",$user->username,$message);
                    mail($user->email, $subject, $msg, $headers);
                }

            }

            /*log*/
            $cp = str_replace('_','-',$_GET['cp']);
            $this->wbs_helper->log('Document add',"Added document <b>{$post['file_title']}</b> for {$cp}: <b>{$job->development_name}</b>");

        } else {
            print 'Error in file uploading...';
            print $this->upload->display_errors();
        }
        redirect('constructions/' . $url . '/' . $did . '' . $file_category.'?cp='.$_GET['cp']);
    }


	public function development_document_update($construction_id,$doc_id) {

        $user = $this->session->userdata('user');
        $user_id = $user->uid;
        $post = $this->input->post();
        $url = $post['url'];

        /*log*/
        $cp = str_replace('_','-',$_GET['cp']);
        $job = $this->db->get_where('construction_development',array('id'=>$construction_id),1,0)->row();
        $this->wbs_helper->log('Document update',"Updated document <b>{$post['file_title']}</b> for {$cp}: <b>{$job->development_name}</b>");

        $file_category = '/' . 'documents';

        $config['upload_path'] = UPLOAD_FILE_PATH_DEVELOPMENT_DOCUMENT;

        $config['allowed_types'] = '*';
        $config['max_size'] = '100000KB';
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

		$pu = $post['permission_users'];

		$per_users = implode(",",$pu );
		

		if(!$_FILES['upload_document']['name']){

			$document = array(
	                'filename_custom' => $post['file_title'],
	                'notify_user' => implode(',',$post['notify_user']),
					//'permitted_users' => $per_users
					'document_group_permission' => $post['document_group_permission']
	            );
			$this->developments_model->development_document_update($document,$doc_id);
		}else{
		
	        if($this->upload->do_upload('upload_document')) {
	
	            $upload_data = $this->upload->data();
	            $document = array(
	                'filename' => $upload_data['file_name'],
	                'filetype' => $upload_data['file_type'],
	                'filesize' => $upload_data['file_size'],
	                'filepath' => $upload_data['full_path'],
	                'filename_custom' => $post['file_title'],
	                'notify_user' => implode(',',$post['notify_user']),
					//'permitted_users' => $per_users
					'document_group_permission' => $post['document_group_permission']
	            );
				$this->developments_model->development_document_update($document,$doc_id);
	
	            /*sending notification mail*/
	            if(!empty($post['notify_user'])){
	                $sql = "select username, email from users where uid in (".implode(',',$post['notify_user']).") and company_id = ".$this->wp_company_id;
	                $users = $this->db->query($sql)->result();
	                $to = array();
	                $to_name = array();
	                foreach($users as $user){
	                    $to[] = $user->email;
	                    $to_name[] = "{$user->username} <{$user->email}>";
	                }
	                $to = implode(", ",$to);
	                $to_name = implode(", ",$to_name);
	                $subject = 'Construction Document Update Notification';
	                $document_info = $this->db->get_where('construction_development_documents',array('id'=>$doc_id, ),0,1)->row();
	                $message = '
	                        <html>
	                            <body>
	                                <p>
	                                    Hi,<br>
	                                    A document '.$post['file_title'].' was updated.<br>
	                                    <a href="'.site_url('constructions/construction_documents/'.$construction_id.'/documents?cp='.$document_info->construction_phase).'">Click here</a> to see the document:<br>
	                                    Thank You
	                                </p>
	                            </body>
	                        </html>';
	                // To send HTML mail, the Content-type header must be set
	                $headers  = 'MIME-Version: 1.0' . "\r\n";
	                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	                $headers .= 'To: '.$to_name . "\r\n";
	
	                mail($to, $subject, $message, $headers);
	            }
	        } else {
	            print 'Error in file uploading...';
	            print $this->upload->display_errors();
	        }
		}
        redirect('constructions/construction_documents/' . $construction_id . '' . $file_category.'?cp='.$_GET['cp']);
    }

    public function add_new_milestone() {

        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $post = $this->input->post();

        $development_id = $post['development_id'];
		
		$this->wbs_helper->is_own_job($development_id);

        $phase_ids = $post['phase_ids'];

        $str_pid = '';

        if (isset($_POST['phase_ids'])) {
            for ($i = 0; $i < count($phase_ids); $i++) {
                if ($i < (count($phase_ids) - 1)) {
                    $str_pid = $str_pid . $phase_ids[$i] . ',';
                } else {
                    $str_pid = $str_pid . $phase_ids[$i];
                }
            }
        } else {
            $str_pid = '';
        }

        if ($this->input->post('submit')) {


            $add_new_milestone = array(
                'development_id' => $development_id,
                'milestone_title' => $post['milestone_title'],
                'milestone_date' => $this->wbs_helper->to_mysql_date($post['milestone_date']),
                'milestone_select_color' => $post['milestone_select_color'],
                'milestone_phases' => $str_pid,
                'created' => date("Y-m-d"),
                'created_by' => $user_id
            );

            $this->developments_model->add_new_milestone($add_new_milestone);
        }
        redirect('constructions/development_detail/' . $development_id);
    }

    public function update_milestone($id) {

        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $post = $this->input->post();

        $development_id = $post['development_id'];
		
		$this->wbs_helper->is_own_job($development_id);

        $phase_ids = $post['phase_ids'];

        $str_pid = '';

        if (isset($_POST['phase_ids'])) {
            for ($i = 0; $i < count($phase_ids); $i++) {
                if ($i < (count($phase_ids) - 1)) {
                    $str_pid = $str_pid . $phase_ids[$i] . ',';
                } else {
                    $str_pid = $str_pid . $phase_ids[$i];
                }
            }
        } else {
            $str_pid = '';
        }

        if ($this->input->post('submit')) {


            $update_milestone = array(
                'development_id' => $development_id,
                'milestone_title' => $post['milestone_title'],
                'milestone_date' => $this->wbs_helper->to_mysql_date($post['milestone_date']),
                'milestone_select_color' => $post['milestone_select_color'],
                'milestone_phases' => $str_pid,
                'updated_by' => $user_id
            );

            $this->developments_model->update_milestone($id, $update_milestone);
        }
        redirect('constructions/development_detail/' . $development_id);
    }

    public function update_all_phase_task_status($development_id, $phase_id, $status) {
		
		$this->wbs_helper->is_own_job($development_id);
		
        if($this->user_app_role == 'contractor') return;
        $this->developments_model->update_all_phase_tasks($development_id, $phase_id, $status);

        /*log*/
        $job_name = $this->db->get_where('construction_development',array('id'=>$development_id),1,0)->row()->development_name;
        $phase_info = $this->db->get_where('construction_development_phase',array('id'=>$phase_id),1,0)->row();
        $cp = str_replace('_',' ',$phase_info->construction_phase);
        $status = ($status) ? "complete":"incomplete";
        $this->wbs_helper->log('Update phase status',"Marked phase <b>{$phase_info->phase_name}</b>  in <b>{$cp}</b> - <b>{$job_name}</b> as <b>{$status}</b>");

    }

    public function update_all_stage_phase_status($development_id, $stage_no, $status) {
        if($this->user_app_role == 'contractor') return;
        $this->developments_model->update_all_satge_phase($development_id, $stage_no, $status);
    }

    public function email_outlook_development($photo_id) {
        $this->developments_model->email_outlook_development($photo_id);
    }

    public function development_photo_delete() {
        if($this->user_app_role == 'contractor') return;
        $post = $this->input->post();
        $development_id = $post['dev_id'];
		
		$this->wbs_helper->is_own_job($development_id);
        
		$development_photo_id = $post['dev_photo_id'];

        /*log*/
        $photo = $this->db->get_where('construction_development_photos',array('id'=>$development_photo_id),1,0)->row();
        $development = $this->db->get_where('construction_development',array('id' => $development_id),1,0)->row();
        $cp = str_replace('_','-',$_GET['cp']);
        $this->wbs_helper->log('Photo delete',"Deleted photo <b>{$photo->filename}</b> in {$cp}: <b>{$development->development_name}</b>");

        $this->developments_model->development_photo_delete($development_photo_id);


        redirect('constructions/construction_photos/' . $development_id."?cp=".$_GET['cp']);
    }

    public function development_document_delete() {
        if($this->user_app_role == 'contractor') return;
        $post = $this->input->post();
        $development_id = $post['dev_id'];
		
		$this->wbs_helper->is_own_job($development_id);
        
		$dev_document_id = $post['dev_document_id'];
        /*getting the job of this document. to validate the company*/
        $this->db->select("doc.id, doc.filename_custom");
        $this->db->join('construction_development job','job.id = doc.development_id');
        $doc = $this->db->get_where('construction_development_documents doc',array('doc.id'=>$dev_document_id, 'job.wp_company_id'=>$this->wp_company_id, 'job.id'=>$development_id),0,1)->row();

        /*log*/
        $cp = str_replace('_','-',$_GET['cp']);
        $job = $this->db->get_where('construction_development',array('id'=>$development_id),1,0)->row();
        $this->wbs_helper->log('Document delete',"Deleted document <b>{$doc->filename_custom}</b> for {$cp}: <b>{$job->development_name}</b>");

		$target_page	= $post['file_category'];
        $this->developments_model->development_document_delete($doc->id);
        redirect('constructions/construction_documents/' . $development_id.'/'.$target_page.'?cp='.$_GET['cp']);
    }

    public function development_update($development_id) {
		
		$this->wbs_helper->is_own_job($development_id);

        if($this->user_app_role == 'contractor') return;

        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        if ($this->input->post('submit')) {

            $post = $this->input->post();

            $config['upload_path'] = UPLOAD_DEVELOPMENT_IMAGE_PATH;
            $config['allowed_types'] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('feature_photo')) {
                $upload_data = $this->upload->data();

                $file = array(
                    'filename' => $upload_data['file_name'],
                    'filetype' => $upload_data['file_type'],
                    'filesize' => $upload_data['file_size'],
                    'filepath' => $upload_data['full_path'],
                    'created' => date("Y-m-d H:i:s"),
                    'uid' => $user_id
                );
                $photo_insert_id = $this->developments_model->development_feature_photo_insert($file);
            } else {
                $photo_insert_id = $post['fid'];
            }

            $development_update = array(
                'development_name' => $post['development_name'],
                'development_location' => $post['development_location'],
                'development_city' => $post['development_city'],
                'development_size' => $post['development_size'],
                'number_of_stages' => $post['number_of_stages'],
                'number_of_lots' => $post['number_of_lots'],
                'land_zone' => $post['land_zone'],
                'ground_condition' => $post['ground_condition'],
                'project_manager' => $post['project_manager'],
                'civil_engineer' => $post['civil_engineer'],
                'civil_manager' => $post['civil_manager'],
                'geo_tech_engineer' => $post['geo_tech_engineer'],
                'status' => $post['status'],
                'fid' => $photo_insert_id,
                'updated_by' => $user_id
            );

            $id = $this->developments_model->development_update($development_id, $development_update);
        }

        redirect('constructions/development_detail/' . $development_id, 'refresh');
    }

    public function notes($dev_id = '') {
		
		$this->wbs_helper->is_own_job($dev_id);

        $development = $this->developments_model->get_development_detail($dev_id)->row();
        $data['development_details'] = $development;
        $data['title'] = $development->development_name;

        $data['development_id'] = $dev_id;
        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($dev_id);
        $data['request_info'] = $this->developments_model->getDevelopmentsInfo($dev_id);

        /*task #4581*/
        $job_id_arr = array($development->id);
        $this->db->select('id');
        $child_jobs = $this->db->get_where('construction_development',array('parent_unit' => $development->id))->result();
        foreach($child_jobs as $child){
            $job_id_arr[] = $child->id;
        }
        $prev_notes = $this->developments_model->getPriviousDevelopmentsNotes($job_id_arr, $_GET['cp']);
        /***********/

        //$prev_notes = $this->developments_model->getPriviousDevelopmentsNotes($dev_id, $_GET['cp']);
        $data['prev_notes'] = $this->notes_image_tmpl($prev_notes, $development->id);
        //print_r($prev_notes); exit;
        //$data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true); 
        $data['development_content'] = $this->load->view('developments/development_notes_view', $data, true);

        $this->load->view('includes/header', $data);
        //$this->load->view('developments/development_sidebar',$data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function show_notes_with_image($rid) {

        /*task #4581*/
        $job_id_arr = array($rid);
        $this->db->select('id');
        $child_jobs = $this->db->get_where('construction_development',array('parent_unit' => $rid))->result();
        foreach($child_jobs as $child){
            $job_id_arr[] = $child->id;
        }
        $prev_notes = $this->developments_model->getPriviousDevelopmentsNotes($job_id_arr, $_GET['cp']);

        //$prev_notes = $this->developments_model->getPriviousDevelopmentsNotes($rid, $_GET['cp']);
        echo $this->notes_image_tmpl($prev_notes, $rid);

        //echo '<p>Me : '.urldecode($note).'<br /> '.  date('d/m/Y g:i a').'<p>';
    }

    public function notes_image_tmpl($prev_notes, $jid) {

        $user = $this->session->userdata('user');
        $user_id = $user->uid;
		
		$this->wbs_helper->is_own_job($jid);

        $align_class = '';
        $tmpl = '';
        foreach ($prev_notes as $notes) {

            /*task #4581*/
            $notes_from = "";
            if($notes->project_id != $jid){
                $notes_from = "notes from ".$notes->development_name;
            }

            $note_id = $notes->nid;
            $delete_link = '<span class="del" onClick="notesDelete(' . $note_id . ')"> X </span>';
            if($this->user_app_role == 'contractor'){
                $delete_link = '';
            }

            if ($notes->notes_by == $user_id) {
                $showuser = 'Me';
                $notified_user = $this->developments_model->getNotifiedUserName($notes->notify_user_id);
                $creation_time = date('g:i a d/m/Y', strtotime($notes->created));

                /*task #4581*/
                if($notes_from){
                    $showuser .= "<br>".$notes_from;
                }

                $align_class = 'right';
                if (!$notes->notes_image_id == null) {
                    $show_file = $this->notes_model->getNotesImage($notes->notes_image_id);
                    $file_name = $show_file->filename;
                    $allowedExts = array("gif", "jpeg", "jpg", "png");
                    $temp = explode(".", $file_name);
                    $extension = end($temp);
                    if (in_array($extension, $allowedExts)) {
                        //this is image
                        $tmpl .= '<div class="' . $align_class . '"><span class="time-left">' . $creation_time . '</span><br/><div class="userme"> :' . $showuser . '</div> <div style="float: right;" class="notes_body"><img height="100" width="100" src="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '"/></div> </div>';
                    } else {
                        //this is file not image
                        $tmpl .= '<div class="' . $align_class . '"><span class="time-left">' . $creation_time . '</span><br/><div class="userme"> :' . $showuser . '</div> <div style="float: right;" class="notes_body"><a target="_blank" href="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '">' . $file_name . '</a></div> </div>';
                    }
                } else {
                    $tmpl .= '<div class="' . $align_class . '"><span class="time-left">' . $creation_time . '</span><br/><div class="userme"> :' . $showuser . '</div> <div style="float: right;" class="notes_body">' . $notes->notes_body . '</div><div style="margin-left: 40px;float: left;">' . $notified_user . '</div>'.$delete_link.'  </div>';
                }


                $tmpl .= '<div class="clear"></div>';
            } else {
                $showuser = $notes->username;
                $creation_time = date('g:i a d/m/Y', strtotime($notes->created));
                $align_class = 'left';
                $notified_user = $this->developments_model->getNotifiedUserName($notes->notify_user_id);

                /*task #4581*/
                if($notes_from){
                    $showuser .= "<br>".$notes_from;
                }

                if (!$notes->notes_image_id == null) {
                    $show_file = $this->notes_model->getNotesImage($notes->notes_image_id);
                    $file_name = $show_file->filename;
                    $allowedExts = array("gif", "jpeg", "jpg", "png");
                    $temp = explode(".", $file_name);
                    $extension = end($temp);
                    if (in_array($extension, $allowedExts)) {
                        //this is image
                        $tmpl .= '<div class="' . $align_class . '"><span class="time-right">' . $creation_time . '</span><br/><div class="useranother">' . $showuser . ':</div> <div style="float: left;" class="notes_body"><img height="100" width="100" src="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '"/></div> </div>';
                    } else {
                        //this is not image
                        $tmpl .= '<div class="' . $align_class . '"><span class="time-right">' . $creation_time . '</span><br/><div class="useranother">' . $showuser . ':</div> <div style="float: left;" class="notes_body"><a target="_blank" href="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '">' . $file_name . '</a></div> </div>';
                    }
                } else {
                    $tmpl .= '<div class="' . $align_class . '"><span class="time-right">' . $creation_time . '</span><br/><div class="useranother">' . $showuser . ':</div>  <div style="float: left;" class="notes_body">' . $notes->notes_body . '</div><div style="margin-right: 30px;float: right;">' . $notified_user . '</div>'.$delete_link.'  </div>';
                }

                $tmpl .= '<div class="clear"></div>';
            }
        }
        return $tmpl;
    }

    public function show_notes($rid, $company_id = null, $notify_user_id = null) {
        
        $user = $this->session->userdata('user');
        //print_r($user);
        $note = $_GET['notes'];

        $user_id = $user->uid;
        $user_email = $user->email;
        $user_name = $user->username;
        $user_role = $user->rid;
        $note0 = urldecode($note);
        $now = date('Y-m-d H:i:s');

		// find the logo
		$wp_company_id = $user->company_id;
		$this->db->select("wp_company.*,wp_file.*");
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
	 	$this->db->where('wp_company.id', $wp_company_id);	
		$wpdata = $this->db->get('wp_company')->row();
		$logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

		// insert note
		//$insert_note = $this->notes_model->insertNote($rid, $note_body, $user_id, $notify_user_id, $now);
        //$prev_notes = $this->notes_model->getPriviousNotes($rid);
        //echo $this->notes_image_tmpl($prev_notes);


        $note1 = str_replace("forward_slash", "/", $note0);
        $note2 = str_replace("sign_of_hash", "#", $note1);
        $note3 = str_replace("sign_of_intertogation", "?", $note2);
        $note4 = str_replace("sign_of_plus", "+", $note3);
        $note5 = str_replace("sign_of_exclamation", "!", $note4);
        $note6 = str_replace("percentage", "%", $note5);
        $note7 = str_replace("back_slash", "\\", $note6);

        $note_body = $note7;

        $request_info = $this->developments_model->getDevelopmentsInfo($rid);

        $request_title = $request_info->development_name;
        $job_number = $request_info->job_number;

        $request_created_by = $request_info->created_by;

        $from = $user_email;
        $notes_from = $user_name;
        $subject = 'New Note on Construction Management System: Job#' . $job_number . ' - ' . $request_title;

        /*$notify_user_info = $this->developments_model->get_user_info($notify_user_id);
        foreach ($notify_user_info as $user_info) {
            $user_name1[] = $user_info->username;
            $notify_user_email[] = $user_info->email;
        }
        $assign_user_name = implode(", ", $user_name1);
        $notify_user_to = implode(", ", $notify_user_email);*/

        $insert_note = $this->developments_model->insertNote($rid, $note_body, $user_id, $notify_user_id, $now, $_GET['cp']);

        $notify_user_email = array();
        $notify_user_name = array();

        if($notify_user_id != 'null'){
            $sql = "select * from contact_contact_list where wp_company_id = $this->wp_company_id and id in ({$notify_user_id})";
            $res = $this->db->query($sql)->result();
            foreach($res as $row){
                $notify_user_email[] = $row->contact_email;
                $notify_user_name[] = $row->contact_first_name." ".$row->contact_last_name;
            }

        }elseif($notify_user_id == 'null' && $company_id != 'null'){
            $sql = "select * from contact_contact_list where wp_company_id = $this->wp_company_id and company_id = {$company_id}";
            $res = $this->db->query($sql)->result();
            foreach($res as $row){
                $notify_user_email[] = $row->contact_email;
                $notify_user_ids[] = $row->id;
                $notify_user_name[] = $row->contact_first_name." ".$row->contact_last_name;
            }
            $notify_user_id = implode(',',$notify_user_ids);
        }

        $notify_user_to = implode(",", $notify_user_email);

        $from2 = $user_email;
        $notes_from2 = $user_name;
        $subject2 = 'New Note from Construction Management System: Job#' . $job_number . ' - ' . $request_title;

        $headers = "From: " . $from2 . "\r\n";
        //$headers .= "Reply-To: " . $notify_user_to . "\r\n";
        $headers .= "Reply-To: construction@e-wclp.co.nz\r\n";
        $headers .= "CC: ". $cc . "\r\n";
        $headers .= "BCC: " . "mail_helper@e-wclp.co.nz" . "\r\n";

		//$headers = "From: " . "construction@e-wclp.co.nz" . "\r\n";
		//$headers .= "Reply-To: " . "construction@e-wclp.co.nz" . "\r\n";
		//$cc = implode(",",$notify_user_email);
		//if(!empty($cc)){
            //$headers .= "CC: ". $cc . "\r\n";
        //}
		//$headers .= "BCC: " . "mail_helper@e-wclp.co.nz" . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$headers .= "X-noteId:" . $insert_note . "\r\n";
        $headers .= "X-app:" . 'construction' . "\r\n";
        $headers .= "X-companyId:" . $user->company_id . "\r\n";

        $message2 = '';
        $message2 .= '<html><body>';
        $message2 .= '<table border="0" rules="all" style="border-color: #666;" cellpadding="10">';
        $message2 .= "<tr><td><strong>Job Number:</strong></td><td> " . $job_number . "</td></tr>";
        $message2 .= "<tr><td><strong>Job Title: </strong></td><td> " . $request_title . "</td></tr>";
        $message2 .= "<tr><td><strong>Task Notes: </strong></td><td> " . $note_body . "</td></tr>";
        //$message2 .= "<tr><td><strong>Notified: </strong></td><td>" . $assign_user_name . "</td></tr>";
        $message2 .= "<tr><td><strong>Notified: </strong></td><td>" . implode(", ",$notify_user_name) . "</td></tr>";
        $message2 .= "<tr><td><strong>Notes Form: </strong></td><td>" . $notes_from2 . "</td></tr>";
        $message2 .= "<tr><td><strong>URL: </strong></td><td><a href='".base_url() . "constructions/notes/" . $rid."?cp=".$_GET['cp']."'>" . base_url() . "constructions/notes/" . $rid. "?cp=".$_GET['cp']."</a></td></tr>";
        $message2 .= "</table>";
        $message2 .= "</body></html>";
        //$msg_body='message body';
        $msg_body2 = $message2;
        mail($notify_user_to, $subject2, $msg_body2, $headers);

        /*task #4581*/
        $job_id_arr = array($rid);
        $this->db->select('id');
        $child_jobs = $this->db->get_where('construction_development',array('parent_unit' => $rid))->result();
        foreach($child_jobs as $child){
            $job_id_arr[] = $child->id;
        }
        $prev_notes = $this->developments_model->getPriviousDevelopmentsNotes($job_id_arr, $_GET['cp']);
        //$prev_notes = $this->developments_model->getPriviousDevelopmentsNotes($rid, $_GET['cp']);
        echo $this->notes_image_tmpl($prev_notes, $rid);

        /*log*/
        $cp = str_replace('_','-',$_GET['cp']);
        $this->wbs_helper->log('Note',"Added note for {$cp}: <b>{$request_info->development_name}</b>");
    }

    public function notes_delete($pid, $noteid) {
		
		$this->wbs_helper->is_own_job($pid);

        if($this->user_app_role == 'contractor') return;

        $this->developments_model->deleteDevelopmentsNotes($noteid);
        /*task #4581*/
        $job_id_arr = array($pid);
        $this->db->select('id');
        $child_jobs = $this->db->get_where('construction_development',array('parent_unit' => $pid))->result();
        foreach($child_jobs as $child){
            $job_id_arr[] = $child->id;
        }
        $prev_notes = $this->developments_model->getPriviousDevelopmentsNotes($job_id_arr);
        //$prev_notes = $this->developments_model->getPriviousDevelopmentsNotes($pid);
        echo $this->notes_image_tmpl($prev_notes, $pid);

        /*log*/
        $development_name = $this->db->get_where('construction_development',array('id'=>$pid),0,1)->row()->development_name;
        $this->wbs_helper->log('Note delete',"Deleted note for <b>{$development_name}</b>");

    }

    public function update($id, $field, $value) {
        if($this->user_app_role == 'contractor') return;
        $value = urldecode($value);
        $res = $this->db->simple_query("update construction_development set {$field}='{$value}' where id = {$id}");
        if ($res)
            echo 1;
        else
            echo 0;

        /*log*/
        $job = $this->db->get_where('construction_development',array('id'=>$id),1,0)->row()->development_name;
        $this->wbs_helper->log('Job Edit','For job: <b>'.$job.'</b> updated <b>'.$field.'</b> value to <b>'.$value.'</b>');
        exit;

    }

    public function add_remove_job_to_unit($uid, $jid, $add) {
		
		$this->wbs_helper->is_own_job($jid);

        if($this->user_app_role == 'contractor') return;

        if ($add == 'true') {
            $query = "update construction_development set parent_unit = {$uid} where id = {$jid}";
        } else {
            $query = "update construction_development set parent_unit = NULL where id = {$jid}";
        }
        $this->db->simple_query($query);
    }
    
    private function _set_phase_dates($pid,$start_date,$development_id, $construction_phase, $planned_finish_date = "", $update_tasks = true){
        if(in_array($pid, $this->processed_phases)){
            return;
        }
		
		$this->wbs_helper->is_own_job($development_id);
		
        $this->processed_phases[] = $pid;
        $planned_start_date = $this->wbs_helper->to_mysql_date($start_date);
        /* updating the start date and end date */
        $sql = "SELECT construction_template_phase.*,construction_development_phase.phase_name,construction_development_phase.phase_person_responsible   
                FROM construction_template_phase, construction_development_phase
                WHERE construction_template_phase.template_id = construction_development_phase.template_id
                    AND construction_template_phase.phase_no = construction_development_phase.phase_no
                    AND construction_development_phase.id = {$pid}";

        $phase_info = $this->db->query($sql)->row();
        if(empty($phase_info)){
            /*this phase is not under any template. was created directly under job*/
            $sql = "SELECT construction_development_phase.*
                FROM construction_development_phase
                WHERE construction_development_phase.id = {$pid}";

            $phase_info = $this->db->query($sql)->row();
        }
        if($phase_info->phase_length == 0){
                $phase_info->phase_length = 5;
            }
        
        $start = new DateTime($planned_start_date);
        $end = '';
        if ($planned_finish_date == "") {
            
            for ($i = 0; $i < $phase_info->phase_length; $i++) {
                if ($i == 0) {
                    $end = clone $start;
                } else {
                    $end->add(new DateInterval("P1D"));
                    $week_day = $end->format('w');
                    if ($week_day == 6) {
                        $end->add(new DateInterval("P2D"));
                    } elseif ($week_day == 0) {
                        $end->add(new DateInterval("P1D"));
                    }
                }
            }
        } else {
            $planned_finish_date = $this->wbs_helper->to_mysql_date($planned_finish_date);
            $end = new DateTime($planned_finish_date);
        }
        
        
        $update_sql = "UPDATE construction_development_phase
                       SET planned_start_date = '".$start->format("Y-m-d")."',
                           planned_finished_date = '".$end->format("Y-m-d")."' 
                       WHERE id = {$pid}";
        $this->db->simple_query($update_sql);

		$this->email_arr[] = $phase_info->phase_person_responsible."# Phase Name - ".$phase_info->phase_name."<br> Start Date - ".$start->format("Y-m-d"). " End Date - ".$end->format("Y-m-d")."<hr>";

        /*if update_task_dates parameter is -1 we will only update this phase's dates. not the tasks under it or other dependent phases*/
        if($update_tasks === -1){
            return;
        }
        
        /* now setting the start and end date of all tasks under this phase */
        if ($update_tasks) {
            $tasks_sql = "SELECT construction_development_task.id tid, task_start_date, actual_completion_date, construction_template_task.*
                      FROM construction_template_task  JOIN construction_development_task ON construction_template_task.id = construction_development_task.construction_template_task_id
                      WHERE construction_development_task.phase_id = {$pid}";

            $tasks = $this->db->query($tasks_sql)->result();

            foreach ($tasks as $task) {
                if(!$task->start_day){
                    $task->start_day = 1;
                }
                $phase_start_date = clone $start;
                $task_start_day = clone $phase_start_date;
                for ($i = 0; $i < $task->start_day; $i++) {
                    if ($i != 0) {
                        $task_start_day->add(new DateInterval("P1D"));
                        $week_day = $task_start_day->format('w');
                        if ($week_day == 6) {
                            $task_start_day->add(new DateInterval("P2D"));
                        } elseif ($week_day == 0) {
                            $task_start_day->add(new DateInterval("P1D"));
                        }
                    }
                }
                if($task->task_length == 0){
                    $task_finish_day = clone $end;
                }
                for ($i = 0; $i < $task->task_length; $i++) {
                    if ($i == 0) {
                        $task_finish_day = clone $task_start_day;
                    } else {
                        $task_finish_day->add(new DateInterval("P1D"));
                        $week_day = $task_finish_day->format('w');
                        if ($week_day == 6) {
                            $task_finish_day->add(new DateInterval("P2D"));
                        } elseif ($week_day == 0) {
                            $task_finish_day->add(new DateInterval("P1D"));
                        }
                    }
                }

                $update_sql = "UPDATE construction_development_task
                       SET task_start_date = '" . $task_start_day->format("Y-m-d") . "',
                           actual_completion_date = '" . $task_finish_day->format("Y-m-d") . "'
                       WHERE id = {$task->tid}";

                $this->db->simple_query($update_sql);

				$this->email_arr[] = $task->tpr."# Phase Name - ".$phase_info->phase_name."<br> Task Name - ".$task->task_name."  <br> Start Date - ".$task_start_day->format("Y-m-d"). " End Date - ".$task_finish_day->format("Y-m-d")."<hr>";

                /*task 4200*/
                /*if any phase is dependent on this task we will change that phase's dates*/
                if($task->id){
                    $start_date_phase = $task_finish_day;
                    $start_date_phase->add(new DateInterval('P1D'));
                    $start_date_phase = $start_date_phase->format('Y-m-d');
                    $this->db->select('construction_development_phase.*');
                    $this->db->join('construction_template_phase', 'construction_template_phase.phase_no = construction_development_phase.phase_no AND construction_template_phase.template_id = construction_development_phase.template_id');
                    $this->db->where('construction_template_phase.task_dependency',$task->id);
                    $dependent_phases = $this->db->get('construction_development_phase')->result();

                    foreach($dependent_phases as $p){
                        /*log*/
                        $this->wbs_helper->log('Update phase',"Updated phase <b>{$p->phase_name}</b>(as dependent on task: {$task->tid}) - <b>job:{$p->development_id}</b>");

                        $this->_set_phase_dates($p->id, $start_date_phase, $development_id, $p->construction_phase);
                    }
                }
            }
        }
        
        
        /*now setting date for all dependent phases */
        
        $sql = "SELECT construction_development_phase.*
                FROM construction_template_phase, construction_development_phase
                WHERE  construction_template_phase.template_id = construction_development_phase.template_id
                    AND construction_template_phase.phase_no = construction_development_phase.phase_no
                    AND construction_development_phase.development_id = {$development_id}
                    AND construction_template_phase.dependency = {$phase_info->id}
                    AND dont_use_dependency != 1";
        $sql .= " AND construction_development_phase.construction_phase = '{$construction_phase}'";
        $dependents = $this->db->query($sql)->result();
        foreach($dependents as $d){
            $next_phase_start_date = clone $end;
            $next_phase_start_date->add(new DateInterval("P1D"));
            $week_day = $next_phase_start_date->format('w');
                    if ($week_day == 6) {
                        $next_phase_start_date->add(new DateInterval("P2D"));
                    } elseif ($week_day == 0) {
                        $next_phase_start_date->add(new DateInterval("P1D"));
                    }
            /*log*/
            $p_name = $this->db->get_where('construction_development_phase',array('id'=>$pid),1,0)->row()->phase_name;
            $this->wbs_helper->log('Update phase',"Updated phase <b>{$d->phase_name}</b> (as dependent on phase: {$p_name}) in <b>{$d->construction_phase}</b> - <b>job:{$d->development_id}</b>");

            $this->_set_phase_dates($d->id, $next_phase_start_date->format('d-m-Y'), $development_id, $construction_phase, "", $update_tasks);
        }
        
    }

    /*getting the dependent phases new dates to show in warning popup for phase update*/
    private function _get_phase_dates_html($pid,$start_date,$development_id, $construction_phase, $planned_finish_date = "", $html = ''){
        if(in_array($pid, $this->processed_phases)){
            return;
        }
		
		$this->wbs_helper->is_own_job($development_id);
		
        $this->processed_phases[] = $pid;
        $planned_start_date = $this->wbs_helper->to_mysql_date($start_date);

        $sql = "SELECT construction_template_phase.*, construction_development_phase.planned_start_date, construction_development_phase.planned_finished_date
                FROM construction_template_phase, construction_development_phase
                WHERE construction_template_phase.template_id = construction_development_phase.template_id
                    AND construction_template_phase.phase_no = construction_development_phase.phase_no
                    AND construction_development_phase.id = {$pid}";

        $phase_info = $this->db->query($sql)->row();

        /*if no phase info. the phase is directly under the job*/
        if(!($phase_info)){
            $sql = "SELECT construction_development_phase.planned_start_date, construction_development_phase.planned_finished_date
                FROM construction_development_phase
                WHERE construction_development_phase.id = {$pid}";

            $phase_info = $this->db->query($sql)->row();
            $phase_info->id = -1; //to avoid an error in the later query
        }

        $old_start_date = ($phase_info->planned_start_date == '0000-00-00') ? '' : date_create_from_format('Y-m-d',$phase_info->planned_start_date)->format('d-m-Y');
        $old_end_date = ($phase_info->planned_finished_date == '0000-00-00') ? '' : date_create_from_format('Y-m-d',$phase_info->planned_finished_date)->format('d-m-Y');
        $new_start_date = date_create_from_format('Y-m-d',$planned_start_date)->format('d-m-Y');
        $html .= "<tr>
                        <td>{$phase_info->phase_name}</td>
                        <td>{$old_start_date}</td>
                        <td>{$old_end_date}</td>
                        <td>{$new_start_date}</td>";

        if($phase_info->phase_length == 0){
            $phase_info->phase_length = 5;
        }

        $start = new DateTime($planned_start_date);
        $end = '';
        if ($planned_finish_date == "") {

            for ($i = 0; $i < $phase_info->phase_length; $i++) {
                if ($i == 0) {
                    $end = clone $start;
                } else {
                    $end->add(new DateInterval("P1D"));
                    $week_day = $end->format('w');
                    if ($week_day == 6) {
                        $end->add(new DateInterval("P2D"));
                    } elseif ($week_day == 0) {
                        $end->add(new DateInterval("P1D"));
                    }
                }
            }
        } else {
            $planned_finish_date = $this->wbs_helper->to_mysql_date($planned_finish_date);
            $end = new DateTime($planned_finish_date);
        }

        $html .= "<td>{$end->format('d-m-Y')}</td>
                </tr>";

        /*now setting date for all dependent phases */

        $sql = "SELECT construction_development_phase.id
                FROM construction_template_phase, construction_development_phase
                WHERE  construction_template_phase.template_id = construction_development_phase.template_id
                    AND construction_template_phase.phase_no = construction_development_phase.phase_no
                    AND construction_development_phase.development_id = {$development_id}
                    AND construction_template_phase.dependency = {$phase_info->id}
                    AND dont_use_dependency != 1";
        $sql .= " AND construction_development_phase.construction_phase='{$construction_phase}'";

        $dependents = $this->db->query($sql)->result();
        foreach($dependents as $d){
            $next_phase_start_date = clone $end;
            $next_phase_start_date->add(new DateInterval("P1D"));
            $week_day = $next_phase_start_date->format('w');
            if ($week_day == 6) {
                $next_phase_start_date->add(new DateInterval("P2D"));
            } elseif ($week_day == 0) {
                $next_phase_start_date->add(new DateInterval("P1D"));
            }
            $html .= $this->_get_phase_dates_html($d->id, $next_phase_start_date->format('d-m-Y'), $development_id, $construction_phase,'', '');
        }

        return $html;

    }

    public function remove_dependency(){
        if($this->user_app_role != 'manager' && $this->user_app_role != 'admin') return;
        $is_pre_construction = 0;
        $development_id = $this->input->post('development_id');
        $construction_phase = $this->input->post('construction_phase');
        $sql = "update construction_development_phase set dont_use_dependency = 1 where development_id = {$development_id} and construction_phase = '{$construction_phase}' ";
        $this->db->simple_query($sql);

        /*log*/
        $job_name = $this->db->get_where('construction_development',array('id'=>$development_id),1,0)->row()->development_name;
        $cp = str_replace('_',' ',$construction_phase);
        $this->wbs_helper->log('Remove dependency',"Removed dependency for all phases in <b>{$cp}</b> - <b>{$job_name}</b>");

        redirect($this->agent->referrer(), 'refresh');

    }

    public function clone_template($template_id){

        if($this->user_app_role != 'manager' && $this->user_app_role != 'admin') return;

        $user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;

        /*copying the tmeplate info*/
        $this->db->where('id', $template_id);
        $this->db->where('wp_company_id', $wp_company_id);
        $row =  $this->db->get('construction_template')->row();

        unset($row->id);
        unset($row->updated);

        $row->created_by = $user->uid;
        $row->created = date("Y-m-d H:i:s");
        $row->updated_by = $user->uid;

        $this->db->insert('construction_template',$row);

        $new_template_id  = $this->db->insert_id();

        /*coppying the phases*/
        $phase_ids = array();

        $this->db->where('template_id', $template_id);
        $query = $this->db->get('construction_template_phase');

        foreach ($query->result() as $row) {
            $old_phase_id = $row->id;
            unset($row->id);
            $row->template_id = $new_template_id;
            $row->created_by = $user->uid;
            $row->created = date("Y-m-d H:i:s");
            $row->updated_by = $user->uid;
            $this->db->insert('construction_template_phase',$row);
            $new_phase_id = $this->db->insert_id();

            /*copying tasks for this phase*/
            $this->db->where('template_id', $template_id);
            $this->db->where('phase_id', $old_phase_id);
            $query = $this->db->get('construction_template_task');

            foreach ($query->result() as $row2) {
                unset($row2->id);
                $row2->template_id = $new_template_id;
                $row2->phase_id = $new_phase_id;
                $row2->created_by = $user->uid;
                $row2->created = date("Y-m-d H:i:s");
                $row2->updated_by = $user->uid;
                $this->db->insert('construction_template_task',$row2);
            }
            $phase_ids[] = compact('old_phase_id', 'new_phase_id');
            foreach($phase_ids as $pid){
                $query = "update construction_template_phase set dependency = {$pid['new_phase_id']}
                          where template_id = {$new_template_id} and dependency = {$pid['old_phase_id']}";
                $this->db->simple_query($query);
            }
        }

        redirect('template/template_basic_info_update/'.$new_template_id);
    }

    public function phase_update_warning($did, $pid, $construction_phase){
        $html = "";
        $msg = "";
        $planned_finished_date = $this->input->post('planned_finished_date') ? $this->input->post('planned_finished_date') : '';
        $html = $this->_get_phase_dates_html($pid,$this->input->post('planned_start_date'),$did,$construction_phase,$planned_finished_date,$html);
        echo json_encode(array('html' => $html, 'msg' => $msg));

    }
    public function task_update_warning($did, $pid, $task_id, $construction_phase){
        $html = "";
        $msg = "";
        $planned_finished_date = $this->input->post('actual_completion_date') ? $this->input->post('actual_completion_date') : '';
        $start_date = $this->input->post('task_start_date') ? $this->input->post('task_start_date') : '';

        /*we will show a warning if the tasks finish date is greater than the phase's finish date*/
        $sql = "SELECT construction_development_phase.*
                FROM construction_development_phase
                WHERE construction_development_phase.id = {$pid} limit 0, 1";

        $phase_info = $this->db->query($sql)->row();

        /*if tasks start date is not within phase period we will show a message*/
        if($this->wbs_helper->to_mysql_date($start_date) < $phase_info->planned_start_date || $this->wbs_helper->to_mysql_date($start_date) > $phase_info->planned_finished_date){
            $msg = "You are adding a task outside <b>{$phase_info->phase_name}</b> Start Date and Finish Date. This phase will be updated and all it's dependencies will be removed.";
        }
        elseif( $this->wbs_helper->to_mysql_date($planned_finished_date) > $phase_info->planned_finished_date){
            $html = $this->_get_task_dates_html($pid, $task_id, $this->input->post('task_start_date'),$did,$construction_phase,$planned_finished_date,$html);
        }
		else{
			$html = $this->_get_task_dates_html($pid, $task_id, $this->input->post('task_start_date'),$did,$construction_phase,$planned_finished_date,$html);
		}
        echo json_encode(array('html' => $html, 'msg' => $msg));

    }


	// task update warning popup html
	// written by Syed Nurul Islam task #4344, #4371
	private function _get_task_dates_html($pid, $task_id, $start_date,$development_id, $construction_phase, $planned_finish_date = "", $html = ''){
        $planned_start_date = $this->wbs_helper->to_mysql_date($start_date);
		$planned_finish_date = $this->wbs_helper->to_mysql_date($planned_finish_date);

		// collection task information for popup show (first time)
		$task_sql = "SELECT * FROM construction_development_task WHERE id = $task_id";
		$task_info = $this->db->query($task_sql)->row();
		$html .= "<tr>
                        <td>Task Name: <br>{$task_info->task_name}</td>
                        <td>{$task_info->task_start_date}</td>
                        <td>{$task_info->actual_completion_date}</td>
                        <td>{$planned_start_date}</td>
					    <td>{$planned_finish_date}</td></tr>";
	

        $task_info = $this->db->get_where('construction_development_task',array('id'=>$task_id),1,0)->row();

        if(!$task_info->construction_template_task_id){
            return $html;
        }

        $this->db->select('construction_development_phase.*');
		$this->db->join('construction_template_phase', 'construction_template_phase.phase_no = construction_development_phase.phase_no AND construction_template_phase.template_id = construction_development_phase.template_id', 'left');
 		$this->db->where('construction_template_phase.task_dependency',$task_info->construction_template_task_id);
		$this->db->where('construction_development_phase.development_id',$development_id);
		$this->db->where('construction_development_phase.construction_phase',$construction_phase);

		$dependent_phases = $this->db->get('construction_development_phase')->result();

		foreach($dependent_phases as $dependent_phase){

			$start_date = date_create_from_format('Y-m-d',$planned_finish_date);
			$start_date->add(new DateInterval('P1D'));
			$week_day = $start_date->format('w');
			if($week_day == 6){
				$start_date->add(new DateInterval("P2D"));
			}elseif($week_day == 0){
 				$start_date->add(new DateInterval("P1D"));
			}

			$start_date = $start_date->format('Y-m-d');

			// end date
			$planned_start_date = $this->wbs_helper->to_mysql_date($start_date);
			$start = new DateTime($planned_start_date);
        	$end = '';
			if($dependent_phase->phase_length == 0){
                $dependent_phase->phase_length = 5;
            }


			for ($i = 0; $i < $dependent_phase->phase_length; $i++) {
                if ($i == 0) {
                    $end = clone $start;
                } else {
                    $end->add(new DateInterval("P1D"));
                    $week_day = $end->format('w');
                    if ($week_day == 6) {
                        $end->add(new DateInterval("P2D"));
                    } elseif ($week_day == 0) {
                        $end->add(new DateInterval("P1D"));
                    }
                }
            }


			$html .= "<tr>
                        <td>{$dependent_phase->phase_name}</td>
                        <td>{$dependent_phase->planned_start_date}</td>
                        <td>{$dependent_phase->planned_finished_date}</td>
                        <td>{$start_date}</td>
						<td>{$end->format("Y-m-d")}</td>
					</tr>";
		}
		
        return $html;

    }


    public function create_form(){

        if($this->user_app_role != 'manager' && $this->user_app_role != 'admin') return;

        $data['title'] = "Add Form";

        if ($this->input->post('name')) {
            $post = $this->input->post();
            $tasks = $post['task'];
            $data = array(
              'name' => $post['name'],
              'wp_company_id' => $this->wp_company_id,
              'created' => date("Y-m-d H:i:s")
            );
            $this->db->insert('construction_checklist_form',$data);
            $form_id = $this->db->insert_id();
            foreach($post['stage'] as $ind => $stage){
                if(!$stage) continue;
                $data = array(
                    'stage_name' => $stage, 'form_id' => $form_id, 'wp_company_id' => $this->wp_company_id
                );
                $this->db->insert('construction_check_stage', $data);
                $stage_id = $this->db->insert_id();
                foreach($tasks[$ind] as $task){
                    if(!$task) continue;
                    $data = array(
                        'stage_id' => $stage_id, 'task_name' => $task, 'form_id' => $form_id
                    );
                    $this->db->insert('construction_check_list',$data);
                }
            }

            /*log*/
            $this->wbs_helper->log('Form create',"Created form <b>{$post['name']}</b>");

            if($form_id){
                $this->session->set_flashdata('success-message', "Form <b>{$post['name']}</b> created successfully.");
                redirect(site_url('job/show_popup_menu'));
            }

        }else{
            $data['maincontent'] = $this->load->view('form/add_form', $data, true);
            $this->load->view('includes/popup_home', $data);
        }
    }
    public function form_list(){

        if($this->user_app_role != 'manager' && $this->user_app_role != 'admin') return;

        $this->db->where('wp_company_id',$this->wp_company_id);
        $data['forms'] = $this->db->get('construction_checklist_form')->result();
        $data['maincontent'] = $this->load->view('form/list', $data, true);
        $this->load->view('includes/popup_home', $data);
    }
    public function edit_form($form_id){

        if($this->user_app_role != 'manager' && $this->user_app_role != 'admin') return;

        if ($this->input->post('name')) {

            /*log*/
            $form = $this->db->get_where('construction_checklist_form',array('id'=>$form_id),1,0)->row();
            if($form->name != $this->input->post('name')){

                $this->wbs_helper->log('Form edit',"Renamed <b>{$form->name}</b> to <b>{$this->input->post('name')}</b>");
            }

            $this->db->where(array(
                'id' => $form_id, 'wp_company_id' => $this->wp_company_id
            ));
            if($this->db->update('construction_checklist_form',array('name' => $this->input->post('name')))){
                $this->session->set_flashdata('success-message', "Form <b>{$this->input->post('name')}</b> updated successfully.");
                redirect(site_url('constructions/form_list'));
            }
        }else{
            /*getting form data*/
            $this->db->select('form.id form_id, form.name form_name, stage.id stage_id, stage.stage_name, task.id task_id, task.task_name');
            $this->db->join('construction_check_stage stage','form.id = stage.form_id', 'left');
            $this->db->join('construction_check_list task','stage.id = task.stage_id', 'left');
            $this->db->where(array('form.id'=>$form_id, 'form.wp_company_id' => $this->wp_company_id));
            $rows = $this->db->get('construction_checklist_form form')->result();
            $form_info = array();
            $form_info['stages'] = array();
            foreach($rows as $row){
                if(!$form_info['stages'][$row->stage_id] ){
                    $form_info['stages'][$row->stage_id] = array('name' => $row->stage_name, 'tasks' => array());
                }
                $form_info['stages'][$row->stage_id]['tasks'][$row->task_id] = $row->task_name;
            }
            $form_info['name'] = $row->form_name;
            $form_info['id'] = $row->form_id;
            $data['form_info'] = $form_info;
            $data['maincontent'] = $this->load->view('form/edit_form', $data, true);
            $this->load->view('includes/popup_home', $data);
        }
    }
    public function delete_form($form_id){

        if($this->user_app_role != 'manager' && $this->user_app_role != 'admin') return;

        if($form_id){

            /*log*/
            $form = $this->db->query("select * from construction_checklist_form where id = {$form_id} AND wp_company_id = {$this->wp_company_id}")->row();
            $this->wbs_helper->log('Delete form',"delete form <b>{$form->name}</b>");

            /*deleting form*/
            if($this->db->delete('construction_checklist_form',array('id' => $form_id, 'wp_company_id' => $this->wp_company_id))){
                $this->session->set_flashdata('success-message', "Form deleted successfully.");
                redirect(site_url('constructions/form_list'));
            }
        }
    }
    /* add/edit/delete form stages and tasks*/
    public function update_form($form_id, $element, $op, $element_id){

        if($this->user_app_role != 'manager' && $this->user_app_role != 'admin') return;

        $form = $this->db->query("select * from construction_checklist_form where id = {$form_id} AND wp_company_id = {$this->wp_company_id}")->row();

        /*log*/
        $this->wbs_helper->log('Update form',"{$op} {$element} in form <b>{$form->name}</b>");

        if($form){
            if($element == 'stage'){
                switch($op){
                    case 'add':
                        $data = array(
                            'stage_name' => 'New Stage', 'form_id' => $form->id, 'wp_company_id' => $this->wp_company_id
                        );
                        $this->db->insert('construction_check_stage', $data);
                        $stage_id = $this->db->insert_id();
                        echo $stage_id; exit;
                    case 'update':
                        $this->db->where(array(
                            'id' => $element_id, 'wp_company_id' => $this->wp_company_id, 'form_id' => $form->id
                        ));
                        $data = array(
                            'stage_name' => $this->input->post('name')
                        );
                        $this->db->update('construction_check_stage', $data);
                        exit;
                    case 'delete':
                        $this->db->delete('construction_check_stage',array('id' => $element_id, 'form_id'=>$form->id, 'wp_company_id' => $this->wp_company_id));
                        $this->db->delete('construction_check_list',array('stage_id' => $element_id, 'form_id'=>$form->id));
                        exit;


                }
            }
            if($element == 'task'){
                switch($op){
                    case 'add':
                        $data = array(
                            'task_name' => 'New Task',
                            'form_id' => $form->id,
                            'stage_id' => $this->input->post('stage_id')
                        );
                        $this->db->insert('construction_check_list', $data);
                        $stage_id = $this->db->insert_id();
                        echo $stage_id; exit;
                    case 'update':
                        $this->db->where(array(
                            'id' => $element_id,
                            'form_id' => $form->id,
                            'stage_id' => $this->input->post('stage_id')
                        ));
                        $data = array(
                            'task_name' => $this->input->post('name')
                        );
                        $this->db->update('construction_check_list', $data);
                        exit;
                    case 'delete':
                        $this->db->delete('construction_check_list',array('id' => $element_id, 'stage_id' => $this->input->post('stage_id'), 'form_id'=>$form->id));
                        exit;

                }
            }
        }

    }

    /*the tendering page*/
    public function tendering($jid){
		
		$this->wbs_helper->is_own_job($jid);

        $this->_replace_job_id_in_session_url($jid);

        $development = $this->db->get_where('construction_development',array('id' => $jid, 'wp_company_id' => $this->wp_company_id), 0, 1)->row();

        $data['title'] = $development->development_name;

        $data['development_id'] = $jid;

        $data['development_details'] = $development;

        $data['wp_company_id'] = $this->wp_company_id;

        $this->db->select(
            "item.id item_id,
             contact.id contact_id,
             company.id company_id,
             item.name item,
             item.job_id item_job_id,
             CONCAT(contact.contact_first_name,' ',contact.contact_last_name) contact_name,
             company.company_name company_name,
             contact.contact_email contact_email,
             company.company_email company_email,
             DATE_FORMAT(t_jobs.date_submitted, '%d-%m-%Y') date_submitted,
             t_jobs.id construction_tendering_job_id,
             date_received,
             company2.company_name contact_company_name,
             item_contact.id item_contact_id,
             item_contact.job_id item_contact_job_id,
             received_fid", false
        );
        $this->db->join('construction_tendering_templates template','template.id = job.tendering_template_id');
        $this->db->join('construction_tendering_template_items item', 'item.template_id = template.id','left');
        $this->db->join('construction_tendering_item_contacts item_contact', 'item_contact.item_id = item.id', 'left');
        $this->db->join('construction_tendering_jobs t_jobs','t_jobs.job_id = job.id AND t_jobs.item_id = item.id AND t_jobs.contact_id = item_contact.id', 'left');
        $this->db->join('contact_contact_list contact','contact.id = item_contact.contact_contact_list_id','left');
        $this->db->join('contact_company company','company.id = item_contact.contact_company_id','left');
        $this->db->join('contact_company company2','company2.id = contact.company_id', 'left'); /*task #4555*/
        $this->db->order_by('order','asc');
        $this->db->where("(item.job_id IS NULL OR item.job_id = {$jid})"); // task #4580

        if($this->input->post('search')){
            $sesData['item_name']= $this->input->post('item_name');
            $sesData['contractor_name']= $this->input->post('contractor_name');
            $sesData['company_name']= $this->input->post('company_name');
            //$sesData['date_received']= $this->input->post('date_received');
           
            $this->session->set_userdata($sesData); 
            if($this->session->userdata('item_name')!=''){
                $this->db->like('item.name', $this->input->post('item_name') );
                
            }
 
            if($this->session->userdata('contractor_name')!=''){

                $this->db->like('CONCAT(contact.contact_first_name," ",contact.contact_last_name)', $this->input->post('contractor_name') );
                
            }

            if($this->session->userdata('company_name')!=''){
                $this->db->like('company2.company_name', $this->input->post('company_name') );
                
            }


            /*if($this->session->userdata('date_received')!=''){
                $this->db->like('date_received', $this->input->post('date_received') );
                
            }*/
 
           
        }

        $rows = $this->db->get_where('construction_development job',array(
            'job.id' => $jid, 'job.wp_company_id'=>$this->wp_company_id
        ))->result();

        $tendering_info = array();
        foreach($rows as $row){
            $tendering_info[$row->item_id][] = $row;
        }
        $data['tendering_info'] = $tendering_info;

        /*the groups*/
        $this->db->select("group.name group_name,
                       group.id group_id,
                       item.id item_id,
                       item.name item,
                       CONCAT(contact2.contact_first_name,' ',contact2.contact_last_name) contact_name,
                       company.company_name company_name,
                       contact.id contact_id,
                       contact2.contact_email,
                       company.company_email,
                       contact_contact_list_id,
                       contact_company_id
                       ", false);
        $this->db->join('construction_tendering_template_items item','item.group_id = group.id', 'left');
        $this->db->join('construction_tendering_item_contacts contact','contact.item_id = item.id', 'left');
        $this->db->join('contact_contact_list contact2','contact2.id = contact.contact_contact_list_id', 'left');
        $this->db->join('contact_company company','company.id = contact.contact_company_id', 'left');
        $this->db->join('construction_tendering_templates template','template.id = item.template_id', 'left');
        $this->db->where('template.id',$development->tendering_template_id);
		$this->db->where("(item.job_id IS NULL OR item.job_id = {$development->id})"); // task #4580
        $groups_contacts = $this->db->get('construction_tendering_groups group')->result();

        $groups = array();
        foreach($groups_contacts as $group){
            $groups[$group->group_name][$group->item][] = (array)$group;
        }
        $data['group_contacts'] = $groups;
        $data['groups'] = $this->db->get('construction_tendering_groups')->result();

        /*task #4580*/
        $this->db->order_by('category_name');
        $data['contact_categories'] = $this->db->get_where('contact_category',array('wp_company_id' => $this->wp_company_id))->result();
        $this->db->order_by('company_name');

        $data['contact_companies'] = $this->db->get_where('contact_company',array('wp_company_id' => $this->wp_company_id))->result();
        $this->db->order_by('contact_first_name');

        $data['contact_contacts'] = $this->db->get_where('contact_contact_list',array('wp_company_id' => $this->wp_company_id))->result();

        $this->db->select('item.*');
        $this->db->join('construction_tendering_template_items item','item.template_id = construction_tendering_templates.id');
        $this->db->where("(item.job_id IS NULL OR item.job_id = {$development->id})");
        $this->db->where('wp_company_id',$this->wp_company_id);
        $this->db->where('construction_tendering_templates.id',$development->tendering_template_id);
        $this->db->order_by('item.name');
        $data['tendering_items'] = $this->db->get('construction_tendering_templates')->result();

        $data['development_content'] = $this->load->view('developments/development_tendering', $data, true);

        $this->load->view('includes/header', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);

    }

    public function upload_tender(){

        if ($_FILES['tenderFile']) {

            $config['upload_path'] = UPLOAD_DEVELOPMENT_IMAGE_PATH . 'tendering/submits/';
            $config['allowed_types'] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            //$_FILES['tenderFile']['name'] = time().'_'.$_FILES['tenderFile']['name'];

            if ($this->upload->do_upload('tenderFile')) {

                $upload_data = $this->upload->data();

                $file = array(
                    'filename' => $upload_data['file_name'],
                    'filetype' => $upload_data['file_type'],
                    'filesize' => $upload_data['file_size'],
                    'filepath' => $upload_data['full_path'],
                    'created' => date("Y-m-d H:i:s"),
                    'uid' => $this->user_id,
                    'wp_company_id' => $this->wp_company_id
                );
                $this->db->insert('construction_file',$file);
                echo $this->db->insert_id(); exit;
            } else {
                echo -1; exit;
            }

        }else{
            echo -1; exit;
        }
    }
    public function send_tender($email, $fid, $items){
        /*verifying the file and contact*/
        $file = $this->db->get_where('construction_file',array('fid'=>$fid, 'wp_company_id'=>$this->wp_company_id), 0, 1)->row();
        if(is_null($file)){
            echo -1; exit;
        }
        $email = urldecode($email);
        /*$this->db->join('construction_tendering_template_items item', 'item.template_id = template.id');
        $this->db->join('construction_tendering_item_contacts contact', 'contact.item_id = item.id');
        $contact = $this->db->get_where('construction_tendering_templates template',array('contact.id'=>$cid,'wp_company_id'=>$this->wp_company_id), 0, 1)->row();
        if(is_null($contact)){
            echo -1; exit;
        }*/
        /*getting all info*/
        $this->db->select("CONCAT(contact2.contact_first_name,' ',contact2.contact_last_name) contact_name,
                            contact2.contact_email,
                            company.company_name,
                            company.company_email,
                            item.id item_id,
                            item.template_id,
                            item.name item_name,
                            contact.id cid", false);
        $this->db->join('construction_tendering_item_contacts contact','contact.item_id = item.id');
        $this->db->join('construction_development development', 'development.tendering_template_id = item.template_id');
        $this->db->join('contact_contact_list contact2','contact2.id = contact.contact_contact_list_id','left');
        $this->db->join('contact_company company','company.id = contact.contact_company_id','left');
        $domain = $_SERVER['SERVER_NAME'];
        $this->db->where('development.id',$_SESSION[$domain]['current_job']);
        $this->db->where('(contact2.contact_email ="'.$email.'" OR company.company_email = "'.$email.'")');
        $this->db->where('item_id in ('.$items.')');

        $info = $this->db->get('construction_tendering_template_items item')->result();

        /*sending mail*/
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.wclp.co.nz';
        $config['smtp_port'] = '2525';
        $config['smtp_user'] = 'tendering@wclp.co.nz';
        $config['smtp_pass'] = 'ZacGoi52)';
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n";

        $this->load->library('email',$config);
        $this->email->set_mailtype("html");

        $this->db->select('job.id, job.job_number, job.development_name, wp_company.client_name, wp_company.id as client_id');
        $this->db->join('wp_company','wp_company.id = job.wp_company_id');
        $job = $this->db->get_where('construction_development job',array('job.id'=>$_SESSION[$domain]['current_job']),0, 1)->row();

        $subject = "#{$job->job_number} - {$job->development_name} - {$job->client_name} Quote Request";
        $contractor_name = ($info[0]->contact_name) ? $info[0]->contact_name : $info[0]->company_name;
        //$contractor_email = ($info[0]->contact_email) ? $info[0]->contact_email : $info[0]->company_email;
        $message =  "Dear {$contractor_name},<br><br>".
            "You have the opportunity to quote these following items: <br>
            <ul>";
        foreach($info as $inf){
            $message .= "<li>".$inf->item_name."</li>";
        }
        $message .= "</ul>";
        $message .= "Please contact Sophie Cruttenden - sophie@williamsproperty.co.nz - 027 695 0132.<br><br>";
        $message .= "Please use the attached document for your pricing. <br><br>
                     Thank you.";

        $file = $file->filepath;

		// Task No 4462:Construction: Tendering E-mail
		if($job->client_id=='29'){
			$this->email->from('tendering@williamsproperty.co.nz', 'Tendering System');
		}else{
			$this->email->from('tendering@wclp.co.nz', 'Tendering System');
		}
        
        //$this->email->to($contractor_email);
        $this->email->to($email);

        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach($file);

        if($this->email->send()){
            $data = array();
            foreach($info as $inf){
                $data[] = array(
                    'job_id' => $job->id,
                    'template_id' => $inf->template_id,
                    'contact_id' => $inf->cid,
                    'item_id' => $inf->item_id,
                    'date_submitted' => date('Y-m-d'),
                    'submitted_fid' => $fid
                );
                $this->db->delete('construction_tendering_jobs', array('job_id' => $job->id, 'contact_id' => $info->cid, 'item_id' => $inf->item_id));
            }

            $this->db->insert_batch('construction_tendering_jobs',$data);

            /*log*/
            $this->wbs_helper->log('Send tender','Sent tender for job: <b>'.$job->development_name.'</b> to <b>'.$contractor_name.'</b>');
            /*****/

            echo 1;

        }else{
            echo -1;
        }
        exit;
    }

    public function upload_quote(){
        $domain = $_SERVER['SERVER_NAME'];
        $job_id = $_SESSION[$domain]['current_job'];
		$this->wbs_helper->is_own_job($job_id);
        $post = $this->input->post();
        if($_FILES['quoteFile']['size'] == 0 || empty($post['contact']) || empty($post['item_id'])){
            echo "Fill up the form properly.";
            exit;
        }
        $this->db->select('c.id contact_id, item_id, t.id template_id');
        $this->db->join('construction_tendering_templates t', 't.id = j.tendering_template_id');
        $this->db->join('construction_tendering_template_items i', 'i.template_id = t.id');
        $this->db->join('construction_tendering_item_contacts c', 'c.item_id = i.id');
        $this->db->where('j.id',$job_id);
        if(strpos($post['contact'],'contact_') !== false){
            $this->db->where('c.contact_contact_list_id',str_replace("contact_","",$post['contact']));
        }else{
            $this->db->where('c.contact_company_id',str_replace("company_","",$post['contact']));
        }
        $this->db->where('item_id in ('.implode(',',$post['item_id']).')');
        $info = $this->db->get('construction_development j')->result();
        if($info){
            /*uploading quote*/
            $config['upload_path'] = UPLOAD_DEVELOPMENT_IMAGE_PATH . 'tendering/quotes/';
            $config['allowed_types'] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('quoteFile')) {

                $upload_data = $this->upload->data();

                $file = array(
                    'filename' => $upload_data['file_name'],
                    'filetype' => $upload_data['file_type'],
                    'filesize' => $upload_data['file_size'],
                    'filepath' => $upload_data['full_path'],
                    'created' => date("Y-m-d H:i:s"),
                    'uid' => $this->user_id,
                    'wp_company_id' => $this->wp_company_id
                );
                $this->db->insert('construction_file',$file);
                $fid = $this->db->insert_id();

                if($fid){
                    /*adding / updating entry*/
                    foreach($info as $inf){
                        $data = array(
                            'contact_id' => $inf->contact_id,
                            'item_id' => $inf->item_id,
                            'template_id' => $inf->template_id,
                            'job_id' => $job_id
                        );
                        $row = $this->db->get_where('construction_tendering_jobs',$data, 0, 1)->row();
                        if($row){
                            /*tender was sent. we update the entry*/
                            $this->db->insert('construction_tendering_received_files',array(
                                'construction_tendering_job_id' => $row->id,
                                'date_received' => date('Y-m-d'),
                                'received_fid' => $fid
                            ));
                        }else{
                            /*tender was not submitted. we add a new entry*/
                            $this->db->insert('construction_tendering_jobs', $data);
                            $this->db->insert('construction_tendering_received_files',array(
                                'construction_tendering_job_id' => $this->db->insert_id(),
                                'date_received' => date('Y-m-d'),
                                'received_fid' => $fid
                            ));
                        }

                    }

                    /*log*/
                    $this->db->select('j.development_name, i.name item_name, CONCAT(contact.contact_first_name," ",contact.contact_last_name) contact_name, company.company_name',false);
                    $this->db->join('construction_tendering_templates t', 't.id = j.tendering_template_id');
                    $this->db->join('construction_tendering_template_items i', 'i.template_id = t.id');
                    $this->db->join('construction_tendering_item_contacts c', 'c.item_id = i.id');
                    $this->db->join('contact_contact_list contact','contact.id = c.contact_contact_list_id','left');
                    $this->db->join('contact_company company','company.id = c.contact_company_id','left');
                    $this->db->where('j.id',$job_id);
                    if(strpos($post['contact'],'contact_') !== false){
                        $this->db->where('c.contact_contact_list_id',str_replace("contact_","",$post['contact']));
                    }else{
                        $this->db->where('c.contact_company_id',str_replace("company_","",$post['contact']));
                    }
                    $this->db->where('item_id in ('.implode(',',$post['item_id']).')');
                    $info = $this->db->get('construction_development j')->result();
                    $items = array();
                    foreach($info as $inf){
                        $items[] = "<b>".$inf->item_name."</b>";
                    }
                    $items = implode(", ",$items);
                    $contractor = ($info[0]->company_name)?"company <b>".$info[0]->company_name."</b>" : "contact <b>".$info[0]->contact_name."</b>";
                    $this->wbs_helper->log('Upload quote',"Uploaded quote in job <b>{$info[0]->development_name}</b> for {$contractor} for items: {$items}");
                }
            }
        }

        redirect(site_url('constructions/tendering/'.$job_id.'?cp=pre_construction'));

    }

    public function download_quote($fid = null){

        if(is_null($fid)) exit;

        $file = $this->db->get_where('construction_file',array(
            'fid' => $fid, 'wp_company_id' => $this->wp_company_id
        ),0,1)->row();

        /*log*/
        $this->db->select('development_name, concat(contact.contact_first_name," ",contact.contact_last_name) contact_name, company_name',false);
        $this->db->join('construction_development','j.job_id = construction_development.id');
        $this->db->join('construction_tendering_item_contacts c','c.id = j.contact_id');
        $this->db->join('contact_contact_list contact','contact.id = c.contact_contact_list_id','left');
        $this->db->join('contact_company','contact_company.id = c.contact_company_id','left');
        $info = $this->db->get_where('construction_tendering_jobs j',array('received_fid'=>$fid),1,0)->row();
        $contractor = ($info->company_name)? "company <b>{$info->company_name}</b>" : "contact <b>{$info->contact_name}</b>";
        $this->wbs_helper->log('Download quote',"Downloaded quote for job <b>{$info->development_name}</b> for {$contractor}");

        header('Content-type: '.$file->filetype);
		if($file->filetype != 'application/pdf'){
        	header('Content-Disposition: attachment; filename="' . $file->filename . '"');
		}
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file->filepath));
        header('Accept-Ranges: bytes');

        @readfile($file->filepath); 

    }

    public function milestones($job_id, $op){
		
		$this->wbs_helper->is_own_job($job_id);

        $job = $this->db->get_where('construction_development', array(
            'id' => $job_id, 'wp_company_id' => $this->wp_company_id
        ), 0,1)->row();

        if($op == 'add'){

            $this->db->insert('construction_development_milestones',array(
                'milestone_template_id' => $this->input->post('template_id'),
                'job_id' => $job->id,
                'date' => $this->input->post('date'),
                'construction_phase'=> $_GET['cp']
            ));

            /*log*/
            $m = $this->db->get_where('construction_milestone_templates',array('id'=>$this->input->post('template_id')),1,0)->row()->name;
            $cp = str_replace('_','-',$_GET['cp']);
            $this->wbs_helper->log('Add milestone',"Added milestone <b>{$m}</b> in <b>{$cp}</b> - <b>{$job->development_name}</b>");

        }

        if($op == 'update'){
            $milestone = $this->db->get_where('construction_development_milestones', array(
                'id' => $this->input->post('id'), 'wp_company_id' => $this->wp_company_id, 'job_id'=>$job->id
            ), 0,1)->row();
            $this->db->where('id', $milestone->id);
            $this->db->update('construction_development_milestones',array(
                'name' => $this->input->post('name'),
                'date' => $this->input->post('date')
            ));

            /*log*/
            $this->db->select("construction_milestone_templates.name");
            $this->db->join('construction_milestone_templates','construction_milestone_templates.id = construction_development_milestones.milestone_template_id');
            $m = $this->db->get_where('construction_development_milestones',array('id'=>$this->input->post('id')),1,0)->row()->name;
            $cp = str_replace('_','-',$_GET['cp']);
            $this->wbs_helper->log('Update milestone',"Updated milestone <b>{$m}</b> in <b>{$cp}</b> - <b>{$job->development_name}</b>");
        }

        if($op == 'delete'){

            $this->db->delete('construction_development_milestones',array(
                'id'=>$this->input->post('id'),
                'job_id'=>$job->id
            ));

            /*log*/
            $this->db->select("construction_milestone_templates.name");
            $this->db->join('construction_milestone_templates','construction_milestone_templates.id = construction_development_milestones.milestone_template_id');
            $m = $this->db->get_where('construction_development_milestones',array('construction_development_milestones.id'=>$this->input->post('id')),1,0)->row()->name;
            $cp = str_replace('_','-',$_GET['cp']);
            $this->wbs_helper->log('Delete milestone',"Deleted milestone <b>{$m}</b> in <b>{$cp}</b> - <b>{$job->development_name}</b>");
        }

        redirect(site_url('constructions/construction_overview/'.$job->id.'?cp='.$_GET['cp']));
    }
    private function _replace_job_id_in_session_url($pid){

        if(is_null($this->db->get_where('construction_development',array('id'=>$pid, 'wp_company_id'=>$this->wp_company_id), 0, 1)->row())){
            return;
        }

        $domain = $_SERVER['SERVER_NAME'];

        $_SESSION[$domain]['current_job'] = $pid;

        /*re building the pre construction and construction page in session*/
        if(array_key_exists($domain,$_SESSION) && array_key_exists('construction_page',$_SESSION[$domain])){

            $_SESSION[$domain]['construction_page'] = $this->_rebuild_url_in_session($_SESSION[$domain]['construction_page']);
        }
        if(array_key_exists($domain,$_SESSION) && array_key_exists('pre_construction_page',$_SESSION[$domain])){

            $_SESSION[$domain]['pre_construction_page'] = $this->_rebuild_url_in_session($_SESSION[$domain]['pre_construction_page']);
        }
        if(array_key_exists($domain,$_SESSION) && array_key_exists('post_construction_page',$_SESSION[$domain])){

            $_SESSION[$domain]['post_construction_page'] = $this->_rebuild_url_in_session($_SESSION[$domain]['post_construction_page']);
        }
    }
    private function _rebuild_url_in_session($url){
        /*getting the uri*/
        $url = str_replace(base_url(),'',$url);
        /*
         * now the url is in format "controller/action/job_id"
         * we will replace this job_id with new job_id and return the URL
         */
        $domain = $_SERVER['SERVER_NAME'];
        $url_parts = explode('/',$url);
        $url_parts[2] = $_SESSION[$domain]['current_job'] ;
        return base_url().implode('/',$url_parts);
    }

    /*task #4167*/
    public function update_tendering_status(){

        $post = $this->input->post();

        $job_id = $post['job_id'];
		
		$this->wbs_helper->is_own_job($job_id);

        $item_id = $post['item_id'];

        $contact_id = ($post['contact_id']) ? $post['contact_id'] : '-1';

        $company_id = ($post['company_id']) ? $post['company_id'] : '-1';

        /*getting job info*/
        $job = $this->db->get_where('construction_development',array('id'=>$job_id, 'wp_company_id'=>$this->wp_company_id),1,0)->row();

        /*task #4566*/
        $this->db->where(array(
            'job_id' => $job->id, 'item_id' => $item_id
        ));
        $this->db->update('construction_tendering_job_status',array('status'=>0));

        /*$construction_template_task_id = $this->db->get_where('construction_tendering_template_items',array('id'=>$item_id),1,0)->row()->construction_template_task_id;
        $this->db->where('construction_template_task_id',$construction_template_task_id);
        $this->db->where('development_id',$job_id);
        $this->db->update('construction_development_task',array('task_person_responsible' => $contact_id));*/

        /*task #4633*/
        /*getting the contact's category*/
        $category = $this->db->get_where('construction_tendering_item_contacts',array('contact_contact_list_id' => $contact_id, 'item_id' => $item_id),1,0)->row()->category_id;

        if($category){
            /*making the contact person responsible in all tasks in this job in this category*/
            $this->db->where(array(
                'development_id' => $job->id,
                'task_category' => $category
            ));
            $this->db->update('construction_development_task',array(
                    'task_person_responsible' => $contact_id,
                    'task_company' => $category
                )
            );
        }

        /*updating status*/
        /*if it already has an entry for status we will update. otherwise insert*/
        $sql = "select * from construction_tendering_job_status
                where job_id = {$job->id} AND
                item_id = {$item_id} AND
                (contact_id = {$contact_id} OR company_id = {$company_id}) LIMIT 0,1";
        $res = $this->db->query($sql)->row();
        if($res){
            $this->db->where('id',$res->id);
            $res->status = $post['status'];
            $this->db->update('construction_tendering_job_status',$res);
            $id = $res->id;
        }else{
            $this->db->insert('construction_tendering_job_status',$post);
            $id = $this->db->insert_id();
        }
        /*log*/
        $this->db->select('development_name, item.name item_name, concat(contact.contact_first_name," ",contact.contact_last_name) contact_name, company_name',false);
        $this->db->join("construction_development","construction_development.id = status.job_id");
        $this->db->join("construction_tendering_template_items item", "item.id = status.item_id");
        $this->db->join('contact_contact_list contact','contact.id = status.contact_id','left');
        $this->db->join('contact_company','contact_company.id = status.company_id','left');
        $info = $this->db->get_where("construction_tendering_job_status status",array('status.id'=>$id),1,0)->row();
        $contractor = ($info->company_name)? "company <b>{$info->company_name}</b>" : "contact <b>{$info->contact_name}</b>";
        $status = ($post['status'])?"checked":"unchecked";
        $this->wbs_helper->log('Tendering status',"{$status} tendering status for {$contractor} item: <b>{$info->item_name}</b> in job: <b>{$info->development_name}</b>");

    }

	public function update_purchaser(){
        $dev_id = $_GET['dev_id'];
		
		$this->wbs_helper->is_own_job($dev_id);
		
        $cid = urldecode($_GET['cid']);

        $add = array(
			'purchaser' => $cid
		);
        $this->db->where('id',$dev_id);
        $this->db->update('construction_development',$add);
    }
	public function update_investor(){
        $dev_id = $_GET['dev_id'];
		$this->wbs_helper->is_own_job($dev_id);
        $cid = urldecode($_GET['cid']);

        $add = array(
			'investor' => $cid
		);
        $this->db->where('id',$dev_id);
        $this->db->update('construction_development',$add);
    }

    public function download_charts()
    {

        //define('K_PATH_IMAGES','');

        $this->load->library('Pdf');

        /*setting the logo*/
        $this->db->select("wp_company.*,wp_file.*");
        $this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
        $this->db->where('wp_company.id', $this->wp_company_id);
        $wpdata = $this->db->get('wp_company')->row();

        //$logo = $_SERVER['DOCUMENT_ROOT'].'/uploads/logo/'.$wpdata->filename;
        $logo = 'http://www.wclp.co.nz/uploads/logo/' . $wpdata->filename;

        if ($this->input->post('job_id')) {

            $job = $this->db->get_where('construction_development', array('id' => $this->input->post('job_id')), 1, 0)->row();

            $dt = date('d-m-Y H:i:s');
            $html = <<<EOT
            <br><br>
            <table width="100%" style="margin-top:20px">
						<tr>
						<td><img src="{$logo}" height="67"></td>
						<td style="text-align:left">
						<span style="font-size:16px;">Combined Overview for job #{$job->job_number}: {$job->development_name}</span><br />
						<span style="font-size:12px;">Generated at {$dt}</span>
						</td>
						</tr>
			</table>
EOT;
        } else {
            $html = '<img height="80px" src="' . $logo . '" />';
        }

        $this->pdf->headerHtml = $html;

        //$this->pdf->setHeaderData($logo);

        $this->pdf->setPageOrientation('L', true);

        $this->pdf->SetTopMargin(30);

        $svg_data = $this->input->post('svg');

        foreach ($svg_data as $svg) {
            $svg = str_replace('data:image/svg+xml;base64,', '', $svg);
            $imgdata = base64_decode($svg);
            $orientation = ($this->input->post('h') > $this->input->post('w')) ? 'P' : 'L';
            if ($orientation == 'P') {
                $this->pdf->AddPage($orientation, array($this->input->post('w') * 0.26, $this->input->post('h') * 0.26));
                $this->pdf->ImageSVG('@' . $imgdata, '', '', $this->input->post('w') * 0.26, $this->input->post('h') * 0.26);
            } else {
                $this->pdf->AddPage($orientation);
                $this->pdf->ImageSVG('@' . $imgdata);
            }

        }
        $this->pdf->output('wpconstruction_charts.pdf', 'D');
        exit;


    }
    
    public function update_list($development_id,$field,$value){

		if($field == 'project_manager'){
			$user_role_id = 2;
		}elseif($field == 'builder'){
			$user_role_id = 4;
		}elseif($field == 'investor'){
			$user_role_id = 5;
		}else{
			$user_role_id = 3;
		}

        
        $add = array(
			$field => $value
		);	 
        $this->db->where('id',$development_id);
        $this->db->update('construction_development',$add);

		$contact_ids = explode(",",$value);
		
		// delete all entry regarding this job and user_role
		$this->db->where('job_id',$development_id);
		$this->db->where('user_role_id',$user_role_id);
 		$this->db->delete('construction_user_permitted_job');

		for($i=0;$i < count($contact_ids); $i++){
			
			$this->db->select('system_user_id');
			$this->db->where('id',$contact_ids[$i]);
			$res = $this->db->get('contact_contact_list')->row();
			
			$uid = $res->system_user_id;
			
			if($uid){

				$insert_data = array(
                	'user_id' => $uid,
                	'job_id' => $development_id,
                	'user_role_id'=>$user_role_id
            	);

            	$this->db->insert('construction_user_permitted_job', $insert_data); 
			}
			
		}
		
		echo '1';
    }

    public function add_tendering_item(){

        if($this->input->post() && $this->input->post('task_id') && $this->input->post('job_id')){
            $post = $this->input->post();
            $job = $this->db->get_where('construction_development',array('id' => $post['job_id'],'wp_company_id' => $this->wp_company_id), 1, 0)->row();
            $this->db->select('task.*');
            $this->db->join('construction_template','construction_template.id = task.template_id');
            $task = $this->db->get_where('construction_template_task task',array('task.id' => $post['task_id'],'construction_template.wp_company_id' => $this->wp_company_id), 1, 0)->row();
            if($job && $task){
                $data = array(
                    'template_id' => $job->tendering_template_id,
                    'job_id' => $job->id,
                    'name' => $task->task_name,
                    'group_id' => $post['group'],
                    'construction_template_task_id' => $task->id
                );
                $this->db->insert('construction_tendering_template_items',$data);

                $id = $this->db->insert_id();

                $this->db->select("max(`order`) o");
                $this->db->where('template_id', $job->tendering_template_id);
                $this->db->where("(job_id IS NULL OR job_id = {$job->id})");
                $order = $this->db->get('construction_tendering_template_items')->row()->o;

                $this->db->where('id',$id);
                $this->db->update('construction_tendering_template_items',array('order' => $order+1));

                /*log*/
                $this->wbs_helper->log('Tendering item add',"Added item <b>{$task->task_name}</b> in Job <b>{$job->development_name}</b>");
                /*****/

                redirect(site_url('constructions/tendering/'.$job->id.'?cp='.$post['cp']));

            }
        }
    }

    public function add_tendering_contact(){

        $post = $this->input->post();
        $job = $this->db->get_where('construction_development',array('id' => $post['job_id'],'wp_company_id' => $this->wp_company_id), 1, 0)->row();
        $item = '';
        $company = '';
        $contact = '';

        if($this->input->post() && $this->input->post('item') && $this->input->post('company')){

            $this->db->select('item.*');
            $this->db->join('construction_tendering_templates','construction_tendering_templates.id = item.template_id');
            $item = $this->db->get_where('construction_tendering_template_items item',array('item.id' => $post['item'],'construction_tendering_templates.wp_company_id' => $this->wp_company_id), 1, 0)->row();

            $company = $this->db->get_where('contact_company',array('id' => $post['company'],'wp_company_id' => $this->wp_company_id), 1, 0)->row();

            if($post['contact']){
                $contact = $this->db->get_where('contact_contact_list',array('id' => $post['contact'],'wp_company_id' => $this->wp_company_id), 1, 0)->row();
            }

            if($job && $item && $company){

                $data = array(
                    'item_id' => $item->id,
                    'job_id' => $job->id,
                    'category_id' => $post['category']
                );
                if($contact){
                    $data['contact_contact_list_id'] = $contact->id;
                    $msg = "Added contact <b>{$contact->contact_first_name} {$contact->contact_last_name}</b> under item <b>{$item->name}</b> in job <b>{$job->development_name}</b>";
                }else{
                    $data['contact_company_id'] = $company->id;
                    $msg = "Added company <b>{$contact->company_name}</b> under item <b>{$item->name}</b> in job <b>{$job->development_name}</b>";
                }

                $this->db->insert('construction_tendering_item_contacts',$data);

                /*log*/
                $this->wbs_helper->log('Tendering contact add',$msg);
                /*****/
            }

        }

        redirect(site_url('constructions/tendering/'.$job->id.'?cp='.$post['cp']));
    }

    public function delete_tendering_item(){

        $success = false;

        if($this->input->post() && $this->input->post('job_id') && $this->input->post('item_id')){

            $post = $this->input->post();

            $job = $this->db->get_where('construction_development',array('id' => $post['job_id'],'wp_company_id' => $this->wp_company_id), 1, 0)->row();

            $this->db->select('item.*');
            $this->db->join('construction_tendering_templates','construction_tendering_templates.id = item.template_id');
            $item = $this->db->get_where('construction_tendering_template_items item',array('item.id' => $post['item_id'],'construction_tendering_templates.wp_company_id' => $this->wp_company_id), 1, 0)->row();

            if($job && $item){

                $this->db->where(array(
                    'id' => $item->id,
                    'job_id' => $job->id
                ));

                $this->db->delete('construction_tendering_template_items');

                if($this->db->affected_rows() == 1){
                    /*log*/
                    $msg = "deleted item <b>{$item->name}</b> from job <b>{$job->development_name}</b>";
                    $this->wbs_helper->log('Tendering item delete',$msg);
                    /*****/

                    $success = true;
                }

            }
        }

        if(!$success){

            http_response_code(500);
        }
    }

    public function delete_tendering_contact(){

        $success = false;

        if($this->input->post() && $this->input->post('job_id') && $this->input->post('contact_id')){

            $post = $this->input->post();

            $job = $this->db->get_where('construction_development',array('id' => $post['job_id'],'wp_company_id' => $this->wp_company_id), 1, 0)->row();

            $this->db->select('contact.*, item.name item_name');
            $this->db->join('construction_tendering_template_items item','item.id = contact.item_id');
            $this->db->join('construction_tendering_templates template','template.id = item.template_id');
            $this->db->where('contact.id',$post['contact_id']);
            $this->db->where('template.wp_company_id',$this->wp_company_id);
            $this->db->where('contact.job_id',$job->id);

            $contact = $this->db->get('construction_tendering_item_contacts contact',1,0)->row();

            if($contact->contact_contact_list_id){
                $c = $this->db->get_where('contact_contact_list',array('id'=>$contact->contact_contact_list_id),1,0)->row();
                $contact_name = $c->contact_first_name." ".$c->contact_last_name;
            }else{
                $c = $this->db->get_where('contact_company',array('id'=>$contact->contact_company_id),1,0)->row();
                $contact_name = $c->company_name;
            }

            if($job && $contact){

                $this->db->where(array(
                    'id' => $contact->id,
                    'job_id' => $contact->job_id
                ));

                $this->db->delete('construction_tendering_item_contacts');

                if($this->db->affected_rows() == 1){
                    /*log*/
                    $msg = "deleted contact <b>{$contact_name}</b> from item <b>{$contact->item_name}</b> in job <b>{$job->development_name}</b>";
                    $this->wbs_helper->log('Tendering item delete',$msg);
                    /*****/

                    $success = true;
                }

            }
        }

        if(!$success){

            http_response_code(500);
        }
    }


	public function delete_tendering_quote(){
        $success = false;
        if($this->input->post() && $this->input->post('job_id') && $this->input->post('quote_id')){
            $post = $this->input->post();
			$this->wbs_helper->is_own_job($post['job_id']);
			$this->db->where(array(
 				'id' => $this->input->post('quote_id')
			));
 			$this->db->delete('construction_tendering_received_files');
			if($this->db->affected_rows() == 1){
 				$success = true;
			}  
        }
        /*if(!$success){

            http_response_code(500);
        }*/
    }

}