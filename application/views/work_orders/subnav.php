<p id="subnav">
<?= anchor("Work_orders", "Open Work Items", active_attr($active, 'open')) ?> | 
<?= anchor("Work_orders/closed", "Closed Work Items", active_attr($active, 'closed')) ?> |
<?= anchor("Work_orders/calendar/global", "Task Calender", active_attr($active, 'month')) ?> | 
<?= anchor("Work_orders/mine", "My Open Work Items", active_attr($active, 'mine')) ?> |  
<?= anchor("Work_orders/calendar/mine", "My Task Calender", active_attr($active, 'my_month')) ?> | 
<?= anchor("Work_orders/today", "My Day", active_attr($active, 'today')) ?> |
<?= anchor("Work_orders/create/", "[+] New Work Item", active_attr($active, 'new')) ?>
</p>
