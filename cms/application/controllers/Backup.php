<?php
class Backup extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation'));
		$this->load->model('loan_model','',TRUE);
                $this->load->library('mbs_helper');
	}
	
	public function database() {	
            $data['title'] = 'Database Backup';
            $data['action'] = 'backup/database';            
            
            
            if ($this->input->post('submit')){
                
                // print_r($this->input->post()); exit;
                $post = $this->input->post();
                
                // Load the DB utility class
                $this->load->dbutil();

                $prefs = array(     
                         'format'      => 'zip',             
                         'filename'    => 'mbs_backup.sql'
                );

                $backup =& $this->dbutil->backup($prefs); 

                $db_name = 'mbsbackup-'. date("Ymd-His") .'.zip';
                // $save = $db_name;
                $save = './backups/'.$db_name;

                $this->load->helper('file');
                write_file($save, $backup); 

                if (isset($post['download']) && ($post['download']==1) ){
                    $this->load->helper('download');
                    force_download($db_name, $backup);                
                }
                $data['message'] = 'Database backup completed successfully.';      
            }                        

            $data['maincontent'] = $this->load->view('backup_submit_form',$data,true);

            $this->load->view('includes/header',$data);
            $this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
                                
	}
        
	public function download() {
            
            $data['title'] = 'Database Backup List';
            $data['action'] = 'backup/download';                                    
            $data['table'] = 'Coming soond';                                    

            $data['maincontent'] = $this->load->view('backup_list',$data,true);
            $this->load->view('includes/header',$data);
            $this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
                                
	}
        
}
	
?>