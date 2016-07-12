<? $i = 0; ?>
<table>
  <? foreach($locations->result() as $loc) { 
      if(!empty($loc->dgf_name)){
      ?>
      <tr id="<?=$loc->id?>" class="<?= $i % 2 == 0 ? "row" : "row_alt" ?>">
          <td><?= preg_replace("/$q/i", "<b>$0</b>", $loc->dgf_name) ?>
			  <?= preg_replace("/^$q/i", "<b>$0</b>", $loc->dgf_address) ?>
		  </td>
      </tr>
	  	 <? $i++ ?> 
  <? }} ?>
</table>