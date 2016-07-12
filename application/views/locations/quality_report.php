<h1>Quality Report</h1>
<p> <?=anchor("locations/quality/asc", "Best to Worst", $sort == 'asc' ? array('class' => 'active') : '')?> | 
<?=anchor("locations/quality/desc", "Worst to Best", $sort == 'desc' ? array('class' => 'active') : '')?>
</p>
<table style="width:100%">
	<tr>
		<th>Location</th>
		<th>Acid Number</th>
		<th>Test Date</th>
	</tr>
<? foreach($locations->result() as $loc) : 
	if(!empty($loc->dgf_name)) : ?>      
      <tr id="<?=$loc->id?>" class="<?= alternator("row_alt", 'row') ?>">
        <td>
			        <?= anchor('locations/edit/' . $loc->id, $loc->dgf_name) ?>
  				<br/>
  				<span class="subinfo">
  					<?= $loc->dgf_address ?>, 
  					<?= $loc->city ?>, <?=  $loc->state ?> <?= $loc->zip ?>
  				</span>
		    </td>
        <td class="c" >
          <?= p_num($loc->ffa_number) ?>
		    </td>
		    <td class="c" >
		      <?= date_to_mdy($loc->ffa_test_date) ?>
		    </td>
      </tr>
  <? endif;
	endforeach; ?>
</table>