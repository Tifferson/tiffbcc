<p id="subnav">
<?= anchor("routes", "Active Routes", active_attr($active, 'list')) ?> | 
<?= anchor("routes/deactivated", "Inactive Routes", active_attr($active, 'inactive') )?> |
<?= anchor("routes/create", "[+] New Route", active_attr($active, 'new')) ?>
</p>
