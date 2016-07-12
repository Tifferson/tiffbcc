 <p id="subnav">
  <?= anchor('locations/', "Search Locations", active_attr($active, 'index')) ?> | 
  <?= anchor('locations/newest/20', "Newest Locations", active_attr($active, 'newest'))?> |
  <?= anchor('locations/customers', "Current Customers", active_attr($active, 'customers')) ?> | 
  <?= anchor('locations/reports', "Reports", active_attr($active, 'reports'))  ?> |
  <?= anchor('locations/create', "[+] Add New Location", active_attr($active, 'new')) ?>
 </p>
