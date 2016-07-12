<div id="main_nav">
  <p>
	<? $admin = $this->session->userdata('is_admin') == '1' ?>
	<?= anchor('Home', "Home", active_attr($active, 'home')) ?>
	<? if($admin || $roles['t'] == '1') : ?>
		<?= anchor('Work_orders', "Tasks", active_attr($active, 'tasks')) ?>
	<? endif ?>
	<? if($admin || $roles['s'] == '1') : ?>
		<?= anchor('Locations', "Sources", active_attr($active, 'sources')) ?>
	<? endif ?>
	<? if($admin || $roles['c'] == '1') : ?>
		<?= anchor('Routes', "Collection", active_attr($active, 'collection')) ?>
	<? endif ?>
	<? if(false && ($admin || $roles['p'] == '1')) : ?>
		<?= anchor('Fuel', "Production", active_attr($active, 'products')) ?>
	<? endif ?>
	<? if($admin || $roles['r'] == '1') : ?>
		<?= anchor('Reporting', "Reporting", active_attr($active, 'reporting')) ?>
	<? endif ?>
	<? /*if($admin || $roles['k'] == '1') : ?>
		<?= anchor('knowledge', "Knowledge Base", active_attr($active, 'knowledge')) ?>
	<? endif */ ?>
	<? if($admin) : ?>
		<?= anchor('Settings', "Settings", active_attr($active, 'settings')) ?>
	<? endif ?>
  </p>
</div>

<?if($active != 'home') :?>
<div id="second_nav">
	<p>
		<? if($active == 'tasks') : ?>
			<?= anchor("Calls", "Phone Calls", active_attr($second_nav, 'calls')) ?>
			<?= anchor("Work_orders", "Work Items", active_attr($second_nav, 'work_orders'))?>
			<?= anchor("Reminders/show", "Reminders", active_attr($second_nav, 'reminders'))?>
		<? elseif($active == 'sources' ) : ?>
			<?= anchor("Locations", "Locations", active_attr($second_nav, 'locations')) ?>
			<?= anchor("Leads", "Lead Management", active_attr($second_nav, 'leads')) ?>
		<? elseif($active == 'collection' ) :?>
			<?= anchor("Routes", "Route Management", active_attr($second_nav, 'routes')) ?>
			<?= anchor("Pickups", "Collection Runs", active_attr($second_nav, 'pickups')) ?>
      <?= anchor("Routes/calendar", "Calendar", active_attr($second_nav, 'calendar')) ?>
		<? elseif($active == 'products' ) : ?>
			<?= anchor("Fuel", "Fuel Management", active_attr($second_nav, 'fuel')) ?>
			<?= anchor("Sales", "Fuel Sales", active_attr($second_nav, 'sales')) ?>
		<? elseif($active == 'reporting' ) : ?>
			<?= anchor("Reporting", "Reporting", active_attr($second_nav, 'reporting')) ?>
			<?//= anchor("rins", "RIN Management", active_attr($second_nav, 'rins')) ?>
		<? elseif($active == 'settings' ) : ?>
			<?= anchor("Settings", "Configuration", active_attr($second_nav, 'config'))?>
			<?= anchor("Users", "User Manager", active_attr($second_nav, 'users'))?>
		<? endif ?> 
	</p>
</div>
<?endif?>
