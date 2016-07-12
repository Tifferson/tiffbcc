<h1>New Leads Uploaded!</h1>
<p class="box">You uploaded <b><?=count($results)?></b> leads</p>
<table>
	<tr>
		<th>Add or Edit</th>
		<th>Name</th>
		<th>City</th>
		<th>State</th>
		<th>Zip</th>
	</tr>
<?foreach($results as $result) : ?>
	<tr>
		<td><?=$result['type']?></td>
		<td><?=$result['dgf_name']?></td>
		<td><?=$result['city']?></td>
		<td><?=$result['state']?></td>
		<td><?=$result['zip']?></td>
	</tr>
<?endforeach?>
</table>
<a href="<?= site_url("leads") ?>">Back to Lead List</a>