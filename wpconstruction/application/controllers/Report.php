<?php 
class Report extends CI_Controller {
	
	private $limit = 50;
	
	function __construct() {
		
		parent::__construct();		
		
		$this->load->helper(array('form', 'url', 'file', 'html', 'email'));
        $this->load->library(array('table', 'form_validation', 'session','Wbs_helper'));
        $this->load->library('Pdf');
		$this->load->model('report_model','',TRUE);
		$this->load->model('developments_model','',TRUE);
		$user = $this->session->userdata('user');
        $this->user_id = $user->uid;
        $this->wp_company_id = $user->company_id;
	}         
	public function index() {		
		
            $data['title'] = 'Report';
			$data['developments'] = $this->report_model->get_devlopments();

            $data['maincontent'] = $this->load->view('report',$data,true);
            $this->load->view('includes/header',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);  
	}
	public function user_log($page=0){

		$this->load->library('table','pagination');
		$this->load->helper('form');

		$this->load->helper(array('url'));

		/*user info*/
		$user = $this->session->userdata('user');
		$sql = "select LOWER(ar.application_role_name) role
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                where ua.user_id = {$user->uid} and a.id = 5 limit 0, 1";
		$user_app_role = $this->db->query($sql)->row()->role;
		if($user_app_role != 'manager' AND $user_app_role != 'admin') exit;

		/*pagination*/
		$this->load->library('pagination');

		$config['base_url'] = site_url('report/user_log');

		$this->db->join('users','users.uid = construction_user_logs.user_id');
		$this->db->where('users.company_id',$user->company_id);
		if($_GET['uid']){
			$this->db->where('user_id',$_GET['uid']);
		}
		$config['total_rows'] = $this->db->count_all_results('construction_user_logs');
		$config['per_page'] = 200;
		$config['reuse_query_string'] = TRUE;
		$config['full_tag_open'] = '<nav><ul class="pagination">';
		$config['full_tag_close'] = '</ul></nav>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		$pagination = $this->pagination->create_links();

		/*filtering form*/
		$this->db->select('distinct(construction_user_logs.user_id), users.username',false);
		$this->db->join('users','users.uid = construction_user_logs.user_id');
		$users = $this->db->get_where('construction_user_logs',array('users.company_id'=>$user->company_id))->result();
		$options = array(''=>'all');
		foreach($users as $u){
			$options[$u->user_id] = $u->username;
		}
		$form = form_open(site_url('report/user_log'),array('class'=>'form-inline','method'=>'get'));
		$form .= form_dropdown('uid', $options,$_GET['uid'],array('class'=>'form-control'));
		$form .= form_submit('submit', 'Submit', 'class=form-control');
		$form .= form_close();


		/*getting the log entries*/
		$this->db->select('users.username, construction_user_logs.*');

		$this->db->join('users','users.uid = construction_user_logs.user_id');

		$this->db->order_by('construction_user_logs.id','desc');

		$cond = array('users.company_id'=>$user->company_id);

		if($_GET['uid']){
			$cond['user_id'] = $_GET['uid'];
		}

		$this->db->where("message NOT LIKE 'Viewed%'");
		$this->db->where("message NOT LIKE 'Visited%'");

		$logs = $this->db->get_where('construction_user_logs',$cond,$config['per_page'],$page)->result();

		/*generating table*/
		$this->table->set_heading('User', 'Page', 'Activity', 'Time');

		foreach($logs as $log){

			$this->table->add_row($log->username, $log->page, $log->message, date('d-m-Y H:i:s',strtotime($log->created_at)));

		}
		$tmpl = array (
			'table_open'          => '<table class="table table-striped" style="margin-top: 5px">',

			'heading_row_start'   => '<tr>',
			'heading_row_end'     => '</tr>',
			'heading_cell_start'  => '<th>',
			'heading_cell_end'    => '</th>',

			'row_start'           => '<tr>',
			'row_end'             => '</tr>',
			'cell_start'          => '<td>',
			'cell_end'            => '</td>',

			'row_alt_start'       => '<tr>',
			'row_alt_end'         => '</tr>',
			'cell_alt_start'      => '<td>',
			'cell_alt_end'        => '</td>',

			'table_close'         => '</table>'
		);
		$this->table->set_template($tmpl);

		$data['title'] = "User Log";
		$this->load->view('includes/header',$data);
		$data['maincontent'] = $form.$this->table->generate().$pagination;
		$this->load->view('contact/contact_home',$data);
		$this->load->view('includes/footer');
	}

