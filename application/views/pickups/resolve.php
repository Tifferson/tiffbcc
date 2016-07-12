<table id="stops" style="border: 2px solid black;width:100%" class="tablesorter">
<thead>
<tr>
	<th>Stop</th>
	<th>Route</th>
  <th>Date</th>
	<th>Gallons Oil</th>
    <? if($track_solids_and_water) : ?>
      <th style="font-size:0.8em">Gal Solids Taken</th>
      <th style="font-size:0.8em">Gal Solids Left</th>
      <th style="font-size:0.8em">Gal Water Taken</th>
      <th style="font-size:0.8em">Gal Water Left</th>
     <? endif ?>
	<th style="width:50%">Comments</th>
  <th class="{sorter: false}"> Resolve </th>
</tr>
</thead>
<tbody>
<?foreach($pickups->result() as $pickup) : ?>
	<tr id="<?=$pickup->id?>">
		<td><?=anchor("locations/edit/" . $pickup->location_id, clean_txt($pickup->dgf_name))?>
			<br/>
			<span class="subinfo"><?=loc_address($pickup)?></span>
		</td>
		<td><?=$pickup->route_name?></td>
    <td><?=mysql_to_mdy($pickup->date)?></td>
    <td><?=$pickup->gallons?></td>
    <? if($track_solids_and_water) : ?>
      <td><?=$pickup->gallons_solids_taken?></td>
      <td><?=$pickup->gallons_solids_left?></td>
      <td><?=$pickup->gallons_water_taken?></td>
      <td><?=$pickup->gallons_water_left?></td>
     <? endif ?>
    <td><?=$pickup->notes . "<br/>" . $pickup->loc_notes?>&nbsp;</td>
		<td><a href="#resolve" onclick="resolve(<?=$pickup->id?>)">Resolve</a></td>
	</tr>
<?endforeach?>
</tbody>
</table>

<script type="text/javascript">
  function resolve(pickup_id){
    if(confirm("Resolve this missed pickup?")){
      extra = prompt("Enter extra information about resolution");
      params = {
        'id':pickup_id,
        'extra':extra
      }
      $.post("<?=site_url('pickups/save_resolution')?>",params,
        function(data){ $('#' + pickup_id).remove()})

    }
  }
</script>


