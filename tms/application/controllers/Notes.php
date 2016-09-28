<?php

// main ajax back end
class Notes extends CI_Controller
{

    function __construct()
    {
        parent::__construct();


        $this->load->model('notes_model', '', TRUE);
        $this->load->model('request_model', '', TRUE);
        $this->load->library(array('table', 'form_validation', 'session'));
        $this->load->library('breadcrumbs');
        $this->load->helper(array('form', 'url', 'file'));

        $redirect_login_page = base_url() . 'user';
        if (!$this->session->userdata('user')) {
            redirect($redirect_login_page, 'refresh');

        }
    }

    public function notes_list($generate_for_mail = false)
    {
        $data['title'] = 'Task Notes';


        $all_notes = $this->notes_model->getAllNotes();
        //$data['all_notes'] = $all_notes;
        //print_r($all_notes); exit;

        $sl = 1;

        /*$this->table->set_heading(
            '#', 'COMPANY', 'PROJECT',
            'TASK(ID & NAME)', 'MANAGER',
            'NOTES', 'PERSON NOTIFIED',
            'TIME'
        );*/
        //task #4117
        $this->table->set_heading(
            '#', 'Project',
            'Task(ID & Name)', 'Manager', 'Notes From',
            'Notes', 'Person Notified',
            'Time'
        );
        foreach ($all_notes as $notes) {

            $assign_manager = $this->request_model->get_assign_manager($notes->assign_manager_id);
            $person_notify = $this->notes_model->getNotifiedUser($notes->notify_user_id);

            $this->table->add_row(
                $sl,
                //$notes->company_name, //task #4117
                $notes->project_name,
                '#' . $notes->request_id . ' - ' . $notes->request_title,
                array('data' => implode(", ", $assign_manager), 'class' => 'hidden599'),
                $notes->username, //task #4117
                $notes->notes_body,
                $person_notify,
                date(("G:i"), strtotime($notes->created))
            );
            $sl++;
        }


        $tmpl = array(
            'table_open' => '<table border="0" cellpadding="2" cellspacing="1" class="table table-hover">',
            'heading_cell_start'  => '<th style="vertical-align: middle">',
            'table_close' => '</table>'
        );

        $this->table->set_template($tmpl);
        $data['table'] = $this->table->generate();

        if(!$generate_for_mail){
            $data['maincontent'] = $this->load->view('notes_list_view', $data, true);

            $this->load->view('includes/header', $data);
            $this->load->view('home', $data);
            $this->load->view('includes/footer', $data);
        }else{
            $this->load->view('notes_list_view_for_mail', $data);
        }


    }


    public function index($req_no = '')
    {

        //echo $req_id;
		$user = $this->session->userdata('user');
		$company_id = $user->company_id;

        $data['title'] = 'Task Notes';        

        $data['request_info'] = $this->notes_model->getRequestInfo($company_id,$req_no);

		$req_id = $data['request_info']->id;
		$data['request_id'] = $req_id;

		$update_notes_view = $this->request_model->notes_view_update($req_id);

        $prev_notes = $this->notes_model->getPriviousNotes($req_id);
        $data['prev_notes'] = $this->notes_image_tmpl($prev_notes);
        //print_r($prev_notes);
        // load view
        $data['maincontent'] = $this->load->view('notes_view', $data, true);

        $this->load->view('includes/header', $data);
        //$this->load->view('includes/sidebar',$data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer', $data);


    }

