<h1>Locations With Soon-to-Expire (2 months) Contracts</h1>

<? if($expiring->num_rows() < 1) : 0 ?>
<p>No locations have contracts expiring in 2 months.</p>
<? else : ?>
<table style="width:100%">
	<tr>
		<th>Location</th>
		<th>DGF Rep</th>
		<th>Contract Start Date</th>
		<th>Contract End Date</th>
	</tr>
<?$i = 0?>
<? foreach($expiring->result() as $loc) : 
	if(!empty($loc->dgf_name)) : ?>      
      <tr id="<?=$loc->id?>" class="<?= $i % 2 == 0 ? "row" : "row_alt" ?>">

          <td>
			<?= anchor('locations/edit/' . $loc->id, $loc->dgf_name) ?>
				<br/>
				<span class="subinfo">
					<?= clean_txt($loc->dgf_address) ?>, 
					<?= $loc->city ?>, <?=  $loc->state ?> <?= $loc->zip ?>
				</span>
		  </td>
		  <td style="width:15%">
					<?=$loc->dgf_rep?> <br/>
		  </td>
          <td style="text-align:center" >
			<?= mysql_to_mdy($loc->start_date) ?>
		  </td>
		  <td style="text-align:center" >
			<?= mysql_to_mdy($loc->end_date) ?><br/>
		  </td>
      </tr>
	 <? $i++ ?> 
  <? endif;
	endforeach; ?>
</table>
<? endif ?>


<h1>Locations With Expired Contracts</h1>

<? if($expired->num_rows() < 1) : 0 ?>
  <p>No locations (with contracts) have expired contracts.</p>
<? else : ?>
<table style="width:100%">
	<tr>
		<th>Location</th>
		<th>DGF Rep</th>
		<th>Contract Start Date</th>
		<th>Contract End Date</th>
	</tr>
<?$i = 0?>
<? foreach($expired->result() as $loc) : 
	if(!empty($loc->dgf_name)) : ?>      
      <tr id="<?=$loc->id?>" class="<?= $i % 2 == 0 ? "row" : "row_alt" ?>">

          <td>
			<?= anchor('locations/edit/' . $loc->id, $loc->dgf_name) ?>
				<br/>
				<span class="subinfo">
					<?= clean_txt($loc->dgf_address) ?>, 
					<?= $loc->city ?>, <?=  $loc->state ?> <?= $loc->zip ?>
				</span>
		  </td>
		  <td style="width:15%">
					<?=$loc->dgf_rep?> <br/>
		  </td>
          <td style="text-align:center" >
			<?= date_to_mdy($loc->start_date) ?>
		  </td>
		  <td style="text-align:center" >
			<?= date_to_mdy($loc->end_date) ?><br/>
		  </td>
      </tr>
	 <? $i++ ?> 
  <? endif;
	endforeach; ?>
</table>
<? endif ?>

