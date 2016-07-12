<h1>Collection Routes</h1>

<div class="buttons">
<button id="reorder" type="button" onclick="reorder()">Reorder Routes</button>
<button style="display:none" id="done" type="button" onclick="done_reordering()">Done Reordering</button>
</div>
<br/>
<br/>
<? $num_not = $not_on->num_rows(); ?>

<? if($num_not > 0) : ?>
<p>You have <?=colorize($num_not,'red')?> location<?=$num_not == 1 ? '' : 's' ?> not on a route. 
	<?=anchor("routes/unassigned","view locations")?>
</p>
<? endif?>

<ul id="route_list">
<? foreach($routes->result() as $route) : ?>

	<li id="order_<?=$route->id?>">

     <span style="font: 1.2em boldest;"><?= $route->name ?></span>
    <br/>
     

      <span class="subinfo">
        <?=$route->description?>
      </span>	
<br/>
      <?=anchor("routes/edit/" . $route->id, 'edit')?> | 
	    <?=anchor("routes/printable/" . $route->id, 'print')?> |
       <?=anchor("pickups/enter_route/" . $route->id, 'enter collection')?> |
        <?=anchor("pickups/show_dates/" . $route->id, 'view collections')?> |
      <a href="#deactivate" onclick="deactivate_route(<?=$route->id?>)">deactivate</a>
     



  </li>
<?endforeach?>
</ul>

<script type="text/javascript">

  function reorder(){
    $("#route_list li").addClass('sortable_box')
    $("#route_list").sortable()
    $("#reorder, #done").toggle()
  }

  function done_reordering(){
      $("#route_list li").removeClass('sortable_box')
      params = $("#route_list").sortable('serialize')
      $("#route_list").sortable('destroy')
      $.post("<?=site_url('routes/reorder_display')?>", params, function(data){})

      $("#reorder, #done").toggle()
  }

  


  function deactivate_route(id){
    if(confirm("Deactivate this route?")) {
        $.post("<?=site_url('routes/deactivate')?>",
                {'id':id}, function(data){
                      $("#order_" + id).remove()
                })

      }
  }
</script>