    public function show_notes($rid, $notify_user_id = '')
    {
        $user = $this->session->userdata('user');
		$request_no = $_GET['request_no'];

        $user_id = $user->uid;
        $user_email = $user->email;
        $user_name = $user->username;
        $user_role = $user->rid;
        $note_body = $_GET['notes'];
        $now = date('Y-m-d H:i:s');

		$wp_company_id = $user->company_id;

		$this->db->select("wp_company.*,wp_file.*");
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
	 	$this->db->where('wp_company.id', $wp_company_id);	
		$wpdata = $this->db->get('wp_company')->row();

		$logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

        $insert_note = $this->notes_model->insertNote($rid, $note_body, $user_id, $notify_user_id, $now);
        $prev_notes = $this->notes_model->getPriviousNotes($rid);
        echo $this->notes_image_tmpl($prev_notes);


        $request_info = $this->notes_model->get_request_user_info($rid);

        $request_id = $request_info->id;
        $request_title = $request_info->request_title;
        $request_project = $request_info->project_name;
        $request_created_by = $request_info->created_by;

        $assign_manager_id = $request_info->assign_manager_id;
        $assign_developer_id = $request_info->assign_developer_id;


        $assign_manager_info = $this->request_model->get_manager_info($assign_manager_id);

        foreach ($assign_manager_info as $manager) {
            $manager_name[] = $manager->username;
            $manager_email[] = $manager->email;
        }
        $assign_manager_name = implode(", ", $manager_name);
        $assign_manager_email = implode(", ", $manager_email);


        $assign_developer_info = $this->request_model->get_developer_info($assign_developer_id);
        foreach ($assign_developer_info as $developer) {
            $developer_name[] = $developer->username;
            $developer_email[] = $developer->email;
        }
        $assign_developer_name = implode(", ", $developer_name);
        $assign_developer_email = implode(", ", $developer_email);

        $dev_name = $assign_developer_name;
        $dev_email = $assign_developer_email;
        $staff_name = $assign_manager_name;
        $staff_email = $assign_manager_email;


        //user role 2 for staff 3 for developer

        /* we will send to both dev and managers*/

        /*if ($user_role == 2) {
            $to = $dev_email;
            $cc = '';
        } elseif ($user_role == 3) {
            $to = $staff_email;
            $cc = '';
        } else {
            $to = $staff_email;
            $cc = $dev_email;
        }*/

        $notify_user_info = $this->notes_model->get_user_info($notify_user_id);
        $notify_user_email = array();
        foreach ($notify_user_info as $user_info) {
            $notify_user_name[]=$user_info->username;
            $notify_user_email[] = $user_info->email;
        }

        $to = array_merge($manager_email, $developer_email);
        $to[] = $user_email;

        /*modified on 24 Aug, 2015 for task #1517*/

        $from = $user_email;
        $notes_from = $user_name;
        //$subject = 'You have a notes from '.$notes_from.' on Request -'.$request_title;
        $subject = 'New note from -'.$request_project . ' - '.$request_title.' - #' . $request_no;
        $cc = implode(",",$notify_user_email);

        //$headers = "From: ".$from . "\r\n";
        //$headers = "From: " . "taskmanager@williamscorporation.co.nz" . "\r\n";
        $headers = "From: " . "tms@e-wclp.co.nz" . "\r\n";
        //$headers .= "Reply-To: " . "taskmanager@williamscorporation.co.nz" . "\r\n";
        $headers .= "Reply-To: " . "tms@e-wclp.co.nz" . "\r\n";
        if(!empty($cc)){

            $headers .= "CC: ". $cc . "\r\n";
        }
        $headers .= "BCC: " . "mail_helper@e-wclp.co.nz" . "\r\n";
        //$headers .= "BCC: " . "taskman_helper@williamscorporation.co.nz" . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $headers .= "X-noteId:" . $insert_note . "\r\n";
        $headers .= "X-app:" . 'tms' . "\r\n";
        $headers .= "X-companyId:" . $user->company_id . "\r\n";

        $message = '';
        $message .= '<html><body>';
        $message .= '<table cellpadding="10" cellspacing="0" width="100%">';
		$message .= '<tr><td colspan="2" align="center" style="background-color:#d72235; height:50px; width:100%"></td></tr>';
		$message .= '<tr><td colspan="2" align="center" style="background-color:#fdb93a; height:5px; width:100%"></td></tr>';
		$message .= '<tr><td colspan="2" align="center"><img src="'.$logo.'" width="300" /></td></tr>';
		$message .= '<tr><td colspan="2" align="center"> <h1 style="color:#e92028; margin:0px; padding:0px; font-size:24px;">Task Management System</h1> <span style="color:#e92028"> New Request from '.$user_name.' on Project: '.$request_project.'</span> </td></tr>';
        /* Information */
		
        $message .= "<tr><td style='border-right: 1px solid #000'><strong>Request Id :</strong> </td><td>" . $request_no . "</td></tr>";
        $message .= "<tr><td style='border-right: 1px solid #000'><strong>Request Title:</strong> </td><td>" . $request_title . "</td></tr>";
        $message .= "<tr><td style='border-right: 1px solid #000'><strong>Task Notes:</strong> </td><td>" . $note_body . "</td></tr>";
        $message .= "<tr><td style='border-right: 1px solid #000'><strong>Project Name:</strong> </td><td>" . $request_project . "</td></tr>";
        if(!empty($notify_user_name)){

            $message .= "<tr><td style='border-right: 1px solid #000'><strong>Notified:</strong> </td><td>" . implode(", ",$notify_user_name) . "</td></tr>";
            //$headers .= "X-notified:" . implode(", ",$notify_user_name) . "\r\n";
        }

        $message .= "<tr><td style='border-right: 1px solid #000'><strong>Notes From :</strong> </td><td>" . $notes_from . "</td></tr>";

        $message .= "<tr><td style='border-right: 1px solid #000'><strong>URL :</strong> </td><td>" . site_url("notes/index/" . $request_no) .  "</td></tr>";
		$message .= '<tr><td colspan="2" align="center" style="background-color:#fdb93a; height:5px; width:100%"></td></tr>';
		$message .= '<tr><td colspan="2" align="center" style="background-color:#d72235; height:50px; width:100%"></td></tr>';
        $message .= "</table>";
		
        $message .= "<br>";
        /*$message .= "------reply below this line------";
        $message .= "<br><br><br><br><br><br><br><br><br><br><br><br>";
		$message .= "------reply end------";
		$message .= "<br><br><br><br><br><br><br><br><br><br><br><br>";*/
        $message .= "</body></html>";
        //$msg_body='message body';
        $msg_body = $message;
        $to = array_unique($to);
        $recipients = implode(',',$to);
        mail($recipients, $subject, $msg_body, $headers);
        exit;

        /*we will not send a separate mail to notified persons (27-Aug-2015)*/
        /*$notify_user_info = $this->notes_model->get_user_info($notify_user_id);
        $notify_user_email = array();
        foreach ($notify_user_info as $user_info) {
            //$user_name[]=$user->name;
            $notify_user_email[] = $user_info->email;
        }
        //$assign_user_name= implode(", ", $user_name);
        $notify_user_to = implode(", ", $notify_user_email);


        $from2 = $user_email;
        $notes_from2 = $user_name;
        $subject2 = 'Hi, You have a notification from ' . $notes_from2 . ' on task -' . $request_title;

        $headers2 = "From: " . "taskmanager@williamscorporation.co.nz" . "\r\n";
        $headers2 .= "Reply-To: " . "taskmanager@williamscorporation.co.nz" . "\r\n";
        //$headers .= "CC: ". $cc . "\r\n";
        $headers2 .= "MIME-Version: 1.0\r\n";
        $headers2 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $message2 = '';
        $message2 .= '<html><body>';
        $message2 .= "Hello, <strong>" . $notes_from2 . "</strong> has added a new note to task '" . $request_title . "'  on project '" . $request_project . "'<br />";
        $message2 .= "Note Description: " . $note_body . " <br />";
        $message2 .= " To view this conversation, follow this link: " . "https://williamscorporation.co.nz/wp/tms/" . "notes/index/" . $rid . "";
        $message2 .= "</body></html>";
        //$msg_body='message body';
        $msg_body2 = $message2;
        mail($notify_user_to, $subject2, $msg_body2, $headers2);*/


    }


