<h2>Welcome to The Biodiesel Control Center!</h2>

<h1>Oil Sources Statistics</h1>
<table style="width:100%">
	<tr>
	 <td>
	 <!-- begin nested table -->
	 <table>
		<tr>
			<th>Location Status</th>
			<th>Total</th>
		</tr>
		<tr>
			<td>Customers: </td>
			<td><?=colorize($total_customers, 'green') ?></td>
		</tr>
		<tr>
			<td>Customers &amp; Locations:</td>
			<td><?=colorize($total_locations, 'green')?></td>
		</tr>
		<tr>
			<td>Customers, Locations, &amp; Ignored:</td>
			<td><b><?=$all_known?><b/></td>
		</tr>
	</table>
	<!-- end nested table -->
	</td>
	<!--<td>
<? //if(!empty($total_customers) && !empty($total_locations))  : ?> 
	<img src="http://chart.apis.google.com/chart?
chs=500x200
&amp;chd=t:<?=$total_customers?>,<?=$total_locations - $total_customers?>
&amp;chds=0,<?=$total_customers + $total_locations?>
&amp;cht=p3
&amp;chl=Customers|Other+Locations 
&amp;chtt=Customer to Known Locations Ratio" />
<? //endif ?>
</td>-->
</tr>
</table>

<?if($reminders->num_rows() > 0) : ?>
<h1>Your Reminders</h1>
<ul>
	<?foreach($reminders->result() as $reminder) : ?>
  <li id="reminder_<?=$reminder->id?>">
    <span <?= $reminder->done == '1' ? 'style="text-decoration:line-through"' : '' ?> >
     <?= date_to_mdy($reminder->date) ?></span>:

        <?= anchor( 'locations/edit/' . $reminder->location_id, $reminder->dgf_name ) ?><br/>
        <br/><?= $reminder->notes ?>
  </li>
	<?endforeach?>
</ul>

<?endif?>


<? if($calls->num_rows() > 0) : ?>
<h1>Your Open Calls</h1>
<?$i = 0;?>
<ul>
<? foreach($calls->result() as $call) : ?>
    <li><?=$call->caller . ' - ' . $call->callback?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		(<?= anchor("Calls/edit/" . $call->id, "edit" )?>)
		(<?= anchor("Calls/close/" . $call->id, "close" )?>)</li>
<?endforeach?>
</ul>
<?endif?>


<?$i = 0;?>

<? if($work_items->num_rows > 0) : ?>
<br/>
<h1>Your Open Work Orders</h1>
<ul>
    <? foreach($work_items->result() as $work_item) : ?>
    <li><?=mysql_to_mdy($work_item->due_date)?> - 
			<?=anchor("Work_orders/edit/" . $work_item->id, $work_item->task)?></li>
    <?endforeach?>
</ul>
<?endif?>

<? if(!empty($pricing)) : ?>
<h1>Pricing (as of <?=mysql_to_human($pricing->when)?>)</h1>
<table>
<tr><td>Biodiesel Price to Reseller Customers</td><td><?=$pricing->b100_retail_price?></td></tr>
<tr><td>Biodiesel Price to Fleet Customers</td><td>Negotiable- fwd to sales rep</td></tr>
<tr><td>SVO Price to Reseller Customers</td><td><?=$pricing->svo_retail_price?></td></tr>
<tr><td>SVO Price to Fleet Customers</td><td><?=$pricing->svo_fleet_price?></td></tr>
<tr><td></td><td></td></tr>
</table>
<? endif ?>
