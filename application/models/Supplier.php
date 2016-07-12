<?

class Supplier extends OC_Model{

	function __construct(){
		parent::__construct();
		
		$this->table_name = "suppliers";
		$this->default_sort = "name";
	}
	
	


}



//end of file vendor.php