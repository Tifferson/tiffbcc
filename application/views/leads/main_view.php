<script type="text/javascript">

</script>
<div id="filters">
<h1>Filters</h1>	  
	<form action="" method="POST">

		Date Added:
		<input type="radio" name="date_added" value="ASC"/> &uarr; 
		<input type="radio" name="date_added" value="DESC"/> &darr;
		<b>|</b>
		Size: 
		<input type="radio" name="size" value="ASC"/> &uarr; 
		<input type="radio" name="size" value="DESC"/>	&darr;

		<input type="submit" value="Sort" />
	</form>
</div>

<div >
	<?=  $this->load->view('leads/list_locations', $locations) ?>
</div>

