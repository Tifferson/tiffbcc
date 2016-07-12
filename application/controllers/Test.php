<?php
class Test extends CI_Controller{
    
    
    function __construct(){
        
        parent::__construct();
       $this->load->model('Testmod');
    }
    function index(){
     print $this->Testmod->get_Tiffany();
    }
}
?>