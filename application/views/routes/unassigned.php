<h1>Unassigned Locations</h1>
<p>The following locations are not on any scheduled route.</p>
<ul>
<? foreach($not_on->result() as $loc) : ?>
	<li id="<?=$loc->id?>"><?=anchor("locations/edit/" . $loc->id, $this->location->human_name($loc))?>
      <div id="atr" class="buttons">
        <button onclick="$('#atr').hide();$('#add_to_route').show('slow');" type="button">[+] Add to route</button>
      </div>
      <br/>
      <div id="add_to_route" style="display:none">
	      <p><b>Suggested Route(s):</b> <?= $suggest[$loc->id] ? $suggest[$loc->id] : 'no suggestion'?></p>
        <table>
        <tr>
        <td>
                    Route:   
        </td>
        <td>
                <?= select($loc->id . '_route', $routes) ?>
        </td>
        </tr>
        </table>

        <div class="buttons">
	        <button onclick="add_to_route(<?=$loc->id?>)" type="button">Add to Route</button>
        </div>
        <br/>
        <br/>
      </div>
      <br/>
  </li>
<? endforeach ?>
</ul>

<script type="text/javascript">
  function add_to_route(loc_id){

    route_id = $("select[name=" + loc_id + "_route]").val();

    if(route_id == ""){ 
      alert("No route selected")
    }
    else{
      $.post("<?=site_url('routes/add_location')?>", {'location_id':loc_id, 'route_id':route_id}, 
          function(data){ $("#" + loc_id ).remove() })
      $('#add_to_route').hide('slow')
      $('#atr').show()
    }  
  }
</script>
