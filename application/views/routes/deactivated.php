<h1>Deactivated Routes</h1>

<ul>
<? foreach($routes->result() as $route) : ?>

	<li id="<?=$route->id?>"> <?= $route->name ?>
		(<a href="#activate" onclick="activate_route(<?=$route->id?>)">activate</a> | 
      <a href="#delete" onclick="delete_route(<?=$route->id?>)">delete</a> 
    )
		<br/>
		<span class="subinfo">
			<?=$route->description?>
		</span>	
	</li>
<?endforeach?>
</ul>


<script type="text/javascript">
  function activate_route(id){
    if(confirm("Activate this route?")) {
        $.post("<?=site_url('routes/activate')?>",
                {'id':id}, function(data){
                      $("#" + id).remove()
                })

      }
  }

  function delete_route(id){
    if(confirm("Delete this route?")) {
        $.post("<?=site_url('routes/delete')?>",
                {'id':id}, function(data){
                      $("#" + id).remove()
                })

      }
  }
</script>
