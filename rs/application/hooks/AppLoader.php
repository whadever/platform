<?php

class AppLoader
{

	private $CI;
			
    function AppPermission() {

		$this->CI = & get_instance();
		if($this->CI->session->userdata('user')){
	        $user = $this->CI->session->userdata('user');
			$db = $this->CI->load->database("default", true);
			$user_id = $user->uid;

			$company_id = $user->company_id; 
			$time_zone = $db->query("SELECT time_zone FROM wp_company WHERE id = $company_id")->row();
			$tz = $time_zone->time_zone;
			date_default_timezone_set($tz);

			$auth = $db->query("SELECT * FROM users_application WHERE user_id = '$user_id' AND application_id = '8'")->row();
			if(!$auth)
			{
				redirect('http://'.$_SERVER['SERVER_NAME'],'refresh'); 
			}
		}else{
			redirect('http://'.$_SERVER['SERVER_NAME'],'refresh');
		}
    }
}