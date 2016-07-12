<?php
    
class Home extends OC_Controller {

    
   
        function __construct(){
		parent::__construct();
		
		$this->title = "Home";
		$this->main_nav = "home";
		$this->nav_active = "home";
		$this->path_prefix = 'Home/';
		
		$this->load->model(array('Call', 'Workorder', 'Reminder','User'));
        
		
}
    
	function index()
	{
		//var_dump($this->session->userdata('logged_in'));
		//echo "hello";
	  
	  $this->require_login();
		
	  $this->db->order_by("when DESC");
	  $this->db->limit(1);
	  $res = $this->db->get("pricing");
	  $data['pricing'] = $res->row();
	  
      
	  $data['reminders'] = $this->Reminder->get_for_user($this->session->userdata('user_id'));
	  
	  $data['calls'] = $this->Call->my_calls();
	  $data['work_items'] = $this->Workorder->mine();
    
		$data['total_customers'] = $this->Location->total_customers();
		$data['total_locations'] = $this->Location->total_locations();
	 	$data['all_known'] = $this->Location->total_locations(false);
		
		$data['title'] = "Welcome to DGF Control Center";
		
		$this->simple_page("home", $data);
	}
	
	function drug_date(){
		$data['title'] = "DGF Random Day Calculator";
		$this->simple_page("drug_date", $data);
	}


}

/* End of file Home.php */
/* Location: ./system/application/controllers/Home.php */
?>