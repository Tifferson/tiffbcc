<h1>Currently Open Routes</h1>
<ul>
<? foreach($open->result() as $route) : ?>
	<li> <?=anchor("pickups/enter_route/" . $route->id, $route->name)?>
		<br/>
    Scheduled for: <b><?=date_to_mdy($route->next_date)?></b>
    <br/>
		<span class="subinfo">
			<?=$route->description?>
		</span>	
	</li>
<?endforeach?>

</ul>


<? if( isset($all_routes) ) : ?>
<h1>Enter Past Collection</h1>
<form>
  <?= select('past_route', $all_routes) ?>
</form>

<script type="text/javascript">

  function past_route(){

    id = $('select[name=past_route]').val()
    url = "<?= site_url('pickups/enter_route') ?>"
    if( id != '' ){
      window.location = url + '/' + id
    }

  }

  $( function(){ 
      $('select[name=past_route]').click( function(){ past_route() })
   })

</script>
<? endif ?>
