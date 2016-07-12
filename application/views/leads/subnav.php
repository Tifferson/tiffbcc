<p id="subnav">
  <?= anchor('leads', "Leads List", active_attr($active, 'list')) ?> | 
  <?= anchor('leads/mine', "My Leads", active_attr($active, 'mine')) ?> | 
  <?= anchor('leads/follow_ups', "Follow Ups", active_attr($active, 'follow')) ?> |
  <?= anchor('leads/upload_fs', "Upload Foodservice Leads", active_attr($active, 'upload_fs'))?>
</p>
