<?php

class Admindevelopment extends CI_Controller {
    private $user_id, $wp_company_id, $user_app_role;
    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'file'));
        $this->load->library(array('table', 'form_validation', 'session'));
        $this->load->model('admindevelopment_model', '', TRUE);
        $this->load->library('Wbs_helper');
        $this->load->helper('email');
        date_default_timezone_set("NZ");

        $redirect_login_page = base_url() . 'user';
        if (!$this->session->userdata('user')) {
            redirect($redirect_login_page, 'refresh');
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
    }

    public function development_list() {

        $data['title'] = 'Job List';
        $get = $_GET;
        //$this->limit = 50;
        $data['admindevelopments'] = $this->admindevelopment_model->development_list($get)->result();

        $data['devlopment_sub_sidebar'] = $this->load->view('admindevelopment/development_sub_sidebar', $data, true);
        $data['maincontent'] = $this->load->view('admindevelopment/development_list', $data, true);
        //$this->load->view('includes/header',$data);
        //$this->load->view('admindevelopment/development_sidebar',$data);
        $this->load->view('includes/popup_home', $data);
        //$this->load->view('includes/footer',$data);
    }

    public function development_start() {

        $data['title'] = 'Add Job';

        $data['devlopment_sub_sidebar'] = $this->load->view('admindevelopment/development_sub_sidebar', $data, true);
        $data['devlopment_content'] = $this->load->view('admindevelopment/development_start', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('admindevelopment/development_sidebar', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function development_add_template() {
        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $data['title'] = 'Add Job';
        $data['page_title'] = 'Add development Template';
        $data['action'] = site_url('admindevelopment/development_add_template');

        $data['devlopment_sub_sidebar'] = $this->load->view('admindevelopment/development_sub_sidebar', $data, true);
        $data['devlopment_content'] = $this->load->view('admindevelopment/development_add_template', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('admindevelopment/development_sidebar', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function development_add_template_update($development_id) {
        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $data['title'] = 'Edit Job';
        $data['page_title'] = 'Edit development Template';
        $data['action'] = site_url('admindevelopment/development_add_template');

        $data['admindevelopment'] = $this->admindevelopment_model->development_id($development_id)->row();

        $data['devlopment_sub_sidebar'] = $this->load->view('admindevelopment/development_sub_sidebar', $data, true);
        $data['devlopment_content'] = $this->load->view('admindevelopment/development_add_template', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('admindevelopment/development_sidebar', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function development_add_stage($did) {

        $data['title'] = 'Add Job';
        $data['page_title_before'] = 'Add Job Template';
        $data['page_title'] = 'Add Stage Template';
        $data['action'] = site_url('admindevelopment/development_add_stage');
        $data['development_id'] = $did;

        $data['devlopment_sub_sidebar'] = $this->load->view('admindevelopment/development_sub_sidebar', $data, true);
        $data['devlopment_content'] = $this->load->view('admindevelopment/development_add_stage', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('admindevelopment/development_sidebar', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function development_add_stage_update($did) {

        $data['title'] = 'Edit Job';
        $data['page_title_before'] = 'Edit Job Template';
        $data['page_title'] = 'Edit Stage Template';
        $data['action'] = site_url('admindevelopment/development_add_stage');

        $data['admindevelopment'] = $this->admindevelopment_model->development_id($did)->row();

        $data['devlopment_sub_sidebar'] = $this->load->view('admindevelopment/development_sub_sidebar', $data, true);
        $data['devlopment_content'] = $this->load->view('admindevelopment/development_add_stage', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('admindevelopment/development_sidebar', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function development_review($did) {

        $data['title'] = 'Add Job';
        $data['page_title_before'] = 'Add Job Template';
        $data['page_title'] = 'Add Stage Template';

        $data['development_id'] = $did;

        $data['devlopment_sub_sidebar'] = $this->load->view('admindevelopment/development_sub_sidebar', $data, true);
        $data['devlopment_content'] = $this->load->view('admindevelopment/development_review', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('admindevelopment/development_sidebar', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function development_review_update($did) {

        $data['title'] = 'Edit Job';
        $data['page_title_before'] = 'Edit Job Template';
        $data['page_title'] = 'Edit Stage Template';

        $data['development_id'] = $did;
        $data['admindevelopment'] = $this->admindevelopment_model->development_id($did)->row();

        $data['devlopment_sub_sidebar'] = $this->load->view('admindevelopment/development_sub_sidebar', $data, true);
        $data['devlopment_content'] = $this->load->view('admindevelopment/development_review', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('admindevelopment/development_sidebar', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function development_add() {
        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $data['title'] = 'Add Job';

        $data['action'] = site_url('admindevelopment/development_add');


        if ($this->input->post('submit')) {

            $post = $this->input->post();

            $development_add = array(
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
                'created' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            );

            $id = $this->admindevelopment_model->development_add($development_add);

            redirect('admindevelopment/development_add_template/' . $id);
        }
        $data['devlopment_sub_sidebar'] = $this->load->view('admindevelopment/development_sub_sidebar', $data, true);
        //$data['devlopment_sub_sidebar']=$this->load->view('includes/devlopment_sub_sidebar',$data,true);
        $data['devlopment_content'] = $this->load->view('admindevelopment/development_add', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('admindevelopment/development_sidebar', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function development_update($development_id) {
        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $data['title'] = 'Edit Job';
        $data['page_title'] = 'Edit Job Template';
        $data['action'] = site_url('admindevelopment/development_update/' . $development_id);


        if ($this->input->post('submit')) {

            $post = $this->input->post();
            $tid = $post['tid'];
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
                'fid' => $post['photo_insert_id'],
                'updated_by' => $user_id
            );

            $id = $this->admindevelopment_model->development_update($development_id, $development_update);

            redirect('admindevelopment/development_add_template_update/' . $development_id . '/' . $tid);
        } else {
            $data['admindevelopment'] = $this->admindevelopment_model->development_id($development_id)->row();
            $fid = $data['admindevelopment'];
            $data['file'] = $this->admindevelopment_model->development_feature_photo_id($fid->fid)->row();
        }

        $data['devlopment_sub_sidebar'] = $this->load->view('admindevelopment/development_sub_sidebar', $data, true);
        //$data['devlopment_sub_sidebar']=$this->load->view('includes/devlopment_sub_sidebar',$data,true);
        $data['devlopment_content'] = $this->load->view('admindevelopment/development_add', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('admindevelopment/development_sidebar', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function development_phase_add() {

        if ($this->input->post('submit')) {

            $post = $this->input->post();
            $template_id = $post['template_id'];
            $development_id = $post['development_id'];
            $url = $post['url'];

            $development_phase_add = array(
                'development_id' => $development_id,
                'template_id' => $template_id,
                'phase_name' => $post['phase_name'],
                'phase_length' => $post['phase_length'],
                'ordering' => $post['phase_ordering'],
                'planned_start_date' => $this->wbs_helper->to_mysql_date($post['planned_start_date']),
                'planned_finished_date' => $this->wbs_helper->to_mysql_date($post['planned_finished_date']),
                'phase_person_responsible' => $post['phase_person_responsible']
            );

            $phase_id = $this->admindevelopment_model->development_phase_add($development_phase_add);

            redirect('admindevelopment/' . $url . '/' . $development_id . '/' . $template_id . '/' . $phase_id);
        }
    }

    public function development_phase_update($phase_id) {

        if ($this->input->post('submit')) {

            $post = $this->input->post();
            $template_id = $post['template_id'];
            $development_id = $post['development_id'];
            $url = $post['url'];

            $development_phase_update = array(
                'phase_name' => $post['phase_name'],
                'planned_start_date' => $this->wbs_helper->to_mysql_date($post['planned_start_date']),
                'planned_finished_date' => $this->wbs_helper->to_mysql_date($post['planned_finished_date']),
                'phase_person_responsible' => $post['phase_person_responsible']
            );

            $this->admindevelopment_model->development_phase_update($phase_id, $development_phase_update);

            redirect('admindevelopment/' . $url . '/' . $development_id . '/' . $template_id . '/' . $phase_id);
        }
    }

    public function development_phase_delete($phase_id) {

        $post = $this->input->post();
        $template_id = $post['template_id'];
        $development_id = $post['development_id'];
        $url = $post['url'];

        $this->admindevelopment_model->development_phase_delete($phase_id, $template_id, $development_id);
        // redirect to Employee list page
        redirect('admindevelopment/' . $url . '/' . $development_id . '/' . $template_id);
    }

    public function development_task_add() {

        if ($this->input->post('submit')) {

            $post = $this->input->post();
            $template_id = $post['template_id'];
            $development_id = $post['development_id'];
            $phase_id = $post['phase_id'];
            $url = $post['url'];

            $development_task_add = array(
                'development_id' => $development_id,
                'template_id' => $template_id,
                'phase_id' => $phase_id,
                'task_name' => $post['task_name'],
                'task_length' => $post['task_length'],
                'ordering' => $post['task_ordering'],
                'task_start_date' => $this->wbs_helper->to_mysql_date($post['task_start_date'])
            );

            $this->admindevelopment_model->development_task_add($development_task_add);

            redirect('admindevelopment/' . $url . '/' . $development_id . '/' . $template_id . '/' . $phase_id);
        }
    }

    public function development_task_update($task_id) {

        if ($this->input->post('submit')) {

            $post = $this->input->post();
            $template_id = $post['template_id'];
            $development_id = $post['development_id'];
            $phase_id = $post['phase_id'];
            $url = $post['url'];

            $development_task_update = array(
                'task_name' => $post['task_name'],
                'task_start_date' => $this->wbs_helper->to_mysql_date($post['task_start_date'])
            );

            $this->admindevelopment_model->development_task_update($task_id, $development_task_update);

            redirect('admindevelopment/' . $url . '/' . $development_id . '/' . $template_id . '/' . $phase_id);
        }
    }

    public function development_task_delete($task_id) {

        $post = $this->input->post();
        $template_id = $post['template_id'];
        $development_id = $post['development_id'];
        $phase_id = $post['phase_id'];
        $url = $post['url'];

        $this->admindevelopment_model->development_task_delete($task_id);
        // redirect to Employee list page
        redirect('admindevelopment/' . $url . '/' . $development_id . '/' . $template_id . '/' . $phase_id);
    }

    public function upload_development_feature_photo() {

        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $config['upload_path'] = UPLOAD_DEVELOPMENT_IMAGE_PATH;
        $config['allowed_types'] = '*';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('feature_photo')) {
            $upload_data = $this->upload->data();
            echo '<img width="296" height="306" src="' . base_url() . 'uploads/development/' . $upload_data['file_name'] . '"/>';
            //print_r($upload_data); 
            $document = array(
                'filename' => $upload_data['file_name'],
                'filetype' => $upload_data['file_type'],
                'filesize' => $upload_data['file_size'],
                'filepath' => $upload_data['full_path'],
                'created' => date("Y-m-d H:i:s"),
                'uid' => $user_id
            );
            $photo_insert_id = $this->admindevelopment_model->development_feature_photo_insert($document);

            echo '<input type="hidden" id="development_photo_id" value="' . $photo_insert_id . '" />';
        } else {
            echo 'error in file uploading...';
            print $this->upload->display_errors();
        }
    }

    public function development_delete($development_id) {
        /*log*/
        $job = $this->db->get_where('construction_development',array('id'=>$development_id),1,0)->row()->development_name;
        $this->wbs_helper->log('Job Delete','Deleted job: <b>'.$job.'</b>.');

        $this->admindevelopment_model->development_delete($development_id);

        // redirect to Employee list page
        redirect('admindevelopment/development_list');

    }

    public function set_development_template($did, $tid, $construction_phase = 'tid') {
        $post = $this->input->post();
        $development_tid_update = array(
            $construction_phase => $tid
        );
        switch($construction_phase){
            case 'pre_construction_tid': $construction_phase = 'pre_construction'; break;
            case 'tid': $construction_phase = 'construction'; break;
            case 'post_construction_tid': $construction_phase = 'post_construction'; break;
        }
        $this->admindevelopment_model->development_tid_update($did, $development_tid_update);

        $this->admindevelopment_model->development_template_update($did, $tid, $construction_phase);

        /*log*/
        $job = $this->db->get_where('construction_development',array('id'=>$did),1,0)->row()->development_name;
        $this->wbs_helper->log('Job Edit',"Changed <b>{$construction_phase}</b> template for job: <b>".$job."</b>");
    }

    public function set_tendering_template($did, $tid) {
        $job = $this->db->get_where('construction_development',array('id' => $did, 'wp_company_id'=>$this->wp_company_id),0,1)->row();
        $template = $this->db->get_where('construction_tendering_templates',array('id' => $tid, 'wp_company_id'=>$this->wp_company_id),0,1)->row();
        $this->db->delete('construction_tendering_jobs',array('job_id'=>$job->id));
        $this->db->update('construction_development', array('tendering_template_id'=>$template->id), array('id' => $job->id));

        /*log*/
        $job = $this->db->get_where('construction_development',array('id'=>$did),1,0)->row()->development_name;
        $this->wbs_helper->log('Job Edit',"Changed tendering template for job: <b>".$job."</b>");
    }

    public function set_satge_template($template_id, $stage_no, $development_id) {

        $this->admindevelopment_model->stage_template_update($template_id, $stage_no, $development_id);
    }

    public function stage_phase_add() {

        if ($this->input->post('submit')) {

            $post = $this->input->post();
            $stage_no = $post['stage_no'];
            $template_id = $post['template_id'];
            $development_id = $post['development_id'];
            $url = $post['url'];

            $stage_phase_add = array(
                'template_id' => $template_id,
                'development_id' => $development_id,
                'stage_no' => $stage_no,
                'phase_name' => $post['phase_name'],
                'phase_length' => $post['phase_length'],
                'ordering' => $post['phase_ordering'],
                'planned_start_date' => $this->wbs_helper->to_mysql_date($post['planned_start_date']),
                'planned_finished_date' => $this->wbs_helper->to_mysql_date($post['planned_finished_date']),
                'phase_person_responsible' => $post['phase_person_responsible']
            );

            $phase_id = $this->admindevelopment_model->stage_phase_add($stage_phase_add);

            redirect('admindevelopment/' . $url . '/' . $development_id . '/' . $stage_no . '/' . $phase_id);
        }
    }

    public function stage_phase_update($phase_id) {

        if ($this->input->post('submit')) {

            $post = $this->input->post();
            $development_id = $post['development_id'];
            $stage_no = $post['stage_no'];
            $phase_id = $post['phase_id'];
            $url = $post['url'];

            $stage_phase_update = array(
                'phase_name' => $post['phase_name'],
                'planned_start_date' => $this->wbs_helper->to_mysql_date($post['planned_start_date']),
                'planned_finished_date' => $this->wbs_helper->to_mysql_date($post['planned_finished_date']),
                'phase_person_responsible' => $post['phase_person_responsible']
            );

            $this->admindevelopment_model->stage_phase_update($phase_id, $stage_phase_update);

            redirect('admindevelopment/' . $url . '/' . $development_id . '/' . $stage_no . '/' . $phase_id);
        }
    }

    public function stage_phase_delete($task_id) {

        $post = $this->input->post();
        $development_id = $post['development_id'];
        $stage_no = $post['stage_no'];
        $phase_id = $post['phase_id'];
        $url = $post['url'];

        $this->admindevelopment_model->stage_phase_delete($task_id);
        // redirect to Employee list page
        redirect('admindevelopment/' . $url . '/' . $development_id . '/' . $stage_no . '/' . $phase_id);
    }

    public function stage_task_add() {

        if ($this->input->post('submit')) {

            $post = $this->input->post();
            $template_id = $post['template_id'];
            $development_id = $post['development_id'];
            $stage_no = $post['stage_no'];
            $phase_id = $post['phase_id'];
            $url = $post['url'];

            $stage_task_add = array(
                'development_id' => $development_id,
                'template_id' => $template_id,
                'phase_id' => $phase_id,
                'stage_no' => $post['stage_no'],
                'task_name' => $post['task_name'],
                'task_length' => $post['task_length'],
                'ordering' => $post['task_ordering'],
                'task_start_date' => $this->wbs_helper->to_mysql_date($post['task_start_date'])
            );

            $this->admindevelopment_model->stage_task_add($stage_task_add);

            redirect('admindevelopment/' . $url . '/' . $development_id . '/' . $stage_no . '/' . $phase_id);
        }
    }

    public function stage_task_update($task_id) {

        if ($this->input->post('submit')) {

            $post = $this->input->post();
            $development_id = $post['development_id'];
            $stage_no = $post['stage_no'];
            $phase_id = $post['phase_id'];
            $url = $post['url'];

            $stage_task_update = array(
                'task_name' => $post['task_name'],
                'task_start_date' => $this->wbs_helper->to_mysql_date($post['task_start_date'])
            );

            $this->admindevelopment_model->stage_task_update($task_id, $stage_task_update);

            redirect('admindevelopment/' . $url . '/' . $development_id . '/' . $stage_no . '/' . $phase_id);
        }
    }

    public function stage_task_delete($task_id) {

        $post = $this->input->post();
        $development_id = $post['development_id'];
        $stage_no = $post['stage_no'];
        $phase_id = $post['phase_id'];
        $url = $post['url'];

        $this->admindevelopment_model->stage_task_delete($task_id);
        // redirect to Employee list page
        redirect('admindevelopment/' . $url . '/' . $development_id . '/' . $stage_no . '/' . $phase_id);
    }

    public function development_phase_ordering() {
        $this->admindevelopment_model->development_phase_ordering();
    }

    public function development_task_ordering() {
        $this->admindevelopment_model->development_task_ordering();
    }

    public function stage_phase_ordering() {
        $this->admindevelopment_model->stage_phase_ordering();
    }

    public function stage_task_ordering() {
        $this->admindevelopment_model->stage_task_ordering();
    }

}