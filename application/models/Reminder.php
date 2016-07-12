<?
class Reminder extends OC_Model{

  function __construct(){
		parent::__construct();
		
		$this->table_name = "follow_ups";
	//	$this->default_sort = "name";
	}

  function get_query(){
    //experimental, but decouples table names!!
    $this->load->model(array('User', 'Location'));
   
    $this->db->order_by('date', 'desc');
    $this->db->select("{$this->table_name}.*, {$this->Location->table_name}.dgf_name, {$this->User->table_name}.name as username");

    $this->db->join($this->User->table_name, "{$this->User->table_name}.id = {$this->table_name}.user_id", 'left');
    $this->db->join($this->Location->table_name, "{$this->Location->table_name}.id = {$this->table_name}.location_id", 'left');
  }

  function get_for_location($loc_id){
    $this->db->where('location_id', $loc_id);
    $this->get_query();
    return $this->db->get($this->table_name);
  }

  function get_for_user($user_id){
    $this->db->where('user_id', $user_id);
    $this->get_query();
    return $this->db->get($this->table_name);

  }

  function get($id){
    $this->get_query();
    $this->db->where($this->table_name . '.id', $id);

    return $this->db->get($this->table_name);

  }


  function get_others($id){
    $this->get_query();
    $this->db->where('user_id <>', $id);

    return $this->db->get($this->table_name);

  }


  function add_new(){
	
    $data = array(
     'date' => mdy_to_mysql($this->input->post("date")),
     'user_id' => $this->input->post("user_id"),
     'notes' => $this->input->post("notes"),
     'location_id' => $this->input->post("location_id") );
	
	error_log("add_new model: ".print_r($data,true));
	
   if( $this->db->insert($this->table_name, $data) ) :
      error_log("here");
      return $this->db->insert_id();

   else : 
      return FALSE;
   endif;

  }


  function delete($id){
    $this->db->where('id', $id);
    return $this->db->delete($this->table_name);
  }


  function mark_done($id){
    $this->db->set('done', 1);
    $this->db->where('id', $id);
    return $this->db->update($this->table_name);

  }

}



//end of file