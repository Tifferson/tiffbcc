<?
Class Users extends OC_Controller{

	function __construct(){
		parent::__construct();
		
		$this->title = "Users";
		$this->main_nav = "settings";
		$this->nav_active = "users";
		$this->path_prefix = 'users/';
		
		$this->load->model(array('User'));


    //boot people not allowed

    if(! $this->session->userdata('is_admin') ) :
      $this->session->set_flashdata("message", "cannot access this part of site");
      redirect("");    
    endif;
	}
	
	function index(){
		$this->session->keep_flashdata('message');
		redirect("Users/permissions");
	}
	
	function permissions(){
    $this->db->order_by("active", 'desc');
		$data['users'] = $this->User->get_all();

		$this->subnav_active = "list_users";
		$this->simple_page("list_users", $data);
	}
	
	function save_permissions(){
		$this->User->save_permissions();
		$this->session->set_flashdata("message", "User Permissions Saved");

		redirect("Users");
	}
	
	function create(){
		if($this->input->post("save")) :
			//save and redirect
			$this->User->save_new();
			$this->session->set_flashdata("message", "Saved New User");
			redirect("Users");	
		else : 	
			$this->subnav_active = "new";
			$this->simple_page("new_user");
		endif;
	}

	function edit(){
		if($this->input->post("save")) :
			//save and redirect
			$this->User->update();
			$this->session->set_flashdata("message", "Edited User");
			redirect("Users");
		else : 
			//show edit screen
			$id = $this->uri->segment(3);
			$data['user'] = $this->User->get($id);		
			$this->simple_page("edit", $data);
		endif;
	}

  
  function delete(){
    $dependencies = $this->User->find_dependencies();
        
    if($dependencies) : 
      echo $dependencies;
    else :  
      $this->User->delete();
      echo "Deleted user";
    endif;
  }

}
