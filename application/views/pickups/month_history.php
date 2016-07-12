<h1>Pickup History by Month</h1>

<ul>
<?foreach($dates->result() as $date) : ?>
	<li>
		<? $href = "pickups/by_month/" . $date->month . "/" . $date->year ?>
		<? $link = $date->monthname . " " . $date->year ?>
		<?= anchor($href, $link) ?>
	</li>
<?endforeach;?>
</ul>