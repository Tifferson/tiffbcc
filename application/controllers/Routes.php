<?
class Routes extends OC_Controller {

	function __construct()
	{
		parent::__construct();
		$this->require_login();
		
		$this->load->model('Route');
		
		$this->title = "Collection Routes";
		$this->main_nav = "collection";
		$this->nav_active = "routes";
		$this->path_prefix = 'routes/';
		
		$this->load->vars(array('user_filter' => array('collection' => 1)));
	}
			
	function index(){
    	$data['not_on'] = $this->Route->get_unassigned();
		  $data['routes'] = $this->Route->get_all();
		
		$this->subnav_active = "list";
		$this->simple_page('list', $data);
	}
	
/*
	function show(){
		$id = $this->uri->segment(3);
		
		$data['route'] = $this->route->get($id);
    $data['stops'] = $this->route->get_stops($id);
		
		$this->simple_page('build', $data);
	}
*/
	
	function unassigned(){
    $data['not_on'] = $this->Route->get_unassigned();
    $routes = $this->Route->get_all();

    $data['suggest'] = array();
    foreach($data['not_on']->result() as $loc)  
      $data['suggest'][$loc->id] = $this->Route->suggest_from_zip($loc->zip);
  
    $data['routes'] = array();
    foreach($routes->result() as $r) 
      $data['routes'][$r->id] = $r->name;
    
		$this->simple_page('unassigned', $data);
	}


  function get_unassigned(){
    $data['locations'] = $this->Route->get_unassigned();
    $data['highlight_zip'] = false;
    $data['q'] = '';

    $this->load->view('locations/search_results', $data);
  }

  //ajax reciever
  function reorder_display(){
    $this->route->save_display_order();
  }
	
	
	function printable(){
		$id = $this->uri->segment(3);
		
		$data['route'] = $this->Route->get($id);
    $data['stops'] = $this->Route->get_stops($id);
		
		$this->load->view('simple_header', array('title' => $data['route']->name));
		$this->load->view('routes/printable', $data);
		$this->load->view('simple_footer');
	}
	
	function create(){
	  
	  $this->subnav_active = "new";
	  $this->simple_page('new');
	}
	
	function edit(){
	  $id = $this->uri->segment(3);
	  
	  $data['route'] = $this->Route->get($id);
    $data['stops'] = $this->Route->get_stops($id);
		
		$this->simple_page('edit', $data);
	}
	
	function save_new(){
	  $this->route->save_new();
	  redirect('Routes');
	}
	
	function update(){
	  $this->Route->update();

    $this->session->set_flashdata("message", "Updated " . $this->input->post('name'));

	  redirect('Routes');
	}


  function reorder_stops(){
    
    $this->route->save_order();
  }

  function deactivated(){
  
    $data['routes'] = $this->Route->get_deactivated();

    $this->subnav_active = "inactive";
    $this->simple_page('deactivated', $data);

  }
	
	function save_order(){
    	$this->Route->save_order();
	}
	
	function add_batch_to_route(){
		$ids = $this->input->post('ids');
    $route_id = $this->input->post('route_id');
		foreach($ids as $location_id){
			  $this->route->add_location($location_id, $route_id);
		}
    
    $this->db->where_in('id', $ids);
    $stops = $this->db->get('locations');
	  $this->load->view("routes/stops_li", array('stops' => $stops));
	}



  function add_location(){
    $location_id = $this->input->post('location_id');
    $route_id = $this->input->post('route_id');

    //do it    
    $this->Route->add_location($location_id, $route_id);
    
    //get route ifo for return display
    $r = $this->Route->get($route_id);

    echo "<li id=\"route_{$r->id}\">";
    echo anchor("Routes/show/" . $r->id, $r->name);
		echo " (<a href=\"#remove\" onclick=\"remove_from_route({$r->id})\">remove</a>)";
		echo "</li>";
   
  }

  function remove_location(){
    $location_id = $this->input->post('location_id');
    $route_id = $this->input->post('route_id');

    $this->Route->remove_location($location_id, $route_id);
  }


