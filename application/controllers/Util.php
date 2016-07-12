<?

class Util extends OC_Controller {

	var $sp = "<br/>\n";
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper("download");
    $this->load->model('Batchupload');
	}
	
	function save(){
		$table = $this->uri->segment(3);
		$id = $this->uri->segment(4);
		$field = $this->input->post('field', true);
		$value = $this->input->post('value', true);
		
		if($field && $value) :
			$this->db->set($field, $value);
			$this->db->where('id', $id);
			$this->db->update($table);
		endif;	
	}
	
	function phone_numbers(){
	
		$data['output'] = file_get_contents("http://www.dining512.com/N_Austin.html");
		$this->simple_page("sandbox/512_restaurant_parser", $data);
	
	}
	
	function g(){
		$this->load->view("sandbox/google_search_api");
	}
	
	function dialog(){
		$this->simple_page("sandbox/dialog");
	}
	
	function keepalive(){
	
	}
	
	function download(){
	
		$filename = preg_replace("/\/Util\/download/", ".", uri_string());//$this->session->flashdata("download_file");
		
		if($file = read_file($filename)){
			$name = basename($filename);
			force_download($name, $file);
		}
		else{
			$this->load->view("download_error");
		}
	}
	
	function index(){
		echo "Utilities" . $this->sp;
	}
	
	function test(){
		$this->load->view('simple_header', array('title' => "Test"));
		$this->load->view('sandbox/select_w_other.php');
		$this->load->view('simple_footer');
	}
	
	

	
	function geocode(){
	//	$this->db->where("(latitude='' or longitude='') and is_customer=1");
	//	$locations = $this->db->get('locations');
		$this->db->limit(2);
		$locations = $this->Location->customers();
		

		echo $locations->num_rows();

		foreach($locations->result() as $location){
			
			$address = loc_address($location);
			$url = "http://geocoder.us/service/csv?address=" . urlencode($address);
			echo $url;
			$handle = fopen($url, "r");
			$contents = '';
			while (!feof($handle)) {
			  $contents .= fread($handle, 8192);
			}
			
			fclose($handle);
			echo $contents;
			echo "<br/>";
			
			$data = explode(",", $contents);
			
			if(isset($data[1])) :
				$coords = array( 'latitude' => $data[0], 'longitude' => $data[1]);
			
				if(is_numeric($coords['latitude']) && is_numeric($coords['longitude'])) : 
					$this->db->where('id', $location->id);
					$this->db->update('locations', $coords);
				else :
					echo "non-numerics";
				endif;
			else :
				echo "ERROR";
			endif;
			
			echo "<br/>";
			sleep(1);

		}
	
		
	}
	
	function gizmo(){
		$sp = $this->sp;
		$count = 0;
		
		$query = "SELECT * FROM `locations_gizmo` WHERE `id` IN (SELECT `fkContacts` FROM `events_gizmo`)";
		$gizmos = $this->db->query($query);
		
		foreach($gizmos->result() as $gizmo){
			
			echo "<b>Gizmo Location: " . $gizmo->id . " </b> $sp";
			echo $gizmo->name . $sp;
			echo $gizmo->address . $sp;

			//match gizmo to mrl locations
			$this->db->where('name LIKE', $gizmo->name);
			$this->db->where("city_address LIKE '%" . $this->street_number($gizmo->address) . "%'");
			$rs = $this->db->get("locations");
			
			if($rs->num_rows() > 0){
				$count++;
				$loc = $rs->row();
				
				$this->db->where('fkContacts', $gizmo->id);
				$rs_giz_events = $this->db->get('events_gizmo');
				foreach($rs_giz_events->result() as $event){
			
					$sales_event = array(
						'location_id' => $loc->id,
						'when' => $event->date1,
						'dgf_rep' => $event->associate . " (gizmo)",
						'description' => $event->action . "",
						'notes' => "[Imported from Gizmo] " . $event->comments
					);
					var_dump($sales_event);
					$this->db->insert('sales_events', $sales_event);
					echo $sp;
				}

				echo "<b>DGF Location: " . $loc->id . "</b>$sp";
				echo $loc->name . $sp;
				echo $loc->city_address . $sp; 
			}
			else{
				echo "not found in locations" . $sp;
				$sales_event = array(
						'when' => $event->date1,
						'dgf_rep' => $event->associate . " (gizmo)",
						'description' => $event->action,
						'notes' => "[Imported from Gizmo] " . $event->comments
				);
				print_r($sales_event);	
			}
			echo $sp . $sp;
		}

		$total_found = $gizmos->num_rows();
		echo "$sp Total Found: $total_found $sp";
		echo "Total Edited: $count $sp";
	
	}
	
	function strip($string){
		return preg_replace("/[^\d\w]/",'',$string);
	}
	
	function street_number($string){
		$matches = array();
		preg_match("/^([\d]*)?\s/", $string, $matches);
		if(isset($matches[1])) return $matches[1];
		else return "";
	}
	
	function import_field(){
		$sp = $this->sp;
		$count = 0;

		$field = 'status';
		
		$this->db->where("$field IS NOT NULL");
		$this->db->where("$field != ''");
		$gizmos = $this->db->get('locations_gizmo');
		
		foreach($gizmos->result() as $gizmo){
			
			echo "Gizmo Location: $sp";
			echo $gizmo->name . $sp;
			echo $gizmo->address . $sp;
			echo "$field: " . $gizmo->$field . $sp;

			
			$this->db->where('name LIKE', $gizmo->name);
			$this->db->where("city_address LIKE '%" . $this->street_number($gizmo->address) . "%'");
			$rs = $this->db->get("locations");
			
			if($rs->num_rows() > 0){
				$count++;
				$row = $rs->row();
				
				if(empty($row->$field)){
					$this->db->where('id', $row->id);
					$this->db->set('notes', $row->notes . " [ " . $gizmo->$field . " ] -Gizmo");
					$this->db->update("locations");
					echo "updated" . $sp;
				}
				else{
					print "Not edited" . $sp;
					
				}
				
				//continue mapping-- should i include events???
				echo "DGF Location: $sp";
				echo $row->name . $sp;
				echo $row->city_address . $sp;
			}
			else{
				echo "not found in locations" . $sp;
			}
			echo $sp;
		}

		$total_found = $gizmos->num_rows();
		echo "$sp Total Found: $total_found $sp";
		echo "Total Edited: $count $sp";
	}
	
	function delete_doubles(){
		//$this->db->limit(10);
		$sp = $this->sp;
		$names = array();
		$locs = $this->db->get('locations');
		$count = 0;
		foreach($locs->result() as $location){
			
			$name = $location->name;

			$street_address = $location->city_address;
			
			$this->db->where("name LIKE", $name);
		//	$this->db->where('is_lead !=', 1);
		//	$this->db->where('is_customer !=', 1);
			$this->db->where("city_address LIKE", $street_address);
			$rs = $this->db->get('locations');
			
			if($rs->num_rows() > 1 && !in_array($name, $names)){
				$good_id = $rs->row();
				$bad_loc = $rs->row();
				//$this->db->where('id', $bad_loc->id);
				//$this->db->delete('locations');
				$names[] = $name;
				echo $name . " -- " . $sp;
				echo $street_address . $sp;
				$count++;
			}
			
			
		/*	$name = preg_replace("/[^\w]/",'',$name);
			echo $name . $sp;
		*/

		}
		echo "Total: $count $sp"; 
	}
	
	
	
	function import(){
	
	
	  $data = '';
	  
	  $files = array('north', 'northwest', 'northeast', 'west', 'central', 'east', 'southwest', 'southeast', 'south');
	  foreach($files as $f){
	      $data .= read_file("./dining512/$f.txt") ."\n";
	  }

	  $lines = explode("\n", $data);
	  
	  $count = 0;
	  $max = 2000;
	  $manual = 0;
	  $new = 0;
	  $updated = 0;
	  $nothing = 0;
	  $matches = 0;
	  $live = true;
	  	
	 $l_to_p = count($lines);  
	  print "Lines to process: " . $l_to_p . '<br/>';
	  	  
	  foreach($lines as $line) :
	    if( ($l_to_p / 3 > $count) && count($lines) < 6) :
	      echo "done <br/>";
	      break;
	    endif; 
	    
	    if($count++ > $max) break;
	    print $count . '<br/>';
	    
	    $name = array_shift($lines);
	    $address = array_shift($lines);
	    	    
	    if(!preg_match('/\d/', $address)) :
	      $name .= ' ' . $address;
	      $address = array_shift($lines);
	    endif; 
	    
  
	    $citystate = array_shift($lines);
	    $phone = array_shift($lines);
	    $space = array_shift($lines);
	    
	 //   if($space != "\n") array_unshift($lines, $space);
	

  	    echo "Info from 512<br/>";	    
  	    echo $name . "<br/>";
  	    echo $address . "<br/>";
  	    echo $citystate . '<br/>';
  	    echo $phone . '<br/>';
	    
  	    $a_tokens = explode(' ', $address);
  	    $size = count($a_tokens);
	     
  	    if($size > 0) : 
    	    $street_num = $a_tokens[0];
	    
    	    if($size > 1 ) : 
    	      if(strlen($a_tokens[1]) <= 2 && $size >= 2){
      	      $street_name = $a_tokens[2];
      	    }else{
      	      $street_name = $a_tokens[1];
      	    }
      	    $do_second_try = true;
      	  else :
      	    $do_second_try = false;
    	    endif; 
    	  else : 
    	    $do_second_try = false;
    	  endif;
	  

  	    //first try
  	    $this->db->like('name' , $name );
  	    $this->db->like('city_address' , $address );
  	    $rs = $this->db->get("locations");
	    
  
  	    if($rs->num_rows() > 0) : 
  	      //it worked!! (unlikely)
  	      $winner = $rs->row_array();
  	      echo "Tight Match {$winner['name']} <br/>";
	      
  	      $old_phone_key = preg_replace('/[^\d]/', '', $winner['phone']);
  	      $new_phone_key = preg_replace('/[^\d]/', '', $phone);
  	      
  	      
  	      if($old_phone_key == $new_phone_key) : 
  	        echo "Old phone ($old_phone_key) and new phone ($new_phone_key) match <br/>";
  	        $matches++;
  	      else :
  	        echo "Updating {$winner['name']}'s phone number (was: {$winner['phone']}) in our db...<br/>";
  	        if($live) :
  	          $this->db->update('locations', array('phone' => $phone), array('id' => $winner['id']));
  	        endif;
  	        $updated++;
  	      endif;
	        
	        
  	    else :
	      
  	      if($do_second_try) :
  	      //second try
	      
    	     // $this->db->like('city_address', $street_num);
	     
    	      $this->db->where('city_address REGEXP \'^' . addslashes($street_num) . ' \'');
    	      $this->db->like('city_address', $street_name );
	      
    	      $rs = $this->db->get('locations');
	      
	      
    	      if($rs->num_rows() > 0) :
    	        //second try worked
    	        $winner = $rs->row_array();
    	        echo "Loose Match Found {$winner['name']} @ {$winner['dgf_address']}";
    	        
    	        $old_phone_key = preg_replace('/[^\d]/', '', $winner['phone']);
            	$new_phone_key = preg_replace('/[^\d]/', '', $phone);
	        
    	        //find something matching in the name
    	        $name_tokens = explode(' ', $name);
    	        if(stristr($winner['name'], $name_tokens[0])) :
    	          echo " and want to update from ({$winner['phone']}) to ($phone)<br/>";
    	              

        	      if($old_phone_key == $new_phone_key) : 
        	        echo "Old phone ($old_phone_key) and new phone ($new_phone_key) match <br/>";
        	        $matches++;
        	      else :
        	        echo "Who's phone number is right? <br/>";
        	      endif;
    	        else : 
    	           echo " and will not update {$winner['name']},  {$winner['dgf_address']}  <br/>";
    	           
    	           if($old_phone_key == $new_phone_key) : 
          	        echo "Old phone ($old_phone_key) and new phone ($new_phone_key) match <br/>";
          	        $matches++;
          	      else :
          	        echo "Likely Colocation - entering $name, $address as new record into db";
          	        $manual++;
          	        //----
          	                 $cs_tokens = explode(',', $citystate);
                	            $city = $cs_tokens[0];
                	            $data = array(
                   	                  'name' => $name,
                   	                  'dgf_name' => $name,
                   	                  'city_address' => $address,
                   	                  'dgf_address' => $address,
                   	                  'phone' => $phone,
                   	                  'city' => $city,
                   	                  'state' => 'TX',
                   	                  'lead_source' => 'dining512.com',
                   	                  'notes' => "Imported from dining512 ($count)",
                   	                  'region' => 'A',
                   	                  'date_added' => now_for_mysql()
                   	          );
                   	          if($live) : 
                   	            $this->db->insert('locations', $data);
                   	          else :
                   	            var_dump($data);
                   	          endif;
                   	  //-----
          	      endif;
    	          
    	        endif;	        
	      

    	      else :
    	          echo "New Entry - entering $name, $address as new record into db";
    	          $new++;
    	          
    	            $cs_tokens = explode(',', $citystate);
    	            $city = $cs_tokens[0];
    	            $data = array(
       	                  'name' => $name,
       	                  'dgf_name' => $name,
       	                  'city_address' => $address,
       	                  'dgf_address' => $address,
       	                  'phone' => $phone,
       	                  'city' => $city,
       	                  'state' => 'TX',
       	                  'lead_source' => 'dining512.com',
       	                  'notes' => "Imported from dining512 ($count)",
       	                  'region' => 'A',
       	                  'date_added' => now_for_mysql()
       	          );
       	          if($live) : 
       	            $this->db->insert('locations', $data);
       	          else :
       	            var_dump($data);
       	          endif;
	              
    	      endif;
    	    else : 
    	        echo "Didn't make second attempt b.c something failed, ignoring $name - $address <br/>";
    	        $nothing++;
    	    endif;
  	    endif;   
  	     
  	    echo "<br/>--<br/>";  
  	    
	  endforeach;
	  
	  echo "Matched what we had: $matches, percentage: " . p_num(100 * (1.0 * $matches/$count)) . '<br/>'; 
	  
	  echo "Updated Phone Number: $updated, percentage: " . p_num(100 * (1.0 * $updated/$count)) . '<br/>';
	  
	  echo "Not Found in our DB and added as new: $new, percentage: " . p_num(100 * (1.0 * $new/$count)) . '<br/>';
	   
	  echo "Likely Colocations: $manual, percentage: " . p_num(100 * (1.0 * $manual/$count)) . '<br/>';
	   
	  echo "Did nothing: $nothing, percentage: " . p_num(100 * (1.0 * $nothing/$count)) . '<br/>';
	  
	}
	
	/*function dbforge(){
		$tables = array('activity_log', 'biodiesel_blendings', 'biodiesel_incoming', 'biodiesel_outgoing',
							'calls', 'collections', 'container_types', 'customers', 'locations',
							'locations_backup', 'locations_backup_oldest', 'locations_deleted',
							'locations_routes', 'oil_outgoing', '');

	}*/	


  function quickbooks(){
    $this->batchupload->import_quickbooks();

  }
}

?>
