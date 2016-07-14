<?
	if(isset($user_filter) && is_array($user_filter)) :
		$Users = $this->User->filtered($user_filter);
	else :
		$Users = $this->User->employees();
	endif;
?>
<select class="required" name="<?=$field?>" >
	<option value="">...</option>
	<? foreach($Users->result() as $User) : ?>
		<option value="<?=$User->id?>"
			<?= isset($selected) && $selected == $User->id ? 'selected="selected"' : '' ?> >
			<?=$User->name?>
		</option>
	<? endforeach ?>
</select>
