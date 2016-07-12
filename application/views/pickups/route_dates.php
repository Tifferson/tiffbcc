<h1><?=$route ? $route->name : "Manual Pickups"?></h1>


<? if($dates->num_rows() > 0) : ?>
<ul>
  <? $i = 0 ?>
  <?foreach($dates->result() as $date) : ?>
    <? $d = explode(' ', $date->date); ?>
    <? $r_id = $route ? $route->id : 0 ?>
    <li id="<?=$i?>">
      <?=mysql_to_mdy($date->date)?> (<?= anchor('pickups/show_route/' . $r_id . "/" . $d[0] , 'view'  )?> | 
      <a href="#delete" onclick="delete_run(<?=$route->id?>,'<?=$d[0]?>', <?=$i?>)">delete</a>)
    </li>
  <? $i ++ ?>
  <?endforeach?>
</ul>
<? else : ?>
No pickups entered for this route.
<? endif?>


<script type="text/javascript">

function delete_run(route_id, date, index){

  if(confirm("Delete this collection?")){
    params = {'route_id':route_id, 'date':date}
    $.post("<?=site_url('pickups/delete_run')?>", params, function(){
      $("#" + index).remove()
    })
  }

}

</script>
