<?
/** Pickups Controller by Chris Continanza 

Pickups are tied to routes-- they represent when we
actually went out an collected oil, and now want to enter 
and track information about what happened.  If all goes well,
then we just enter the data.  On routes, however, not every location
gets picked up.  This is flagged on the route entry sheet and 
a mechanism is provided for 'resolving' the problems, which is 
really just adding more comments and turning a flag off.

This controller provides views for entering pickups from the
route sheet and manual pickups, as well as creating the reporting views.

**/

class Pickups extends OC_Controller{

	function __construct(){
		parent::__construct();
		$this->require_login();
		$this->load->model(array('Route','Pickup'));
		
		$this->title = "Collection Runs";
		$this->main_nav = "collection";
		$this->nav_active = "pickups";
		$this->path_prefix = 'pickups/';
		
		//restrict user_filter to users with collection enabled
    //track_solids_and_water is a global setting stored in the settings table
		$this->load->vars(array('user_filter' => array('collection' => 1),
                            'track_solids_and_water' => get_setting('track_solids_and_water')
                             ));
	}

	
	function index(){
		redirect("Pickups/enter_routes");
	}
	

  //list routes to choose from
	function by_route(){
	
	  $data['routes'] = $this->Route->get_all();
	  $data['link'] = 'show_dates';
	  	  
	  $this->subnav_active = "reports";
	  $this->simple_page('route_list', $data);
	}

  //list dates a route was run
	function show_dates(){
	  $id = $this->uri->segment(3);
 
		$data['route'] = $this->Route->get($id);
    $data['dates'] = $this->Pickup->dates_for_route($id);  
		
		$this->subnav_active = "reports";
	  $this->simple_page('route_dates', $data);		  
	}
	

  //list months to choose from
	function select_month(){

    $data['dates'] = $this->Pickup->months_with_data();
    		
		$this->subnav_active ="reports";
		$this->simple_page('month_history', $data);
	}


  //show pickups for month with URI parameters	
	function by_month(){
		$month = $this->uri->segment(3);
		$year = $this->uri->segment(4);
	
		$data['on_date'] = num_to_month($month) . " " . $year;
		$data['pickups'] = $this->pickup->by_month($month, $year);
		$data['show_date'] = true;
		
		$this->subnav_active = "reports";
		
		$this->simple_page("show_route", $data);	
	
	}
	
	//show route on a given date
	function show_route(){
		
	  $id = $this->uri->segment(3);
	  $date = $this->uri->segment(4);
	  
	  $data['on_date'] = date_to_mdy($date);
    $data['route'] = $this->route->get($id);
    $data['pickups'] = $this->pickup->route_on_date($id, $date);

    $this->subnav_active = "reports";
    $this->simple_page("show_route", $data);
	}
	
  //select location to choose from
	function location_history(){
		$this->subnav_active = 'loc_history';
		$data['locations'] = $this->Location->customers();
		$this->simple_page('loc_list', $data);
	}
	

  //show pickups that have a had problems and resolve them
	function resolve(){

	  $this->subnav_active = "reports";
	  $data['pickups'] = $this->Pickup->need_resolution();
	  $this->simple_page("resolve", $data);  
	}
	
  //show locations that haven't been providing oil (< 10 gal in 3 months)
	function no_producers(){
    
	  $this->subnav_active = "reports";

	  $data['pickups'] = $this->Pickup->no_producers();
	  $this->simple_page("no_producers", $data);  
	}

  //show locations that have produced the most oil
	function top_producers(){
    $limit = $this->uri->segment(3);    

    $data['show_gallons'] = true;
	  $data['pickups'] = $this->Pickup->top_producers($limit);
	  
    $this->subnav_active = "reports";
	  $this->simple_page("top_producers", $data);  
	}
	
	
  //AJAX call from resolving problems
	function save_resolution(){
	    $id = $this->input->post('id');
	    $extra = $this->input->post('extra');
	    
      $this->Pickup->save_resolution($id, $extra);
	    echo($id);
	}
	

  //display list of routes to enter collection run
	function enter_routes(){
		$data['open'] = $this->Route->get_enterable();
    
    //format database result into key-value pairs    
    $routes_rs = $this->Route->get_all();

    $routes_array = array();
    foreach($routes_rs->result() as $r) $routes_array[$r->id] = $r->name;
    if(!empty($routes_array)) :
       $data['all_routes'] = $routes_array;
    endif; 
		$this->subnav_active = 'enter_route';
		$this->simple_page('enter_route_list', $data);
	}
	
  //form to enter a collection run
	function enter_route(){
		$id = $this->uri->segment(3);

		$data['route'] = $this->Route->get($id);
		$data['stops'] = $this->Route->get_stops($id);
		
		$this->subnav_active = 'enter_route';
		$this->simple_page("enter_route", $data);
	}
	
  //form to enter a manual pickup
	function add(){
		$data['locations'] = $this->Location->customers();
		$this->subnav_active = 'manual';
		$this->simple_page('add', $data);
	}
	
	
  //save the data for a collection route
	function save_route(){
      $this->Pickup->enter_route_from_post();
	    $this->session->set_flashdata('message', "Entered Pickups");
	    redirect("Pickups");
	}
	
	//enter a manual pickup
	function create(){
		//save new, pass
		$add_more = isset($_POST['submit_and']);
		
    $this->Pickup->save_manual_pickup();
    $this->session->set_flashdata("message", "Pickup Saved");    

		if($add_more){
			redirect("Pickups/add");
		}
		else redirect("Pickups");

	}
	
  //show all pickups for a location
	function show_history(){
		$loc_id = $this->uri->segment(3);
		
		$data['location'] = $this->Location->get_one($loc_id);
		$data['pickups'] =  $this->Pickup->by_location($loc_id);
		
		$this->subnav_active = 'reports';
		$this->simple_page("loc_history", $data);
	}
	
  //delete a pickup (AJAX)
	function delete(){
		$id = $this->input->post('id');
		$this->Pickup->delete($id);
	}


  //delete a collection run (AJAX)
  function delete_run(){
		$r_id = $this->input->post('route_id');
    $date = $this->input->post('date');
		$this->Pickup->delete_run($r_id, $date);

  }
	
}

//eof Pickups
