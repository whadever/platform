<?php
class welcome_model extends CI_Model {
	
	private $primary_key = 'eid';
	private $table_emp='employee_profile';
	private $table_comp='company_profile';                     
       
        function __construct() {
		parent::__construct();
	}
	
	public function visa_expire_count() {            
               $this->db->where('DATEDIFF(expiredate_of_visa, NOW())<=', VISA_EXPIRAE_ALERT);
               return $this->db->count_all_results($this->table_emp);
	}
	
	public function visa_expire_list($offset=0,$limit=10, $sort_by = 'empname', $order_by = 'ASC') {		
            $this->db->select('eid, empname, expiredate_of_visa, compname, DATEDIFF(expiredate_of_visa, NOW()) as df');       
            $this->db->join('company_profile', 'company_profile.cid = employee_profile.company_id', 'left outer');
            $this->db->order_by($sort_by, $order_by);
            $this->db->where('DATEDIFF(expiredate_of_visa, NOW())<=', VISA_EXPIRAE_ALERT);
            return $this->db->get($this->table_emp, $limit, $offset);		
	}
        public function passport_expire_count() {            
               $this->db->where('DATEDIFF(expiredate_of_passport, NOW())<=', PASSPORT_EXPIRE_ALERT);
               return $this->db->count_all_results($this->table_emp);
	}
	
	public function passport_expire_list($offset=0,$limit=10, $sort_by = 'empname', $order_by = 'ASC') {		
            $this->db->select('eid, empname, expiredate_of_passport, compname, DATEDIFF(expiredate_of_passport, NOW()) as df');       
            $this->db->join('company_profile', 'company_profile.cid = employee_profile.company_id', 'left outer');
            $this->db->order_by($sort_by, $order_by);
            $this->db->where('DATEDIFF(expiredate_of_passport, NOW())<=', PASSPORT_EXPIRE_ALERT);
            return $this->db->get($this->table_emp, $limit, $offset);		
	}     
}
?>