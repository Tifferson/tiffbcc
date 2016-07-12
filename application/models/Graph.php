<?

class Graph extends OC_Model{
	
	function __construct(){
		parent::__construct();
		
		//$this->load->model(array());
	}
	
	
	function monthly($year){
		$query = "SELECT MONTH(`date`) as 'month', 
					SUM(gallons) as gallons FROM `pickups` 
					WHERE YEAR(`date`)='$year' 
					GROUP BY MONTH(`date`)
					ORDER BY MONTH(`date`)";
		$rs = $this->db->query($query);
		
		return $this->rs_to_months($rs);
	}
	
	
	function rs_to_months($rs){
		$monthly_graph = $rs->result_array();
		
		$month_data = array();
		foreach(array(1,2,3,4,5,6,7,8,9,10,11,12) as $month) :
			foreach($monthly_graph as $data) :
				if($data['month'] == $month)
					$month_data[$month] = $data['gallons'] / 100;
			endforeach;
			
			if(!isset($month_data[$month]))
				$month_data[$month] = 0;
		endforeach;
		
		$string = '';
		foreach($month_data as $point)
			$string .= $point. ",";
		$string = trim($string, ",");
		
		return $string;
	}

	function sales_between_month($start_date, $end_date){
		$start = mdy_to_mysql($start_date);
		$end = mdy_to_mysql($end_date);
	
		$query = "SELECT MONTH(`date`) as 'month',
						YEAR(`date`) as 'year',		
					SUM(amount) as amount FROM `biodiesel_outgoing` 
					WHERE `date` >= '$start'
						AND `date` <= '$end'
					GROUP BY MONTH(`date`)
					ORDER BY MONTH(`date`)";
		$rs = $this->db->query($query);
		
	//	$monthly_graph = $rs->result_array();
		
		//$month_data = array();
		$max = 0;
		
		/*foreach(array(1,2,3,4,5,6,7,8,9,10,11,12) as $month) :
			foreach($monthly_graph as $data) :
				if($data['month'] == $month) :
					$month_data[$month] = $data['amount'];
					if($data['amount'] > $max) $max = $data['amount'];
				endif;
			endforeach;
			
		endforeach;
		*/
		
		$month_data = array();
		$labels = array();
		
		foreach($rs->result_array() as $month) :
			$month_data[]= $month['amount'];
			$labels[]= num_to_month($month['month']) . " " . $month['year'];
			if($month['amount'] > $max) $max = $month['amount'];
		endforeach;
		
		$data_string = implode(',',$month_data);
		$label_string = urlencode(implode('|',$labels));
		return array('data' => $data_string, 
						'label' => $label_string,
						'max' => $max);
	
	}
	
	
	function sales_by_customer($start_date, $end_date){
		$start = mdy_to_mysql($start_date);
		$end = mdy_to_mysql($end_date);
	
		$query = "SELECT customers.name as name,
					SUM(biodiesel_outgoing.amount) as amount 
					FROM `biodiesel_outgoing`, `customers`
					WHERE `biodiesel_outgoing`.`date` >= '$start'
						AND `biodiesel_outgoing`.`date` <= '$end'
						AND biodiesel_outgoing.customer_id = customers.id
					GROUP BY MONTH(`biodiesel_outgoing`.`date`)
					ORDER BY MONTH(`biodiesel_outgoing`.`date`)";
		$rs = $this->db->query($query);
		
		$max = 0;
		if($rs->num_rows() == 0) return array();
		foreach($rs->result_array() as $customer) :
			$data[]= $customer['amount'];
			$labels[] = $customer['name'];
			if($customer['amount'] > $max) $max = $customer['amount'];
		endforeach;
		
		$data_string = implode(',',$data);
		$label_string = implode('|',$labels);
		return array('data' => $data_string, 
						'label' => $label_string,
						'max' => $max);
	
	}
	
	function customer_acquisition(){
		$query = "SELECT COUNT(id) as total, 
							date_format(date_added_as_customer,'%b 1, %Y') as date_added_as_customer
						FROM locations
						WHERE MONTH(`date_added_as_customer`) != 0
						GROUP BY YEAR(`date_added_as_customer`), MONTH(`date_added_as_customer`)" ;
		
		$rs = $this->db->query($query);
		
		$min = 0;
		$max = 0;
		$data=array();
		foreach($rs->result() as $row) :
			$date=$row->date_added_as_customer;
			$total=$row->total;
			$data[] = array($total, $date);
			if($row->total > $max) $max = $row->total;
			if($row->total < $min) $min = $row->total;
		endforeach;

		$line = array();
		foreach($data as $datum) {
			$line[] = "['".implode("','",array_reverse($datum))."']";
		}
		$line = "[".implode(",",$line)."]";
				
		
		return array('data' => $data, 'max' => $max, 'min' => $min, 'linedata' => $line);
	
	}
}