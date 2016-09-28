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
			$auth = $db->query("SELECT * FROM users_application WHERE user_id = '$user_id' AND application_id = '11'")->row();
			if(!$auth)
			{
				redirect('http://'.$_SERVER['SERVER_NAME'],'refresh'); 
			}
			/*checking company permission*/
			$company_auth = $db->query("SELECT * FROM wp_company_applications WHERE company_id = '$user->company_id' AND application_id = '5'")->row();
			if(!$company_auth)
			{
				redirect('http://'.$_SERVER['SERVER_NAME'],'refresh');
			}
		}else{
			redirect('http://'.$_SERVER['SERVER_NAME'],'refresh');
		}
    }
}