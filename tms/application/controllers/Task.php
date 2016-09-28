<?php
class Task extends CI_Controller{
    private $limit = 20;
    private $user_id;
    private $wp_company_id;

    function __construct(){
        parent::__construct();
		$this->load->model('task_model', '', TRUE);
        $this->load->model('request_model', '', TRUE);
        $this->load->model('overview_model', '', TRUE);
        $this->load->model('notes_model', '', TRUE);
        $this->load->library('wbs_helper');
        $this->load->library('breadcrumbs');
        $this->load->library('user_agent');
        $this->load->library(array('table', 'form_validation', 'session'));
        $this->load->helper(array('form', 'url', 'email'));

        $redirect_login_page = base_url() . 'user';
        if (!$this->session->userdata('user')){
            redirect($redirect_login_page, 'refresh');

        }
        $user = $this->session->userdata('user');
        $this->user_id = $user->uid;
        $this->wp_company_id = $user->company_id;
    }

    public function index(){
        $data['title'] = 'Requests';
        $data['maincontent'] = $this->load->view('requests', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function pending_tasks(){
        $data['title'] 			= 	'Pending Tasks';
		$data['pending_tasks'] 	= 	$this->task_model->get_pending_tasks();
		$data['closed_tasks'] 	=  	$this->task_model->get_closed_tasks();
        $data['maincontent'] 	= 	$this->load->view('task/pending_tasks_overview', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer', $data);
    }

	public function task_update($task_id){

		$data['title'] = 'Update Task';
        $data['action'] = site_url('task/task_update/' . $task_id);
		$this->_set_rules();
		if ($this->form_validation->run() === FALSE){
            $data['request'] = $this->request_model->get_request_detail($task_id)->row();
        }else{

			$post = $this->input->post();
            $config['upload_path'] = UPLOAD_FILE_PATH_REQUEST_DOCUMENT;
            $config['allowed_types'] = '*';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $document_id = $post['document_id'];

            if($this->upload->do_upload('upload_document')){
                $upload_data = $this->upload->data();
                $file = array(
                    'filename' => $upload_data['file_name'],
                    'filetype' => $upload_data['file_type'],
                    'filesize' => $upload_data['file_size'],
                    'filepath' => $upload_data['full_path'],
                    'updated' => date("Y-m-d H:i:s"),
                    'uid' => $user_id
                );
                if ($document_id > 0) {
                    $file_update_id = $this->request_model->request_file_update($document_id, $file);
                } else {
                    $document_insert_id = $this->request_model->file_insert($file);
                }

            }else{
        
            }

            $image_id = $post['image_id'];
            if($this->upload->do_upload('upload_image')){
                $upload_data = $this->upload->data();
                $file = array(
                    'filename' => $upload_data['file_name'],
                    'filetype' => $upload_data['file_type'],
                    'filesize' => $upload_data['file_size'],
                    'filepath' => $upload_data['full_path'],
                    'updated' => date("Y-m-d H:i:s"),
                    'uid' => $user_id
                );
                if($image_id > 0){
                    $file_update_id = $this->request_model->request_file_update($image_id, $file);
                }else{
                    $image_insert_id = $this->request_model->file_insert($file);
                }

            }else{

            }

            // Update data
            $request_update = array(
                'request_no' => $this->input->post('request_no'),
                'request_date' => $this->wbs_helper->to_mysql_date($this->input->post('request_date')),
                'request_title' => $this->input->post('request_title'),
                'request_description' => $this->input->post('request_description'),
                'company_id' => $this->wp_company_id,
                'project_id' => $this->input->post('project_id'),
                'assign_manager_id' => implode(",", $this->input->post('assign_manager_id')),
                'assign_developer_id' => implode(",", $this->input->post('assign_developer_id')),
                'priority' => $this->input->post('priority'),
                'estimated_completion' => $this->wbs_helper->to_mysql_date($this->input->post('estimated_completion')),
                'request_status' => $this->input->post('request_status'),
                'document_id' => ($document_id == 0) ? $document_insert_id : $document_id,
                'image_id' => ($image_id == 0) ? $image_insert_id : $image_id,
                'updated' => date("Y-m-d H:i:s"),
                'updated_by' => $user_id
            );

            $this->request_model->update($task_id, $request_update);
            $this->session->set_flashdata('success-message', 'Task Successfully Updated.');
            redirect('request/request_detail/' . $task_id);

		}
		$data['maincontent'] = $this->load->view('task/task_add', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer', $data);
	}

	function _set_rules(){
        $this->form_validation->set_rules('request_title', 'Title', 'required');
        $this->form_validation->set_rules('assign_manager_id[0]', 'Assign Manager', 'callback_assign_manager_id_check');
        $this->form_validation->set_rules('project_id', 'Project', 'callback_project_id_check');
	}
	public function assign_manager_id_check($str){
        if($str == ''){
            $this->form_validation->set_message('assign_manager_id_check', 'Task should be assigned at least One Manager');
            return FALSE;
        }else{
            return TRUE;
        }
    }
    public function company_id_check($str){
        if($str == 0){
            $this->form_validation->set_message('company_id_check', 'Select Project Company');
            return FALSE;
        }else{
            return TRUE;
        }
    }
    public function project_id_check($str){
        if($str == 0){
            $this->form_validation->set_message('project_id_check', 'Select Project');
            return FALSE;
        }else{
            return TRUE;
        }
    }

}