<?php
class Report extends CI_controller{
	
	function __construct(){
		parent::__construct();
		$this->load->helper(array('url','form'));
		$this->load->library(array('session','table'));
		$this->load->model('report_model','',TRUE);
		if(!$this->session->userdata('user')){
			redirect("user");
		}
	}
	function index(){
		$user = $this->session->userdata('user'); 
		$data['title'] = 'Williams Community : Report';
		//$data['users'] = $this->client_model->get_client_info()->result();

		$this->load->view('report/report',$data);
		
	}
	
	function risk_notification(){
		$user = $this->session->userdata('user'); 
		$data['title'] = 'Williams Community : Report';
		//$data['users'] = $this->client_model->get_client_info()->result();
		$clients = $this->report_model->at_risk_client()->result();
		
		foreach($clients as $client){
			if (valid_email($client->email))
			{
				$to = "help@williamsplatform.com";
				$subject = "New “At Risk Client“";

				$message = "
				<html>
				<body>
				<p>".$client->client_name." has not logged in for more than 7 days.<p/>
				<p> Please follow them up, their details are:</p>
				<table>
					<tr><td>Client Name:</td><td>".$client->client_name."</td></tr>
					<tr><td>Client URL:</td><td>".$client->url."</td></tr>
					<tr><td>Client Email:</td><td>".$client->email."</td></tr>
					<tr><td>Date Client Created:</td><td>".$client->created."</td></tr>
					<tr><td>Last Login:</td><td>".$client->last_login."</td></tr>
				</table>
				</body>
				</html>
				";

				// Always set content-type when sending HTML email
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				// More headers
				$headers .= 'From: <info@wclp.co.nz>' . "\r\n";
				//$headers .= 'Cc: alimuls@gmail.com' . "\r\n";

				mail($to,$subject,$message,$headers);
			}
		}
		//$this->load->view('report/report',$data);
		
	}
	function invoice_notification(){
		$user = $this->session->userdata('user'); 
		$data['title'] = 'Williams Community : Report';
		//$data['users'] = $this->client_model->get_client_info()->result();
		$clients = $this->report_model->at_risk_client()->result();
		
		foreach($clients as $client){
			if (valid_email($client->email))
			{
				$to = "office@williamsbusiness.co.nz";
				$subject = "Invoice Report“";

				$message = "
				<html>
				<body>
				<p>".$client->client_name." has not logged in for more than 7 days.<p/>
				<p> Please follow them up, their details are:</p>
				<table>
					<tr><td>Client Name:</td><td>".$client->client_name."</td></tr>
					<tr><td>Client URL:</td><td>".$client->url."</td></tr>
					<tr><td>Client Email:</td><td>".$client->email."</td></tr>
					<tr><td>Date Client Created:</td><td>".$client->created."</td></tr>
					<tr><td>Last Login:</td><td>".$client->last_login."</td></tr>
				</table>
				</body>
				</html>
				";

				// Always set content-type when sending HTML email
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				// More headers
				$headers .= 'From: <info@wclp.co.nz>' . "\r\n";
				//$headers .= 'Cc: alimuls@gmail.com' . "\r\n";

				mail($to,$subject,$message,$headers);
			}
		}
		//$this->load->view('report/report',$data);
		
	}

	public function export_client_list()
	{
		/*getting the data*/
		$this->db->select('wp_company.*, users.username, users.email');
		$this->db->join('users','users.company_id = wp_company.id');
		$this->db->where('users.role',1);
		$clients = $this->db->get('wp_company')->result();

		//load our new PHPExcel library
		$this->load->library('excel');

		//activate worksheet number 1

		$this->excel->setActiveSheetIndex(0);

		foreach(range('A','I') as $columnID) {
			$this->excel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		$active_sheet = $this->excel->getActiveSheet();
		//name the worksheet
		$active_sheet->setTitle('Client List');
		//set cell A1 content with some text
		$active_sheet->setCellValue('A1', 'Company Name');
		$active_sheet->setCellValue('B1', 'Company URL');
		$active_sheet->setCellValue('C1', 'Company Username');
		$active_sheet->setCellValue('D1', 'Email');
		$active_sheet->setCellValue('E1', 'Person in Charge');
		$active_sheet->setCellValue('F1', 'Phone No.');
		$active_sheet->setCellValue('G1', 'Address');
		$active_sheet->setCellValue('H1', 'Registration Date');
		$active_sheet->setCellValue('I1', 'Status');
		$active_sheet->freezePane('A2');
		//change the font size
		$active_sheet->getStyle('A1:I1')->getFont()->setSize(14);
		//make the font become bold
		$active_sheet->getStyle('A1:I1')->getFont()->setBold(true);

		$i = 2;
		$williams_companies = array(24,28,29,31,34);
		foreach($clients as $client){
			$active_sheet->setCellValue('A'.$i, $client->client_name);
			$active_sheet->setCellValue('B'.$i, $client->url);
			$active_sheet->setCellValue('C'.$i, $client->username);
			$active_sheet->setCellValue('D'.$i, $client->email);
			$active_sheet->setCellValue('E'.$i, $client->person_in_charge);
			$active_sheet->setCellValue('F'.$i, $client->phone_number);
			$active_sheet->setCellValue('G'.$i, $client->address);
			$active_sheet->setCellValue('H'.$i, date("d-m-Y H:i:s",strtotime($client->created)));

			if(($client->payment_token && $client->is_active) || in_array($client->id, $williams_companies)){
				$active_sheet->setCellValue('I'.$i, "paid");
				$active_sheet->getStyle('I'.$i.':'.'I'.$i)->getFont()->setColor(new PHPExcel_Style_Color('FF008000'));
			}else{
				$trial_expire_date = date_create_from_format('Y-m-d H:i:s',$client->created)->add(new DateInterval('P30D'));
				$today = new DateTime();
				if($today <= $trial_expire_date){
					$days_left = date_diff($trial_expire_date,$today)->days;
					if($days_left < 7){
						$active_sheet->setCellValue('I'.$i, $trial_expire_date->format('d-m-Y'));
						$active_sheet->getStyle('I'.$i.':'.'I'.$i)->getFont()->setColor(new PHPExcel_Style_Color('FFFF0000'));
					}else{
						$active_sheet->setCellValue('I'.$i, $trial_expire_date->format('d-m-Y'));
					}
				}else{

					$active_sheet->setCellValue('I'.$i, "expired");
				}
			}

			$i++;
		}
		$filename = 'wclp_client_list.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
//force user to download the Excel file without writing it to server's HD
		//$objWriter->save(__DIR__."/".$filename);
		ob_end_clean();
		$objWriter->save("php://output");
	}

	
}
