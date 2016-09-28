<?php

class Calendar extends CI_Controller
{
    private $user_app_role;
    private $user_id;
    private $wp_company_id;

    function __construct()
    {
        parent::__construct();
        $this->load->model('request_model', '', TRUE);
        $this->load->library(array('session'));
        $this->load->helper(array('url'));
        $redirect_login_page = base_url() . 'user';
        if (!$this->session->userdata('user')) {
            redirect($redirect_login_page, 'refresh');

        }
        /*getting user's application role*/
        $user = $this->session->userdata('user');
        $this->user_id = $user->uid;
        $this->wp_company_id = $user->company_id;

        $sql = "select LOWER(ar.application_role_name) role
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                where ua.user_id = {$user->uid} and a.id = 3 limit 0, 1";
        $this->user_app_role = $this->db->query($sql)->row()->role;

    }

    public function index()
    {

        $data['title'] = 'Calendar';
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $data['user_app_role'] = $this->user_app_role;

        /*getting contractor list*/
        $data['contractors'] = '';
        if($this->user_app_role == 'admin' || $this->user_app_role == 'manager'){
            $sql = "SELECT uid, username
                    FROM users u LEFT JOIN users_application ua ON u.uid = ua.user_id
                             LEFT JOIN application_roles ar ON ar.application_id = ua.application_id AND ar.application_role_id = ua.application_role_id
                    WHERE u.company_id = {$this->wp_company_id} AND ar.application_id = 3 AND LOWER(ar.application_role_name) = 'contractor'";
            $data['contractors'] = $this->db->query($sql)->result();

        }
        $this->load->view('includes/header', $data);
        $this->load->view('calendar', $data);
        $this->load->view('includes/footer', $data);
    }

    /* draws a calendar */
    function get_calendar($month, $year)
    {		

        $user = $this->session->userdata('user');
        /*get all tasks started or ended in this month*/

        $first_date = date('Y-m-01', mktime(0, 0, 0, $month, 1, $year));
        $last_date = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));

        $mark_today = date('j');

		
        /*$query = "SELECT request.*, project.project_name
                  FROM   request, project
                  WHERE  request.company_id = {$this->wp_company_id} AND estimated_completion BETWEEN '{$first_date}' and '{$last_date}' AND request.project_id = project.id";*/

        //task #4109
        $query =  "SELECT request.*, project.project_name " .
                  "FROM   request, project " .
                  "WHERE  request.company_id = {$this->wp_company_id} AND request_date <= '{$last_date}' AND request_status != 2 AND request.project_id = project.id";

		 /*non admin / managers will be able to see their tasks only*/
        if($this->user_app_role != 'admin' && $this->user_app_role != 'manager'){

            $query .= " AND CONCAT(',',request.assign_developer_id,',') LIKE '%,{$this->user_id},%'";
        }

        $tasks = $this->db->query($query)->result();

        $today = date('Y-m-d');
        /* draw table */
        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

        /* table headings */
        $headings = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $calendar .= '<thead><tr class="calendar-row"><th class="calendar-day-head">' . implode('</th><th class="calendar-day-head">', $headings) . '</th></tr></thead>';

        /* days and weeks vars now ... */
        $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));

        /*we have to start the week from monday*/
        if ($running_day == 0) {
            $running_day = 6;
        } else {
            $running_day--;
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

            $list = "<ul>";
            foreach ($tasks as $task) {
                /*generating classes for this task*/
                $class = "";
                $tooltip = "";
                $tooltip_class = "";
                $contractor_names = array();
                $contractor_ids = array();
				$manager_names = array();
				$manager_ids = array();

                //task #4131
                if (($this_date == $task->estimated_completion && $this_date >= date('Y-m-d')) || ($task->estimated_completion < $this_date && $this_date == date('Y-m-d'))) {
                    /*class for project*/
                    $class .= " project-{$task->project_id}";

                    /*class for own task*/

					if($this->user_app_role == 'admin' || $this->user_app_role == 'manager'){
						if (in_array($this->user_id,explode(',',$task->assign_manager_id))) {
                        	$class .= " mytask";
						}
					}else{
						if (in_array($this->user_id,explode(',',$task->assign_developer_id))) {
                        	$class .= " mytask";
                    	}
					}
                    
                    /*getting contractors for this task*/
                    if($task->assign_developer_id){

                        $sql = "select * from users where uid in ({$task->assign_developer_id})";

                        $contractors = $this->db->query($sql)->result();
                        foreach($contractors as $c){
                            $contractor_names[] = $c->username;
                            $contractor_ids[] = $c->uid;
                        }
                    }
					/* Get the Assign Manager Name for this task */
					if($task->assign_manager_id){
						$manager_sql = "SELECT * FROM `users` WHERE uid in ({$task->assign_manager_id})";
						$managers = $this->db->query($manager_sql)->result();
						foreach($managers as $manager){
							$manager_names[] = $manager->username;
							$manager_ids[] = $manager->uid;
						}
					}

                    $class .= " contractor-".implode(" contractor-",$contractor_ids);
					$class .= " manager-".implode(" manager-",$manager_ids);

                    /*class for overdue tasks*/
                    if($task->estimated_completion < date('Y-m-d')){
                        $class .= " overdue";
                    }
                    /*the tooltip div*/
                    $tooltip_title = "<span class=\"{$tooltip_class}\">{$task->project_name} - {$task->request_no} - {$task->request_title}</span>";
                    $tooltip = "<div class='tooltip_description'
										style='display:none;'
										title='{$tooltip_title}'>" .
                        "<b>Person Responsible:</b>&nbsp".implode(", ",$contractor_names)."<br/><b>Project Manager:</b>".implode(", ",$manager_names)."<br>".
                        /*"<b>Contact Number:</b> {$task->phone_no}<br/>" .
                        "<b>Notes:</b><br>" . nl2br($task->note)*/
                        "</div>";
                    $list .= "<li class='{$class}'>" . $task->request_title . "{$tooltip}</li>";
                }
            }

        $list .= "</ul>";
        $calendar .= $list;

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