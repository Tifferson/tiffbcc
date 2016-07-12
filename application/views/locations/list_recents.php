<h1><?=$heading?></h1>
<? $this->load->view("locations/locations_table") ?>
<? /*
<table style="width:100%" class="tablesorter">
<thead>
	<tr>
		<th>Who/When</th>
		<th>Location</th>
		<th>Info</th>
	</tr>
</thead>
<tbody>
<?$i = 0?>
<? foreach($locations->result() as $loc) : 
	if(!empty($loc->dgf_name)) : ?>      
      <tr id="<?=$loc->id?>" class="<?= $i % 2 == 0 ? "row" : "row_alt" ?>">
		  <td>
				<? if($which == 'sales') : ?>
					<?=$loc->last_contacted_by?> <br/>
					<?=mysql_to_mdy($loc->last_date_contacted)?>
				<? else : ?>
					<?=$loc->last_edited_by?> <br/>
					<?=mysql_to_mdy($loc->last_edited)?>
				<? endif ?>
		  </td>
          <td>
		  
			<?= anchor('locations/edit/' . $loc->id, clean_txt($loc->dgf_name)) ?>
				<br/>
				<span class="subinfo">
					<?= clean_txt($loc->dgf_address) ?>, 
					<?= clean_txt($loc->city) ?>, <?=  $loc->state ?> <?= $loc->zip ?>
				</span>
		  </td>
          <td style="font-size: 0.8em" >
			<b>Phone:</b> <?= ucwords(strtolower($loc->phone)) ?> <br/>
			<b>Contact:</b> <?= $loc->contact_name ?><br/>
			<? if($loc->num_containers > 0) : ?>
			<b>Containers:</b> (<?=$loc->num_containers?>) <?=$loc->container_type?>
			<?elseif($loc->is_customer == '1') :?>
				containers unknown
			<?endif?>
		  </td>
      </tr>
	 <? $i++ ?> 
  <? endif;
	endforeach; ?>
</tbody>
</table> */ ?>
