<?php 
class Form extends CI_Controller {

	private $user_id;
	private $user_app_role;
	private $wp_company_id;
	private $username;

	function __construct() {
		parent::__construct();
		$this->load->model('form_model','',TRUE);
		//$this->load->model('user_model','',TRUE);
		$this->load->library(array('form_validation', 'session'));
		$this->load->library('Wbs_helper');
		$this->load->library('user_agent');
		$this->load->library('pdf');
		$this->load->helper(array('form', 'url'));
        //$this->load->helper('email');

		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		
		}
		$user=  $this->session->userdata('user');
		$this->user_id = $user->uid;
		$this->wp_company_id = $user->company_id;
		$this->username = $user->username;

		$sql = "select LOWER(ar.application_role_name) role
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                where ua.user_id = {$user->uid} and a.id = 8 limit 0, 1";

		$this->user_app_role = ($this->db->query($sql)->row()) ? $this->db->query($sql)->row()->role : '';
		              
	}
        
    public function index(){
		redirect(site_url('form/submit'));
	}

	/*showing the add / edit template form*/
	public function add($id = null){

		//if($this->user_app_role != 'manager') return;
		if($this->user_app_role != 'admin') return; //task #4497

		$data['title'] = 'Create Report';

		$user=  $this->session->userdata('user');
		$data['user']=$user;

		/*getting theme color*/
		$this->db->select("wp_company.*,wp_file.*");
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
		$this->db->where('wp_company.id', $this->wp_company_id);
		$wpdata = $this->db->get('wp_company')->row();

		$data['color_one'] = $wpdata->colour_one;
		$data['color_two'] = $wpdata->colour_two;

		/*getting all form fields in case of updating*/
		if(!is_null($id)){

			$form = $this->form_model->get_form($id);
			if(!$form){
				echo "not a valid form"; exit;
			}

			$data['form_fields'] = $this->form_model->get_form_fields($id, 'manager');
			$data['form'] = $form;

			array_walk($data['form_fields'],function($el){
				if($el->select_options){
					$el->select_options = unserialize($el->select_options);
				}
			});

		}



		$data['maincontent'] = $this->load->view('form_add',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}

	/*creating the form*/
	public function create($id = null){

		//if($this->user_app_role != 'manager') return;
		if($this->user_app_role != 'admin') return; //task #4497

		$messages = array();

		/*validation*/
		if (empty($this->input->post('name')))
		{
			$messages[] = "Report name cannot be blank.";

		}
		$fields = json_decode($this->input->post('fields'));

		if(empty($fields)){

			$messages[] = "Report must have at least one form field.";
		}

		if(!empty($messages)){

			$this->session->set_flashdata('warning-message', implode("<br>",$messages));

			redirect(site_url('form/add'));
		}

		if(is_null($id)){

			$data = array(
				'name' => $this->input->post('name') ,
				'manager_id' => $this->user_id,
				'wp_company_id' => $this->wp_company_id,
				'created'=>date("Y-m-d H:i:s")
			);

			$this->db->insert('rs_forms', $data);

			$form_id = $this->db->insert_id();

		}else{
			/*update*/
			$form = $this->form_model->get_form($id);
			if(!$form){
				return;
			}
			$form_id = $form->id;
			$this->db->where('id', $form_id);
			$this->db->update('rs_forms', array('name' => $this->input->post('name')));
		}
		/*adding / updating / deleting form fields*/
		$field_id_arr = array();
		foreach($fields as $field){

			$data = array(
				'form_id' => $form_id,
				'column' => $field->col,
				'order' => $field->order,
				'type' => $field->type,
				'title' => $field->label,
				'select_options' => (isset($field->options)) ? serialize($field->options) : null,
				'required' => ($field->required == 1) ? 1 : 0

			);

			if(!isset($field->id)){

				$this->db->insert('rs_form_fields', $data);

				$field_id_arr[] = $this->db->insert_id();

			}else{

				$this->db->where('id', $field->id);

				$this->db->update('rs_form_fields', $data);

				$field_id_arr[] = $field->id;
			}
		}

		$this->db->where('form_id',$form_id);
		$this->db->where('id NOT IN ('.implode(',', $field_id_arr).')');
		$this->db->delete('rs_form_fields');

		redirect(site_url('form/staffs/'.$form_id));
	}

	/*the page to assign staffs to a form*/
	public function staffs($form_id = null){

		if(is_null($form_id)) exit;

		//if($this->user_app_role != 'manager') return;
		if($this->user_app_role != 'admin') return; //task #4497

		$form = $this->form_model->get_form($form_id);

		$form_user_id_arr = array();

		$res = $this->db->query("select user_id, frequency, deadline from rs_form_users where form_id = {$form->id}")->result();

		foreach($res as $row){

			$form_user_id_arr[] = $row->user_id;
		}
		$data['deadline'] = (isset($row))? $row->deadline : null;
		$data['title'] = 'Add Staffs';

		$user=  $this->session->userdata('user');
		$data['user']=$user;
		$data['wp_company_id'] = $this->wp_company_id;

		$data['form'] = $form;
		$data['form_users'] = $form_user_id_arr;
		$data['notify_managers'] = explode(',',$form->managers_to_notify);
		$data['frequency'] = (isset($row)) ? $row->frequency : '';
		$data['staffs'] = $this->form_model->get_all_staffs();
		$data['managers'] = $this->form_model->get_all_managers();
		/*now a form can be assigned to both staffs and managers */
		$data['staffs'] = array_merge($data['staffs'], $data['managers']);
		$data['maincontent'] = $this->load->view('form_users',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);

	}

	/*assign staffs to a form*/
	public function staff_add(){

		//if($this->user_app_role != 'manager') return;
		if($this->user_app_role != 'admin') return; // task #4497

		$form = $this->form_model->get_form($this->input->post('form_id'));

		if(!$form){
			echo "not a valid form."; exit;
		}

		// if(empty($this->input->post('staffs'))){
		// 	echo "no staff selected."; exit;
		// }

		/*validating the deadline*/
		$time_format = '/^(0[1-9]|1[012]){1}:00 (AM|PM)$/';
		switch($this->input->post('frequency')){
			case 'daily':
				if(!preg_match($time_format, $this->input->post('deadline-daily-time'))){
					echo "not a valid time"; exit;
				}
				$deadline = $this->input->post('deadline-daily-time');
				break;
			case 'weekly':
				if(!preg_match($time_format, $this->input->post('deadline-weekly-time'))){
					echo "not a valid time"; exit;
				}
				$day = (int)$this->input->post('deadline-weekly-day');
				if($day > 6 || $day < 0){
					echo "not a valid day"; exit;
				}
				$deadline = $day." ".$this->input->post('deadline-weekly-time'); break;
			case 'fortnightly':
				if(!preg_match($time_format, $this->input->post('deadline-fortnightly-time1')) || !preg_match($time_format, $this->input->post('deadline-fortnightly-time2'))){
					echo "not a valid time"; exit;
				}
				$day1 = (int)$this->input->post('deadline-fortnightly-day1');
				$day2 = (int)$this->input->post('deadline-fortnightly-day2');
				if($day1 > 31 || $day1 < 1 || $day2 > 31 || $day2 < 1 || $day1 > $day2){
					echo "not a valid day"; exit;
				}
				$deadline = $day1." ".$this->input->post('deadline-fortnightly-time1').','.
							$day2." ".$this->input->post('deadline-fortnightly-time2');
				break;
			case 'monthly':
				if(!preg_match($time_format, $this->input->post('deadline-monthly-time'))){
					echo "not a valid time"; exit;
				}
				$day = (int)$this->input->post('deadline-monthly-day');
				if($day > 31 || $day < 0){
					echo "not a valid day"; exit;
				}
				$deadline = $day." ".$this->input->post('deadline-monthly-time'); break;
			case 'yearly':
				if(!preg_match($time_format, $this->input->post('deadline-yearly-time'))){
					echo "not a valid time"; exit;
				}
				$day = (int)$this->input->post('deadline-yearly-day');
				if($day > 31 || $day < 0){
					echo "not a valid day"; exit;
				}
				$month = (int)$this->input->post('deadline-yearly-month');
				if($month > 12 || $month < 1){
					echo "not a valid month"; exit;
				}
				$deadline = $month." ".$day." ".$this->input->post('deadline-yearly-time'); break;
			default:
				echo "not a valid frequency."; exit;

		}
		$staffs_id = implode(",", $this->input->post('staffs'));

		$managers_to_notify = is_array($this->input->post('managers_to_notify'))? implode(",", $this->input->post('managers_to_notify')):null;


		if($staffs_id !=''){


		/*getting uid of all staffs being added*/
		$this->db->where('company_id',$this->wp_company_id);
		$this->db->where('uid IN ('.$staffs_id.')');
		$staffs = $this->db->get('users')->result();

		/*deleting the staffs which are removed*/
		$this->db->where('form_id',$form->id);
		$this->db->where('user_id NOT IN ('.$staffs_id.')');
		$this->db->delete('rs_form_users');
		}
		else{


            $staffs = null;
            $this->db->where('form_id',$form->id);
			$this->db->delete('rs_form_users');


		}
		

		

		

		/*get all existing staffs for this form*/
		$this->db->where('form_id',$form->id);
		$existing_staffs = array();
		$res = $this->db->get('rs_form_users')->result();
		foreach($res as $r){
			$existing_staffs[] = $r->user_id;
		}

		$data = array();
		foreach($staffs as $staff){
			/*will not add a staff if already assigned*/
			if(in_array($staff->uid,$existing_staffs)) continue;
			$data[] = array(
				'form_id' => $form->id,
				'user_id' => $staff->uid,
				'frequency' => $this->input->post('frequency'),
				'deadline' => $deadline
			);
		}

		if(!empty($data)){

			$this->db->insert_batch('rs_form_users', $data);
		}

		/*updating settings for existing users*/
		foreach($existing_staffs as $es_id){
			$this->db->update('rs_form_users', array(
				'frequency' => $this->input->post('frequency'),
				'deadline' => $deadline
			), array('form_id' => $form->id, 'user_id' => $es_id));
		}


		/*adding managers to notify in the form*/
		$this->db->where('id', $form->id);
		$this->db->update('rs_forms',array('managers_to_notify' => $managers_to_notify));

		/*if the form is new we will add an entry in the submission periods table*/
		$sql = "select * from rs_submission_periods where form_id = {$form->id} limit 0, 1";
		$res = $this->db->query($sql);
		if ($res->num_rows() == 0){
			$dt = new DateTime();
			switch ($this->input->post('frequency')){
				case 'daily':
					$dt->add(new DateInterval('P1D'));
					$this->db->insert('rs_submission_periods',array(
						'form_id' => $form->id,
						'from' => $dt->format('Y-m-d'),
						'to' => $dt->format('Y-m-d')
					));
					break;
				case 'weekly':
					$week_days = array(
						'Sunday',
						'Monday',
						'Tuesday',
						'Wednesday',
						'Thursday',
						'Friday',
						'Saturday',
					);
					if($dt->format('w') == $day){
						$dt->add(new DateInterval('P1D'));
					}
					$this->db->insert('rs_submission_periods',array(
						'form_id' => $form->id,
						'from' => $dt->format('Y-m-d'),
						'to' => $dt->setTimestamp(strtotime("next {$week_days[$day]}"))->format('Y-m-d')
					));
					break;
				case 'fortnightly':
					$num_days_this_month = cal_days_in_month(CAL_GREGORIAN, $dt->format('n'), $dt->format('y'));
					if($day2 > $num_days_this_month){
						$day2 = $num_days_this_month;
					}
					if($dt->format('j')==$day1 || $dt->format('j')==$day2){
						$dt->add(new DateInterval('P1D'));
					}
					if($dt->format('j') > $day1 && $dt->format('j') <= $day2){
						$this->db->insert('rs_submission_periods',array(
							'form_id' => $form->id,
							'from' => $dt->format('Y-m-d'),
							'to' => $dt->add(new DateInterval("P".($day2-$dt->format('j'))."D"))->format('Y-m-d')
						));
					}elseif($dt->format('j') > $day2) {
						$this->db->insert('rs_submission_periods',array(
							'form_id' => $form->id,
							'from' => $dt->setTimestamp(strtotime('first day of next month'))->format('Y-m-d'),
							'to' => $dt->add(new DateInterval("P".($day1-1)."D"))->format('Y-m-d')
						));
					}else{
						$this->db->insert('rs_submission_periods',array(
							'form_id' => $form->id,
							'from' => $dt->format('Y-m-d'),
							'to' => $dt->add(new DateInterval("P".($day1-$dt->format('j'))."D"))->format('Y-m-d')
						));
					}
					break;
				case 'monthly':

					if($dt->format('j') == $day){
						$dt->add(new DateInterval('P1D'));
					}

					$num_days_in_month = cal_days_in_month(CAL_GREGORIAN, $dt->format('n'), $dt->format('y'));
					if($day > $num_days_in_month){
						$day2 = $num_days_in_month;
					}else{
						$day2 = $day;
					}
					$deadline_date = date_create_from_format('Y-n-j',$dt->format('Y')."-{$dt->format('n')}-{$day2}");
					if($dt->getTimestamp() > $deadline_date->getTimestamp()){
						/*deadline will be in the next month*/
						$deadline_date->add(new DateInterval("P1M"));
					}
					/*adjusting the day if it is greater than the maximum date of the month*/
					$num_days_in_month = cal_days_in_month(CAL_GREGORIAN, $deadline_date->format('n'), $deadline_date->format('y'));
					if($day > $num_days_in_month){
						$day2 = $num_days_in_month;
					}else{
						$day2 = $day;
					}
					$deadline_date = date_create_from_format('Y-n-j',$deadline_date->format('Y')."-{$deadline_date->format('n')}-{$day2}");
					$this->db->insert('rs_submission_periods',array(
						'form_id' => $form->id,
						'from' => $dt->format('Y-m-d'),
						'to' => $deadline_date->format('Y-m-d')
					));
					break;
				case 'yearly':
					if($dt->format('j') == $day && $dt->format('n') == $month ){
						$dt->add(new DateInterval('P1D'));
					}
					$num_days_this_month = cal_days_in_month(CAL_GREGORIAN, $month, $dt->format('y'));
					if($day > $num_days_this_month){
						$day2 = $num_days_this_month;
					}else{
						$day2 = $day;
					}
					$deadline_date = date_create_from_format('Y-n-j',$dt->format('Y')."-{$month}-{$day2}");
					if($dt->getTimestamp() >= $deadline_date->getTimestamp()){
						/*deadline will be in the next year*/
						$deadline_date->add(new DateInterval("P1Y"));
					}
					/*adjusting the day if it is greater than the maximum date of the month*/
					$num_days_in_month = cal_days_in_month(CAL_GREGORIAN, $deadline_date->format('n'), $deadline_date->format('y'));
					if($day > $num_days_in_month){
						$day2 = $num_days_in_month;
					}else{
						$day2 = $day;
					}
					$deadline_date = date_create_from_format('Y-n-j',$deadline_date->format('Y')."-{$deadline_date->format('n')}-{$day2}");
					$this->db->insert('rs_submission_periods',array(
						'form_id' => $form->id,
						'from' => $dt->format('Y-m-d'),
						'to' => $deadline_date->format('Y-m-d')
					));
					break;

			}
		}

		$this->session->set_flashdata('success-message', "<b>{$form->name}</b> saved successfully.");
		redirect(site_url('form/show_list'));


	}

	/*showing list of forms to manager to edit*/
	public function show_list()
	{
		//if($this->user_app_role != 'manager') return;
		if($this->user_app_role != 'admin') return; //task #4497

		//$forms = $this->form_model->get_manager_forms();
		/*now all managers can edit all forms*/
		$this->db->where('wp_company_id',$this->wp_company_id);
		$this->db->where('active',1);
		$data['forms'] = $this->db->get('rs_forms')->result();

		$data['form_staffs'] = array();

		$data['form_creators'] = array();

		/*getting assigned staffs for all forms and the creators*/
		foreach($data['forms'] as $form){
			$data['form_staffs'][$form->id] = array();
			$this->db->select('uid, username');
			$this->db->join('rs_form_users','users.uid = rs_form_users.user_id');
			$this->db->where('rs_form_users.form_id',$form->id);
			$staffs = $this->db->get('users')->result();
			foreach($staffs as $staff){
				$data['form_staffs'][$form->id][] = $staff->username;
				//task #4452
				$data['form_staffs_with_id'][$form->id][] = array('username'=>$staff->username, 'uid'=>$staff->uid);
			}

			$this->db->where('uid', $form->manager_id);
			$data['form_creators'][$form->id] = $this->db->get('users')->row()->username;
		}

		$data['title'] = "Forms";

		$data['wp_company_id'] = $this->wp_company_id;

		/*getting all uncompleted reports. task #4617*/
		$query = "SELECT p.*, users.uid user_id, users.username, form.id form_id, form.name form_name ".
				" FROM rs_submission_periods p JOIN rs_forms form ON p.form_id = form.id ".
				" JOIN rs_form_users ON rs_form_users.form_id = form.id ".
				" JOIN users ON users.uid = rs_form_users.user_id".
				"	WHERE p.id NOT IN (SELECT submission_period_id FROM rs_submits WHERE form_id = form.id AND user_id = users.uid)".
				"	AND (SELECT COUNT(*) FROM rs_stopped_reports WHERE user_id = users.uid AND form_id = form.id AND from_date <= p.from AND to_date >= p.to) = 0 ".
				"	AND form.wp_company_id = {$this->wp_company_id}" .
				"	ORDER BY p.id desc";

		$data['unsubmitted_reports'] = $this->db->query($query)->result();

		$data['maincontent'] = $this->load->view('form_list',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}

	/*submitting a form by a staff*/
	public function submit($form_id = null, $submission_period_id = null)
	{

		$forms = $this->form_model->get_staff_forms();

		$data['forms'] = $forms;


		/*getting theme color*/
		$this->db->select("wp_company.*,wp_file.*");
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
		$this->db->where('wp_company.id', $this->wp_company_id);
		$wpdata = $this->db->get('wp_company')->row();

		$data['color_one'] = $wpdata->colour_one;
		$data['color_two'] = $wpdata->colour_two;

		if(is_null($form_id)){
			/*showing the list of forms*/

			$data['title'] = "Submit Report";

			$data['maincontent'] = $this->load->view('user_form_list',$data,true);
		}else{

			$form = $this->form_model->get_form($form_id, 'staff');

			if(!$form){
				echo "not a valid form."; exit;
			}

			/*checking the number of pending reports. cannot submit if there is no pending report */
			$overdue = $this->db->query("select overdue from rs_form_users where form_id = {$form->id} and user_id = {$this->user_id}")->row();
			if($overdue->overdue == 0){
				$this->session->set_flashdata('warning-message', "Your don't have any overdue for report: <b>{$form->name}</b>.");
				redirect(site_url('form/submit'));
			}

			if(is_null($submission_period_id)){
				$this->db->order_by("id","desc");
				$submission_period_id = $this->db->get_where('rs_submission_periods',array('form_id'=>$form_id),1,0)->row()->id;
			}

			$submission_period = $this->db->get_where('rs_submission_periods',array('id'=>$submission_period_id, 'form_id'=>$form_id),1,0)->row();

			/*this submission period not for this form*/
			if(!$submission_period){
				$this->session->set_flashdata('warning-message', "Invalid report.");
				redirect(site_url('form/submit'));
			}

			$submits = $this->db->get_where('rs_submits',array(
				'form_id'=>$form_id,
				'user_id'=>$this->user_id,
				'submission_period_id'=>$submission_period_id),1,0)->row();

			if($submits){
				$this->session->set_flashdata('warning-message', "You already submitted this report.");
				redirect(site_url('form/submit'));
			}

			if(!$this->input->post('submit')){

				/*showing the form*/

				$data['title'] = $form->name;

				$data['form_fields'] = $this->form_model->get_form_fields($form->id, 'staff');

				$data['form'] = $form;

				$data['submission_period'] = $submission_period;

				$data['user_role'] = $this->user_app_role;

				array_walk($data['form_fields'],function($el){
					if($el->select_options){
						$el->select_options = unserialize($el->select_options);
					}
				});

				$data['maincontent'] = $this->load->view('form_display',$data,true);
			}else{

				/*the period for which the report is submitted*/
				$sql =  "select * from rs_submission_periods ".
						"WHERE id = {submission_period_id} AND form_id = {$form->id} " .
						"LIMIT 0, 1";

				/*updating the overdue counter*/
				$sql = "update rs_form_users set overdue = overdue - 1 where form_id = {$form->id} and user_id = {$this->user_id}";
				$this->db->simple_query($sql);

				/*submitting form*/
				$submit_date = date('Y-m-d H:i:s');
				$this->db->insert('rs_submits',array(
					'form_id' => $form->id,
					'user_id' => $this->user_id,
					'manager_id' => $form->manager_id,
					'date' => $submit_date,
					'submission_period_id' => $submission_period_id
				));
				$submit_id =  $this->db->insert_id();

				/*initializing file upload (for document type fields)*/
				$config['upload_path'] = FCPATH.'documents';

				$config['allowed_types'] = '*';
				$config['max_size'] = '100000KB';
				$config['overwrite'] = TRUE;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				/*inserting field values*/
				$form_fields = $this->form_model->get_form_fields($form->id, 'staff');
				$data = array();
				$error_message = "";
				foreach($form_fields as $field){
					if($field->required && $field->type != 'document' && $this->input->post('field_'.$field->id) == ''){
						$error_message = "Some required fields are empty.";
						$this->session->set_flashdata('warning-message', $error_message);
						/*restoring the overdue counter and deleting the form submission*/
						$sql = "update rs_form_users set overdue = overdue + 1 where form_id = {$form->id} and user_id = {$this->user_id}";
						$this->db->simple_query($sql);
						$this->db->delete('rs_submits', array('id' => $submit_id));
						redirect(site_url('form/submit/'.$form->id."/".$submission_period_id));
					}
					/*checking for document type field*/
					$fid = null;
					if($field->type == 'document'){
						if($field->required && $_FILES['field_'.$field->id]['size'] == 0){
							$error_message = "Some required fields are empty.";
							$this->session->set_flashdata('warning-message', $error_message);
							/*restoring the overdue counter and deleting the form submission*/
							$sql = "update rs_form_users set overdue = overdue + 1 where form_id = {$form->id} and user_id = {$this->user_id}";
							$this->db->simple_query($sql);
							$this->db->delete('rs_submits', array('id' => $submit_id));
							redirect(site_url('form/submit/'.$form->id));
						}
						/*uploading the file (for document type field)*/
						if ($this->upload->do_upload('field_'.$field->id)) {
							$upload_data = $this->upload->data();
							$document = array(
								'wp_company_id' => $this->wp_company_id,
								'filename' => $upload_data['file_name'],
								'filetype' => $upload_data['file_type'],
								'filesize' => $upload_data['file_size'],
								'filepath' => $upload_data['full_path'],
								'filename_custom' => $upload_data['file_name'],
								'created' => time(),
								'uid' => $this->user_id,
							);
							$this->db->insert('file',$document);
							$fid = $this->db->insert_id();
						}

					}
					$data[] = array(
						'submit_id' => $submit_id,
						'user_id' => $this->user_id,
						'form_id' => $form->id,
						'field_id' => $field->id,
						'field_label' => $field->title,
						/*saving the fid in case of document type field*/
						'field_value' => ($fid) ? $fid : nl2br($this->input->post('field_'.$field->id))
					);
				}

				if($this->db->insert_batch('rs_submit_values', $data)){

					/*saving the report pdf*/
					$this->_save_report_pdf($submit_id);

					/*sending mail to manager*/
					$recipients = array();
					$res = $this->db->query("select * from users where uid = {$form->manager_id} or uid = {$this->user_id} limit 0,2")->result();
					foreach($res as $r){
						//task #4116 - the creator manager of the form will not get mail by default
						//if($r->uid == $form->manager_id){
							/*task #4091*/
						//	$recipients[] = array(
						//		'name' => $r->username, 'email' => $r->email
						//	);
						//}
						if($r->uid == $this->user_id){
							$user_name = $r->username;
							/*task #4095*/
							$user_email = $r->email;
						}
					}
					/*getting managers to notify*/
					$managers_to_notify = array();
					if($form->managers_to_notify){
						$this->db->select('username, email');
						$this->db->where("uid in ({$form->managers_to_notify})");
						$this->db->where("company_id",$form->wp_company_id);
						$rows = $this->db->get('users')->result();
						foreach($rows as $r){
							//$managers_to_notify[] = $r->email;
							/*task #4091*/
							$recipients[] = array(
								'name' => $r->username, 'email' => $r->email
							);
						}

					}
					$config['protocol'] = 'smtp';
					$config['smtp_host'] = 'mail.wclp.co.nz';
					$config['smtp_port'] = '2525';
					$config['smtp_user'] = 'reporting_system@wclp.co.nz';
					$config['smtp_pass'] = 'Reporting1';
					$config['mailtype'] = 'html';
					$config['charset'] = 'iso-8859-1';
					$config['wordwrap'] = TRUE;
					$config['newline'] = "\r\n";

					$this->load->library('email',$config);
					$this->email->set_mailtype("html");

					/*$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$msg = "Hi {$to_name},<br> {$user_name} has submitted report: <b>{$form->name}</b>.<br>Thank You.";
					mail($to, "Reporting system: report submission notification", $msg, $headers);*/

					$subject = "Reporting system: report submission notification";
					$message =  "Hi #to_name#,<br><br>".
						        "<b>{$user_name}</b> has submitted <b>{$form->name}</b> on ".$submit_date."<br><br>".
								"<a href='".site_url('form/report/'.$submit_id)."'>Click this link</a> to see the report.<br><br>".
								"Thank You.";
					$file = FCPATH.'reports/'.$user_name."-".$form->name."-".str_replace(':','_',$submit_date).".pdf";

					/*if(!empty($managers_to_notify)){
						$this->email->cc($managers_to_notify);
					}*/ // task #4091

					$this->email->attach($file);
					// task #4091
					foreach($recipients as $recipient){
						$this->email->clear();
						$this->email->to($recipient['email']);
						//$this->email->from('reporting_system@wclp.co.nz', 'Reporting System');
						$this->email->from($user_email, $user_name); // task #4095
						$this->email->subject($subject);
						$this->email->message(str_replace('#to_name#',$recipient['name'],$message));
						$this->email->send();
					}

					$this->session->set_flashdata('success-message', "Report <b>{$form->name}</b> submitted successfully.");
					redirect(site_url('form/submit'));
				}
			}
		}

		$data['wp_company_id'] = $this->wp_company_id;
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}

	/*viewing the submitted reports*/
	public function view(){

		$data['title'] = "View Reports";
		/*getting theme color*/
		$this->db->select("wp_company.*,wp_file.*");
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
		$this->db->where('wp_company.id', $this->wp_company_id);
		$wpdata = $this->db->get('wp_company')->row();

		$data['color_one'] = $wpdata->colour_one;
		$data['color_two'] = $wpdata->colour_two;

		if(!$this->input->post('submit')){

			if($this->user_app_role == 'manager' || $this->user_app_role == 'admin'){
				//$data['forms'] = $this->form_model->get_manager_forms();
				/*for a later requirement all managers will be able to see all submitted forms*/
				$this->db->where('wp_company_id',$this->wp_company_id);
				$data['forms'] = $this->db->get('rs_forms')->result();

				//$data['staffs'] = $this->form_model->get_all_staffs();
				/*getting staffs for each forms*/
				$arr_staff_forms = array();
				foreach($data['forms'] as $f){
					$arr_staff_forms[$f->id] = array(
						array('user_id'=>'all', 'user_name' => 'all')
					);
					$rs = $this->db->query("select users.uid, users.username from users, rs_form_users where users.uid = rs_form_users.user_id AND form_id = {$f->id}")->result();
					foreach($rs as $r){
						$arr_staff_forms[$f->id][] = array(
							'user_id' => $r->uid, 'user_name' => $r->username
						);
					}

				}
				$data['staffs'] = json_encode($arr_staff_forms);
			}else{
				$data['forms'] = $this->form_model->get_staff_forms();
			}

			$data['maincontent'] = $this->load->view('view_home',$data,true);

		}else{
			if(!$this->input->post('form')){
				$this->session->set_flashdata('warning-message', "You must select a report.");
				redirect(site_url('form/view'));
			}
			if(($this->user_app_role == 'manager' || $this->user_app_role == 'admin') && !$this->input->post('staff')){
				$this->session->set_flashdata('warning-message', "You must select a staff.");
				redirect(site_url('form/view'));
			}
			//$this->db->select("rs_submits.id, rs_submits.date, rs_submits.form_id, rs_submission_periods.from, rs_submission_periods.to, rs_form_users.frequency, rs_form_users.deadline, rs_forms.name, username");
			$this->db->select("rs_submits.id, rs_submits.date, rs_submits.form_id, rs_submission_periods.from, rs_submission_periods.to, rs_forms.name, username");
			$this->db->join("rs_forms","rs_submits.form_id = rs_forms.id");
			$this->db->join("users","rs_submits.user_id = users.uid","left"); //user may have been deleted from the system. that's why left join
			//$this->db->join("rs_form_users","rs_form_users.user_id = rs_submits.user_id AND rs_form_users.form_id = rs_submits.form_id ");
			$this->db->join("rs_submission_periods","rs_submission_periods.id = rs_submits.submission_period_id");
			$this->db->order_by("rs_submits.date", "desc");
			$this->db->where("rs_submits.form_id", $this->input->post('form'));
			$this->db->where("rs_submits.marked_as_complete_by_admin = 0"); //task #4617
			// task #4186
			if($this->input->post('staff') != 'all'){

				if($this->user_app_role == 'manager' || $this->user_app_role == 'admin'){
					$this->db->where(array(
						'rs_submits.user_id' => $this->input->post('staff')/*,
					'rs_forms.manager_id' => $this->user_id*/ //because now all managers will see all submitted reports
					));
					$stf = $this->db->query("select username from users where uid = {$this->input->post('staff')} limit 0, 1")->row();
					$data['staff_name'] = $stf->username;
				}else{
					$this->db->where(array(
						'rs_submits.user_id' => $this->user_id
					));
				}
			}else{
				$data['staff_name'] = "All Staffs";
			}
			$data['reports'] = $this->db->get('rs_submits')->result();
			//$data['late_submissions'] = $this->_get_late_submissions_list($data['reports']);

			$data['maincontent'] = $this->load->view('report_list',$data,true);
		}

		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}

	/*display the report pdf*/
	public function report($submit_id = null){

		if(is_null($submit_id)) exit;

		$this->db->select('rs_submits.date, rs_forms.name, users.username');
		$this->db->join('rs_submits', 'rs_submits.id = rs_submit_values.submit_id');
		$this->db->join('rs_forms', 'rs_submits.form_id = rs_forms.id');
		$this->db->join('users', 'rs_submits.user_id = users.uid', 'left'); //user may have been deleted from the system. that's why left join
		$this->db->where('rs_submits.id',$submit_id);

		if($this->user_app_role == 'manager' || $this->user_app_role == 'admin'){
			//$this->db->where('rs_submits.manager_id', $this->user_id);
		}else{
			$this->db->where('rs_submits.user_id', $this->user_id);
		}

		$values = $this->db->get('rs_submit_values')->row();

		if(!$values) exit;

		if($values->username){
			$file = FCPATH.'reports/'.$values->username."-".$values->name."-".str_replace(':','_',$values->date).".pdf";
			$filename = $values->username."-".$values->name."-".str_replace(':','_',$values->date).".pdf";
		}else{
			$file = glob(FCPATH."reports/*-".$values->name."-".str_replace(':','_',$values->date).".pdf")[0];
			$filename = basename($file);
		}

		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="' . $filename . '"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($file));
		header('Accept-Ranges: bytes');

		@readfile($file);

	}

	/*display uploaded file for document type field. task #4057*/
	public function document($fid = null){

		if(is_null($fid)) exit;

		if($this->user_app_role != 'manager' && $this->user_app_role != 'admin'){
			echo "Don't have access."; exit;
		}

		$f = $this->db->get_where('file',array('fid' => $fid, 'wp_company_id' => $this->wp_company_id), 0, 1)->row();

		$file = FCPATH.'documents/'.$f->filename;

		header('Content-type: '.$f->filetype);
		header('Content-Disposition: inline; filename="' . $f->filename . '"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($file));
		header('Accept-Ranges: bytes');

		@readfile($file);

	}

	public function duplicate($fid){
		if($this->user_app_role != 'manager' && $this->user_app_role != 'admin') return;

		$this->db->where(array('wp_company_id'=>$this->wp_company_id, 'id'=>$fid));
		$form = $this->db->get('rs_forms')->row();
		if($form){
			$data = array(
				'name' => $form->name." (copy)" ,
				'manager_id' => $this->user_id,
				'wp_company_id' => $this->wp_company_id,
				'created'=>date("Y-m-d H:i:s")
			);

			$this->db->insert('rs_forms', $data);

			$form_id = $this->db->insert_id();

			/*now copying the fields*/
			$this->db->where('form_id',$form->id);
			$fields = $this->db->get('rs_form_fields')->result();
			$data = array();
			foreach($fields as $field){
				$data[] = array(
					'form_id' => $form_id,
					'column'=> $field->column,
					'order' => $field->order,
					'type' => $field->type,
					'title' => $field->title,
					'select_options'=> $field->select_options,
					'required' => $field->required
				);
			}
			if($this->db->insert_batch('rs_form_fields', $data)){
				redirect(site_url('form/add/'.$form_id));
			}

		}else{
			echo "not a valid form."; exit;
		}
	}
	public function delete($fid){
		if($this->user_app_role != 'manager' && $this->user_app_role != 'admin') return;

		$this->db->where(array('wp_company_id'=>$this->wp_company_id, 'id'=>$fid));
		$form = $this->db->get('rs_forms')->row();
		if($form){
			$this->db->where('id',$form->id);
			//task #4186
			//$this->db->delete('rs_forms');
			$this->db->update('rs_forms',array('active'=>0));

			/*$this->db->where('form_id',$form->id);
			$this->db->delete('rs_form_fields');*/

			$this->db->where('form_id',$form->id);
			$this->db->delete('rs_form_users');

			$this->session->set_flashdata('success-message', "<b>{$form->name}</b> deleted successfully.");
			redirect(site_url('form/show_list'));


		}else{
			echo "not a valid form."; exit;
		}
	}
	/*saving report pdf*/
	private function _save_report_pdf($submit_id = null){

		if(is_null($submit_id)) exit;

		$this->db->select('rs_submit_values.*,rs_submits.date, rs_forms.name, users.username, rs_form_fields.column, rs_form_fields.order, rs_form_fields.type, rs_submission_periods.from from_period, rs_submission_periods.to to_period');
		$this->db->join('rs_submits', 'rs_submits.id = rs_submit_values.submit_id');
		$this->db->join('rs_forms', 'rs_submits.form_id = rs_forms.id');
		$this->db->join('users', 'rs_submits.user_id = users.uid');
		$this->db->join('rs_form_fields', 'rs_submit_values.field_id = rs_form_fields.id');
		//task #4218
		$this->db->join('rs_submission_periods', 'rs_submission_periods.id = rs_submits.submission_period_id');

		$this->db->where('rs_submits.id',$submit_id);

		/*if($this->user_app_role == 'manager'){
			$this->db->where('rs_submits.manager_id', $this->user_id);
		}else{
			$this->db->where('rs_submits.user_id', $this->user_id);
		}*/
		$this->db->order_by('column','ASC');
		$this->db->order_by('order','ASC');
		$values = $this->db->get('rs_submit_values')->result();

		if(!$values) exit;

		/*getting theme color*/
		$this->db->select("wp_company.*,wp_file.*");
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
		$this->db->where('wp_company.id', $this->wp_company_id);
		$wpdata = $this->db->get('wp_company')->row();

		$data['color_one'] = $wpdata->colour_one;
		$data['color_two'] = $wpdata->colour_two;

		/*generating pdf*/
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('Report');
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		/*getting the logo*/
		$user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;

		$this->db->select("wp_company.*,wp_file.*");
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
		$this->db->where('wp_company.id', $wp_company_id);
		$wpdata = $this->db->get('wp_company')->row();

		$logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

		$html = <<<EOT
				<style>
					.tbl td{
						border: 0px solid #666666;
						border-collaplse: collapse;
					}
					td.lbl{
						background-color: {$data['color_one'] };
						color: #FFFFFF;
						font-weight: bold;
					}
				</style>
EOT;
		$period = date('d F, Y',strtotime($values[0]->from_period));
		if($values[0]->from_period != $values[0]->to_period){
			$period .= ' - ' . date('d F, Y',strtotime($values[0]->to_period));
		}

		/*managers can modify the submission period to be displayed in the report. this is based on a requirement
		on 28 Mar 2016*/
		if(($this->user_app_role == 'manager' || $this->user_app_role == 'admin') && $this->input->post('from_date') && $this->input->post('to_date')){
			$period = date('d F, Y',strtotime($this->input->post('from_date')));
			$period .= ' - ' . date('d F, Y',strtotime($this->input->post('to_date')));
		}

		$header_html = '<table width="100%">
						<tr>
						<td><img src="'.$logo.'" height="67"></td>
						<td style="text-align:right">
						<span style="font-size:20px; font-weight:bold">'.$values[0]->name.'</span><br />
						<span style="font-size:10px;">'.$period.'</span><br />
						<span style="font-size:16px;">'.$values[0]->username.'</span><br/>
						<span style="font-size:8px;">Submitted On: '.date_create_from_format('Y-m-d H:i:s',$values[0]->date)->format('d F, Y H:i:s').'</span>
						</td>
						</tr>
						</table>';




		$col1 = array();
		$col2 = array();

		foreach($values as $v){

			if($v->column == 1){
				$col1[] = $v;
			}else{
				$col2[] = $v;
			}

		}

		$len = (count($col1) > count($col2)) ? count($col1) : count($col2);

		$html .= '<table class="tbl" cellpadding="5">';
		for($i = 0; $i < $len; $i++){
			$html .= '<tr>';
			$c1 = (isset($col1[$i]))? $col1[$i] : '';
			$c2 = (isset($col2[$i]))? $col2[$i] : '';
			/*task #4057*/
			if($c1 && $c1->type == 'document'){
				$file = $this->db->get_where('file',array('fid'=>$c1->field_value),0,1)->row();
				$html .= '<td class="lbl" width="20%">'.$c1->field_label.'</td><td width="30%"><a href="'.site_url('form/document/'.$c1->field_value).'">'.$file->filename.'</a></td>';
			}else{
				$html .= '<td class="lbl" width="20%">'.$c1->field_label.'</td><td width="30%">'.$c1->field_value.'</td>';
			}

			if($c2 && $c2->type == 'document'){
				$file = $this->db->get_where('file',array('fid'=>$c2->field_value),0,1)->row();
				$html .= '<td class="lbl" width="20%">'.$c2->field_label.'</td><td width="30%"><a href="'.site_url('form/document/'.$c2->field_value).'">'.$file->filename.'</a></td>';
			}else{
				$html .= '<td class="lbl" width="20%">'.$c2->field_label.'</td><td width="30%">'.$c2->field_value.'</td>';
			}
			$html .= '</tr>';
		}
		$html .= "</table>";

		$pdf->headerHtml = $header_html;
		$pdf->SetHeaderMargin(5);
		$pdf->SetTopMargin(38);
		$pdf->AddPage();
		$pdf->setFontSize(9);
		$pdf->writeHTML($html);
		//$pdf->Output($values[0]->username."-".$values[0]->name."-".$values[0]->date.".pdf", 'I');
		$pdf->Output(FCPATH.'reports/'.$values[0]->username."-".$values[0]->name."-".str_replace(':','_',$values[0]->date).".pdf",  'F');
	}

	/*task #4136*/
	public function stop_report(){
		if($this->input->post('submit')){
			$post = $this->input->post();
			$error_message = array();
			if(!$post['form'] || !$post['comment'] || !$post['from_date'] || !$post['to_date']){
				$error_message[] = "One or more fields are empty.";
			}
			$d = DateTime::createFromFormat('Y-m-d', $post['from_date']);
			if(!$d || $d->format('Y-m-d') != $post['from_date']){
				$error_message[] = "Not a valid from date.";
			}
			$d = DateTime::createFromFormat('Y-m-d', $post['to_date']);
			if(!$d || $d->format('Y-m-d') != $post['to_date']){
				$error_message[] = "Not a valid to date.";
			}
			if($post['to_date'] < $post['from_date']){
				$error_message[] = 'From date cannot be greater than to date.';
			}
			if(!empty($error_message)){

				$this->session->set_flashdata('warning-message', implode("<br>",$error_message));

				redirect(site_url('form/submit'));
			}
			$data = array();
			foreach($post['form'] as $key => $form){
				/*task #4476*/
				$this->db->where('user_id', $this->user_id);
				$this->db->where('from_date <=', $post['from_date']);
				$this->db->where('to_date >=', $post['to_date']);
				if($this->db->get('rs_stopped_reports')->row()){
					unset($post['form'][$key]);
					continue;
				}

				$data[] = array(
					'form_id' => $form,
					'user_id' => $this->user_id,
					'from_date' => $post['from_date'],
					'to_date' => $post['to_date'],
					'comment' => $post['comment']
				);
			}
			if(empty($data)){

				$this->session->set_flashdata('warning-message', 'Forms are already stopped for this range.');

				redirect(site_url('form/submit'));
			}
			$this->db->insert_batch('rs_stopped_reports',$data);
			$this->session->set_flashdata('success-message', 'Report stopped successfully.');

			/*sending mail to managers*/
			$notified_managers = array();
			$message = "Hi #manager_name#,<br><br>

							#staff_name# stopped these following reports:<br>
							#report_list#

							Reason: #comment#";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Reporting System <reporting_system@wclp.co.nz>' . "\r\n";
            $username = $this->session->userdata('user')->username;
			$sql = "select * from rs_forms where id in (".implode(',',$post['form']).")";
			$forms = $this->db->query($sql)->result();
			foreach($forms as $form){
				if(!$form->managers_to_notify) continue;
				$sql = "select username, email from users where uid in (".$form->managers_to_notify.")";
				$managers = $this->db->query($sql)->result();
				foreach($managers as $manager){
					$notified_managers[$manager->username]['email'] = $manager->email;
					$notified_managers[$manager->username]['forms'][] = $form->name;
				}
			}
			foreach($notified_managers as $name => $manager){
				$report_list = "<ol>";
				foreach($manager['forms'] as $form){
					$from = date('d F, Y', strtotime($post['from_date']));
					$to = date('d F, Y', strtotime($post['to_date']));
					$report_list .= "<li style='padding: 5px'>{$form}. From {$from} to {$to} </li>";
				}
				$report_list .= "</ol>";
				$msg = str_replace(array('#manager_name#','#staff_name#','#report_list#','#comment#'), array($name, $username, $report_list, $this->input->post('comment')), $message);
				mail("{$name} <{$manager['email']}>", "Report Stopping Notification", $msg, $headers);
			}

			redirect(site_url('form/submit'));
		}
	}

	//task #4219
	public function preview($form_id, $submission_period_id){

		/*getting all fields from db*/
		$this->db->select('rs_forms.name AS form_name, rs_form_fields.*');
		$this->db->join('rs_form_fields','rs_form_fields.form_id = rs_forms.id');
		$this->db->join('rs_form_users','rs_form_users.form_id = rs_forms.id');
		$fields = $this->db->get_where('rs_forms',array('rs_forms.id' => $form_id, 'rs_form_users.user_id'=>$this->user_id))->result();

		if(empty($fields)){
			return;
		}
		$post = $this->input->post();

		/*the period for which the report is submitted*/
		$sql =  "select * from rs_submission_periods ".
			"WHERE id = {$submission_period_id} " .
			"AND form_id = {$form_id} ".
			"LIMIT 0, 1";

		$submission_period = $this->db->query($sql)->row();



		/*getting theme color*/
		$this->db->select("wp_company.*,wp_file.*");
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
		$this->db->where('wp_company.id', $this->wp_company_id);
		$wpdata = $this->db->get('wp_company')->row();

		$data['color_one'] = $wpdata->colour_one;
		$data['color_two'] = $wpdata->colour_two;

		/*getting the logo*/
		$this->db->select("wp_company.*,wp_file.*");
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
		$this->db->where('wp_company.id', $this->wp_company_id);
		$wpdata = $this->db->get('wp_company')->row();

		$logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

		$html = <<<EOT
				<style>
					.tbl td{
						border: 1px solid #666666;
						border-collaplse: collapse;
						padding: 8px;
					}
					td{
						border: none;
					}
					td.lbl{
						background-color: {$data['color_one'] };
						color: #FFFFFF;
						font-weight: bold;
					}
				</style>
EOT;
		$period = date('d F, Y',strtotime($submission_period->from));
		if($submission_period->from != $submission_period->to){
			$period .= ' - ' . date('d F, Y',strtotime($submission_period->to));
		}
		$header_html = '<table style="min-width: 595px; max-width: 595px;">
						<tbody style="border: none">
						<tr>
						<td><img src="'.$logo.'" height="67"></td>
						<td style="text-align:right">
						<span style="font-size:20px; font-weight:bold">'.$fields[0]->form_name.'</span><br />
						<span style="font-size:10px;">'.$period.'</span><br />
						<span style="font-size:16px;">'.$this->username.'</span><br/>
						<span style="font-size:8px;">Submitted On: '.date('d F, Y H:i:s').'</span>
						</td>
						</tr>
						</tbody>
						</table>';




		$col1 = array();
		$col2 = array();

		foreach($fields as $v){

			if($v->column == 1){
				$col1[] = $v;
			}else{
				$col2[] = $v;
			}

		}

		$len = (count($col1) > count($col2)) ? count($col1) : count($col2);

		$html .= '<table class="tbl" cellpadding="5" style="min-width: 595px; max-width: 595px;">';
		for($i = 0; $i < $len; $i++){
			$html .= '<tr>';
			$c1 = (isset($col1[$i]))? $col1[$i] : '';
			$c2 = (isset($col2[$i]))? $col2[$i] : '';

			if($c1 && $c1->type == 'document'){
				$html .= '<td class="lbl" width="20%">'.$c1->title.'</td><td width="30%"><a href="#">'.$_FILES['field_'.$c1->id]['name'].'</a></td>';
			}else{
				$title = (is_object($c1)) ? $c1->title : '';
				$val = (is_object($c1)) ? $post['field_'.$c1->id] : "";
				$html .= '<td class="lbl" width="20%">'.$title.'</td><td width="30%">'.nl2br($val).'</td>';
			}

			if($c2 && $c2->type == 'document'){
				$html .= '<td class="lbl" width="20%">'.$c2->title.'</td><td width="30%"><a href="#">'.$_FILES['field_'.$c2->id]['name'].'</a></td>';
			}else{
				$title = (is_object($c2)) ? $c2->title : '';
				$val = (is_object($c2)) ? $post['field_'.$c2->id] : "";
				$html .= '<td class="lbl" width="20%">'.$title.'</td><td width="30%">'.nl2br($val).'</td>';
			}
			$html .= '</tr>';
		}
		$html .= "</table>";

		echo $header_html;
		echo $html;
	}

	/*task #4452*/
	public function deactivate_report_temporary(){
		if($this->user_app_role != 'manager' && $this->user_app_role != 'admin') return;

		if($this->input->post('submit')){

			$post = $this->input->post();

			$error_message = array();

			if(!$post['form'] || !$post['staffs'] || !$post['comment'] || !$post['from_date'] || !$post['to_date']){
				$error_message[] = "One or more fields are empty.";
			}
			$d = DateTime::createFromFormat('Y-m-d', $post['from_date']);
			if(!$d || $d->format('Y-m-d') != $post['from_date']){
				$error_message[] = "Not a valid from date.";
			}
			$d = DateTime::createFromFormat('Y-m-d', $post['to_date']);
			if(!$d || $d->format('Y-m-d') != $post['to_date']){
				$error_message[] = "Not a valid to date.";
			}
			if($post['to_date'] < $post['from_date']){
				$error_message[] = 'From date cannot be greater than to date.';
			}
			if(!empty($error_message)){

				$this->session->set_flashdata('warning-message', implode("<br>",$error_message));

				redirect(site_url('form/show_list'));
			}
			$data = array();
			foreach($post['staffs'] as $staff){
				$data[] = array(
					'form_id' => $post['form'],
					'user_id' => $staff,
					'from_date' => $post['from_date'],
					'to_date' => $post['to_date'],
					'comment' => $post['comment']
				);
			}
			$this->db->insert_batch('rs_stopped_reports',$data);
			$this->session->set_flashdata('success-message', 'Report deactivated successfully.');

			/*sending mail to managers*/
			$notified_managers = array();
			$message = "Hi #manager_name#,<br><br>

							Report <b>#report#</b> is deactivated for:

							#staff_name#<br><br>

							From <u>#from#</u> to <u>#to#</u> <br><br>

							Reason: #comment#";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Reporting System <reporting_system@wclp.co.nz>' . "\r\n";
			$username = $this->session->userdata('user')->username;
			$sql = "select * from rs_forms where id = {$post['form']} limit 0, 1";
			$form = $this->db->query($sql)->row();
			if($form->managers_to_notify){
				$sql = "select username, email from users where uid in (".$form->managers_to_notify.")";
				$managers = $this->db->query($sql)->result();
				foreach($managers as $manager){
					$notified_managers[$manager->username]['email'] = $manager->email;
					//$notified_managers[$manager->username]['forms'][] = $form->name;
				}
			}
			$this->db->where("uid in (".implode(',',$post['staffs']).")");
			$staffs = $this->db->get('users')->result();
			$staff_arr = array();
			foreach($staffs as $s){
				$staff_arr[] = $s->username;
			}
			$staff_names = implode(', ',$staff_arr);

			$from = date('d F, Y', strtotime($post['from_date']));
			$to = date('d F, Y', strtotime($post['to_date']));

			foreach($notified_managers as $name => $manager){
				$msg = str_replace(array('#manager_name#','#staff_name#','#report#','#comment#','#from#','#to#'), array($name, $staff_names, $form->name, $this->input->post('comment'),$from,$to), $message);
				mail("{$name} <{$manager['email']}>", "Report Deactivate Notification", $msg, $headers);
			}

			redirect(site_url('form/show_list'));
		}
	}

	public function get_unsubmitted_report_list($form_id){

		$no_overdue = $this->db->get_where('rs_form_users',array('form_id' => $form_id, 'user_id' => $this->user_id),1,0)->row()->overdue;

		$query = "	SELECT * FROM rs_submission_periods p ".
				 "	WHERE p.form_id = {$form_id} ".
				 "	AND p.id NOT IN (SELECT submission_period_id FROM rs_submits WHERE form_id = {$form_id} AND user_id = {$this->user_id})".
				 "	AND (SELECT COUNT(*) FROM rs_stopped_reports WHERE user_id = {$this->user_id} AND form_id = {$form_id} AND from_date <= p.from AND to_date >= p.to) = 0 ".
				 "	ORDER BY p.id desc".
				 "	LIMIT 0, {$no_overdue}";

		header('Content-Type: application/json');
		echo json_encode( $this->db->query($query)->result() );
	}

	/*task #4617*/
	public function mark_report_as_complete(){

		$post = $this->input->post();

		if($post && $this->user_app_role == 'admin'){

			$form = $this->db->get_where('rs_forms',array('id' => $post['form_id'], 'wp_company_id' => $this->wp_company_id),1,0)->row();

			if($form){

				$data = array(
					'form_id' => $form->id,
					'user_id' => $post['user_id'],
					'date' => date('Y-m-d H:i:s'),
					'submission_period_id' => $post['id'],
					'marked_as_complete_by_admin' => 1
				);

				if($this->db->insert('rs_submits', $data)){
					header('Content-Type: application/json');
					echo json_encode(array('status' => 'success'));
					exit;
				}
			}
		}
		header('Content-Type: application/json');
		echo json_encode(array('status' => 'error', 'message' => 'an error occurred.'));
	}

}

