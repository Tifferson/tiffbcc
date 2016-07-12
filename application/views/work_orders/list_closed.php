<table style="width:100%" class="tablesorter">
<thead>
	<tr>
	  <th>Date Finished</th>
	  <th>Date Due</th>
		<th>For</th>
		<th style="width:60%">Task</th>
		<th class="{sorter:false}">Actions</th>
	</tr>
</thead>
<tbody>
	<? $i = 0; ?>
  <? foreach($work_orders->result() as $wo ) :?>

  
	<tr class="<?= $i % 2 == 1 ? "row" : "row_alt" ?>">

		<td style="text-align:left">
      <?= mysql_to_mdy($wo->date_finished) ?>
		</td>
	<!-- date finished -->
  	<td style="text-align:center">
  		<?= mysql_to_mdy($wo->due_date)?>
  	</td>
  	
	  <td><?= $this->user->id_to_name($wo->assigned_to) ?> </td>
		<td><a href="#null" onclick="$('#<?=$wo->id?>_subinfo').toggle()"><?= $wo->task ?></a>
		<br/>
		<span id="<?=$wo->id?>_subinfo" class="subinfo" style="display:none">
		  <? $comment = $wo->comments . "<br/>" . $wo->closing_comments ?>
			<?= substr($comment, 0, 200) ?>
			<?= strlen($comment) > 200 ? "..." : '' ?>
				<br/>Signed off by: <?=$wo->signed_off_by?>
		</span>
		</td>


		<td style="text-align:center">
		  <a href="<?=site_url("Work_orders/edit/" . $wo->id)?>"> edit/view </a> | 
		  <a href="<?=site_url("/Work_orders/reopen/" . $wo->id) ?>"> reopen </a> 
		</td>
		
	
		<? $i++ ?>
  <? endforeach ?>
 </tbody>
</table>
