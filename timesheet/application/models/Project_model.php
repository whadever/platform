<?php

class Project_model extends CI_Model
{

    private $primary_key = 'id';
    private $table_project = 'project';
    private $table_request = 'request';
    private $table_task_hours = 'task_hours';
    private $table_users = 'users';


    function __construct()
    {
        parent::__construct();
    }

    function get_project_no()
    {
        $sql = "SELECT MAX(project_no) max_project_no FROM project";

        $query = $this->db->query($sql)->row();
        return $query->max_project_no;
    }
    
    function get_project_list_by_user()
    {
        $user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;
		$user_id = $user->uid;
		
		$this->db->where('company_id',$wp_company_id);
		$this->db->where('user_id',$user_id);
		$this->db->where('application_id','3');
        $tms_role = $this->db->get('users_application')->row()->application_role_id;
        
        $this->db->where('wp_company_id',$wp_company_id);
        if($tms_role=='3'){
        	$this->db->where("FIND_IN_SET ($user_id, assign_developer_id)");
		}else{
			$this->db->where("FIND_IN_SET ($user_id, assign_manager_id)");
		}
        $this->db->where("project_status",'1');
        $this->db->order_by('project_name');
        $result = $this->db->get($this->table_project);
        //echo $this->db->last_query();
        return $result;
    }

    function get_project_list()
    {
        $user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;

        $this->db->where('wp_company_id',$wp_company_id);
        $this->db->order_by('project_name');
        $result = $this->db->get($this->table_project);
        return $result;
    }


    public function project_list_search_count($project_name, $sort_by = 'id', $order_by = 'desc', $offset = 0, $limit = 10, $get = NULL)
    {

        $this->db->like("project_name", $project_name);
        if ($sort_by == 'project_status') {
            $this->db->order_by($sort_by . ' ' . $order_by . ' , project_name asc');
        } else {
            $this->db->order_by($sort_by, $order_by);
        }

        $this->db->limit($limit, $offset);

        $result = $this->db->get($this->table_project);
        //echo  $this->db->last_query();
        return $result;

    }

    function delete_project_with_requests_notes($pid)
    {
        /* echo $pid;

        $request_sql = "select id from request where project_id=".$pid;
        $request_res= mysql_query($request_sql);
        while ($request_row = mysql_fetch_assoc($request_res)) {
             $request_ids[]= $request_row['id'];
        }
        print_r($request_ids);


         *
         */
        $this->db->where($this->primary_key, $pid);
        $this->db->delete($this->table_project);
    }

    function close_project($pid)
    {
        $data = array(
            'project_status' => 2
        );

        $this->db->where('id', $pid);
        $this->db->update('project', $data);


    }

    function update_project($pid, $person)
    {
        $this->db->where($this->primary_key, $pid);
        $this->db->update($this->table_project, $person);
    }

    function get_project_detail($pid)
    {
        $this->db->select('project.*, company.id as company_id, company.company_name, users.username');
        $this->db->from('project');
        $this->db->join('company', 'project.company_id = company.id', 'left');
        $this->db->join('users', 'project.created_by = users.uid', 'left');
        $this->db->where('project.' . $this->primary_key, $pid);
        $query = $this->db->get();
        return $query;

        //return $this->db->get($this->table_project);


    }

    public function get_assign_manager($assign_manager_id)
    {
        $assign_manager = explode(",", $assign_manager_id);

        if ($assign_manager[0] > 0) {
            $manager_name = array();
            foreach ($assign_manager as $manager_id) {
                $this->db->select('username');
                $this->db->where('uid', $manager_id);
                $manager = $this->db->get($this->table_users)->row();
                $manager_name[] = $manager->username;

            }
            $manager_name_string = implode(", ", $manager_name);

        } else {
            $manager_name_string = 'No Manager Assigned';
        }

        return $manager_name_string;
    }