	function all_job_report($job_id){
		$data['title'] = 'Reports';
		$data['job_id']= $job_id;
		
		$data['development_content'] = $this->load->view('report/all_job_report', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);  
		
	}
	
	public function milestone_report() {		
		$data['show_construction_phase_column'] = true;
        $data['title'] = 'Milestone Report';
        
        $this->db->select("construction_development_milestones.date,construction_development_milestones.milestone_template_id, construction_milestone_templates.name, construction_development.job_number, construction_development.development_name, construction_development.id, construction_development.parent_unit");
        
        $this->db->join('construction_development_milestones', 'construction_development_milestones.job_id=construction_development.id');
        $this->db->join('construction_milestone_templates', 'construction_milestone_templates.id=construction_development_milestones.milestone_template_id');
        
        $this->db->where('construction_development.wp_company_id', $this->wp_company_id);
		$this->db->where('construction_development.status', 1);

        $this->db->order_by("construction_development.id", 'DESC');
        $this->db->group_by("construction_development.id");
        $data['milestone'] = $this->db->get('construction_development')->result();
        
        $data['development_content'] = $this->load->view('milestone_report', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);  
	}
	
	public function milestone_report_send_email() {
	  
        $this->db->select("construction_development_milestones.date,construction_development_milestones.milestone_template_id, construction_milestone_templates.name, construction_development.job_number, construction_development.development_name, construction_development.id");
        $this->db->join('construction_development_milestones', 'construction_development_milestones.job_id=construction_development.id');
        $this->db->join('construction_milestone_templates', 'construction_milestone_templates.id=construction_development_milestones.milestone_template_id');      
        $this->db->where('construction_development.wp_company_id', $this->wp_company_id);
        $this->db->order_by("construction_development.id", 'DESC');
        $this->db->group_by("construction_development.id");
        $milestone = $this->db->get('construction_development')->result();
        
        $this->db->select("wp_company.*,wp_file.*");
        $this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
        $this->db->where('wp_company.id', $this->wp_company_id);
        $wpdata = $this->db->get('wp_company')->row();
        $logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;
        
        $html = '<p><img width="170px" src="'.$logo.'" /> Milestone Report</p>';
        $html .= '<table width="100%" align="left" border="1" cellpadding="4" cellspacing="0">';
			$html .= '<thead>';
				$html .= '<tr>';
					$html .= '<th>Job Number</th>';
					$html .= '<th>Job</th>';
					
					$user = $this->session->userdata('user');
					$this->db->select("construction_milestone_templates.name");
			        $this->db->join('construction_development_milestones', 'construction_development_milestones.job_id=construction_development.id');
			        $this->db->join('construction_milestone_templates', 'construction_milestone_templates.id=construction_development_milestones.milestone_template_id');
			        $this->db->where('construction_development.wp_company_id', $this->wp_company_id);
			        $this->db->order_by("construction_development.id", 'DESC');
			        $this->db->group_by("construction_development_milestones.milestone_template_id");
			        $dates = $this->db->get('construction_development')->result();
					foreach($dates as $date):
						$html .= '<th>'.$date->name.'</th>';
					endforeach;
				$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
		
			foreach($milestone as $mile): 
				$html .= '<tr>';
					$html .= '<td>'.$mile->job_number.'</td>';
					$html .= '<td>'.$mile->development_name.'</td>';

					$this->db->select("construction_development_milestones.milestone_template_id");
			        $this->db->join('construction_development_milestones', 'construction_development_milestones.job_id=construction_development.id');
			        $this->db->join('construction_milestone_templates', 'construction_milestone_templates.id=construction_development_milestones.milestone_template_id');
			        $this->db->where('construction_development.wp_company_id', $this->wp_company_id);
			        $this->db->order_by("construction_development.id", 'DESC');
			        $this->db->group_by("construction_development_milestones.milestone_template_id");
			        $tems = $this->db->get('construction_development')->result();
					foreach($tems as $tem):
						$html .= '<td>';

							$this->db->select("construction_development_milestones.*");
					        $this->db->where('job_id', $mile->id);
					        $dates = $this->db->get('construction_development_milestones')->result();
							foreach($dates as $date):

								if($date->milestone_template_id==$tem->milestone_template_id)
								{
									if($date->date < date('Y-m-d')){
										$html .= '<span style="color:green;font-weight: bold;">'.$date->date.'</span><br>'; 
									}else{
										$html .= '<span style="font-weight: bold;">'.$date->date.'</span><br>'; 
									}
								} 
							endforeach;
						$html .= '</td>';
					endforeach;
				$html .= '</tr>';
			endforeach;
			$html .= '</tbody>';
		$html .= '</table>';
		
		//echo $html;
		
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('Schedule Report');
		$pdf->SetHeaderMargin(0);
		$pdf->SetTopMargin(0);
		$pdf->setPrintHeader(false);
		$pdf->setFooterMargin(0);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('Author');
				
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.'', PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('helvetica', '', 10);
        
        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();
		

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));      

		// Print text using writeHTMLCell()
		//$pdf->writeHTML($msg_body, true, false, true, false, '');
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		//$tcpdf->Write(10, 'Google', 'http://www.google.com/', false, 'L', true);
		//$pdf->Output('develompents.pdf', 'I');
		
		// a random hash will be necessary to send mixed content
		$separator = md5(time());

		// carriage return type (we use a PHP end of line constant)
		$eol = PHP_EOL;

		// attachment name
		$filename = "milestone_report.pdf";

		// encode data (puts attachment in proper format)
		$pdfdoc = $pdf->Output("", "S");
		$attachment = chunk_split(base64_encode($pdfdoc));

		// main header
		$from = 'no-reply@wclp.co.nz';
		
		$email = implode(',',$_POST['contact_email']);	
		$to = $email;
		
		$subject = 'Construction Management System -- Milestone Report';
		
		$headers  = "From: ".$from.$eol;
		//$headers .= 'Cc: '. $addition_email . "\r\n";
		$headers .= "MIME-Version: 1.0".$eol;
		$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

		$body = "--".$separator.$eol;
		$body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
		$body .= "This is a Milestone Report attachment detail.".$eol;

		// message
		$body .= "--".$separator.$eol;
		$body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
		$body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
		$body .= $eol;

		// attachment
		$body .= "--".$separator.$eol;
		$body .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol; 
		$body .= "Content-Transfer-Encoding: base64".$eol;
		$body .= "Content-Disposition: attachment".$eol.$eol;
		$body .= $attachment.$eol;
		$body .= "--".$separator."--";

		if(mail($to, $subject, $body, $headers))
		{
			//echo 'Mail Sent Successfully';
			redirect('report/milestone_report');
		}else{ 
			//echo  'Mail did not Sent'; 
			redirect('report/milestone_report');
		}
			
	}
	function construction_investor_report($job_id=''){
		
		$this->wbs_helper->is_own_job($job_id);
		
		$job = $this->db->get_where('construction_development', array('id' => $job_id, 'wp_company_id'=>$this->wp_company_id), 0, 1)->row();
		$development = $this->developments_model->get_development_detail($job_id)->row();
        $data['development_details'] = $development;
        $data['job_id'] = $job_id;

		$data['investor_data'] = $this->developments_model->get_investor_data($job_id)->row();

		$data['investor_draw_down'] = $this->developments_model->get_investor_draw_down($job_id)->result();

        $data['job_pre_construction_info'] = $this->developments_model->get_development_phase_info($job_id, 'pre_construction')->result();
        $data['job_construction_info'] = $this->developments_model->get_development_phase_info($job_id, 'construction')->result();
		$data['job_post_construction_info'] = $this->developments_model->get_development_phase_info($job_id, 'post_construction')->result();


		$data['development_id'] = $job->id;
        $data['current_job'] = $job->id;

        $this->db->select("phase.*, job.development_name");
        $this->db->join('construction_development job',"job.id = phase.development_id");
        $where = "development_id = {$job->id} ";
        /*if it is unit we will include the child jobs (and child jobs will not have pre-construction)*/
        $where .= "OR (job.parent_unit = {$job->id} AND construction_phase <> 'pre_construction')";
        /*if it is child job we will include the pre-construction of parent unit*/
        if($job->parent_unit){

            $where .= "OR (job.id = {$job->parent_unit} AND construction_phase = 'pre_construction')";
        }
        $this->db->where($where);
        $this->db->order_by('is_unit','desc');
        $this->db->order_by('job.id','asc');
        $this->db->order_by("FIELD(construction_phase, 'pre_construction', 'construction', 'post_construction')");
        $data['development_overview_info'] = $this->db->get('construction_development_phase phase')->result();

        $this->db->select("templates.name, milestone.*");
        $this->db->join("construction_milestone_templates templates", "templates.id = milestone.milestone_template_id");
        $data['milestones'] = $this->db->get_where('construction_development_milestones milestone',array('job_id'=>$job_id))->result();
        $data['show_construction_phase_column'] = true;
		
        $data['development_content'] = $this->load->view('developments/construction_investment_report', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('developments/development_home', $data);
        $this->load->view('includes/footer', $data);
	}
	public function update_investor_data($job_id, $field, $value){
		if($field == 'estimation_settlement_date'){
			$value = date("Y-m-d", strtotime(urldecode($value)));
		}else{
        	$value = urldecode($value);
		}
        $exist = $this->db->query("SELECT * FROM construction_investor_data WHERE job_id = {$job_id}")->row();
		if($exist->id){
        	$res = $this->db->simple_query("UPDATE construction_investor_data set {$field}='{$value}' where job_id = {$job_id}");
		}else{
			$res = $this->db->simple_query("INSERT INTO construction_investor_data (job_id, {$field}) VALUES ('{$job_id}','{$value}') ");
		}
        if($res)
            echo 1;
        else
            echo 0;

        /*log*/
        //$job = $this->db->get_where('construction_development',array('id'=>$id),1,0)->row()->development_name;
        //$this->wbs_helper->log('Job Edit','For job: <b>'.$job.'</b> updated <b>'.$field.'</b> value to <b>'.$value.'</b>');
        //exit;

    }
	function update_investor_draw_down($job_id, $field, $value,$draw_down_id){

		if($field == 'draw_date'){
			$value = date("Y-m-d", strtotime(urldecode($value)));
		}else{
        	$value = urldecode($value);
		}
		if($draw_down_id > 0 ){
        	$this->db->simple_query("UPDATE construction_investor_draw_downs set {$field}='{$value}' where id = {$draw_down_id}");
			echo $draw_down_id;
		}else{
			$res = $this->db->simple_query("INSERT INTO construction_investor_draw_downs (job_id, {$field}) VALUES ('{$job_id}','{$value}') ");
			echo $insert_id = $this->db->insert_id();
		}

	}
	function delete_draw_down($id,$job_id){
		$this->db->delete('construction_investor_draw_downs',array(
                'id'=>$id,
                'job_id'=>$job_id
            ));
		echo '1';
	}

	function investor_report_pdf(){
		$job_id = $this->input->post('job_id');
		
		$investor_data = $this->developments_model->get_investor_data($job_id)->row();
		$development = $this->developments_model->get_development_detail($job_id)->row();
		$investor_draw_down = $this->developments_model->get_investor_draw_down($job_id)->result();
		
		$total_draw_down = 0;
		$total_predicted_interest_earned = 0;
		$total_current_interest_earned = 0;
		foreach($investor_draw_down as $draw){ 
			$total_draw_down = $total_draw_down + $draw->draw_down_amount;
			$daysf = date_diff(date_create($draw->draw_date),date_create($investor_data->estimation_settlement_date));
			$current_daysf = date_diff(date_create($draw->draw_date),date_create(date("y-m-d")));

			$days = (int)$daysf->format("%a");
			$current_days = (int)$current_daysf->format("%a");
			
			$predicted_interest_earned = (($draw->draw_down_amount * $investor_data->interest_rate)/(100 * 365)) * $days; 
			$current_interest_earned = (($draw->draw_down_amount * $investor_data->interest_rate)/(100 * 365)) * $current_days; 

			$total_predicted_interest_earned = $total_predicted_interest_earned + $predicted_interest_earned;
			$total_current_interest_earned = $total_current_interest_earned + $current_interest_earned;
		}
		
		$html = '<br><br>';
		$html .= '<table border="0" cellpadding="4" cellspacing="0" width="100%">';
		$html .= '<tbody>';
		$html .= '<tr>';
		$html .= '<td width="50%">';
		
		$html .= '<table border="0" cellpadding="4" cellspacing="0" width="100%">';
		$html .= '<tbody>';
		
		$html .= '<tr>';
		$html .= '<td colspan="2"><span>Agreed Funding Facility</span><br>$'.number_format($investor_data->agreed_funding_facility).'</td>';
		$html .= '</tr>';
		
		$html .= '<tr>';
		$html .= '<td colspan="2"><span>Estimation Settlement Date</span><br>'.date("d-m-Y", strtotime($investor_data->estimation_settlement_date)).'</td>';
		$html .= '</tr>';
		
		$html .= '<tr>';
		$html .= '<td colspan="2"><span>Interest Rate %</span><br>'.$investor_data->interest_rate.'</td>';
		$html .= '</tr>';
		
		$html .= '<tr>';
		$html .= '<td><span>Predicted Interest Earned</span><br>$'.round($total_predicted_interest_earned,2).'</td>';
		$html .= '<td><span>Current Interest Earned</span><br>$'.round($total_current_interest_earned,2).'</td>';
		$html .= '</tr>';
		
		$html .= '</tbody>';
		$html .= '</table>';
		
		$html .= '</td>';
		
		$html .= '<td width="50%">';
		$html .= '<table border="0" cellpadding="4" cellspacing="0" width="100%">';
		$html .= '<thead>';
		$html .= '<tr>';
		$html .= '<th><strong>Date</strong></th>';
		$html .= '<th><strong>Draw Downs</strong></th>';
		$html .= '</tr>';
		$html .= '</thead>';
		$html .= '<tbody>';
		
		foreach($investor_draw_down as $draw){ 
			//$total_draw_down = $total_draw_down + $draw->draw_down_amount;
			$html .= '<tr>';
			$html .= '<td style="border-top:1px solid #000;">'.date("d-m-Y", strtotime($draw->draw_date)).'</td>';
			$html .= '<td style="border-top:1px solid #000;">$'.number_format($draw->draw_down_amount).'</td>';
			$html .= '</tr>';
		}
		
		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '</td>';
		
		$html .= '</tr>';
		$html .= '</tbody>';
		$html .= '</table>';
		
		$total_draw_down_percent = ($total_draw_down/$investor_data->agreed_funding_facility)*100;
		$rest_draw = 100 - ( ($total_draw_down/$investor_data->agreed_funding_facility)*100); 
		
		$html .= '<br><br><table align="center" color="#ffffff" border="0" cellspacing="0" cellpadding="4" width="100%">';
		$html .= '<tbody>';
		$html .= '<tr>';
		$html .= '<td bgcolor="#197b30" width="'.$total_draw_down_percent.'%">$'.number_format($total_draw_down).'</td>';
		$html .= '<td bgcolor="#fbba00" width="'.$rest_draw.'%">$'.number_format($investor_data->agreed_funding_facility - $total_draw_down).'</td>';
		$html .= '</tr>';
		$html .= '</tbody>';
		$html .= '</table>';
		
		$html .= '<br><br><table border="0" cellpadding="0" cellspacing="0" width="100%">';
		$html .= '<thead>';
		$html .= '<tr>';
		$html .= '<th><strong>New Sales Plan:</strong></th>';
		$html .= '<th><strong>Current Sales Plan:</strong></th>';
		$html .= '</tr>';
		$html .= '</thead>';
		$html .= '<tbody>';
		$html .= '<tr>';
		$html .= '<td>'.$development->new_sales_plan.'</td>';
		$html .= '<td>'.$development->current_sales_plan.'</td>';
		$html .= '</tr>';
		$html .= '</tbody>';
		$html .= '</table>';
		
		/*setting the logo*/
        $this->db->select("wp_company.*,wp_file.*");
        $this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
        $this->db->where('wp_company.id', $this->wp_company_id);
        $wpdata = $this->db->get('wp_company')->row();

        $logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

        $job = $this->db->get_where('construction_development',array('id' => $job_id),1,0)->row();

        $dt = date('d-m-Y H:i:s');
        $html_h = <<<EOT
        <br><br>
        <table width="100%" style="margin-top:20px">
					<tr>
					<td width="30%"><img src="{$logo}" height="67"></td>
					<td style="text-align:left">
					<span style="font-size:16px;">Investor Report for #{$job->job_number} - {$job->development_name}</span><br />
					<span style="font-size:12px;">Generated at {$dt}</span>
					</td>
					</tr>
		</table>
EOT;
		
		$this->pdf->headerHtml = $html_h;

		$this->pdf->SetTitle('investor report');
        $this->pdf->setPageOrientation('L');
        $this->pdf->SetTopMargin(30);
        $this->pdf->AddPage('L');
        $this->pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $svg_data = $this->input->post('svg');

        foreach($svg_data as $svg){
            $svg = str_replace('data:image/svg+xml;base64,','',$svg);
            $imgdata = base64_decode($svg);
            $this->pdf->AddPage('L');
			$this->pdf->writeHTMLCell(0, 0, '', '', '<div style="text-align:center">', 0, 1, 0, true, '', true);
            $this->pdf->ImageSVG('@'.$imgdata,'','',278,'','','M','C');
			$this->pdf->writeHTMLCell(0, 0, '', '', '</div>', 0, 1, 0, true, '', true);
        }
        
        $this->pdf->output('investor_report.pdf','D');
	}

}
?>