<?

class Leads extends OC_Controller{

  var $upload_config;
	
	function __construct(){
		parent::__construct();
		$this->require_login();
		
		$this->load->model(array('Batchupload','Location'));
		
    //OC_Controller Variables
		$this->title = "BCC > Sources > Leads";
		$this->main_nav = "sources";
		$this->nav_active = "leads";
		$this->path_prefix = 'leads/';

    //this class' variables
    $this->upload_config = array();
		$this->upload_config['upload_path'] = './uploads/';
		$this->upload_config['allowed_types'] = 'csv|text/csv|text';		
	}

	function index(){
		
		$this->db->where('is_lead', '1');
		$this->db->where("ignore != '1'");

		$this->db->order_by("name asc");


		$data['locations'] = $this->db->get('locations');
	
		$this->subnav_active = "list";
		$this->simple_page('list_locations', $data);	

	}   
	################################################################
	
	### CGW 8/11/2009
	function follow_ups(){
		
		$this->db->where('is_lead', '1');
		$this->db->where("ignore != '1'");

		$this->db->order_by("name asc");


		$data['locations'] = $this->db->get('locations');
	
		$this->subnav_active = "list";
		$this->simple_page('list_locations', $data);	

	}  
	################################################################
	
	function mine(){
		$id = $this->session->userdata('user_id');
		$this->db->where("id IN (SELECT location_id FROM users_leads WHERE user_id=$id)");
		$this->db->order_by("dgf_name asc");

		$data['locations'] = $this->db->get('locations');
		$data['mine'] = true;
		
		$this->title = "DGF Contract Leads";
		$this->subnav_active = "mine";
		$this->simple_page('list_locations', $data);	
	}
	
	
	function make_active(){

			$loc_id = $this->uri->segment(3);
			$this->db->set("location_id", $loc_id);
			$this->db->set('user_id', $this->session->userdata('user_id'));
			$this->db->insert("users_leads");
      
      $this->session->set_flashdata("message", "Added lead to list");
			redirect('leads/mine');

	}

  function remove_lead(){
    $id = $this->uri->segment(3);

    $this->db->set('is_lead', '0');
    $this->db->where('id', $id);
    $this->db->update('locations');

    $this->session->set_flashdata("message", "Removed lead from leads list");
		redirect('Leads');

  }
	
	function make_inactive(){
		$id = $this->uri->segment(3);
		$this->db->set('active_rep', '');
		$this->db->where('id', $id);
		$this->db->update("locations");

    $this->session->set_flashdata("message", "Removed lead from your leads");
		redirect('Leads');
	}
	
	
	//AJAX methods
	function make_my_leads(){
		$ids = $this->input->post('ids');
		$this->location->make_my_leads($ids);
		$this->session->flashdata("message", "Added leads to your leads");
		echo "Success";
	}
	
	function remove_my_leads(){
		$ids = $this->input->post("ids");
		$this->location->remove_my_leads($ids);
		echo "Success";	
	}
	
	
	function sort_list(){
	  
	  $sort_by = $this->uri->segment(3);
	  $order = $this->uri->segment(4);
	  $is_mine = $this->uri->segment(5);
	

	  
	 	$this->db->where("ignore != '1'");
		$my_id = $this->session->userdata('user_id');

		if($is_mine == 'mine') :
			$this->subnav_active = "mine";
		 	$this->db->where("id IN (SELECT location_id FROM users_leads WHERE user_id=$my_id)");
		else : 
	     	$this->subnav_active = "list";
			$this->db->where('is_lead', '1');
		endif;
		
	 	$this->db->order_by("$sort_by $order");
	
		$data['locations'] = $this->db->get('locations');
		$data['so'] = $order;
		$data['mine'] = $is_mine == "mine";
				
		
		$this->simple_page('list_locations', $data);
	}
	
	function ignore(){
	  $id = $this->input->post('id');
	  $this->db->where('id', $id);
	  $this->db->set("ignore", "1");
	  $this->db->set("is_lead", "0");
	  $this->db->set("ignore_reason", $this->input->post("reason"));
	  
	  $this->db->update("locations");
	  //$this->index();
	}
	
	function events(){
	  if($this->input->post("save_event")){
	    unset($_POST['save_event']);
	  }
	  
	  $id = $this->uri->segment(3);
	  $this->db->where('id', $id);
	  $res = $this->db->get('locations');
	  $data['loc'] = $res->row();
	  
	  $this->db->where('location_id', $id);
	  $data['events'] = $this->db->get('sales_events');
	  
	  $this->title = "Sales Events";
	  
	  $this->simple_page('events', $data);
	  
	}
	
	function save_event(){
	    $i = consume($_POST, 'i');
	    $event = $this->location->save_event();
	  	$this->load->view('leads/event_li', array('event' => $event, 'i' => $i ) );
	}
	
	function delete_event(){
	    $this->db->where('id', $this->input->post('id'));
	    $this->db->delete('sales_events');
	}
	
	function upload_city(){
		$this->load->helper('form');
		
		$data['which_csv'] = 'city';
		$data['error'] = '';
		$this->subnav_active = "upload_city";
		$this->simple_page('upload', $data);
	}
	
	function upload_fs(){
		$this->load->helper('form');
		
		$data['which_csv'] = 'fs';
		$data['error'] = '';
		$this->subnav_active = "upload_fs";
		$this->simple_page('upload', $data);
	}



  function confirm_upload(){
    $this->load->library('upload', $this->upload_config);
    $this->load->helper('form');
		
		if ( ! $this->upload->do_upload())
		{
			$data['error'] =  $this->upload->display_errors();
			
			$this->subnav_active = "upload";
			$this->simple_page('upload', $data);
		}	
		else
		{
			$data = $this->upload->data();
			
		  $results = $this->batchupload->parse_fs_csv($data['full_path']);
	
			$this->subnav_active = "upload";

			$page_data['results'] = $results;
      $page_data['filename'] = $data['full_path'];
			$this->simple_page('confirm_upload', $page_data);
		}

  }

	function do_upload(){
      $file_name = $this->input->post('filename');

		  $results = $this->batchupload->parse_fs_csv($file_name, true);
	
			$this->subnav_active = "upload";

			$data['results'] = $results;
			$this->simple_page('upload_success', $data);
		
	}
	
/*
	function do_upload(){
		$this->load->library('upload', $this->upload_config);
		
		if ( ! $this->upload->do_upload())
		{
			$data['error'] =  $this->upload->display_errors();
			$data['which_csv'] = $this->input->post("which_csv");
			
			$this->subnav_active = "upload";
			$this->simple_page('upload', $data);
		}	
		else
		{
			$data = $this->upload->data();
			
			if($this->input->post("which_csv") == "city") :
				$results = $this->batchupload->parse_city_csv($data['full_path']);
			else :
				$results = $this->batchupload->parse_fs_csv($data['full_path']);
			endif;
			
			$this->subnav_active = "upload";
			//$this->session->set_flashdata('message', "Upload Success");
			//redirect("locations");
			$data['results'] = $results;
			$this->simple_page('upload_success', $data);
		}
	}
	*/
	
}

