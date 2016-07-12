<h1>Reports for Locations</h1>
<ul>
	<li>
		<?= anchor('locations/customers', "Current Customers", 	active_attr($active, 'customers') )?> 
		<br/>All of the current customers
	</li>
	<li>
		<?= anchor('locations/recents', "Recently Edited", 	active_attr($active, 'recent') )?> 
		<br/>Locations who's information has been recently modified
	</li>
	<li>
		<?= anchor('reporting/acquire', "Customer Acquisition Graph", active_attr($active, 'customers') )?> 
		<br/>Graph of customers acquired by month
	</li>
	<li>
		<?= anchor('locations/recent_sales', "Last 200 Contacted", 	active_attr($active, 'recent_sales') )?>
		<br/>Locations that have recently had events added to them
	</li>
	<? /*<li>
		<?= anchor('locations/pending', "Pending Verification", active_attr($active, 'pending') )?>
		<br/>Locations that have become customers but are not actively producing quality oil yet
	</li>
  */ ?>
	<li><?= anchor('locations/under_contract', "Under Contract", active_attr($active, 'contract') )?>
		<br/>Locations that have signed a service agreement
	</li>
	<li><?= anchor('locations/contract_ending', "Contracts Expiring or Expired" )?>
		<br/>Locations with a service agreementh expiring in 2 months or already expired
	</li>
	<li><?= anchor('locations/quality', "Quality", active_attr($active, 'quality') )?>
		<br/>Quality of oil at locations
	</li>
</ul>
