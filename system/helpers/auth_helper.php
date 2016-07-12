<?

function require_login(&$session){

	if($session->userdata('logged_in') == true){
		return true;
	}
	else{
		$session->set_flashdata("message_class", "warning");
		$session->set_flashdata("message", "Login Required");
		$session->set_flashdata("destination", $_SERVER['REQUEST_URI'] );
		redirect("auth");
	}
}

?>