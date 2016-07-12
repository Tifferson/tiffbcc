<h1><?=$route->name?></h1>

<form <?=form_action("pickups/save_route")?> class="validate">
<input type="hidden" name="route_id" value="<?=$route->id?>" />
<input type="hidden" name="track_solids_and_water" value="<?= $track_solids_and_water ? 1 : 0?>" />
<table>
	<tr><td>Date of Route:</td>
	  	<td><input  type="text" name="date" class="date_pick required" value="<?=date_to_mdy($route->next_date)?>"/></td>
  </tr>
   
<tr><td>Driver:</td>
  	<td><?=$this->load->view('users_dropdown', array('field' => 'driver_id'))?></td>
</tr>
</table>

<!-- begin input table -->
<table id="stops" style="border: 2px solid black;width:100%">
<tr>
	<th></th>
	<th>Stop</th>
	<th>Comments</th>
	<th>Gallons Oil</th>
  <? // extra info for solids and water ?>
  <? if($track_solids_and_water) : ?>
      <th style="font-size:0.8em">Gal Solids Taken</th>
      <th style="font-size:0.8em">Gal Solids Left</th>
      <th style="font-size:0.8em">Gal Water Taken</th>
      <th style="font-size:0.8em">Gal Water Left</th>
  <? endif ?>
	<th style="font-size:0.7em">Needs Follow Up</th>
</tr>
<? $i = 1 ?>
<? foreach($stops->result() as $stop) : ?>
	<tr id="<?=$stop->id?>">
		<td><?= $i ?></td>
		<td><?=clean_txt($stop->dgf_name)?>
			<br/>
			<span class="subinfo"><?=loc_address($stop)?></span>
		</td>
		<td><textarea name="pickups[<?=$stop->id?>][comments]"></textarea></td>
			<td style="text-align: center">
       <input size="4" onkeyup="update_total()" type="text" name="pickups[<?=$stop->id?>][gallons]" /></td>
    
    <? // extra info for solids and water ?>
    <? if($track_solids_and_water) : ?>

      <td style="text-align: center">
        <input size="4" onkeyup="update_total()" type="text" name="pickups[<?=$stop->id?>][gallons_solids_taken]" /></td>
      <td style="text-align: center">
        <input size="4" onkeyup="update_total()" type="text" name="pickups[<?=$stop->id?>][gallons_solids_left]" /></td>
      <td style="text-align: center">
        <input size="4" onkeyup="update_total()" type="text" name="pickups[<?=$stop->id?>][gallons_water_taken]" /></td>
      <td style="text-align: center">
        <input size="4" onkeyup="update_total()" type="text" name="pickups[<?=$stop->id?>][gallons_water_left]" /></td>
    <? endif ?>
		<td style="text-align: center"><input type="checkbox" name="pickups[<?=$stop->id?>][picked_up]" /></td>
	</tr>
<? $i++ ?>
<?endforeach?>
	<tr>
		<td colspan="3" style="text-align:right">Total:</td>
		<td id="total_gal">0</td>
      <? if($track_solids_and_water) : ?>
          <td id="solids_taken">0</td>
          <td id="solids_left">0</td>
          <td id="water_taken">0</td>
          <td id="water_left">0</td>
      <? endif ?>
		<td>&nbsp; </td>
	</tr>
</table>
<!-- end input table -->

<p style="text-align:right">
  <input type="submit" value="Save Route" />
</p>

</form>

<script type="text/javascript">

  function update_total(){
	  total_gallons = 0
    solids_taken = 0
    solids_left = 0
    water_taken = 0
    water_left = 0

	  $("input[name*=\[gallons\]]").each( function(i) { 
       if(this.value != "") total_gallons += parseInt(this.value) 
    } )
    $("input[name*=gallons_solids_taken]").each( function(i) { 
          if(this.value != "") solids_taken += parseInt(this.value) 
    } )
    $("input[name*=gallons_solids_left]").each( function(i) { 
          if(this.value != "") solids_left += parseInt(this.value) 
    } )
    $("input[name*=gallons_water_taken]").each( function(i) { 
          if(this.value != "") water_taken += parseInt(this.value) 
    } )
    $("input[name*=gallons_water_left]").each( function(i) { 
          if(this.value != "") water_left += parseInt(this.value) 
    } )
  	$("#total_gal").text(total_gallons)
    $("#solids_taken").text(solids_taken)
    $("#solids_left").text(solids_left)	
    $("#water_taken").text(water_taken)
    $("#water_left").text(water_left)

  }

</script>
