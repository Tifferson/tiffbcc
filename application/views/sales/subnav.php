<div id="subnav">	
	<table style="width:100%">
		<tr>
			<td>
				<b>Sales:</b>
		<?= anchor('sales/show', "View Sales", active_attr($active, 'show')) ?> |
		<?= anchor('sales/sale', "[+] Sell Fuel", active_attr($active, 'sale')) ?>
			</td>
			<td>
				<b>Customers:</b>
		<?= anchor('sales/customers', "Customer List", active_attr($active, 'list_customers')) ?> | 
		<?= anchor('sales/create_customer', "[+] Add New Customer", active_attr($active, 'create_customer')) ?>
			</td>
		</tr>
	</table>
</div>