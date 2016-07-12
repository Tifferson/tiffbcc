<?
class Container extends OC_Model{

	function __construct(){
		parent::__construct();
		
		$this->table_name = "storage_containers";
		$this->default_sort = "name";
	}
	
	/*function stringify($c){

		if($c->size > 0)
			return $c->name . " (" . $c->size . " gal) (" . ($c->size - $c->current_level) . " free)";
		else
			return $c->name;
	}*/
	
	function get_fuel(){
		$query = "SELECT storage_containers.*, products.name as product_name 
					FROM storage_containers 
					LEFT JOIN products 
					ON products.id = storage_containers.product_id
					WHERE holds_fuel = 1
					ORDER BY name";
		return $this->db->query($query);
		
	}

}