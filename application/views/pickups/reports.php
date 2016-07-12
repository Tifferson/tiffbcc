<h1>Oil Collection Reports</h1>
<ul>
	<li><?= anchor('pickups/by_route', 'by Route', active_attr($active, 'route_history')) ?>
		<br/>Pickups on date of route
	</li>
	<li><?= anchor('pickups/location_history', 'by Location ', active_attr($active, 'loc_history')) ?> 
		<br/>All pickups for a location
	</li>
	<li><?= anchor('pickups/select_month', 'by Month', active_attr($active, 'month')) ?>
		<br/>Total Gallons collected by Month
	</li>
	<li><?= anchor('pickups/resolve', 'Need Follow Up', active_attr($active, 'missed')) ?>
		<br/>Pickups that need someone to follow up or investigate
	</li>
	<li><?= anchor('pickups/no_producers', 'Non-producers', active_attr($active, 'no_prod')) ?>
		<br/>Locations that have not produced oil in 60 days
	</li>
	<li><?= anchor('pickups/top_producers', 'Top Producers', active_attr($active, 'top')) ?>
		<br/>Locations that have produced the most oil
	</li>
</ul>
