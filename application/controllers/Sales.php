<?

class Sales extends OC_Controller{

	function __construct(){
		parent::__construct();
		
		$this->load->model(array('Customer', 'Sale',  'Product', 'Incoming', 'Container'));
		
		$this->title = "DGF Fuel Sales";
		$this->main_nav = "products";
		$this->nav_active = "sales";
		$this->path_prefix = 'sales/';
	}

	//list sales
	function index(){
		$this->require_login();
		redirect("Sales/show");
	}
	
	//enter a sale
	function sale(){
	  $data['customers'] = $this->Customer->select_array();
	  $data['products'] = $this->Product->select_array();
	  $data['containers'] = $this->Container->select_array();
	  
	  $this->subnav_active = "sale";
	  
	  $this->simple_page("enter_sale", $data);
	}
	
	
	//no display-- enter sale into system
	function create_sale(){
	 
		$this->Sale->new_sale();
		$this->session->set_flashdata("message", "Entered sale");
		redirect("Sales/show");
	}
		
	function show(){
	  $this->subnav_active = "show";
		$this->simple_page("show_sales");
	}

	function get_sales(){
		$this->load->model("graph");
	
		$start = $this->input->post("start_date");
		$end = $this->input->post("end_date");
		
		$data['start_date'] = $start;
		$data['end_date'] = $end;
		
		$data['sales'] = $this->Sale->sales_between($start, $end);
		if($data['sales']->num_rows() == 0) :
			$data['month_graph'] = $this->Graph->sales_between_month($start, $end);
			$data['customer_graph'] = $this->Graph->sales_by_customer($start, $end);
		endif;
		$this->load->view("sales/list_sales", $data);
	}
	
	function customers(){
	  $data['customers'] = $this->Customer->get_all();
	  $this->simple_page('list_customers', $data);
	}
	
	//display new customer page
	function create_customer(){
		$this->simple_page("new_customer");
	}
}