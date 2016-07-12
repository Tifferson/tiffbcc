<table class="hint" style="width:100%">
  <tr>
    <td>
    Hold shift and click to sort on multiple fields
  </td>
	<td><a href="#hide" onclick="$('.hint').hide('slow')">hide hint</a></td>
  </tr>
</table>

<table style="width:100%" class="tablesorter">
<thead>
	<tr>
		<th class="{sorter: 'text'}">Location</th>
		<th>Under Contract&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<th>Date Added to DB&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<th>Date Acquired&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<th>Latest Event&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<th class="{sorter: false}" style="width:40%">Info</th>
	</tr>
</thead>
<tbody>
<?$i = 0?>
<? if(isset($locations) && $locations->num_rows() > 0) : ?>
<? foreach($locations->result() as $loc) : 
	if(!empty($loc->dgf_name)) : ?>      
      <tr id="<?=$loc->id?>" class="<?= $i % 2 == 0 ? "row" : "row_alt" ?>">
          <td>
			<span style="display:none"><?=$loc->dgf_name?></span>
			<?= anchor('locations/edit/' . $loc->id, clean_txt($loc->dgf_name)) ?>
				<br/>
				<span class="subinfo">
					<?= clean_txt($loc->dgf_address) ?>, 
					<?= clean_txt($loc->city) ?>, <?=  $loc->state ?> <?= $loc->zip ?>
				</span>
		  </td>

		  <td class="c"><?= $loc->under_contract == 1 ? 'Y' : 'N' ?></td>
		  <td class="c"><?= date_to_sortable($loc->date_added)?></td>
		  <td class="c"><? if(!stristr($loc->start_date, empty_date())) :
							echo date_to_sortable($loc->start_date);
						   else :
							echo date_to_sortable($loc->date_added_as_customer);
						   endif;
						?></td>
		  <td class="c"><?= date_to_sortable($loc->last_date_contacted) ?></td>
		  
          <td style="font-size: 0.8em" >
			<b>Phone:</b> <?= ucwords(strtolower($loc->phone)) ?> <br/>
			<b>Contact:</b> <?= $loc->contact_name ?><br/>
			<? if($loc->num_containers > 0) : ?>
			<b>Containers:</b> (<?=$loc->num_containers?>) <?=$loc->container_type?>
			<?elseif($loc->is_customer == '1') :?>
				containers unknown
			<?endif?>
			
			<? $roles = $this->session->userdata("roles") ?>
			<?if($roles['c'] == '1') : ?>
				<?= empty($loc->pickup_specifics) ? "" : "<br/><b>Notes:</b> " . $loc->pickup_specifics ?>
			<? else : ?>
				<?= empty($loc->notes) ? "" : "<br/><b>Notes:</b> " . $loc->notes ?>
			<? endif ?>
		  </td>
      </tr>
	<?$i++?>
  <? endif;
	endforeach; ?>
</tbody>
</table>
<?endif?>