    public function upload_note_image()
    {
        $req_id = $_POST['request_id'];
        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        // print_r($_FILES); //return;
        //$post = $this->input->post();

        $config['upload_path'] = UPLOAD_NOTES_PATH_IMAGE_FILE;
        $config['allowed_types'] = '*';


        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $document_insert_id = 0;
        if ($this->upload->do_upload('note_image')) {
            $upload_data = $this->upload->data();
            // echo $upload_data->file_name;
            //print_r($upload_data);
            // insert data to file table
            // get latest id from frim table and insert it to loan table
            $document = array(
                'filename' => $upload_data['file_name'],
                'filetype' => $upload_data['file_type'],
                'filesize' => $upload_data['file_size'],
                'filepath' => $upload_data['full_path'],
                //'filename_custom'=>$post['note_image'],
                'created' => date("Y-m-d H:i:s"),
                'uid' => $user_id
            );
            $image_insert_id = $this->notes_model->notes_image_insert($document);
        } else {
            print 'error in file uploading...';
            print $this->upload->display_errors();
        }

        $notes_data = array(
            'request_id' => $req_id,
            'notes_body' => '',
            'notes_image_id' => $image_insert_id,
            'notes_by' => $user_id,
            'created' => date("Y-m-d H:i:s", time())

        );

        $id = $this->notes_model->notes_image_save($notes_data);

    }

