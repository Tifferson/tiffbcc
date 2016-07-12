<?

class RIN extends OC_Model{
	
	function __construct(){
		parent::__construct();
		
		$this->table_name = "rins";
	}
	
	function rin_to_data($string){
		$rin = array();
		$rin['attached'] = $string[0];
		$rin['year'] = substr($string, 1, 4);
		$rin['company_id'] = substr($string, 5, 4);
		$rin['facility_id'] = substr($string, 9, 5);
		$rin['batch_id'] = substr($string, 14, 5);
		$rin['equivalency'] = substr($string, 19, 2) * 0.1;
		$rin['cellulosic'] = $string[21];
		$rin['start_gallon'] = substr($string, 22, 8);
		$rin['end_gallon'] = substr($string, 30, 8);
		$rin['original_rin'] = $string;
		return $rin;
	}
	
	function new_rin($rin){
		$rin = rin_to_data($rin);
		$rin['original_rin'] = $rin;
		$rin['date_acquired'] = now_for_mysql();
		$this->db->insert($this->table_name, $rin);
		return $this->db->insert_id();
	}	
}