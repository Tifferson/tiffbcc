<?
  class Route extends CI_Model{
    
    function __construct(){
      parent::__construct();
    }
    
    function save_new(){
      $this->db->insert("routes", $this->post_to_route());
      
      $this->session->set_flashdata("message", "Saved " . $this->input->post('name'));
    }
    
    function update(){
      $this->db->where('id', $this->input->post('id'));
      $route = $this->post_to_route();
      $route['frequency'] = $this->input->post("frequency");
      $route['next_date'] = mdy_to_mysql($this->input->post('next_date'));
      $this->db->update('routes', $route);

      //save order of locations
      $this->save_order(true);
    

      //update route's future dates
      $this->make_schedule();
    }

    
    function make_schedule(){

      $id = $this->input->post('id');
      $seed = mdy_to_mysql($this->input->post('next_date'));
      $frequency = $this->input->post("frequency");
      
      $sql = 'INSERT INTO scheduled_runs (route_id, `date`) VALUES';

      if($frequency && $frequency > 0) :
        for($i = 1; $i <= 50; $i++) :
          $current = $frequency * $i;
          $sql .=  " ($id, DATE_ADD('$seed', INTERVAL $current WEEK)),  ";              
        endfor;
        $sql = trim($sql, ",  ");


        //out with the old
        $this->db->where('route_id', $id);
        $this->db->delete("scheduled_runs");
        //and in with the new
        $this->db->query($sql);
      endif;


    }    
 

    function save_order($hack_order = false){
      $id = $this->input->post('id');
  	  $order = $this->input->post("order");

      //hack to get info from url-encoded array (removing order[]= from string and breaking apart on &)
      if($hack_order) :     
       $order = preg_replace('/order\[\]\=/','',$order);
       $order = explode('&', $order);
      endif;

  	  $i = 1;
  	  foreach($order as $location){
        $this->db->where('route_id', $id);
  	    $this->db->where('location_id', $location);
  	    $this->db->set('stop_number', $i);
  	    //echo $i;
  	    $this->db->update('stops');
  	    $i++;
  	  }  
    }

    function save_display_order(){

  	  $order = $this->input->post("order");
 
  	  $i = 1;
  	  foreach($order as $id){
        $this->db->where('id', $id);
  	    $this->db->set('display_order', $i);
  	    $this->db->update('routes');
  	    $i++;
  	  }  

    }
   
    function get($id){
      $rs = $this->db->get_where('routes', array('id' => $id));
      return $rs->row();
    }


    //Problems---
    function by_location($loc_id){
      $route_ids = $this->route_ids_for_location($loc_id);
      if(!empty($route_ids)) : 
	        $this->db->where_in('id', $route_ids);
	        return $this->db->get("routes");
      else :
        return false;
      endif;
    }


    //use join table to get locations on this route
    function route_ids_for_location($loc_id){
	    error_log("here");$this->db->select('route_id');
	    $this->db->where('location_id', $loc_id);
	    $rs = $this->db->get('stops');
     	 $data = array();
	    foreach($rs->result() as $r) $data[] = $r->route_id;
	    return $data;
    }

    //use join table to get routes for this location
    function location_ids_for_route($route_id){
    	
	    $this->db->select('location_id');
	    $this->db->where('route_id', $route_id);
	    $rs = $this->db->get('stops');
      $data = array();
	    foreach($rs->result() as $r) $data[] = $r->location_id;
	    return $data;
    }
    
    function get_all(){
     // $this->db->order_by("week, cycle");
      $this->db->order_by("display_order");
      $this->db->where("active", 1);
      $this->db->where("id !=", 0);
      return $this->db->get("routes");
    }

    /****************** OLD FUNCTION - 6.23.2009
      function get_enterable(){
      $this->db->where('next_date >= DATE_SUB(NOW(), INTERVAL 1 WEEK)');
      return $this->get_all();
    }*/

    function get_enterable(){
      $this->db->where('next_date <>', empty_date());
      return $this->get_all();
    }

    //add location to route
    function add_location($location_id, $route_id){
      $current = $this->route_ids_for_location($location_id);

      if(!in_array($route_id, $current)) :
        //insert into join table
        $this->db->insert("stops", array('location_id' => $location_id, 'route_id' => $route_id));

        //update location on_route to true
        $this->db->where('id', $location_id);
        $this->db->update("locations", array('on_route' => '1'));
      endif;
    }
    
    //remove location from route-- check on_route value
    function remove_location($location_id, $route_id){
      $this->db->where(array('location_id' => $location_id, 'route_id' => $route_id));
      $this->db->delete("stops");

      //UPDATE on_route value
      $this->db->where('location_id', $location_id);
      $rs = $this->db->get('stops');
      if($rs->num_rows() == 0) :
          $this->db->where('id', $location_id);
          $this->db->update("locations", array('on_route' => '0'));
      endif;  

    }
    
    function get_stops($id){

      $ids  = $this->location_ids_for_route($id);
      if(!empty($ids)) :
        $this->db->select("locations.*, stops.stop_number as stop_number");
        $this->db->from('locations');
        $this->db->join('stops', "locations.id = stops.location_id AND stops.route_id = $id", 'left');
       	$this->db->where_in('locations.id', $ids);
        $this->db->order_by('stops.stop_number ASC');
    	  return $this->db->get();
      else :
        return false;
      endif; 
    }
    


    
    function suggest_from_zip($zip){
      //optimized to not use sub-selects
      $zip = mysql_real_escape_string($zip);

      $query1 =  " SELECT locations.id
                          FROM locations
                          WHERE locations.zip = '$zip' ";
      $rs = $this->db->query($query1);
      if($rs->num_rows() == 0) return false;

      $similar_zips = array();
      foreach($rs->result() as $loc) $similar_zips[] = $loc->id;
      $similar_zips = join(',', $similar_zips);      

      $query2 = " SELECT stops.route_id
                      FROM stops
                      WHERE stops.location_id IN ( $similar_zips )";

      $rs = $this->db->query($query2);
      if($rs->num_rows() == 0) return false;

      $similar_stops = array();
      foreach($rs->result() as $s) $similar_stops[] = $s->route_id;
      $similar_stops = join(',', $similar_stops);  
      
      $query = "SELECT routes.name, routes.id
                    FROM routes
                    WHERE routes.id
                    IN ($similar_stops)";
      $rs = $this->db->query($query);
      $suggestion = array();
      foreach( $rs->result() as $route) :
          $suggestion[] = trim($route->name);
      endforeach;
      if(empty($suggestion)) return false;
      else return join(', ', $suggestion);
    }
    
    function get_unassigned(){
      $this->db->where('id NOT IN (SELECT location_id FROM stops)');
  		$this->db->where('is_customer', '1');
  		return $this->db->get('locations');
    }
    
    function post_to_route(){

	    $name = $this->input->post("name");
 
      $route = array(
        'name' => $name,
        'description' => $this->input->post("description"),
        'active' => 1
      );
      
      return $route;
    }


	function get_month($month, $year){
		$this->db->where("MONTH(`next_date`) = $month");
		$this->db->where("YEAR(`next_date`) = $year");
		$this->db->select("DAYOFMONTH(`next_date`) as day");
		$this->db->select('routes.*');
		$result = $this->db->get("routes");	  
		
		return $result;

	}

  function get_runs_for_calendar($month, $year){
      $this->db->distinct();    
      $this->db->select('`date`, route_id, DAYOFMONTH(`date`) as day, routes.name as route_name');
	 		$this->db->where("MONTH(`date`) = $month");
		  $this->db->where("YEAR(`date`) = $year");
      $this->db->join("routes", "routes.id = pickups.route_id", 'left');
      return $this->db->get('pickups');
  }

   function get_pickups_for_route_day($route_id, $date){
      $this->db->select('`date`, locations.dgf_name as name');
	 		$this->db->where("date", $date);
      $this->db->where('route_id', $route_id);
      $this->db->join("locations", "locations.id = pickups.location_id", 'left');
      return $this->db->get('pickups');
  }

  function get_upcoming_runs($month, $year){

      $this->db->select('`date`, route_id, DAYOFMONTH(`date`) as day, routes.name as route_name');
	 		$this->db->where("MONTH(`date`) = $month");
		  $this->db->where("YEAR(`date`) = $year");
      $this->db->join("routes", "routes.id = scheduled_runs.route_id", 'left');
      return $this->db->get("scheduled_runs");
  }


	
	function get_calendar_month($month, $year){
	  
    //currently scheduled
    $rs = $this->get_month($month, $year);
	  $data = array();
	  foreach($rs->result() as $r) : 
      if(!isset($data[$r->day])) $data[$r->day] = '';
	    $data[$r->day] .= '- ' . anchor('pickups/show_dates/' . $r->id,  $r->name) . '<br/>';
	  endforeach;

    //past runs
	  $runs = $this->get_runs_for_calendar($month, $year);
	  foreach($runs->result() as $run) :
      if(!isset($data[$run->day])) $data[$run->day] = '';
      if($run->route_id == 0) $run->route_name = "Manual Pickups";
      
      $date = explode(' ', $run->date);    
	    $data[$run->day] .= '-' . anchor('pickups/show_route/' . $run->route_id . '/' . $date[0],  $run->route_name) . '<br/>';
    
      if($run->route_id == 0) :
        $pickups = $this->get_pickups_for_route_day($run->route_id, $run->date);
        foreach($pickups->result() as $pickup) :
            $data[$run->day] .= "&nbsp;&nbsp;&nbsp;-" . $pickup->name . '<br/>' ; 
        endforeach;
      endif;
	  endforeach;

    //future runs
    $future = $this->get_upcoming_runs($month, $year);
  	foreach($future->result() as $f) : 
      if(!isset($data[$f->day])) $data[$f->day] = '';
	    $data[$f->day] .= '- ' . $f->route_name . '<br/>';
	  endforeach;


	  return $data;
	}

  function deactivate($id){
    $this->db->where('id', $id);
    $this->db->set('active', 0);
    $this->db->update('routes');

  }

  function activate($id){
    $this->db->where('id', $id);
    $this->db->set('active', 1);
    $this->db->update('routes');

  }

  function get_deactivated(){
    $this->db->where('active', 0);
    return $this->db->get('routes');
  }
    
  function delete($id){
    $this->db->where('id', $id);
    $this->db->delete('routes');

    $this->db->where('route_id', $id);   
    $this->db->delete('stops');
    
   // $rs = $this->db->where_not_in('location_id', "SELECT id FROM locations WHERE is_customer=1");
   

   
  }
    
    
  }
?>
