<?
ini_set('display_startup_errors','1');
class Locations extends OC_Controller{

  var $limit_search = 500;  

	function __construct()
	{	
		parent::__construct();
		
		$this->require_login();

		$this->load->helper('string');
		$this->load->library('user_agent');
		$this->load->model(array('Location', 'Route', 'Batchupload', 'Pickup'));
		
		$this->title = "Sources > Locations";
		$this->main_nav = "sources";
		$this->nav_active = "locations";
		$this->path_prefix = 'locations/';

    //$this->output->enable_profiler(TRUE);
	}
	

  function index()
	{
		//if(preg_match("/iphone/i", $this->agent->mobile())){
			//$this->load->view('locations/iphone_locations_view');
		//}else{
			$this->subnav_active = "index";
			$data['current_search'] = $this->session->userdata("locations_search_query");
			$this->simple_page('locations_view', $data);
		//}	
	}
	
	function save_query(){
		$search = array(
			"s" => $this->input->post("s"),
			"search_all" => $this->input->post("search_all") ? 1 : 0,
			"ignored" => $this->input->post("ignored") ? 1 : 0
		);
		$this->session->set_userdata("locations_search_query", $search );
	}
	
	function edit(){
    $this->load->model(array('Workorder', 'Reminder'));
  

		$id = $this->uri->segment(3);
		$this->db->where("id", $id);
		$result = $this->db->get('locations');
		
		$data['loc'] = $result->row();
		
		$data['reminders'] = $this->Reminder->get_for_location($id);
    $data['suggest_route'] = $this->Route->suggest_from_zip($data['loc']->zip);

		$data['current_routes'] = $this->Route->by_location($data['loc']->id);
		$data['pickups'] = $this->Pickup->by_location($id);
		
		$data['all_routes'] = $this->Route->get_all();

    $emp_rs = $this->User->employees(false);
    $employees = array();
    foreach($emp_rs->result() as $e) $employees[$e->name] = $e->name;
    if (!empty($data['loc']->dgf_rep) && !@$employees[$data['loc']->dgf_rep]) {
        $employees[$data['loc']->dgf_rep] = $data['loc']->dgf_rep;
    }
    
    $data['employees'] = $employees;

    //little bit of fudge for the work orders
    $this->db->limit(5);
    $this->db->order_by("due_date DESC");
    $data['work_orders'] = $this->Workorder->by_location($id);
		
		$this->db->where('location_id', $id);
		$data['events'] = $this->db->get('sales_events');
		
		$this->simple_page('edit', $data);

   
	}
	
  function newest(){
    $limit = $this->uri->segment(3);
    
    if(!$limit) $limit = 100;
		$data['locations'] = $this->Location->get_newest($limit);
    $data['limit'] = $data['locations']->num_rows();
    $data['total_locations'] = $this->Location->total_locations();

		$this->subnav_active = "newest";
		$this->simple_page('list_newest', $data);
  }
	
	function customers(){

		$data['locations'] = $this->Location->customers();
		
		$this->subnav_active = "customers";
		$this->simple_page('list_customers', $data);
	}

  function contract_ending(){
    $data['expired'] = $this->Location->get_expired_contracts();
    $data['expiring'] = $this->Location->get_expiring_contracts();
    
    
		$this->subnav_active = "reports";
		$this->simple_page('contract_ending_ended', $data);

  }
	
	function web_list(){

		$data['locations'] = $this->Location->customers();
		
		$this->subnav_active = "customers";
		$this->load->view('locations/website_list', $data);
	}
		
	function kml(){
		$this->db->limit(2);
		$data['locations'] = $this->Location->customers();
		
		header("Pragma: public"); // required
		header("Expires: 0");       
	//	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	//	header("Cache-Control: private",false);
	//	header("Content-Type: application/vnd.google-earth.kml+xml kml; charset=utf8");
		$this->load->view('locations/kml', $data);
	}
	

	function recents(){
	//	$how_many = $this->input->post
		
		$data['locations'] = $this->Location->recent_edits(20);
		$data['heading'] = "Last 20 edited locations";
		$data['which'] = 'edits';
		$this->subnav_active = "reports";
		$this->simple_page('list_recents', $data);	  
	}
	
