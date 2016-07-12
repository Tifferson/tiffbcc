
<? function m($string){
	return ucwords(strtolower($string));
}
?>
<h1><?= m($loc->dgf_name) ?></h1>
<table style="width:100%">
<tr><td style="vertical-align:top">
<form id="edit_form" action="<?=site_url("locations/save_location")?>" method="POST">
<input type="hidden" name="id" value="<?= $loc->id ?>" />

<table>
<tr>
<td>
    Name: 
</td>
<td>
    <input type="text" name="dgf_name" value="<?= m($loc->dgf_name) ?>" size="35" />  
</td>
</tr>
<tr>
<td>
    Address:
</td>
<td>
    <input type="text" name="dgf_address" value="<?= m($loc->dgf_address) ?>" size="35" /> 
</td>
</tr>
<tr>
<td>
    City: 
</td>
<td>
    <input type="text" name="city" value="<?= m($loc->city) ?>" size="20" />  
</td>
</tr>
<tr>
<td>
    Zip: 
</td>
<td>       
    <input type="text" name="zip" value="<?= $loc->zip ?>" size="5" />
</td>
</tr>
<tr>
<td>
    Phone: 
</td>
<td>  
    <input type="text" name="phone" value="<?= $loc->phone ?>" size="15" />
</td>
</tr>
<tr>
<td>
	<a href="<?= $loc->website ?>">Website:</a>
</td>
<td>
	<input type="text" name="website" size="35" value="<?= $loc->website ?>" />
</td>
</tr>
</table>
<a target="_blank" href="http://maps.google.com?q=<?= urlencode($loc->dgf_address . ", " . $loc->city . ", TX " . $loc->zip)?>">See on map</a>
<br/>
<br/>
    Notes: <br/>
    <textarea name="notes" rows="3" cols="40"><?= trim($loc->notes) ?></textarea>
	 <br/>
	 <input type="submit" name="submit" value="Save" style="margin:5px;"/> 
	 <a style="margin:5px;" href="<?=site_url('leads')?>">Back</a> or 
     <input type="submit" name="submit" value="Delete" onclick="return confirm('Delete this location?')"  style="margin:5px;" /> 
</form>

</td><td style="width:100%; vertical-align:top">

		<h1>Events</h1>
		<ul id="events">
			<?if($events->num_rows() > 0):?>
				<? $i = 0; ?>
				<?foreach($events->result() as $event) : ?>
					<li <?= $i % 2 == 0 ? 'class="row_alt"' : '' ?> > 
						<?= $event->dgf_rep ?> <?= mdate("%m/%d/%y", human_to_unix($event->when)) ?>: 
						<a href="#event_<?=$event->id?>" onclick="$('#event_<?=$event->id?>').toggle()">
						<?= $event->description ?>
						</a>
						<p id="event_<?=$event->id?>" style="display: none">
							<?=$event->notes?>
						</p>
					</li>
				<?endforeach?>
			<?endif?>
		</ul>
		
		<form action="<?=site_url("leads/events/" . $loc->id)?>" method="POST">
			<fieldset><legend>Add New Event</legend>
				<input type="hidden" name="save_event" value="1" />
				<input type="hidden" name="location_id" value="<?=$loc->id?>" />
				
				Rep: <input type="text" name="dgf_rep" /> <br/>
				Description: <br/> <input type="text" name="description" size="45" />
				<br/>
				Notes:<br/>
				<textarea name="notes" rows='2' cols='40'></textarea>
				<br/>
				<input type="submit" value="Save Event" />
			</fieldset>
		</form>
		
</td></tr>
</table>
