<p id="subnav">
<?= anchor('Calls/mine', 'My Calls', active_attr($active, 'mine')) ?> | 
<?= anchor('Calls', 'New Calls', active_attr($active, 'index')) ?> | 
<?= anchor('Calls/answered', 'Answered Calls', active_attr($active, 'answered')) ?> | 
<?= anchor('Calls/create', '[+] New Call', active_attr($active, 'create')) ?>
</p>