<h1><?=$route->name?> (<?=anchor('routes/printable/' . $route->id, 'print')?>)</h1>

<!-- locations search box -->
<div style="float:right; width:400px;">
  <h2>Add Locations to Route</h2>
	<? $this->load->view("routes/search_locations", array('routes' => 1)) ?>
	<div id="search_results">
	</div>
</div>
<!-- end locations search box -->


<!-- route data and stops -->
<div >
<form action="<?=site_url('routes/update')?>" method="POST" onsubmit="return on_save();">

  <table>
    <tr>
        <td>Name:</td>
        <td><input type="text" name="name" value="<?=$route->name?>" /></td>
    </tr>

    <tr>
      <td>Next Date Scheduled:</td>
      <td><input type="text" class="date_pick" name="next_date" 
                  value="<?=date_to_mdy($route->next_date, false)?>" /></td>
    </tr>
    <tr>
        <td><input type="checkbox" 
              <?=$route->frequency > 0 ? 'checked="checked"' : ''?>
              onclick="toggle_repeat(this)"/>Repeat:</td>
        <td>every 
            <input <?=$route->frequency > 0 ? '' : 'disabled="true"'?> 
              type="text" name="frequency" value="<?=$route->frequency?>" size="4"/>   
        weeks</td>
    </tr>

    <tr>
        <td>Description:</td>
        <td><textarea name="description" cols="50"><?=$route->description?></textarea></td>
    </tr>
   </table>

  <div class="buttons">
    <button type="submit" name="submit">Save Route</button>
    <button id="reorder" type="button" onclick="reorder_stops()">Reorder Stops</button>
    <button style="display:none" id="done" type="button" onclick="done_reordering(<?=$route->id?>)">Done Reordering</button>
  </div>
  <br/><br/>


  <!-- list of orderable stops -->
  <ol id="stops">
  <? if($stops) : ?>
  <? $this->load->view("routes/stops_li", array('stops' => $stops)) ?>
  <? endif ?>
  </ol>

  <input type="hidden" name="id" value="<?=$route->id?>" />
  <input type="hidden" name="order" value='' />




</form>
<!-- END list of orderable stops -->
</div>
<!-- end route data and stops -->



<script type="text/javascript">
	$(function(){$("#stops").sortable()})

  function toggle_repeat(input){
      if($(input).attr("checked")){
        $("input[name=frequency]").attr('disabled','')
      }
      else{
        $("input[name=frequency]").attr('disabled','true')
      } 
  }


  function reorder_stops(){
    $("#stops li").addClass('sortable_box')
    $("#stops").sortable()
    $("#reorder, #done").toggle()
  }

  function done_reordering(id){
      $("#stops li").removeClass('sortable_box')
      params = $("#stops").sortable('serialize')
      params = params + "&id=" + id
      $.post("<?=site_url('routes/reorder_stops')?>", params, function(data){})
      $("#reorder, #done").toggle()
  }


		
	function on_save(){
    $('input[name=order]').val($("#stops").sortable('serialize'))
    return true;
    //$("#route_form").submit()
	}

	function location_action(){
//		action = $(s).val()
//		$(s).val('')
		
		ids = []
		$("input.location[type=checkbox]:checked").each(function(){ ids.push(this.name)})
		

		if(ids.length > 0) {
//		alert(ids)
//			switch(action)
//			{
//				case 'add_to_route':
          add_to_route(ids);
//					break;	
//			}//endswitch
		}//endif
	}

  function add_to_route(ids){
  
    r_id = $('input[name=id]').val();

    if(confirm("Add location(s) to route?")) {
		  $.post("<?=site_url('routes/add_batch_to_route')?>",{'ids[]':ids, 'route_id':r_id},
			  function(data){
           $("#stops").append(data)
           alert("Location(s) add to route")
        },'html')
    }

  }

  function remove(id){
     r_id = $('input[name=id]').val();

    if(confirm("Remove location from route?")) {
		  $.post("<?=site_url('routes/remove_location')?>",{'location_id':id, 'route_id':r_id},
			  function(data){
           $("#order_" + id).remove()
        })
    } 

  }


	
</script>

