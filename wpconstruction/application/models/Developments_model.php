<?php

class Developments_model extends CI_Model
{

    private $primary_key = 'id';
    private $table_development = 'construction_development';
    private $table_file = 'construction_file';
    private $table_notes = 'construction_notes';
    private $table_development_photo_notes = 'construction_development_photo_notes';
    private $table_development_photos = 'construction_development_photos';
    private $table_development_task = 'construction_development_task';
    private $table_users = 'users';
    private $table_development_phase = 'construction_development_phase';
    private $table_stage_phase = 'construction_stage_phase';
    private $table_development_documents = 'construction_development_documents';
    private $table_development_milestone = 'construction_development_milestone';
    private $table_contacts = 'contact_contact_list';


    function __construct()
    {
        parent::__construct();
    }

    public function development_document_search($development_id, $search)
    {
        $this->db->select('`id`,`filename_custom`,`created`');
        $this->db->where('development_id', $development_id);
        if (isset($search)) {
            $search1 = urldecode($search);
            $this->db->where('filename_custom', $search1);
            $this->db->order_by('filename_custom', 'ASC');
        } else {
            $this->db->order_by('id', 'DESC');
        }
        $dev_documents = $this->db->get($this->table_development_documents)->result();

        $ppp = '<table><thead><tr><th>Document</th><th>Date Added</th></tr></thead><tbody>';
        foreach ($dev_documents as $dev_document) {
            $ppp .= '<tr id="document_' . $dev_document->id . '" onclick="loadDocument(' . $dev_document->id . ')">';
            $ppp .= '<td>' . $dev_document->filename_custom . '</td>';
            $ppp .= '<td>' . date('d-m-Y', $dev_document->created) . '</td>';
            $ppp .= '<input type="hidden" value="' . $dev_document->id . '" />';
            $ppp .= '</tr>';
        }
        $ppp .= '</tbody></table>';
        echo $ppp;
    }

    public function get_developments_list_contractor()
    {

        $this->db->select('`id`,`development_name`');
        $this->db->order_by('development_name', 'ASC');
        $devs = $this->db->get($this->table_development)->result();

        $i = 1;
        foreach ($devs as $dev) {
            $user = $this->session->userdata('user');
            $user_uid = $user->uid;
            $this->ums->select('hds_dev_permission');
            $this->ums->where('user_id', $user_uid);
            $this->ums->where('application_id', '1');
            $user = $this->ums->get('users_application')->row();
            $user_permissions = $user->hds_dev_permission;
            $user_permission_arr = explode(",", $user_permissions);
            for ($a = 0; $a < count($user_permission_arr); $a++) {
                if ($user_permission_arr[$a] == $dev->id) {
                    $j = 1;
                    $development_id = $dev->id;
                    $development_name = $dev->development_name;
                    $this->db->select('MIN(`development_task_status`) as all_task_status');
                    $this->db->where('development_id', $development_id);
                    $development_task_status = $this->db->get($this->table_development_task)->result();

                    $this->db->select('MIN(`phase_status`) as aphase_status');
                    $this->db->where('development_id', $development_id);
                    $development_stage_phase_status = $this->db->get($this->table_stage_phase)->result();

                    $dev_info[$i][$j] = $development_id;
                    if ($development_task_status[0]->all_task_status == 0 or $development_task_status[0]->all_task_status == '' or $development_stage_phase_status[0]->aphase_status == 0 or $development_stage_phase_status[0]->aphase_status == '') {
                        $dev_info[$i][$j + 1] = 0;
                    } else {
                        $dev_info[$i][$j + 1] = 1;
                    }
                    $dev_info[$i][$j + 2] = $development_name;
                    //$dev_info[$i][$j+3] = $development_stage_phase_status[0]->aphase_status;
                    $i = $i + 1;
                } // if condition end;
            } // for loop end;
        }
        return $dev_info;

    }