	function calendar(){
    $prefs['template'] = $this->config->item('calendar_template');
	  $prefs['show_next_prev'] = TRUE;
    $prefs['next_prev_url'] = site_url('Routes/calendar/');

		$year = $this->uri->segment(3);
    $month = $this->uri->segment(4);
		
		if(empty($month)) $month = date('n');
		if(empty($year)) $year = date('Y');
		
		$data['month'] = $month;
		$data['year'] = $year;
		$data['data'] = $this->Route->get_calendar_month($month, $year);

    $this->load->library('calendar', $prefs);
		
    $this->nav_active = "calendar";
		$this->simple_page('calendar', $data);
	}

  function deactivate(){

      $id = $this->input->post("id");
      $this->Route->deactivate($id);
    
   }

  function activate(){
      $id = $this->input->post("id");
      $this->Route->activate($id);

  }

  function delete(){
      $id = $this->input->post("id");
      $this->Route->delete($id);
   }



	/*

	
	function correct_address(){
		$id = $this->input->post('id');
		$corrected_address = $this->input->post('address');

		$this->db->where('id', $id);
		$this->db->update('locations', array('corrected_address' => $corrected_address));
	}
	
	function get_route(){
		$id = $this->input->post('id');
		$stops = $this->db->query("SELECT `order`, locations.id as id, name, address, day, corrected_address, city, zip FROM stops 
				LEFT JOIN locations 
				ON stops.location_id = locations.id
				WHERE week_id=?",array($id));
		
		$return = array();
		if($stops->num_rows > 0){
			foreach($stops->result() as $row){
				$address = trim(empty($row->corrected_address) ? $row->address : $row->corrected_address);
				$day = empty($row->day) ? 'stops' : $row->day;
				if(!isset($return[$day])) $return[$day] = array();
				$return[$day][] = array('name' => $row->name, 'address' => $address, 'loc_id' => $row->id, 'order' => $row->order);
			}
		}
		echo json_encode($return);
	}
	
	function save_week(){
		$fields = array('none', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
		$week = $this->input->get_post('week_id');
		$this->db->where('week_id', $week);
		$this->db->delete('stops');
	
		foreach($fields as $day){
			$i = 0;
			$stops = $this->input->get_post($day);
			var_dump($stops);
			if($stops){
				foreach($stops as $loc_id){
					$data = array('week_id' => $week, 'location_id' => $loc_id, 'day' => $day, 'order' => $i);
					var_dump($data);
					$this->db->insert('stops', $data);
				}
			}
		}
	}
	
	function get_address(){
		$id = $this->uri->segment(3);
		$this->db->where('id', $id);
		$query = $this->db->get('locations');
		
		$return = array();
		if($query->num_rows() > 0){
			$return = $query->row();
		}
		
		echo json_encode($return);
	}
	
	function get_directions()
	{
		/*
		$this->db->limit(5);
		$this->db->order_by("NAME ASC");
		$restaurants = $this->db->get('master_restaurant_list');
		
		$waypoints = "[";
		foreach($restaurants->result() as $row){
			$waypoints .= "\"{$row->address}, {$row->city}, TX, {$row->zip}\",";
		}
		$waypoints = trim($waypoints, ",") . "]";
		* /
		$addresses = $this->input->post('address');

		$waypoints = "[";
		foreach($addresses as $address){
			$waypoints .= "\"$address\",";
		}
		$waypoints = trim($waypoints, ",") . "]";
		$data['addresses'] = $addresses;
		$data['waypoints'] = $waypoints;
		$this->load->view('route_view', $data);
	}
	
	function view_route(){
		$week = $this->uri->segment(3);
		$route = $this->uri->segment(4);
		
		$where = array('route_number' => $route, 'week_number' => $week);
		$this->db->where($where);

		$result = $this->db->get('locations');
		foreach($result->result() as $row){
			$addresses[$row->id]= clean_address($row->dgf_address) . ", " . $row->city . ", TX " . $row->zip;
		}
		
		$this->db->where($where);
		
		$data['json_addresses'] = json_encode($addresses);
		$data['week'] = $week;
		$data['route'] = $route;
		$data['restaurants'] = $this->db->get('locations');
		$data['dgf_address'] = "5217 Cesar Chavez, Austin, TX 78702";
		$this->load->view('route_mapper/route_view3', $data);
		
	
	}
	*/
}

?>
