<select name="route_id">
	<option value="">...</option>
<?foreach($routes->result() as $route) : ?>
	<option value="<?=$route->id?>" 
	<?= $active == $route->id ? "selected='selected'" : '' ?>
	><?=$route->name?></option>
<?endforeach?>
</select>