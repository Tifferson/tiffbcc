<h1>Customers</h1>
<table style="width:100%">
<tr>
  <th>Name</th>
  <th>Address</th>
  <th>City</th>
  <th>State</th>
  <th>Zip</th>
  <th>Email</th>
  <th>Actions</th>
</tr>

<?foreach($customers->result() as $c) : ?>
<tr class="<?=alternator("row", "row_alt")?>">
  <td><?=$c->name ?></td>
  <td><?=$c->address ?></td>
  <td><?=$c->city ?></td>
  <td><?=$c->state ?></td>
  <td><?=$c->zip ?></td>
  <td><?=$c->email ?></td>
  <td>Edit | Delete</td>
</tr>
<? endforeach ?>
</table>