<h1>Locations <?=ucwords($status)?> Under Contract</h1>
<p>
<?if($status == "not") : ?>
	<?= anchor("locations/under_contract/", "locations under contract") ?>
<? else : ?>
	<?= anchor("locations/under_contract/not", "locations not under contract") ?>
<? endif ?>	
</p>
<table style="width:100%">
	<tr>
		<th>Location</th>
		<th>DGF Rep</th>
		<th>Contract Start Date</th>
		<th>Contract End Date</th>
	</tr>
<?$i = 0?>
<? foreach($locations->result() as $loc) : 
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