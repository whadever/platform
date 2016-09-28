<?php

class Job extends CI_controller {

    private $user_id, $wp_company_id;

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'file', 'html', 'email'));
        $this->load->model('job_model', '', TRUE);
        $this->load->model('developments_model', '', TRUE);
        $this->load->model('admindevelopment_model', '', TRUE);
        $this->load->model('contact_model', '', TRUE);
        $this->load->library(array('table', 'form_validation', 'session', 'Pdf','Wbs_helper'));
        $this->load->helper('email');
        date_default_timezone_set("NZ");

        $redirect_login_page = base_url() . 'user';
        if (!$this->session->userdata('user')) {
            if(in_array($this->uri->segment(2), array('show_popup_menu','change_job'))){
                //echo "<h1>You are not logged in.</h1>"; 
                echo "<script>window.parent.location = '{$redirect_login_page}';</script>";
                exit;
            }
            redirect($redirect_login_page, 'refresh');
        }
        
        $method = $this->router->method;
        $domain = $_SERVER['SERVER_NAME'];
        /*if($method == 'trade_contact_list' || $method == 'checklist'){
            $_SESSION[$domain]['construction_page'] = array('uri' => uri_string(),'jobid_position'=>3);
        }
        if($method == 'consultants_contact_list'){
            $_SESSION[$domain]['pre_construction_page'] = array('uri' => uri_string(),'jobid_position'=>3);
        }*/

        $user = $this->session->userdata('user');
        $this->user_id = $user->uid;
        $this->wp_company_id = $user->company_id;
    }

    function index($id = null) {
        $user = $this->session->userdata('user');
        $data['title'] = 'Job Overview';
        $data['latest_job'] = (is_null($id)) ? $this->job_model->get_latest_job() : $this->job_model->get_job($id);

        $_SESSION[$_SERVER['SERVER_NAME']]['current_job'] = $data['latest_job']->id;

        $data['maincontent'] = $this->load->view('job/job', $data, true);
        $data['current_job'] = $data['latest_job']->id;

        $this->load->view('includes/header', $data);
        $this->load->view('job/job_home', $data);
        $this->load->view('includes/footer', $data);
    }

    function checklist($id = null, $form_id = -1) {
		
		$this->wbs_helper->is_own_job($id);

        if(!is_null($id)){

            $_SESSION[$_SERVER['SERVER_NAME']]['current_job'] = $id;

        }elseif(!$_SESSION[$_SERVER['SERVER_NAME']]['current_job']){

            $_SESSION[$_SERVER['SERVER_NAME']]['current_job'] = $this->job_model->get_latest_job()->id;
        }

        $data['latest_job'] = $this->job_model->get_job($_SESSION[$_SERVER['SERVER_NAME']]['current_job']);

        /* getting stage and list data */
        //$res = $this->job_model->get_list_data($_SESSION[$_SERVER['SERVER_NAME']]['current_job']);
        $data['is_submitted'] = false;

        /*if($this->db->query('select * from construction_check_list_status where construction_phase = "'.$_GET['cp'].'" AND form_id = '.$form_id.' AND job_id = '.$_SESSION[$_SERVER['SERVER_NAME']]['current_job'].' limit 0,1')->row()){
            $data['is_submitted'] = true;
            $sql = "SELECT stage.id stage_id, stage.stage_name, list.id task_id, list.task_name, status.status, status.note
                    FROM construction_check_stage stage JOIN construction_check_list list on stage.id = list.stage_id
                         LEFT JOIN construction_check_list_status status on status.check_list_id = list.id and status.stage_id = stage.id
                    WHERE stage.wp_company_id = {$this->wp_company_id} AND construction_phase = '{$_GET['cp']}' AND status.form_id = {$form_id} AND status.job_id = {$_SESSION[$_SERVER['SERVER_NAME']]['current_job']}
                    ORDER BY stage.id ASC ";
        }else{

           $sql = "SELECT stage.id stage_id, stage.stage_name, list.id task_id, list.task_name
                   FROM construction_check_stage stage JOIN construction_check_list list on stage.id = list.stage_id
                   WHERE stage.wp_company_id = {$this->wp_company_id} AND stage.form_id = {$form_id}
                   ORDER BY stage.id ASC ";
        }*/ // task #4322

        $sql = "SELECT stage.id stage_id, stage.stage_name, list.id task_id, list.task_name
                   FROM construction_check_stage stage JOIN construction_check_list list on stage.id = list.stage_id
                   WHERE stage.wp_company_id = {$this->wp_company_id} AND stage.form_id = {$form_id}
                   ORDER BY stage.id ASC ";


        $res = $this->db->query($sql)->result();

        $stage_info = array();

        foreach ($res as $r) {

            $status = (isset($r->status)) ? $r->status : '';
            $note = (isset($r->note)) ? $r->note : '';

            $stage_info[$r->stage_name][] = array(
                'stage_id' => $r->stage_id,
                'task_id' => $r->task_id,
                'task_name' => $r->task_name,
                'status' => $status,
                'note' => $note
            );
        }

        $data['stage_info'] = $stage_info;
        $data['form_id'] = $form_id;

        //$data['maincontent'] = $this->load->view('job/checklist', $data, true);

        $data['development_details'] = $data['latest_job'];
        $data['title'] = "";
        $data['development_content'] = $this->load->view('job/checklist', $data, true);

        $this->load->view('includes/header', $data);
        //$this->load->view('job/job_home', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function checklist_submit($jid){
		
		$this->wbs_helper->is_own_job($jid);

        $form_info = $this->db->query("select * from construction_checklist_form where id = {$this->input->post('form_id')}")->row();

        /*if($this->db->query("select * from construction_check_list_status WHERE form_id = {$form_info->id} AND job_id = {$jid} AND construction_phase = '{$_GET["cp"]}' limit 0,1")->row()){
            echo json_encode(array(
                'status'=>'error',
                'message'=>'You already submitted checklist for this job.'
            ));
            exit;
        }*/ // task #4322
        $post = $this->input->post();

        /*getting all task id */
        $sql = "SELECT stage.id stage_id, stage.stage_name, list.id task_id, list.task_name
                   FROM construction_check_stage stage JOIN construction_check_list list on stage.id = list.stage_id
                   WHERE stage.wp_company_id = {$this->wp_company_id} AND stage.form_id = {$form_info->id}
                   ORDER BY stage.id ASC ";
        $tasks = $this->db->query($sql)->result();

        foreach($tasks as $task){
            if(!isset($post['task_status_'.$task->task_id]) || !in_array($post['task_status_'.$task->task_id],array(0,1,2)) ){
                echo json_encode(array(
                    'status'=>'error',
                    'message'=>'Please answer all the questions.'
                ));
                exit;
            }
        }

        /*now inserting data */
        $insert_data = array();
        foreach($tasks as $task){
            $insert_data[] = array(
                'check_list_id' => $task->task_id,
                'stage_id' => $task->stage_id,
                'job_id' => $jid,
                'note' => $post['task_note_'.$task->task_id],
                'status' => $post['task_status_'.$task->task_id],
                'form_id' => $form_info->id,
                'construction_phase' => $_GET['cp']
            );
        }
        if($this->db->insert_batch('construction_check_list_status', $insert_data)){
            echo json_encode(array(
                'status'=>'success',
                'message'=>''
            ));
        }else{
            echo json_encode(array(
                'status'=>'error',
                'message'=>'Database error.'
            ));
        }
        /*generating and saving pdf as a document under this job*/

        $sql = "SELECT stage.id stage_id, stage.stage_name, list.id task_id, list.task_name, status.status, status.note
                    FROM construction_check_stage stage JOIN construction_check_list list on stage.id = list.stage_id
                         LEFT JOIN construction_check_list_status status on status.check_list_id = list.id and status.stage_id = stage.id
                    WHERE stage.wp_company_id = {$this->wp_company_id} AND construction_phase = '{$_GET['cp']}' AND status.job_id = {$jid} AND status.form_id = {$form_info->id}
                    ORDER BY status.id DESC ,stage.id ASC LIMIT 0, ".count($insert_data); // task #4322. There can be previous submission of the this form. we are only taking the last submitted values by limiting the results.

        $this->db->select("wp_company.*,wp_file.*");
        $this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
        $this->db->where('wp_company.id', $this->wp_company_id);
        $wpdata = $this->db->get('wp_company')->row();
        $logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;
        $job_info = $this->db->query("select d.*,u.email,u.username from construction_development d LEFT JOIN users u ON d.project_manager = u.uid WHERE d.id = {$jid} limit 0, 1")->row();

        $body = "";
        $stage_info = array();
        $tick = site_url('images/tick.png');
        $res = $this->db->query($sql)->result();
        foreach ($res as $r) {

            $status = (isset($r->status)) ? $r->status : '';
            $note = (isset($r->note)) ? $r->note : '';

            $stage_info[$r->stage_name][] = array(
                'task_name' => $r->task_name,
                'status' => $status,
                'note' => $note
            );
        }
        foreach($stage_info as $stage => $info){
            $body .= '<tr>';
            /*$body .= "<td></td>";
            $body .= "<td></td>";
            $body .= "<td></td>";*/
            $body .= "<td colspan='5'><h3>{$stage}</h3></td>";
            $body .= "</tr>";
            foreach($info as $i){
                $na = "";
                $yes = "";
                $no = "";
                switch($i['status']){
                    case "2": $na = '<img src="'.$tick.'" />'; break;
                    case "0": $no = '<img src="'.$tick.'" />'; break;
                    case "1": $yes = '<img src="'.$tick.'" />'; break;
                }
                $body .= '<tr  class="row">';

                $body .= '<td style="text-align: center">'.$na.'</td>';
                $body .= '<td style="text-align: center">'.$yes.'</td>';;
                $body .= '<td style="text-align: center">'.$no.'</td>';
                $body .= "<td>{$i['task_name']}</td>";
                $body .= "<td>{$i['note']}</td>";

                $body .= "</tr>";
            }
        }
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $title = strtoupper($job_info->development_name.' '.$form_info->name.' CHECKLIST');
        $pdf->SetTitle($title);
        $pdf->SetTopMargin(2);
        $pdf->setPrintHeader(false);
        $pdf->setMargins(8,8);
        $pdf->AddPage();
        $pdf->setFontSize(9);
        /*logo*/
        $pdf->writeHTMLCell(127.5,200,5,10, '<img width="170px" src="'.$logo.'" />');
        /*slogan*/
        $html = <<<EOT
                <h2>{$title}</h2>
                <span style="color: #FF0000">APPEARANCE IS EVERYTHING, MORE SALES = MORE HOMES BUILT.<br>
                          HANDOVER THIS HOME AS IF YOU WERE GOING TO BUY IT</span>
EOT;
        $pdf->writeHTMLCell(0,0,65, 10, $html, 0, 0, false, true, 'R');

        $pdf->Ln();

        /*table*/
        $dt = date('d F, y');
        $html = <<<EOT
            <style>
            .head{
                border:1px solid #EEE; border-collapse: collapse;
                font-weight: bold;
                text-align: center;
            }
            .lbl{
                width: 90 pt;
                display: block;
                font-weight: bold;
            }
            .row td{
                border: 1px solid #EEE;
            }
            </style>
            <br><br><br><br>
            <table style="margin:0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="50%" style="font-size: 10pt"><span class="lbl">Date:</span>  {$dt} <br /><br />
                    <span class="lbl">Address:</span>  {$job_info->development_location} <br />
                    </td>
                    <td width="50%" style="font-size: 10pt"><span class="lbl">Job Number:</span>   {$job_info->job_number} <br /><br />
                    <span class="lbl">Builder:</span>  {$job_info->username} <br />
                    </td>
                </tr>
            </table>
            <table id="tbl" cellpadding="5" style="width: 850 pt">
                    <tr>
                        <td  class="head" width="8%">N/A</td>
                        <td  class="head" width="8%">YES</td>
                        <td  class="head" width="8%">NO</td>
                        <td  class="head" >TRADE</td>
                        <td  class="head" >NOTE</td>
                    </tr>
                    {$body}
            </table>
EOT;

        $pdf->writeHTML($html);

        $file_name = str_replace(' ','_',$form_info->name).'_'.$jid.time().'.pdf';

        $pdf->Output(FCPATH.'uploads/development/documents/'.$file_name, 'F');
		$domain = $_SERVER['SERVER_NAME'];

        if($domain == 'horncastle.wclp.co.nz'){
			$document = array(
	            'development_id' => $jid,
	            'filename' => $file_name,
	            'filetype' => 'application/pdf',
	            'filesize' => filesize(FCPATH.'uploads/documents/'.$file_name),
	            'filepath' => FCPATH.'uploads/documents/'.$file_name,
	            'filename_custom' => "{$form_info->name}: {$job_info->development_name} ",
	            'created' => time(),
	            'uid' => $this->user_id,
	            'notify_user' => $job_info->project_manager,
	            'construction_phase' => $_GET['cp'],
				'target_page'	=> 'health_and_safety'
	        );
		}else{
	        $document = array(
	            'development_id' => $jid,
	            'filename' => $file_name,
	            'filetype' => 'application/pdf',
	            'filesize' => filesize(FCPATH.'uploads/documents/'.$file_name),
	            'filepath' => FCPATH.'uploads/documents/'.$file_name,
	            'filename_custom' => "{$form_info->name}: {$job_info->development_name} ",
	            'created' => time(),
	            'uid' => $this->user_id,
	            'notify_user' => $job_info->project_manager,
	            'construction_phase' => $_GET['cp']
	        );
		}


        $this->developments_model->development_document_insert($document);

        /*sending notification to project manager*/
        if($job_info->email){
            $user = $this->session->userdata('user');
            $user_email = $user->email;
            $headers2 = "From: " . $user_email . "\r\n";
            $headers2 .= "Reply-To: " . $user_email . "\r\n";
            $headers2 .= "MIME-Version: 1.0\r\n";
            $headers2 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $sub = "{$form_info->name} document submission notification";
            $msg = "Hello,<br>A {$form_info->name} document has been added to the system for job: {$job_info->development_name}.";
            mail($job_info->email, $sub, $msg, $headers2);
        }
        /*log*/
        $cp = str_replace('_','-',$_GET['cp']);
        $this->wbs_helper->log('Form submit',"Submitted form <b>{$form_info->name}</b> for {$cp}: <b>{$job_info->development_name}</b>");

        exit;


    }
    function add_job() {
        $data['title'] = "Add job";
        $user = $this->session->userdata('user');
        $user_id = $user->uid;
        $wp_company_id = $user->company_id;

        $this->_set_rules();
        if ($this->form_validation->run() === FALSE) {
            $data['users'] = $this->db->query("select uid, username from users where role != 1")->result();
            $data['templates'] = $this->db->query('SELECT id, template_name FROM construction_template where wp_company_id = '.$wp_company_id)->result_array();
            /*getting tendering templates*/
            $data['tendering_templates'] = $this->db->get_where('construction_tendering_templates',array('wp_company_id'=>$this->wp_company_id))->result();
            $data['maincontent'] = $this->load->view('job/add_job', $data, true);
            //$this->load->view('includes/header',$data);
            $this->load->view('includes/popup_home', $data);
            //$this->load->view('includes/footer',$data);
        } else {

            $job_data = array(
                'wp_company_id' => $wp_company_id,
                'job_number' => $this->input->post('job_number'),
                'bcn_number' => $this->input->post('bcn_number'),
                'settlement_date' => $this->input->post('settlement_date'),
                'unconditional_date' => $this->input->post('unconditional_date'),
                'purchased_unconditional_date' => $this->input->post('purchased_unconditional_date'),
                'purchased_settlement_date' => $this->input->post('purchased_settlement_date'),
                'development_name' => $this->input->post('development_name'),
                'development_location' => $this->input->post('development_location'),
                'development_city' => $this->input->post('development_city'),
                'development_size' => $this->input->post('development_size'),
                'land_zone' => $this->input->post('land_zone'),
                'ground_condition' => $this->input->post('ground_condition'),
                'project_manager' => $this->input->post('project_manager'),
                'draughtsman' => $this->input->post('draughtsman'),
                'engineer' => $this->input->post('engineer'),
                'council' => $this->input->post('council'),
                'tid' => $this->input->post('tid'),
                'pre_construction_tid' => $this->input->post('pre_construction_tid'),
                'post_construction_tid' => $this->input->post('post_construction_tid'),
                'tendering_template_id' => $this->input->post('tendering_template_id'),
                'status' => $this->input->post('status'),
                'created' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            );

            $jid = $this->job_model->job_save($job_data);

            /*creating the phases. task #4532*/
            if($job_data['pre_construction_tid']){
                $development_tid_update = array(
                    'pre_construction_tid' => $job_data['pre_construction_tid']
                );
                $construction_phase = 'pre_construction';

                $this->admindevelopment_model->development_tid_update($jid, $development_tid_update);

                $this->admindevelopment_model->development_template_update($jid, $job_data['pre_construction_tid'], $construction_phase);
            }
            if($job_data['tid']){
                $development_tid_update = array(
                    'tid' => $job_data['tid']
                );
                $construction_phase = 'construction';

                $this->admindevelopment_model->development_tid_update($jid, $development_tid_update);

                $this->admindevelopment_model->development_template_update($jid, $job_data['tid'], $construction_phase);
            }
            if($job_data['post_construction_tid']){
                $development_tid_update = array(
                    'post_construction_tid' => $job_data['post_construction_tid']
                );
                $construction_phase = 'post_construction';

                $this->admindevelopment_model->development_tid_update($jid, $development_tid_update);

                $this->admindevelopment_model->development_template_update($jid, $job_data['post_construction_tid'], $construction_phase);
            }

            /* adding tasks to this job */
            /*
              $tasks = $this->db->query("select * from `construction_check_list` where job_id IS NULL ")->result_array();
              $insert_data = array();
              foreach($tasks as $task){
              $insert_data[] = array(
              'check_list_id' => $task['id'],
              'stage_id' => $task['stage_id'],
              'job_id' => $jid,
              'status' => 0
              );
              }
              $this->db->insert_batch('construction_check_list_status', $insert_data);
             */

            /*creating fixed documents. task #4083*/
            if(in_array($_SERVER['SERVER_NAME'],array('property.wclp.co.nz','xprobuilders.wclp.co.nz'))){
                $documents = array(
                    'Sale and Purchase Agreement',
                    'Geotech',
                    'Initial Plans for Tendering',
                    'Submitted Council Plans',
                    'Consented Council Plans',
                    'Consented Council Supporting Docs',
                    'Consented Council Specification',
                    'Signed Landscaping Plan',
                    'Signed Interior Colors',
                    'Signed Exterior Colors',
					'Property Title',
					'Signed Investor Proposal'
                );
                foreach($documents as $doc){
					if($doc == 'Property Title' or $doc == 'Signed Investor Proposal' ){
						$per = 5;
					}else{
						$per = 0;
					}
                    $document = array(
                        'development_id' => $jid,
                        'filename' => 'blank.jpg',
                        'filetype' => 'image/jpeg',
                        'filesize' => 0,
                        'filepath' => UPLOAD_FILE_PATH_DEVELOPMENT_DOCUMENT.'blank.jpg',
                        'filename_custom' => $doc,
                        'created' => time(),
                        'uid' => $this->user_id,
                        'construction_phase' => 'construction',
						'permitted_user_group' => $per
                    );

                    $this->developments_model->development_document_insert($document);
                }
            }
            //redirect('job/index/'.$id);
            $this->load->view('job/save_success');

            /*log*/
            $job = $this->input->post('development_name');
            $this->wbs_helper->log('Job ADD','Created job: <b>'.$job.'</b>.');
        }
    }

    function add_unit() {
        $data['title'] = "Add Unit Development";
        $user = $this->session->userdata('user');
        $user_id = $user->uid;
        $wp_company_id = $user->company_id;

        $this->_set_rules();
        if ($this->form_validation->run() === FALSE) {
            $data['templates'] = $this->db->query('SELECT id, template_name FROM construction_template where wp_company_id = '.$wp_company_id)->result_array();
            $jobs_query = "SELECT dev.*, construction_template.template_name FROM construction_development dev LEFT JOIN construction_template ON dev.tid = construction_template.id
						   WHERE dev.is_unit = 0 AND dev.parent_unit IS NULL AND dev.wp_company_id = {$wp_company_id}
						   ORDER BY dev.`id` DESC";
            $data['admindevelopments'] = $this->db->query($jobs_query)->result();
            $data['maincontent'] = $this->load->view('job/add_unit', $data, true);
            //$this->load->view('includes/header',$data);
            $this->load->view('includes/popup_home', $data);
            //$this->load->view('includes/footer',$data);
        } else {

            $job_data = array(
                'wp_company_id' => $wp_company_id,
                'job_number' => $this->input->post('job_number'),
                'development_name' => $this->input->post('development_name'),
                'tid' => $this->input->post('tid'),
                'pre_construction_tid' => $this->input->post('pre_construction_tid'),
                'status' => 1,
                'is_unit' => 1,
                'created' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            );

            $jid = $this->job_model->job_save($job_data);

            /* adding tasks to this job */
            /*
              $tasks = $this->db->query("select * from `construction_check_list` where job_id IS NULL ")->result_array();
              $insert_data = array();
              foreach($tasks as $task){
              $insert_data[] = array(
              'check_list_id' => $task['id'],
              'stage_id' => $task['stage_id'],
              'job_id' => $jid,
              'status' => 0
              );
              }
              $this->db->insert_batch('construction_check_list_status', $insert_data);
             */



            $tid = $this->input->post('tid');
            $pre_construction_tid = $this->input->post('pre_construction_tid');

            $development_tid_update = array(
                'tid' => $tid,
                'pre_construction_tid' => $pre_construction_tid
            );

            $this->admindevelopment_model->development_tid_update($jid, $development_tid_update);

            if($tid){

                $this->admindevelopment_model->development_template_update($jid, $tid);
            }

            if($pre_construction_tid){

                $this->admindevelopment_model->development_template_update($jid, $pre_construction_tid, 1);
            }

            //redirect('job/index/'.$id);
            /* related jobs */
            $related_jobs = implode(',', $this->input->post('related_jobs'));
            $this->db->simple_query("update construction_development set parent_unit = {$jid} where id in ({$related_jobs})");
            $this->load->view('job/save_success');

        }
    }

    function edit_job() {
        $data['title'] = "Edit job";
        $user = $this->session->userdata('user');
        $user_id = $user->uid;
        $wp_company_id = $user->company_id;

        $this->_set_rules();
        if ($this->form_validation->run() === FALSE) {
            $data['users'] = $this->db->query("select uid, username from users where role != 1")->result();
            $data['templates'] = $this->db->query('SELECT id, template_name FROM construction_template where wp_company_id = '.$wp_company_id)->result_array();
            $data['tendering_templates'] = $this->db->get_where('construction_tendering_templates',array('wp_company_id'=>$this->wp_company_id))->result();
            $data['jobs'] = $this->db->query('SELECT id, job_number, development_name FROM construction_development where wp_company_id = '.$wp_company_id)->result_array();
            $data['maincontent'] = $this->load->view('job/edit_job', $data, true);
            //$this->load->view('includes/header',$data);
            $this->load->view('includes/popup_home', $data);
            //$this->load->view('includes/footer',$data);
        } else {

            $job_data = array(
                //'job_number' => $this->input->post('job_number'),
                'development_name' => $this->input->post('development_name'),
                'development_location' => $this->input->post('development_location'),
                'development_city' => $this->input->post('development_city'),
                'development_size' => $this->input->post('development_size'),
                'bcn_number' => $this->input->post('bcn_number'),
                'settlement_date' => $this->input->post('settlement_date'),
                'unconditional_date' => $this->input->post('unconditional_date'),
                'purchased_unconditional_date' => $this->input->post('purchased_unconditional_date'),
                'purchased_settlement_date' => $this->input->post('purchased_settlement_date'),
                //'number_of_stages' => $this->input->post('number_of_stages'),
                'land_zone' => $this->input->post('land_zone'),
                'ground_condition' => $this->input->post('ground_condition'),
                'project_manager' => $this->input->post('project_manager'),
                'tid' => $this->input->post('tid'),
                'pre_construction_tid' => $this->input->post('pre_construction_tid'),
                //'civil_manager' => $this->input->post('civil_manager'),
                //'job_location' => $this->input->post('job_location'),
                //'number_of_lots' => $this->input->post('number_of_lots'),
                //'civil_eng' => $this->input->post('civil_eng'),
                //'geo_tech_eng' => $this->input->post('geo_tech_eng'),
                'status' => $this->input->post('status'),
                'created' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            );

            $id = $this->job_model->job_update($this->input->post('id'), $job_data);
            //redirect('job');
            $this->load->view('job/save_success');

            /*log*/
            $job = $this->input->post('development_name');
            $this->wbs_helper->log('Job Edit','Edited job: <b>'.$job.'</b>.');
        }
    }

    function job_view() {

        $data['latest_job'] = $this->job_model->get_latest_job();
        $data['maincontent'] = $this->load->view('job/view_job', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('job/job_home', $data);
        $this->load->view('includes/footer', $data);
    }

    function _set_rules() {
        $this->form_validation->set_rules('job_name', 'Job Name');
    }

    function show_popup_menu() {
        $data['maincontent'] = $this->load->view('includes/popup_menu', array(), true);
        $this->load->view('includes/popup_home', $data);
    }

    function get_job_details($id) {
        if ($id) {
            $data = $this->db->query('SELECT * FROM construction_development where id = ' . $id . ' limit 0,1')->row();
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
        }
    }

    function change_job() {
        $data['jobs'] = $this->job_model->get_job_list();

        $data['maincontent'] = $this->load->view('job/change_job', $data, true);
        $this->load->view('includes/popup_home', $data);
    }

    function add_list() {
        $data['title'] = "Add List";
        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $this->_set_rules();
        if ($this->form_validation->run() === FALSE) {
            $data['maincontent'] = $this->load->view('list/add_list', $data, true);
            //$this->load->view('includes/header',$data);
            $this->load->view('includes/popup_home', $data);
            //$this->load->view('includes/footer',$data);
        } else {

            $job_data = array(
                'job_number' => $this->input->post('job_number'),
                'development_name' => $this->input->post('development_name'),
                'development_location' => $this->input->post('development_location'),
                'development_city' => $this->input->post('development_city'),
                'development_size' => $this->input->post('development_size'),
                //'number_of_stages' => $this->input->post('number_of_stages'),
                'land_zone' => $this->input->post('land_zone'),
                'ground_condition' => $this->input->post('ground_condition'),
                'project_manager' => $this->input->post('project_manager'),
                'tid' => $this->input->post('tid'),
                //'civil_manager' => $this->input->post('civil_manager'),
                //'job_location' => $this->input->post('job_location'),
                //'number_of_lots' => $this->input->post('number_of_lots'),
                //'civil_eng' => $this->input->post('civil_eng'),
                //'geo_tech_eng' => $this->input->post('geo_tech_eng'),
                'created' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            );

            $id = $this->job_model->job_save($job_data);
            //redirect('job');
            $this->load->view('job/save_success');
        }
    }

    function edit_list() {
        $user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;

        $data = array();
        $query = "SELECT id, stage_name FROM `construction_check_stage` where wp_company_id = {$wp_company_id}";
        $data['stages'] = $this->db->query($query)->result_array();
        $data['maincontent'] = $this->load->view('list/edit_list', $data, true);
        //$this->load->view('includes/header',$data);
        $this->load->view('includes/popup_home', $data);
    }

    function add_stage() {
        $name = $this->input->post('name');
        $job_id = $_SESSION['current_job'];
        $user = $this->session->userdata('user');
        $user_id = $user->uid;
        $wp_company_id = $user->company_id;
        $created = date("Y-m-d H:i:s");

        $query = "INSERT INTO `construction_check_stage` ( `wp_company_id` , `stage_name` , `job_id` , `status` , `created_by` , `created` )
				  VALUES ('{$wp_company_id}', '{$name}', '{$job_id}', '0', '{$user_id}', '{$created}')";
        if ($this->db->simple_query($query)) {
            echo $this->db->insert_id();
        } else {
            echo -1;
        }
        exit;
    }

    function update_stage($id) {
        $name = $this->input->post('name');

        $query = "UPDATE `construction_check_stage` SET stage_name = '{$name}' where id = {$id}";
        if ($this->db->simple_query($query)) {
            echo 1;
        } else {
            echo -1;
        }
        exit;
    }

    function add_task() {

        $name = $this->input->post('name');
        $stage_id = $this->input->post('stage');
        $job_id = $_SESSION['current_job'];
        $user = $this->session->userdata('user');
        $user_id = $user->uid;
        $created = date("Y-m-d H:i:s");

        $query = "INSERT INTO `construction_check_list` ( `stage_id` , `job_id` ,  `task_name` , `status` , `created_by` , `created` )
				  VALUES ('{$stage_id}', NULL, '{$name}', '0', '{$user_id}', '{$created}')";

        if ($this->db->simple_query($query)) {
            $task_id = $this->db->insert_id();
            /* now adding this task to every job */
            /*$jobs = $this->db->query('select id from construction_development')->result_array();
            $insert_data = array();
            foreach ($jobs as $job) {
                $insert_data[] = array(
                    'check_list_id' => $task_id,
                    'stage_id' => $stage_id,
                    'job_id' => $job['id'],
                    'status' => 0
                );
            }
            $this->db->insert_batch('construction_check_list_status', $insert_data);*/
            echo $task_id;
        } else {
            echo -1;
        }
        exit;
    }

    function add_task_to_job() {

        $name = $this->input->post('name');
        $stage_id = $this->input->post('stage');
        $job_id = $_SESSION['current_job'];
        $user = $this->session->userdata('user');
        $user_id = $user->uid;
        $created = date("Y-m-d H:i:s");

        $query = "INSERT INTO `construction_check_list` ( `stage_id` , `job_id` ,  `task_name` , `status` , `created_by` , `created` )
				  VALUES ('{$stage_id}', '{$job_id}', '{$name}', '0', '{$user_id}', '{$created}')";

        if ($this->db->simple_query($query)) {
            $task_id = $this->db->insert_id();
            /* now adding this task to the job */
            $insert_data = array(
                'check_list_id' => $task_id,
                'stage_id' => $stage_id,
                'job_id' => $job_id,
                'status' => 0
            );
            $this->db->insert('construction_check_list_status', $insert_data);
            echo json_encode(array('task_id' => $task_id, 'status_id' => $this->db->insert_id()));
        } else {
            echo -1;
        }
        exit;
    }

    function update_task($id) {
        $name = $this->input->post('name');
        $query = "UPDATE `construction_check_list` SET task_name = '{$name}' where id={$id}";
        if ($this->db->simple_query($query)) {
            echo 1;
        } else {
            echo -1;
        }
        exit;
    }

    function update_note($id) {
        $note = $this->input->post('note');
        $query = "UPDATE `construction_check_list_status` SET note = '{$note}' where id={$id}";
        if ($this->db->simple_query($query)) {
            echo 1;
        } else {
            echo -1;
        }
        exit;
    }

    function update_task_status($id) {
        $status = $this->input->post('status');
        $stage_id = $this->input->post('stage_id');
        $job_id = $_SESSION['current_job'];
        $check_query = "SELECT count(*) cnt FROM construction_check_list_status WHERE stage_id = $stage_id AND job_id = $job_id AND check_list_id = " . $id;
        $num = $this->db->query($check_query)->row();
        if ($num->cnt == 0) {
            $query = "INSERT INTO  `construction_check_list_status` (check_list_id, stage_id,job_id,status) VALUES('$id', '$stage_id','$job_id', '$status' ) ";
        } else {
            $query = "UPDATE `construction_check_list_status` SET status = {$status} where stage_id = $stage_id AND job_id = $job_id AND check_list_id = " . $id;
        }
        if ($this->db->simple_query($query)) {
            echo 1;
        } else {
            echo -1;
        }
        exit;
    }

    function delete_task($id) {
        $query = "delete from `construction_check_list`  where id={$id}";
        if ($this->db->simple_query($query)) {
            echo 1;
        } else {
            echo -1;
        }
        exit;
    }

    function load_list($stage_id) {
        $query = "select * from `construction_check_list` where stage_id = " . $stage_id;
        $data['list'] = $this->db->query($query)->result_array();
        $this->load->view('list/list', $data);
    }

    function delete_check_list($id) {
        if ($this->db->simple_query("delete from `construction_check_list_status` where id=$id")) {
            echo 1;
        } else {
            echo -1;
        }
        exit;
    }

    function trade_contact_list($id = null) {
		
		$this->wbs_helper->is_own_job($id);
    	
    	$development = $this->developments_model->get_development_detail($id)->row();
        $data['development_details'] = $development;       
        $data['title'] = $development->development_name;

        $data['latest_job'] = (is_null($id)) ? $this->job_model->get_latest_job() : $this->job_model->get_job($id);
        $_SESSION['current_job']  = (is_null($id)) ? $data['latest_job']->id : $id;
        $job_query = "select task.task_name, contact.contact_first_name, contact.id contact_id, contact.contact_last_name, contact.contact_phone_number, contact.contact_mobile_number, contact.contact_email, company.company_name, company.company_address
                      from construction_development_task task LEFT JOIN contact_contact_list contact ON task.task_person_responsible = contact.id
                           LEFT JOIN contact_company company ON contact.company_id = company.id
                           LEFT JOIN construction_development_phase ON task.phase_id = construction_development_phase.id
                      WHERE task.development_id = {$_SESSION['current_job']} AND construction_development_phase.construction_phase = '{$_GET['cp']}'
                      ORDER BY construction_development_phase.ordering asc, task.ordering asc";
        //$data['contacts'] = $this->contact_model->get_contact_list(0)->result();
        //$data['job_task_list'] = $this->job_model->get_job_task_list($_SESSION['current_job']);
        $data['job_task_list'] = $this->db->query($job_query)->result();
        //$data['job_phase_list'] = $this->job_model->get_job_phase_list($_SESSION['current_job']);
        $data['development_content'] = $this->load->view('job/trade_contact_list', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);

        /*log*/
        $cp = str_replace('_','-',$_GET['cp']);
        $this->wbs_helper->log('Trade contact list',"Visited trade contact list for {$cp}: <b>{$data['latest_job']->development_name}</b>");
    }
    /*just a copy of trade_contact_list*/
    function consultants_contact_list($id = null) {
		
		$this->wbs_helper->is_own_job($id);
    	
    	$development = $this->developments_model->get_development_detail($id)->row();
        $data['development_details'] = $development;       
        $data['title'] = $development->development_name;

        $data['latest_job'] = (is_null($id)) ? $this->job_model->get_latest_job() : $this->job_model->get_job($id);
        $_SESSION['current_job'] = (is_null($id)) ? $data['latest_job']->id : $id;
        $job_query = "select task.task_name, contact.contact_first_name, contact.id contact_id, contact.contact_last_name, contact.contact_phone_number, contact.contact_mobile_number, contact.contact_email, company.company_name, company.company_address
                      from construction_development_task task LEFT JOIN contact_contact_list contact ON task.task_person_responsible = contact.id
                           LEFT JOIN contact_company company ON contact.company_id = company.id
                           LEFT JOIN construction_development_phase ON task.phase_id = construction_development_phase.id
                      WHERE task.development_id = {$_SESSION['current_job']} AND task.is_pre_construction = 1
                      ORDER BY construction_development_phase.ordering asc, task.ordering asc";

        $data['job_task_list'] = $this->db->query($job_query)->result();
        $data['development_content'] = $this->load->view('job/trade_contact_list', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);

        /*log*/
        $this->wbs_helper->log('Consultant contact list','Viewed for job: <b>'.$data['latest_job']->development_name.'</b>.');
    }

    public function save_task_document($did) {

        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $post = $this->input->post();

        $config['upload_path'] = UPLOAD_FILE_PATH_DEVELOPMENT_DOCUMENT;

        $config['allowed_types'] = 'pdf|xls|xlsx|csv';
        //$config['max_size'] = '1000000';
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('upload_document')) {
            $upload_data = $this->upload->data();
            // insert data to file table
            // get latest id from frim table and insert it to loan table
            $document = array(
                'filename' => $upload_data['file_name'],
                'filetype' => $upload_data['file_type'],
                'filesize' => $upload_data['file_size'],
                'filepath' => $upload_data['full_path'],
                'filename_custom' => $post['file_title'],
                'created' => time(),
                'uid' => $user_id
            );

            $id = $this->job_model->file_insert($document);

            $task_id = $post['check_list_id'];
            $update = array(
                'file_id' => $id
            );
            $this->job_model->update_check_list($task_id, $update);
        }
        redirect('job/checklist/' . $did);
    }

    public function check_list_file_delete($fid, $id, $did) {

        $this->job_model->file_delete($fid);

        $update = array(
            'file_id' => 0
        );
        $this->job_model->update_check_list($id, $update);

        redirect('job/checklist/' . $did);
    }
}