    public function change_development_status_contractor($status, $development_city, $development_name)
    {

        if ($status != 2) {
            $this->db->select('`id`,`development_name`');
            if ($development_city != '0') {
                $this->db->where('development_city', $development_city);
            }
            if (!empty($development_name) && $development_name != 'ZiaurRahman123') {
                $this->db->like('development_name', $development_name);
            }
            $this->db->order_by('development_name', 'ASC');
            $devs = $this->db->get($this->table_development)->result();

            $i = 1;
            if (!empty($devs)) {
                foreach ($devs as $dev) {
                    $user = $this->session->userdata('user');
                    $user_uid = $user->uid;
                    $this->ums->select('hds_dev_permission');
                    $this->ums->where('user_id', $user_uid);
                    $this->ums->where('application_id', '1');
                    $user = $this->ums->get('users_application')->row();
                    $user_permissions = $user->hds_dev_permission;
                    $user_permission_arr = explode(",", $user_permissions);
                    for ($a = 0; $a < count($user_permission_arr); $a++) {
                        if ($user_permission_arr[$a] == $dev->id) {
                            $j = 1;
                            $development_id = $dev->id;
                            $development_name = $dev->development_name;
                            $this->db->select('MIN(`development_task_status`) as all_task_status');
                            $this->db->where('development_id', $development_id);
                            $development_task_status = $this->db->get($this->table_development_task)->result();

                            $this->db->select('MIN(`phase_status`) as aphase_status');
                            $this->db->where('development_id', $development_id);
                            $development_stage_phase_status = $this->db->get($this->table_stage_phase)->result();

                            $dev_info[$i][$j] = $development_id;
                            if ($development_task_status[0]->all_task_status == 0 or $development_task_status[0]->all_task_status == '' or $development_stage_phase_status[0]->aphase_status == 0 or $development_stage_phase_status[0]->aphase_status == '') {
                                $dev_info[$i][$j + 1] = 0;
                            } else {
                                $dev_info[$i][$j + 1] = 1;
                            }
                            $dev_info[$i][$j + 2] = $development_name;
                            //$dev_info[$i][$j+3] = $development_stage_phase_status[0]->aphase_status;
                            $i = $i + 1;
                        } // if condition end;
                    } // for loop end;
                }
                return $dev_info;
            }


        } elseif ($status == 2 && $development_city != '0') {
            $this->db->select('`id`,`development_name`');
            if ($development_city != '0') {
                $this->db->where('development_city', $development_city);
            }
            if (!empty($development_name) && $development_name != 'ZiaurRahman123') {
                $this->db->like('development_name', $development_name);
            }
            $this->db->order_by('development_name', 'ASC');
            $devs = $this->db->get($this->table_development)->result();

            $i = 1;
            if (!empty($devs)) {
                foreach ($devs as $dev) {
                    $user = $this->session->userdata('user');
                    $user_uid = $user->uid;
                    $this->ums->select('hds_dev_permission');
                    $this->ums->where('user_id', $user_uid);
                    $this->ums->where('application_id', '1');
                    $user = $this->ums->get('users_application')->row();
                    $user_permissions = $user->hds_dev_permission;
                    $user_permission_arr = explode(",", $user_permissions);
                    for ($a = 0; $a < count($user_permission_arr); $a++) {
                        if ($user_permission_arr[$a] == $dev->id) {
                            $j = 1;
                            $development_id = $dev->id;
                            $development_name = $dev->development_name;
                            $this->db->select('MIN(`development_task_status`) as all_task_status');
                            $this->db->where('development_id', $development_id);
                            $development_task_status = $this->db->get($this->table_development_task)->result();

                            $this->db->select('MIN(`phase_status`) as aphase_status');
                            $this->db->where('development_id', $development_id);
                            $development_stage_phase_status = $this->db->get($this->table_stage_phase)->result();

                            $dev_info[$i][$j] = $development_id;
                            if ($development_task_status[0]->all_task_status == 0 or $development_task_status[0]->all_task_status == '' or $development_stage_phase_status[0]->aphase_status == 0 or $development_stage_phase_status[0]->aphase_status == '') {
                                $dev_info[$i][$j + 1] = 0;
                            } else {
                                $dev_info[$i][$j + 1] = 1;
                            }
                            $dev_info[$i][$j + 2] = $development_name;
                            //$dev_info[$i][$j+3] = $development_stage_phase_status[0]->aphase_status;
                            $i = $i + 1;
                        } // if condition end;
                    } // for loop end;
                }
                //print_r($dev_info); exit;
                return $dev_info;
            }


        } elseif ($development_name == 'ZiaurRahman123') {
            $this->db->order_by('development_name', 'ASC');
            return $this->db->get($this->table_development)->result();
        } else {
            if (!empty($development_name)) {
                $this->db->like('development_name', $development_name);
            }
            $this->db->order_by('development_name', 'ASC');
            return $this->db->get($this->table_development)->result();
        }

    }

    function get_project_no()
    {
        $sql = "SELECT MAX(project_id) max_project_no FROM project";

        $query = $this->db->query($sql)->row();
        return $query->max_project_no;
    }

    public function add_new_milestone($add_new_milestone)
    {

        $this->db->insert($this->table_development_milestone, $add_new_milestone);
        return $this->db->insert_id();
    }

    public function update_milestone($id, $update_milestone)
    {
        $this->db->where($this->primary_key, $id);
        $this->db->update($this->table_development_milestone, $update_milestone);
    }

    function get_development_milestone_detail($did)
    {

        $this->db->where('development_id', $did);
        return $this->db->get($this->table_development_milestone);

    }

    public function development_update($development_id, $development_update)
    {
        $this->db->where($this->primary_key, $development_id);
        $this->db->update($this->table_development, $development_update);
    }

    function development_task_delete($task_id)
    {
        $this->db->where($this->primary_key, $task_id);
        $this->db->delete($this->table_development_task);
    }

    public function development_feature_photo_insert($file)
    {
        $this->db->insert($this->table_file, $file);
        return $this->db->insert_id();
    }

    function development_phase_delete($phase_id)
    {
        $this->db->where($this->primary_key, $phase_id);
        $this->db->delete($this->table_development_phase);

        $this->db->where('phase_id', $phase_id);
        $this->db->delete($this->table_development_task);
    }

    public function project_save($person)
    {

        $this->db->insert($this->table_project, $person);
        return $this->db->insert_id();
    }

