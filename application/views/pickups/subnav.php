<p id="subnav">
	<?= anchor('pickups/enter_routes', '[+] Enter Route Sheet', active_attr($active, 'enter_route')) ?> | 
	<?= anchor('pickups/add', '[+] Enter Manual Pickup', active_attr($active, 'manual')) ?> |
	<?= anchor('pickups/reports', "Reports", active_attr($active, 'reports')) ?>
</p>