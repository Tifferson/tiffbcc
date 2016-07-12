<h1>Sales from <?=$start_date?> to <?=$end_date?></h1>
<? if($sales->num_rows > 0) : ?>
<?$total_gal = 0?>
<table style="width:100%">
  <tr>
    <th>Date</th>
    <th>Amount</th>
    <th>Customer</th>
    <th>Product</th>
    <th>Container</th>
    <th>Price</th>
  </tr>
<? foreach($sales->result() as $sale) : ?>
	<tr class="<?=alternator('row', 'row_alt')?>">
	  <td><?= mysql_to_mdy($sale->date) ?></td>
	  <td style="text-align:right"><?= $sale->amount ?></td>
	  <td><?= $sale->customer_name?></td>
	  <td><?= $sale->product_name?></td>
	  <td><?= $sale->container_name?></td>
	  <td>$<?= p_num($sale->price)?></td>
	</tr>
	<? $total_gal += $sale->amount ?>
<? endforeach ?>
<tr>
		<td colspan="6">&nbsp;</td>
</tr>
<tr>
		<td colspan="1">&nbsp;</td>
		<td style="text-align: right; font-weight:bold; border-top: 2px solid black" ><?=$total_gal?></td>
		<td style="text-align: left; font-weight:bold">&nbsp;&nbsp;&nbsp;total gal</td>
		<td colspan="4">&nbsp;</td>
</tr>
</table>
<h1>Breakdown by Month and by Customer</h1>
<div class="c">
<img src="http://chart.apis.google.com/chart?
chs=450x250
&amp;chd=t:<?=$month_graph['data']?>
&amp;chds=0,<?=$month_graph['max']?>
&amp;chco=4D89F9,C6D9FD,CFD4E6
&amp;cht=p
&amp;chl=<?=$month_graph['label']?>
&amp;chtt=Gallons+Sold+by+Month" />

<img src="http://chart.apis.google.com/chart?
chs=450x250
&amp;chd=t:<?=$customer_graph['data']?>
&amp;chds=0,<?=$customer_graph['max']?>
&amp;chco=4D89F9,C6D9FD,CFD4E6
&amp;cht=p
&amp;chl=<?=$customer_graph['label']?>
&amp;chtt=Gallons+Sold+by+Customer" />
</div>
<? else : ?>
<p>No sales for this range</p>
<? endif ?>