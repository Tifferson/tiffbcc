<h1>New Route</h1>

<form action="<?=site_url("routes/save_new")?>" method="POST">
  <table>
    <tr>
        <td>Name:</td>
        <td><input type="text" name="name" /></td>
    </tr>
    <tr>
        <td>Description:</td>
        <td><input type='text' name="description" /></td>
    </tr>
  </table>
  
  <input type="submit" value="Save Route" />
</form>
