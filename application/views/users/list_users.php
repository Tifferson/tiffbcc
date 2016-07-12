<?
	function checkbox($u, $field){
		$id = $u->id;
		$on = $u->$field == '1' ? 'checked="checked"' : '';
		
		return <<<HTML
			<input name="users[$id][$field]" 
			$on
			type="checkbox" />
HTML;
	
	}

?>

<h1>Users and Permissions</h1>

<form <?=form_action("users/save_permissions")?>>
<table style="width:100%">
	<tr>
		<th>User</th>
    <th>Active</th>
		<th>Admin</th>
		<th>Employee</th>
		<th>Tasks</th>
		<th>Sources</th>
		<th>Collection</th>
		<th>Production</th>
		<th>Reporting</th>
		<th>Knowledge Base</th>
		<th>Actions</th>
	</tr>
	
<?	foreach($users->result() as $u) : ?>
	<tr id="<?=$u->id?>" class="<?=alternator('row_alt','row')?> c"
      <?= $u->active == 0 ? 'style="color:#666"' : '' ?>
    >
		<td style="text-align:left"><span style="text-decoration:<?= $u->active == 0 ? 'line-through' : 'none' ?>"><?=$u->name?></span>
      <span style="font-size:0.8em">
			<? if(!empty($u->email)) : ?>
				<br/><?=$u->email?>
			<? endif ?>
      <? if (!@empty($u->last_login_ip) || !@empty($u->last_login_datetime)) { ?>
        <small><br />Last login 
	  	<?= !@empty($u->last_login_ip) ? "ip {$u->last_login_ip}" : '' ?>
        <?= !@empty($u->last_login_datetime) ? "at {$u->last_login_datetime}" : '' ?>
      	</small>
      <? } ?>
      <? if (!@empty($u->login_success_count) || !@empty($u->login_fail_count)) { ?>
        <small><br />
	  	<?= !@empty($u->login_success_count) ? "Successful: {$u->login_success_count}" : '' ?>
        <?= !@empty($u->login_fail_count) ? "Failed: {$u->login_fail_count}" : '' ?>
      	</small>
      <? } ?>
       </span>
		</td>
    <td><?= checkbox($u, 'active') ?>
        <input name="users[<?=$u->id?>][placeholder]" value="1" type="hidden" /> 
    </td>
		<td><?= checkbox($u, 'is_admin') ?> </td>
		<td><?= checkbox($u, 'is_employee') ?> </td>
		<td><?= checkbox($u, 'tasks') ?> </td>
		<td><?= checkbox($u, 'sourcing') ?> </td>
		<td><?= checkbox($u, 'collection') ?> </td>
		<td><?= checkbox($u, 'production') ?> </td>
		<td><?= checkbox($u, 'reporting') ?> </td>
		<td><?= checkbox($u, 'knowledge') ?> </td>
		<td><?= anchor('Users/edit/' . $u->id, 'Edit') ?> | <a href="#delete" onclick="del(<?=$u->id?>)"> Delete </a> </td>
	</tr>
<? endforeach ?>

</table>
<div class="buttons">
	<button type="submit" class="positive">Save Permissions</button>
</div>
</form>

<script type="text/javascript">
	function del(id){
		if(confirm("Delete user?")){
			$.post("<?=site_url('Users/delete')?>", {'id':id}, function(data){
        alert(data);
				if(data.match(/delete/i)) $("#" + id).remove()
			})
		}
	}
</script>
