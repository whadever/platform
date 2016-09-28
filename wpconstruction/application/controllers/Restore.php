<?php

class Restore extends CI_Controller
{

    private $user_id = '';
    private $wp_company_id = '';
    private $user_app_role_id = '';

    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model', '', TRUE);
        $this->load->model('job_model', '', TRUE);
        date_default_timezone_set("NZ");
        $this->load->library(array('table', 'form_validation', 'session', 'wbs_helper'));
        $this->load->helper(array('url'));
        $redirect_login_page = base_url() . 'user';
        if (!$this->session->userdata('user')) {
            redirect($redirect_login_page, 'refresh');

        }

        /*getting user's application role*/
        $user = $this->session->userdata('user');
        $sql = "select ua.application_role_id role_id
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                where ua.user_id = {$user->uid} and a.id = 5 limit 0, 1";
        $this->user_app_role_id = $this->db->query($sql)->row()->role_id;
        $this->user_id = $user->uid;
        $this->wp_company_id = $user->company_id;

        /*horncastle will not get the option to restore*/
        if($user->company_id == 34) exit;

    }
    
    public function index()
    {

        if(!$this->input->post('backup_time') && !$this->input->post('job')){

            $data['backup_times'] = $this->db->query('SELECT DISTINCT backup_time FROM construction_development_phase_backup')->result();
        }
        if($this->input->post('backup_time') && !$this->input->post('job')){
            $sql =  " SELECT DISTINCT construction_development.id, construction_development.development_name" .
                    " FROM construction_development_phase_backup JOIN construction_development ON construction_development_phase_backup.development_id = construction_development.id" .
                    " WHERE backup_time = '".$this->input->post('backup_time') ."'".
                    " AND construction_development.wp_company_id = {$this->wp_company_id} ";

            $data['backup_time'] = $this->input->post('backup_time');
            $data['jobs'] = $this->db->query($sql)->result();

            $this->session->set_userdata('backup_time',$this->input->post('backup_time'));
        }
        if($this->input->post('job')){

            /*verify own job*/
            $job = $this->db->get_where('construction_development',array('id' => $this->input->post('job'), 'wp_company_id' => $this->wp_company_id),1,0)->row();
            if(!$job){
                die('not a valid job');
            }
            $this->session->set_userdata('backup_job_id',$job->id);

            /*backups*/
            $this->db->select('phase.construction_phase, phase.id phase_id, phase.phase_name, DATE_FORMAT(phase.planned_start_date, "%d-%m-%Y") phase_start, DATE_FORMAT(phase.planned_finished_date, "%d-%m-%Y") phase_end');
            $phases = $this->db->get_where('construction_development_phase_backup phase',array(
                'phase.development_id' => $this->input->post('job'),
                'phase.backup_time' => $this->input->post('backup_time')
            ))->result();

            $this->db->select('phase.id phase_id, phase.construction_phase, task.id task_id, task.task_name, DATE_FORMAT(task.task_start_date, "%d-%m-%Y") task_start, DATE_FORMAT(task.actual_completion_date, "%d-%m-%Y") task_end');
            $this->db->join('construction_development_phase_backup phase','task.phase_id = phase.development_phase_id');
            $tasks = $this->db->get_where('construction_development_task_backup task',array(
                'task.development_id' => $this->input->post('job'),
                'task.backup_time' => $this->input->post('backup_time'),
                'phase.backup_time' => $this->input->post('backup_time')
            ))->result();

            $data['backups'] = array();

            foreach($phases as $backup){
                if(!$data['backups'][$backup->construction_phase]){
                    $data['backups'][$backup->construction_phase] = array();
                }
                if(!$data['backups'][$backup->construction_phase][$backup->phase_id]){
                    $data['backups'][$backup->construction_phase][$backup->phase_id] = array(
                        'phase_name' => $backup->phase_name." ({$backup->phase_start} - {$backup->phase_end})",
                        'tasks' => array()
                    );
                }
            }

            foreach($tasks as $task){
                $data['backups'][$task->construction_phase][$task->phase_id]['tasks'][$task->task_id] = $task->task_name." ({$task->task_start} - {$task->task_end})";
            }

            $data['job_name'] = $job->development_name;
            $data['backup_time'] = $this->session->userdata('backup_time');

        }

        $data['template_content'] = $this->load->view('job_restore',$data,true);
        $this->load->view('template/template_home',$data);
    }

    public function restore(){

        /*verify own job*/
        $job = $this->db->get_where('construction_development',array('id' => $this->session->userdata('backup_job_id'), 'wp_company_id' => $this->wp_company_id),1,0)->row();
        if(!$job){
            die('not a valid job');
        }

        $post = $this->input->post();

        $phase = array();

        $task = array();

        $restore_date = $this->session->userdata('backup_time');

        $log = array("Restoring data <b>".$restore_date."</b> for job <b>{$job->development_name}</b>");

        foreach($post['d'] as $d){
            if(strpos($d,'phase-') === 0){
                $phase[] = str_replace('phase-','',$d);
            }
            if(strpos($d,'task-') === 0){
                $task[] = str_replace('task-','',$d);
            }
        }
        if(!empty($phase)){
            /*restoring phase*/
            $this->db->where('id in ('.implode(',',$phase).')');
            $ph_rows = $this->db->get('construction_development_phase_backup')->result();
            $ph_id = array();
            $ph_names = array();
            $insert_data = array();
            foreach($ph_rows as $row){
                $ph_id[] = $row->development_phase_id;
                $ph_names[] = $row->development_name;
                $data = array();
                foreach(get_object_vars($row) as $field => $value) {
                    if($field == 'id' || $field == 'backup_time') continue;
                    if($field == 'development_phase_id'){
                        $data['id'] = $value;
                    }else{
                        $data[$field] = $value;
                    }
                }
                $insert_data[] = $data;

                /*log msg*/
                $log[] = "restored phase <b>{$data['phase_name']}</b> in <b>{$data['construction_phase']}</b>.";
            }

            $this->db->where('id in ('.implode(',',$ph_id).')');
            $this->db->delete('construction_development_phase');

            $this->db->insert_batch('construction_development_phase',$insert_data);
        }

        if(!empty($task)){
            /*restoring tasks*/
            $this->db->where('id in ('.implode(',',$task).')');
            $task_rows = $this->db->get('construction_development_task_backup')->result();
            $task_id = array();
            $task_names = array();
            $insert_data = array();
            foreach($task_rows as $row){
                $task_id[] = $row->development_task_id;
                $task_names[] = $row->task_name;
                $data = array();
                foreach(get_object_vars($row) as $field => $value) {
                    if($field == 'id' || $field == 'backup_time') continue;
                    if($field == 'development_task_id'){
                        $data['id'] = $value;
                    }else{
                        $data[$field] = $value;
                    }
                }
                $insert_data[] = $data;

                /*log msg*/
                $log[] = "restored task <b>{$data['task_name']}</b> in phase #{$data['phase_id']}.";
            }

            $this->db->where('id in ('.implode(',',$task_id).')');
            $this->db->delete('construction_development_task');

            $this->db->insert_batch('construction_development_task',$insert_data);
        }

        /*log*/
        $this->load->library('Wbs_helper');
        $msg = implode("<br>",$log);
        $this->wbs_helper->log('Data restore',$msg);


        $res = array('status'=>1);
        echo json_encode($res);
        exit;
    }

}