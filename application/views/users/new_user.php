<h1>New User</h1>
<form <?=form_action('Users/create')?> class="validate">
<table>
  <tr>
    <td>Username:</td>
    <td><input type="text" name="name" class="required"/></td>
  </tr>
  <tr>
    <td>Email Address:</td>
    <td><input type="text" name="email" class="required"/></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><input type="password" name="password" class="required"/></td>
  </tr>
</table>
<p>Assign permissions on 'users and permissions' screen</p>
<p class="buttons"> 
	<button class="positive" type="submit" value="save" name="save">Save New User</button>
</p>
</form>
