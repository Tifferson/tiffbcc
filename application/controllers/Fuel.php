<?

class Fuel extends OC_Controller{
  
  function __construct(){
    parent::__construct();
	
		$this->load->model(array('Supplier', 'Product', 'Incoming', 'Container'));
		$this->load->helper('form');
  
		$this->load->library('table');
  
		$this->title = "DGF Fuel Log";
		$this->main_nav = "products";
		$this->nav_active = "fuel";
		$this->path_prefix = 'fuel/';
		
		$this->load->vars(array('user_filter' => array('production' => 1)));
  }
  
  function index(){
    redirect("Fuel/inventory");
    //$this->simple_page("status");
  }
  
  /** SUPPLIER FUNCTIONS **/
  //new supplier
  function create_supplier(){
  
	$this->simple_page("new_supplier");
  }
  
  //show all suppliers
  function suppliers(){
	$data['suppliers'] = $this->Supplier->get_all();
	
	$this->subnav_active = "list_suppliers";
	$this->simple_page("suppliers", $data);
  }
  
  //edit or delete a supplier
  function edit_supplier(){
  
  }
  
  function update_supplier(){
  
  }
  
  function delete_supplier(){
  
  }
  
  /** END SUPPLIER FUNCTIONS **/
  
  
  //** INCOMING FUEL STUFF **/
  
  //inventory 
  function inventory(){
	$data['products'] = $this->Product->get_all();
	$data['containers'] = $this->Container->get_fuel();
	
	$this->subnav_active = "reports";
	$this->simple_page("inventory", $data);
	
  }
    
  function incoming_fuel(){
	$this->incoming_data();
	$this->subnav_active = "incoming_fuel";
	$this->simple_page("incoming");
  }
  
  function incoming_data(){
	$data['suppliers'] = $this->Supplier->select_array();
	$data['products'] = $this->Product->select_array();
	$data['containers'] = $this->Container->select_array();
	
	$this->load->vars($data);
  }

  function upload_incoming(){
		
		if($this->incoming->accept_incoming()) :
			$this->session->set_flashdata("message", "Saved incoming");
			redirect("Fuel/index");
		else :
			$this->session->set_flashdata("display_errors", $this->incoming->errors);
			$this->session->set_flashdata("message", "Errors with file uploads");
			$this->session->set_flashdata("message_class", "error");
			redirect("Fuel/incoming_fuel");
		endif;
  }
  
  //display incoming fuel log
  function incoming_log(){
		//$data['deliveries'] = $this->incoming->get_log();
		
		$this->subnav_active = "reports";
		$this->simple_page("incoming_log");
  }

	function get_incoming(){
		$start = $this->input->post("start_date");
		$end = $this->input->post("end_date");
		
		$data['start_date'] = $start;
		$data['end_date'] = $end;
		$data['incoming'] = $this->incoming->log_between($start, $end);
		$this->load->view("fuel/list_incoming", $data);
	}
  

  //view one delivery and download attached reports
  function view(){
	$id = $this->uri->segment(3);
  
	$data['i'] = $this->incoming->get_one($id);
	
	$this->simple_page("view_incoming", $data);
  }
  
  //edit an incoming delivery
  function edit_incoming(){
		$id = $this->uri->segment(3);
		$this->incoming_data();
		$data['i'] = $this->incoming->get_one($id);
		
		$this->subnav_active = "reports";
		$this->simple_page("edit_incoming", $data);
  }
  
  //save it
  function update_incoming(){
		var_dump($this->input->post('incoming'));	
		//$this->db->update("biodiesel_incoming", );
		
  }
  
  //delete an incoming delivery and forward back to list
  function delete_incoming(){
	$this->incoming->delete($this->input->post("id"));
	$this->session->set_flashdata("message", "Deleted incoming fuel entry");
	redirect("Fuel/incoming_log");
  }
  
  /** END INCOMING FUEL STUFF **/
  
  //** FUEL ACTIONS **/
  
    //toll biodiesel screen
  function toll(){
	
	$this->subnav_active = "toll";
	$this->simple_page("toll");
  }

  //make biodiesel screen
  function make(){
	
	$this->subnav_active = "make";
	$this->simple_page("make");
  }
  
  //blend biodiesel screen
  function blend(){
	
	$this->subnav_active = "blend";
	$this->simple_page("blend");
  }
  
  
  /**END FUEL ACTIONS **/
  
  //ajax call
  function get_product(){
	$id = $this->input->post('id');
	echo json_encode($this->product->get($id));
  }
  


}

//eof