<?
  class OC_Model extends CI_Model{
   
	var $table_name;
	var $default_sort;
    function __construct(){
        parent::__construct();
    }
	
   function get_all(){
		if(!empty($this->default_sort))
			$this->db->order_by($this->default_sort);
		return $this->db->get($this->table_name);
	}
		
	function get($id){
		$this->db->where("id", $id);
		$rs = $this->db->get($this->table_name);
		return $rs->row();
	}
	
	function select_array(){
		$rs = $this->get_all();
		$array = array();
		foreach($rs->result() as $row) 
			$array[$row->id] = $row->name;
		return $array;
	}
	
  }
?>