<?php

class AppLoader
{

	private $CI;
			
    function AppPermission() {

		$this->CI = & get_instance();
		if($this->CI->session->userdata('user')){
	        $user = $this->CI->session->userdata('user');
			$db = $this->CI->load->database("ums", true);
			$user_id = $user->uid;
			$auth = $db->query("SELECT * FROM users_application WHERE user_id = '$user_id' AND application_id = '2'")->row();
			if(!$auth)
			{
				redirect('http://'.$_SERVER['SERVER_NAME'],'refresh'); 
			}
		}else{
			redirect('http://'.$_SERVER['SERVER_NAME'],'refresh');
		}
    }
}