    public function show_notes_with_image($rid)
    {


        $prev_notes = $this->notes_model->getPriviousNotes($rid);
        echo $this->notes_image_tmpl($prev_notes);


        //echo '<p>Me : '.urldecode($note).'<br /> '.  date('d/m/Y g:i a').'<p>';

    }

    public function notes_image_tmpl2($prev_notes)
    {

        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $align_class = '';
        $tmpl = '';
        foreach ($prev_notes as $notes) {


            if ($notes->notes_by == $user_id) {
                $showuser = 'Me';
                $notified_user = $this->notes_model->getNotifiedUserName($notes->notify_user_id);
                $creation_time = date('g:i a d/m/Y', strtotime($notes->created));
                $align_class = 'right';
                if (!$notes->notes_image_id == null) {
                    $show_file = $this->notes_model->getNotesImage($notes->notes_image_id);
                    $file_name = $show_file->filename;
                    $allowedExts = array("gif", "jpeg", "jpg", "png", "PNG");
                    $temp = explode(".", $file_name);
                    $extension = end($temp);
                    if (in_array($extension, $allowedExts)) {
                        //this is image
                        $tmpl .= '<div class="' . $align_class . '"><div class="userme"> :' . $showuser . '</div> <div style="float: right;" class="notes_body"><img height="100" width="100" src="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '"/></div><div class="show-time" style="float:right;"><span class="time-left1">' . $creation_time . '</span></div> </div>';
                    } else {
                        //this is file not image
                        $tmpl .= '<div class="' . $align_class . '"><div class="userme"> :' . $showuser . '</div> <div style="float: right;" class="notes_body"><a target="_blank" href="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '">' . $file_name . '</a></div><div class="show-time" style="float:right;"><span class="time-left1">' . $creation_time . '</span></div> </div>';
                    }


                } else {
                    $tmpl .= '<div class="' . $align_class . '"><div class="userme"> :' . $showuser . '</div> <div style="float: right;" class="notes_body">' . $notes->notes_body . '</div><div style="float:right;"> ' . $notified_user . ' <span class="time-left1">' . $creation_time . '</span></div> </div>';
                }


                $tmpl .= '<div class="clear"></div>';

            } else {
                $showuser = $notes->username;
                $creation_time = date('g:i a d/m/Y', strtotime($notes->created));
                $align_class = 'left';
                $notified_user = $this->notes_model->getNotifiedUserName($notes->notify_user_id);
                if (!$notes->notes_image_id == null) {
                    $show_file = $this->notes_model->getNotesImage($notes->notes_image_id);
                    $file_name = $show_file->filename;
                    $allowedExts = array("gif", "jpeg", "jpg", "png", "PNG");
                    $temp = explode(".", $file_name);
                    $extension = end($temp);
                    if (in_array($extension, $allowedExts)) {
                        //this is image
                        $tmpl .= '<div class="' . $align_class . '"><div class="useranother">' . $showuser . ':</div> <div style="float: left;" class="notes_body"><img height="100" width="100" src="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '"/></div> <span class="time-right">' . $creation_time . '</span></div>';
                    } else {
                        //this is not image
                        $tmpl .= '<div class="' . $align_class . '"><div class="useranother">' . $showuser . ':</div> <div style="float: left;" class="notes_body"><a target="_blank" href="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '">' . $file_name . '</a></div> <span class="time-right">' . $creation_time . '</span></div>';
                    }
                } else {
                    $tmpl .= '<div class="' . $align_class . '"><div class="useranother">' . $showuser . ':</div>  <div style="float: left;" class="notes_body">' . $notes->notes_body . '</div><div style="float:left;"><span class="time-right1">' . $creation_time . '</span> ' . $notified_user . '</div> </div>';
                }

                $tmpl .= '<div class="clear"></div>';
            }
        }
        return $tmpl;

    }

