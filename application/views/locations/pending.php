<h1>Locations Pending Verification</h1>
<table style="width:100%">
	<tr>
		<th>Who/When</th>
		<th>Location</th>
		<th>Info</th>
	</tr>
<?$i = 0?>
<? foreach($locations->result() as $loc) : 
	if(!empty($loc->dgf_name)) : ?>      
      <tr id="<?=$loc->id?>" class="<?= $i % 2 == 0 ? "row" : "row_alt" ?>">
		  <td>
					<?=$loc->dgf_rep?> <br/>
					<?=mysql_to_mdy($loc->date_added_as_customer, false)?>
		  </td>
          <td>
			<?= anchor('locations/edit/' . $loc->id, $loc->dgf_name) ?>
				<br/>
				<span class="subinfo">
					<?= clean_txt($loc->dgf_address) ?>, 
					<?= $loc->city ?>, <?=  $loc->state ?> <?= $loc->zip ?>
				</span>
		  </td>
          <td style="font-size: 0.8em" >
			<b>Date Added to DB:</b> <?= date_to_mdy($loc->date_added) ?> <br/>
			<b>Acquisition Date:</b> <?= date_to_mdy($loc->date_added_as_customer) ?><br/>
		  </td>
      </tr>
	 <? $i++ ?> 
  <? endif;
	endforeach; ?>
</table>