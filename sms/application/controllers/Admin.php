<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author 	: Joyonto Roy
 *	date		: 27 september, 2014
 *	Ekattor School Management System Pro
 *	http://codecanyon.net/user/Creativeitem
 *	support@creativeitem.com
 */

class Admin extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->helper(array('url','form'));
		$this->load->library(array('session','table'));
		//date_default_timezone_set("NZ");
		
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		
		if ($this->session->userdata('login_type')!='admin'){
        	redirect('login');
		}
		
		$user = $this->session->userdata('user');
		$this->company_id = $user->company_id;
    }
    
    /***ADMIN DASHBOARD***/
    function dashboard()
    {
        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('admin_dashboard');
        $this->load->view('backend/index', $page_data);
    }
    
    function dashboard_update()
    {
            
        if($this->input->post('two_option')=='1'){
            $data['time_end']      = $this->input->post('time_end');
            $data['time_start']      = $this->input->post('time_start');
            $data['day']   = $this->input->post('day');
            $data['class_id']       = $this->input->post('class_id');
            $this->db->insert('sms_class_routine', $data);
            
			redirect(base_url() . 'admin/dashboard/', 'refresh');
		}else{
			$data['notice_title']      = $this->input->post('text');
            $data['create_timestamp']      = strtotime($this->input->post('date'));
            $data['company_id']      = $this->company_id;
            $this->db->insert('sms_noticeboard', $data);
            
			redirect(base_url() . 'admin/dashboard/', 'refresh');
		}      
    }
    
    
    /****MANAGE STUDENTS CLASSWISE*****/
	function student_add()
	{
			
		$page_data['page_name']  = 'student';
		$page_data['page_title'] = get_phrase('add_student');
		$this->load->view('backend/index', $page_data);
	}
	
	function student_bulk_add($param1 = '')
    {
        $user = $this->session->userdata('user');
        $user_id = $user->uid;
        $wp_company_id = $user->company_id;

        $data['title'] = 'Add Bulk Student';
        $data['action'] = site_url('backend/student_bulk_add');

        if($param1 == 'import_excel') {
           
            $class_id = implode(',',$this->input->post('class_id'));
            
            //replacing the student_import file with the new one
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_import.xlsx');

            $post = $this->input->post();
            
            $company_id = $this->input->post('company_id');
           
            //  Include PHPExcel_IOFactory
            include "application/libraries/third_party/PHPExcel/IOFactory.php";
            //include 'PHPExcel/IOFactory.php';

            $inputFileName = 'uploads/student_import.xlsx';

            //  Read your Excel workbook
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch(Exception $e) {
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }

            //  Get worksheet dimensions
            $sheet = $objPHPExcel->getSheet(0); 
            $highestRow = $sheet->getHighestRow(); 
            $highestColumn = $sheet->getHighestColumn();

            //print_r($highestRow); exit;
            //  Loop through each row of the worksheet in turn
            //$rowData[] = array();
            for ($row = 2; $row <= $highestRow; $row++){ 
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                NULL,
                                                TRUE,
                                                FALSE);
                
                //print_r($rowData);
                for($i=0; $i<count($rowData); $i++){
                    //print_r($rowData);exit;
                    //  Insert row data array into your database of choice here
                    //echo $rowData[$i][0];exit;
                    if($rowData[$i][0] != NULL){
                        $data1['name']          =   $rowData[$i][0];
                        $data1['birthday']      =   $rowData[$i][1];
                        $data1['sex']           =   $rowData[$i][2];
                        $data1['address']       =   $rowData[$i][3];
                        $data1['phone']         =   $rowData[$i][4];
                        $data1['email']         =   $rowData[$i][5];
                        $data1['password']      =   $rowData[$i][6];
                        $data1['id_number']     =   $rowData[$i][7];
                        $data1['father_name']   =   $rowData[$i][8];
                        $data1['mother_name']   =   $rowData[$i][9];
                        $data1['income']        =   $rowData[$i][10];
                        $data1['school']        =   $rowData[$i][11];  
                        $data1['starting_grade']   =   $rowData[$i][12];                           
                    }
                    else{
                        break;
                    }
                }
                $data1['class_id']   =   $class_id;
                $data1['company_id'] =   $this->company_id;
                $this->db->insert('sms_student' , $data1);
            }

            redirect(base_url() . 'admin/student_information/', 'refresh');
        }
       
        $page_data['page_name']  = 'student_bulk_add';
        $page_data['page_title'] = get_phrase('add_bulk_student');
        $this->load->view('backend/index', $page_data);
    }
    
    // function student_bulk_add($param1 = '')
    // {
    //  if ($param1 == 'import_excel')
    //  {
    //      $class_id = implode(',',$this->input->post('class_id'));
            
    //      move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_import.xlsx');
    //      // Importing excel sheet for bulk student uploads

    //      include 'simplexlsx.class.php';
            
    //      $xlsx = new SimpleXLSX('uploads/student_import.xlsx');
            
    //      list($num_cols, $num_rows) = $xlsx->dimension();
    //      $f = 0;
    //      foreach( $xlsx->rows() as $r ) 
    //      {
    //          // Ignore the inital name row of excel file
    //          if ($f == 0)
    //          {
    //              $f++;
    //              continue;
    //          }
    //          for( $i=0; $i < $num_cols; $i++ )
    //          {
    //              if ($i == 0)        $data['name']           =   $r[$i];
    //              else if ($i == 1)   $data['birthday']       =   $r[$i];
    //              else if ($i == 2)   $data['sex']            =   $r[$i];
    //              else if ($i == 3)   $data['address']        =   $r[$i];
    //              else if ($i == 4)   $data['phone']          =   $r[$i];
    //              else if ($i == 5)   $data['email']          =   $r[$i];
    //              else if ($i == 6)   $data['password']       =   $r[$i];
    //              else if ($i == 7)   $data['id_number']          =   $r[$i];
    //          }
    //          $data['class_id']   =   $class_id;
    //          $data['company_id'] =   $this->company_id;
                
    //          $this->db->insert('sms_student' , $data);
    //          //print_r($data);
    //      }
    //      redirect(base_url() . 'admin/student_information/', 'refresh');
    //  }
    //  $page_data['page_name']  = 'student_bulk_add';
    //  $page_data['page_title'] = get_phrase('add_bulk_student');
    //  $this->load->view('backend/index', $page_data);
    // }
	
	function student_information($class_id = '')
	{
			
		$page_data['page_name']  	= 'student_information';
		$page_data['page_title'] 	= get_phrase('student_information');
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
	}
	
	function student_marksheet($class_id = '')
	{	
		$page_data['page_name']  = 'student_marksheet';
		$page_data['page_title'] 	= get_phrase('student_marksheet');
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
	}
	
    function student($param1 = '', $param2 = '', $param3 = '')
    {
        if ($param1 == 'create') {
        	$class_id = implode(',',$this->input->post('class_id'));
        	
            $data['name']       = $this->input->post('name');
            $data['birthday']   = $this->input->post('birthday');
            $data['sex']        = $this->input->post('sex');
            $data['address']    = $this->input->post('address');
            $data['phone']      = $this->input->post('phone');
            $data['email']      = $this->input->post('email');
            $data['father_name']        = $this->input->post('father_name');
            $data['mother_name']        = $this->input->post('mother_name');
            $data['income']             = $this->input->post('incomesource');
            $data['school']             = $this->input->post('school');
            $data['starting_grade']     = $this->input->post('starting_grade');
            $data['class_id']   = $class_id;
            if ($this->input->post('section_id') != '') {
                $data['section_id'] = $this->input->post('section_id');
            }
            $data['company_id']  = $this->company_id;
            $data['id_number']       = $this->input->post('id_number');
            $this->db->insert('sms_student', $data);
            $student_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $student_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('sms_student', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'admin/student_add/', 'refresh');
        }
        if ($param1 == 'do_update') {
        	$class_id = implode(',',$this->input->post('class_id'));
        	
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['father_name']        = $this->input->post('father_name');
            $data['mother_name']        = $this->input->post('mother_name');
            $data['income']             = $this->input->post('incomesource');
            $data['school']             = $this->input->post('school');
            $data['starting_grade']     = $this->input->post('starting_grade');
            $data['class_id']    = $class_id;
            $data['section_id']  = $this->input->post('section_id');
            //$data['parent_id']   = $this->input->post('parent_id');
            $data['id_number']        = $this->input->post('id_number');
            
            $this->db->where('student_id', $param2);
            $this->db->update('sms_student', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $param2 . '.jpg');
            $this->crud_model->clear_cache();
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/student_information/', 'refresh');
        } 
		
        if ($param1 == 'delete') {
            $this->db->where('student_id', $param2);
            $this->db->delete('sms_student');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/student_information/', 'refresh');
        }
    }

    /*Update attendance*/
    function att_update($attendance_id,$status){
        $data['status'] = $status;
        $this->db->where('attendance_id', $attendance_id);
        $this->db->update('sms_attendance', $data);
    }

    /*If student 4 days dont attend class, They will be listed*/
    function student_not_attend()
    {
        $this->db->select('sms_student.*,sms_class.name as class_name');
        $this->db->join('sms_class','sms_class.class_id=sms_student.class_id');
        $this->db->where('sms_student.company_id',$this->company_id);
        $students = $this->db->get('sms_student')->result();
        
        $row = '<table border="1" width="100%" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <td style="font-weight: bold;">Pod</td>
                            <td style="font-weight: bold;">ID Number</td>
                            <td style="font-weight: bold;">Student Name</td>
                            <td style="font-weight: bold;">Gender</td>
                            <td style="font-weight: bold;">Phone</td>
                            <td style="font-weight: bold;">Email</td>
                        </tr>
                    </thead>
                    <tbody>';
        foreach($students as $student){
            $student_id = $student->student_id;
            
            $this_week = date('w');
            $week_start = date('Y-m-d', strtotime('+'.(1-$this_week).' days'));
            $week_end = date('Y-m-d', strtotime('+'.(6-$this_week).' days'));

            $this->db->where('date <= ', $week_end);
            $this->db->where('date >= ', $week_start);
            $this->db->where('student_id',$student_id);
            $this->db->where('status','2');
            $attendance = $this->db->get('sms_attendance')->result();
            echo $this->db->last_query();
            if(count($attendance)>=4){
                $row .= '<tr>
                            <td>'.$student->class_name.'</td>
                            <td>'.$student->id_number.'</td>
                            <td>'.$student->name.'</td>
                            <td>'.$student->sex.'</td>
                            <td>'.$student->phone.'</td>
                            <td>'.$student->email.'</td>
                        </tr>';
            }       
        }
        $row .= '</tbody>
                </table>';
        echo $row;
    }
    
    function students_print()
    {
        
        $page_data['page_title'] 	= get_phrase('students_information'); 
        $page_data['page_name']  = 'student_print';   
        $this->load->view('backend/index_print', $page_data);
    }
    
    function students_pdf()
    {          
        $page_title	= get_phrase('students_information'); 
		
		$html ='<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#fff">
			<thead>
			    <tr bgcolor="#818285" style="color:#fff;font-weight: bold;">
			        <th><div>'.get_phrase('ID_Number').'</div></th>
		            <th><div>'.get_phrase('photo').'</div></th>
		            <th><div>'.get_phrase('name').'</div></th>
		            <th><div>'.get_phrase('address').'</div></th>
		            <th><div>'.get_phrase('email').'</div></th>
			    </tr>
			</thead>
			<tbody>';
			
        $this->db->order_by('student_id','DESC');
        $students   =   $this->db->get_where('sms_student',array('company_id'=>$this->company_id))->result_array();
        $i = 1;

        foreach($students as $row){
        	if($i==1){
				$style = 'bgcolor="#d1d2d4"';
			}else{
				$style = 'bgcolor="#e7e7e8"';
			}
	        $html .='<tr '.$style.'>
	        <td>'.$row['id_number'].'</td>
	        <td><img width="40" height="40" src="'.$this->crud_model->get_image_url("student",$row["student_id"]).'"></td>
	        <td>'.$row['name'].'</td>
	        <td>'.$row['address'].'</td>
	        <td>'.$row['email'].'</td>
	    	</tr>';

	    	if($i==2){
				$i = 1;
			}else{
				$i++;
			}	
    	}
    	
		$html .='</tbody>
		</table>';
		
		$this->helper->make_pdf($html,$page_title);
    }

    /* Export PDF Per Pods - Student Details */
    public function details_pod_pdf($pod = NULL)
    {
    $page_title = get_phrase('student details on pod'); 

    $this->db->select('sms_student.*');
        $this->db->join('sms_class', 'sms_class.class_id=sms_student.class_id');

        $where = array('sms_class.company_id' => $this->company_id, 'sms_class.class_id' => $pod);
        $this->db->where($where);

        $students = $this->db->get('sms_student')->result();
        $class= $this->db->get_where('sms_class', array('class_id' => $pod))->row();
        $html = '';

        $html .= '<h1>'. $class->name .'</h1>';
  
            $html .= '<table width="100%" border="1" cellpadding="4" cellspacing="0">';
            
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>Student ID</th>';
            $html .= '<th>Student Photo</th>';
            $html .= '<th>Student Name</th>';
            $html .= '<th>Student Birthday</th>';
            $html .= '<th>Student Gender</th>';
            $html .= '<th>Student Phone</th>';
            $html .= '<th>Student Email</th>';
            $html .= '<th>Student Address</th>';
            $html .= "<th>Father's Name</th>";
            $html .= "<th>Mother's Name</th>";
            $html .= '<th>Source of Income</th>';
            $html .= '<th>School</th>';
            $html .= '<th>Starting Grade</th>';
            $html .= '</tr>';
            $html .= '</thead>';

            $html .= '<tbody>';

    foreach($students as $student){

        $html .= '<tr nobr="true">';
        $html .= '<td>'.$student->student_id.'</td>';
        $html .= '<td><img width="40" height="40" src="'.$this->crud_model->get_image_url("student",$student->student_id).'"></td>';
        $html .= '<td>'.$student->name.'</td>';
        $html .= '<td>'.$student->birthday.'</td>';
        $html .= '<td>'.$student->sex.'</td>';
        $html .= '<td>'.$student->phone.'</td>';
        $html .= '<td>'.$student->email.'</td>';
        $html .= '<td>'.$student->address.'</td>';
        $html .= '<td>'.$student->father_name.'</td>';
        $html .= '<td>'.$student->mother_name.'</td>';  
        $html .= '<td>'.$student->income.'</td>';   
        $html .= '<td>'.$student->school.'</td>';   
        $html .= '<td>'.$student->starting_grade.'</td>';   
        $html .= '</tr>';

    }
            
            $html .= '</tbody>';
            
            $html .= '</table>';

    $this->helper->make_pdf($html,$page_title);

    }

     /* Export PDF Per Pods - Exam Marks */
       function marks_exam_pod_pdf($pod = NULL)
       {
                $page_title = get_phrase('student marks on pod'); 

                $class= $this->db->get_where('sms_class', array('class_id' => $pod))->row();
                $html = '';

                $html .= '<h1>'. $class->name .'</h1>';

                $class_id = $pod;

                $this->db->select('sms_exam.*');
            $this->db->join('sms_exam', 'sms_exam.exam_id=sms_mark.exam_id');
            $this->db->where('sms_mark.class_id', $class_id);
            $this->db->group_by('sms_mark.exam_id');
            $exams = $this->db->get('sms_mark')->result();
            
            $html .= '<table width="100%" border="1" cellpadding="4" cellspacing="0">';
            
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>Student Name</th>';
            foreach($exams as $exam){
                $html .= '<th>'.$exam->name.'</th>';
            }
            $html .= '</tr>';
            $html .= '</thead>';
            
            $this->db->select('sms_student.*');
            $this->db->join('sms_student', 'sms_student.student_id=sms_mark.student_id');
            $this->db->where('sms_mark.class_id', $class_id);
            $this->db->group_by('sms_mark.student_id');
            $students = $this->db->get('sms_mark')->result();

            $html .= '<tbody>';

            foreach($students as $student){
                $html .= '<tr>';
                $html .= '<td>'.$student->name.'</td>';
                
                $student_id = $student->student_id;
                
                foreach($exams as $exam){
                    $exam_id = $exam->exam_id;
                    $this->db->select('sms_mark.mark_obtained');
                    
                    $this->db->where('sms_mark.class_id', $class_id);
                    $this->db->where('sms_mark.student_id', $student_id);
                    $this->db->where('sms_mark.exam_id', $exam_id);
                    $student_mark = $this->db->get('sms_mark')->row();
                    //echo $this->db->last_query();
                    
                    if($student_mark->mark_obtained>'0'){
                        $html .= '<td>'.$student_mark->mark_obtained.'</td>';
                    }else{
                        $html .= '<td></td>';
                    }
                }
                
                $html .= '</tr>';
            }

                $html .= '</tbody>';
            
            $html .= '</table>';

                $this->helper->make_pdf($html,$page_title);
       }

     /* Export Excel Per POD - Exam Marks */
     public function marks_exam_pod_excel($pod = NULL)
     {
        $a_z = array('A');
        $current = 'A';
        while ($current != 'ZZZ') {
            $a_z[] = ++$current;
        }
        
        //load our new PHPExcel library
        $this->load->library('excel');  
        
        $this->db->select('sms_class.*');
        $this->db->join('sms_mark', 'sms_mark.class_id=sms_class.class_id');
        $this->db->where('sms_class.company_id', $this->company_id);
        $this->db->group_by('sms_mark.class_id');
        $classes = $this->db->get('sms_class')->result();
        
        $class= $this->db->get_where('sms_class', array('class_id' => $pod))->row();

        $this->excel->getActiveSheet()->setTitle('Marks');
        $this->excel->setActiveSheetIndex(0);
        
        $j = 1;
        $i = 0;             
            
            $class_id = $class->class_id;
            
            $this->excel->getActiveSheet()->setCellValue($a_z[$i].''.$j, $class->name);
            
            //change the font size
            $this->excel->getActiveSheet()->getStyle('A'.$j)->getFont()->setSize(14);
            //make the font become bold
            $this->excel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
            
            $this->db->select('sms_exam.*');
            $this->db->join('sms_exam', 'sms_exam.exam_id=sms_mark.exam_id');
            $this->db->where('sms_mark.class_id', $class_id);
            $this->db->group_by('sms_mark.exam_id');
            $exams = $this->db->get('sms_mark')->result();
            
            $j++;
            $this->excel->getActiveSheet()->setCellValue($a_z[$i].''.$j, 'Student Name');
            
            //change the font size
            $this->excel->getActiveSheet()->getStyle('A'.$j)->getFont()->setSize(14);
            //make the font become bold
            $this->excel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
            
            $k = 1;
            foreach($exams as $exam){
                $this->excel->getActiveSheet()->setCellValue($a_z[$k].''.$j, $exam->name);
                $k++;
            }

            $k = 1;
            $this->db->select('sms_student.*');
            $this->db->join('sms_student', 'sms_student.student_id=sms_mark.student_id');
            $this->db->where('sms_mark.class_id', $class_id);
            $this->db->group_by('sms_mark.student_id');
            $students = $this->db->get('sms_mark')->result();
            
            $html .= '<tbody>';

            foreach($students as $student){

                $j++;
                $this->excel->getActiveSheet()->setCellValue($a_z[$i].''.$j, $student->name);
                
                $student_id = $student->student_id;
                $k = 1;
                foreach($exams as $exam){
                    $exam_id = $exam->exam_id;
                    $this->db->select('sms_mark.mark_obtained');
                    $this->db->where('sms_mark.class_id', $class_id);
                    $this->db->where('sms_mark.student_id', $student_id);
                    $this->db->where('sms_mark.exam_id', $exam_id);
                    $student_mark = $this->db->get('sms_mark')->row();
                    
                    if($student_mark->mark_obtained>'0'){
                        $this->excel->getActiveSheet()->setCellValue($a_z[$k].''.$j, $student_mark->mark_obtained);
                    }else{
                        $this->excel->getActiveSheet()->setCellValue($a_z[$k].''.$j, '');
                    }
                    $k++;
                }
                $k = 1;
            }

        $filename = 'marks.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
     }

     /* Export Excel Per POD - Student Details */
     public function details_pod_excel($pod = NULL)
       {
        //load our new PHPExcel library
        $this->load->library('excel');  

        $this->db->select('sms_student.*');
        $this->db->join('sms_class', 'sms_class.class_id=sms_student.class_id');

        $where = array('sms_class.company_id' => $this->company_id, 'sms_class.class_id' => $pod);
        $this->db->where($where);

        $students = $this->db->get('sms_student')->result_array();
        $class= $this->db->get_where('sms_class', array('class_id' => $pod))->row();

        foreach(range('A','K') as $columnID) {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $active_sheet = $this->excel->getActiveSheet();
        //name the worksheet
        $active_sheet->setTitle('Student Info');
        //set cell A1 content with some text

            /* Title */
            $active_sheet->setCellValue('A1', $class->name);
            //change the font size
            $active_sheet->getStyle('A1')->getFont()->setSize(26);
            //make the font become bold
            $active_sheet->getStyle('A1')->getFont()->setBold(true);

            /* Header */
            $active_sheet->setCellValue('A3', 'Student Name');
            $active_sheet->setCellValue('B3', 'Student ID');
            $active_sheet->setCellValue('C3', 'Birthday');
            $active_sheet->setCellValue('D3', 'Gender');
            $active_sheet->setCellValue('E3', 'Phone');
            $active_sheet->setCellValue('F3', 'Email');
            $active_sheet->setCellValue('G3', "Father's Name");
            $active_sheet->setCellValue('H3', "Mothers's Name");
            $active_sheet->setCellValue('I3', 'Source Of Income');
            $active_sheet->setCellValue('J3', 'School');
            $active_sheet->setCellValue('K3', 'Starting Grade');

            //change the font size
            $active_sheet->getStyle('A3:K3')->getFont()->setSize(14);
            //make the font become bold
            $active_sheet->getStyle('A3:K3')->getFont()->setBold(true);

            /* Students */
        $i=4;
        foreach ($students as $row)
        {
            $active_sheet->setCellValue('A'.$i, $row['name']);
            $active_sheet->setCellValue('B'.$i, $row['student_id']);
            $active_sheet->setCellValue('C'.$i, $row['birthday']);
            $active_sheet->setCellValue('D'.$i, $row['sex']);
            $active_sheet->setCellValue('E'.$i, $row['phone']);
            $active_sheet->setCellValue('F'.$i, $row['email']);
            $active_sheet->setCellValue('G'.$i, $row['father_name']);
            $active_sheet->setCellValue('H'.$i, $row['mother_name']);
            $active_sheet->setCellValue('I'.$i, $row['income']);
            $active_sheet->setCellValue('J'.$i, $row['school']);
            $active_sheet->setCellValue('K'.$i, $row['starting_grade']);
            $i++;
        }

        $filename = 'student_details.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
         }
    

    
     /****MANAGE PARENTS CLASSWISE*****/
    function parent($param1 = '', $param2 = '', $param3 = '')
    {
            
        if ($param1 == 'create') {
            $data['name']        			= $this->input->post('name');
            $data['children']        		= $this->input->post('children');
            $data['email']       			= $this->input->post('email');
            $data['password']    			= $this->input->post('password');
            $data['phone']       			= $this->input->post('phone');
            $data['address']     			= $this->input->post('address');
            $data['profession']  			= $this->input->post('profession');
            $this->db->insert('sms_parent', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('parent', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'admin/parent/', 'refresh');
        }
        if ($param1 == 'edit') {
            $data['name']                   = $this->input->post('name');
            $data['children']        		= $this->input->post('children');
            $data['email']                  = $this->input->post('email');
            $data['phone']                  = $this->input->post('phone');
            $data['address']                = $this->input->post('address');
            $data['profession']             = $this->input->post('profession');
            $this->db->where('parent_id' , $param2);
            $this->db->update('sms_parent' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/parent/', 'refresh');
        }
        if ($param1 == 'delete') {
            $this->db->where('parent_id' , $param2);
            $this->db->delete('sms_parent');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/parent/', 'refresh');
        }
        $page_data['page_title'] 	= get_phrase('all_parents');
        $page_data['page_name']  = 'parent';
        $this->load->view('backend/index', $page_data);
    }
    
    function parent_print()
    {
        $page_data['page_title'] 	= get_phrase('parents_information'); 
        $page_data['page_name']  = 'parent_print';   
        $this->load->view('backend/index_print', $page_data);
    }
    
    function parent_pdf()
    {
         
        $page_title	= get_phrase('parents_information'); 
		
		$html ='<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#fff">
			<thead>
			    <tr bgcolor="#818285" style="color:#fff;font-weight: bold;">
			        <th><div>'.get_phrase('name').'</div></th>
			        <th><div>'.get_phrase('children').'</div></th>
			        <th><div>'.get_phrase('email').'</div></th>
			        <th><div>'.get_phrase('phone').'</div></th>
			        <th><div>'.get_phrase('profession').'</div></th>
			    </tr>
			</thead>
			<tbody>';
			
        $this->db->order_by('parent_id','DESC');
        $parents   =   $this->db->get('sms_parent' )->result_array();
        $i = 1;
        foreach($parents as $row){
        	if($i==1){
				$style = 'bgcolor="#d1d2d4"';
			}else{
				$style = 'bgcolor="#e7e7e8"';
			}
	        $html .='<tr '.$style.'>
	        <td>'.$row['name'].'</td>
	        <td>'.$row['children'].'</td>
	        <td>'.$row['email'].'</td>
	        <td>'.$row['phone'].'</td>
	        <td>'.$row['profession'].'</td>
	    	</tr>';
	    	if($i==2){
				$i = 1;
			}else{
				$i++;
			}	
    	}
    	
		$html .='</tbody>
		</table>';
		
		$this->helper->make_pdf($html,$page_title);
    }
	
    
    /****MANAGE TEACHERS*****/
    function teacher($param1 = '', $param2 = '', $param3 = '')
    {
          
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['password']    = $this->input->post('password');
            $data['company_id']    = $this->company_id;
            $this->db->insert('sms_teacher', $data);
            $teacher_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $teacher_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('sms_teacher', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'admin/teacher/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('teacher_id', $param2);
            $this->db->update('sms_teacher', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/teacher/', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_teacher_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('teacher', array(
                'teacher_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('teacher_id', $param2);
            $this->db->delete('sms_teacher');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/teacher/', 'refresh');
        }
        $page_data['teachers']   = $this->db->get('sms_teacher')->result_array();
        $page_data['page_name']  = 'teacher';
        $page_data['page_title'] = get_phrase('manage_teacher');
        $this->load->view('backend/index', $page_data);
    }
    
    function teachers_pdf()
    {          
        $page_title	= 'Teacher Information'; 
		
		$html ='<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#fff">
			<thead>
			    <tr bgcolor="#818285" style="color:#fff;font-weight: bold;">
		            <th><div>'.get_phrase('photo').'</div></th>
		            <th><div>'.get_phrase('name').'</div></th>
		            <th><div>'.get_phrase('address').'</div></th>
		            <th><div>'.get_phrase('email').'</div></th>
			    </tr>
			</thead>
			<tbody>';
			
        $this->db->order_by('teacher_id','DESC');
        $teachers   =   $this->db->get_where('sms_teacher',array('company_id'=>$this->company_id))->result_array();
        $i = 1;
        foreach($teachers as $row){
        	if($i==1){
				$style = 'bgcolor="#d1d2d4"';
			}else{
				$style = 'bgcolor="#e7e7e8"';
			}
	        $html .='<tr '.$style.'>
	        <td><img width="40" height="40" src="'.$this->crud_model->get_image_url("teacher",$row["teacher_id"]).'"></td>
	        <td>'.$row['name'].'</td>
	        <td>'.$row['address'].'</td>
	        <td>'.$row['email'].'</td>
	    	</tr>';
	    	if($i==2){
				$i = 1;
			}else{
				$i++;
			}	
    	}
    	
		$html .='</tbody>
		</table>';
		
		$this->helper->make_pdf($html,$page_title);
    }
    
    /****MANAGE SUBJECTS*****/
    function subject($param1 = '', $param2 = '' , $param3 = '')
    {
            
        if ($param1 == 'create') {
            $data['name']       = $this->input->post('name');
            $data['class_id']   = $this->input->post('class_id');
            $data['teacher_id'] = $this->input->post('teacher_id');
            $this->db->insert('sms_subject', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'admin/subject/'.$data['class_id'], 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']       = $this->input->post('name');
            $data['class_id']   = $this->input->post('class_id');
            $data['teacher_id'] = $this->input->post('teacher_id');
            
            $this->db->where('subject_id', $param2);
            $this->db->update('sms_subject', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/subject/'.$data['class_id'], 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('sms_subject', array(
                'subject_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('subject_id', $param2);
            $this->db->delete('sms_subject');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/subject/'.$param3, 'refresh');
        }
		 $page_data['class_id']   = $param1;
        $page_data['subjects']   = $this->db->get_where('subject' , array('class_id' => $param1))->result_array();
        $page_data['page_name']  = 'subject';
        $page_data['page_title'] = get_phrase('manage_subject');
        $this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE CLASSES*****/
    function classes($param1 = '', $param2 = '')
    {
            
        if ($param1 == 'create') {
            $data['name']         = $this->input->post('name');
            $data['name_numeric'] = $this->input->post('name_numeric');
            $data['teacher_id']   = $this->input->post('teacher_id');
            $data['company_id']   = $this->company_id;
            $this->db->insert('sms_class', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'admin/classes/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']         = $this->input->post('name');
            $data['name_numeric'] = $this->input->post('name_numeric');
            $data['teacher_id']   = $this->input->post('teacher_id');
            
            $this->db->where('class_id', $param2);
            $this->db->update('sms_class', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/classes/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('sms_class', array(
                'class_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('class_id', $param2);
            $this->db->delete('sms_class');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/classes/', 'refresh');
        } 
        
        $page_data['page_name']  = 'class';
        $page_data['page_title'] = get_phrase('manage_class');
        $this->load->view('backend/index', $page_data);
    }

    /****MANAGE SECTIONS*****/
    function section($class_id = '')
    {
           
        // detect the first class
        if ($class_id == '')
            $class_id           =   $this->db->get('sms_class')->first_row()->class_id;

        $page_data['page_name']  = 'section';
        $page_data['page_title'] = get_phrase('manage_sections');
        $page_data['class_id']   = $class_id;
        $this->load->view('backend/index', $page_data);    
    }

    function sections($param1 = '' , $param2 = '')
    {
        
        if ($param1 == 'create') {
            $data['name']       =   $this->input->post('name');
            $data['nick_name']  =   $this->input->post('nick_name');
            $data['class_id']   =   $this->input->post('class_id');
            $data['teacher_id'] =   $this->input->post('teacher_id');
            $this->db->insert('sms_section' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'admin/section/' . $data['class_id'] , 'refresh');
        }

        if ($param1 == 'edit') {
            $data['name']       =   $this->input->post('name');
            $data['nick_name']  =   $this->input->post('nick_name');
            $data['class_id']   =   $this->input->post('class_id');
            $data['teacher_id'] =   $this->input->post('teacher_id');
            $this->db->where('section_id' , $param2);
            $this->db->update('sms_section' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/section/' . $data['class_id'] , 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('section_id' , $param2);
            $this->db->delete('sms_section');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/section' , 'refresh');
        }
    }

    function get_class_section($class_id)
    {
        $sections = $this->db->get_where('sms_section' , array(
            'class_id' => $class_id
        ))->result_array();
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_class_subject($class_id)
    {
        $subjects = $this->db->get_where('sms_subject' , array(
            'class_id' => $class_id
        ))->result_array();
        foreach ($subjects as $row) {
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
        }
    }

    /****MANAGE EXAMS*****/
    function exam($param1 = '', $param2 = '' , $param3 = '')
    {
        
		$config['upload_path'] = './uploads/exam_document/';
		$config['allowed_types'] = 'pdf|docx|xlsx';
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		
        if ($param1 == 'create') {	
			$file = '';
   			if ($this->upload->do_upload('document')){
	            $upload_data = $this->upload->data(); 
	            $file = $upload_data['file_name'];
        	}
			if($this->input->post('recurring_yes_no')){
				$recurring_yes_no = 'Yes';
			}else{
				$recurring_yes_no = 'No';
			}
            $data['name']    = $this->input->post('name');
            $data['date']    = $this->input->post('date');
            $data['category']    = $this->input->post('category');
            $data['class_id']    = $this->input->post('class_id');
            $data['comment'] = $this->input->post('comment');
            $data['recurring_yes_no']    = $recurring_yes_no;
            $data['recurring'] = $this->input->post('recurring');
            $data['document'] = $file;
            $data['company_id'] = $this->company_id;
            
            $this->db->insert('sms_exam', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'admin/exam/', 'refresh');
        }
        if ($param1 == 'edit' && $param2 == 'do_update') {
        	$file = $this->input->post('edit_document');
   			if ($this->upload->do_upload('document')){
	            $upload_data = $this->upload->data(); 
	            $file = $upload_data['file_name'];
        	}
        	if($this->input->post('recurring_yes_no')){
				$recurring_yes_no = 'Yes';
			}else{
				$recurring_yes_no = 'No';
			}
			
            $data['name']    = $this->input->post('name');
            $data['date']    = $this->input->post('date');
            $data['category']    = $this->input->post('category');
            $data['class_id']    = $this->input->post('class_id');
            $data['comment'] = $this->input->post('comment');
            $data['recurring_yes_no']    = $recurring_yes_no;
            $data['recurring'] = $this->input->post('recurring');
            $data['document'] = $file;
            
            $this->db->where('exam_id', $param3);
            $this->db->update('sms_exam', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/exam/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('sms_exam', array(
                'exam_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('exam_id', $param2);
            $this->db->delete('sms_exam');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/exam/', 'refresh');
        }
        if($param1 == 'search'){
        	$sesData['exam_year']= $this->input->post('year');
            $sesData['exam_name_search']= $this->input->post('name_search');
            $sesData['exam_comment_search']= $this->input->post('comment_search');
            $sesData['exam_category_search']= $this->input->post('category_search');
            $this->session->set_userdata($sesData); 
            if($this->session->userdata('exam_year')!=''){
                $this->db->like('date', $this->input->post('year') );
                
            }
            if($this->session->userdata('exam_name_search')!=''){
                //$this->db->like('date', $this->input->post('all_search'));
                $this->db->like('sms_exam.name', $this->input->post('name_search'));
                //$this->db->like('comment', $this->input->post('all_search'));
            }

            if($this->session->userdata('exam_comment_search')!=''){
                //$this->db->like('date', $this->input->post('all_search'));
                //$this->db->like('sms_exam.name', $this->input->post('all_search'));
                $this->db->like('comment', $this->input->post('comment_search'));
            }

            if($this->session->userdata('exam_category_search')!=''){
                //$this->db->like('date', $this->input->post('all_search'));
                //$this->db->like('sms_exam.name', $this->input->post('all_search'));
                $this->db->like('category', $this->input->post('category_search'));
            }
		}
		$this->db->select('sms_exam.*, sms_class.name as class_name');
		$this->db->join('sms_class', 'sms_class.class_id = sms_exam.class_id','left');
		$this->db->where('sms_exam.company_id', $this->company_id);
        $this->db->order_by('exam_id','DESC');
        $exams = $this->db->get('sms_exam')->result_array();
        $page_data['exams']      = $exams;
        $page_data['page_name']  = 'exam';
        $page_data['page_title'] = get_phrase('manage_exam');
        $this->load->view('backend/index', $page_data);
    }

    /****** SEND EXAM MARKS VIA SMS ********/
    function exam_marks_sms($param1 = '' , $param2 = '')
    {
        
        if ($param1 == 'send_sms') {

            $exam_id    =   $this->input->post('exam_id');
            $class_id   =   $this->input->post('class_id');
            $receiver   =   $this->input->post('receiver');

            // get all the students of the selected class
            $students = $this->db->get_where('sms_student' , array(
                'class_id' => $class_id
            ))->result_array();
            // get the marks of the student for selected exam
            foreach ($students as $row) {
                if ($receiver == 'student')
                    $receiver_phone = $row['phone'];
                if ($receiver == 'parent' && $row['parent_id'] != '') 
                    $receiver_phone = $this->db->get_where('parent' , array('parent_id' => $row['parent_id']))->row()->phone;
                

                $this->db->where('exam_id' , $exam_id);
                $this->db->where('student_id' , $row['student_id']);
                $marks = $this->db->get('sms_mark')->result_array();
                $message = '';
                foreach ($marks as $row2) {
                    $subject       = $this->db->get_where('sms_subject' , array('subject_id' => $row2['subject_id']))->row()->name;
                    $mark_obtained = $row2['mark_obtained'];  
                    $message      .= $row2['student_id'] . $subject . ' : ' . $mark_obtained . ' , ';
                    
                }
                // send sms
                $this->sms_model->send_sms( $message , $receiver_phone );
            }
            $this->session->set_flashdata('flash_message' , get_phrase('message_sent'));
            redirect(base_url() . 'admin/exam_marks_sms' , 'refresh');
        }
                
        $page_data['page_name']  = 'exam_marks_sms';
        $page_data['page_title'] = get_phrase('send_marks_by_sms');
        $this->load->view('backend/index', $page_data);
    }

    /****MANAGE EXAM MARKS*****/
    function marks($exam_id = '', $class_id = '', $subject_id = '')
    {
        
        if ($this->input->post('operation') == 'selection') {
            $page_data['exam_id']    = $this->input->post('exam_id');
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['subject_id'] = $this->input->post('subject_id');
            
            if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0) {
                redirect(base_url() . 'admin/marks/' . $page_data['exam_id'] . '/' . $page_data['class_id'], 'refresh');
            } else {
                $this->session->set_flashdata('mark_message', 'Choose exam, class and subject');
                redirect(base_url() . 'admin/marks/', 'refresh');
            }
        }
        if ($this->input->post('operation') == 'all_update') {
        	
        	$_post = $this->input->post();
        	$mark_id = $_post['mark_id'];
        	
        	for($i=0; $i<count($mark_id); $i++){
				$data['mark_obtained'] = $_post['mark_obtained'][$i];
	            $data['grade']       = $_post['grade'][$i];
	            $data['comment']       = $_post['comment'][$i];
	            
	            $this->db->where('mark_id', $_post['mark_id'][$i]);
	            $this->db->update('sms_mark', $data);
			}
            
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/marks/' . $this->input->post('exam_id') . '/' . $this->input->post('class_id'), 'refresh');
        }
        $page_data['exam_id']    = $exam_id;
        $page_data['class_id']   = $class_id;
        $page_data['subject_id'] = $subject_id;
        
        $page_data['page_info'] = 'Exam marks';
        
        $page_data['page_name']  = 'marks';
        $page_data['page_title'] = get_phrase('manage_exam_marks');
        $this->load->view('backend/index', $page_data);
    }
    
    function mark_update($mark_id,$mark_obtained,$grade,$comment){
    	//echo 'hello';
    	$data['mark_obtained'] = $mark_obtained;
        $data['grade']       = $grade;
        $data['comment']       = urldecode($comment);
        //print_r($data);
        $this->db->where('mark_id', $mark_id);
        $this->db->update('sms_mark', $data);
	}
    
    function load_class_by_exam($exam_id){
    	$this->db->select('sms_class.class_id,sms_class.name');
    	$this->db->join('sms_class', 'sms_class.class_id=sms_exam.class_id');
    	$this->db->where('exam_id', $exam_id);
		$classes = $this->db->get('sms_exam')->result();
		$row = '<option value="">'.get_phrase('select_a_class').'</option>';
		foreach($classes as $class){
			$row .= '<option value="'.$class->class_id.'">'.$class->name.'</option>';
		}
		echo $row;
	}
	
	function marks_exam_pdf()
    {
        
        $page_title	= get_phrase('exam_marks'); 
        
        $this->db->select('sms_class.*');
    	$this->db->join('sms_mark', 'sms_mark.class_id=sms_class.class_id');
    	$this->db->where('sms_class.company_id', $this->company_id);
    	$this->db->group_by('sms_mark.class_id');
    	$classes = $this->db->get('sms_class')->result();
    	
    	$html = '';
    	foreach($classes as $class){
			$class_id = $class->class_id;
			$html .= '<h1>'.$class->class_name.'</h1>';
			
			$this->db->select('sms_exam.name as exam_name, sms_exam.exam_id');
	    	$this->db->join('sms_exam', 'sms_exam.exam_id=sms_mark.exam_id');
	    	$this->db->where('sms_mark.class_id', $class_id);
	    	$this->db->group_by('sms_mark.exam_id');
	    	$exams = $this->db->get('sms_mark')->result();
	    	
	    	$html .= '<table width="100%" border="1" cellpadding="4" cellspacing="0">';
	    	
	    	$html .= '<thead>';
	    	$html .= '<tr>';
	    	$html .= '<th>Student Name</th>';
	    	foreach($exams as $exam){
				$html .= '<th>'.$exam->exam_name.'</th>';
			}
	    	$html .= '</tr>';
	    	$html .= '</thead>';
	    	
	    	$this->db->select('sms_student.*');
	    	$this->db->join('sms_student', 'sms_student.student_id=sms_mark.student_id');
	    	$this->db->where('sms_mark.class_id', $class_id);
	    	$this->db->group_by('sms_mark.student_id');
	    	$students = $this->db->get('sms_mark')->result();
	    	
	    	$html .= '<tbody>';

	    	foreach($students as $student){
	    		$html .= '<tr>';
				$html .= '<td>'.$student->student_name.'</td>';
				
				$student_id = $student->student_id;
		    	
		    	foreach($exams as $exam){
					$exam_id = $exam->exam_id;
					$this->db->select('sms_mark.mark_obtained');
			    	$this->db->where('sms_mark.class_id', $class_id);
			    	$this->db->where('sms_mark.student_id', $student_id);
			    	$this->db->where('sms_mark.exam_id', $exam_id);
			    	$student_mark = $this->db->get('sms_mark')->row();
			    	
			    	if($student_mark->mark_obtained>'0'){
						$html .= '<td>'.$student_mark->mark_obtained.'</td>';
					}else{
						$html .= '<td></td>';
					}
				}
				
				$html .= '</tr>';
			}
	    	
	    	$html .= '</tbody>';
	    	
	    	$html .= '</table>';
			
		}
    	
        $this->helper->make_pdf($html,$page_title);
    }
    
    
    /****MANAGE GRADES*****/
    function grade($param1 = '', $param2 = '')
    {
        
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['grade_point'] = $this->input->post('grade_point');
            $data['mark_from']   = $this->input->post('mark_from');
            $data['mark_upto']   = $this->input->post('mark_upto');
            $data['comment']     = $this->input->post('comment');
            $this->db->insert('sms_grade', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'admin/grade/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['grade_point'] = $this->input->post('grade_point');
            $data['mark_from']   = $this->input->post('mark_from');
            $data['mark_upto']   = $this->input->post('mark_upto');
            $data['comment']     = $this->input->post('comment');
            
            $this->db->where('grade_id', $param2);
            $this->db->update('sms_grade', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/grade/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('sms_grade', array(
                'grade_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('grade_id', $param2);
            $this->db->delete('sms_grade');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/grade/', 'refresh');
        }
        $page_data['grades']     = $this->db->get('grade')->result_array();
        $page_data['page_name']  = 'grade';
        $page_data['page_title'] = get_phrase('manage_grade');
        $this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGING CLASS ROUTINE******************/
    function class_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($param1 == 'create') {
            $data['class_id']   = $this->input->post('class_id');
            //$data['subject_id'] = $this->input->post('subject_id');
            $data['time_start'] = $this->input->post('time_start');
            $data['time_end']   = $this->input->post('time_end');
            $data['day']        = $this->input->post('day');
            $data['frequency']        = $this->input->post('frequency');
            
            $this->db->insert('sms_class_routine', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'admin/class_routine/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['class_id']   = $this->input->post('class_id');
            //$data['subject_id'] = $this->input->post('subject_id');
            $data['time_start'] = $this->input->post('time_start');
            $data['time_end']   = $this->input->post('time_end');
            $data['day']        = $this->input->post('day');
            $data['frequency']        = $this->input->post('frequency');
            
            $this->db->where('class_routine_id', $param2);
            $this->db->update('sms_class_routine', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/class_routine/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('sms_class_routine', array(
                'class_routine_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('class_routine_id', $param2);
            $this->db->delete('sms_class_routine');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/class_routine/', 'refresh');
        }
        $page_data['page_name']  = 'class_routine';
        $page_data['page_title'] = get_phrase('class_schedule');
        $this->load->view('backend/index', $page_data);
    }
	
	/****** DAILY ATTENDANCE *****************/
	function manage_attendance($date='',$month='',$year='',$class_id='',$previous_attendance='')
	{
		
		if($_POST)
		{
			
			// Loop all the students of $class_id
            $students   =   $this->db->get_where('sms_student', array('class_id' => $class_id))->result_array();
            foreach ($students as $row)
            {
                $attendance_status  =   $this->input->post('status_' . $row['student_id']);

                $this->db->where('student_id' , $row['student_id']);
                $this->db->where('date' , $this->input->post('date'));

                $this->db->update('sms_attendance' , array('status' => $attendance_status));
            }

			$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
			redirect(base_url() . 'admin/manage_attendance/'.$date.'/'.$month.'/'.$year.'/'.$class_id , 'refresh');
		}
        $page_data['date']     =	$date;
        $page_data['month']    =	$month;
        $page_data['year']     =	$year;
        $page_data['class_id'] =	$class_id;
        $page_data['previous_attendance'] =	$previous_attendance;
		
        $page_data['page_name']  =	'manage_attendance';
        $page_data['page_title'] =	get_phrase('daily_attendance');
		$this->load->view('backend/index', $page_data);
	}
	
	
	function attendance_selector()
	{
		if($this->input->post('previous_attendance')){
			redirect(base_url() . 'admin/manage_attendance/'.$this->input->post('date').'/'.$this->input->post('class_id').'/previous_attendance', 'refresh');
		}else{
			
			redirect(base_url() . 'admin/manage_attendance/'.$this->input->post('date').'/'.
							$this->input->post('class_id') , 'refresh');
		}				
	}
    /******MANAGE BILLING / INVOICES WITH STATUS*****/
    function invoice($param1 = '', $param2 = '', $param3 = '')
    {
        
        if ($param1 == 'create') {
            $data['student_id']         = $this->input->post('student_id');
            $data['title']              = $this->input->post('title');
            $data['description']        = $this->input->post('description');
            $data['amount']             = $this->input->post('amount');
            $data['amount_paid']        = $this->input->post('amount_paid');
            $data['due']                = $data['amount'] - $data['amount_paid'];
            $data['status']             = $this->input->post('status');
            $data['creation_timestamp'] = strtotime($this->input->post('date'));
            
            $this->db->insert('sms_invoice', $data);
            $invoice_id = $this->db->insert_id();

            $data2['invoice_id']        =   $invoice_id;
            $data2['student_id']        =   $this->input->post('student_id');
            $data2['title']             =   $this->input->post('title');
            $data2['description']       =   $this->input->post('description');
            $data2['payment_type']      =  'income';
            $data2['method']            =   $this->input->post('method');
            $data2['amount']            =   $this->input->post('amount_paid');
            $data2['timestamp']         =   strtotime($this->input->post('date'));

            $this->db->insert('sms_payment' , $data2);

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'admin/invoice', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['student_id']         = $this->input->post('student_id');
            $data['title']              = $this->input->post('title');
            $data['description']        = $this->input->post('description');
            $data['amount']             = $this->input->post('amount');
            $data['status']             = $this->input->post('status');
            $data['creation_timestamp'] = strtotime($this->input->post('date'));
            
            $this->db->where('invoice_id', $param2);
            $this->db->update('sms_invoice', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/invoice', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('sms_invoice', array(
                'invoice_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'take_payment') {
            $data['invoice_id']   =   $this->input->post('invoice_id');
            $data['student_id']   =   $this->input->post('student_id');
            $data['title']        =   $this->input->post('title');
            $data['description']  =   $this->input->post('description');
            $data['payment_type'] =   'income';
            $data['method']       =   $this->input->post('method');
            $data['amount']       =   $this->input->post('amount');
            $data['timestamp']    =   strtotime($this->input->post('timestamp'));
            $this->db->insert('sms_payment' , $data);

            $data2['amount_paid']   =   $this->input->post('amount');
            $this->db->where('invoice_id' , $param2);
            $this->db->set('amount_paid', 'amount_paid + ' . $data2['amount_paid'], FALSE);
            $this->db->set('due', 'due - ' . $data2['amount_paid'], FALSE);
            $this->db->update('sms_invoice');

            $this->session->set_flashdata('flash_message' , get_phrase('payment_successfull'));
            redirect(base_url() . 'admin/invoice', 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('invoice_id', $param2);
            $this->db->delete('sms_invoice');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/invoice', 'refresh');
        }
        $page_data['page_name']  = 'invoice';
        $page_data['page_title'] = get_phrase('manage_invoice/payment');
        $this->db->order_by('creation_timestamp', 'desc');
        $page_data['invoices'] = $this->db->get('sms_invoice')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    function date_range($first, $last, $step = '+7 day', $output_format = 'Y-m-d' ) {

	    $dates = array();
	    
	    $current = strtotime($first);
	    $last = strtotime($last);

	    while( $current <= $last ) {

	        $dates[] = date($output_format, $current);
	        $current = strtotime($step, $current);
	    }

	    return $dates;
	}
    /*Export CLass Attendance Excel per POD*/
    public function view_class_attendance_excel($pod = NULL){
        //load our new PHPExcel library
        $this->load->library('excel');  

        $this->db->select('sms_student.*');
        $this->db->join('sms_class', 'sms_class.class_id=sms_student.class_id');

        $where = array('sms_class.company_id' => $this->company_id, 'sms_class.class_id' => $pod);
        $this->db->where($where);

        $students = $this->db->get('sms_student')->result();
        $class= $this->db->get_where('sms_class', array('class_id' => $pod))->row();

        foreach(range('A','K') as $columnID) {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $active_sheet = $this->excel->getActiveSheet();
        //name the worksheet
        $active_sheet->setTitle('Student Info');
        //set cell A1 content with some text

        /* Title */
        $active_sheet->setCellValue('A1', $class->name);
        //change the font size
        $active_sheet->getStyle('A1')->getFont()->setSize(26);
        //make the font become bold
        $active_sheet->getStyle('A1')->getFont()->setBold(true);

        $min_date = date('Y-m-d',strtotime('first day of this month'));
		//beginning of the week IN CALENDAR not month
		$min_date = date('Y-m-d',strtotime('Monday this week', strtotime($min_date)));
        $max_date = date('Y-m-d',strtotime('last day of this month'));
        //end of week IN CALENDAR (unless if the last day is sunday it isn't count)
		$max_date = date('Y-m-d', strtotime('Monday next week',strtotime($max_date)));
        $weeks = $this->date_range($min_date,$max_date);

            /* Header */
        $active_sheet->setCellValue('A3', 'Student Name');
        $col = 66;
        foreach($weeks as $i=>$week){
            if($i!=0){
                $active_sheet->setCellValue(chr($col).'3','Week '.$i);
                $col++;
            }
        }

      

        //change the font size
        $active_sheet->getStyle('A3:K3')->getFont()->setSize(14);
        //make the font become bold
        $active_sheet->getStyle('A3:K3')->getFont()->setBold(true);

        /* Students Name+Attendance*/
        $i=4;
        foreach ($students as $student)
        {
            $col = 66;
            $active_sheet->setCellValue('A'.$i, $student->name);
            foreach($weeks as $w=>$week){


                $absent=0;
                $present=0;
                if($w!='0'){
                    $this->db->select('sms_attendance.*');
                    $this->db->where('student_id',$student->student_id);
                    $this->db->where('date >=',$week_s);
                    $this->db->where('date <=', $weeks[$w]);

                    $attendance = $this->db->get('sms_attendance')->result();
                    $d = 0;
                    $day = array();

                    foreach($attendance as $att){
                      if($att->status=='1'){
                            $present++;
                            $day[$d] = $att->status;
                        }
                      else if($att->status=='2'){
                            $absent++;
                            
                        }
                        $d++;
                    }
                    $stats = '';
                    for($x = 0; $x < 7; $x++){
                        if($day[$x] != '' && $x == 0)
                            $stats .= 'M ';
                        else if($x == 1 && $day[$x] != '' )
                            $stats .= 'Tu ';
                        else if($x == 2 && $day[$x] != '' )
                            $stats .= 'W ';
                        else if($x == 3 && $day[$x] != '' )
                            $stats .= 'Th ';
                        else if($x == 4 && $day[$x] != '' )
                            $stats .= 'F ';
                        else if($x == 5 && $day[$x] != '' )
                            $stats .= 'Sa ';
                        else if($x == 6 && $day[$x] != '' )
                            $stats .= 'Su ';
                    }
                    $active_sheet->setCellValue(chr($col).$i,$present.' / '.count($attendance).' ('.$stats.')');
                    $col++;

                }$week_s = $weeks[$w];
            }
            $i++;
        }

        $filename = 'class_attendance.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    //Export Class Attendance PDF per POD
    function view_class_attendance_pdf($pod){
        $page_title = get_phrase('Class_attendance'); 
        //$date = $this->db->query("SELECT MIN(date) as min_date, MAX(date) as max_date FROM sms_attendance LEFT JOIN sms_student ON sms_attendance.student_id=sms_student.student_id WHERE date!='0000-00-00' AND company_id=".$this->company_id)->row();
        //print_r($date);
        $min_date = date('Y-m-d',strtotime('first day of this month'));
	//beginning of the week IN CALENDAR not month
	$min_date = date('Y-m-d',strtotime('Monday this week', strtotime($min_date)));
        $max_date = date('Y-m-d',strtotime('last day of this month'));
        //end of week IN CALENDAR (unless if the last day is sunday it isn't count)
	$max_date = date('Y-m-d', strtotime('Monday next week',strtotime($max_date)));
        //get the weekly cycle through the date range func        
        $weeks = $this->date_range($min_date,$max_date);
        $this->db->select('sms_student.*');
        $this->db->join('sms_class', 'sms_class.class_id=sms_student.class_id');
        $where = array('sms_class.company_id' => $this->company_id, 'sms_class.class_id' => $pod);
        $this->db->where($where);
        $max_date=date('Y-m-d',strtotime('-1 day',strtotime($max_date)));

        $students = $this->db->get('sms_student')->result();
        $class= $this->db->get_where('sms_class', array('class_id' => $pod))->row();
        $html = '<h2>'. $class->name .'</h2>';
        $html .= '<h5>From : '.$min_date.'&nbsp;&nbsp;To   : '.$max_date.'</h5>';
        $html .= '<table width="100%" border="1" cellpadding="7" cellspacing="0">
                  <thead>
                  <tr style="font-weight: bold;">
                    <th><div>'.get_phrase('Student Name').'</div></th>';
        foreach($weeks as $i=>$week){
            if($i!=0){
                $html .= '<th> Week '.$i.'</th>';
            }
        }
        $html .= '</tr></thead><tbody>';


        foreach($students as $student){

            $html.= '<tr nobr="true">';
            $html.= '<td>'.$student->name.'</td>';
            

          foreach($weeks as $i=>$week){

            $absent=0;
            $present=0;
            if($i!='0'){
                $this->db->select('sms_attendance.*');
                $this->db->where('student_id', $student->student_id);
                $this->db->where('date >=', $week_s);
                $this->db->where('date <=', $weeks[$i]);
                $attendance = $this->db->get('sms_attendance')->result();


                $d = 0;
                $day = array();

                foreach($attendance as $att){
                  if($att->status=='1'){
                    $present++;
                        $day[$d] = $att->status;
                    }
                  else if($att->status=='2'){
                    $absent++;
                        
                    }
                    
                    $d++;
                }
                $stats = '';
                for($x = 0; $x < 7; $x++){
                    if($day[$x] != '' && $x == 0)
                        $stats .= 'M ';
                    else if($x == 1 && $day[$x] != '' )
                        $stats .= 'Tu ';
                    else if($x == 2 && $day[$x] != '' )
                        $stats .= 'W ';
                    else if($x == 3 && $day[$x] != '' )
                        $stats .= 'Th ';
                    else if($x == 4 && $day[$x] != '' )
                        $stats .= 'F ';
                    else if($x == 5 && $day[$x] != '' )
                        $stats .= 'Sa ';
                    else if($x == 6 && $day[$x] != '' )
                        $stats .= 'Su ';

                }

                $html .= '<td>'.$present.' / '.count($attendance).'</td>';
            }
            $week_s = $weeks[$i];
          }
          $html.= '</tr>'; 
        }
        $html .= '</tbody></table>';    
        //echo $html; exit;
        $this->helper->make_pdf($html,$page_title);
    }
    /*Attendance percentage of all classes/POD together from start til now*/
    function view_all_attendance_pdf()
    {                    
            
        $page_title	= get_phrase('view_all_attendance'); 
        
        $date = $this->db->query("SELECT MIN(date) as min_date, MAX(date) as max_date FROM sms_attendance LEFT JOIN sms_student ON sms_attendance.student_id=sms_student.student_id WHERE date!='0000-00-00' AND company_id=".$this->company_id)->row();
        //print_r($date);
        $min_date = $date->min_date;
        $max_date = $date->max_date;
        
        $weeks = $this->date_range($min_date,$max_date);

        //print_r($weeks);

        //$datetime1 = new DateTime("$min_date");
		//$datetime2 = new DateTime("$max_date");
		//$interval = $datetime1->diff($datetime2);
		//$weeks = (int)(($interval->days) / 7);
		
		//$html = '';
		//$html .= '<table width="100%" border="1" cellpadding="4" cellspacing="0">';
		// $html .= '<thead>';
  //   	$html .= '<tr>';
  //   	$html .= '<th>'.get_phrase('classes').'</th>';
		// foreach($weeks as $i=>$week){
		// 	if($i!='0'){
		// 		$html .= '<th>Week '.$i.'</th>';
		// 	}
		// }
		// $html .= '</tr>';
	 //    $html .= '</thead>';
    	
  //   	$html .= '<tbody>';
    	
  //   	$this->db->where('company_id', $this->company_id);
  //   	$classes = $this->db->get('sms_class')->result();
  //   	foreach($classes as $class){
  //   		$class_id = $class->class_id;
		// 	$html .= '<tr>';
  //   		$html .= '<td>'.$class->name.'</td>';
    		
  //   		foreach($weeks as $i=>$week){
		// 		if($i!='0'){
		//     		$this->db->select('sms_student.student_id, sms_student.name as student_name, sms_attendance.status, sms_attendance.date');
		// 	    	$this->db->join('sms_attendance', 'sms_attendance.student_id=sms_student.student_id');
		// 	    	$this->db->where_in('sms_student.class_id', array($class_id));
		// 	    	$this->db->where('sms_attendance.date >=', $week_s);
		// 	    	$this->db->where('sms_attendance.date <=', $weeks[$i]);
		// 	    	$student = count($this->db->get('sms_student')->result());
			    	
		// 	    	$this->db->select('sms_student.student_id, sms_student.name as student_name, sms_attendance.status, sms_attendance.date');
		// 	    	$this->db->join('sms_attendance', 'sms_attendance.student_id=sms_student.student_id');
		// 	    	$this->db->where_in('sms_student.class_id', array($class_id));
		// 	    	$this->db->where('sms_attendance.date >=', $week_s);
		// 	    	$this->db->where('sms_attendance.date <=', $weeks[$i]);
		// 	    	$this->db->where('sms_attendance.status', '1');
		// 	    	$status_1 = count($this->db->get('sms_student')->result());
		// 	    	//echo $this->db->last_query(); 
		// 	    	$atteds = $status_1/$student*100;
		// 	    	if($atteds>'0'){
		// 				$html .= '<td>'.(int)$atteds.' %</td>';
		// 			}else{
		// 				$html .= '<td></td>';
		// 			}
			    	
	 //    		}
	 //    		$week_s = $weeks[$i];
		// 	}
    		
    		
  //   		$html .= '</tr>';
		// }
		// $html .= '</tbody>';
    	
  //   	$html .= '</table>';
    	
  //   	//echo $html;
  //       $this->helper->make_pdf($html,$page_title);
        $this->db->where('company_id', $this->company_id);
        $classes = $this->db->get('sms_class')->result();
        
        $html = '<table width="100%" border="1" cellpadding="7" cellspacing="0">
                  <thead>
                  <tr nobr="true" style="font-weight: bold;">
                    <th><div>'.get_phrase('class').'</div></th>';
        
        foreach($classes as $class){
                $class_id = $class->class_id;       
                $html .= '<th>'.$class->name.'</th>';

        }
        $html .= '</tr></thead><tbody>';
        
        
        
        foreach($weeks as $i=>$week){
            $html .= '<tr>';
            if($i!='0'){

                $html .= '<td style="font-weight: bold;">Week '.$i.'</td>';
                foreach($classes as $class){
                    if($i!='0'){
                        $class_id = $class->class_id;
                        $this->db->select('sms_student.student_id, sms_student.name as student_name, sms_attendance.status, sms_attendance.date');
                        $this->db->join('sms_attendance', 'sms_attendance.student_id=sms_student.student_id');
                        $this->db->where_in('sms_student.class_id', array($class_id));
                        $this->db->where('sms_attendance.date >=', $week_s);
                        $this->db->where('sms_attendance.date <=', $weeks[$i]);
                        $student = count($this->db->get('sms_student')->result());
                        
                        $this->db->select('sms_student.student_id, sms_student.name as student_name, sms_attendance.status, sms_attendance.date');
                        $this->db->join('sms_attendance', 'sms_attendance.student_id=sms_student.student_id');
                        $this->db->where_in('sms_student.class_id', array($class_id));
                        $this->db->where('sms_attendance.date >=', $week_s);
                        $this->db->where('sms_attendance.date <=', $weeks[$i]);
                        $this->db->where('sms_attendance.status', '1');
                        $status_1 = count($this->db->get('sms_student')->result());
                        //echo $this->db->last_query(); 
                        $atteds = $status_1/$student*100; 
                        
                        if($atteds>'0'){
                            $html .= '<td>'.(int)$atteds.' %</td>';
                        }else{
                            $html .= '<td></td>';
                        }
                        
                    }
            }
            }
            $week_s = $weeks[$i];
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        //echo $html; exit;
        $this->helper->make_pdf($html,$page_title);
    }


    /**********ACCOUNTING********************/
    function income($param1 = '' , $param2 = '')
    {
        $page_data['page_name']  = 'income';
        $page_data['page_title'] = get_phrase('incomes');
        $this->db->order_by('creation_timestamp', 'desc');
        $page_data['invoices'] = $this->db->get('sms_invoice')->result_array();
        $this->load->view('backend/index', $page_data); 
    }

    function expense($param1 = '' , $param2 = '')
    {
             
        if ($param1 == 'create') {
            $data['title']               =   $this->input->post('title');
            $data['expense_category_id'] =   $this->input->post('expense_category_id');
            $data['description']         =   $this->input->post('description');
            $data['payment_type']        =   'expense';
            $data['method']              =   $this->input->post('method');
            $data['amount']              =   $this->input->post('amount');
            $data['timestamp']           =   strtotime($this->input->post('timestamp'));
            $this->db->insert('sms_payment' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'admin/expense', 'refresh');
        }

        if ($param1 == 'edit') {
            $data['title']               =   $this->input->post('title');
            $data['expense_category_id'] =   $this->input->post('expense_category_id');
            $data['description']         =   $this->input->post('description');
            $data['payment_type']        =   'expense';
            $data['method']              =   $this->input->post('method');
            $data['amount']              =   $this->input->post('amount');
            $data['timestamp']           =   strtotime($this->input->post('timestamp'));
            $this->db->where('payment_id' , $param2);
            $this->db->update('sms_payment' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/expense', 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('payment_id' , $param2);
            $this->db->delete('sms_payment');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/expense', 'refresh');
        }

        $page_data['page_name']  = 'expense';
        $page_data['page_title'] = get_phrase('expenses');
        $this->load->view('backend/index', $page_data); 
    }

    function expense_category($param1 = '' , $param2 = '')
    {
        
        if ($param1 == 'create') {
            $data['name']   =   $this->input->post('name');
            $this->db->insert('sms_expense_category' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'admin/expense_category');
        }
        if ($param1 == 'edit') {
            $data['name']   =   $this->input->post('name');
            $this->db->where('expense_category_id' , $param2);
            $this->db->update('sms_expense_category' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/expense_category');
        }
        if ($param1 == 'delete') {
            $this->db->where('expense_category_id' , $param2);
            $this->db->delete('sms_expense_category');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/expense_category');
        }

        $page_data['page_name']  = 'expense_category';
        $page_data['page_title'] = get_phrase('expense_category');
        $this->load->view('backend/index', $page_data);
    }

    /**********MANAGE LIBRARY / BOOKS********************/
    function book($param1 = '', $param2 = '', $param3 = '')
    {
            
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['price']       = $this->input->post('price');
            $data['author']      = $this->input->post('author');
            $data['class_id']    = $this->input->post('class_id');
            $data['status']      = $this->input->post('status');
            $data['company_id']      = $this->company_id;
            $this->db->insert('sms_book', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'admin/book', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['price']       = $this->input->post('price');
            $data['author']      = $this->input->post('author');
            $data['class_id']    = $this->input->post('class_id');
            $data['status']      = $this->input->post('status');
            
            $this->db->where('book_id', $param2);
            $this->db->update('sms_book', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/book', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('sms_book', array(
                'book_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('book_id', $param2);
            $this->db->delete('sms_book');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/book', 'refresh');
        }
        if ($param1 == 'change_status') {
        
            $data['status']      = $param3; //$this->input->post('status');
            
            $this->db->where('book_id', $param2);
            $this->db->update('sms_book', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/book', 'refresh');
        }
        
        $page_data['page_name']  = 'book';
        $page_data['page_title'] = get_phrase('manage_library_books');
        $this->load->view('backend/index', $page_data);
        
    }
    /**********MANAGE TRANSPORT / VEHICLES / ROUTES********************/
    function transport($param1 = '', $param2 = '', $param3 = '')
    {
         
        if ($param1 == 'create') {
            $data['route_name']        = $this->input->post('route_name');
            $data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
            $data['description']       = $this->input->post('description');
            $data['route_fare']        = $this->input->post('route_fare');
            $this->db->insert('sms_transport', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'admin/transport', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['route_name']        = $this->input->post('route_name');
            $data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
            $data['description']       = $this->input->post('description');
            $data['route_fare']        = $this->input->post('route_fare');
            
            $this->db->where('transport_id', $param2);
            $this->db->update('sms_transport', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/transport', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('sms_transport', array(
                'transport_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('transport_id', $param2);
            $this->db->delete('sms_transport');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/transport', 'refresh');
        }
        $page_data['transports'] = $this->db->get('transport')->result_array();
        $page_data['page_name']  = 'transport';
        $page_data['page_title'] = get_phrase('manage_transport');
        $this->load->view('backend/index', $page_data);
        
    }
    /**********MANAGE DORMITORY / HOSTELS / ROOMS ********************/
    function dormitory($param1 = '', $param2 = '', $param3 = '')
    {
          
        if ($param1 == 'create') {
            $data['name']           = $this->input->post('name');
            $data['number_of_room'] = $this->input->post('number_of_room');
            $data['description']    = $this->input->post('description');
            $this->db->insert('sms_dormitory', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'admin/dormitory', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']           = $this->input->post('name');
            $data['number_of_room'] = $this->input->post('number_of_room');
            $data['description']    = $this->input->post('description');
            
            $this->db->where('dormitory_id', $param2);
            $this->db->update('sms_dormitory', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/dormitory', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('sms_dormitory', array(
                'dormitory_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('dormitory_id', $param2);
            $this->db->delete('sms_dormitory');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/dormitory', 'refresh');
        }
        $page_data['dormitories'] = $this->db->get('dormitory')->result_array();
        $page_data['page_name']   = 'dormitory';
        $page_data['page_title']  = get_phrase('manage_dormitory');
        $this->load->view('backend/index', $page_data);
        
    }
    
    /***MANAGE EVENT / NOTICEBOARD, WILL BE SEEN BY ALL ACCOUNTS DASHBOARD**/
    function noticeboard($param1 = '', $param2 = '', $param3 = '')
    {  
        
        if ($param1 == 'create') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
            $this->db->insert('sms_noticeboard', $data);

            $check_sms_send = $this->input->post('check_sms');

            if ($check_sms_send == 1) {
                // sms sending configurations

                $parents  = $this->db->get('sms_parent')->result_array();
                $students = $this->db->get('sms_student')->result_array();
                $teachers = $this->db->get('sms_teacher')->result_array();
                $date     = $this->input->post('create_timestamp');
                $message  = $data['notice_title'] . ' ';
                $message .= get_phrase('on') . ' ' . $date;
                foreach($parents as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
                foreach($students as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
                foreach($teachers as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
            }

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'admin/noticeboard/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
            $this->db->where('notice_id', $param2);
            $this->db->update('sms_noticeboard', $data);

            $check_sms_send = $this->input->post('check_sms');

            if ($check_sms_send == 1) {
                // sms sending configurations

                $parents  = $this->db->get('sms_parent')->result_array();
                $students = $this->db->get('sms_student')->result_array();
                $teachers = $this->db->get('sms_teacher')->result_array();
                $date     = $this->input->post('create_timestamp');
                $message  = $data['notice_title'] . ' ';
                $message .= get_phrase('on') . ' ' . $date;
                foreach($parents as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
                foreach($students as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
                foreach($teachers as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
            }

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/noticeboard/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('sms_noticeboard', array(
                'notice_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('notice_id', $param2);
            $this->db->delete('sms_noticeboard');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'admin/noticeboard/', 'refresh');
        }
        $page_data['page_name']  = 'noticeboard';
        $page_data['page_title'] = get_phrase('manage_noticeboard');
        $page_data['notices']    = $this->db->get('sms_noticeboard')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    /* private messaging */

    function message($param1 = 'message_home', $param2 = '', $param3 = '') {
        
        if ($param1 == 'send_new') {
            $message_thread_code = $this->crud_model->send_new_private_message();
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'admin/message/message_read/' . $message_thread_code, 'refresh');
        }

        if ($param1 == 'send_reply') {
            $this->crud_model->send_reply_message($param2);  //$param2 = message_thread_code
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'admin/message/message_read/' . $param2, 'refresh');
        }

        if ($param1 == 'message_read') {
            $page_data['current_message_thread_code'] = $param2;  // $param2 = message_thread_code
            $this->crud_model->mark_thread_messages_read($param2);
        }

        $page_data['message_inner_page_name']   = $param1;
        $page_data['page_name']                 = 'message';
        $page_data['page_title']                = get_phrase('private_messaging');
        $this->load->view('backend/index', $page_data);
    }
    
    /*****SITE/SYSTEM SETTINGS*********/
    function system_settings($param1 = '', $param2 = '', $param3 = '')
    {
        if ($param1 == 'do_update') {
			 
            $data['description'] = $this->input->post('system_name');
            $this->db->where('type' , 'system_name');
            $this->db->update('sms_settings' , $data);

            $data['description'] = $this->input->post('system_title');
            $this->db->where('type' , 'system_title');
            $this->db->update('sms_settings' , $data);

            $data['description'] = $this->input->post('address');
            $this->db->where('type' , 'address');
            $this->db->update('sms_settings' , $data);

            $data['description'] = $this->input->post('phone');
            $this->db->where('type' , 'phone');
            $this->db->update('sms_settings' , $data);

            $data['description'] = $this->input->post('paypal_email');
            $this->db->where('type' , 'paypal_email');
            $this->db->update('sms_settings' , $data);

            $data['description'] = $this->input->post('currency');
            $this->db->where('type' , 'currency');
            $this->db->update('sms_settings' , $data);

            $data['description'] = $this->input->post('system_email');
            $this->db->where('type' , 'system_email');
            $this->db->update('sms_settings' , $data);

            $data['description'] = $this->input->post('system_name');
            $this->db->where('type' , 'system_name');
            $this->db->update('sms_settings' , $data);

            $data['description'] = $this->input->post('language');
            $this->db->where('type' , 'language');
            $this->db->update('sms_settings' , $data);

            $data['description'] = $this->input->post('text_align');
            $this->db->where('type' , 'text_align');
            $this->db->update('sms_settings' , $data);
			
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated')); 
            redirect(base_url() . 'admin/manage_profile/', 'refresh');
        }
        if ($param1 == 'upload_logo') {
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/logo.png');
            $this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
            redirect(base_url() . 'admin/manage_profile/', 'refresh');
        }
        if ($param1 == 'change_skin') {
            $data['description'] = $param2;
            $this->db->where('type' , 'skin_colour');
            $this->db->update('sms_settings' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('theme_selected')); 
            redirect(base_url() . 'admin/manage_profile/', 'refresh'); 
        }
        $page_data['page_name']  = 'system_settings';
        $page_data['page_title'] = get_phrase('system_settings');
        $page_data['settings']   = $this->db->get('sms_settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    /*****SMS SETTINGS*********/
    function sms_settings($param1 = '' , $param2 = '')
    {
        if ($param1 == 'clickatell') {

            $data['description'] = $this->input->post('clickatell_user');
            $this->db->where('type' , 'clickatell_user');
            $this->db->update('sms_settings' , $data);

            $data['description'] = $this->input->post('clickatell_password');
            $this->db->where('type' , 'clickatell_password');
            $this->db->update('sms_settings' , $data);

            $data['description'] = $this->input->post('clickatell_api_id');
            $this->db->where('type' , 'clickatell_api_id');
            $this->db->update('sms_settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/sms_settings/', 'refresh');
        }

        if ($param1 == 'twilio') {

            $data['description'] = $this->input->post('twilio_account_sid');
            $this->db->where('type' , 'twilio_account_sid');
            $this->db->update('sms_settings' , $data);

            $data['description'] = $this->input->post('twilio_auth_token');
            $this->db->where('type' , 'twilio_auth_token');
            $this->db->update('sms_settings' , $data);

            $data['description'] = $this->input->post('twilio_sender_phone_number');
            $this->db->where('type' , 'twilio_sender_phone_number');
            $this->db->update('sms_settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/sms_settings/', 'refresh');
        }

        if ($param1 == 'active_service') {

            $data['description'] = $this->input->post('active_sms_service');
            $this->db->where('type' , 'active_sms_service');
            $this->db->update('sms_settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'admin/sms_settings/', 'refresh');
        }

        $page_data['page_name']  = 'sms_settings';
        $page_data['page_title'] = get_phrase('sms_settings');
        $page_data['settings']   = $this->db->get('sms_settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    /*****LANGUAGE SETTINGS*********/
    function manage_language($param1 = '', $param2 = '', $param3 = '')
    {
		
		if ($param1 == 'edit_phrase') {
			$page_data['edit_profile'] 	= $param2;	
		}
		if ($param1 == 'update_phrase') {
			$language	=	$param2;
			$total_phrase	=	$this->input->post('total_phrase');
			for($i = 1 ; $i < $total_phrase ; $i++)
			{
				//$data[$language]	=	$this->input->post('phrase').$i;
				$this->db->where('phrase_id' , $i);
				$this->db->update('sms_language' , array($language => $this->input->post('phrase'.$i)));
			}
			redirect(base_url() . 'admin/manage_profile/edit_phrase/'.$language, 'refresh');
		}
		if ($param1 == 'do_update') {
			$language        = $this->input->post('language');
			$data[$language] = $this->input->post('phrase');
			$this->db->where('phrase_id', $param2);
			$this->db->update('sms_language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'admin/manage_profile/', 'refresh');
		}
		if ($param1 == 'add_phrase') {
			$data['phrase'] = $this->input->post('phrase');
			$this->db->insert('sms_language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'admin/manage_profile/', 'refresh');
		}
		if ($param1 == 'add_language') {
			$language = $this->input->post('language');
			$this->load->dbforge();
			$fields = array(
				$language => array(
					'type' => 'LONGTEXT'
				)
			);
			$this->dbforge->add_column('sms_language', $fields);
			
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'admin/manage_profile/', 'refresh');
		}
		if ($param1 == 'delete_language') {
			$language = $param2;
			$this->load->dbforge();
			$this->dbforge->drop_column('sms_language', $language);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			
			redirect(base_url() . 'admin/manage_profile/', 'refresh');
		}
		$page_data['page_name']        = 'manage_language';
		$page_data['page_title']       = get_phrase('manage_language');
		//$page_data['language_phrases'] = $this->db->get('language')->result_array();
		$this->load->view('backend/index', $page_data);	
    }
    
    /*****BACKUP / RESTORE / DELETE DATA PAGE**********/
    function backup_restore($operation = '', $type = '')
    {
        
        if ($operation == 'create') {
            $this->crud_model->create_backup($type);
        }
        if ($operation == 'restore') {
            $this->crud_model->restore_backup();
            $this->session->set_flashdata('backup_message', 'Backup Restored');
            redirect(base_url() . 'admin/backup_restore/', 'refresh');
        }
        if ($operation == 'delete') {
            $this->crud_model->truncate($type);
            $this->session->set_flashdata('backup_message', 'Data removed');
            redirect(base_url() . 'admin/backup_restore/', 'refresh');
        }
        
        $page_data['page_info']  = 'Create backup / restore from backup';
        $page_data['page_name']  = 'backup_restore';
        $page_data['page_title'] = get_phrase('manage_backup_restore');
        $this->load->view('backend/index', $page_data);
    }
    
    /******MANAGE OWN PROFILE AND CHANGE PASSWORD***/
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        
        if ($param1 == 'edit_phrase') {
			$page_data['edit_profile'] 	= $param2;	
		}
		
        if ($param1 == 'update_profile_info') {
            $data['name']  = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            
            $this->db->where('admin_id', $this->session->userdata('admin_id'));
            $this->db->update('sms_admin', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/admin_image/' . $this->session->userdata('admin_id') . '.jpg');
            $this->session->set_flashdata('flash_message', get_phrase('account_updated'));
            redirect(base_url() . 'admin/manage_profile/', 'refresh');
        }
        if ($param1 == 'change_password') {
            $data['password']             = $this->input->post('password');
            $data['new_password']         = $this->input->post('new_password');
            $data['confirm_new_password'] = $this->input->post('confirm_new_password');
            
            $current_password = $this->db->get_where('sms_admin', array(
                'admin_id' => $this->session->userdata('admin_id')
            ))->row()->password;
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
                $this->db->where('admin_id', $this->session->userdata('admin_id'));
                $this->db->update('sms_admin', array(
                    'password' => $data['new_password']
                ));
                $this->session->set_flashdata('flash_message', get_phrase('password_updated'));
            } else {
                $this->session->set_flashdata('flash_message', get_phrase('password_mismatch'));
            }
            redirect(base_url() . 'admin/manage_profile/', 'refresh');
        }
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        //$page_data['edit_data']  = $this->db->get_where('sms_admin', array(
            //'admin_id' => $this->session->userdata('admin_id')
        //))->result_array();
        $this->load->view('backend/index', $page_data);
    }

	// export to excel
	function student_export_to_excel(){
		/*getting the data*/
		$this->db->select('sms_student.*');
		$this->db->join('sms_class','sms_class.class_id = sms_student.class_id');
		$this->db->where('sms_student.company_id',$this->company_id);
		$students = $this->db->get('sms_student')->result();

		//load our new PHPExcel library
		$this->load->library('excel');
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);

		foreach(range('A','G') as $columnID) {
			$this->excel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		$active_sheet = $this->excel->getActiveSheet();
		//name the worksheet
		$active_sheet->setTitle('Student List');
		//set cell A1 content with some text
		$active_sheet->setCellValue('A1', 'ID_Number');
		$active_sheet->setCellValue('B1', 'Name');
		$active_sheet->setCellValue('C1', 'Address');
		$active_sheet->setCellValue('D1', 'Email');
		$active_sheet->setCellValue('E1', 'Birthday');
		$active_sheet->setCellValue('F1', 'Gender');
		$active_sheet->setCellValue('G1', 'Phone');
		//change the font size
		$active_sheet->getStyle('A1:G1')->getFont()->setSize(14);
		//make the font become bold
		$active_sheet->getStyle('A1:G1')->getFont()->setBold(true);

		$i = 2;
		foreach($students as $student){
			$active_sheet->setCellValue('A'.$i, $student->id_number);
			$active_sheet->setCellValue('B'.$i, $student->name);
			$active_sheet->setCellValue('C'.$i, $student->address);
			$active_sheet->setCellValue('D'.$i, $student->email);
			$active_sheet->setCellValue('E'.$i, $student->birthday);
			$active_sheet->setCellValue('F'.$i, $student->sex);
			$active_sheet->setCellValue('G'.$i, $student->phone);
			$i++;
		}
		$filename = 'student_list.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache

		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');

	}
	
	// teachers export to excel
	function teachers_export_to_excel(){
		/*getting the data*/
		$this->db->select('sms_teacher.*');
		$this->db->where('company_id',$this->company_id);
		$teachers = $this->db->get('sms_teacher')->result();

		//load our new PHPExcel library
		$this->load->library('excel');
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);

		foreach(range('A','G') as $columnID) {
			$this->excel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		$active_sheet = $this->excel->getActiveSheet();
		//name the worksheet
		$active_sheet->setTitle('Teacher List');
		//set cell A1 content with some text
		$active_sheet->setCellValue('A1', 'Name');
		$active_sheet->setCellValue('B1', 'Address');
		$active_sheet->setCellValue('C1', 'Email');
		$active_sheet->setCellValue('D1', 'Birthday');
		$active_sheet->setCellValue('E1', 'Gender');
		$active_sheet->setCellValue('F1', 'Phone');
		//change the font size
		$active_sheet->getStyle('A1:F1')->getFont()->setSize(14);
		//make the font become bold
		$active_sheet->getStyle('A1:F1')->getFont()->setBold(true);

		$i = 2;
		foreach($teachers as $row){
			$active_sheet->setCellValue('A'.$i, $row->name);
			$active_sheet->setCellValue('B'.$i, $row->address);
			$active_sheet->setCellValue('C'.$i, $row->email);
			$active_sheet->setCellValue('D'.$i, $row->birthday);
			$active_sheet->setCellValue('E'.$i, $row->sex);
			$active_sheet->setCellValue('F'.$i, $row->phone);
			$i++;
		}
		$filename = 'teacher_list.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache

		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');

	}
	
	// export to excel
	function attendance_export_to_excel(){
		
		$a_z = array('A');
		$current = 'A';
		while ($current != 'ZZZ') {
		    $a_z[] = ++$current;
		}
		
		//load our new PHPExcel library
		$this->load->library('excel');
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);

		$active_sheet = $this->excel->getActiveSheet();
		//name the worksheet
		$active_sheet->setTitle('View All Attendance');
		
		$date = $this->db->query("SELECT MIN(date) as min_date, MAX(date) as max_date FROM sms_attendance LEFT JOIN sms_student ON sms_attendance.student_id=sms_student.student_id WHERE date!='0000-00-00' AND company_id=".$this->company_id)->row();
        $min_date = $date->min_date;
        $max_date = $date->max_date;      
        $weeks = $this->date_range($min_date,$max_date);
    	
    	$active_sheet->setCellValue('A1', 'Pods');

        $this->db->where('company_id', $this->company_id);
        $classes = $this->db->get('sms_class')->result();
        $x=1;
		foreach($classes as $class){
				$class_id = $class->class_id;			
				$active_sheet->setCellValue($a_z[$x].'1', $class->name);
                $x++;
		}

		$j = 2;
    	foreach($weeks as $i=>$week){
    		if($i!='0'){
    		$active_sheet->setCellValue('A'.$j, 'Week '.$i);
    		}
    		foreach($classes as $class){
				if($i!='0'){
		    		$this->db->select('sms_student.student_id, sms_student.name as student_name, sms_attendance.status, sms_attendance.date');
			    	$this->db->join('sms_attendance', 'sms_attendance.student_id=sms_student.student_id');
			    	$this->db->where_in('sms_student.class_id', array($class_id));
			    	$this->db->where('sms_attendance.date >=', $week_s);
			    	$this->db->where('sms_attendance.date <=', $weeks[$i]);
			    	$student = count($this->db->get('sms_student')->result());
			    	
			    	$this->db->select('sms_student.student_id, sms_student.name as student_name, sms_attendance.status, sms_attendance.date');
			    	$this->db->join('sms_attendance', 'sms_attendance.student_id=sms_student.student_id');
			    	$this->db->where_in('sms_student.class_id', array($class_id));
			    	$this->db->where('sms_attendance.date >=', $week_s);
			    	$this->db->where('sms_attendance.date <=', $weeks[$i]);
			    	$this->db->where('sms_attendance.status', '1');
			    	$status_1 = count($this->db->get('sms_student')->result());
			    	//echo $this->db->last_query(); 
			    	$atteds = $status_1/$student*100; 
			    	
			    	if($atteds>'0'){
						$active_sheet->setCellValue($a_z[$i].''.$j, (int)$atteds.'%');
					}else{
						$active_sheet->setCellValue($a_z[$i].''.$j, '');
					}
			    	
	    		}
	    		
			}
    		$j++;
            $week_s = $weeks[$i];
		}
		
		$filename = 'attendance.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache

		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');

	}
	
	// export to excel
	function exam_export_to_excel(){
		
		//load our new PHPExcel library
		$this->load->library('excel');
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);

		foreach(range('A','G') as $columnID) {
			$this->excel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		$active_sheet = $this->excel->getActiveSheet();
		//name the worksheet
		$active_sheet->setTitle('Exam List');
		//set cell A1 content with some text
		$active_sheet->setCellValue('A1', 'Exam Name');
		$active_sheet->setCellValue('B1', 'Pod');
		$active_sheet->setCellValue('C1', 'Date');
		$active_sheet->setCellValue('D1', 'Category');
		$active_sheet->setCellValue('E1', 'Comment');
		$active_sheet->setCellValue('F1', 'Recurring');
		$active_sheet->setCellValue('G1', 'Document');
		
		//change the font size
		$active_sheet->getStyle('A1:G1')->getFont()->setSize(14);
		//make the font become bold
		$active_sheet->getStyle('A1:G1')->getFont()->setBold(true);
		
		$this->db->select('sms_exam.*, sms_class.name as class_name');
		$this->db->join('sms_class', 'sms_class.class_id = sms_exam.class_id','left');
		$this->db->where('sms_exam.company_id', $this->company_id);
        $this->db->order_by('exam_id','DESC');
        $exams = $this->db->get('sms_exam')->result_array();

		$i = 2;
		foreach($exams as $row){
			if($row['recurring']!=''){ $recurring = ' | '.$row['recurring']; }else{ $recurring = ''; }
			
			$active_sheet->setCellValue('A'.$i, $row['name']);
			$active_sheet->setCellValue('B'.$i, $row['class_name']);
			$active_sheet->setCellValue('C'.$i, $row['date']);
			$active_sheet->setCellValue('D'.$i, $row['category']);
			$active_sheet->setCellValue('E'.$i, $row['comment']);
			$active_sheet->setCellValue('F'.$i, $row['recurring_yes_no'].''.$recurring);
			$active_sheet->setCellValue('G'.$i, $row['document']);
			$i++;
		}

		$filename = 'exam.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache

		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');

	}
	
		
	// export to excel
	function mark_export_to_excel(){
		
		$a_z = array('A');
		$current = 'A';
		while ($current != 'ZZZ') {
		    $a_z[] = ++$current;
		}
		
		//load our new PHPExcel library
		$this->load->library('excel');	
		
		$this->db->select('sms_class.*');
    	$this->db->join('sms_mark', 'sms_mark.class_id=sms_class.class_id');
    	$this->db->where('sms_class.company_id', $this->company_id);
    	$this->db->group_by('sms_mark.class_id');
    	$classes = $this->db->get('sms_class')->result();
    	
    	$this->excel->getActiveSheet()->setTitle('Marks');
    	$this->excel->setActiveSheetIndex(0);
    	
    	$j = 1;
    	$i = 0;
    	foreach($classes as $m=>$class){     			
    		
			$class_id = $class->class_id;
			
			$this->excel->getActiveSheet()->setCellValue($a_z[$i].''.$j, $class->name);
			
			//change the font size
			$this->excel->getActiveSheet()->getStyle('A'.$j)->getFont()->setSize(14);
			//make the font become bold
			$this->excel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
			
			$this->db->select('sms_exam.*');
	    	$this->db->join('sms_exam', 'sms_exam.exam_id=sms_mark.exam_id');
	    	$this->db->where('sms_mark.class_id', $class_id);
	    	$this->db->group_by('sms_mark.exam_id');
	    	$exams = $this->db->get('sms_mark')->result();
	    	
	    	$j++;
	    	$this->excel->getActiveSheet()->setCellValue($a_z[$i].''.$j, 'Student Name');
	    	
	    	//change the font size
			$this->excel->getActiveSheet()->getStyle('A'.$j)->getFont()->setSize(14);
			//make the font become bold
			$this->excel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
	    	
	    	$k = 1;
	    	foreach($exams as $exam){
				$this->excel->getActiveSheet()->setCellValue($a_z[$k].''.$j, $exam->name);
				$k++;
			}
	    	$k = 1;
	    	
	    	$this->db->select('sms_student.*');
	    	$this->db->join('sms_student', 'sms_student.student_id=sms_mark.student_id');
	    	$this->db->where('sms_mark.class_id', $class_id);
	    	$this->db->group_by('sms_mark.student_id');
	    	$students = $this->db->get('sms_mark')->result();
	    	
	    	$html .= '<tbody>';

	    	foreach($students as $student){

				$j++;
				$this->excel->getActiveSheet()->setCellValue($a_z[$i].''.$j, $student->name);
				
				$student_id = $student->student_id;
		    	$k = 1;
		    	foreach($exams as $exam){
					$exam_id = $exam->exam_id;
					$this->db->select('sms_mark.mark_obtained');
			    	$this->db->where('sms_mark.class_id', $class_id);
			    	$this->db->where('sms_mark.student_id', $student_id);
			    	$this->db->where('sms_mark.exam_id', $exam_id);
			    	$student_mark = $this->db->get('sms_mark')->row();
			    	
			    	if($student_mark->mark_obtained>'0'){
						$this->excel->getActiveSheet()->setCellValue($a_z[$k].''.$j, $student_mark->mark_obtained);
					}else{
						$this->excel->getActiveSheet()->setCellValue($a_z[$k].''.$j, '');
					}
					$k++;
				}
				$k = 1;
			}
			//$j = 1+$j++;
		}
    	
    	/*$j = 1;
    	$i = 0;
    	foreach($classes as $m=>$class){     
    		if($m=='0'){
				$this->excel->setActiveSheetIndex($m); 
			}else{
				$this->excel->createSheet();
				$this->excel->setActiveSheetIndex($m); 
			}	
			
    		$this->excel->getActiveSheet()->setTitle('Pod - '.$class->name);			
    		
			$class_id = $class->class_id;
			
			$this->excel->getActiveSheet()->setCellValue($a_z[$i].''.$j, $class->name);
			
			//change the font size
			$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
			//make the font become bold
			$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
			
			$this->db->select('sms_exam.name as exam_name', 'sms_exam.exam_id');
	    	$this->db->join('sms_exam', 'sms_exam.exam_id=sms_mark.exam_id');
	    	$this->db->where('sms_mark.class_id', $class_id);
	    	$this->db->group_by('sms_mark.exam_id');
	    	$exams = $this->db->get('sms_mark')->result();
	    	
	    	$j++;
	    	$this->excel->getActiveSheet()->setCellValue($a_z[$i].''.$j, 'Student Name');
	    	
	    	$k = 1;
	    	foreach($exams as $exam){
				$this->excel->getActiveSheet()->setCellValue($a_z[$k].''.$j, $exam->exam_name);
				$k++;
			}
	    	$k = 1;
	    	
	    	$this->db->select('sms_student.name as student_name', 'sms_student.student_id');
	    	$this->db->join('sms_student', 'sms_student.student_id=sms_mark.student_id');
	    	$this->db->where('sms_mark.class_id', $class_id);
	    	$this->db->group_by('sms_mark.student_id');
	    	$students = $this->db->get('sms_mark')->result();
	    	
	    	$html .= '<tbody>';

	    	foreach($students as $student){

				$j++;
				$this->excel->getActiveSheet()->setCellValue($a_z[$i].''.$j, $student->student_name);
				
				$student_id = $student->student_id;
		    	$k = 1;
		    	foreach($exams as $exam){
					$exam_id = $exam->exam_id;
					$this->db->select('sms_mark.mark_obtained');
			    	$this->db->where('sms_mark.class_id', $class_id);
			    	$this->db->where('sms_mark.student_id', $student_id);
			    	$this->db->where('sms_mark.exam_id', $exam_id);
			    	$student_mark = $this->db->get('sms_mark')->row();
			    	
			    	if($student_mark->mark_obtained>'0'){
						$this->excel->getActiveSheet()->setCellValue($a_z[$k].''.$j, $student_mark->mark_obtained);
					}else{
						$this->excel->getActiveSheet()->setCellValue($a_z[$k].''.$j, '');
					}
					$k++;
				}
				$k = 1;
			}
			$j = 1;
		}*/

		$filename = 'marks.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache

		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');

	}
    
}
