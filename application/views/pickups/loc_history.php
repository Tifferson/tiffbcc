<h1><?=anchor("locations/edit/" . $location->id, $location->dgf_name)?></h1>
<p style="padding:5px;margin:#666;background:#eee">
<?=$location->dgf_address?><br/>
<?=$location->city?>, <?=$location->state?>  <?=$location->zip?><br/>
<?if(!empty($location->notes)) : ?>
	<b>Location Notes:</b> <?$location->notes?>
<?endif?>
</p>
<? $this->load->view('pickups/pickup_table', array('hide_location' => true, 'show_date' => true)) ?>
<? /*
<table style="border:1px solid #666; width: 100%">
	<tr>
		<th >Date</th>
		<th> Route</th>
		<th >Estimated Gallons</th>
		<th style="width:50%">Pickup Notes</th>
	</tr>	
	<?	$total_gallons = 0;	?>
<? foreach($pickups->result() as $pickup) : ?>
	<tr class="<?=alternator('row' , 'row_alt')?>">
		<td><?=mysql_to_mdy($pickup->date)?></td>
			<td><?=$pickup->route_id == 0 ? "Manual Pickup" : $pickup->route_name?></td>
		<td style="text-align:right">
			<?=$pickup->gallons?>	
		</td>
		<td><?=$pickup->notes?></td>
	</tr>
	<?  $total_gallons += $pickup->gallons; ?>
<? endforeach ?>
	<tr>
		<td></td>
		<td style="text-align:right;"><b>Total Gallons:</b></td>
		<td style="text-align:left;border-top:2px solid black;"><?=$total_gallons?></td>
		<td></td>
	</tr>
</table>
*/ ?>
