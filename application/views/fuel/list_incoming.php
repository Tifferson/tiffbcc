<h1>Incoming fuel from <?=$start_date?> to <?=$end_date?></h1>
<? if($incoming->num_rows > 0) : ?>
<? $total = 0; ?>
<table style="width:100%">
	<tr>
		<th>Date</th>
		<th>Product</th>
		<th>Supplier</th>
		<th>Amount</th>
		<th>Container</th>
		<th>Price</th>
		<th>Actions</th>
	</tr>
<? foreach($incoming->result() as $delivery) : ?>
<tr id="<?=$delivery->id?>" class="<?= alternator("row", "row_alt") ?>">
	<td>
		<?= mysql_to_mdy($delivery->when) ?>
	</td>
	<td>
		<?= $delivery->product_name ?>
	</td>
	<td>
		<?= $delivery->supplier_name ?>
	</td>
	<td style="text-align:right">
		<?= $delivery->amount ?>
	</td>
	<td >
		<?= $delivery->container_name ?>
	</td>
	<td style="text-align:center">
		$<?= p_num($delivery->price) ?>
	</td>
	<td style="text-align:center">
		<?= anchor('fuel/view/' . $delivery->id, "View") ?> |
		<?// anchor('fuel/edit_incoming/' . $delivery->id, "Edit") ?> 
		<a href="#del_<?=$delivery->id?>" onclick="delete_incoming(<?=$delivery->id?>)">Delete</a>
	</td>
</tr>
<? $total += $delivery-> amount ?>
<? endforeach ?>
<tr>
		<td colspan="6">&nbsp;</td>
</tr>
<tr>
		<td colspan="3">&nbsp;</td>
		<td style="text-align: right; font-weight:bold; border-top: 2px solid black" ><?=$total?></td>
		<td style="text-align: left; font-weight:bold">&nbsp;&nbsp;&nbsp;total gal</td>
		<td colspan="1">&nbsp;</td>
</tr>
</table>
<? else : ?>
<p>No incoming fuel deliveries for this range</p>
<? endif ?>