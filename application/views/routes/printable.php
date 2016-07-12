<h1><?=$route->name?></h1>
<table id="stops" style="border: 2px solid black;width:100%">
<tr>
	<th></th>
	<th>Stop</th>
	<th>Notes</th>
	<th>Amount</th>
</tr>
<? $i = 1 ?>
<? foreach($stops->result() as $stop) : ?>
	<tr id="<?=$stop->id?>">
		<td><?= $i ?></td>
		<td><?=clean_txt($stop->dgf_name)?>
			<br/>
			<span class="subinfo"><?=loc_address($stop)?><br/>Phone: <?=$stop->phone?></span>
			<br/>
			Pickup Time: <?= $stop->pickup_time_general ?>
		</td>
		<td style="width:55%"><span class="subinfo"><?=$stop->pickup_specifics?>&nbsp;</span></td>
		<td style="width:5%">&nbsp;</td>
	</tr>
<? $i++ ?>
<?endforeach?>
</table>
<br/>
<table style="width:100%">
  <tr>
    <td style="border-bottom:1px solid black">Driver: </td>
    <td style="border-bottom:1px solid black">Date: </td>
  </tr>
</table>