
<h2>DieselGreen Fuels collects used fryer oil from 140 restaurants!</h2>
<p>
<a style="font-size:1.2em;font-weight:bold" href="http://maps.google.com/maps/ms?hl=en&ie=UTF8&msa=0&msid=106701950445774864366.00043cba0ab06fb5f5121&ll=30.252246,-97.746391&spn=0.122186,0.2635&z=13">See them on a map</a>
</p>

<div style="padding:20px;border:1px solid #666;background:#ccc">
<h3>Our featured restaurant of the month is:</h3>
<a href="http://www.terraburgeraustin.com">
				<img border="0" src="http://www.terraburgeraustin.com/library/images/common/logo.gif" alt="Terra Burger" /> </a>
<br/>


<h3>More Info from TerraBurger:</h3>
	<p style="font-style:italic">
TerraBurger is located on the UT "Drag", at 2522 Guadalupe in Austin, TX, (next to the Hole in the Wall.) We have dine in and a to-go window, and we also have parking in the back of the building, and drive-up parking where you can get a juicy organic burger without getting out of your car.
<br/>
Simply put, TerraBurger's mission is to provide a more wholesome alternative to those who love a good fast food burger by using only all natural and organic ingredients. We're also committed to doing so in an environmentally friendly fashion by incorporating "green" practices in everything we do.
</p>
</div>

<table >
<? foreach($locations->result() as $loc) : 
	if(!empty($loc->dgf_name)) : ?>      
      <tr id="<?=$loc->id?>" >
          <td style="border:1px solid black; padding:5px;">
		  
			<?if( !empty($loc->website) ) : ?>
				<?= anchor(make_url($loc->website), clean_txt($loc->dgf_name)) ?>
			<?else : ?>
				<?= clean_txt($loc->dgf_name) ?>
			<?endif?>

			
			
			
			<div class="subinfo; style:block">
				<?= clean_txt($loc->dgf_address) ?>, 
				<?= clean_txt($loc->city) ?>, <?=  $loc->state ?> <?= $loc->zip ?>
			</div>
			
		  </td>
      </tr>

  <? endif;
	endforeach; ?>
</table>