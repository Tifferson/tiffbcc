
<select name="status">
	<option value="">...</option>
	<? foreach($status_info as $id=>$status) : ?>
		<option 
		<?= isset($selected_status) && $selected_status == $id ? 'selected="selected"' : '' ?>
		value="<?=$id?>"><?=$status['human']?></option>
	<? endforeach ?>
</select>