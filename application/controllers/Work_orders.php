<?
 class Work_orders extends OC_Controller{
    
	var $date_string = "D, \<b>n/d/y</b>";
	var $statuses;

	function __construct(){
      parent::__construct();
	    
	    $this->require_login();
  
	    $this->load->model("Workorder");

	    $this->title = "DGF Work Items";
		$this->main_nav = "tasks";
	    $this->nav_active = "work_orders";
	    $this->path_prefix = 'work_orders/';
		
		//put status hashmap into controller logic so it can be shared -- could have also been done w. left joins
		$statii = $this->db->get("statuses");
		foreach($statii->result() as $status) :
			$this->statuses[$status->id] = array('human' => $status->status_human, 'abbrev' => $status->status_abbr);

		endforeach;
		
		  //user dropdown on roles
	  	$this->load->vars(array('user_filter' => array('is_employee' => 1), 'status_info' => $this->statuses));
    }
    
    function index(){

	  	$data['work_orders'] = $this->Workorder->get_open();
  		$data['date_string'] = $this->date_string;
		 $data['status_info'] = $this->statuses;
		 $data['priorities'] = $this->session->userdata("work_order_priority");
		
	    $this->subnav_active = "open";
	    $this->simple_page('list_open', $data);
    }
    
    function chron(){
  	    $this->Workorder->clear_empty();

  	  	$data['work_orders'] = $this->Workorder->get_open_chron();
    		$data['date_string'] = $this->date_string;
		$data['status_info'] = $this->statuses;
		$data['priorities'] = $this->session->userdata("work_order_priority");

		    $this->subnav_active = "open";
		    $this->simple_page('list_open', $data);
      }
    
	/*
    function order_created(){
	    $this->workorder->clear_empty();
	     
	  	$data['work_orders'] = $this->workorder->get_open();
  		$data['date_string'] = $this->date_string;
  		$data['which'] = "Open";
  		$data['title'] = $data['which'] . " Work Orders";
		    
		$this->subnav_active = "open";
		$this->simple_page('list', $data);

    }
 */
 
	 function mine(){

	   
	    $data['work_orders'] = $this->Workorder->mine();
    	$data['date_string'] = $this->date_string;
		$data['status_info'] = $this->statuses;
		$data['priorities'] = $this->session->userdata("work_order_priority");

		$this->subnav_active = "mine";
		$this->simple_page("list_open", $data);
	 
	 }
    
    
    function reopen(){
        $id = $this->uri->segment(3);
		    $this->Workorder->reopen($id);
        redirect('Work_orders'); 
      
    }
	
	function cache_priority(){
		$settings = array(
			0 => $this->input->post("priority_0") ? 1 : 0,
			1 => $this->input->post("priority_1") ? 1 : 0,
			2 => $this->input->post("priority_2") ? 1 : 0,
			3 => $this->input->post("priority_3") ? 1 : 0,
			4 => $this->input->post("priority_4") ? 1 : 0,
			'all' => $this->input->post("all") ? 1 : 0
		);
		
		$this->session->set_userdata("work_order_priority", $settings);
	}
    
  function create(){
  	$data['loc_query'] = $this->Location->customers();
	$data['date_string'] = $this->date_string;
	$data['status_info'] = $this->statuses;
	
	$this->subnav_active = 'new';
	$this->simple_page('new', $data);
  }
	
	function edit(){

	    $id = $this->uri->segment(3);
		
		$this->load->helper('form');
		$this->db->where('id', $id);
		$wo = $this->db->get('work_orders');

		$data['status_info'] = $this->statuses;
		$data['loc_query'] = $this->Location->customers();
		$data['work_order'] = $wo->row();
		$data['date_string'] = $this->date_string;

		$this->simple_page('edit', $data);
	}
	
	function delete(){
	  $id = $this->input->post('id');
	  $this->db->where("id", $id);
	  $this->db->delete("work_orders");
	  
	  $this->session->set_flashdata("message", "Deleted work order");
	  redirect('Work_orders');
	}
	
	function save_new(){
		$this->Workorder->new_from_post();
		$this->session->set_flashdata("message", "Created work order");
		redirect('Work_orders');
	}
	
	function save(){
		$this->Workorder->edit_from_post();
		$this->session->set_flashdata("message", "Saved work order");
		redirect('Work_orders');
	}
	
	function finish(){
	  $id = $this->uri->segment(3);
      
		$this->db->where('id', $id);
		$result = $this->db->get("work_orders");
		$work_order = $result->row();
    
		$data['work_order'] = $work_order;
		$data['status_info'] = $this->statuses;
	
		$this->title = $work_order->task;
		$this->simple_page('finish', $data);
	  
	}
	
	function cancel(){
		$id = $this->input->post('id', true);
		$this->db->where('id', $id);
		$this->db->delete('work_orders');
		redirect('Work_orders');
	}
	

  function printable(){
   
   	$id = $this->uri->segment(3);
	  
	  $this->Workorder->mark_printed($id);
      
    /*  $this->db->select("work_orders.*, locations.dgf_name as location_name, 
										locations.dgf_address as address, 
										locations.state as state, 
										locations.city as city, 
										locations.zip as zip,
										locations.pickup_specifics, locations.container_type");
	  $this->db->where('work_orders.id', $id);
	  $this->db->join('locations', 'locations.id = work_orders.location_id', 'left');
    $result = $this->db->get("work_orders"); */
    
    $work_order = $this->Workorder->get_printable($id);

	  	
    $data['date_string'] = $this->date_string;
	  $data['date_fmt'] = $this->date_string;
    $data['work_order'] = $work_order;

	  $data['title'] = $work_order->task;
	  $this->load->view('simple_header', $data);
    $this->load->view('work_orders/show', $data);
	  $this->load->view('simple_footer');
	
  }

	
	function calendar(){
    $prefs['template'] = $this->config->item('calendar_template');

    $global = $this->uri->segment(3);
    if($global == 'global') :
		  $this->subnav_active = "month";
    else :
      $this->subnav_active = 'my_month';
    endif;
    $url = 'Work_orders/calendar/' . $global;

	
	  $prefs['show_next_prev'] = TRUE;
    $prefs['next_prev_url'] = site_url($url);

    $this->load->library('calendar', $prefs);
		
		$month = $this->uri->segment(5);
		$year = $this->uri->segment(4);

		
		if(empty($month)) $month = date('n');
		if(empty($year)) $year = date('Y');
		
		$data['month'] = $month;
		$data['year'] = $year;
		$data['data'] = $this->Workorder->get_calendar_month($month, $year, $global == 'mine');
		

		$this->simple_page('calendar', $data);
	}


  //AJAX call
  function get_closed(){

    $start_date = $this->input->post("start_date");
    $end_date = $this->input->post("end_date");

		$this->db->where('`due_date` >=', mdy_to_mysql($start_date));
	  $this->db->where('`due_date` <=', mdy_to_mysql($end_date));
	  $this->db->where("is_open", 0);
	  $this->db->order_by("date_finished DESC");
    
	  $data['work_orders'] = $this->db->get("work_orders");
	  $data['date_string'] = $this->date_string; 
	  $data['status_info'] = $this->statuses;

    $this->load->view('work_orders/list_closed', $data);
  }

  //show page
	
	function closed(){

	
	  $this->subnav_active = "closed"; 
	   
	  $this->simple_page('closed');
	}
	
	function today(){
		$data['today_work_orders'] = $this->Workorder->for_today();
    $this->db->where("due_on", '0');
    $this->db->where("`due_date` <= DATE_ADD(NOW(), INTERVAL 1 MONTH)",'',false);
    $data['due_on_work_orders'] = $this->Workorder->mine();
		$data['title'] = "Work Items for " . mysql_to_mdy(now_for_mysql());
		
		$this->load->view('work_orders/list_day', $data);
	}

  function Location(){
    $id = $this->uri->segment(3);
    $data['work_orders'] = $this->Workorder->by_location($id);
    $data['location'] = $this->Location->get_one($id);

    $this->simple_page('by_location', $data);

  }
	
/*
	function show(){
		$which = $this->uri->segment(3);
		
		if($which == "all"){
		    $data['which'] = "All";
		    //	$this->db->order_by("due_date DESC");
		}
		else if($which == "open"){
		  	
	  	  $this->db->where("is_open", 1);
	      $data['which'] = "Open";
		  $this->subnav_active = "open";
		}
		else if($which == "closed"){
		  //	$this->db->order_by("date_finished DESC");
		  $this->db->where("is_open", 0);
		  $data['which'] = "Closed"; 
		  $this->subnav_active = "closed";	  
		}
		
		$this->db->order_by("due_date DESC");
		
		  $data['work_orders'] = $this->db->get("work_orders");
		  $data['date_string'] = $this->date_string;
	  
	    $this->title = $data['which'] . " Work Orders";

      $this->simple_page('list', $data);
	
	}
	
	*/
   
 }




?>
