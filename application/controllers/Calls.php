<?
	class Calls extends OC_Controller{
	
		function __construct(){
			parent::__construct();
			
			$this->load->model('Call');
			
			//login protected
			$this->require_login();
			
			//layout variables
			$this->title = "Call Log";
			$this->main_nav = 'tasks';
			$this->nav_active = "calls";
			$this->path_prefix = 'calls/';
			
			$this->load->vars(array('user_filter' => array('is_employee' => 1)));
		}
	
		function index(){
    	if($this->input->post('new')){
				unset($_POST['new']);
				$this->Call->new_from_post();
			}

			$data['Calls'] = $this->Call->open_calls();
			
			$this->subnav_active = "index";
			$this->simple_page('list_calls', $data);
		}
		
		
		function mine(){
			$data['Calls'] = $this->Call->my_calls();
			
			$this->subnav_active = "mine";
			$this->simple_page('list_calls', $data);  
		}
		
		function answered(){
			$data['answered'] = true;
			$data['Calls'] = $this->Call->answered_calls();
			
			$this->subnav_active = "answered";
			$this->simple_page('list_calls', $data);
		
		}
		
		function close(){
			$id = $this->uri->segment(3);
			$call = $this->Call->get_one($id); 
			
			$this->simple_page('answer', array('Call' => $call));		
		}
		
		function record_answer(){
			$this->Call->save_answer();
			redirect('Calls');
		}

				
		function edit(){
			$id = $this->uri->segment(3);
			$call = $this->Call->get_one($id); 
		
			$this->simple_page('edit', array('Call' => $call));
		}
		
		function update_call(){
			//TODO: move to model, use sani post instead of raw post
			$this->db->where("id", consume($_POST,'id'));
		
			$datetime = mdy_to_mysql(consume($_POST, 'date')) . ' ' . time_to_mysql(consume($_POST, 'time'));	
			$_POST['called_when'] = $datetime;
		 
			$this->db->update('calls', $_POST);
			redirect("Calls");
		}
		
		function create(){
			$this->subnav_active = "create";
			$this->simple_page('create');
		}
		
		function delete(){
			$this->Call->delete();
			redirect('Calls');
		}
		
	}

//eof