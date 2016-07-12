<?
class Call extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function new_from_post(){
	
		$date = mdy_to_mysql(consume($_POST, 'date'));
		$time = time_to_mysql(consume($_POST, 'time'));
		$datetime =  $date . ' ' . 	$time;
		$_POST['called_when'] = $datetime; 

		$this->db->insert('calls', $_POST);
	}
	
	function open_calls(){
		$this->db->order_by('called_when DESC');
		$this->db->where('answered', '0');
		return $this->db->get('calls');
	}
	
	function my_calls(){
	  $this->db->order_by('called_when DESC');
		$this->db->where('answered', '0');
		$this->db->where('user_id', $this->session->userdata('user_id'));
		return $this->db->get('calls');
	}
	
	function answered_calls(){
		$this->db->order_by('answered_when DESC');
		$this->db->where('answered', '1');
		return $this->db->get('calls');
	}
	
	function months_of_calls_ans(){
	  $query = "SELECT DISTINCT MONTH(called_when) as month, 
	                            MONTHNAME(called_when) as month_name, 
	                            YEAR(called_when) as year 
	                            FROM calls 
	                            WHERE answered=1
	                            ORDER BY year DESC, month DESC";
	  $rs = $this->db->query($query);
    return $rs->result();
	}
	
	function save_answer(){
		$id = $this->input->post('id');
		$response = $this->input->post('response');
			
		$this->db->where('id', $id);
		$this->db->set('response', $response);
		$this->db->set('answered', 1);
		$this->db->set('answered_when', now_for_mysql());
			
		$this->db->update('calls');
	}
	
	
	//POSSIBLY ADD THESE AS EXTENSION TO MODEL
	
	function get_one($id){
		$response = $this->db->get_where("calls", array('id' => $id));
		return $response->row();
	}
	
	function delete(){
		$id = $this->input->post('id');
		$this->db->where('id', $id);
		$this->db->delete('calls');
	}


}
?>