<?if($locations->num_rows() > 0 ) : ?>
<h3>There are <?=$locations->num_rows()?> leads in the list</h3>
<!--
<p style="padding:5px">
	<form>
		<input type="checkbox" name="region" value="austin" /> Austin
		<input type="checkbox" name="region" value="sanantonio" /> San Antonio
		<input type="checkbox" name="region" value="dfw" /> Dallas / Fort Worth
		<input type="checkbox" name="region" value="houston" /> Houston
	</form>
</p>
-->

<table style="width:100%" class="tablesorter">
<thead>
	<tr style="background:#ddd;">
		<th>Location</th>
		<th>Contact</th>
		<th>Last Date Contacted</th>
		<th>Date Added</th>
		<th style="width:200px;">Actions</th>
	</tr>
</thead>
<tbody>
  <? foreach($locations->result() as $loc) :
      if(!empty($loc->dgf_name)) :
      ?>
      <tr id="<?=$loc->id?>" class="<?= alternator('row' , 'row_alt') ?>">
          <td class="name"><b><?= anchor('locations/edit/' . $loc->id, clean_txt($loc->dgf_name)) ?> </b><br/> 
          <?= clean_txt( $loc->dgf_address) ?>, <?= clean_txt($loc->city) ?></td>
          <td><?=$loc->contact_name?> <br/><?= $loc->phone ?></td>
		  <td class="c"><?= mysql_to_mdy($loc->last_date_contacted, false) ?></td>
		  <td class="c"><?= date_to_mdy($loc->date_added, false)?></td>
		  <td class="center" style="font-size:0.8em;">
		  	<?if(isset($mine) && $mine) : ?> 
			     <a href="<?=site_url('leads/make_inactive/' . $loc->id)?>">remove</a> 
			  <? else : ?>
			     <a href="<?=site_url('leads/make_active/' . $loc->id)?>">add to my leads</a>
           | <a href="<?=site_url('leads/remove_lead/' . $loc->id)?>">remove</a> 
			  <? endif ?>
		      | <a href="#ignore" onclick="ignore(<?=$loc->id?>); return false;">ignore</a> 
		  </td>
      </tr>

  <? endif ?>
 <? endforeach ?>
</tbody>
</table>

<script type='text/javascript'>
  ignore_url = '<?=site_url('leads/ignore')?>';

	function ignore(id){
	  name = $("#" + id + " td.name").text()
	  if(confirm("Ignore " + name)){
	    params = {'id':id, 'reason':prompt("Enter reason below")}
	    $.post(ignore_url, params, function(data){
	      $("#" + id).remove()
	    })
	  }
	}
</script>
<?endif?>
