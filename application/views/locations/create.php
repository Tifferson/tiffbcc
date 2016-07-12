<script type="text/javascript">
	$(document).ready(
	  function(){ 
	    $("tr.is_cust_opt").hide() 
	    $("input[name=container_dropoff_date]").datepicker()
	  })
	
	function toggle(){
    if($('input[name=is_customer]').attr('checked')){
    		$("tr.is_cust_opt").show()
    }
    else{
        $("tr.is_cust_opt").hide()
    }
	}

</script>
<?
	$one_day = 24*60*60;
?>

<h1>New Location</h1>
<p>Enter the information below.  After saving, you will be taken to the edit location screen
  to enter the rest of the information.</p>
<form action="<?=site_url('locations/create_new')?>" method="POST">
<table>
<tr>
<td style="width:15em">
    Name: 
</td>
<td>
    <input type="text" name="dgf_name" size="35" />  
</td>
</tr>
<tr>
<td>
    Address:
</td>
<td>
    <input type="text" name="dgf_address"  size="35" /> 
</td>
</tr>
<tr>
<td>
    City: 
</td>
<td>
    <input type="text" name="city"  size="20" />  
</td>
</tr>
<tr>
<td>
    State: 
</td>
<td>       
   <?$this->load->view("states")?>
</td>
</tr>

<tr>
<td>
    Zip: 
</td>
<td>       
    <input type="text" name="zip"  size="5" />
</td>
</tr>

<tr>
<td>
	Is this a Customer:
</td>
<td>
	<input type="checkbox" name="is_customer" onclick="toggle()" /> yes
</td>
</tr>
  <tr class="is_cust_opt">
	<td colspan='2' style="padding: 10px;border:1px solid #666;background: #ddd"> 
	    <b>Container Dropoff Details</b> - 
      (<a href="#cancel" onclick='$("tr.is_cust_opt").hide()'> hide </a> )  <br/>
	    Enter information about the containers the restaurant uses to store the oil. <br/> 
    </td>
  </tr>

  <tr class="is_cust_opt">
    <td>Container Type:</td>
    <td>
      <?= select_other('container_type', $this->Location->containers(), '')?>
    </td>
   </tr>
  <tr class="is_cust_opt">
    <td>Container Dropoff Date:</td>
    <td>
		<input type="text" name="container_dropoff_date" value="<?= date("m/d/Y", time() + 4*$one_day ) ?>" />
    </td>
   </tr>
  <tr class="is_cust_opt">
     <td>
        Number of Containers:
     </td>
     <td>
       <select name="num_containers">
          <option value="0">0</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
       </select>
     </td>
  </tr>
  
  <tr class="is_cust_opt">
    <td colspan='2'>Special Notes for Barrel Dropoff:</td>
  </tr>
  <tr class="is_cust_opt">
    <td colspan='2'>
		  <textarea name="special_notes_for_dropoff" rows="5" cols="50"></textarea>
    </td>
   </tr>
   

</table>


<input type="submit" value="Save" />

</form>
