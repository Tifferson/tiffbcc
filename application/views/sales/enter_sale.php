<h1>Enter a Sale</h1>

<form <?= form_action("sales/create_sale") ?>>

<table>
		<tr>
			<td>Date:</td>
			<td><input class="validate" type="text" name="date"/> </td>
		</tr>
		<tr>
			<td>Customer: </td>
			<td>
				<?= select_other('customer_id', $customers ); ?>
			</td>
		</tr>

		<tr>
			<td>Product: </td>
			<td>
				<?= select('product_id', $products ); ?>
			</td>
		</tr>
		<tr>
			<td>From Container: </td>
			<td>
				<?= select('container_id', $containers ); ?>
			</td>
		</tr>
		<tr>
			<td>Quantity:</td>
			<td><input class="validate" type="text" name="amount"/> (gallons)</td>
		</tr>
		<tr>
			<td>Price:</td>
			<td><input class="validate" type="text" name="price"/> </td>
		</tr>

</table>
<input type="submit" value="Submit Sale" />
</form>

<script type="text/javascript">
	$( function(){
			$("input[name=date]").datepicker()
	})
</script>