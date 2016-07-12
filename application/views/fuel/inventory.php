<h1>Fuel Totals</h1>
<table style="width:100%">
	<tr>
		<th >Product</th>
		<th style="width:40%">Description</th>
		<th>Quantity on Hand</th>
		<!--<th>Credit Taken</th>-->
		<th>Actions</th>
	</tr>
	<? $i = 1 ?>
	<? foreach($products->result() as $product) : ?>
	<tr class="<?= $i % 2 == 0 ? "row" : "row_alt" ?>">
		<td>
			<?= $product->name ?>
		</td>
		<td>
			<?= $product->description ?>
		</td>
		<td style="text-align:center">
			<?= $product->quantity ?>
		</td>
	<? /*	<td style="text-align:center">
			<?= $product->tax_credit_taken == 1 ? "Y" : "N" ?>
		</td>
		*/
	?>
		<td style="text-align:center">
			Edit | Delete
		</td>
	</tr>
	<? $i++ ?>
	<?endforeach ?>
</table>

<h1>Containers</h1>

<table style="width:100%">
	<tr>
		<th>Container</th>
		<th>Product</th>
		<th>Capacity</th>
		<th>Current Level</th>
		<th>Fullness</th>
		<th>Actions</th>
	</tr>
	<? $i = 1 ?>
	<? foreach($containers->result() as $c) : ?>
	<tr class="<?= $i % 2 == 0 ? "row" : "row_alt" ?>">
		<td>
			<?= $c->name ?>
		</td>
		<td style="text-align:center">
			<?= $c->product_name ?>
		</td>
		<td style="text-align:center">
			<?= $c->size ?>
		</td>
		<td style="text-align:center">
			<?= $c->current_level ?>
		</td>
		<td style="text-align:center">
		<? if($c->size > 0) : ?>
			<img src="http://chart.apis.google.com/chart?
				chs=100x50
					&amp;chd=t:<?=$c->current_level?>
					&amp;chds=0,<?=$c->size?>
					<? //&amp;chco=4D89F9,C6D9FD  | < ?=$c->size? > ?>
					&amp;cht=gom" />
		<?endif?>
		</td>
		<td style="text-align:center">
			Edit | Delete
		</td>
	</tr>
	<? $i++ ?>
	<?endforeach ?>
</table>
