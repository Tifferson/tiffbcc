<h1>Close Work Order</h1>

<form action="<?=site_url('Work_orders/save')?>" method="POST" class="validate">
<input type="hidden" name="id" value="<?=$work_order->id?>" />
<input type="hidden" name="is_open" value="0" />
<? $size = 25 ?>
<table>
 <tr> <td class="label" style="width:24%">Task:</td> 
	  <td><?=$work_order->task?></td> 
 </tr>
  <tr> <td class="label">Signed Off By:</td>
      <td><input size="<?=$size?>" type="text" 
			name="signed_off_by" value="<?=$work_order->signed_off_by?>" class="required" /></td>
 </tr>
  <tr> <td class="label">Date Finished:</td>
      <td><input class="date_pick" size="15" id="date_finished" type="text" name="date_finished"  class="required"
          value="<?=mysql_to_mdy($work_order->date_finished)?>" /></td>
 </tr>
 
  <tr> <td colspan="2" class="label">Comments:</td></tr>
  <tr> <td colspan="2"><?=preg_replace("/\n/", '<br/>', $work_order->comments)?></td></tr>
 
  <tr> <td colspan="2" class="label">Closing Comments:</td></tr>
  <tr> <td colspan="2"><textarea rows='15' cols='50' name="closing_comments"><?=$work_order->closing_comments?></textarea></td></tr>
</table>

<input type="submit" value="Save" />
</form>
