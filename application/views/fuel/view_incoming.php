<h1>Incoming Fuel</h1>
	<table style="width:60%">
		<tr>
			<td>Supplier: </td>
			<td>
				<?= $i->supplier_name ?>
			</td>
		</tr>
		<tr>
			<td>Product: </td>
			<td>
				<?= $i->product_name ?>
			</td>
		</tr>
		<tr>
			<td>Recieving Container: </td>
			<td>
				<?= $i->container_name ?>
			</td>
		</tr>
		<tr>
			<td>Quantity:</td>
			<td><?= $i->amount ?> gal</td>
		</tr>
		<tr>
			<td>Price:</td>
			<td>$<?= $i->price ?> </td>
		</tr>
		<tr>
			<td>RIN:</td>
			<td><?=$i->rin?></td>
		</tr>
		<tr>
			<td>Date Delivered:</td>
			<td><?=mysql_to_mdy($i->when)?> </td>
		</tr>
		
		<tr>
			<td>Excise Taxes Paid:</td>
			<td> rest of fields.... </td>
		</tr>
		
		<tr class="tax_credit" style="display:none">
			<td>Tax Credit Taken:</td>
			<td><input type="checkbox" name="tax_credit_taken"/> </td>
		</tr>
		<tr class="coa">
			<td>COA: </td>
			<td>
				<?= $this->incoming->download_link($i->coa, "COA") ?>
			</td>
		</tr>
		<tr >
			<td>Bill of Lading: </td>
			<td>
				<?= $this->incoming->download_link($i->bill_of_lading, "Bill of Lading") ?>
			</td>
		</tr>
		<tr>
			<td>Weigh sheets before:</td>
			<td>
				<?= $this->incoming->download_link($i->weigh_before, "Weigh sheet before") ?>
			</td>
		</tr>
		<tr>
			<td>Weigh sheets after:</td>
			<td>
				<?= $this->incoming->download_link($i->weigh_after, "Weigh sheet after") ?>
			</td>
		</tr>
		<tr>
			<td>Internal Quality Report: </td>
			<td>
				<?= $this->incoming->download_link($i->interntal_quality_report, "Internal Quality Report") ?>
			</td>
		</tr>
	</table>
	
<?= '</form>' ?>