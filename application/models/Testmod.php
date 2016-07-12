<?php
class Testmod extends OC_Model{
     

    function __construct(){
        parent::__construct();
    }

    function get_tiffany(){
        $this->db->select("tiffany.*");
        return $this->db->get('tiffany');
    }
   
}
?>
