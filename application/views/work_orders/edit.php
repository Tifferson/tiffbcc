<h1>Edit Work Item</h1>

<form action="<?=site_url('Work_orders/save')?>" method="POST" class="validate">
<input type="hidden" name="id" value="<?=$work_order->id?>" />

<? $size = 25 ?>
<table>
 <tr> <td class="label" style="width:24%">Task:</td> 
	  <td><input size="<?=$size * 2?>" type="text" name="task" value="<?=$work_order->task?>" /></td> 
 </tr>
  <tr> <td class="label">Due Date:</td>
      <td><input size="15" class="date_pick" type="text" name="due_date" value="<?= mysql_to_mdy($work_order->due_date) ?>" /></td>
 </tr>  

 <tr>
    <td>&nbsp;</td>
    <td> do work <input type="radio" name="due_on" value="1" 
            <?= $work_order->due_on == '1' ?  'checked="checked"' : '' ?>/> <b> ON </b>   
        <input type="radio" name="due_on" value="0" 
            <?= $work_order->due_on == '0' ?  'checked="checked"' : '' ?>/> <b> BY </b> this date 
    </td>
  </tr>

 <tr> <td class="label">Priority:</td>
      <td>
		<select name="priority">
			<option <?= $work_order->priority == 0 ? 'selected="selected"' : ''?> >0</option>
			<option <?= $work_order->priority == 1 ? 'selected="selected"' : ''?> >1</option>
			<option <?= $work_order->priority == 2 ? 'selected="selected"' : ''?> >2</option>
			<option <?= $work_order->priority == 3 ? 'selected="selected"' : ''?> >3</option>
			<option <?= $work_order->priority == 4 ? 'selected="selected"' : ''?> >4</option>
		</select>
	  </td>
 </tr>
  <tr> <td class="label">For:</td>
      <td>
		<?= $this->load->view('users_dropdown', array("selected" => $work_order->assigned_to, "field" => 'assigned_to')) ?>
	  </td>
 </tr>
  <tr> <td class="label">Work When:</td> 
		<td><input size="<?=$size?>" type="text" name="work_time" value="<?=$work_order->work_time ?>" /></td>
 </tr>
 <tr> <td class="label">Status:</td>
      <td>
		<?= $this->load->view('work_orders/status_dropdown', array("selected_status" => $work_order->status)) ?>
	  </td>
 </tr>

  <tr> <td class="label">Location:</td>
	<td>   
	<select name="location_id">
	   <option value="">...</option>
<!--	 
	 <option value="dgf_office">DieselGreen Office</option>
	   <option value="dgf_shop">DieselGreen Office</option>
	   
		<!-- TODO include suppliers -- >
	   <option value="sa">San Antonio</option> -->
	   
	<? foreach($loc_query->result() as $location) { ?>
		<option value="<?=$location->id?>" 
		<?=($work_order->location_id == $location->id) ? 'selected="selected"' : ''?>
		 ><?=ucwords(strtolower($location->dgf_name))?> @ <?=ucwords(strtolower($location->dgf_address))?></option>
	<? } ?>
	</select>
	</td>
 </tr>
  <tr> <td colspan="2" class="label">Comments:</td></tr>
  <tr> <td colspan="2"><textarea rows='13' cols='65' name="comments"><?=$work_order->comments?></textarea></td></tr>
	<tr> <td class="label">Created By:</td><td> <?=$this->User->id_to_name($work_order->created_by)?></td></tr>

 <? if($work_order->is_open == '0') :?>
   <tr> <td class="label">Signed Off By:</td>
       <td><input size="<?=$size?>" type="text" name="signed_off_by" value="<?=$work_order->signed_off_by?>" /></td>
  </tr>
   <tr> <td class="label">Date Finished:</td>
       <td><input class="date_pick" size="15" type="text" name="date_finished" value="<?=mysql_to_mdy($work_order->date_finished)?>" /> (MM/DD/YY)</td>
  <tr> <td colspan="2" class="label">Closing Comments:</td></tr>
  <tr> <td colspan="2"><textarea rows='13' cols='65' name="closing_comments"><?=$work_order->closing_comments?></textarea></td></tr>
 <? endif ?>
</table>



<input type="submit" value="Save" />
</form>


  <form action="<?=site_url("Work_orders/delete")?>" method="POST">
	 <input type="hidden" name="id" value="<?=$work_order->id?>" />
	 <input type="submit" value="Delete" />
	</form> <br/>
	<a href="<?=site_url("Work_orders")?>">Back</a>
