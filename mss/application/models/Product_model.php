<?php 
class Product_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_user = 'users';
	private $table_product = 'product';
	private $table_product_type = 'product_type';
	private $table_product_maintenance = 'product_maintenance_period';	
    private $table_document = 'file';
        
	
	
	function __construct() {
		parent::__construct();
	}
        
    public function get_product_list($get) { 
	    $user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;

		if(!empty($get)){
			$product_search = $get['product_search'];
			$product_type = $get['product_type'];
			$product_specifications = $get['product_specifications'];

			$sesData['product_search']=$product_search;	
			$sesData['product_type']=$product_type;
			$sesData['product_specifications']=$product_specifications;
	        $this->session->set_userdata($sesData);
		}
		$product_search = $this->session->userdata('product_search');
		$product_type = $this->session->userdata('product_type');
		$product_specifications = $this->session->userdata('product_specifications');

   		$this->db->select("product.*, product_type.product_type_name, a.filename as file1, b.filename as file2, c.filename as file3, paint.filename as filepaint");
        $this->db->join('product_type', 'product_type.id = product.product_type_id', 'left');
        $this->db->join('file a', 'a.id = product.product_document_id', 'left');
		$this->db->join('file b', 'b.id = product.product_document_id_1', 'left');
		$this->db->join('file c', 'c.id = product.product_document_id_2', 'left');
		$this->db->join('file paint', 'paint.id = product.product_document_paint', 'left');
		
		if(!empty($product_search)){ 
			$this->db->like('product_name', $product_search); 
		}

		if(!empty($product_specifications)){ 
			$this->db->like('product_specifications', $product_specifications); 
		}
		
		if(!empty($product_type)){ 
			$this->db->where('product_type_id', $product_type); 
		}

		$this->db->where('wp_company_id', $wp_company_id);
		$this->db->order_by('product.id', 'DESC');
        return $this->db->get($this->table_product);
	}
        
        function get_product_type(){
			$user = $this->session->userdata('user');
			$wp_company_id = $user->company_id;

            $query = $this->db->query("SELECT id, product_type_name FROM product_type WHERE wp_company_id='$wp_company_id' ORDER BY product_type_name");
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->id] = $row->product_type_name; 
            } 
            return $rows;
        }
        
         function get_product_category(){
            $query = $this->db->query("SELECT id, category_name FROM product_category ORDER BY category_name");
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->id] = $row->category_name; 
            } 
            return $rows;
        }
		
		public function get_product_maintenance_period($pid){
			$this->db->where('product_id', $pid);   
			$res= $this->db->get($this->table_product_maintenance);
			return $res;
			
		}
        
        public function product_document_insert($document){
            $this->db->insert($this->table_document, $document);
            return $this->db->insert_id();            
        }
        public function product_save($product_data){

			$this->db->insert($this->table_product, $product_data);
			return $this->db->insert_id();
		}
		public function product_maintenance_data($data){
			 			 
			 $this->db->insert_batch($this->table_product_maintenance, $data);
		}
		public function update_product_maintenance_data($data){
			 $this->db->delete($this->table_product_maintenance, array('product_id' => $data[0]['product_id'])); 			 
			 $this->db->insert_batch($this->table_product_maintenance, $data);
		}
		
        
        function get_product_detail($pid){
             //$this->db->select('request.*, project.project_name, company.company_name, a.name as manager_name, b.name as developer_name, c.name as created_by, f.filename as document, f.filepath as document_path, i.filename as image, i.filepath as image_path'); 
              //$this->db->select('product.*, project.project_name, company.company_name,  c.name as created_by, f.filename as document, f.filepath as document_path, i.filename as image, i.filepath as image_path');
             $this->db->select('product.*, file.id as document_id, file.filename');
             $this->db->join('file', 'file.id = product.product_document_id', 'left');
             
             //$this->db->join('file i', 'request.image_id = i.fid', 'left');
             //$this->db->join('users a', 'request.assign_manager_id = a.uid', 'left'); 
             //$this->db->join('users b', 'request.assign_developer_id = b.uid', 'left'); 
             //$this->db->join('users c', 'request.created_by = c.uid', 'left');
             $this->db->where('product.id', $pid);               
             return $this->db->get($this->table_product);
             //echo $this->db->last_query();
	}
    function product_update($id, $product_data){
		$this->db->where($this->primary_key, $id);
		$this->db->update($this->table_product, $product_data);
	}

    function product_delete($pid)
	{
		$this->db->where($this->primary_key,$pid);
		$this->db->delete($this->table_product);
		
		
		$this->db->where('product_id', $pid);
		$this->db->delete($this->table_product_maintenance);
	}

	function product_document_delete($id)
	{
		$this->db->where($this->primary_key,$id);
		$this->db->delete($this->table_document);
	}

	function product_type_list()
	{
		$user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;

		$this->db->select("product_type.*");
		$this->db->where('wp_company_id', $wp_company_id);
		$this->db->order_by('id', 'DESC');
        return $this->db->get($this->table_product_type);
	}
	
	public function produt_type_add($add)
	{
		$this->db->insert($this->table_product_type, $add);
		return $this->db->insert_id();
	}

	function produt_type_update($id,$update)
	{
		$this->db->where($this->primary_key, $id);
		$this->db->update($this->table_product_type, $update);
	}
	
	function product_type_delete($id)
	{
		$this->db->where($this->primary_key,$id);
		$this->db->delete($this->table_product_type);
	}

	public function product_report(){
		$this->db->select("product.*,product_type.product_type_name, file.filename");
		$this->db->join('product_type', 'product_type.id = product.product_type_id', 'left');
		$this->db->join('file', 'file.id = product.product_document_id', 'left');
		return $this->db->get($this->table_product);
	}

	public function document_load_report($id){
		$this->db->where($this->primary_key,$id);
		return $this->db->get($this->table_document);
	}

	public function size_pdf($size){
	    $result = array();
	    $tmp = exlode('x', $size);
	    $result['height'] = round(trim($tmp[0])/2.83);
	    $result['width'] = round(trim($tmp[1])/2.83);
	
	    return $result;
	}


}
	
	