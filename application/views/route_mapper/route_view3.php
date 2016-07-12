<html>
<head>
	<title>DGF Route Mapper</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAmFBo_rh8OklPO40lirugNBQ9qwQALdZeh8vyqDognjFVK0TWDxQtkB3w7JSJ026xmMr0529RlCXHew"
      type="text/javascript"></script>
    <script src="/dg_assets/jquery-1.2.6.min.js"></script>
	<script src="/dg_assets/jquery-ui.min.js"></script>
    <script type="text/javascript">
    //<![CDATA[
	var addresses = <?=$json_addresses?>;
	var map;
	var geocoder;
	
	function initialize(){
		map = new GMap2(document.getElementById("map_canvas"));
		map.setCenter(new GLatLng(30.251409,-97.697181), 12);
		map.addControl(new GMapTypeControl());
		map.addControl(new GLargeMapControl());
		//map.addControl(new GSmallZoomControl());
		map.addControl(new GScaleControl());
		//map.addControl(new GSmallMapControl());
	 	geocoder = new GClientGeocoder();
	
	}
	
		//body onload
	$(function(){
			$('#stops').sortable();
			initialize();
	})	
    //]]>
    </script>
	<style type="text/css">
		#locations_panel{
			float:left;
			width:300px;
			height:600px;
			overflow:auto;
		}
		
		#map_panel, #map_canvas{
			width: 400px;
			height: 400px;
		}
		
		#map_panel{
			float:left;
		}
		
				
		a.clean_link{
			text-decoration: none;
			color: blue;
		}
		
		a.active_link{
			text-decoraion: underline;
			color: black;
		}
		
		a.clean_link:hover{
			color: #CCC;
		}
		
		/* Locations list */
		dl{
			margin-top: 0;
		}
		dl a{
			color: black;
		}
		dl a:hover{
			font-weight: bold;
		}
		dt{
			padding: 2px 5px;
			background: #CFD4E6;
		}
				
		dd{
			background: #CFD4E6;
			margin:0;
			padding: 2px 5px 5px 10px;
			border-bottom: 2px solid white;
			font-size: 0.8em;
		}
		
		dt.alt, dd.alt{ 
			background: #DDD;
		}
		/* END Locations List */
		


	</style>
</head>
<body onunload="GUnload()">
	<!-- header -->
	<div style="clear:both">
	<h1>Week <?=$week?> : Route <?=$route?></h1>
		<img id="act" src="/dg_assets/activity_indicator.gif" style="display:none" />
	</div>
	
	<!-- interface --> 
	<div id="canvas" style="width:1000px; height:600px">
	<div id="locations_panel">
	</div>
	
	<div id="map_panel">
		<div id="map_canvas">
		</div>
	</div>
	

	</div>

</body>
</html>