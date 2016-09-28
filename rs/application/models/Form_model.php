<?php
class form_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_form='rs_forms';
	
	private $user_id;
	private $wp_company_id;

	function __construct() {
		parent::__construct();
		
		$user=  $this->session->userdata('user');
		$this->user_id = $user->uid;
		$this->wp_company_id = $user->company_id;

		              
	}

	function create_form($data){

	}

	function get_form($id, $role = 'manager'){

		if($role == 'manager'){

			$this->db->where('id',$id);

			//$this->db->where('manager_id',$this->user_id);
		}else{

			$this->db->select('rs_forms.*, rs_form_users.frequency');

			$this->db->join('rs_form_users', 'rs_forms.id = rs_form_users.form_id');

			$this->db->where(array(
				'rs_forms.id' => $id, 'rs_form_users.user_id' => $this->user_id
			));

		}

		$this->db->where('wp_company_id',$this->wp_company_id);

		return $this->db->get($this->table_form)->row();
	}

	function get_form_fields($form_id, $role = 'staff'){

		$this->db->select("rs_forms.name, rs_form_fields.*");

		$this->db->from($this->table_form);

		$this->db->join('rs_form_fields', 'rs_forms.id = rs_form_fields.form_id');

		if($role == 'manager'){

			//$this->db->where('manager_id',$this->user_id);

		}else{

			$this->db->join('rs_form_users', 'rs_forms.id = rs_form_users.form_id');

			$this->db->where('user_id',$this->user_id);
		}

		$this->db->where('rs_forms.id',$form_id);

		$this->db->order_by("column", "asc");

		$this->db->order_by("order", "asc");

		return $this->db->get()->result();

	}

	function get_all_staffs(){

		$sql = "select users.*
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                     LEFT JOIN users ON ua.user_id = users.uid AND ua.company_id = users.company_id
                where a.id = 8 AND ar.application_role_id = 3 AND users.company_id = {$this->wp_company_id}";


		return $this->db->query($sql)->result();
	}

	function get_all_managers(){

		$sql = "select users.*
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                     LEFT JOIN users ON ua.user_id = users.uid AND ua.company_id = users.company_id
                where a.id = 8 AND ( ar.application_role_id = 1 OR ar.application_role_id = 2 ) AND users.company_id = {$this->wp_company_id}";


		return $this->db->query($sql)->result();
	}

	function get_manager_forms(){

		$this->db->where('manager_id',$this->user_id);

		$this->db->where('wp_company_id',$this->wp_company_id);

		return $this->db->get($this->table_form)->result();
	}

	function get_staff_forms(){

		$this->db->select("rs_forms.*");

		$this->db->from($this->table_form);

		$this->db->join('rs_form_users', 'rs_forms.id = rs_form_users.form_id');

		$this->db->where('user_id',$this->user_id);

		$this->db->where('wp_company_id',$this->wp_company_id);

		return $this->db->get()->result();

	}
	
}
?>