<?

class User extends OC_Model{
	
	var $filter;
	
	function __construct(){
		parent::__construct();
		
		$this->table_name = "users";
		$this->default_sort = "id";
		
		$this->filter = array();
		$this->permissions = array('active', 'is_admin', 'is_employee',
						'tasks', 'sourcing', 'collection', 'production',
						'reporting', 'knowledge');
	
	}
	
	function set_filter($array_vals){
		$this->filter = $array_vals;
	}
	
	function id_to_name($id){
		$rs = $this->db->get_where('users', array('id' => $id));
		if($rs->num_rows() > 0){
			$user = $rs->row();
			return $user->name;
		}else return '';
	}
	
	function filtered($filter, $employees = true){

	  if(!empty($filter)) $this->db->or_where($filter);

    if($employees) return $this->employees();
    else {
		  $this->db->order_by("name");
      $this->db->where('active', 1);    
      return $this->db->get('users');
    }

	}

	
	function employees($active = true) {
	    $this->db->order_by("name");
		$this->db->where('is_employee', 1);
		if ($active) $this->db->where('active',1);
		return $this->db->get('users');
	}
	
	function save_permissions(){
 
		$users = $this->input->post("users");
		foreach($users as $id => $settings) :
			//clear all to zero
			$this->db->where('id', $id);
			foreach($this->permissions as $p) $this->db->set($p, 0);
			$this->db->update($this->table_name);			
	
      unset($settings['placeholder']);
      //need placeholder so id for user w. nothing checked is still in POST		
    	//set to what's one
      if(!empty($settings)) :
      $this->db->where('id', $id);
			foreach($settings as $name=>$val){
        $this->db->set($name, 1);
      }
			$this->db->update($this->table_name);
      endif;	
  	endforeach;
		
	}

  function has_email($username){
  
      $this->db->where('name', $username);
      $rs = $this->db->get($this->table_name);

      if($rs->num_rows() > 0) :
        $row = $rs->row();
        if(!empty($row->email)) return $row;
        else return false;
      else :
        return false;
      endif;

  }

	function delete(){
		$id = $this->input->post('id');
    $this->db->where('id', $id);
		$this->db->delete($this->table_name);
	}

	function save_new(){
		$user = $this->from_post();
		$this->db->insert($this->table_name, $user);	
	}

	function update(){
		$id = $this->input->post("id");
		$this->db->where(array('id' => $id));
		$this->db->update($this->table_name, $this->from_post());
	}

	function from_post(){
		$a = array(
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email')
		);
		if (@strlen($this->input->post('password'))>0) {
			$a['password'] = trim($this->input->post('password'));
		}
		return $a;
	}

  function find_dependencies(){
    $dependencies = false;
    $names = array();
    $id = $this->input->post("id");

    $this->db->select('id');
    $this->db->where('driver_id', $id);
    $rs = $this->db->get('pickups');

    if($rs->num_rows() > 0) :
      $dependencies = true;
      $names[] = 'pickups';
    endif;

    $this->db->select('id');
    $this->db->where('user_id', $id);
    $rs = $this->db->get('users_leads');
    
    if($rs->num_rows() > 0) :
      $dependencies = true;
      $names[] = 'leads';
    endif;

    $this->db->select('id');
    $this->db->where('assigned_to', $id);
    $rs = $this->db->get('work_orders');

    if($rs->num_rows() > 0) :
      $dependencies = true;
      $names[] = 'work orders';
    endif;


    //return
    if($dependencies) :
      return "User is linked to: " . join(',', $names);
    else :
      return FALSE;
    endif;

  }
	

}

?>
