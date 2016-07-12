<?


class Batchupload extends CI_Model{

	var $mapping;
	var $blocklist = array('id');

	function __construct(){
		parent::__construct();
		
		$this->mapping = array();
	
	}
	
	function blocklist(){
		return $this->blocklist;
	}

	function upload_from_file($filename){
		$this->parse_csv($filename);
	}

	function get_fields(){
		$query = "DESCRIBE locations";
		$rs = $this->db->query($query);
		return $rs;
	}
	
	function make_mapping_from_post(){
		$mapping = $this->input->mapping();

	}
	
	function prep_db(){
		$this->db->set("is_new_lead", "0");
		$this->db->update("locations");
	}
	
	function screen_na($string){
		if(trim($string) == "N/A")
			return '';
		else return $string;
	}
	
	function parse_fs_csv($filename, $commit_changes = false){

		$this->prep_db();
		
		$results = array();
	
		$file_handle = fopen($filename, "r");
		
		//eat first line for lunch/dinner
		$first_line = fgetcsv($file_handle, 2048);

		while (!feof($file_handle) ) :

			$line = fgetcsv($file_handle, 2048);
			$name = clean_txt($line[0]);

     if(!empty($name)) :
			$address = clean_txt($line[5]);
			$notes = clean_txt($line[2]);

      //build location from CSV columns
			$location = array(
	          'name' => $name,
	          'dgf_name' => clean_txt($name),
	          'city_address' => $address,
	          'dgf_address' => $address,
	          'city' => clean_txt($line[6]),
			  'state' => $line[7],
			  'zip' => $line[8],
	          'food_service_type' => trim($this->screen_na($line[18]) . ' ' . $line[1]),
			  'contact_name' => $this->screen_na($line[9]) . ' ' . $this->screen_na($line[10]),
			  'phone' => $line[11],
			  'date_added' =>  mdate("%Y/%m/%d", time()),
			  'notes' => $notes,
			  'license_status' => $line[14],
			  'region' => $this->input->post("region"),
			  'is_lead' => '1',
			  'is_new_lead' => '1',
			  'foodservice_lead' => '1'
	        );
			
			
			if($line[15] == "OWNER"){
				$location['business_owner'] = $location['contact_name'];
			}
 
			$id = $this->find_double($name, $address);	

				if($id === FALSE) :
          if($commit_changes) $this->db->insert('locations', $location);
					$location['type'] = 'new';
					$results[] = $location;
				else :
				//	$this->db->where('id', $id);
				//	$this->db->update('locations', $location);
					$location['type'] = 'already exists';
				  $results[] = $location;
				endif;
			endif;

		endwhile;
		
		fclose($file_handle);
		
		return $results;
	}

	

/*
	function parse_city_csv($filename){
		$this->prep_db();
		
		$results = array();
	
		$file_handle = fopen($filename, "r");
		
		//eat first line
		$headers = fgetcsv($file_handle, 2048);

		while (!feof($file_handle) ) :

			$line = fgetcsv($file_handle, 2048);
      
	      
	      $name = ucwords(strtolower($line[0]));
			
			if(!empty($name)) :

		    $address = ucwords(strtolower($line[3]));
	      $matches = array();
	      preg_match('/(\D*)(\d*)/i', $line[4], $matches);
		  
		   $location = array(
	          'name' => $name,
	          'dgf_name' => clean_txt($name),
	          'city_date_updated' => mdy_to_mysql(empty($line[2]) ? $line[1] : $line[2]),
	          'city_address' => $this->clean_string($address),
	          'dgf_address' => $this->clean_string($address),
	          'city' => ucwords(strtolower($matches[1])),
	          'zip' => $matches[2],
			  'state' => 'TX',
	      'food_service_type' => $line[5],
			  'business_owner' => $line[7],
			  'num_employees' => $line[12],
			  'square_feet' => $line[13],
			  'served_per_week' => $line[14],
			  'permit_type' => $line[15],
			  'license_status' => $line[16],
			  'date_added' =>  mdate("%Y/%m/%d", time()),
			  'is_lead' => '1',
			  'is_new_lead' => '1'
	        );
      

        $id = $this->find_double($name, $address);	

				if($id === FALSE) :
					$this->db->insert('locations', $location);
					$location['type'] = 'add';
					$results[] = $location;
				else :
					$this->db->where('id', $id);
					$this->db->update('locations', $location);
					$location['type'] = 'edit';
				  $results[] = $location;
				endif;
			endif;
    
		endwhile;
		
		fclose($file_handle);
		
		return $results;
	}
	*/

  //return FALSE if not found, otherwise return location's id
  function find_double($name, $address){


        //try to extract street address for second (fuzzy) lookup
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

      $name = preg_replace('/\'/', '', $name);  

      //first try name and address
  	  $this->db->like('name' , $name );
  	  $this->db->like('dgf_address' , $address );
  	  $rs = $this->db->get("locations");
	    
      if($rs->num_rows() > 0) : 
  	     //it worked!! (unlikely)
      //  echo 'found it - ';
  	     $winner = $rs->row_array();
         return $winner['id'];
      elseif($do_second_try) :
  	      //second try

    	      $this->db->where('dgf_address REGEXP \'^' . addslashes($street_num) . ' \'');
    	      $this->db->like('dgf_address', $street_name );
	      
    	      $rs = $this->db->get('locations');

    	      if($rs->num_rows() > 0) {

    	        $name_tokens = explode(' ', $name);
              $found = $rs->row_array();
              if(stristr($found['dgf_name'], $name_tokens[0])) :
                //second try worked
            //    echo 'second time - ';
                return $found['id'];
              endif;
             }
      endif;

      //nothing found
      return FALSE;
  }

  function clean_string($string){
    return preg_replace('/\'/', '', $string);
  }
	
	
/*
  OLD find double logic-- new method refined on 512dining import
	function find_double($name, $address){
	  $this->db->where('name LIKE', $name);
	  $this->db->where("city_address LIKE '%" . $address . "%'");
	  $rs = $this->db->get("locations");
	  $row = $rs->row();
	  
	  if($rs->num_rows() == 0) :
		return FALSE;
	  else :
		return $row->id;
	  endif;
	  
	}
*/

  function import_quickbooks(){

    $filename = 'ybiofuels.csv';
 
	  $file_handle = fopen($filename, "r");
		
		//eat first line for lunch/dinner
		$first_line = fgetcsv($file_handle, 4096);

		while (!feof($file_handle) ) :

			$line = fgetcsv($file_handle, 4096);

			$name = clean_txt($line[4]);

    if(!empty($name)) :

      //build location from CSV columns
			$location = array(
	          'name' => $name,
	          'dgf_name' => clean_txt($name),
	          'dgf_address' => $line[44],
            'address2' => $line[46],
	          'city' => $line[48],
			  'state' => $line[50],
			  'zip' => $line[52],
        'bill_to1' => $line[68],
        'bill_to2' => $line[70],
        'bill_to_city' => $line[72],
        'bill_to_state' => $line[74],
        'bill_to_zip' => $line[76],
        'dgf_rep' => $line[92],
        'terms' => $line[94],
			  'contact_name' => $line[28],
			  'phone' => $line[22],
			  'date_added' =>  mdate("%Y/%m/%d", time()),
			  'notes' => $line[98],
        'is_customer' => 1,
        'email' => $line[80]
	      );
		
      var_dump($location);
      echo "<br/>";
    endif;
		/*
          if($commit_changes) $this->db->insert('locations', $location);
					$location['type'] = 'new';
					$results[] = $location;
    */
		endwhile;
		
		fclose($file_handle);

   // return $results;

  }

  
}


?>
