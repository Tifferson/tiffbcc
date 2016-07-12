<?

class Customer extends OC_Model{

	function __construct(){
		parent::__construct();
		
		$this->table_name = "customers";
		$this->default_sort = "name";
	}


}



//end of file customer.php