<h1>Select Route</h1>
<ul>
<? foreach($routes->result() as $route) : ?>
	<li> <?=anchor("pickups/" . $link . "/" . $route->id, $route->name)?>
		<br/>
    Scheduled for: <b><?=date_to_mdy($route->next_date)?></b>
    <br/>
		<span class="subinfo">
			<?=$route->description?>
		</span>	
	</li>
<?endforeach?>

<? if($link == "show_dates") :?>
  <li><?=anchor("pickups/" . $link . "/0", "Manual Pickups")?></li>
<? endif ?>
</ul>
