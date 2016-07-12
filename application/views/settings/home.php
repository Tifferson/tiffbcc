<h1>Settings</h1>
<? if(!empty($s)) : ?>
<form <?=form_action("settings/update_settings")?>>
<input type="hidden" name="id" value="<?=$s->id?>" />
<table>
	<tr>
		<td>Company Name:</td>
		<td><input  name="company_name"
					size="30" type="text" value="<?=$s->company_name?>" /> </td>
	</tr>
	
	<tr>
		<td>Address line 1:</td>
		<td><input name="address1" 
				type="text" value="<?=$s->address1?>" /> </td>
	</tr>
	
	<tr>
		<td>Address line 2:</td>
		<td><input  name="address2"  
		type="text" value="<?=$s->address2?>" /> </td>
	</tr>
	
	<tr>
		<td>City:</td>
		<td><input name="city" type="text" value="<?=$s->city?>" /> </td>
	</tr>
	
	<tr>
		<td>State:</td>
		<td><input name="state" size="2" type="text" value="<?=$s->state?>" /> </td>
	</tr>
	
	<tr>
		<td>Zip Code:</td>
		<td><input name='zip' size="10" type="text" value="<?=$s->zip?>" /> </td>
	</tr>

  <tr>
		<td style="text-align:right">
      <input name="track_solids_and_water"
				type="checkbox" <?=$s->track_solids_and_water == '1' ? 'checked="checked"' : ''?> /></td>
		<td>Track Solids and Water on Collection</td>
	</tr>
	<? /*
	<tr>
		<td>Google Search API:</td>
		<td><input name="google_api_key"
				type="text" size="128" value="<?=$s->google_api_key?>" /> </td>
	</tr>
  */ ?>

</table>
<div class="buttons">
	<button type="submit" class="positive" >Save Settings</button>
</div>
</form>
<? endif ?>