    public function get_assign_developer($assign_developer_id)
    {
        $assign_developer = explode(",", $assign_developer_id);
        if ($assign_developer[0] > 0) {
            $developer_name = array();
            foreach ($assign_developer as $developer_id) {
                $this->db->select('username');
                $this->db->where('uid', $developer_id);
                $developer = $this->db->get($this->table_users)->row();
                $developer_name[] = $developer->username;
            }
            $developer_name_string = implode(", ", $developer_name);
        } else {
            $developer_name_string = 'No contractor Assigned';
        }
        return $developer_name_string;
    }


    function company_name()
    {
        $this->db->order_by('compname', "ASC");
        return $this->db->get($this->table_project);
    }

    function company_list_print()
    {
        $this->db->order_by('compname', "ASC");
        return $this->db->get($this->table_project);
    }

    function company_load($company_id)
    {
        $this->db->where($this->primary_key, $company_id);
        return $this->db->get($this->table_project)->row();
    }


    function get_company_list()
    {
        $query = $this->db->query("SELECT comp.`cid`, comp.`compname` FROM company_profile comp ORDER BY comp.`compname`");
        $rows = array();
        foreach ($query->result() as $row) {
            $rows[$row->cid] = $row->compname;
        }
        return $rows;
    }


    function check_company_name_exists($company_name)
    {
        $this->db->where('compname', $company_name);
        $this->db->from($this->table_project);
        return $this->db->count_all_results();
    }

    public function company_list_search_count_all($get = NULL)
    {
        $cname = '';
        $cond = array();
        if ($get) {
            $cname = trim($get['cname']);
            if (!empty($cname)) $cond[] = 'compname LIKE "%' . $cname . '%"';
        }
        $sql = "SELECT COUNT(compname) total_rows FROM company_profile";
        if (!empty($cond)) $sql .= ' WHERE ' . implode(' AND ', $cond);

        $query = $this->db->query($sql)->row();
        return $query->total_rows;
    }


    public function file_insert($file)
    {
        $this->db->insert($this->table_file, $file);
        return $this->db->insert_id();
    }

    function get_project_open_bug($pid, $user_id, $role_id)
    {
        $this->db->select('`id`, `request_no`, `request_title`, `request_status`');
        $this->db->where('project_id', $pid);
        $this->db->where('request_status', 1);
        if ($role_id == 3) {
            $this->db->where("FIND_IN_SET($user_id , assign_developer_id)");
        }
        return $this->db->get($this->table_request);
    }

    function get_project_close_request($pid, $user_id, $role_id)
    {
        $this->db->select('`id`, `request_no`, `request_title`, `request_status`');
        $this->db->where('project_id', $pid);
        $this->db->where('request_status', 2);
        if ($role_id == 3) {
            $this->db->where("FIND_IN_SET($user_id , assign_developer_id)");
        }
        return $this->db->get($this->table_request);
    }

    public function get_project_hours($pid)
    {
        //$result = $this->db->get($this->table_task_hours);
        //return $result;
        $this->db->select('task_hours.*, request.request_title as task_title, users.username');
        $this->db->from('task_hours');
        $this->db->join('request', 'request.id = task_hours.task_id', 'left');
        $this->db->join('project', 'project.id = request.project_id', 'left');
        $this->db->join('users', 'task_hours.created_by = users.uid', 'left');
        $this->db->where('project.' . $this->primary_key, $pid);
        $query = $this->db->get();
        return $query;

    }

    public function get_project_total_hours($pid)
    {
        //$result = $this->db->get($this->table_task_hours);
        //return $result;
        $this->db->select('sum(task_hours.hour) as total_hours, sum(task_hours.minute) as total_minutes');
        $this->db->from('task_hours');
        $this->db->join('request', 'request.id = task_hours.task_id', 'left');
        $this->db->join('project', 'project.id = request.project_id', 'left');
        $this->db->where('project.' . $this->primary_key, $pid);
        $query = $this->db->get();
        return $query;

    }


}

?>