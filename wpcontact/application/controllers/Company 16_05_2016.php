<?php

class Company extends CI_Controller
{

    private $limit = 20;

    function __construct()
    {

        parent::__construct();

        $this->load->model('company_model', '', TRUE);
        $this->load->model('contact_model', '', TRUE);
        $this->load->model('company_notes_model', '', TRUE);
        $this->load->library(array('table', 'form_validation', 'session'));
        $this->load->library('Wbs_helper');
        $this->load->library('breadcrumbs');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('email');
        date_default_timezone_set("NZ");

        $redirect_login_page = base_url() . 'user';
        if (!$this->session->userdata('user')) {
            redirect($redirect_login_page, 'refresh');

        }

    }

    public function index()
    {
        $data['title'] = 'Company';

        //$companys = $this->company_model->company_list_search_count($sort_by,$order_by,$offset,$this->limit,$get)->result();
        $companys = $this->company_model->get_company()->result();
        $data['companys'] = $companys;
        $data['maincontent'] = $this->load->view('company', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer', $data);

    }

    public function company_list($sort_by = 'id', $order_by = 'desc', $offset = 0)
    {
        $user = $this->session->userdata('user');
		$user_id = $user->uid;
        $wp_company_id = $user->company_id;

        $company_name = $this->input->post('company_name');
        if ($this->input->post('company_sort') == '') {
            $sort_by = 'company_name';
            $order_by = 'asc';
        } else if ($this->input->post('company_sort') == 'id') {
            $sort_by = 'id';
            $order_by = 'desc';
        } else {
            $sort_by = $this->input->post('company_sort');
            $order_by = 'asc';
        }

        $data['title'] = 'Companies';
        $data['sort_by'] = $this->input->post('company_sort');

        $get = $_GET;
        $this->limit = 50;

        //$companies = $this->company_model->get_company_list();
        $companies = $this->db->query("SELECT * FROM contact_company WHERE wp_company_id={$wp_company_id} ORDER BY company_name")->result();
        //$data['companies'] = $companies;
        $this->load->library('table');
        $this->table->set_empty("");
        $this->table->set_heading('#',$company_name,'ADDRESS(S)','CONTACT NUMBER(S)','CITY',/*'COUNTRY',*/'EMAIL','TAG(s)','ACTIONS');
        foreach ($companies as $company){
            $eee = "'";
			$status = '';
            //if($company->status==1){$status = 'ACTIVE';}else{$status = 'INACTIVE';}
            $tags= isset($company->category_id) ? $company->category_id : "";
            $tags = explode('|',$tags);
            $tags = array_slice($tags,1,count($tags)-2);
            $tag_links = array();
            foreach($tags as $tag){
                if(!$tag) continue;
                $tag_name = $this->db->query("select category_name from contact_category where id = {$tag} limit 0,1 ")->row();
                $tag_name = $tag_name->category_name;
                $tag_links[] = "<a href='".base_url()."company/tag/{$tag}"."'>{$tag_name}</a>";
            }

			$appRole = $this->getUserRole(4,$user_id)->application_role_id;
			if($appRole == 1)
			{
				$status .= '<a href="'.base_url().'company/company_update/'.$company->id.'"><img class="edit_icon" src="'.base_url().'images/icons/icon_edit.png" /></a><a onclick="return confirm('.$eee.'Are you sure want to delete this Company?'.$eee.');" href="'.base_url().'company/company_delete/'.$company->id.'"><img class="edit_icon" style="margin-right:5px;" src="'.base_url().'images/delete_icon.png" /></a>';
			}
			else
			{

			}
            $this->table->add_row(
                $company->id,
                anchor(base_url().'company/company_details/'.$company->id, $company->company_name ,array('title'=>$company->company_name, 'data-toggle'=>'tooltip', 'class'=>'mytooltip')),
                $company->company_address,
                $company->contact_number,
                $company->company_city,
                /*$company->company_country,*/
                $company->company_email,
                implode(", ",$tag_links),
                $status
            );
        }
        $tmpl = array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="theme-table invoice-table">',
            'heading_row_start'   => '<tr id="header">',
            'heading_row_end'     => '</tr>',);
        $this->table->set_template($tmpl);

        $data['table'] = $this->table->generate();
        $data['maincontent'] = $this->load->view('company/company_list', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer', $data);
    }

	/*public function company_list($sort_by = 'company_name', $order_by = 'asc', $offset = 0)
	{
      
		$data['title'] = 'Company List';
		$get = $_GET;
		//print_r($get);
		$searchdata['searchvalue'] = $get;
		$this->session->set_userdata($searchdata);
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$companys = $this->company_model->company_list_search_count($sort_by,$order_by,$offset,$this->limit, $get)->result();
		$config['base_url'] = site_url("company/company_list/$sort_by/$order_by");
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$data['action'] = $config['base_url'];
		$config['total_rows'] = $this->company_model->company_list_search_count_all($get);
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = 5;
		$config['num_links'] = 10;
		$this->load->library('pagination');
		$this->pagination->initialize($config);
 		$data['pagination'] = $this->pagination->create_links();

		$this->load->library('table');
		$this->table->set_empty("");
		$this->table->set_caption($config['total_rows'] . ' Records found.');                

		$order_by = ($order_by == 'desc' ?  'asc' : 'desc');               
		$sl = $offset + 1;
		$sort_url = 'company/company_list';

		$title_link = anchor("$sort_url/company_name/$order_by/$offset", 'COMPANY NAME(S)');
		$company_name = array('data' => $title_link, 'class' => 'cls_company', 'width' =>'20%');
            
		$description = array('data' => 'Description', 'class' => 'description', 'width' =>'30%');
		$project_name = array('data' => 'Project', 'class' => 'description', 'width' =>'20%');

		$this->table->set_heading('#',$company_name,'ADDRESS(S)','CONTACT NUMBER(S)','CITY','COUNTRY','TAG(s)','STATUS');

		foreach ($companys as $company){
			$eee = "'";
			if($company->status==1){$status = 'ACTIVE';}else{$status = 'INACTIVE';}
            $tags= isset($company->category_id) ? $company->category_id : "";
            $tags = explode('|',$tags);
            $tags = array_slice($tags,1,count($tags)-2);
            $tag_links = array();
            foreach($tags as $tag){
                if(!$tag) continue;
                $tag_name = $this->db->query("select category_name from contact_category where id = {$tag} limit 0,1 ")->row();
                $tag_name = $tag_name->category_name;
                $tag_links[] = "<a href='".base_url()."company/tag/{$tag}"."'>{$tag_name}</a>";
            }
			$this->table->add_row( 
				$sl,
				anchor(base_url().'company/company_details/'.$company->id, $company->company_name ,array('title'=>$company->company_name, 'data-toggle'=>'tooltip', 'class'=>'mytooltip')),
				$company->company_address,
				$company->contact_number, 
				$company->company_city, 
				$company->company_country,
                implode(", ",$tag_links),
				$status.'<a href="'.base_url().'company/company_update/'.$company->id.'"><img class="edit_icon" src="'.base_url().'images/icons/icon_edit.png" /></a><a onclick="return confirm('.$eee.'Are you sure want to delete this Company?'.$eee.');" href="'.base_url().'company/company_delete/'.$company->id.'"><img class="edit_icon" style="margin-right:5px;" src="'.base_url().'images/delete_icon.png" /></a>'
			 ); 
			$sl++;
		}

		$tmpl = array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="theme-table invoice-table">' );
		$this->table->set_template($tmpl);
		$data['table'] = $this->table->generate();

		$data['maincontent'] = $this->load->view('company/company_list', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer', $data);
    }*/

    public function tag($tag_id = NULL) {
        $tag_info = $this->db->query("select * from contact_category where id = {$tag_id}")->row();
        $data['title'] = 'Company List With Tag: ' . $tag_info->category_name;
        $this->load->library('table');
        $this->table->set_empty("");
        //$this->table->set_caption($config['total_rows'] . ' Records found.');
        $this->table->set_heading('#', 'COMPANY NAME', 'ADDRESS(S)', 'CONTACT NUMBER(S)', 'CITY', 'COUNTRY', 'STATUS');
        $companies = $this->db->query("select * from contact_company where category_id = {$tag_id} or category_id like '%|{$tag_id}|%'" )->result();
        $sl = 1;
        foreach ($companies as $company) {
            if ($company->status == 1) {
                $status = 'ACTIVE';
            } else {
                $status = 'INACTIVE';
            }
            $this->table->add_row(
                    $sl, anchor(base_url() . 'company/company_details/' . $company->id, $company->company_name, array('title' => $company->company_name, 'data-toggle' => 'tooltip', 'class' => 'mytooltip')), $company->company_address, $company->contact_number, $company->company_city, $company->company_country, $status . '<a href="' . base_url() . 'company/company_update/' . $company->id . '"><img class="edit_icon" src="' . base_url() . 'images/icons/icon_edit.png" /></a>'
            );
            $sl++;
        }

        $tmpl = array('table_open' => '<table border="0" cellpadding="2" cellspacing="1" class="theme-table invoice-table">');
        $this->table->set_template($tmpl);
        $data['table'] = $this->table->generate();
        $data['maincontent'] = $this->load->view('company/company_list_tag', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function company_add()
    {
        $user = $this->session->userdata('user');
        $user_id = $user->uid;
		$wp_company_id = $user->company_id;

        $data['title'] = 'Add New Company';
        $data['action'] = site_url('company/company_add');

        $this->_set_rules();

        if ($this->form_validation->run() === FALSE) {
            $data['maincontent'] = $this->load->view('company/company_add', $data, true);
            $this->load->view('includes/header', $data);
            $this->load->view('home', $data);
            $this->load->view('includes/footer', $data);

        } else {

            $post = $this->input->post();
            $config['upload_path'] = UPLOAD_FILE_PATH_REQUEST_DOCUMENT;
            $config['allowed_types'] = '*';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $document_insert_id = 0;
            if ($this->upload->do_upload('upload_document')) {
                $upload_data = $this->upload->data();
                //print_r($upload_data);
                // insert data to file table
                // get latest id from frim table and insert it to loan table
                $document = array(
                    'filename' => $upload_data['file_name'],
                    'filetype' => $upload_data['file_type'],
                    'filesize' => $upload_data['file_size'],
                    'filepath' => $upload_data['full_path'],
                    //'filename_custom'=>$post['upload_document'],
                    'created' => date("Y-m-d H:i:s"),
                    'uid' => $user_id
                );
                $document_insert_id = $this->company_model->file_insert($document);
            } else {
                // print 'error in file uploading...'; 
                // print $this->upload->display_errors() ;  
            }

            $image_insert_id = 0;
            if ($this->upload->do_upload('upload_image')) {
                $upload_data = $this->upload->data();
                //print_r($upload_data); 
                // insert data to file table
                // get latest id from frim table and insert it to loan table
                $image = array(
                    'filename' => $upload_data['file_name'],
                    'filetype' => $upload_data['file_type'],
                    'filesize' => $upload_data['file_size'],
                    'filepath' => $upload_data['full_path'],
                    //'filename_custom'=>$post['upload_image'],
                    'created' => date("Y-m-d H:i:s"),
                    'uid' => $user_id
                );
                $image_insert_id = $this->company_model->file_insert($image);
            } else {
                // print 'error in image uploading...'; 
                // print $this->upload->display_errors() ;  
            }

            $company_data = array(
                'company_name' => $this->input->post('company_name'),
                'company_email' => $this->input->post('company_email'),
                'company_address' => $this->input->post('company_address'),
                'contact_number' => $this->input->post('contact_number'),
                'category_id' => "|".implode("|",$this->input->post('category_id'))."|",
                'company_image_id' => $image_insert_id,
                'company_notes' => $this->input->post('company_notes'),
                'company_city' => $this->input->post('company_city'),
                'company_country' => $this->input->post('company_country'),
                'company_website' => $this->input->post('company_website'),
                'created' => date("Y-m-d H:i:s"),
                'created_by' => $user_id,
				'wp_company_id' => $wp_company_id,
                'status' => 1
            );

            $company_id = $this->company_model->company_save($company_data);

            if(!empty($post['contact_first_name'])){
                $files = $_FILES;
                for($i = 0; $i < count($post['contact_first_name']); $i++){

                    $image_insert_id = 0;

                    if($files['contact_upload_image']['size'][$i] != 0){

                        $_FILES['userfile']['name']= $files['contact_upload_image']['name'][$i];
                        $_FILES['userfile']['type']= $files['contact_upload_image']['type'][$i];
                        $_FILES['userfile']['tmp_name']= $files['contact_upload_image']['tmp_name'][$i];
                        $_FILES['userfile']['error']= $files['contact_upload_image']['error'][$i];
                        $_FILES['userfile']['size']= $files['contact_upload_image']['size'][$i];

                        $this->upload->initialize($config);

                        if ($this->upload->do_upload()){

                            $upload_data = $this->upload->data();

                            //print_r($upload_data);
                            // insert data to file table
                            // get latest id from frim table and insert it to loan table
                            $image = array(
                                'filename'=>$upload_data['file_name'],
                                'filetype'=>$upload_data['file_type'],
                                'filesize'=>$upload_data['file_size'],
                                'filepath'=>$upload_data['full_path'],
                                //'filename_custom'=>$post['upload_image'],
                                'created'=>date("Y-m-d H:i:s"),
                                'uid'=>$user_id
                            );
                            $image_insert_id = $this->contact_model->file_insert($image);
                        }
                    }

                    $contact_data = array(
                        'wp_company_id' => $wp_company_id,
                        'contact_first_name' => $post['contact_first_name'][$i],
                        'contact_last_name' => $post['contact_last_name'][$i],
                        'company_id' =>$company_id,
                        'contact_phone_number' => $post['contact_phone_number'][$i],
                        'contact_mobile_number' => $post['contact_mobile_number'][$i],
                        'contact_email' => $post['contact_email'][$i],
                        'category_id' => $post['contact_category_id'][$i],
                        'contact_title' => $post['contact_title'][$i],
                        'contact_address' => $post['contact_address'][$i],
                        'contact_city' => $post['contact_city'][$i],
                        'contact_country' => $post['contact_country'][$i],
                        'contact_website' => $post['contact_website'][$i],
                        'contact_image_id' =>$image_insert_id,
                        'status'  => '1',
                        'contact_notes' => $post['contact_notes'][$i],
                        'created' => date("Y-m-d H:i:s"),
                        'created_by' =>$user_id
                    );
                    $this->contact_model->contact_save($contact_data,0);
                }
            }
            $this->session->set_flashdata('success-message', 'Company Successfully Added');
            redirect('company/company_list');

        }


    }

    public function company_delete($id)
    {

        // delete company
        $this->company_model->company_delete($id);
        $this->session->set_flashdata('warning-message', 'Company Successfully Removed');
        // redirect to company list page
        redirect('company/company_list');
    }


    function company_update($pid)
    {
        $user = $this->session->userdata('user');
        $user_id = $user->uid;
        $appRole = $this->getUserRole(4,$user_id)->application_role_id;
        /*only admin can update*/
        if($appRole != 1){
            show_error('You are not allowed to edit this company.', 403);
        }
        $data['title'] = 'Update Company';
        $data['action'] = site_url('company/company_update/' . $pid);
        $this->_set_rules();

        if ($this->form_validation->run() === FALSE) {
            $data['company'] = $this->company_model->get_company_detail($pid)->row();
        } else {

            $post = $this->input->post();
            $config['upload_path'] = UPLOAD_FILE_PATH_REQUEST_DOCUMENT;
            $config['allowed_types'] = '*';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $image_insert_id = 0;
            if ($this->upload->do_upload('upload_image')) {
                $upload_data = $this->upload->data();
                $image = array(
                    'filename' => $upload_data['file_name'],
                    'filetype' => $upload_data['file_type'],
                    'filesize' => $upload_data['file_size'],
                    'filepath' => $upload_data['full_path'],
                    //'filename_custom'=>$post['upload_image'],
                    'created' => date("Y-m-d H:i:s"),
                    'uid' => $user_id
                );
                $image_insert_id = $this->company_model->file_insert($image);
            } else {
                // print 'error in image uploading...'; 
                // print $this->upload->display_errors() ;  
            }

            if ($image_insert_id == 0) {
                $image_insert_id = $this->input->post('image_id');
            }

            $company_data = array(
                'company_name' => $this->input->post('company_name'),
                'company_email' => $this->input->post('company_email'),
                'company_address' => $this->input->post('company_address'),
                'contact_number' => $this->input->post('contact_number'),
                'category_id' => "|".implode("|",$this->input->post('category_id'))."|",
                'company_image_id' => $image_insert_id,
                'company_notes' => $this->input->post('company_notes'),
                'company_city' => $this->input->post('company_city'),
                'company_country' => $this->input->post('company_country'),
                'company_website' => $this->input->post('company_website'),
                'created' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            );

            $this->company_model->update($pid, $company_data);
            $this->session->set_flashdata('success-message', 'Company Successfully Updated');
            redirect('company/company_list');
        }


        $data['maincontent'] = $this->load->view('company/company_add', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer', $data);
    }

    function _set_rules()
    {
        //$this->form_validation->set_rules('compname', 'compname', 'trim|required|is_unique[company_profile.compname]');
        //$this->form_validation->set_rules('company_id', 'Company Id', 'callback_company_id');
        $this->form_validation->set_rules('company_name', 'Company Name');
        //$this->form_validation->set_rules('company_name', 'Company Name', 'required|min_length[5]|max_length[12]');
        // $this->form_validation->set_rules('email_addr_1', 'Email', 'required|valid_email|is_unique[company_profile.email_addr_1]');
    }

    public function auto_load_company()
    {


        $page = intval($_POST['p']);
        $current_page = $page - 1;
        $records_per_page = 20;
        $start = $current_page * $records_per_page;

        $result = $this->company_model->get_limited_company($start, $records_per_page);
        $html = "";

        $user = $this->session->userdata('user');
        $user_id = $user->uid;
        $app_role = $this->getUserRole(4,$user_id)->application_role_id;

        foreach ($result as $company) {
            if ($company->status == 1) {
                $stat = "ACTIVE";
            } else {
                $stat = "INACTIVE";
            }

            $edit_link = "";
            /*only admins will get edit link*/
            if($app_role == 1){
                $edit_link = '<a href="' . base_url() . 'company/company_update/' . $company->id . '"><img class="edit_icon" src="' . base_url() . 'images/icons/icon_edit.png" /></a>';
            }

            $html .= '<tr><td width="5%">' . $company->id . '</td>
						<td width="20%"><a href="' . base_url() . 'company/company_details/' . $company->id . '">' . $company->company_name . '</a></td>
						<td width="20%">' . $company->company_address . '</td>
						<td width="15%">' . $company->contact_number . '</td>
						<td width="15%">' . $company->company_city . '</td>
						<td width="15%">' . $company->company_country . '</td>
						<td width="10%">' . $stat . $edit_link.'</td></tr>';
        }

        $data = array(
            'html' => $html
        );
        echo json_encode($data);

    }


    function company_details($pid = 0)
    {

        if ($pid <= 0) {
            redirect('company/company_list');
        }


        $company = $this->company_model->get_company_detail($pid)->row();


        $data['title'] = 'Company Detail for : ' . $company->company_name;
        $data['company_id'] = $pid;
        $data['company_title'] = $company->company_name;
        $data['company'] = $company;
        $data['contacts'] = array();

        $contact_list = $this->company_model->get_contact_list($pid);
        foreach ($contact_list as $contact){
            $file_name = isset($contact->filename) ? $contact->filename : '';
            $data['contacts'][] = array(
                'name'=>$contact->contact_first_name.' '.$contact->contact_last_name,
                'title'=>$contact->contact_title,
                'filename'=>$file_name,
                'id'=>$contact->id
            );
        }

		$data['all_contacts'] = $contact_list;

		$data['maincontent'] = $this->load->view('company/company_detail', $data, true);
        $this->load->view('includes/header', $data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer', $data);
    }

    public function notes_image_tmpl($prev_notes)
    {
        $user = $this->session->userdata('user');
        $user_id = $user->uid;

        $align_class = '';
        $tmpl = '';
        if (empty($prev_notes)) {
            $tmpl = "<p>No Notes Found</a>";
        }
        foreach ($prev_notes as $notes) {


            if ($notes->notes_by == $user_id) {
                $showuser = 'Me';
                $creation_time = date('g:i a d/m/Y', strtotime($notes->created));
                $notified_user = $this->company_notes_model->getNotifiedUserName($notes->notify_user_id);
                $align_class = 'right';
                if (!$notes->notes_image_id == null) {
                    $show_file = $this->company_notes_model->getNotesImage($notes->notes_image_id);

                    $tmpl .= '<div class="' . $align_class . '"><div class="userme"> :' . $showuser . '</div> <div style="float: right;" class="notes_body"><img height="100" width="100" src="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '"/></div> </div>';
                } else {
                    $tmpl .= '<div class="' . $align_class . '"><div class="userme"> :' . $showuser . '</div> <div style="float: right;" class="notes_body">' . $notes->notes_body . '</div><div style=""><span class="time-left1">' . $creation_time . '</span>' . $notified_user . '</div> </div>';
                }


                $tmpl .= '<div class="clear"></div>';

            } else {
                $showuser = $notes->username;
                $creation_time = date('g:i a d/m/Y', strtotime($notes->created));
                $notified_user = $this->company_notes_model->getNotifiedUserName($notes->notify_user_id);
                $align_class = 'left';
                if (!$notes->notes_image_id == null) {
                    $show_file = $this->company_notes_model->getNotesImage($notes->notes_image_id);
                    $tmpl .= '<div class="' . $align_class . '"><div class="useranother">' . $showuser . ':</div> <div style="float: left;" class="notes_body"><img height="100" width="100" src="' . base_url() . 'uploads/notes/images/' . $show_file->filename . '"/></div> </div>';
                } else {
                    $tmpl .= '<div class="' . $align_class . '"><div class="useranother">' . $showuser . ':</div>  <div style="float: left;" class="notes_body">' . $notes->notes_body . '</div><div style=""><span class="time-right1">' . $creation_time . '</span>' . $notified_user . '</div></div>';
                }

                $tmpl .= '<div class="clear"></div>';
            }
        }
        return $tmpl;

    }
    function edit_note($cid){
        $note =  $this->input->post('note');
        $query = "update contact_company set company_notes = '{$note}' where id = {$cid}";
        $res = $this->db->simple_query($query);
        echo $res; exit;
    }

    private function getUserRole($appId, $uid){
        $query = "select application_role_id from users_application where user_id = {$uid} and application_id = {$appId} limit 0,1";
        return $this->db->query($query)->row();
    }

	function company_search($search_key)
	{

		$search_result = $this->company_model->get_search_result($search_key)->result();
		
		$table = '<table cellspacing="1" cellpadding="2" border="0" class="theme-table invoice-table"><caption>'.count($search_result).' Records found</caption><thead><tr><th>#</th><th width="20%" class="Title">COMPANY NAME(S)</th><th>ADDRESS(S)</th><th>CONTACT NUMBER(S)</th><th>CITY</th><th>COUNTRY</th><th>STATUS</th></tr></thead>';

		for($i=0; $i<count($search_result); $i++)
		{
			$status = $search_result[$i]->status == 1? 'ACTIVE': 'INACTIVE';
			$sl = $i+1;	
			$table.='<tr><td>'.$sl.'</td><td>'.$search_result[$i]->company_name.'</td><td>'.$search_result[$i]->company_address.'</td><td>'.$search_result[$i]->contact_number.'</td><td>'.$search_result[$i]->company_city.'</td><td>'.$search_result[$i]->company_country.'</td><td>'.$status.'</td></tr>';
		}
		$table .= '</table>';
		echo $table;
		
	}


}
