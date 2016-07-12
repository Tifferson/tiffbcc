<h1>Used Fryer Oil Pickup</h1>

<form <?= form_action("pickups/create") ?> >
<table>
  <tr> <td class="label">Location:</td>
   	<td>   
     	<select name="location_id" >
     	   <option value="">...</option>
      	<? foreach($locations->result() as $location) { ?>
     		<option value="<?=$location->id?>" >
     		<?=clean_txt($location->dgf_name)?> @ <?=clean_txt($location->dgf_address)?>
     		</option>
     	<? } ?>
     	</select>
   	</td>
  </tr>
  
	<tr><td>Date:</td>
		<td><input class="date_pick" type="text" name="when" id="when"   /></td>
	</tr>
	<tr><td>Driver:</td>
	  	<td><?$this->load->view('users_dropdown', array('field' => 'driver_id'))?></td>
  	</tr>
	<tr><td>Est. Gallons Oil:</td>
		<td><input type="text" name="gallons"  /> gal</td>
	</tr>

  <? if($track_solids_and_water) : ?>
	<tr><td>Gallons Solids Taken:</td>
		<td><input type="text" name="gallons_solids_taken" /> gal</td>
	</tr>
	<tr><td>Gallons Solids Left:</td>
		<td><input type="text" name="gallons_solids_left" /> gal</td>
	</tr>
	<tr><td>Gallons Water Taken:</td>
		<td><input type="text" name="gallons_water_taken" /> gal</td>
	</tr>
	<tr><td>Gallons Water Left:</td>
		<td><input type="text" name="gallons_water_left" /> gal</td>
	</tr>

  <? endif ?>
 
	<tr><td colspan="2">Notes:<br/>
		<textarea name="notes" rows="3" cols="60"  ></textarea></td>
	</tr>
  <tr><td colspan="2"> <input type="checkbox" name="problems" /> Needs Follow Up </td> </tr>
</table>
<input type="hidden" name="track_solids_and_water" value="<?=$track_solids_and_water ? 1 : 0 ?>" />
<input type="submit" name="submit_and" value="Save and add more"  /> <br/>
<input type="submit" name="submit" value="Save"  /> 
</form>

<script type="text/javascript">
    $( function(){
          $("select[name=location_id]").focus()
      })
</script>
