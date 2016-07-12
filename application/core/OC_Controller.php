<?
	class OC_Controller extends CI_Controller{
	
		var $main_nav;
		var $nav_active;
		var $subnav_active;
		var $title;
		var $path_prefix;

		
	  function __construct(){

           

      parent::__construct();

      //alternate: 'http'. ($_SERVER['HTTPS'] ? 's' : null) .'://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];			
	  }
		
		function simple_page($page, $data = array(), $subnav = true){

      //so we can send people "back" appropriately
      $this->session->set_userdata('previous_url', $this->session->userdata('current_url'));
      $this->session->set_userdata('current_url', $this->uri->uri_string());


			//load header
			$this->load->view('simple_header', array("title" => $this->title));
			
			//load main navigation
			$params['active'] = $this->main_nav;
			$params['second_nav'] = $this->nav_active;
			
			$params['roles'] = $this->session->userdata('roles');
			$this->load->view('site_nav', $params);
			
			//load sub navigation if file exists
			if($subnav && file_exists(APPPATH . "views/" . $this->path_prefix . "subnav.php")) :
				$this->load->view($this->path_prefix . 'subnav', array('active' => $this->subnav_active));
			endif;
			
			//load flash message (will be display if in session)
			$this->load->view("flash_message");
			
			//load the page itself
			$this->load->view($this->path_prefix . $page, $data);
			
			//load footer
			$this->load->view('simple_footer');
		}
		
		function simple_header(){
			$this->load->view('simple_header', array("title" => $this->title));
			$this->load->view('site_nav', array('active' => $this->nav_active));
			$this->load->view($this->path_prefix . 'subnav', array('active' => $this->subnav_active));
		}
		
		function require_login(){
			$ci = &get_instance();
			if($ci->session->userdata('logged_in')){
				//prevent sessions from spilling over
		/*		$id = $ci->session->userdata('user_id');
				$this->db->where("users", array("
		*/
			} 
			else{
				$this->session->set_flashdata("message_class", "warning");
				$this->session->set_flashdata("message", "Login Required");
				$this->session->set_flashdata("destination", $_SERVER['REQUEST_URI'] );
				
				//echo "LOGIN FAIL";
				redirect("Auth");
			}
            
	}
		
		function reports(){
			$this->subnav_active = 'reports';
			$this->simple_page("reports");
		}
		
	
	}
	
?>