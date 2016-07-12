<table id="stops" style="border: 2px solid black;width:100%" class='tablesorter'>
<thead>
<tr>
	<th>Stop</th>
  <? if(isset($show_gallons)) : ?>
	<th>Gallons Oil</th>
  <? endif ?>
</tr>
</thead>
<tbody>
	<?	$i = 0; 
		$total_gallons = 0;
	?>
<? foreach($pickups->result() as $pickup) : ?>
	<tr id="<?=$pickup->id?>">
  

		<td><?=anchor('locations/edit/' . $pickup->location_id, $pickup->dgf_name)?>
			<br/>
			<span class="subinfo"><?=loc_address($pickup)?>
      <br/>
      <b><?= !empty($pickup->loc_notes) ? 'Notes: ' : '' ?></b><?=$pickup->loc_notes?>
      </span>
		</td>

  <? if(isset($show_gallons)) : ?>
		<td><?=$pickup->total?></td>
  <? endif ?>

	</tr>
<?  
  $total_gallons += $pickup->gallons;
  $i++; 
?>
<?endforeach?>


</tbody>

 <? if(isset($show_gallons)) : ?>
	<tr>
		<td style="text-align:right"><b>Total Gallons:</b></td>

		<td><?=$total_gallons?></td>


	</tr>
  <? endif ?>
</table>


