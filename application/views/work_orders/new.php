<script type="text/javascript">
	$(function(){$("input[name=task]").focus()})
</script>
<h1>New Work Item</h1>
<form action="<?=site_url('Work_orders/save_new')?>" method="POST">
<input type="hidden" name="created_by" value="<?=$this->session->userdata("user_id")?>" />
<? $size = 25 ?>
<table>
 <tr> <td class="label" style="width:24%">Task:</td> 
	  <td><input size="<?=$size * 2?>" type="text" name="task"  /></td> 
 </tr>
  <tr> <td class="label">Due Date:</td>
      <td><input  size="15" type="text" class="date_pick" name="due_date" /></td>
 </tr>


 <tr>
    <td>&nbsp;</td>
    <td> do work <input type="radio" name="due_on" value="1" checked="checked"/> <b> ON </b>   
        <input type="radio" name="due_on" value="0" /> <b> BY </b> this date 
    </td>
  </tr>

 
   <tr> <td class="label">Priority:</td>
      <td>
		<select name="priority">
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
		</select>
	  </td>
 </tr>
 
  <tr> <td class="label">For:</td>
      <td>
		<?= $this->load->view('users_dropdown', array("selected" => '', "field" => 'assigned_to')) ?>
	  </td>
 </tr>
  <tr> <td class="label">Work When:</td> 
	<td><input size="<?=$size?>" type="text" name="work_time" /></td>
 </tr>

  <tr> <td class="label">Location:</td>
	<td>   
	<select name="location_id">
	   <option value="">...</option>	   
	<? foreach($loc_query->result() as $location) { ?>
		<option value="<?=$location->id?>">
			<?=ucwords(strtolower($location->dgf_name))?> @ <?=ucwords(strtolower($location->dgf_address))?>
		</option>
	<? } ?>
	</select>
	</td>
 </tr>
  <tr> <td colspan="2" class="label">Comments:</td></tr>
  <tr> <td colspan="2"><textarea rows='13' cols='65' name="comments"></textarea></td></tr>
 
</table>



<input type="submit" value="Save" />
</form>
