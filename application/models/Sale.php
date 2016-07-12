<?

class Sale extends OC_Model{

	function __construct(){
		parent::__construct();
		
		$this->table_name = "biodiesel_outgoing";
		$this->default_sort = "date";
	}
	
	function sales_between($start_date, $end_date){
	  $this->db->select("biodiesel_outgoing.price, biodiesel_outgoing.amount, biodiesel_outgoing.date, 
	                customers.name as customer_name,
	                products.name as product_name,
	                storage_containers.name as container_name");
		$this->db->where('`date` >=', mdy_to_mysql($start_date));
		$this->db->where('`date` <=', mdy_to_mysql($end_date));
		$this->db->join('customers', 'customers.id = biodiesel_outgoing.customer_id', 'left');
		$this->db->join('products', 'products.id = biodiesel_outgoing.product_id','left');
		$this->db->join('storage_containers', 'storage_containers.id = biodiesel_outgoing.container_id', 'left');
		$this->db->order_by("date");
		return $this->db->get($this->table_name);
	}
	
	function new_sale(){
	
		$customer_id = $this->input->post("customer_id");
		if(!is_numeric($customer_id)) :
			$this->db->insert("customers", array("name" => $customer_id));
			$customer_id = $this->db->insert_id();
		endif;
		
		$sale = array(
			'customer_id' => $customer_id,
			'product_id' => $this->input->post("product_id"),
			'container_id' => $this->input->post('container_id'),
			'price' => $this->input->post('price'),
			'amount' => $this->input->post('amount'),
			'date' => mdy_to_mysql($this->input->post('date'))
		);
		
		
		//make entry into transfers to get transfer_id
		$this->db->query("INSERT INTO transfers (from_container_id, to_customer_id, product_id, amount) 
								VALUES({$sale['container_id']}, {$sale['customer_id']}, {$sale['product_id']}, {$sale['amount']} )");
		
		$sale['transfer_id'] = $this->db->insert_id();
	
		//insert sale into database
		$this->db->insert($this->table_name, $sale);
		
		$this->update_inventory($sale);
	
	}
	
	function update_inventory($sale){
		//update product quantity
		$this->db->query("UPDATE products 
							SET quantity = quantity - {$sale['amount']} 
							WHERE id = {$sale['product_id']}");
		
		//update tank capacity
		$this->db->query("UPDATE storage_containers 
							SET product_id = {$sale['product_id']},
								current_level = current_level - {$sale['amount']} 
							WHERE id = {$sale['container_id']}");
	}

}



//end of file sale.php