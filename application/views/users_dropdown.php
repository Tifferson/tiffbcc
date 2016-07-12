<?
	if(isset($user_filter) && is_array($user_filter)) :
		$users = $this->User->filtered($user_filter);
	else :
		$users = $this->User->employees();
	endif;
?>
<select class="required" name="<?=$field?>" >
	<option value="">...</option>
	<? foreach($users->result() as $user) : ?>
		<option value="<?=$user->id?>"
			<?= isset($selected) && $selected == $user->id ? 'selected="selected"' : '' ?> >
			<?=$user->name?>
		</option>
	<? endforeach ?>
</select>
