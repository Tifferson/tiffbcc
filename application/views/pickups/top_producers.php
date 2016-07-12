<h1>Top <?=$pickups->num_rows() ?> Producers</h1>
<form>
Show <select name="n" onchange="show_n();">
          <option value=''>...</option>
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
      </select>
</form>
<p>These locations have produced the most oil.  The Customer Start Date is considered contract start date.  If the contract start date is empty, the system tries to use the date added as customer, otherwise the first pickup date is used.</p>


<table id="stops" style="border: 2px solid black;width:100%" class='tablesorter'>
<thead>
<tr>
	<th width="50%">Stop</th>
	<th width="10%">Gallons Oil</th>
  <th width="20%">Customer Start Date</th>
  <th width="10%">Avg per Month</th>
</tr>
</thead>
<tbody>
	<?	$i = 0; 
		$total_gallons = 0;
	?>
<? foreach($pickups->result() as $pickup) : ?>
	<tr id="<?=$pickup->id?>">
  

		<td><?= $pickup->dgf_name ?> (<?=anchor('locations/edit/' . $pickup->location_id, 'edit')?>
                                  | <?=anchor('pickups/show_history/' . $pickup->location_id, 'pickup history')?>)
			<br/>
			<span class="subinfo"><?=loc_address($pickup)?>
      <br/>
      <b><?= !empty($pickup->loc_notes) ? 'Notes: ' : '' ?></b><?=$pickup->loc_notes?>
      </span>
		</td>

		<td><?=$pickup->total?></td>

	<?php if ($pickup->start_date != empty_date()) { ?>
		<td><?= date_to_mdy($pickup->start_date) ?></td>
		<td><?=round($pickup->total / $pickup->contract_months_active )?></td>
	<?php } elseif ($pickup->acq_date != empty_date()) { ?>
      		<td><?= date_to_mdy($pickup->acq_date) ?></td>
		<td><?=round($pickup->total / $pickup->system_months_active )?></td>
	<?php } elseif ($pickup->first_pickup != empty_date()) { ?>
      		<td><?= date_to_mdy($pickup->first_pickup) ?></td>
		<td><?=round($pickup->total / $pickup->pickup_months_active )?></td>
	<?php } else { ?>
       		<td style="text-align:center">&nbsp;</td>
      		<td >0</td> 
	<?php } ?>

	</tr>
<?  
  $total_gallons += $pickup->gallons;
  $i++; 
?>
<?endforeach?>


</tbody>


<!--	<tr>
		<td style="text-align:right"><b>Total Gallons:</b></td>
		<td><?=$total_gallons?></td>
    <td colspan="2">&nbsp;</td>	
  </tr>
-->
</table>


<script type="text/javascript">

  function show_n(){
      n = $("select[name=n]").val()
      if(n > 0 && n <= 100) window.location = "<?=site_url('pickups/top_producers')?>" + "/" + n 
  }
</script>


