<h1>Work Items for <?=$location->dgf_name?></h1>

<? if($work_orders->num_rows() > 0) : ?>
<table class="tablesorter" style="width:100%;max_width:1000px;">
<thead>
	<tr>
		<th >Due</th>
	  <th>Date Finished</th>
		<th style="font-size:0.7em">Priority</th>
		<th style="font-size:0.7em">For</th>
		<th style="font-size:0.7em">Status</th>
		<th style="width: 45%">Task</th>
		<th class="{sorter:false}">Actions</th>
	</tr>
</thead>
<tbody>
	<? $i = 0; ?>
  <? foreach($work_orders->result() as $wo ) :?>
	<? $days_due = days_from_today($wo->due_date);
		if($days_due < 0) $color = "red";
		elseif($days_due == 0) $color = "yellow";
		else $color = 'green';
		
		if($status_info[$wo->status]['abbrev'] == 'IP') $color = "green";
		if($status_info[$wo->status]['abbrev'] == 'H') $color = "#666";
	?>
  
	<tr class="<?= $i % 2 == 1 ? "row" : "row_alt" ?> priority_<?=$wo->priority?>" style="padding:1px">

	
	<!-- due date -->
	<td style="text-align:center">
		<?	if($status_info[$wo->status]['abbrev'] != 'H') : ?>
			<b><?= mysql_to_mdy($wo->due_date) ?></b>
		<?	else : ?> 
			(on hold)
		<? endif ?>
	</td>

  <!-- date finished -->
		<td style="text-align:left">
      <?= mysql_to_mdy($wo->date_finished, false) ?>
		</td>
	
	<!-- priority -->
	<td style="text-align:center">
		<b><?= $wo->priority > 0 ?  $wo->priority : '' ?></b>
	</td>
	
		

		<!-- for -->
	  <td><?= $this->user->id_to_name($wo->assigned_to) ?> </td>
		<!-- status -->
	  <td style="text-align:center"><?= $status_info[$wo->status]['abbrev'] ?> </td>
		<!-- task w. collapse effect -->
		<td><a href="#null" onclick="$('#<?=$wo->id?>_subinfo').toggle()"><?= $wo->task ?></a>
		<br/>
		<span id="<?=$wo->id?>_subinfo" class="subinfo" style="display:none">
		  <? $comment = $wo->comments  ?>
			<?= substr($comment, 0, 200) ?>
			<?= strlen($comment) > 200 ? "..." : '' ?>
		</span>
		</td>

		<td style="text-align:center">
		  <a href="<?=site_url('Work_orders/edit') . "/" . $wo->id?>"> edit </a> | 
      <a href="<?=site_url('Work_orders/printable') . "/" . $wo->id?>"> print </a> | 
      <a href="<?=site_url('Work_orders/finish/' . $wo->id)?>"> close </a> 
		</td>
		
		<? $i++ ?>
  <? endforeach ?>
 </tbody>
</table>
<? else: ?>
  <p>No work items for this location.</p>
<? endif ?>
