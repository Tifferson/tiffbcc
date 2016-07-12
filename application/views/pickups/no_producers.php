<h1>Where's the oil?</h1>
<p>These locations have produced less than 10 gallons of oil in the past 90 days.</p>

<ul>
<? foreach($pickups->result() as $pickup) : ?>

  

  	<li><?= $pickup->dgf_name ?> 
        ( <?=anchor('locations/edit/' . $pickup->location_id, 'edit')?> | 
          <?=anchor('pickups/show_history/' . $pickup->location_id, 'pickup history')?> )
			<br/>
			<span class="subinfo"><?=loc_address($pickup)?>
      </span>
		</li>

<?endforeach?>
