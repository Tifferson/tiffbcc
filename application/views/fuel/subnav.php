<p id="subnav">
	<?= anchor('fuel/incoming_fuel', "[+] Enter Incoming Fuel", active_attr($active, 'incoming_fuel')) ?> | 
	<?= anchor('fuel/toll', "[+] Toll Biodiesel", active_attr($active, 'toll')) ?> |
	<?= anchor('fuel/make', "[+] Make Biodiesel", active_attr($active, 'make')) ?> |
	<?= anchor('fuel/blend', "[+] Blend Biodiesel", active_attr($active, 'blend')) ?> |
	<?= anchor("fuel/reports", "Reports", active_attr($active, 'reports') )?> |
	<?= anchor('fuel/suppliers', "Suppliers", active_attr($active, 'list_suppliers')) ?>
 </p>