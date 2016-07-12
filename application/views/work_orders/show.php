<p><img style="width:200px;" src="/dg_assets/images/dieselgreen_logo.jpg" /></p>
<h1>DieselGreen Fuels Work Item</h1>
<table style="width:100%">
  <tr> <td class="label" style="width:24%">Task:</td> <td><?=$work_order->task?></td> </tr>
  <tr> <td class="label">Due By:</td> <td><?= date($date_string ,strtotime($work_order->due_date)) ?></td></tr>
  <tr> <td class="label">Work When:</td> <td><?=$work_order->work_time ?></td></tr>
  <?if(!empty($work_order->location_name)){?>
    <tr> <td class="label">Location Info:</td> 
      <td><?=$work_order->location_name ?>
        <br/><?=$work_order->address ?>
         <br/><?= $work_order->city . ', ' . $work_order->state . " " . $work_order->zip?>
         <br/><?=$work_order->pickup_specifics ?>
         <br/><?=$work_order->container_type ?>
      </td>     
    </tr>
  <?}?>
  <tr> <td colspan="2" class="label">Comments:</td></tr>
  <tr> <td colspan="2"><?=preg_replace("/\n/", "<br/>", $work_order->comments)?></td></tr>
  <tr> <td colspan="2" class="label">Created By: <?=$this->user->id_to_name($work_order->created_by)?></td></tr>
  <tr> <td colspan="2" style="height: 100%">&nbsp;</td></tr>
  <tr> <td class="label">Signed Off:</td>
		<td style="border-bottom: 1px dashed black">
			&nbsp;
		</td>
  </tr>
  <tr> <td class="label">Print Name:</td><td style="border-bottom: 1px dashed black">&nbsp; </td></tr>
  <tr> <td class="label">Date:</td>
        <td><span style="border-bottom: 1px dashed black;width:100px;display:block;">&nbsp;</span></td></tr>
  <tr> <td class="label">Entered into DB:</td><td style="border-bottom: 1px dashed black">&nbsp; </td></tr>
  <tr> <td class="label">Date:</td>
        <td><span style="border-bottom: 1px dashed black;width:100px;display:block;">&nbsp;</span></td></tr>
  <tr> <td colspan="2" style="text-align: right;font-size:0.8em">Work Order Id # <?=$work_order->id?></td></tr>
</table>
<?= anchor("Work_orders", "Back") ?>

 <? if(  $work_order->is_open == "1") : ?>
   <script type="text/javascript">
       window.print();
   </script>
<? endif ?>