    public function project_count_all()
    {
        return $this->db->count_all($this->table_project);
    }

    public function get_developments_list()
    {
        $this->db->order_by('development_name', 'ASC');
        return $this->db->get($this->table_development);
    }

    public function get_development_overview_area()
    {

        $this->db->select('`id`,`development_name`');
        $this->db->order_by('id', 'DESC');
        return $this->db->get($this->table_development);

    }

    public function get_developments_list_overview()
    {

        //if (isset($get) && !empty($get['development_name']) ){
        //$dname = $get['development_name'];
        //}

        //if (isset($get) && !empty($get['development_city']) ){
        //$development_city = $get['development_city'];
        //}

        //if (!empty($dname)) $this->db->like('development_name', $dname);
        //if (!empty($development_city)) $this->db->where('development_city', $development_city);

        $this->db->select('`id`,`development_name`');
        $this->db->order_by('development_name', 'ASC');
        $devs = $this->db->get($this->table_development)->result();

        $i = 1;
        foreach ($devs as $dev) {
            $j = 1;
            $development_id = $dev->id;
            $development_name = $dev->development_name;
            $this->db->select('MIN(`development_task_status`) as all_task_status');
            $this->db->where('development_id', $development_id);
            $development_task_status = $this->db->get($this->table_development_task)->result();

            $this->db->select('MIN(`phase_status`) as aphase_status');
            $this->db->where('development_id', $development_id);
            $development_stage_phase_status = $this->db->get($this->table_stage_phase)->result();

            $dev_info[$i][$j] = $development_id;
            if ($development_task_status[0]->all_task_status == 0 or $development_task_status[0]->all_task_status == '' or $development_stage_phase_status[0]->aphase_status == 0 or $development_stage_phase_status[0]->aphase_status == '') {
                $dev_info[$i][$j + 1] = 0;
            } else {
                $dev_info[$i][$j + 1] = 1;
            }
            $dev_info[$i][$j + 2] = $development_name;
            //$dev_info[$i][$j+3] = $development_stage_phase_status[0]->aphase_status;
            $i = $i + 1;
        }
        return $dev_info;


        //$this->db->order_by('id', 'DESC');
        //return $this->db->get($this->table_development);
    }

    function delete($cid)
    {
        $this->db->where($this->primary_key, $cid);
        $this->db->delete($this->table_project);
    }

    function development_photo_delete($development_photo_id)
    {
        $this->db->where($this->primary_key, $development_photo_id);
        $this->db->delete($this->table_development_photos);
    }

    function development_document_delete($dev_document_id)
    {
        $this->db->where($this->primary_key, $dev_document_id);
        $this->db->delete($this->table_development_documents);
    }

    function milestone_delete($mid)
    {
        $this->db->where($this->primary_key, $mid);
        $this->db->delete($this->table_development_milestone);
    }

    function update($cid, $person)
    {
        $this->db->where($this->primary_key, $cid);
        $this->db->update($this->table_project, $person);
    }


    function get_development_detail($pid)
    {

        $this->db->where($this->primary_key, $pid);
        return $this->db->get($this->table_development);

    }

    function get_defelopment_feature_photo($fid)
    {

        $this->db->where('fid', $fid);
        return $this->db->get($this->table_file);

    }

    function get_feature_photo($feature_photo_id)
    {

        $this->db->where('fid', $feature_photo_id);
        return $this->db->get($this->table_file)->row();

    }

    function get_development_number_of_stage($pid)
    {
        $this->db->select('number_of_stages');
        $this->db->where($this->primary_key, $pid);
        return $this->db->get($this->table_development)->row()->number_of_stages;

    }

    public function getDevelopmentPhotos($pid, $month = '', $construction_phase, $user_role)
    {

        $this->db->select('construction_development_photos.*, users.username as username, construction_development.development_name');
        //$this->db->where('project_id', $pid);
        $this->db->where("project_id in (".implode(',',$pid).")"); /*task #4581*/
        $this->db->where('construction_phase', $construction_phase);

        if ($user_role == 'investor') {
            $this->db->where('photo_permission != ', 2);
        }

        if ($user_role == 'contractor' or $user_role == 'builder') {
            $this->db->where('photo_permission ', 1);
        }

        /*if($month != '' && $month != 'all'){
            $this->db->where("CONCAT_WS('-',YEAR(FROM_UNIXTIME(construction_development_photos.created)), MONTH(FROM_UNIXTIME(construction_development_photos.created))) = ",$month);
        }*/

        $this->db->join('users', 'users.uid = construction_development_photos.uid');
        /*task #4581*/
        $this->db->join('construction_development','construction_development.id = construction_development_photos.project_id');
        $this->db->order_by("id", "desc");
        return $this->db->get($this->table_development_photos);
    }

    public function getDevelopmentPhoto($photo_id)
    {

        $this->db->select('construction_development_photos.*, users.username');
        $this->db->where('id', $photo_id);
        $this->db->join('users', 'users.uid = construction_development_photos.uid');

        return $this->db->get($this->table_development_photos);
    }

