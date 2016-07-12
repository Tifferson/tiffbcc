<h1>Edit User</h1>
<form <?=form_action('Users/edit')?> >
<input type="hidden" name="id" value="<?=$user->id?>" />
<table>
  <tr>
    <td>Username:</td>
    <td><input type="text" name="name" 
		value="<?=$user->name?>"/></td>
  </tr>
  <tr>
    <td>Email Address:</td>
    <td><input type="text" name="email"
		value="<?=$user->email?>" /></td>
  </tr>
  <tr>
    <td>New Password:</td>
    <td><input type="password" name="password" class="required"/></td>
  </tr>
</table>
<p>Assign permissions on 'users and permissions' screen</p>
<p class="buttons"> 
	<button class="positive" type="submit" value="save" name="save">Update User</button>
</p>
</form>
