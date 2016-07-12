<?
function current_page() {
 $pageURL = 'http://';
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function back_or($default){
  $ci = &get_instance();
  $p = $ci->session->userdata('previous_url');  

  if($p) return $p;
  else return $default;
}


function loc_address($loc){
	$state = @empty($loc->state)? "TX" : $loc->state;
	return clean_txt($loc->dgf_address) . ", " . clean_txt($loc->city) . ", " . $state . " " . $loc->zip;
}

function clean_txt($string){
	return trim(ucwords(strtolower($string)));
}
function consume(&$post, $field){
if(isset($post[$field])){
	$return = $post[$field];
	unset($post[$field]);
	return $return;
}else return false;
}

function make_url($url){
	$http = stristr($url, "http://") ? '' : "http://";
	$www = stristr($url, "www.") ? '' : "www.";

	return $http . $www . $url;
}

function colorize($string, $color){
return <<<HTML
  <span style="font-weight:bold;color:$color">
		 $string 
	</span>
HTML;
}

function form_action($action){
	return 'action="' . site_url($action) . '" method="POST" ';
}

function active_attr($active_page, $this_page){
	if($active_page == $this_page)
		return array('class' => 'active');
	else return array();
}

function num_to_month($n)
{
    $timestamp = mktime(0, 0, 0, $n, 1, 2005);
    
    return date("M", $timestamp);
}

//print a number with certain precision, basically wrap
function p_num($number, $precision = 2){
	if($number == 0) return '';
	else return sprintf("%." . $precision . "F", $number);
}

//prints a select box that 
function select_other($field_name, $options, $selected = false){
	
	
	$html = "<select name='$field_name' onchange=\"other(this)\">
			<option value=''>...</option>";
	foreach($options as $value => $choice) :
		$html .= "<option ";
    if( $selected && ($selected == $value || stristr($value, $selected)) ) :
		  $html .=  ' selected="selected" '  ;
    endif;		
    $html .= " value=\"$value\"> $choice </option>";
	endforeach;
	$html .= " <option value=\"other\">other</option> </select>";	
	$html .= "<input type=\"text\" style=\"display:none\" disabled=\"true\" name=\"$field_name\" />";

	return $html;
}

//prints a select box that 
function select($field_name, $options, $selected = false){
	
	
	$html = "<select name='$field_name'>
			<option value=''>...</option>";
	foreach($options as $value => $choice) :
		$html .= "<option ";
		$html .= ($selected == $value) ? ' selected="selected" ' : '' ;
		$html .= " value=\"$value\"> $choice </option>";
	endforeach;
	$html .= "</select>";	

	return $html;
}

$tabindex = 1;
function start_tabs(){
	global $tabindex;
	$tabindex = 1;
	return "tabindex=\"$tabindex\"";
}

function next_tab(){
	global $tabindex;
	$tabindex++;
	return "tabindex=\"$tabindex\"";
}

//global get setting function

function get_setting($name){

  $ci =& get_instance(); 

  $ci->db->select($name);
  $rs = $ci->db->get('settings');
  
  if($rs->num_rows() == 0) :
      return false;
  else : 
    $row = $rs->row_array();
    return $row[$name];
  endif;
}


//end of misc_helper.php

