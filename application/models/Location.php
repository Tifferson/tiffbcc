<?
  class Location extends CI_Model{
	
  function __construct(){
      parent::__construct();      

      $this->table_name = 'locations';
  }
	
	/** Main search function **/
   function search($query, $limit){
     $query = mysql_real_escape_string($query);
     
     //include ignored
     if($this->input->post("ignored") == false) :
 			  $this->db->where("`ignore` != 1 AND (dgf_name LIKE '%$query%' OR dgf_address LIKE '$query%' OR zip='$query')");
 	   else :
 		    $this->db->where("dgf_name LIKE '%$query%' OR dgf_address LIKE '$query%' OR zip='$query'");
 	   endif;


    if($this->input->post('customer') == '1') :
      $this->db->where('is_customer', 1);
    endif;
  
 	   

 	   $this->db->order_by("NAME ASC");
     $this->db->limit($limit);
 	   $result = $this->db->get('locations');

     return $result;
  }

  /* new search function * /
  function search($query, $limit){
    
    $this->db->like('dgf_name', $query);
    $this->db->or_like('dgf_address', $query);
    $this->db->or_where('zip', $query);



 	  $this->db->order_by("NAME ASC");
    $this->db->limit($limit);

    return $this->db->get('locations');

  }
	
	/** search 'all' (more) fields than regular search for query key **/
	function search_all($query, $limit){
	  //key
	  $query = mysql_real_escape_string($query);
		
		$fields = array('dgf_name','dgf_address', 'city', 'region', 'zip',
		'contact_name', 'current_renderer', 'business_owner', 'dgf_rep', 
		'notes', 'pickup_specifics', 'last_edited_by' );
		
		//build where string for LIKE comparisons
		$where_string = '';
		$first = true;
		foreach($fields as $field) :
			if(!$first) $where_string .= "OR ";
			$where_string .= " $field LIKE '%$query%' ";
			$first = false;
		endforeach;
		
	
	  $events_select = "SELECT location_id FROM sales_events 
								WHERE description LIKE '%$query%'
								OR dgf_rep LIKE '%$query%'";
	   
	   $rs = $this->db->query($events_select);

		//included ignored
	  if($this->input->post("ignored") == false) :
		  $this->db->where("`ignore` <> 1 AND ($where_string)");
	  else : 
		  $this->db->where("($where_string)");
	  endif;


    $customers_only = $this->input->post('customer') == '1';

    if($customers_only) :
      $this->db->where('is_customer', 1);
	  elseif($rs->num_rows() > 0) :
			$string = '';
			foreach($rs->result() as $row) $string .= $row->location_id . ',';
			$string = trim($string, ',');
			if( $this->input->post("ignored") == false ) : 
				$this->db->or_where("`ignore` = 0 AND id IN ($string)");
			else :
				$this->db->or_where("id IN ($string)");
			endif;
		endif;


	  
	  $this->db->order_by("dgf_name");
	  $this->db->limit($limit);
	  $result = $this->db->get('locations');
    return $result;
	
	}
	
 
  /** Totals **/	
  function total_customers(){
    $query = "SELECT count(id) as total from locations where is_customer=1 and `ignore`=0 AND `canceled`=0";
    $rs = $this->db->query($query);
    $row = $rs->row();
    return $row->total;
  }
  
  function total_locations($include_ignored = true){
	if($include_ignored) : 
		$query = "SELECT count(id) as total from locations where `ignore`=0";
	else : 
		$query = "SELECT count(id) as total from locations";
	endif;
    $rs = $this->db->query($query);
    $row = $rs->row();
    return $row->total;
  }
	/** end totals **/
	
	//clean up the $_POST variable from edit_locations
	function process_post(){
				
		//convert checkboxes to boolean
		$_POST['is_lead'] = $this->input->post('is_lead') ? 1 : 0;
		$_POST['ignore'] = $this->input->post('ignore') ? 1 : 0;
		$_POST['is_customer'] = $this->input->post('is_customer') ? 1 : 0;
		$_POST['commission_paid'] = $this->input->post('commission_paid') ? 1 : 0;
		$_POST['pending_verification'] = $this->input->post('pending_verification') ? 1 : 0;
		$_POST['under_contract'] = $this->input->post('under_contract') ? 1 : 0;
		$_POST['canceled'] = $this->input->post('canceled') ? 1 : 0;
	
		//convert dates to mysql
		$_POST['current_contract_ends'] = mdy_to_mysql($this->input->post("current_contract_ends"));
		$_POST['start_date'] = mdy_to_mysql($this->input->post("start_date"));
		$_POST['end_date'] = mdy_to_mysql($this->input->post("end_date"));
		$_POST['date_commission_paid'] = mdy_to_mysql($this->input->post("date_commission_paid"));
		$_POST['follow_up_date'] = mdy_to_mysql($this->input->post("follow_up_date"));
		$_POST['ffa_test_date'] = mdy_to_mysql($this->input->post("ffa_test_date"));
		$_POST['date_added_as_customer'] = mdy_to_mysql($this->input->post("date_added_as_customer"));
    $_POST['canceled_date'] = mdy_to_mysql($this->input->post("canceled_date"));	
	
		//enter last_edited info into $_POST
		$_POST['last_edited'] = now_for_mysql();
		$_POST['last_edited_by'] = $this->session->userdata("user_name");
					
		//delete events form noise
		unset($_POST['description']);
		unset($_POST['e_notes']);
		unset($_POST['e_dgf_rep']);
		unset($_POST['when']);
		unset($_POST['location_id']);
    //delete route form noise
    unset($_POST['route_id']);
    //google maps form
    unset($_POST['search']);

   //follow up fields
    unset($_POST['fu_date']);
    unset($_POST['fu_user_id']);
		unset($_POST['fu_notes']);	
				
	}
	
	function recent_edits($limit){
	  $this->db->where("last_edited != '0000-00-00 00:00:00'");
	  $this->db->order_by("last_edited DESC");
	  $this->db->limit($limit);
	  return $this->db->get("locations");
	}
	
	function recent_sales($limit){
	  
	  $query = "SELECT * FROM sales_events 
	            LEFT JOIN locations ON sales_events.location_id = locations.id
	            ORDER BY `when` DESC";
	  if(is_numeric($limit)) $query .= " LIMIT $limit";
	 
	//  $this->db->where("id IN (SELECT location_id FROM sales_events ORDER BY `when` DESC)");
	//  return $this->db->get("locations");
	  return  $this->db->query($query);
	}
	
	function save_event(){
		 $_POST['when'] = mdy_to_mysql($_POST['when']);
		   /* $event = array(
		        'location_id' => $this->input->post('location_id')
		        'when' => 
		        'dgf_rep' =>
		        'description' =>
		        'notes' => 
		      )*/
			$this->db->where('id',  $this->input->post('location_id'));
			$this->db->set("last_date_contacted", $_POST['when']);
			$this->db->set("last_contacted_by", $this->session->userdata('user_name'));
			$this->db->update("locations");

		$this->db->insert('sales_events', $_POST);
		$id = $this->db->insert_id();
		$rs = $this->db->get_where('sales_events', array('id' => $id));
		return $rs->row();
	}
	
	//Create new location
	function create(){	
			
		$customer = $this->input->post("is_customer") ? true : false;
			
		$loc_info = array(
		  'name' => $this->input->post('dgf_name'),
		  'date_added' => now_for_mysql(),
		  'dgf_name' => $this->input->post('dgf_name'),
		  'dgf_rep' => $this->session->userdata("user_name"),
		  'dgf_address' => $this->input->post("dgf_address"),
		  'city' => $this->input->post("city"),
		  'state' => $this->input->post("state"),
		  'zip' => $this->input->post("zip"),
		  'container_type' => $this->input->post("container_type"),
		  'num_containers' => $this->input->post("num_containers")
		);
		
		$this->db->insert('locations', $loc_info);
		$id = $this->db->insert_id();

		//work_order for new containers
		if($this->input->post("is_customer")) :
			$this->new_customer($id);
			$this->new_customer_work_orders($loc_info['num_containers'], $loc_info['container_type'], $loc_info['name'], $id );
		endif;
		
		return $id;
		
	}
	
	
	
	function update(){
		
		$id = $this->input->post('id', true);
		unset($_POST['id']);
		
		$this->process_post();
		
		//customer status change means to make work orders
		$was_customer = consume($_POST, 'was_customer');
		
		if($this->input->post("is_customer") == '1' && $was_customer == '0') :
			$this->new_customer_work_orders($this->input->post('num_containers'), 
											$this->input->post('container_type'), 
											$this->input->post('dgf_name'), 
											$id );
			$this->new_customer($id);
    elseif($this->input->post("is_customer") == '0' && $was_customer == '1') : 
      $this->not_customer($id);
		endif;
		
		//extra POST processing
		
		unset($_POST["container_dropoff_date"]);
		unset($_POST["special_notes_for_dropoff"]);
		unset($_POST["special_notes_for_followup"]);

		$this->db->where('id',$id);
		$this->db->update('locations', $_POST);	
	}

  //TODO-- configurable defaults
	
	function new_customer($id){
		$location = array(
			'is_customer' => 1,
		  'pending_verification' => 1,
			'date_added_as_customer' => now_for_mysql()
		);
		
		$this->db->where('id', $id);
		$this->db->update('locations', $location);
	}
	
	function not_customer($id){
	  $this->db->where('location_id', $id);
    $this->db->delete("stops");
	}
	
	function remove_customer_work_order($name, $id){
		$one_week = 7*24*60*60;
		
		$work_order = array(
		  'task' => " Remove Containers for " . $name,
		  'location_id' => $id,
		  'comments' => "Auto-generated work order for dropped restaurant",
		  'due_date' => trim(preg_replace("/pm/i", '', unix_to_human(time() + 5*$one_week ))),
		  'created_by' => $this->session->userdata('user_id')
		);

		$this->db->insert('work_orders', $work_order);		
	}
	
	
	function new_customer_work_orders($num_containers, $con_type, $name, $id){
      
	  $one_day = 24*60*60;
	  $one_week = 7* $one_day; 

	  $due_date = mdy_to_mysql($this->input->post("container_dropoff_date"));
	  $dropoff_notes = $this->input->post('special_notes_for_dropoff');
	  $containers = $this->containers();
	
	  if($num_containers > 0 && !empty($due_date)) :
	  //drop off containers
	//	$plural = $num_containers > 1 ? 's' : '';
		  
		  $work_order = array(
		    'task' => "Drop off " . $num_containers . " " . $containers[$con_type] . " @ " . $name,
		    'location_id' => $id,
		    'comments' => "Auto-generated work order for new restaurant. " . $dropoff_notes,
		    'due_date' =>  $due_date, //trim(preg_replace("/pm/i", '', unix_to_human(time() + 4*$one_day ))),
		    'created_by' => $this->session->userdata('user_id'),
		    'priority' => 3
		  );
     
		  $this->db->insert('work_orders', $work_order);
	   endif;

	}


	function customers(){
    $this->db->where('canceled', '0');
		$this->db->where('is_customer', '1');
		$this->db->order_by("dgf_name ASC");
		return $this->db->get('locations');
	}
	
	
	function quality($sort = 'asc'){
	  $sort = strtolower($sort);
	  if($sort != 'asc' && $sort != 'desc') $sort = 'asc';
	  
	  $this->db->where('is_customer', '1');
	  $this->db->where("ffa_test_date !=  '0000-00-00' ");
	  $this->db->order_by("ffa_number $sort");
	  return $this->db->get('locations');
	  
	}

  function get_newest($limit = 100){
     if(!is_numeric($limit) || $limit > 500) $limit = 100;
     $this->db->order_by('date_added DESC');
     $this->db->limit($limit);
     return $this->db->get('locations');
  }

	
	function pending_verification(){
		$this->db->where("pending_verification", '1');
		return $this->db->get('locations');
	}
	
	function under_contract($status){
		$value = $status == 'not' ? 0 : 1;
		$this->db->where("under_contract", $value);
		$this->db->where("is_customer", 1);
		return $this->db->get('locations');
	}
	
	function human_name($location){
		return "$location->dgf_name @  $location->dgf_address, $location->city, $location->state";
	}
	
	function get_one($id){
		$this->db->where("id", $id);
		$loc_rs = $this->db->get("locations");
		return $loc_rs->row();
	}
	
	
	function containers(){
	  $rs = $this->db->get("container_types");
	  $containers = array();
	  foreach($rs->result() as $row){
	    $containers[$row->short_name] = $row->long_name;
	  }
	  
	  return $containers;
	  
	}

	function delete($id){
		$this->db->where('id', $id);
		$this->db->delete("locations");
		
	/*	$this->db->where('location_id', $id);
		$this->db->delete('work_orders');
    */	
  }
	
	/*LEAD stuff*/
	function make_my_leads($loc_ids){
		if(is_array($loc_ids)) :
			$return = array();
			foreach($loc_ids as $id) $return[] = $this->make_one_lead($id);
		else : //its a string
			$return = $this->make_one_lead($loc_ids);
		endif;
		
		return $return;
	}
	
	function make_one_lead($loc_id){
		$this->db->set("location_id", $loc_id);
		$this->db->set('user_id', $this->session->userdata('user_id'));
		$this->db->insert("users_leads");
		return $this->db->insert_id();
	}
	
	function remove_my_leads($loc_ids){
		if(is_array($loc_ids)) :		
			foreach($loc_ids as $id) $this->remove_one_lead($id);
		else : //its a string
			$this->remove_one_lead($loc_ids);
		endif;
	}
	
	function remove_one_lead($loc_id){
		$this->db->where("location_id", $loc_id);
		$this->db->where('user_id', $this->session->userdata('user_id'));
		return $this->db->delete('users_leads');
	}
	
  function get_expiring_contracts(){

    $this->db->where('under_contract', '1');
    $this->db->where('is_customer', '1');
    $this->db->where("`end_date` < NOW() + INTERVAL 2 MONTH");
    $this->db->where("end_date !=", empty_datetime());

    return $this->db->get('locations');

  }

  function is_my_lead($location_id){
    $this->db->where('location_id', $location_id);
    $this->db->where('user_id', $this->session->userdata('user_id'));
    $rs = $this->db->get('users_leads');
    return $rs->num_rows() > 0;

  }


  function get_expired_contracts(){  
    //2 months

    $this->db->where('under_contract', '1');
    $this->db->where('is_customer', '1');
    $this->db->where("`end_date` < NOW()");
    $this->db->where("end_date !=", empty_datetime());

    return $this->db->get('locations');

  }

  function update_on_route(){
    //UPDATE locations SET on_route=0 WHERE id NOT IN (SELECT distinct location_id FROM stops) AND is_customer=1
    $this->db->set('on_route', '0');
    $this->db->where_not_in('id', 'SELECT distinct location_id FROM stops', false);
    $this->db->where('is_customer', 1);
    $rs = $this->db->update("locations");
  }



} //class

