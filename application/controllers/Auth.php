<? 

class Auth extends CI_Controller{

	function __construct(){
		parent::__construct();
	}
	
	function index(){
		//if you're logged in you don't belong here...
		if($this->session->userdata("logged_in")) redirect("Home");
		
		$this->session->keep_flashdata("destination");
		$data['users'] = $this->User->employees();
	
  	$this->load->view("simple_header", array("title" => 'Login to Biodiesel Control Center'));
		$this->load->view('login_form', $data);
		$this->load->view('simple_footer');
		
		
	}
	
	function do_login(){
	
		$this->db->where('name', $this->input->post('user', true));
		$this->db->or_where('email', $this->input->post('user', true));
		$result = $this->db->get('users');

  		$user = $result->result();

		if ($result->num_rows() == 0) :
			$this->fail("Username not found");

		else :
      			$password =  trim($this->input->post('password', false));
			foreach($result->result() as $user) :	
				if ($user->active != 1) :
					$this->fail("Account disabled");
				elseif( $password == $user->password) :
					$this->log_login($user->id, true);
					$this->populate_session($user);
					redirect('Home');
				else :
					$this->log_login($user->id, false);
					$this->fail("Password incorrect");
				endif;
			endforeach;
		endif;
		
	}
	
	function log_login($user_id, $success) {
		if ($success):
			$this->db->set('last_login_ip', $this->input->ip_address());
			$this->db->set('last_login_datetime', 'NOW()', FALSE);
			$this->db->set('login_success_count', 'login_success_count+1', FALSE);
		else:
			$this->db->set('login_fail_count', 'login_fail_count+1', FALSE);
		endif;
		$this->db->where('id', $user_id);
		$this->db->update('users');
	}

	function populate_session($user){
		$this->session->set_userdata('logged_in', true);
		$this->session->set_userdata('user_name', $user->name);
		$this->session->set_userdata('user_id', $user->id);
		$this->session->set_userdata('is_admin', $user->is_admin);
		$this->session->set_userdata('is_employee', $user->is_employee);
		$this->session->set_userdata('roles', array('t' => $user->tasks,
								's' => $user->sourcing,
								'c' => $user->collection,
								'p' => $user->production,
								'r' => $user->reporting,
								'k' => $user->knowledge));
	}
	
	function fail($message){
		$this->session->set_flashdata("message_class", "error");
		$this->session->set_flashdata("message", $message);
		$this->session->keep_flashdata("destination");
		redirect("Auth");
	}
	
	function logout(){
		$this->session->unset_userdata('logged_in');
		$this->session->unset_userdata('user_id');
		$this->session->set_flashdata("message", "Logged Out");
		redirect("Auth");
	}

  function forgot_password(){
  	  $this->load->view("simple_header", array("title" => 'Forgot Password'));
      $this->load->view("Auth/forgot_password");
		  $this->load->view('simple_footer');
  }

  function process_forgot_password(){

      $username = $this->input->post("username");

      $user = $this->user->has_email($username);

      $this->load->view("simple_header", array("title" => 'Forgot Password'));
      if($user->active==1) :
        //send email
        $message = 'Your password for biodieselsoftware.com is:\n\t ' . $user->password;
        $subject = "Password for biodieselsoftware.com";
        $data['mail_sent'] = mail($user->email, $subject, $message);
        $this->load->view("Auth/forgot_password_result", $data);
      else :
        $data['lookup_failed'] = true;
        $this->db->where('is_admin', 1);
        $data['admins'] = $this->user->employees();
        $this->load->view("Auth/forgot_password_result", $data);
     endif;
     $this->load->view('simple_footer');
  }

}
