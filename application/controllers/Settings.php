<?

class Settings extends OC_Controller{

	function __construct(){
		parent::__construct();
		
		$this->title = "Settings";
		$this->main_nav = "settings";
		$this->nav_active = "config";
		$this->path_prefix = 'settings/';

	}

	function index(){
		$rs = $this->db->get('settings');
		$data['s'] = $rs->row();
		
		$this->subnav_active = "config";
		$this->simple_page("home", $data);
	}
	
	function update_settings(){

    $_POST['track_solids_and_water'] = $this->input->post('track_solids_and_water') ? 1 : 0;		

		$this->db->update("settings", $_POST);
		$this->session->set_flashdata("message", "Settings updated");
		redirect("Settings");
	}

}
