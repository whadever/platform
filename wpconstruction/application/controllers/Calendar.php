<?php

class Calendar extends CI_Controller
{

    private $user_app_role = '';

    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model', '', TRUE);
        $this->load->model('job_model', '', TRUE);
        date_default_timezone_set("NZ");
        $this->load->library(array('table', 'form_validation', 'session', 'wbs_helper'));
        $this->load->helper(array('url'));
        $redirect_login_page = base_url() . 'user';
        if (!$this->session->userdata('user')) {
            redirect($redirect_login_page, 'refresh');

        }

        /*getting user's application role*/
        $user = $this->session->userdata('user');
        $sql = "select LOWER(ar.application_role_name) role
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                where ua.user_id = {$user->uid} and a.id = 5 limit 0, 1";
        $this->user_app_role = $this->db->query($sql)->row()->role;

    }
    
    function statuschange($id)
    {
    	$data = array(
			'development_task_status' => 1
	    );
    	$this->db->where('id', $id);
		$this->db->update('construction_development_task',$data);  
    }

    public function index()
    {

        $data['title'] = 'Calendar';
        $data['user_app_role'] = $this->user_app_role;
        $user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;
        $data['user'] = $user;
        /*getting job list*/
        $jobs = $this->db->query("select * from construction_development where wp_company_id='$wp_company_id'")->result();
        //task #4433
        $job_arr = array();
        foreach($jobs as $job){
            if($job->parent_unit){
                if(!array_key_exists($job->parent_unit, $job_arr)){
                    $job_arr[$job->parent_unit] = array(
                        'children' => array(
                            $job
                        )
                    );

                }else{
                    $job_arr[$job->parent_unit]['children'][] = $job;
                }
            }else{
                if(array_key_exists($job->id, $job_arr)){
                    $job_arr[$job->id] = array(
                        'job_info'=>$job,
                        'children' => $job_arr[$job->id]['children']
                    );
                }else{
                    $job_arr[$job->id] = array(
                        'job_info'=>$job,
                        'children' => array()
                    );
                }
            }
        }

        $data['jobs'] = $job_arr;

        $data['contractors'] = ($this->user_app_role == 'manager' || $this->user_app_role == 'builder' || $this->user_app_role == 'admin') ? $this->db->query("select * from contact_contact_list where wp_company_id = {$wp_company_id} order by contact_first_name")->result() : array();
        //$data['maincontent'] = $this->load->view('overview',$data,true);
        $this->load->view('includes/header', $data);
        $this->load->view('calendar/home', $data);
        $this->load->view('includes/footer', $data);
    }

    /* draws a calendar */
    function get_calendar($month, $year)
    {
        $user = $this->session->userdata('user');
        
		$wp_company_id = $user->company_id;

        /*log*/
        $this->wbs_helper->log('Calendar',"Viewed calendar for <b>{$month}/{$year}</b>");

        /*get all tasks started or ended in this month*/
        $first_date = date('Y-m-01', mktime(0, 0, 0, $month, 1, $year));
        $last_date = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));
        $mark_today = date('j');
        $own_job_only_query = '';
        if($this->user_app_role == 'contractor'){
            $own_job_only_query = " AND contact_contact_list.system_user_id = {$user->uid}";
        }
        /*we have to show all pending tasks irrelevant of their start / end date (task #4023)*/
        /*We don't need to show completed task on calendar (task #4026)*/
        $query = "SELECT task.task_name,
                         task.id                        task_id,
                         task.development_id            dev_id,
                         task.task_start_date           start_date,
                         task.actual_completion_date    end_date,
                         task.development_task_status,
                         task.note,
                         dev.development_name,
						 phase.phase_name,
						 phase.construction_phase,
						 contact_contact_list.id contractor_id,
                         contact_contact_list.contact_first_name first_name,
                         contact_contact_list.contact_last_name last_name,
                         contact_contact_list.contact_phone_number phone_no,
                         contact_contact_list.contact_mobile_number mobile_no,
                         contact_contact_list.system_user_id,
                         unit_job.development_name unit_name
                  FROM   construction_development_task task LEFT JOIN construction_development dev ON task.development_id = dev.id
						 LEFT JOIN construction_development_phase phase ON phase.id = task.phase_id
                         LEFT JOIN contact_contact_list on task.task_person_responsible = contact_contact_list.id
                         LEFT JOIN construction_development unit_job ON dev.parent_unit = unit_job.id
                  WHERE  dev.wp_company_id='$wp_company_id' AND task.task_start_date  <= '{$last_date}' AND task_start_date <> '0000-00-00' {$own_job_only_query}";

        $tasks = $this->db->query($query)->result();
        //echo $this->db->last_query();
        $today = date('Y-m-d');
        /* draw table */
        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

        /* table headings */
        $headings = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

        /*the calendar starts from Sunday. only for HORNCASTLE.WCLP.CO.NZ
         Task #4021*/
        if($_SERVER['SERVER_NAME'] == 'horncastle.wclp.co.nz'){
            $headings = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        }

        $calendar .= '<thead><tr class="calendar-row"><th class="calendar-day-head">' . implode('</th><th class="calendar-day-head">', $headings) . '</th></tr></thead>';

        /* days and weeks vars now ... */
        $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));

        /*we have to start the week from monday*/
        /*except for HORNCASTLE.WCLP.CO.NZ (task #4021)*/
        if($_SERVER['SERVER_NAME'] != 'horncastle.wclp.co.nz'){
            if ($running_day == 0) {
                $running_day = 6;
            } else {
                $running_day--;
            }
        }


        $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();

        /* row for week one */
        $calendar .= '<tbody>';
        $calendar .= '<tr class="calendar-row">';

        /* print "blank" days until the first of the current week */
        $mday = 1;
        for ($x = 0; $x < $running_day; $x++):
            $calendar .= '<td class="calendar-day-np"> </td>';
            $days_in_this_week++;
            $mday++;
        endfor;
        /* keep going with days.... */
        for ($list_day = 1; $list_day <= $days_in_month; $list_day++):
            $this_date = date('Y-m-d', mktime(0, 0, 0, $month, $list_day, $year));
            $today_class = ($list_day == $mark_today) ? ' today' : '';
            $calendar .= "<td class='calendar-day {$today_class}'>";
            /* add in the day number */
            $calendar .= '<div class="day-number">' . $list_day . '</div>';

            /*printing tasks for this date*/
            /* hide all the tasks on Saturday and Sunday (task #4022) */
            if(!in_array(date_create_from_format('Y-m-d',$this_date)->format('w'), array(0,6))){

                $list = "<ul>";
                foreach ($tasks as $task) {
                    if ($task->end_date == '0000-00-00') {
                        $task->end_date = $task->start_date;
                    }
                    /*generating classes for this task*/
                    $class = "";
                    $tooltip = "";
                    $task_is_in_this_date = false;
                    $tooltip_class = "";

                    /*class for status*/
                    //if (($task->start_date <= $this_date && $task->end_date >= $this_date) || ($task->end_date < $this_date && $this_date <= $today)) {
                    //task #4132
                    if (($task->start_date <= $this_date && $task->end_date >= $this_date && $this_date >= $today) || ($task->end_date < $this_date && $this_date == $today)) {
                        $task_is_in_this_date = true;
                        $checked = '';
                        if ($task->development_task_status == 1) {
                            $class .= "status-complete";
                            $tooltip_class = "status-complete";
                            $checked = 'checked';
                        } elseif ($task->end_date < $today) {
                            $class .= "status-overdue";
                            $tooltip_class = "status-overdue";
                        } elseif ($task->start_date <= $today && $task->end_date >= $today) {
                            $class .= "status-ontheway";
                            $tooltip_class = "status-ontheway";
                        } elseif ($task->start_date > $today) {
                            $class .= "status-pending";
                            $tooltip_class = "status-pending";
                        }

                    }

                    if ($task_is_in_this_date) {
                        /*class for job*/
                        $class .= " job-{$task->dev_id} {$task->construction_phase} contractor-{$task->contractor_id} mytask-{$task->system_user_id}";

                        /*class for own task*/
                        if ($task->system_user_id && $task->system_user_id == $user->uid) {
                            $class .= " mytask";
                        }
                        if($task->unit_name==''){
							$unit_name = '';
						}else{
							$unit_name = ' - '.$task->unit_name;
						}
                        /*the tooltip div*/
                        $tooltip_title = "<span class=\"{$tooltip_class}\">{$task->development_name}{$unit_name} - {$task->phase_name} - {$task->task_name}</span>";
                        $tooltip = "<div class='tooltip_description'
										style='display:none;'
										title='{$tooltip_title}'>" .
                            "<b>Person Responsible:</b> {$task->first_name} {$task->last_name}<br/>" .
                            "<b>Contact Number:</b> {$task->phone_no}<br/>" .
                            "<b>Mobile Number:</b> {$task->mobile_no}<br/>" .
                            "<b>Notes:</b><br>" . nl2br($task->note)
                            . "</div>";
                        $list .= "<li class='{$class}'>" . $task->task_name . " <input ".$checked." type='checkbox' onclick='statuschange({$task->task_id})' />{$tooltip}</li>";
                    }
                }

                $list .= "</ul>";
                $calendar .= $list;
            }

        $calendar .= '</td>';
        if ($running_day == 6):
            $calendar .= '</tr>';
            if (($day_counter + 1) != $days_in_month):
                $calendar .= '<tr class="calendar-row">';
            endif;
            $running_day = -1;
            $days_in_this_week = 0;
        endif;
        $days_in_this_week++;
        $running_day++;
        $day_counter = $day_counter + 1;
        endfor;

        /* finish the rest of the days in the week */
        if ($days_in_this_week < 8):
            for ($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar .= '<td class="calendar-day-np"> </td>';
            endfor;
        endif;

        /* final row */
        $calendar .= '</tr>';

        /* end the table */
        $calendar .= '<tbody></table>';

        /* all done, return result */
        echo $calendar;
        exit;
    }

}