<?

class Incoming extends OC_Model{

	var $errors;
	var $query;

	function __construct(){
		parent::__construct();
		$this->load->model("product");
		$this->load->library('upload');
		
		$this->table_name = "biodiesel_incoming";
		$this->default_sort = "when";
		
		$this->errors = "";
		$this->query = "SELECT 
				biodiesel_incoming.*, 
					products.name as product_name,
					suppliers.name as supplier_name,
					storage_containers.name as container_name
					FROM `biodiesel_incoming` 
					LEFT JOIN products ON biodiesel_incoming.product_id = products.id  
					LEFT JOIN suppliers ON biodiesel_incoming.supplier_id = suppliers.id 
					LEFT JOIN storage_containers ON biodiesel_incoming.container_id = storage_containers.id  ";
		
	}
	
	function get_log(){
		$query = $this->query;

		return $this->db->query($query);
	
	}
	
	function log_between($start_date, $end_date){
		$query = $this->query . "WHERE `biodiesel_incoming`.`when` >= '" . mdy_to_mysql($start_date) . "' ";
		$query .= "AND `biodiesel_incoming`.`when` <= '" . mdy_to_mysql($end_date) . "' ";
		
		return $this->db->query($query);
	
	}
	
	function get_one($id){
		$query = $this->query . " WHERE biodiesel_incoming.id = $id ";
		$rs = $this->db->query($query);
		return $rs->row();
	}
	
	
	function accept_incoming(){
	
		$this->errors = "";
		
		//get uploaded files
		$coa = $this->upload_file("coa");
		if(!$coa) $this->errors .= 'COA: <br/>' . $this->upload->display_errors();
	
		$bol = '';
		if($this->input->post("bol")) :
			$bol = $this->upload_file("bill_of_lading");
			if(!$bol) $this->errors .= 'Bill of Lading: <br/>' . $this->upload->display_errors();
		endif;
		
		$before = '';
		$after = '';
		
		
		if($this->input->post("weigh_sheets")) : 
			$before = $this->upload_file("weigh_before");
			if(!$before) $this->errors .= 'Weigh Sheet Before: <br/>' . $this->upload->display_errors();
	
			$after = $this->upload_file("weigh_after");
			if(!$after) $this->errors .= 'Weigh Sheet After: <br/>' . $this->upload->display_errors();
		endif;
		
		$quality = $this->upload_file("internal_quality_report");
		if(!$quality) $this->errors .= 'Internal Quality Report: <br/>' . $this->upload->display_errors();
		
		
		$c = $this->input->post("cert_for_bio");
		if(!empty($c)) :
			$cert = $this->upload_file("cert_for_bio");
			if(!$cert) $this->errors .= 'Certificate for Biodiesel: <br/>' . $this->upload->display_errors();
		endif;
	
	//	if(!empty($this->errors)) return false;
		
		$delivery = array(
			"supplier_id" => $this->supplierize($this->input->post("supplier_id")),
			"product_id" => $this->productize($this->input->post("product_id")),
			"container_id" => $this->input->post("container_id"),
			"amount" =>  $this->input->post("amount"),
			"price" =>  $this->input->post("price"),
			"rin"  =>  $this->input->post("rin"),
			"when" =>  mdy_to_mysql($this->input->post("when")),
			"excise_taxes_paid" =>  $this->input->post("excise_taxes_paid") ? 1 : 0, 
			"tax_credit_taken" =>  $this->input->post("tax_credit_taken") ? 1 : 0,
			"notes" => $this->input->post("notes"),
			"cert_for_bio" => $cert,
			"coa" => $coa,
			"bill_of_lading" => $bol,
			"weigh_before" => $before,
			"weigh_after" => $after,
			"internal_quality_report" => $quality
		);
		
			//make entry into transfers
		$this->db->query("INSERT INTO transfers (from_supplier_id, to_container_id, product_id, amount) 
								VALUES({$delivery['supplier_id']}, {$delivery['container_id']}, 
								{$delivery['product_id']}, {$delivery['amount']} )");
		
		$delivery['transfer_id'] = $this->db->insert_id();
		
		//enter and associate rin if given
		if(!empty($delivery['rin'])) :
			$this->load->model("rin");
			$rin_id = $this->rin->new_rin($delivery['rin']);
			$delivery['rin_id'] = $rin_id;
		endif;
		
		//insert delivery into database
		$this->db->insert("biodiesel_incoming", $delivery);
		
		//one day... $total_cost = 1.00 * $delivery["amount"] * $delivery["price"];
		
		//update product quantity
		$this->db->query("UPDATE products
							SET quantity = quantity + {$delivery['amount']} 
							WHERE id = {$delivery['product_id']}");
		
		//update tank capacity
		$this->db->query("UPDATE storage_containers 
							SET product_id = {$delivery['product_id']},
								current_level = current_level + {$delivery['amount']} 
							WHERE id = {$delivery['container_id']}");
		
		
		return true;
	
	}
	
	
	
	
	function productize($id){
		if(is_numeric($id) || preg_match("/\d+/",$id)) return $id;
		else{
			$product = array(
				'name' => $id,
				'price' => $this->input->post('price'),
				"excise_taxes_paid" =>  $this->input->post("excise_taxes_paid") ? 1 : 0, 
				"tax_credit_taken" =>  $this->input->post("tax_credit_taken") ? 1 : 0
			);
			$this->db->insert("products", $product);
			return $this->db->insert_id();
		}
	}
	
	function supplierize($id){
		if(preg_match('/\d+/',$id)) return $id;
		else{
			$s = array(
				'name' => $id
			);
			$this->db->insert("suppliers", $s);
			return $this->db->insert_id();
			
		}
	}
	
	function upload_file($name){
		
		$config['upload_path'] = './uploads/incoming_fuel/';
		$config['max_size']	= '2048';
		$config['allowed_types'] = 'txt|pdf|doc|rtf';
		$this->upload->initialize($config);
		
		if($this->upload->do_upload($name)) :
			$data = $this->upload->data();
			return $data['file_name'];
		else :
			return false;
		endif;
		
	}
	
	function download_link($file_name, $text){
		if(!file_exists($file_name)) : 
			return "no file";
		else :
			return anchor( "Util/download/uploads/incoming_fuel/" . basename($file_name), $text);
		endif;
	}
	
	function delete($id){
		$delivery = $this->get_one($id);
		

		//update product quantity
		$query = "UPDATE products 
							SET quantity = quantity - {$delivery->amount} 
							WHERE id = {$delivery->product_id}";
		$this->db->query($query);

		//update tank capacity
		$this->db->query("UPDATE storage_containers 
							SET product_id = {$delivery->product_id},
								current_level = current_level - {$delivery->amount} 
							WHERE id = {$delivery->container_id}");
		
		//make entry into transfers
		$this->db->where('id', $delivery->transfer_id);
		$this->db->delete('transfers');
		
		$this->db->where('id', $id);
		$this->db->delete('biodiesel_incoming');
		
	}

}



//end of file delivery.php