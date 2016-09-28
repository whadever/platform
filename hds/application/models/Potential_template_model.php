<?php 
class Potential_template_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_template = 'potential_template';
	private $table_template_phase = 'potential_template_phase';
	private $table_template_task = 'potential_template_task';
	
		
	function __construct() {
		parent::__construct();
	}
 
	public function template_basic_info($template_basic_info){
		$this->db->insert($this->table_template, $template_basic_info);
		return $this->db->insert_id();
	} 
	
	public function save_phase_and_task($template_id,$no_phase,$no_task){
		$no_phases = $no_phase;
		$no_tasks = $no_task;
		
		for($i = 1; $i <= $no_phases; $i++)
		{
			$array_phase[] = array('template_id' => $template_id, 'phase_no' => $i, 'phase_name' => 'Phase Name');
		}
		//print_r($array_phase); exit();
		$this->db->insert_batch($this->table_template_phase, $array_phase);
		
		$this->db->select('id, phase_no');
		$this->db->where('template_id', $template_id);
		$template_phase_data = $this->db->get($this->table_template_phase)->result();

		foreach($template_phase_data as $phase_data)
		{
			
			for($j = 1; $j <= $no_tasks; $j++)
			{				
				$array_task[] = array('template_id' => $template_id, 'phase_id' => $phase_data->id, 'phase_no' => $phase_data->phase_no, 'task_name' => 'Task Name', 'task_no' => $j); 
				
			}
			
		}
		$this->db->insert_batch($this->table_template_task, $array_task);
		
	}  
	
	public function template_id($template_id){
		$this->db->where($this->primary_key, $template_id);
		return $this->db->get($this->table_template);
	}
	
	public function template_basic_info_update($template_id, $template_basic_info_update){
        $this->db->where($this->primary_key, $template_id);
		return $this->db->update($this->table_template,$template_basic_info_update);
	}
    
    public function template_list() {
		$user=  $this->session->userdata('user');          
		$wp_company_id =$user->company_id;

    	$query = $this->db->query("SELECT temp.*, users.username FROM potential_template temp LEFT JOIN users ON temp.created_by = users.uid WHERE wp_company_id='$wp_company_id' ORDER BY temp.`id` DESC");
        return $query;
	} 
	
	public function template_phase_add($template_phase_add){
		$this->db->insert($this->table_template_phase, $template_phase_add);
		return $this->db->insert_id();
	}
	
	function template_phase_update($phase_id,$template_phase_update){
		$this->db->where($this->primary_key,$phase_id);
		$this->db->update($this->table_template_phase,$template_phase_update);
	}

	function template_phase_delete($phase_id,$template_id){
		$this->db->where($this->primary_key,$phase_id);
		$this->db->delete($this->table_template_phase);	

		$this->db->where('phase_id',$phase_id);
		$this->db->where('template_id',$template_id);
		$this->db->delete($this->table_template_task);	
	}
	
	public function template_task_add($template_task_add){
		$this->db->insert($this->table_template_task, $template_task_add);
		return $this->db->insert_id();
	}
	
	function template_task_update($task_id,$template_task_update){
		$this->db->where($this->primary_key,$task_id);
		$this->db->update($this->table_template_task,$template_task_update);
	}
	
	function template_task_delete($task_id){
		$this->db->where($this->primary_key,$task_id);
		$this->db->delete($this->table_template_task);	
	}
	
	function template_delete($template_id){
		$this->db->where($this->primary_key,$template_id);
		$this->db->delete($this->table_template);	
		
		$this->db->where('template_id',$template_id);
		$this->db->delete($this->table_template_task);
		
		$this->db->where('template_id',$template_id);
		$this->db->delete($this->table_template_phase);
	}
	
	function template_phase_ordering(){
		//$list $_GET['listItem_'.$phase_id];
		foreach ($_POST['listItemPhase'] as $position => $item){
            $sql = "UPDATE potential_template_phase SET ordering=$position WHERE id=$item";
            $res = mysql_query($sql);
        }
        return $res;
	}
	
	function template_task_ordering(){
		//$list $_GET['listItem_'.$phase_id];
		foreach ($_POST['listItemTask'] as $position => $item){
            $sql = "UPDATE potential_template_task SET ordering=$position WHERE id=$item";
            $res = mysql_query($sql);
        }
        return $res;
	}
	     
}
?>