    public function notes_image_tmpl($prev_notes)
    {

        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $align_class = '';
        $tmpl = '';
        foreach ($prev_notes as $notes) {


            if ($notes->notes_by == $user_id) {
                $showuser = 'Me';
                $notified_user = $this->notes_model->getNotifiedUserName($notes->notify_user_id);
                $creation_time = date('g:i a d/m/Y', strtotime($notes->created));
                $align_class = 'right';
                if (!$notes->notes_image_id == null) {
                    $show_file = $this->notes_model->getNotesImage($notes->notes_image_id);
                    $file_name = $show_file->filename;
                    $allowedExts = array("gif", "jpeg", "jpg", "png", "PNG", "JPG", "JPEG");
                    $temp = explode(".", $file_name);
                    $extension = end($temp);
                    if (in_array($extension, $allowedExts)) {
                        //this is image
                        $tmpl .= '<div class="' . $align_class . '"><div class="userme"> :' . $showuser . '</div> <div style="float: right;" class="notes_body"><a class="fancybox" href="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '"><img height="100" width="100" src="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '"/></a></div><div style="float:right;"><span class="time-left">' . $creation_time . '</span></div> </div>';
                    } else {
                        //this is file not image
                        $tmpl .= '<div class="' . $align_class . '"><div class="userme"> :' . $showuser . '</div> <div style="float: right;" class="notes_body"><a href="' . base_url() . 'document/download_notefile/' . $show_file->filename . '">' . $file_name . '</a></div><div style="float:right"><span class="time-left1">' . $creation_time . '</span></div> </div>';
                    }


                } else {
                    $tmpl .= '<div class="' . $align_class . '"><div class="userme"> :' . $showuser . '</div> <div style="float: right;" class="notes_body">' . $notes->notes_body . '</div><div style="float:right;"> ' . $notified_user . ' <span class="time-left1">' . $creation_time . '</span></div> </div>';
                }


                $tmpl .= '<div class="clear"></div>';

            } else {
                $showuser = $notes->username;
                $creation_time = date('g:i a d/m/Y', strtotime($notes->created));
                $align_class = 'left';
                $notified_user = $this->notes_model->getNotifiedUserName($notes->notify_user_id);
                if (!$notes->notes_image_id == null) {
                    $show_file = $this->notes_model->getNotesImage($notes->notes_image_id);
                    $file_name = $show_file->filename;
                    $allowedExts = array("gif", "jpeg", "jpg", "png", "PNG", "JPG", "JPEG");
                    $temp = explode(".", $file_name);
                    $extension = end($temp);
                    if (in_array($extension, $allowedExts)) {
                        //this is image
                        $tmpl .= '<div class="' . $align_class . '"><div class="useranother">' . $showuser . ':</div> <div style="float: left;" class="notes_body"><a class="fancybox" href="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '"><img height="100" width="100" src="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '"/></a></div><div style="float:left"><span class="time-right1">' . $creation_time . '</span></div> </div>';
                    } else {
                        //this is file, not image
                        $tmpl .= '<div class="' . $align_class . '"><div class="useranother">' . $showuser . ':</div> <div style="float: left;" class="notes_body"><a href="' . base_url() . 'document/download_notefile/' . $show_file->filename . '">' . $file_name . '</a></div><div style="float:left"><span class="time-right1">' . $creation_time . '</span></div> </div>';
                    }
                } else {
                    $tmpl .= '<div class="' . $align_class . '"><div class="useranother">' . $showuser . ':</div>  <div style="float: left;" class="notes_body">' . $notes->notes_body . '</div><div style="float: left;"><span class="time-right1">' . $creation_time . '</span> ' . $notified_user . '</div> </div>';
                }

                $tmpl .= '<div class="clear"></div>';
            }
        }
        return $tmpl;

    }
}
