<?php 
class AdminDevelopment_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_development = 'development';
	private $table_template = 'template';
	private $table_template_phase = 'template_phase';
	private $table_template_task = 'template_task';
	private $table_file = 'file';
	private $table_dev_phase = 'development_phase';
	private $table_dev_task = 'development_task';
	private $table_stage_phase = 'stage_phase';
	private $table_stage_task = 'stage_task';
		
	function __construct() {
		parent::__construct();
	}
 
	public function development_add($development_add){
		$this->db->insert($this->table_development, $development_add);
		return $this->db->insert_id();
	}
	
	public function development_update($development_id,$development_update){
		$this->db->where($this->primary_key,$development_id);
		$this->db->update($this->table_development,$development_update);
	}
	
	function development_tid_update($development_id,$development_tid_update){
		$this->db->where($this->primary_key,$development_id);
		$this->db->update($this->table_development,$development_tid_update);
	}
	
	function development_delete($development_id){
		$this->db->where($this->primary_key,$development_id);
		$this->db->delete($this->table_development);
		
		$this->db->where('development_id',$development_id);
		$this->db->delete($this->table_dev_phase);
		
		$this->db->where('development_id',$development_id);
		$this->db->delete($this->table_dev_task);
		
		$this->db->where('development_id',$development_id);
		$this->db->delete($this->table_stage_phase);
		
		$this->db->where('development_id',$development_id);
		$this->db->delete($this->table_stage_task);	
	}
	
	public function development_id($development_id){
		$this->db->where($this->primary_key,$development_id);
		return $this->db->get($this->table_development);
	}

    public function file_insert($file){
        $this->db->insert($this->table_file,$file);
        return $this->db->insert_id();            
    }  
    
    public function development_list() {
		$user=  $this->session->userdata('user');          
		$wp_company_id =$user->company_id;

    	$query = $this->db->query("SELECT dev.*, template.template_name FROM development dev LEFT JOIN template ON dev.tid = template.id WHERE dev.wp_company_id='$wp_company_id' ORDER BY dev.`id` DESC");
        return $query;
	} 
	
	public function development_phase_add($development_phase_add){
		$this->db->insert($this->table_dev_phase, $development_phase_add);
		return $this->db->insert_id();
	}
	
	function development_phase_update($phase_id,$development_phase_update){
		$this->db->where($this->primary_key,$phase_id);
		$this->db->update($this->table_dev_phase,$development_phase_update);
	}

	function development_phase_delete($phase_id,$template_id,$development_id){
		$this->db->where($this->primary_key,$phase_id);
		$this->db->delete($this->table_dev_phase);	

		$this->db->where('phase_id',$phase_id);
		$this->db->where('template_id',$template_id);
		$this->db->where('development_id',$development_id);
		$this->db->delete($this->table_dev_task);	
	}
	
	public function development_task_add($development_task_add){
		$this->db->insert($this->table_dev_task, $development_task_add);
		return $this->db->insert_id();
	}
	
	function development_task_update($task_id,$development_task_update){
		$this->db->where($this->primary_key,$task_id);
		$this->db->update($this->table_dev_task,$development_task_update);
	}
	
	function development_task_delete($task_id){
		$this->db->where($this->primary_key,$task_id);
		$this->db->delete($this->table_dev_task);	
	}
		
	public function development_feature_photo_insert($file){
        $this->db->insert('development_photos',$file);
        return $this->db->insert_id();            
    }

	public function development_photo_update($fid,$file){ 
		$this->db->where('id',$fid);
		$this->db->update('development_photos',$file);           
    }

	function development_feature_photo_delete($photo_id){
		$this->db->where('id',$photo_id);
		$this->db->delete('development_photos');	
	}
    
    public function development_feature_photo_id($fid){
		$this->db->where('id', $fid);
		return $this->db->get('development_photos');
	}

	function development_choice_template(){
		$user=  $this->session->userdata('user');          
		$wp_company_id =$user->company_id;

        $query = $this->db->query("SELECT tep.`template_name`, tep.`id` FROM template tep where template_type=1 and wp_company_id='$wp_company_id' ORDER BY tep.`id` DESC");
        $rows = array();
        foreach ($query->result() as $row){
            $rows[$row->id] = $row->template_name; 
        } 
        return $rows;
    }

	function stage_choice_template(){
		$user=  $this->session->userdata('user');          
		$wp_company_id =$user->company_id;

        $query = $this->db->query("SELECT tep.`template_name`, tep.`id` FROM template tep where template_type=2 and wp_company_id='$wp_company_id' ORDER BY tep.`id` DESC");
        $rows = array();
        foreach ($query->result() as $row){
            $rows[$row->id] = $row->template_name; 
        }
        return $rows;
    }

	function development_template_update($did,$tid){
		
		$this->db->where('development_id',$did);
		$this->db->delete($this->table_dev_phase);
		
		$this->db->select('template_id, phase_no, phase_name, phase_length, ordering' );
		$this->db->where('template_id', $tid);
		$template_phase_data = $this->db->get($this->table_template_phase)->result();

		foreach($template_phase_data as $tdata)
		{
			$array[] = array('development_id' => $did, 'template_id' => $tdata->template_id, 'phase_no' => $tdata->phase_no, 'phase_name' => $tdata->phase_name, 'phase_length' => $tdata->phase_length, 'ordering' => $tdata->ordering); 
			//$this->db->set($array);
			//$this->db->insert('mytable');

		}
		$this->db->insert_batch($this->table_dev_phase, $array);
		
		$this->db->select('id, phase_no');
		$this->db->where('template_id', $tid);
		$this->db->where('development_id', $did);
		$phase_ids = $this->db->get($this->table_dev_phase)->result();


		$this->db->where('development_id',$did);
		$this->db->delete($this->table_dev_task);

		foreach($phase_ids as $phase_id)
		{
			$phase_no = $phase_id->phase_no;	
			$this->db->select('template_id, task_name, task_length, phase_no, ordering' );
			$this->db->where('template_id', $tid);
			$this->db->where('phase_no', $phase_no);
			$template_task_data = $this->db->get($this->table_template_task)->result();
			foreach($template_task_data as $tdata)
			{
				
				$array_task[] = array('development_id' => $did, 'template_id' => $tdata->template_id, 'phase_id' => $phase_id->id, 'task_name' => $tdata->task_name, 'task_length' => $tdata->task_length, 'ordering' => $tdata->ordering); 
				//$this->db->set($array);
				//$this->db->insert('mytable');
				
			}

		}
		
		$this->db->insert_batch($this->table_dev_task, $array_task);
	}

    function stage_template_update($template_id, $stage_no, $development_id)
	{

		//$this->db->where('template_id',$template_id);
		$this->db->where('stage_no',$stage_no);
		$this->db->where('development_id',$development_id);
		$this->db->delete($this->table_stage_phase);
		
		$this->db->select('template_id, phase_no, phase_name, phase_length, ordering' );
		$this->db->where('template_id', $template_id);
		$template_stage_phase_data = $this->db->get($this->table_template_phase)->result();

		foreach($template_stage_phase_data as $tdata)
		{
			$array_stage_phase[] = array('development_id' => $development_id, 'template_id' => $tdata->template_id, 'phase_no' => $tdata->phase_no, 'phase_name' => $tdata->phase_name, 'phase_length' => $tdata->phase_length, 'stage_no' => $stage_no, 'ordering' => $tdata->ordering); 

		}
		//print_r($array_stage_phase); exit();
		$this->db->insert_batch($this->table_stage_phase, $array_stage_phase);	
		
		$this->db->select('id, stage_no, phase_no');
		$this->db->where('stage_no', $stage_no);
		$this->db->where('template_id', $template_id);
		$this->db->where('development_id', $development_id);
		$stage_phase_data = $this->db->get($this->table_stage_phase)->result();


		$this->db->where('development_id',$development_id);
		$this->db->where('stage_no',$stage_no);
		$this->db->delete($this->table_stage_task);

		foreach($stage_phase_data as $stage_phase)
		{
			$stage_no = $stage_phase->stage_no;	
			$phase_no = $stage_phase->phase_no;	
			$this->db->select('template_id, task_name, task_length, phase_no, ordering' );
			$this->db->where('template_id', $template_id);
			$this->db->where('phase_no', $phase_no);
			$template_task_data = $this->db->get($this->table_template_task)->result();
			foreach($template_task_data as $tdata)
			{
				
				$array_syage_task[] = array('development_id' => $development_id, 'template_id' => $template_id, 'phase_id' => $stage_phase->id, 'task_name' => $tdata->task_name, 'task_length' => $tdata->task_length, 'stage_no' => $stage_no, 'ordering' => $tdata->ordering); 
				//$this->db->set($array);
				//$this->db->insert('mytable');
				
			}

		}
		
		$this->db->insert_batch($this->table_stage_task, $array_syage_task);	

	}
	
	public function stage_phase_add($stage_phase_add){
		$this->db->insert($this->table_stage_phase, $stage_phase_add);
		return $this->db->insert_id();
	}
	
	function stage_phase_update($phase_id,$stage_phase_update){
		$this->db->where($this->primary_key,$phase_id);
		$this->db->update($this->table_stage_phase,$stage_phase_update);
	}
	
	function stage_phase_delete($phase_id){
		$this->db->where($this->primary_key,$phase_id);
		$this->db->delete($this->table_stage_phase);	
		
		$this->db->where('phase_id',$phase_id);
		$this->db->delete($this->table_stage_task);
	}
	
	public function stage_task_add($stage_task_add){
		$this->db->insert($this->table_stage_task, $stage_task_add);
		return $this->db->insert_id();
	}

	function stage_task_update($task_id,$stage_task_update){
		$this->db->where($this->primary_key,$task_id);
		$this->db->update($this->table_stage_task,$stage_task_update);
	}
	
	function stage_task_delete($task_id){
		$this->db->where($this->primary_key,$task_id);
		$this->db->delete($this->table_stage_task);
	}
	
	function development_phase_ordering(){
		//$list $_GET['listItem_'.$phase_id];
		foreach ($_POST['listItemPhase'] as $position => $item){
            $sql = "UPDATE development_phase SET ordering=$position WHERE id=$item";
            $res = mysql_query($sql);
        }
        return $res;
	}
	
	function development_task_ordering(){
		//$list $_GET['listItem_'.$phase_id];
		foreach ($_POST['listItemTask'] as $position => $item){
            $sql = "UPDATE development_task SET ordering=$position WHERE id=$item";
            $res = mysql_query($sql);
        }
        return $res;
	}
	
	function stage_phase_ordering(){
		//$list $_GET['listItem_'.$phase_id];
		foreach ($_POST['listItemPhase'] as $position => $item){
            $sql = "UPDATE stage_phase SET ordering=$position WHERE id=$item";
            $res = mysql_query($sql);
        }
        return $res;
	}
	
	function stage_task_ordering(){
		//$list $_GET['listItem_'.$phase_id];
		foreach ($_POST['listItemTask'] as $position => $item){
            $sql = "UPDATE stage_task SET ordering=$position WHERE id=$item";
            $res = mysql_query($sql);
        }
        return $res;
	}

	
	    
}