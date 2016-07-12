<?

class Product extends OC_Model{

	function __construct(){
		parent::__construct();
		
		$this->table_name = "products";
		$this->default_sort = "name";
	}


}



//end of file customer.php