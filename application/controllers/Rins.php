<?

class Rins extends OC_Controller{
	
	function __construct(){
		parent::__construct();
		
		$this->title = "RIN Management";
		$this->main_nav = "reporting";
		$this->nav_active = "rins";
		$this->path_prefix = 'rins/';
		
		$this->load->model(array("Rin"));
	}
	
	function index(){
		$data['rins'] = $this->rin->get_all();
		$this->subnav_active = "show";
		$this->simple_page("list_rins", $data); 
	}
}
