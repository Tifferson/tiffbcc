<? 

/*** Pickups model by Chris Continanza 
  A pickup represents one stop on a collection run.
There are two types of pickups: manual pickups, which have id of 0,
and pickups done as part of batch entry on the 'enter routes' page.

****/


class Pickup extends OC_Model{

	function __construct(){
		parent::__construct();

    //set OC_Model attributes
		$this->table_name = "pickups";
	}

	
	//return all pickups for a given location
	function by_location($loc_id){


    $this->db->select("pickups.*, routes.name as route_name, locations.notes as loc_notes");
    $this->db->from('pickups, locations');
    $this->db->where('pickups.location_id', $loc_id);
    $this->db->where('locations.id', $loc_id);
    $this->db->join("routes",  "routes.id = pickups.route_id", 'left');

  	
		return $this->db->get();
	
	}

  
  //return all pickups with location info for a given month
  //month and year come from a menu from values in the database
  function by_month($month, $year){
      $this->db->select("locations.*, locations.notes as loc_notes,
							pickups.*,
							locations.id AS location_id,
							routes.name as route_name");
      $this->db->from("pickups, locations");
      $this->db->where("pickups.location_id = locations.id");
      //$this->db->where("routes.id IN (SELECT route_id FROM stops WHERE location_id = locations.id)");
      $this->db->where('MONTH(pickups.date)', $month);
      $this->db->where('YEAR(pickups.date)', $year);
      $this->db->order_by('pickups.date');
      $this->db->order_by('routes.name');
	    $this->db->join("routes",  "routes.id = pickups.route_id", 'left');
   
      return $this->db->get();
  }


  //return the month / year info for pickups to create menu
  function months_with_data(){
		$query = "SELECT DISTINCT MONTH(`date`) as month, 
								MONTHNAME(`date`) as monthname, 
								YEAR(`date`) as year
								FROM pickups
								ORDER BY year DESC, month DESC";
		return $this->db->query($query);

  }

  
  //get all pickups with a route and date
  //date is in mysql-friendly format

  function route_on_date($id, $date){
    if($id == 0) :
      return $this->manual_pickups_on_date($date);
    else :
      return $this->_route_on_date($id, $date);
    endif;

  }

  function manual_pickups_on_date($date){
     $this->db->select("locations.*, locations.pickup_specifics as loc_notes,
						  pickups.*,
						  locations.id AS location_id");
     
    $this->db->where('pickups.route_id', 0);
    $this->db->where('pickups.date', $date);
    $this->db->join('locations', 'pickups.location_id = locations.id', 'left');
    
    return $this->db->get('pickups');   

  }

  function _route_on_date($id, $date){
          
    $this->db->select("locations.*, locations.pickup_specifics as loc_notes,
						  pickups.*,
						  locations.id AS location_id,
   						routes.name as route_name,
              stops.stop_number as stop_number");
    $this->db->from("pickups, locations, routes, stops");
    $this->db->where('pickups.route_id', $id);
//    $this->db->where('routes.id IN (SELECT route_id FROM stops WHERE location_id = locations.id)');
    $this->db->where('routes.id',$id);
    $this->db->where('pickups.location_id = locations.id');
    $this->db->where('pickups.date', $date);
    $this->db->where('stops.route_id', $id);
    $this->db->where('stops.location_id = pickups.location_id');
    $this->db->order_by('stops.stop_number');
    
    return $this->db->get();
  }

  //delete a pickup
  function delete($id){

     $this->db->where('id', $id);
    
     if(is_numeric($id) and $id >= 0) 
        $this->db->delete('pickups');
  }

  function delete_run($route_id, $date){
    $this->db->where('route_id', $route_id);
    $date = mysql_real_escape_string($date);
    $this->db->where(" DATE(`date`) = '$date' ",'', false);

    if($route_id >= 0 and ! empty($date) ) :
      $this->db->delete('pickups');
    endif;

  }

  //get dates a given route was run
  //because multiple pickups are entered for a route, use DISTINCT to make a menu
  function dates_for_route($id){
  
    $this->db->distinct();
    $this->db->select("date");
    $this->db->where('route_id', $id);
    $this->db->order_by("date", 'desc');
    return $this->db->get('pickups');

  }

  //Save the resolution info for a pickup
  function save_resolution($id, $extra){
	    $this->db->where('id', $id);
	    $this->db->set("no_pickup_ignore",1, FALSE);

	    if($extra)
	      $this->db->set("notes","concat(`notes`,' : $extra')", FALSE);
	    
	    $this->db->update('pickups');
  }


  //enter route
  function enter_route_from_post(){
	   $pickups = $_POST['pickups'];
	   $date = $this->input->post('date');
	   $route_id = $this->input->post("route_id");
	   $driver_id = $this->input->post("driver_id");
     $solids_and_water = $this->input->post("track_solids_and_water") == 1;

	   $total = 0;
	   
	   $date = mdy_to_mysql($date);
	   
	   foreach($pickups as $loc_id => $info){
	   	  //we wanted to reverse how the UI displays "picked_up" and call it problems, that's why this is backwards
	      $picked_up = isset($info['picked_up']) ? 0 : 1;
	      
	      $pickup = array(
	          'route_id' => $route_id,
	          'location_id' => $loc_id,
	          'picked_up' => $picked_up,
	          'gallons' => $info['gallons'],
	          'date' => $date,
	          'driver_id' => $driver_id,
	          'notes' => $info['comments']
	        );

      if($solids_and_water) : 
        $pickup['gallons_solids_taken'] = $info['gallons_solids_taken'];
        $pickup['gallons_solids_left'] = $info['gallons_solids_left'];
        $pickup['gallons_water_taken'] = $info['gallons_water_taken'];
        $pickup['gallons_water_left'] = $info['gallons_water_left'];
      endif;
      
        
	        
       $this->db->insert('pickups', $pickup); 
		   $total += $info['gallons'];
	   }

    //TODO: update containers with total

    $this->db->set("next_date","DATE_ADD(next_date, INTERVAL frequency WEEK)", FALSE);
    $this->db->where("id", $route_id);
    $this->db->update("routes");

    $this->update_scheduled_runs($route_id);
  
  }


  function update_scheduled_runs($route_id){

    $min_rs = $this->db->query("SELECT MIN(`date`) as min FROM scheduled_runs WHERE route_id=$route_id");
    $min = $min_rs->row();
    $min = $min->min;


    $max_rs = $this->db->query("SELECT MAX(`date`) as max FROM scheduled_runs WHERE route_id=$route_id");
    $max = $max_rs->row();
    $max = $max->max;

    $freq_rs = $this->db->query("SELECT frequency FROM routes WHERE id=$route_id");
    $freq = $freq_rs->row();
    $freq = $freq->frequency;

    if($max > 0 and $freq > 0) :
	//move em forward one
 	$this->db->set('`date`', "DATE_ADD('$max', INTERVAL $freq WEEK)", FALSE);
 	$this->db->set("route_id", $route_id);
  	$this->db->insert('scheduled_runs');
	$this->db->where("date", $min);
	$this->db->where('route_id', $route_id);
 	$this->db->delete('scheduled_runs');
    endif;
  }

  //save a manual pickup from POST
  function save_manual_pickup(){
    $pickup = array(
      'route_id' => 0, //manual pickup route id
			'date' => mdy_to_mysql($this->input->post('when')),
			'gallons' => $this->input->post("gallons", TRUE),
			'picked_up' => '1',
			'driver_id' => $this->input->post("driver_id", TRUE),
			'notes' => $this->input->post("notes", TRUE),
			'location_id' => $this->input->post("location_id", TRUE),
      'picked_up' => $this->input->post("problems") ? 0 : 1  //reversed-- see note above
		);

      if($this->input->post("track_solids_and_water") == 1) : 
        $pickup['gallons_solids_taken'] = $this->input->post('gallons_solids_taken');
        $pickup['gallons_solids_left'] = $this->input->post('gallons_solids_left');
        $pickup['gallons_water_taken'] = $this->input->post('gallons_water_taken');
        $pickup['gallons_water_left'] = $this->input->post('gallons_water_left');
      endif;
		
		$this->db->insert("pickups", $pickup);
	
   }
  
  function _producers(){
      $this->db->select("locations.dgf_name, locations.id,
							locations.city, locations.state, locations.zip,
							locations.notes, locations.dgf_address, locations.notes as loc_notes,
							pickups.*, locations.start_date, locations.date_added_as_customer as acq_date,
	      						min(pickups.date) as first_pickup,
              PERIOD_DIFF( DATE_FORMAT(NOW(), '%Y%m'), DATE_FORMAT(locations.start_date, '%Y%m')) as contract_months_active,
              PERIOD_DIFF( DATE_FORMAT(NOW(), '%Y%m'), DATE_FORMAT(locations.date_added_as_customer, '%Y%m')) as system_months_active,
              PERIOD_DIFF( DATE_FORMAT(NOW(), '%Y%m'), DATE_FORMAT(min(pickups.date), '%Y%m')) as pickup_months_active,
							locations.id AS location_id,
							routes.name as route_name, SUM( pickups.gallons ) AS total", false);
      $this->db->from("pickups, locations, routes");
      $this->db->where("routes.id IN (SELECT route_id FROM stops WHERE location_id = locations.id)", '', false);
      $this->db->where("pickups.location_id = locations.id");
		  $this->db->where("no_pickup_ignore", 0);
      $this->db->group_by("locations.id");

  }


  //locations with total gallons less than 10 for the past 90 days
  function no_producers(){

       $this->_producers();
       $this->db->where('(pickups.date > date_sub(now(), interval 90 day))','',false);
        //total gallons less than ten
       $this->db->having("total < 10",'',false);
       return $this->db->get();
    }

  // top $limit producers
  function top_producers($limit){

      if(!$limit || $limit < 0 || $limit > 100) 
          $limit = 25;

      $this->_producers();
      $this->db->order_by('total', 'desc');
      $this->db->limit($limit);

      return $this->db->get();
  }

    
    

    //get pickups that have had problems that need resolution
    function need_resolution(){
	    $query = "SELECT locations.dgf_name, locations.id,
							  locations.city, locations.state, locations.zip,
							  locations.notes, locations.dgf_address, locations.notes as loc_notes,
							  pickups.*,
							  locations.id AS location_id,
							  routes.name as route_name
		              FROM pickups, locations, routes
                  WHERE picked_up=0
					  AND routes.id IN (SELECT route_id FROM stops WHERE location_id = locations.id)
	                  AND pickups.location_id = locations.id
	                  AND no_pickup_ignore=0
					  ORDER BY pickups.date, route_name";
      return $this->db->query($query);

    }

 
}

//end pickups.php
