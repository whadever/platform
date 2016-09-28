<?php

class AppLoader
{
			
    function AppPermission() {

		$CI = & get_instance();

		if($CI->session->userdata('user') || $_GET['uid']){
	        $user = $CI->session->userdata('user');
			$db = $CI->load->database("ums", true);
			if($_GET['uid']){
				$user_id = $_GET['uid'];
			}else{
				$user_id = $user->uid;
			}
			$auth = $db->query("SELECT * FROM users_application WHERE user_id = '$user_id' AND application_id = '6'")->row();
			if(!$auth)
			{
				redirect('http://'.$_SERVER['SERVER_NAME']."/dashboard?uid=$user_id",'refresh'); 
			}
		}else{
			redirect('http://'.$_SERVER['SERVER_NAME'],'refresh');
		}
    }
}