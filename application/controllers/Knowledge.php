<?
class Knowledge extends OC_Controller{
	
	function __construct(){
		parent::__construct();
		
		$this->title = "Knowledge Base";
		$this->main_nav = "knowledge";
		$this->nav_active = "knowledge";
		$this->path_prefix = 'knowledge/';
	}
	
	function index(){
        $this->require_login();
		
		$this->simple_page("home");
	}
}