<table id="stops" style="border: 2px solid black;width:100%" class='tablesorter'>
<thead>
<tr>
	<th>Needs Follow Up</th> 
  <? if(!isset($hide_location)) : ?>
	  <th>Stop</th>
  <? endif ?>
	<? if(isset($show_date)) echo "<th>Date</th><th>Route</th>" ?>
	<th>Gallons Oil</th>
    <? if($track_solids_and_water) : ?>
      <th style="font-size:0.8em">Gal Solids Taken</th>
      <th style="font-size:0.8em">Gal Solids Left</th>
       <th style="font-size:0.8em">Gal Water Taken</th>
      <th style="font-size:0.8em">Gal Water Left</th>
     <? endif ?>
	<th style="width:50%">Comments</th>
  <th class="{sorter: false}">actions</th>
</tr>
</thead>
<tbody>
	<?	$i = 0; 
		$total_gallons = 0;

    if($track_solids_and_water) : 
      $solids_taken = 0;
      $solids_left = 0;
      $water_taken = 0;
      $water_left = 0;
    endif;
	?>
<? foreach($pickups->result() as $pickup) : ?>
	<tr id="<?=$pickup->id?>">
	  <td <?=$pickup->picked_up == '0' ? 'style="color:red" ' : 'style="color:green" '?>>
      <?=$pickup->picked_up == '0' ? 'Y' : 'N'?>
    </td>
      <? if(!isset($hide_location)) : ?>
		<td <? if (!@$pickup->is_customer) { ?>style="text-decoration: line-through;"<? } ?>><?=anchor('locations/edit/' . $pickup->location_id, $pickup->dgf_name)?>
			<br/>
			<span class="subinfo"><?=loc_address($pickup)?>
      <br/>
      <b><?= !empty($pickup->loc_notes) ? 'Notes: ' : '' ?></b><?=$pickup->loc_notes?>
      </span>
		</td>
  <? endif ?>
		<? if(isset($show_date)) : ?>
				<td><?=mysql_to_mdy($pickup->date)?></td>
				<td><?=$pickup->route_id == 0 ? "Manual Pickup" : $pickup->route_name?></td>
		<?endif;?>
		<td><?=$pickup->gallons?></td>
    <? if($track_solids_and_water) : ?>
      <td><?=$pickup->gallons_solids_taken?></td>
      <td><?=$pickup->gallons_solids_left?></td>
      <td><?=$pickup->gallons_water_taken?></td>
      <td><?=$pickup->gallons_water_left?></td>
     <? endif ?>
		<td>
				&nbsp;<?=$pickup->notes?>
    </td>
    <td>
      <a href="#delete" onclick="delete_pickup(<?=$pickup->id?>)">delete</a>
    </td>
	</tr>
	<?  $total_gallons += $pickup->gallons;
      if($track_solids_and_water) : 
        $solids_taken += $pickup->gallons_solids_taken;
        $solids_left += $pickup->gallons_solids_left;
        $water_taken += $pickup->gallons_water_taken;
        $water_left += $pickup->gallons_water_left;
     endif;

  
		$i++; ?>
<?endforeach?>
</tbody>
	<tr>
		<td style="text-align:right"
      <? if(isset($show_date)) : ?>
        colspan="4"
       <? elseif(!isset($hide_location)) : ?>
         colspan="2"
      <? endif ?>
      ><b>Total Gallons:</b></td>
		<td><?=$total_gallons?></td>
    <? if($track_solids_and_water) : ?>
      <td><?=$solids_taken?></td>
      <td><?=$solids_left?></td>
      <td><?=$water_taken?></td>
      <td><?=$water_left?></td>
     <? endif ?>
    <td>&nbsp;</td>
	</tr>
</table>

<script type="text/javascript">
  function delete_pickup(id){
    if(confirm("Delete this pickup?")) {
      $.post("<?=site_url('pickups/delete')?>",
              {'id':id}, function(data){
                    $("#" + id).remove()
              })

    }
  }
</script>
