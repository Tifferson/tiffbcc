<h1>Open Work Items</h1>
	<form id="filters">
		<b>Show Priority: </b>
	 <?//	0 <input type="checkbox" < ?  = ($priorities && $priorities[0])  ? 'checked="checked"' : '' ? > class="filter" name="priority_0" /> |  ?>
		1 <input type="checkbox" <?= ($priorities && $priorities[1])  ? 'checked="checked"' : '' ?> class="filter" name="priority_1" /> | 
		2 <input type="checkbox" <?= ($priorities && $priorities[2])  ? 'checked="checked"' : '' ?> class="filter" name="priority_2" /> |
		3 <input type="checkbox" <?= ($priorities && $priorities[3])  ? 'checked="checked"' : '' ?> class="filter" name="priority_3" /> |
		4 <input type="checkbox" <?= ($priorities && $priorities[4])  ? 'checked="checked"' : '' ?> class="filter" name="priority_4" /> |
		All <input type="checkbox" <?= $priorities['all'] || (!$priorities[1] 
										&& !$priorities[2]
										&& !$priorities[3]
										&& !$priorities[4] )  ? 'checked="checked"' : '' ?>  name="all" />
	</form>
	
<script type="text/javascript">
	$(function(){ 
		$("input[class=filter]").click(filter_priority) 
		$("input[name=all]").click(all)
		if($("input[name=all]").attr('checked') == false) filter_priority()
	})
	
	function filter_priority(){
		i = 0;
		$("input[class=filter]").each( 
			function(){
				if(this.checked){
					$("." + this.name).show()
					i++
				}
				else $("." + this.name).hide()
			})
		if(i < 4) $("input[name=all]").attr('checked', false)
		else $("input[name=all]").attr('checked', true)
		$.post("<?=site_url("Work_orders/cache_priority")?>", $("#filters").serialize())
	}
	
	function all(){
		check_them = this.checked
		$("input[class=filter]").each(
			function(){ this.checked = check_them }
			)
		filter_priority()
	}
</script>

<table class="tablesorter" style="width:100%;max_width:1000px;">
<thead>
	<tr>
		<th>Due</th>
		<th style="font-size:0.7em">Priority</th>
		<th style="font-size:0.7em">Days Left</th>
		<th style="font-size:0.7em">Printed</th>
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
		<?	if($status_info[$wo->status]['abbrev'] == 'H') : ?>
      (on hold)
		<?	elseif($status_info[$wo->status]['abbrev'] == 'IP') : ?>
      (in progress)
		<?	else : ?> 
      
			<b><?= $wo->due_on == '1' ? 'on' : 'by' ?></b>
      <?= date($date_string , strtotime($wo->due_date)) ?>
		<? endif ?>
	</td>
	
	<!-- priority -->
	<td style="text-align:center">
		<b><?= $wo->priority > 0 ?  $wo->priority : '' ?></b>
	</td>
	
	<!-- days left -->
		<td style="text-align:center">
		<?	if($status_info[$wo->status]['abbrev'] == 'H') : ?>
      -
		<?	elseif($status_info[$wo->status]['abbrev'] == 'IP') : ?>
      -  
		<?	else : ?> 
			<?= colorize($days_due, $color)?>
		<? endif ?>
			
		</td>
		
		<!-- printed -->
 	 <td style="text-align:center">
    <? if($status_info[$wo->status]['abbrev'] == 'H') : ?>
      -
		<?	elseif($status_info[$wo->status]['abbrev'] == 'IP') : ?>
      -
		<?	else : 
				if($wo->has_been_printed == '1') :
					echo colorize("Y", "green");
				else : 
					$color = $wo->status > 0 ? "#666" : "red";
					echo colorize("N", $color);
				endif;
		 endif ?>
	
		</td>
		<!-- for -->
	  <td><?= $this->User->id_to_name($wo->assigned_to) ?> </td>
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
		  <a href="<?=site_url('Work_orders/edit') . "/" . $wo->id?>"> edit/view </a> | 
      <a href="<?=site_url('Work_orders/printable') . "/" . $wo->id?>"> print </a> | 
      <a href="<?=site_url('Work_orders/finish/' . $wo->id)?>"> close </a> 
		</td>
		
		<? $i++ ?>
  <? endforeach ?>
 </tbody>
</table>
