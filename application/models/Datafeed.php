<?

class Datafeed extends OC_Model{
	
	function __construct(){
		parent::__construct();
	}
	
	
	function total_gallons_picked_up(){
		$query = "SELECT SUM(gallons) as gallons FROM `pickups`";
		$total = 0;
		$results = $this->db->query($query)->result_array();
		foreach($results as $row) {
			$total += $row['gallons'];
		}
		return $total;
	}
}
