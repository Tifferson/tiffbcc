
<h1>Supplier List</h1>
<?= anchor('fuel/create_supplier', "[+] New Supplier") ?>
<table style="width:100%">
	<tr>
		<th>Name</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>Zip</th>
		<th>Actions</th>
	</tr>
	<? $i = 1 ?>
	<? foreach($suppliers->result() as $vendor) : ?>
	<tr class="<?= $i % 2 == 0 ? "row" : "row_alt" ?>">
		<td>
			<?= $vendor->name ?>
		</td>
		<td style="text-align:center">
			<?= $vendor->address1 ?> 
			<?if(!empty($vendor->address2)) echo "<br/>" . $vendor->address2 ?>
		</td>
		<td style="text-align:center">
			<?= $vendor->city ?>
		</td>
		<td style="text-align:center">
			<?= $vendor->state ?>
		</td>
		<td style="text-align:center">
			<?= $vendor->zip ?>
		</td>
		<td style="text-align:center">
			Edit | Delete
		</td>
	</tr>
	<? $i++ ?>
	<?endforeach ?>

</table>