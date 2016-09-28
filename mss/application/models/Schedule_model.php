<?php 
class Schedule_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_schedule = 'schedule';
	private $table_schedule_product = 'schedule_product';
	private $table_product = 'product';
	private $table_template_product = 'template_product';
	private $table_schedule_template_product = 'schedule_template_product';
	private $table_product_maintenance_period = 'product_maintenance_period';
	private $table_file = 'file';
	
	function __construct() {
		parent::__construct();
	}

	public function load_product_id_by_template($tem_id){
		$this->db->where('template_id', $tem_id);
		return $this->db->get($this->table_template_product);
	}

	public function load_all_product($company_id){
		$this->db->select("product.*");
		$this->db->join('product_type', 'product_type.id = product.product_type_id', 'left');
		$this->db->where('wp_company_id', $company_id);
		return $this->db->get($this->table_product);
	}

	public function get_clients(){
		$user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;

		$this->db->where('wp_company_id', $wp_company_id);
		$this->db->order_by('id', 'ASC');
		return $this->db->get('clients');
	}

	public function get_templates(){
		$user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;

		$this->db->where('wp_company_id', $wp_company_id);
		$this->db->order_by('template_name', 'ASC');
		return $this->db->get('template');
	}

	public function get_product_type(){
		$user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;

		$this->db->where('wp_company_id', $wp_company_id);
		$this->db->order_by('id', 'ASC');
		return $this->db->get('product_type');
	}

	public function get_products(){
		$user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;
		//$tem_product_id = explode(',',$tem_product_id);

		$this->db->select("product.*");
		$this->db->join('product_type', 'product_type.id = product.product_type_id', 'left');
		$this->db->where('wp_company_id', $wp_company_id);
		//$this->db->where_not_in('product.id',$tem_product_id);
		$this->db->order_by('product.id', 'ASC');
		return $this->db->get('product');
	}

	public function load_product_template($tem_id){
		$this->db->select("product.*");
		$this->db->join('template_product', 'template_product.product_id = product.id', 'left');
		$this->db->where('template_product.template_id', $tem_id);
		$rows = $this->db->get($this->table_product)->result();

		$html = '<label for="note">Remove Product(s):</label>';		
		$html .= '<select name="tem_product_id[]" multiple="multiple" placeholder="--Select Product(s)--" class="form-control SlectBox">';
		foreach($rows as $row)
		{
			$html .= '<option value="'.$row->id.'">'.$row->product_name.'</option>';
		}
		$html .= '</select>';
	
		echo $html;

	}

	public function schedule_document_insert($document){
            $this->db->insert($this->table_file, $document);
            return $this->db->insert_id();            
    }

	public function product_report($tem_id,$remove_products){
		$this->db->select("product.*,product_type.product_type_name, a.filename as filename, b.filename as filename1, c.filename as paint");
		$this->db->join('product_type', 'product_type.id = product.product_type_id', 'left');
		$this->db->join('file a', 'a.id = product.product_document_id', 'left');
		$this->db->join('file b', 'b.id = product.product_document_id_1', 'left');
		$this->db->join('file c', 'c.id = product.product_document_paint', 'left');
		$this->db->join('template_product', 'template_product.product_id = product.id', 'left');
		$this->db->where_not_in('template_product.product_id', $remove_products);
		$this->db->where('template_product.template_id', $tem_id);
		return $this->db->get($this->table_product);
	}

	public function product_report_2($p_id){
		$this->db->select("file.filename");
		$this->db->join('file', 'file.id = product.product_document_id', 'left');
		$this->db->where('product.id', $p_id);
		return $this->db->get($this->table_product);
	}

	public function product_report_1($tem_id){
		$this->db->where('template_id', $tem_id);
		return $this->db->get($this->table_template_product);
	}

	public function schedule_product_report($id){
		$this->db->select("product.*,product_type.product_type_name, a.filename as filename, b.filename as filename1, c.filename as paint");
		$this->db->join('product_type', 'product_type.id = product.product_type_id', 'left');
		$this->db->join('file a', 'a.id = product.product_document_id', 'left');
		$this->db->join('file b', 'b.id = product.product_document_id_1', 'left');
		$this->db->join('file c', 'c.id = product.product_document_paint', 'left');
		$this->db->join('schedule_product', 'schedule_product.product_id = product.id', 'left');
		$this->db->where('schedule_product.schedule_id', $id);
		return $this->db->get($this->table_product);
	}

	public function product_report_maintenance($id){
		$this->db->where('product_id', $id);
		return $this->db->get($this->table_product_maintenance_period);
	}

	public function schedule_report($id){
		$this->db->select('schedule.*, clients.job_number,clients.address,d.filename as internal, e.filename as external, f.filename as plan, g.filename as kitchen, h.filename as factory, i.filename as job_specific, j.filename as code_compliance');
		$this->db->where('schedule.id', $id);
		$this->db->join('clients', 'clients.id=schedule.client_id', 'left');
		$this->db->join('file d', 'd.id = schedule.internal_colours', 'left');
		$this->db->join('file e', 'e.id = schedule.external_colours', 'left');
		$this->db->join('file f', 'f.id = schedule.plans', 'left');
		$this->db->join('file g', 'g.id = schedule.kitchen_plans', 'left');
		$this->db->join('file h', 'h.id = schedule.factory_order', 'left');
		$this->db->join('file i', 'i.id = schedule.job_specific_warranties', 'left');
		$this->db->join('file j', 'j.id = schedule.code_compliance_certificate_pdf', 'left');
		return $this->db->get($this->table_schedule)->row();
	}

	public function schedule_add($schedule_add){
		$this->db->insert($this->table_schedule,$schedule_add);
		return $this->db->insert_id();
	}

	public function schedule_product_add($add){
		$this->db->insert($this->table_schedule_product,$add);
		return $this->db->insert_id();
	}

	public function schedule_product_delete($id){
		$this->db->where('schedule_id', $id);
		$this->db->delete($this->table_schedule_product);
	}
	
	public function schedule_update($id,$schedule_update){
        $this->db->where($this->primary_key, $id);
		return $this->db->update($this->table_schedule,$schedule_update);
	}
	
	public function schedule_load($id){
		$this->db->where($this->primary_key, $id);
		return $this->db->get($this->table_schedule)->row();
	}

	public function client_load($client_id){

		$this->db->where('id', $client_id);
		return $this->db->get('clients')->row();
	}

	public function schedule_product_load($id){
		$this->db->where('schedule_id', $id);
		return $this->db->get($this->table_schedule_product);
	}
	
	public function schedule_delete($id){
        $this->db->where($this->primary_key, $id);
		$this->db->delete($this->table_schedule);
		
		$this->db->where('schedule_id', $id);
		$this->db->delete($this->table_schedule_product);
	}
	
	public function schedule_template_product_delete($id){
        $this->db->where('schedule_id', $id);
		return $this->db->delete($this->table_schedule_template_product);
	}
	
	public function product_load($template_id)
	{

	
		$pro_query = $this->db->query("select product.*, product_type.product_type_name from product inner join template_product on product.id = template_product.product_id INNER JOIN product_type ON product_type.id = product.product_type_id WHERE template_product.template_id = $template_id");
		$products = $pro_query->result();
		//$products = $this->db->get($this->table_product)->result();
		$ppp = '';		
		if(count($products)>0)
		{

			foreach($products as $product)
			{
					
				$ppp .= '<tr id="'.$product->id.'" class="'.$product->id.'">';
				$ppp .= '<td class="res-hidden"><img src="'.base_url().'images/drag_drop.png" /><input type="hidden" name="product_id[]" value="'.$product->id.'"></td>';
				$ppp .= '<td>'.$product->product_name.'</td>';
				$ppp .= '<td>'.$product->product_type_name.'</td>';
				$ppp .= '<td>'.$product->product_warranty_year.' Year<br>'.$product->product_warranty_month.' Month</td>';
				$ppp .= '<td class="res-hidden">'.$product->product_maintenance_year.' Year<br>'.$product->product_maintenance_month.' Month</td>';
				$ppp .= '<td class="res-hidden">'.$product->description_of_maintenance.'</td>';
				if($product->file_id != '0')
				{
					$ppp .= '<td class="res-hidden"><img src="'.base_url().'images/output_file.png" /></td>';
				}
				else
				{
					$ppp .= '<td class="res-hidden">No<br>Document</td>';
				}
				$ppp .= '</tr>';
			}
		
			echo $ppp;
		}
		else {
			echo "<tr><td colspan='7'>No Product Found </td></tr>";
		}
		
		
	}
	
	public function product_load_drag($product_id)
	{
			$pro_query = $this->db->query("SELECT * FROM product LEFT JOIN product_type ON product.product_type_id=product_type.id LEFT JOIN file ON product.file_id=file.id where product.id=$product_id");
			$product = $pro_query->row();
			$ppp = '';		
				$ppp .= '<tr id="'.$product_id.'" class="'.$product_id.'">';
				$ppp .= '<td class="res-hidden"><img src="'.base_url().'images/drag_drop.png" /><input type="hidden" name="product_id[]" value="'.$product_id.'"></td>';
				$ppp .= '<td>'.$product->product_name.'</td>';
				$ppp .= '<td>'.$product->product_type_name.'</td>';
				$ppp .= '<td>'.$product->product_warranty_year.' Year<br>'.$product->product_warranty_month.' Month</td>';
				$ppp .= '<td class="res-hidden">'.$product->product_maintenance_year.' Year<br>'.$product->product_maintenance_month.' Month</td>';
				$ppp .= '<td class="res-hidden">'.$product->description_of_maintenance.'</td>';
				if($product->file_id != '0')
				{
					$ppp .= '<td class="res-hidden"><img src="'.base_url().'images/output_file.png" /></td>';
				}
				else
				{
					$ppp .= '<td class="res-hidden">No<br>Document</td>';
				}
				$ppp .= '</tr>';
			print_r($ppp);
	}
	
	public function file_add($add){
		$this->db->insert($this->table_file,$add);
		return $this->db->insert_id();
	}
	
	public function product_add($add){
		$this->db->insert($this->table_product,$add);
		return $this->db->insert_id();
	}
	
	public function schedule_template_product_add($add){
		$this->db->insert($this->table_schedule_template_product,$add);
		return $this->db->insert_id();
	}

	public function single_product_load($id){
		$query = $this->db->query("SELECT * FROM product LEFT JOIN product_type ON product.product_type_id=product_type.id LEFT JOIN file ON product.file_id=file.id where product.id=$id");
		$row = $query->row();
		return $row;
	}
	
	public function insert_ajax_product_file($filename)
    {
        $data = array(
            'filename'      => $filename
        );
        $this->db->insert('file', $data);
        return $this->db->insert_id();
    }

	public function ajax_existing_product_load($get)
	{

		if(!empty($get['search_product'])){
			$search_product = $get['search_product'];
			$pro_query = $this->db->query("select product.* from product WHERE product_name like '%$search_product%'");
			$products = $pro_query->result();
			//$products = $this->db->get($this->table_product)->result();
			//$ppp = '';		
			if(count($products)>0)
			{
				$pro = "<ul>";
				$query_p_t = $this->db->query("SELECT * FROM product_type");
				$rows_p_t = $query_p_t->result();
				foreach($rows_p_t as $row_p_t)
				{
					$pro .= "<li>";
					$pro .= '<p>'.$row_p_t->product_type_name.'</p>';
					$pro .= '<ul id="draggable">';
					$query_p = $this->db->query("SELECT * FROM product where product_type_id=$row_p_t->id and product_name like '%$search_product%'");
					$rows_p = $query_p->result();
					foreach($rows_p as $row_p)
					{
						$pro .= '<li id="'.$row_p->id.'" class="product ui-draggable"><img src="'.base_url().'images/drag_drop.png" />'.$row_p->product_name.'</li>';
					}
					$pro .= "</ul>";
					$pro .= "</li>";
				}
				$pro .= "</ul>";
				echo $pro;
			}
			else {
				echo "<ul>No Product Found</ul>";
			}
		}else{
			$pro = "<ul>";
				$query_p_t = $this->db->query("SELECT * FROM product_type");
				$rows_p_t = $query_p_t->result();
				foreach($rows_p_t as $row_p_t)
				{
					$pro .= "<li>";
					$pro .= '<p>'.$row_p_t->product_type_name.'</p>';
					$pro .= '<ul id="draggable">';
					$query_p = $this->db->query("SELECT * FROM product where product_type_id=$row_p_t->id");
					$rows_p = $query_p->result();
					foreach($rows_p as $row_p)
					{
						$pro .= '<li id="'.$row_p->id.'" class="product"><img src="'.base_url().'images/drag_drop.png" />'.$row_p->product_name.'</li>';
					}
					$pro .= "</ul>";
					$pro .= "</li>";
				}
			$pro .= "</ul>";
			echo $pro;
		}

		$pro1 = "<script>";
		$pro1 .= "jQuery(document).ready(function() {";	
		$pro1 .= '$( ".product" ).draggable({';			
			$pro1 .= 'helper: "clone",';
			$pro1 .= 'revert: "invalid"';
		$pro1 .= "});";
		$pro1 .= "});";
		$pro1 .= "</script>";
		echo $pro1;
		
	}

	
		
}	