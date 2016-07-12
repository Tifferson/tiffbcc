<h1>Pickup Histories</h1>
<p>Select location to view history of all pickups</p>
<form>
<select name="location" onchange="go()">
  <option value=''>...</option>
<? foreach($locations->result() as $location) { ?>
	<option value='<?=site_url("pickups/show_history/" . $location->id)?>' >
	
		<?=clean_txt($location->dgf_name)?> @ <?=clean_txt($location->dgf_address)?>
	</option>
<? } ?>
</form>
<script type="text/javascript">
	function go(){
		href = $("select[name=location]").val()
		window.location = href
	}
</script>
