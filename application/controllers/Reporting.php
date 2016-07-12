<?

class Reporting extends OC_Controller{
	
	function __construct(){
		parent::__construct();
		
		$this->title = "Reporting";
		$this->main_nav = 'reporting';
		$this->nav_active = "reporting";
		$this->path_prefix = 'reporting/';
		
		$this->load->model("Graph");
	}
	
	function index(){
		
		$this->simple_page("list_reports");
		
	}
	
	
	
	function chart(){
		$data['monthly_graph'] = $this->graph->monthly('2009');
		$this->simple_page("chart", $data);
	}
	
	function acquire(){
		$data['graph'] = $this->Graph->customer_acquisition();
		$this->simple_page("customer_acquisition", $data);
	
	}
	
	
}