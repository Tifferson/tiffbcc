<?

class Reminders extends OC_Controller{


	
	function __construct(){
		parent::__construct();
		$this->require_login();
		
    $this->load->model('Reminder');

    //OC_Controller Variables
		$this->title = "BCC > Tasks > Reminders";
		$this->main_nav = "tasks";
		$this->nav_active = "reminders";
		$this->path_prefix = 'reminders/';

	}

  function create(){


  }

  function show(){

	  $data['reminders'] = $this->Reminder->get_for_user($this->session->userdata('user_id'));

    if(  $this->session->userdata('is_admin') == '1' ) :
        $data['other_reminders'] = $this->Reminder->get_others($this->session->userdata('user_id'));
    endif;

    $this->simple_page('show', $data);
	
  }


/* AJAX CALLS */
  function add_new(){

     $date = $this->input->post("date");
     $user = $this->User->id_to_name($this->input->post("user_id"));
     $notes = $this->input->post("notes");
  
     $id = $this->Reminder->add_new();
     error_log("id:".$id);
     if( $id !== false ) :
      $r = $this->Reminder->get($id);
      $this->load->view("reminders/reminder_li", array('reminder' => $r->row()));
     else :
      echo "error";
     endif;

  }

  function delete(){
    $id = $this->input->post("id");
    
    if( !empty($id) && $this->Reminder->delete($id) ) :
      echo "Deleted";
     else :
      echo "error";
     endif; 

  }


  function mark_done(){
    $id = $this->input->post("id");
    
    if( !empty($id) && $this->Reminder->mark_done($id) ) :
      echo "Marked done";
     else :
      echo "error";
     endif; 

  }

}


//end of file




  
