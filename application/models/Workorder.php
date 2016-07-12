<?
class Workorder extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	
	function clean_post(){
		$due = consume($_POST, 'due_date');
		$finished =  consume($_POST, 'date_finished');
		if(!empty($due)) $_POST['due_date'] = mdy_to_mysql($due);	
		if(!empty($finished)) $_POST['date_finished'] = mdy_to_mysql($finished); 
	}
	
	function new_from_post(){
		$this->clean_post();
		$this->db->insert('work_orders', $_POST);
	}
	
	function edit_from_post(){
		$this->clean_post();
		$id = consume($_POST, 'id');
		$this->db->where('id', $id);
		
		$this->db->update('work_orders', $_POST);	
	}
	
	function get_open(){
	   $this->db->order_by("due_on DESC, due_date ASC");
     $this->db->where("is_open", '1');
	   return $this->db->get("work_orders");
	}
	
	function mine(){
	   $this->db->where('assigned_to', $this->session->userdata('user_id'));
	   $this->db->where("is_open", '1');
	   $this->db->order_by("due_date");
	   return $this->db->get("work_orders");
	}
	
	function get_open_chron(){
	   $this->db->order_by("id DESC");
	   $this->db->where("is_open", '1');
	   return $this->db->get("work_orders");  
	}
		
	function reopen($id){
	    $this->db->where('id', $id);
        $this->db->set("is_open", '1');
        $this->db->update("work_orders");
	}
	
	function mark_printed($id){
	  $this->db->where('id', $id);
	  $this->db->set('has_been_printed', '1');
	  $this->db->update('work_orders');
	}
	
	function for_today(){
		$this->db->where("is_open", "1");
    $this->db->where("due_on", '0');
		$this->db->where("assigned_to", $this->session->userdata('user_id'));
	//	$this->db->where('MONTH(`due_date`) = MONTH(NOW())');
	//	$this->db->where('YEAR(`due_date`) = YEAR(NOW())');
		$this->db->where('DATE(`due_date`) = DATE(NOW())');
		$this->select_sql();
		return $this->db->get("work_orders");
	}
	
	function select_sql(){
	  $this->db->select("work_orders.*, locations.dgf_name as location_name, 
										locations.dgf_address as address, 
										locations.state as state, 
										locations.city as city, 
										locations.zip as zip,
										locations.pickup_specifics, locations.container_type");
    $this->db->join('locations', 'locations.id = work_orders.location_id', 'left');
	}
	
	
	function get_printable($id){
    $this->db->where('work_orders.id', $id);
    $this->select_sql();
    $result = $this->db->get("work_orders");
		return $result->row();

	}
	
	function get_month($month, $year, $mine = true){
		$this->db->where("is_open", "1");
    		#$this->db->where('due_on', '1');
    		$this->db->where("(status is null or status not like 'H')");
    		if($mine) $this->db->where("assigned_to", $this->session->userdata('user_id'));
		$this->db->where("MONTH(`due_date`) = $month");
		$this->db->where("YEAR(`due_date`) = $year");
		$this->db->select("*, DAYOFMONTH(`due_date`) as day");
		$this->select_sql();
		$result = $this->db->get("work_orders");	  
		return $result;
	}
	
	function get_calendar_month($month, $year, $mine = true){
	  $rs = $this->get_month($month, $year, $mine);
	  $data = array();
	  foreach($rs->result() as $wo){
		if(!isset($data[$wo->day])) $data[$wo->day] = '';
		$data[$wo->day] .= '- ' . anchor("Work_orders/edit/" . $wo->id, $wo->task) . '<br/>';
	  }
	  
	  return $data;
	}

  function by_location($loc_id){
    $this->db->where('location_id', $loc_id);
    $this->db->order_by("due_date ASC");

    return $this->db->get('work_orders');
  } 


}
