<?

class Activity extends OC_Model{

	function __construct(){
		parent::__construct();
		
		$this->table_name = "activity_log";
		$this->default_sort = "when";
	}


}



//end of file activity.php