    public function getDevelopmentPhotoDetail($photo_id)
    {
        $this->db->select('construction_development_photos.*, users.username as username');
        $this->db->where('id', $photo_id);
        $this->db->join('users', 'users.uid = construction_development_photos.uid');
        return $this->db->get($this->table_development_photos)->row();
    }

    function get_project_notes($pid)
    {
        $this->db->select('notes.*, users.name as username');
        $this->db->from('notes');
        $this->db->where('project_id', $pid);
        $this->db->join('users', 'users.uid = notes.notes_by');
        $this->db->order_by("nid", "desc");
        $query = $this->db->get();
        return $query;
        //return $this->db->get($this->table_notes);


    }

    function get_developments_notes($pid)
    {
        $this->db->select('notes.*, users.name as username');
        $this->db->from('notes');
        $this->db->where('project_id', $pid);
        $this->db->join('users', 'users.uid = notes.notes_by');
        $this->db->order_by("nid", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        return $query;

    }

    function get_project_search_notes($pid, $search_notes)
    {
        $this->db->select('notes.*, users.name as username');
        $this->db->from('notes');
        $this->db->where('project_id', $pid);
        $this->db->like('notes_title', $search_notes);
        $this->db->join('users', 'users.uid = notes.notes_by');
        $this->db->order_by("nid", "desc");
        $query = $this->db->get();
        return $query;
        //return $this->db->get($this->table_notes);

    }

    function get_others_project_search_notes($pid, $search_notes)
    {
        $this->db->select('notes.*, users.name as username');
        $this->db->from('notes');
        $this->db->where('project_id', $pid);
        $this->db->like('notes_title', $search_notes);
        $this->db->join('users', 'users.uid = notes.notes_by');
        $this->db->order_by("nid", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        return $query;

    }


    function get_note_detail($nid)
    {
        $this->db->select('notes.*, users.name as username');
        $this->db->from('notes');
        $this->db->where('nid', $nid);
        $this->db->join('users', 'users.uid = notes.notes_by');

        $query = $this->db->get();
        return $query;

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


    public function development_phase_add($data)
    {
        $this->db->insert($this->table_development_phase, $data);
    }

    public function development_phase_update($phase_id, $data)
    {
        $this->db->where($this->primary_key, $phase_id);
        $this->db->update($this->table_development_phase, $data);
    }

    public function development_task_update($task_id, $data)
    {
        $this->db->where($this->primary_key, $task_id);
        $this->db->update($this->table_development_task, $data);
    }

    public function development_task_add($data)
    {
        $this->db->insert($this->table_development_task, $data);
    }

    public function company_list_search_count($sort_by = 'cid', $order_by = 'desc', $offset = 0, $limit = 10, $get = NULL)
    {
        $cname = '';
        if (isset($get) && !empty($get['cname'])) {
            $cname = $get['cname'];
        }
        $this->db->select('company_profile.*,file.*');
        $this->db->join('file', 'company_profile.fid = file.fid', 'left');
        if (!empty($cname)) $this->db->like('compname', $cname);
        $this->db->order_by($sort_by, $order_by);
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table_project);
    }

    public function file_insert($file)
    {
        $this->db->insert($this->table_file, $file);
        return $this->db->insert_id();
    }

    function get_project_open_bug($pid)
    {
        $this->db->select('`id`, `request_no`, `request_title`, `request_status`');
        $this->db->where('project_id', $pid);
        $this->db->where('request_status', 1);
        return $this->db->get($this->table_request);
    }

    function get_project_close_request($pid)
    {
        $this->db->select('`id`, `request_no`, `request_title`, `request_status`');
        $this->db->where('project_id', $pid);
        $this->db->where('request_status', 2);
        return $this->db->get($this->table_request);
    }

    public function project_photo_insert($file)
    {
        $this->db->insert($this->table_development_photos, $file);
        return $this->db->insert_id();
    }

    function save_project_photo_info($photo_insert_id, $photo_info)
    {

        $this->db->where('id', $photo_insert_id);
        $this->db->update($this->table_development_photos, $photo_info);

    }


    public function insert_development_note($file)
    {
        $this->db->insert($this->table_notes, $file);

    }


    public function get_stage_list($pid)
    {
        $this->db->select('`id`,`number_of_stages`');
        $this->db->where('id', $pid);
        return $this->db->get($this->table_development);

    }

    public function get_phase_info($development_id, $stage_no)
    {
        $this->db->select('`id`,`phase_name`,`phase_length`,`planned_start_date`,`planned_finished_date`,`actual_finished_date`,`phase_status`');
        $this->db->where('development_id', $development_id);
        $this->db->where('stage_no', $stage_no);
        return $this->db->get($this->table_stage_phase);
    }


    public function update_status($phase_id, $status)
    {
        if ($status == 1) {
            $now = date('Y-m-d');
        } else {
            $now = '0000-00-00';
        }
        $phase_data = array('phase_status' => $status, 'actual_finished_date' => $now);
        $this->db->where('id', $phase_id);
        $this->db->update($this->table_stage_phase, $phase_data);
    }

    public function get_development_phase_info($development_id, $construction_phase)
    {
        $this->db->select('construction_development_phase.`id`,construction_development_phase.`phase_name`, construction_development_phase.`dont_use_dependency`, `construction_job_phase_dependency.dependency_phase_id`, `construction_job_phase_dependency.dependency_phase_name`, construction_development_phase.`phase_person_responsible`, `contact_contact_list.id contact_id`, `contact_contact_list.contact_first_name`, `contact_contact_list.contact_last_name`, `contact_contact_list.system_user_id` , planned_start_date,  planned_finished_date, actual_start_date, actual_finished_date, phase_status, construction_development_phase.note note, construction_template_phase.dependency, phase2.phase_name dependency_phase_name, task.task_name dependency_task');
        $this->db->join('contact_contact_list', 'contact_contact_list.id = construction_development_phase.phase_person_responsible', 'left');
        $this->db->join('construction_job_phase_dependency', 'construction_job_phase_dependency.dependency=construction_development_phase.id', 'left');
        //$this->db->join('construction_template_phase phase', 'phase.template_id = construction_development_phase.template_id', 'left');
        $this->db->join('construction_template_phase', 'construction_template_phase.phase_no = construction_development_phase.phase_no AND construction_template_phase.template_id = construction_development_phase.template_id', 'left');
        $this->db->join('construction_template_phase phase2', 'phase2.id = construction_template_phase.dependency', 'left');
        //task #4200
        $this->db->join('construction_template_task task', 'task.id = construction_template_phase.task_dependency', 'left');
        $this->db->where('development_id', $development_id);
        $this->db->where('construction_phase', $construction_phase);
        //$this->db->group_by('phase_no');
        //$this->db->order_by('construction_template_phase.ordering', 'ASC');
        $this->db->order_by('construction_development_phase.ordering', 'ASC');
        //$this->db->order_by('planned_start_date', 'ASC');
        return $this->db->get($this->table_development_phase);

        //echo $this->db->last_query();

    }

    public function get_development_stage_info($development_id)
    {
        $this->db->select('`stage_no` , MIN(`planned_start_date`) as start_date, MAX(`planned_finished_date`) as end_date');
        $this->db->where('development_id', $development_id);
        $this->db->group_by('stage_no');
        return $this->db->get($this->table_stage_phase);

    }

    public function get_all_phase_status($development_id, $stage_no)
    {
        $this->db->select('`stage_no`, MIN(`phase_status`) as aphase_status');
        $this->db->where('development_id', $development_id);
        $this->db->where('stage_no', $stage_no);
        $this->db->group_by('stage_no');
        return $this->db->get($this->table_stage_phase);

    }

    public function get_all_development_phase_status($development_id, $phase_id)
    {
        $this->db->select('`phase_id`, MIN(`development_task_status`) as all_task_status');
        $this->db->where('development_id', $development_id);
        $this->db->where('phase_id', $phase_id);
        $this->db->group_by('phase_id');
        return $this->db->get($this->table_development_task);

    }

    public function change_development_status($status, $development_city, $development_name)
    {

        if ($status != 2) {
            $this->db->select('`id`,`development_name`');
            if ($development_city != '0') {
                $this->db->where('development_city', $development_city);
            }
            if (!empty($development_name) && $development_name != 'ZiaurRahman123') {
                $this->db->like('development_name', $development_name);
            }
            $this->db->order_by('development_name', 'ASC');
            $devs = $this->db->get($this->table_development)->result();

            $i = 1;
            if (!empty($devs)) {
                foreach ($devs as $dev) {
                    $j = 1;
                    $development_id = $dev->id;
                    $development_name = $dev->development_name;
                    $this->db->select('MIN(`development_task_status`) as all_task_status');
                    $this->db->where('development_id', $development_id);
                    $development_task_status = $this->db->get($this->table_development_task)->result();

                    $this->db->select('MIN(`phase_status`) as aphase_status');
                    $this->db->where('development_id', $development_id);
                    $development_stage_phase_status = $this->db->get($this->table_stage_phase)->result();

                    $dev_info[$i][$j] = $development_id;
                    if ($development_task_status[0]->all_task_status == 0 or $development_task_status[0]->all_task_status == '' or $development_stage_phase_status[0]->aphase_status == 0 or $development_stage_phase_status[0]->aphase_status == '') {
                        $dev_info[$i][$j + 1] = 0;
                    } else {
                        $dev_info[$i][$j + 1] = 1;
                    }
                    $dev_info[$i][$j + 2] = $development_name;
                    //$dev_info[$i][$j+3] = $development_stage_phase_status[0]->aphase_status;
                    $i = $i + 1;
                }
                return $dev_info;
            }


        } elseif ($status == 2 && $development_city != '0') {
            $this->db->select('`id`,`development_name`');
            if ($development_city != '0') {
                $this->db->where('development_city', $development_city);
            }
            if (!empty($development_name) && $development_name != 'ZiaurRahman123') {
                $this->db->like('development_name', $development_name);
            }
            $this->db->order_by('development_name', 'ASC');
            $devs = $this->db->get($this->table_development)->result();

            $i = 1;
            if (!empty($devs)) {
                foreach ($devs as $dev) {
                    $j = 1;
                    $development_id = $dev->id;
                    $development_name = $dev->development_name;
                    $this->db->select('MIN(`development_task_status`) as all_task_status');
                    $this->db->where('development_id', $development_id);
                    $development_task_status = $this->db->get($this->table_development_task)->result();

                    $this->db->select('MIN(`phase_status`) as aphase_status');
                    $this->db->where('development_id', $development_id);
                    $development_stage_phase_status = $this->db->get($this->table_stage_phase)->result();

                    $dev_info[$i][$j] = $development_id;
                    if ($development_task_status[0]->all_task_status == 0 or $development_task_status[0]->all_task_status == '' or $development_stage_phase_status[0]->aphase_status == 0 or $development_stage_phase_status[0]->aphase_status == '') {
                        $dev_info[$i][$j + 1] = 0;
                    } else {
                        $dev_info[$i][$j + 1] = 1;
                    }
                    $dev_info[$i][$j + 2] = $development_name;
                    //$dev_info[$i][$j+3] = $development_stage_phase_status[0]->aphase_status;
                    $i = $i + 1;
                }
                //print_r($dev_info); exit;
                return $dev_info;
            }


        } elseif ($development_name == 'ZiaurRahman123') {
            $this->db->order_by('development_name', 'ASC');
            return $this->db->get($this->table_development)->result();
        } else {
            if (!empty($development_name)) {
                $this->db->like('development_name', $development_name);
            }
            $this->db->order_by('development_name', 'ASC');
            return $this->db->get($this->table_development)->result();
        }

    }

    public function change_development_city($development_city)
    {
        if ($development_city != '0') {
            $this->db->where('development_city', $development_city);
        }
        $this->db->order_by('id', 'DESC');
        return $this->db->get($this->table_development);
    }

    public function get_note_author($note_id)
    {
        $this->db->select('notes.notes_title, notes.notes_by, users.email as useremail');
        $this->db->from('notes');
        $this->db->where('nid', $note_id);
        $this->db->join('users', 'users.uid = notes.notes_by');
        $query = $this->db->get();
        return $query;
    }

    public function get_photo_author($photo_id)
    {
        $this->db->select('development_photos.photo_caption, development_photos.filename, users.email as useremail');
        $this->db->from('development_photos');
        $this->db->where('id', $photo_id);
        $this->db->join('users', 'users.uid = development_photos.uid');
        $query = $this->db->get();
        return $query;
    }

    public function get_development_phase_task_info($development_id, $phase_id)
    {

        $this->db->select('construction_development_task.id, task_company, task_category, contact_contact_list.id as cid, `task_person_responsible`,`task_name`,`task_length`,`day_required`,`task_start_date`,`actual_completion_date`,`development_task_status`, contact_contact_list.contact_first_name, contact_contact_list.contact_last_name, note, system_user_id');
        $this->db->join('contact_contact_list', ' contact_contact_list.id = construction_development_task.task_person_responsible', 'left');
        $this->db->where('development_id', $development_id);
        $this->db->where('phase_id', $phase_id);
        $this->db->order_by('ordering', 'ASC');
        return $this->db->get($this->table_development_task);

    }

    public function update_development_phase_task_status($task_id, $status, $phase_id)
    {
        if ($status == 1) {
            $now = date('Y-m-d');
        } else {
            $now = '0000-00-00';
        }
        $task_data = array('development_task_status' => $status);
        $this->db->where('id', $task_id);
        $this->db->update($this->table_development_task, $task_data);


        $this->db->select('`phase_id`, MIN(`development_task_status`) as all_task_status');
        //$this->db->where('development_id', $development_id);
        $this->db->where('phase_id', $phase_id);
        $this->db->group_by('phase_id');
        $res = $this->db->get($this->table_development_task)->result();

        if ($res[0]->all_task_status == 1) {
            $phase_data = array('phase_status' => 1);
            $this->db->where('id', $phase_id);
            $this->db->update($this->table_development_phase, $phase_data);
        } else {
            $phase_data = array('phase_status' => 0);
            $this->db->where('id', $phase_id);
            $this->db->update($this->table_development_phase, $phase_data);
        }


    }


    public function development_phase_task_start_date_update($task_id, $task_update)
    {
        $this->db->where($this->primary_key, $task_id);
        $this->db->update($this->table_development_task, $task_update);
    }

    public function development_stage_task_start_date_update($task_id, $task_update)
    {
        $this->db->where($this->primary_key, $task_id);
        $this->db->update($this->table_stage_phase, $task_update);
    }


    public function development_phase_task_actual_date_update($task_id, $task_update)
    {
        $this->db->where($this->primary_key, $task_id);
        $this->db->update($this->table_development_task, $task_update);
    }

    public function development_stage_task_actual_date_update($task_id, $task_update)
    {
        $this->db->where($this->primary_key, $task_id);
        $this->db->update($this->table_stage_phase, $task_update);
    }


    public function getDevelopmentDocuments($did, $target_page = 'documents', $construction_phase = 'construction')
    {
        $this->db->select('construction_development_documents.*, users.username as username');
        //$this->db->from('stage_documents');
        $this->db->where('development_id', $did);
        $this->db->where('target_page', $target_page);
        $this->db->where('construction_phase', $construction_phase);
        //$this->db->where_in('permitted_users',$uid);
        $this->db->join('users', 'users.uid = construction_development_documents.uid');

        /*task #4083*/
        if (in_array($_SERVER['SERVER_NAME'], array('property.wclp.co.nz', 'xprobuilders.wclp.co.nz'))) {
            $documents = array(
                "'Sale and Purchase Agreement'",
                "'Geotech'",
                "'Initial Plans for Tendering'",
                "'Submitted Council Plans'",
                "'Consented Council Plans'",
                "'Consented Council Supporting Docs'",
                "'Consented Council Specification'",
                "'Signed Landscaping Plan'",
                "'Signed Interior Colors'",
                "'Signed Exterior Colors'",
                "'Property Title'",
		"'Signed Investor Proposal'"
            );
            $this->db->order_by("FIELD(filename_custom," . implode(',', array_reverse($documents)) . ") DESC");
        }
        $this->db->order_by("id", "desc");
        return $this->db->get($this->table_development_documents);

        //echo $this->db->last_query();

    }

    public function getOthersDevelopmentDocuments($did, $target_page = 'documents', $construction_phase = 'construction')
    {
        $this->db->select('construction_development_documents.*, users.username as username');
        //$this->db->from('stage_documents');
        $this->db->where('development_id', $did);
        $this->db->where('construction_phase', $construction_phase);
        $this->db->where('target_page', $target_page);
        //$this->db->where('file_category','1');
        $this->db->join('users', 'users.uid = construction_development_documents.uid');
        $this->db->order_by("id", "desc");
        $this->db->limit(1);
        return $this->db->get($this->table_development_documents);

    }

    public function getOthersDevelopmentDocumentsBycategory($did, $cid)
    {
        $this->db->select('development_documents.*, users.name as username');
        //$this->db->from('stage_documents');
        $this->db->where('development_id', $did);
        $this->db->where('file_category', $cid);
        $this->db->join('users', 'users.uid = development_documents.uid');
        $this->db->order_by("id", "desc");
        $this->db->limit(1);
        return $this->db->get($this->table_development_documents);

    }

    public function get_development_documents_bycategory($did, $cid)
    {
        $this->db->select('development_documents.*, users.name as username');
        //$this->db->from('stage_documents');
        $this->db->where('development_id', $did);
        $this->db->where('file_category', $cid);
        $this->db->join('users', 'users.uid = development_documents.uid');
        $this->db->order_by("id", "desc");
        return $this->db->get($this->table_development_documents);

    }

    public function development_document_insert($file)
    {
        $this->db->insert($this->table_development_documents, $file);
    }

    public function development_document_update($file, $doc_id)
    {

        //print_r($file);  exit;

        $this->db->where('id', $doc_id);
        $this->db->update($this->table_development_documents, $file);
    }

    function get_document_detail($document_id)
    {
        $this->db->select('construction_development_documents.*, users.username as username');
        $this->db->from('construction_development_documents');
        $this->db->where('id', $document_id);
        $this->db->join('users', 'users.uid = construction_development_documents.uid');

        $query = $this->db->get();
        return $query;
    }

    public function update_all_phase_tasks($development_id, $phase_id, $status)
    {
        if ($status == 1) {
            $now = date('Y-m-d');
        } else {
            $now = '0000-00-00';
        }
        $task_data = array('phase_status' => $status);
        $this->db->where('id', $phase_id);
        $this->db->update($this->table_development_phase, $task_data);

    }

    public function update_all_satge_phase($development_id, $stage_no, $status)
    {
        if ($status == 1) {
            $now = date('Y-m-d');
        } else {
            $now = '0000-00-00';
        }
        $task_data = array('phase_status' => $status, 'actual_finished_date' => $now);
        $this->db->where('development_id', $development_id);
        $this->db->where('stage_no', $stage_no);
        $this->db->update($this->table_stage_phase, $task_data);

    }

    function email_outlook_development($photo_id)
    {
        $this->db->select('construction_development_photos.*, users.username as username');
        $this->db->from('construction_development_photos');
        $this->db->join('users', 'users.uid = construction_development_photos.uid');
        $this->db->where('id', $photo_id);

        $photo_result = $this->db->get()->row();

        $date = date("d-m-Y", $photo_result->created);
        $filename = $photo_result->filename;
        $username = $photo_result->username;
        $photo_caption = $photo_result->photo_caption;
        $base_url = base_url();

        $query = 'mailto:?subject=Constructions%20Photo&body=Photo Name: ' . $filename . '%0D%0A Uploaded By: ' . $username . '%0D%0A Uploaded Date: ' . $date . '%0D%0A Photo Caption: ' . $photo_caption . '%0D%0A Photo Link: ' . $base_url . 'uploads/development/' . $filename;
        echo $query;
        //return $query;
    }

    function getDevelopmentsInfo($devid)
    {
        $this->db->select('construction_development.id, construction_development.job_number, construction_development.development_name, c.username as created_by');
        $this->db->join('users c', 'construction_development.created_by = c.uid', 'left');
        $this->db->where('id', $devid);
        return $this->db->get($this->table_development)->row();


    }


    function getPriviousDevelopmentsNotes($devid, $construction_phase)
    {
        $this->db->select('construction_notes.*, users.username, construction_development.development_name');
        $this->db->join('construction_development','construction_development.id = construction_notes.project_id'); // task #4581
        $this->db->join('users', 'construction_notes.notes_by = users.uid', 'left');
        $this->db->where('construction_phase', $construction_phase);
        //$this->db->where('project_id', $devid);
        $this->db->where('project_id in ('.implode(',',$devid).')'); //task #4581
        //$this->db->order_by("created", "desc");
        return $this->db->get($this->table_notes)->result();


    }

    public function getNotifiedUserName($notify_user_id)
    {
        $notified_user = explode(",", $notify_user_id);

        /*$this->db->select('username');
        $this->db->where_in('uid', $notified_user);
        $notified_user_name = $this->db->get($this->table_users)->result();*/

        $this->db->select('contact_first_name', 'contact_last_name');
        $this->db->where_in('id', $notified_user);
        $notified_user_name = $this->db->get($this->table_contacts)->result();

        if (!empty($notified_user_name)) {
            $name = array();
            foreach ($notified_user_name as $user_name) {
                //$name[] = $user_name->username;
                $name[] = $user_name->contact_first_name . " " . $user_name->contact_last_name;
            }
            $user_name = implode(",", $name);
            return 'Notified : ' . $user_name;
        } else {
            return '';
        }

    }

    function get_user_list()
    {
        $user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;

        $this->db->where('users.company_id', $wp_company_id);
        $this->db->where('application_id', '5');
        $this->db->where('application_role_id', '2');
        $this->db->join('users', 'users.uid = users_application.user_id', 'left');
        $this->db->order_by('username', 'ASC');
        $results = $this->db->get('users_application')->result();

        $rows = array();
        foreach ($results as $row) {
            $rows[$row->uid] = $row->username;
        }
        return $rows;
    }

    public function get_user_info($notify_user_id)
    {
        $assign_manager = explode(",", $notify_user_id);

        $this->db->select('username, email');
        $this->db->where_in('uid', $assign_manager);
        $manager = $this->db->get($this->table_users)->result();
        return $manager;
    }

    function insertNote($rid, $note, $uid, $notify_user_id, $now, $construction_phase)
    {
        $note1 = str_replace("forward_slash", "/", $note);
        $note2 = str_replace("sign_of_hash", "#", $note1);
        $note3 = str_replace("sign_of_intertogation", "?", $note2);

        $insert_data = array(
            'project_id' => $rid,
            'notes_body' => $note3,
            'notes_image_id' => 0,
            'notes_by' => $uid,
            'notify_user_id' => $notify_user_id,
            'created' => $now,
            'construction_phase' => $construction_phase
        );
        $this->db->insert('construction_notes', $insert_data);
        return $this->db->insert_id();

    }

    function deleteDevelopmentsNotes($noteid)
    {

        $this->db->where('nid', $noteid);
        $this->db->delete($this->table_notes);


    }

    function job_phase_dependency_delete($phase_id)
    {
        $this->db->where('dependency', $phase_id);
        $this->db->delete('construction_job_phase_dependency');
    }

    function job_phase_dependency($stage_phase_dependency)
    {
        $this->db->insert('construction_job_phase_dependency', $stage_phase_dependency);
        return $this->db->insert_id();
    }

    function job_phase_dependency_name_load($id)
    {
        $this->db->where($this->primary_key, $id);
        $row = $this->db->get($this->table_development_phase)->row();
        $dt = '';
        if ($row->planned_finished_date != '0000-00-00') {
            $dt = date('d-m-Y', strtotime($row->planned_finished_date . ' +1 Weekday'));
        }
        echo json_encode(array('phase_name' => $row->phase_name, 'start_date' => $dt));
    }

    function getPriviousDevelopmentphotoNotes($photo_id)
    {
        $this->db->select('construction_development_photo_notes.*, users.username');
        $this->db->join('users', 'construction_development_photo_notes.notes_by = users.uid', 'left');
        $this->db->where('photo_id', $photo_id);
        return $this->db->get($this->table_development_photo_notes)->result();
    }

    function insertPhotoNote($fid, $note, $uid, $notify_user_id, $now)
    {

        $insert_data = array(
            'photo_id' => $fid,
            'notes_body' => $note,
            'notes_image_id' => 0,
            'notes_by' => $uid,
            'notify_user_id' => $notify_user_id,
            'created' => $now
        );
        $this->db->insert('construction_development_photo_notes', $insert_data);
    }

    function deleteDevelopmentphotoNotes($noteid)
    {
        $this->db->where('nid', $noteid);
        $this->db->delete($this->table_development_photo_notes);
    }

    function get_investor_data($job_id)
    {
        $this->db->where('job_id', $job_id);
        return $this->db->get('construction_investor_data');
    }

    function get_investor_draw_down($job_id)
    {
        $this->db->where('job_id', $job_id);
        return $this->db->get('construction_investor_draw_downs');
    }

}

?>