<? //todo: add comments ?>
<script type="text/javascript">
	$("#num_total").text("<?=$locations->num_rows()?>")
</script>
<form>
<table style="width:100%">
<? foreach($locations->result() as $loc) : 
	if(!empty($loc->dgf_name)) :
   
      if($highlight_zip) $zip = preg_replace("/$q/", "<b>$0</b>", $loc->zip);
      else $zip = $loc->zip;
			
			$edit_url = site_url("locations/edit/" . $loc->id);
			
			if($loc->canceled == 1) $css_class = 'canceled';
			elseif($loc->is_lead == 1) $css_class = "lead";
			elseif($loc->ignore == 1) $css_class = 'ignore';
      elseif($loc->is_customer == 1) $css_class = "customer";
			else $css_class = '';
		  
		  ?>     
      <tr id="<?=$loc->id?>" class="<?=$css_class?>" style="padding: 2px">
		  <td style="width: 1%" >
			<input onclick="update_count(this)" type="checkbox" class="location" name="<?=$loc->id?>" />
		  </td>
          <td onclick="go_to('<?=$edit_url?>')" style="padding: 3px">
			
			 <?= preg_replace("/$q/i", "<b>$0</b>", $loc->dgf_name) ?>
			
				<br/>
				<span class="subinfo">
					<?= preg_replace("/^$q/i", "<b>$0</b>", $loc->dgf_address) ?>, 
					<?= $loc->city ?>, <?=  $loc->state ?> <?= $zip ?>
				</span>
		  </td>
          <td style="font-size: 0.8em" onclick="go_to('<?=$edit_url?>')"  >
			<b>Phone:</b> <?= ucwords(strtolower($loc->phone)) ?> <br/>
			
			<? if($loc->is_lead == '1') : ?>	
			  	<b>Contact:</b> <?= $loc->contact_name ?><br/>
				<b>Contact Phone:</b> <?= $loc->contact_number ?><br/>
				<b>Last Contacted on:</b> <?=mysql_to_mdy($loc->last_date_contacted)?><br/>
			<? endif ?>
			<? if($loc->num_containers > 0) : ?>
				<b>Containers:</b> (<?=$loc->num_containers?>) <?=$loc->container_type?> <br/>
			<?elseif($loc->is_customer == '1') :?>
				containers unknown <br/>
			<?endif?>
		</td>
		<td style="font-size: 0.8em" onclick="go_to('<?=$edit_url?>')"  >
			
			<? $roles = $this->session->userdata("roles") ?>
			<?if($roles['c'] == '1') : ?>
				<?= empty($loc->pickup_specifics) ? "&nbsp;" : "<b>Notes:</b> " . $loc->pickup_specifics ?>
			<? else : ?>
				<?= empty($loc->notes) ? "&nbsp;" : "<b>Notes:</b> " . $loc->notes ?>
			<? endif ?>
		  </td>
      </tr>
  <? endif;
	endforeach; ?>
</table>
</form>
