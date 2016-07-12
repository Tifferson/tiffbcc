<h1>Confirm Upload</h1>
<p class="box">You will  upload <b><?=count($results)?></b> leads</p>
<table>
	<tr>
		<th>Status</th>
		<th>Name</th>
		<th>City</th>
		<th>State</th>
		<th>Zip</th>
	</tr>
<?foreach($results as $result) : ?>
	<tr>
		<td><?=$result['type']?></td>
		<td><?=$result['dgf_name']?> 
        <br/><span class="subinfo"><?=$result['dgf_address']?></span>
    </td>
		<td><?=$result['city']?></td>
		<td><?=$result['state']?></td>
		<td><?=$result['zip']?></td>
	</tr>
<?endforeach?>
</table>
<?= form_open("leads/do_upload") ?>
   <input type="hidden" name="filename" value="<?= $filename ?>" />
  <button type="submit">Finish Upload</button>
<?= '</form>' ?>
