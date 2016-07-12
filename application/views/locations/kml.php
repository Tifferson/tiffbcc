<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<kml xmlns="http://www.opengis.net/kml/2.2">
	<Document>
		<name>DieselGreen Fuels Partner Restaurants</name>
	    <description>Patronize these restaurants for </description>
		<open>1</open>
<? foreach($locations->result() as $location) : ?>
	<? if(!empty($location->latitude) && !empty($location->longitude)) : ?>
	<Placemark>
	  <name><?= $location->dgf_name ?></name>
	  <Point>
	    <coordinates><?=trim($location->latitude)?>,<?=trim($location->longitude)?>,0</coordinates>
	  </Point>
	</Placemark>	
	<? endif ?>
<? endforeach ?>

	</Document>
</kml>