	function recent_sales(){
		
		$limit = 200;
		$data['locations'] = $this->Location->recent_sales($limit);
		$data['heading'] = "Last $limit contacted locations";
		$data['which'] = 'sales';
		$this->subnav_active = "reports";
		$this->simple_page('list_recents', $data);	  
	}
	
	function pending(){
		$data['locations'] = $this->Location->pending_verification();
		$this->subnav_active = "reports";
		$this->simple_page('pending', $data);
		
	}
	
	function under_contract(){
		$status = $this->uri->segment(3); //not or nothing
		$data['status'] = $status;
		$data['locations'] = $this->Location->under_contract($status);
		$this->subnav_active = "reports";
		$this->simple_page('contract', $data);		
	}
	/*
	function recent_sales_all(){
		$data['locations'] = $this->location->recent_sales('all');
		
		$this->subnav_active = "recent_sales";
		$this->simple_page('list_recents', $data);	  
	}
	*/

  function save_location(){
	$action = $_POST['submit'];
	unset($_POST['submit']);

 //$this->output->enable_profiler(TRUE);
	  if(preg_match("/delete/i", $action)){
		$this->Location->delete($_POST['id']);
		$this->session->set_flashdata('message', "Deleted " . $_POST['dgf_name']);
		  redirect(back_or('Locations'));
	  }else{ 
		  $this->Location->update();
		  $name = $this->input->post("dgf_name", true);
		  if($name) $this->session->set_flashdata('message', "Updated $name");
		  redirect(back_or('Locations'));
	  }
  }

  function get_edit(){
    $id = $this->uri->segment(3);
    $this->db->where('id', $id);
    $result = $this->db->get('locations');
    $location = $result->row();
	
	  $this->db->where('location_id', $id);
  	$data['events'] = $this->db->get('sales_events');
		
    $data['loc'] = $location;
  	$data['routes'] = $this->Route->get_all();
  	
  	if(false && preg_match("/iphone/i", $this->agent->mobile())){
  		$this->load->view('locations/iphone_edit_location', $data);
  	}else{
  		$this->load->view('locations/edit_location', $data);
  	}
  }
  
  function create(){    
		$data['routes'] = $this->db->get("routes");
		$this->subnav_active = "new";
		$this->simple_page("create", $data);
  }
  
  function create_new(){
	  $id = $this->Location->create();
	  redirect('Locations/edit/' . $id);
  }
  
  function search(){
    $query = trim($this->input->get_post("s", true));
	
	if($this->input->post("search_all") == false) :
		$result = $this->Location->search($query, $this->limit_search);
	else : 
		$result = $this->Location->search_all($query, $this->limit_search);
	endif;
	
   
    $data['highlight_zip'] = preg_match("/^\d\d\d\d\d/", $query);
    $data['locations'] = $result;
    $data['q'] = $query;
    $data['star_ours'] = true;

	/*if(preg_match("/iphone/i", $this->agent->mobile())){
		$this->load->view('locations/iphone_search_results', $data);
	}else{*/
		$this->save_query();
		$this->load->view('locations/search_results', $data);
	//}
  }

  
	function upload(){
		$this->load->helper('form');
		
		$data['fields'] = $this->Batchupload->get_fields();
		$data['blocklist'] = $this->Batchupload->blocklist();
		
		$data['error'] = '';
		$this->subnav_active = "upload";
		$this->simple_page('upload', $data);
	}

	function do_upload(){
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'csv|text/csv|text';		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload())
		{
			$data['error'] =  $this->upload->display_errors();
			
			$this->subnav_active = "upload";
			$this->simple_page('upload', $data);
		}	
		else
		{
			$data = $this->upload->data();
			
			$this->Batchupload->upload_from_file($data['full_path']);
			
			$this->subnav_active = "upload";
			$this->session->set_flashdata('message', "Upload Success");
			redirect("Locations");
		}
	}
	
	
	function quality(){

	  $sort_order = $this->uri->segment(3);
	  if(!$sort_order || empty($sort_order)) $sort_order = 'asc';
	  $data['locations'] = $this->Location->quality($sort_order);
	  $data['sort'] = $sort_order;
	  
	  $this->subnav_active = "reports";	  
	
	  $this->simple_page('quality_report', $data);
	
	  
	}


  
  

}

