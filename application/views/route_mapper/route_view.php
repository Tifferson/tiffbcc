<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Maps JavaScript API Example</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAmFBo_rh8OklPO40lirugNBQ9qwQALdZeh8vyqDognjFVK0TWDxQtkB3w7JSJ026xmMr0529RlCXHew"
      type="text/javascript"></script>
    <script type="text/javascript">

    //<![CDATA[

	waypoints = <?= $waypoints ?>
	//["5217 Cesar Chavez, Austin, TX 78702", "4801 avenue H, austin, tx, 78751", "austin, tx"]


	

  function initialize() {
      map = new GMap2(document.getElementById("map_canvas"));
      map.setCenter(new GLatLng(30.251409,-97.697181), 15);
      directionsPanel = document.getElementById("route");
      directions = new GDirections(map, directionsPanel);
      directions.loadFromWaypoints(waypoints);
	  //directions.load("from:   to: ");
    }


    //]]>
    </script>
  </head>
  <body onload="initialize()" onunload="GUnload()">
  
	<div style="width:400px; float:left">
		Your Stops:
		<ul>		
		<?foreach($addresses as $address) : ?>
			<li><?=$address?></li>
		<?endforeach;?>
		</ul>
		<div id="route" ></div>  
	</div>
	<div  style="width: 500px; min-height: 500px;float:left">
 	 	<div id="map_canvas" style="width: 500px; height: 500px;"></div>
		
	</div>
  </body>
</html>
