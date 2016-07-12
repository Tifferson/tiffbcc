<html>
<head>
	<title>DGF Route Mapper</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAmFBo_rh8OklPO40lirugNBQ9qwQALdZeh8vyqDognjFVK0TWDxQtkB3w7JSJ026xmMr0529RlCXHew"
      type="text/javascript"></script>
    <script src="/dg_assets/jquery-1.2.6.min.js"></script>
	<script src="/dg_assets/jquery-ui.min.js"></script>
    <script type="text/javascript">
    //<![CDATA[
	var map;
	var geocoder;
	var selected_addresses = [];
	var current_address;
	var current_addres_name;
	var current_address_id;
	var current_week = 1;
	var faq;
	
	var route = []
	
	Array.prototype.remove=function(s){
		var i = this.indexOf(s);
		if(i != -1) this.splice(i, 1);
	}

	function initialize(){
		map = new GMap2(document.getElementById("map_canvas"));
		map.setCenter(new GLatLng(30.251409,-97.697181), 12);
		map.addControl(new GMapTypeControl());
		map.addControl(new GLargeMapControl());
		//map.addControl(new GSmallZoomControl());
		map.addControl(new GScaleControl());
		//map.addControl(new GSmallMapControl());
	 	geocoder = new GClientGeocoder();
		load_route(current_week)
	}

	function showAddress(dom_id, id, address, name) {
	  geocoder.getLatLng(
	    address,
	    function(point) {
	      if (!point) {
			correct_address(id, address);
	      } else {
			add_to_route(dom_id, id, name, address, point);
	      }
	    }
	  );
	}
	
	
	//takes an address from geocoder and adds it to the map and the route list
	function add_to_route(dom_id, id, name, address, point){
		selected_addresses.push(address)
		route[id] = {'name':name, 'address':address, 'point':point}
		map.setCenter(point, map.getZoom());
		var marker = new GMarker(point);
		map.addOverlay(marker);
		$.post('/dgf/route_mapper/get_address/' + id, {}, function(data){
					faq = data
					html = "<p style='padding:5px'><b>" + data.name + "</b><br/>" + data.address + "<br/>" + data.city + ", " + data.zip + "<br/>Containers: " + data.containers + "<br/>Contact: " + data.contact_name + "<br/>Phone: " + data.phone + "</p>"
					marker.bindInfoWindowHtml(html)
		}, 'json')
		//marker.openInfoWindowHtml(html)
		$("#" + dom_id).append(route_li(id, name, address))
		$(".di_" + id).hide()
	}
	
	function route_li(id, name, address){
		string = "<li id='stop_" + id + "' >"+ name +" (<a href='#null' onclick=\"remove_stop('" + id + "','" + address + "')\">remove</a>)</li>";
		return string;
	}
	
	function correct_address(id, address, name){
		current_address_id = id
		current_address = address
		current_address_name = name
		//alert(address + " not found. Please correct.");
		$("#fix_address").focus().show('slow')
		$("input[name=new_address]").val(address)
		
	}

	function try_again(){
		address = $("input[name=new_address]").val()
		current_address = address
		showAddress('none', current_address_id, address, current_address_name)
	}
	
	function save_address(){
		$("#act").show()
		update_address(current_address_id, current_address)
	}
	
	function update_address(id, address){
		$.post('/dgf/route_mapper/correct_address', {'id':id, 'address':address},
			function(data, textStatus){
				$("#act").hide()
				$("#fix_address").hide()
			}) 
	}
	
	function edit_address(id){
		$.post('/dgf/route_mapper/get_address/' + id, {}, function(data){
			correct_address(data.id, data.address, data.name)
		}, 'json')
	}
	
	function remove_stop(id, address){
		selected_addresses.remove(address)
		$("#stop_" + id).remove()
		$(".di_" + id).show()
	}
	
	function submit_addresses(){
		for(i=0; i<selected_addresses.length; i++){
			//alert(selected_addresses[i])
			index = i+1
			$("#form").append("<input name='address[" + index + "]' type='hidden' value='" + selected_addresses[i] + "'/>") 
		}
		$("#form").submit()
	}
	
	function switch_week_to(week_id){
		//save_current_week(current_week)
		clear_weeks()
		$("#week_link_" + current_week).attr('class', 'clean_link')
		load_route(week_id)
		current_week = week_id
	}
	
	function clear_weeks(){
		$("#weeks ul").each(function(){
			$(this).empty()
		})
	}
	
	function save_current_week(){
		$("#act").show()
		data = {}
		$("#weeks ul").each(function(index, element){
			name = $(this).attr('id')
			if(data[name+'[]'] == undefined) data[name+'[]'] = []
			$("#" + name + " > li").each(function(i, e){
				data[name+'[]'][i] = $(this).attr('id').replace("stop_", "")
			})
		})
		data["week_id"] = current_week
		$.post('/dgf/route_mapper/save_week', data, function(){ $("#act").hide() } )
		faq = data
	}
	

	
	function load_route(id){
		$("#week_link_" + id).attr('class', 'active_link')
		$.post('/dgf/route_mapper/get_route', {'id':id},
		function(data){
			$.each(data, function(index, value){
				_index = index
				$.each(value, function(){ showAddress(_index, this.loc_id, this.address, this.name) })
			})
		}, 'json')
	}
	
	//connect sortable routes
	connected = ['#none', '#monday', '#tuesday', '#wednesday', '#thursday', '#friday', '#saturday', '#sunday']
	$(function(){
		for(i=0; i<connected.length; i++){
			$(connected[i]).sortable({connectWith: connected})
		}
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
		
		#map_panel{
			float:left;
			width: 400px;
			height: 400px;
		}
		
		
		#route_panel{
			float:left;
			width:290px;
			height:600px;
			padding: 5px;
			background: #F0F0F0;
			overflow:auto;
		}
		
		#route_panel h3{
			margin: 0;
			padding: 0;
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
		
		#route_panel h2{
			margin: 0;
			padding: 0;
		}

	</style>
</head>
<body onload="initialize()" onunload="GUnload()">
	<div style="clear:both">
	<h1>Route Mapper</h1>

	<p>Click on addresses to add them to the map.  When you've added enough, click 'Get Directions!' to get directions.<br/><a href="javascript:submit_addresses()">Get Directions!</a></p>		
	</div>
	<div id="canvas" style="width:1000px; height:600px">
	<div id="locations_panel">
		<dl>
			<dt><a href="javascript:showAddress('home','<?= $dgf_address ?>', 'DieselGreen Fuels')">DieselGreen Fuels</a></dt>
			<dd><?= $dgf_address ?></dd>
			<? $i = 0; ?>
			<? foreach($restaurants->result() as $row) : ?>
			<!-- preg_replace('/\s[SB|NB|EB|WB]/', '',  -->
				<? $class = $i % 2 == 1 ? '' : 'alt'; ?>
				<? $address = empty($row->corrected_address) ? $row->address : $row->corrected_address; ?>
				<? $address = clean_address($address) . ", " . $row->city . ", TX " . $row->zip ?>
				<dt class="di_<?=$row->id?> <?=$class?>"><a href="javascript:showAddress('none', '<?=$row->id?>', '<?= $address ?>', '<?= addslashes($row->name )?>')"><?= $row->name ?></a> 
					(<a href="#null" onclick="edit_address('<?=$row->id?>')">edit</a>)
				</dt>
				<dd class="di_<?=$row->id?> <?=$class?>"><?= $address ?></dd>
				<? $i = $i+1; ?>
			<? endforeach; ?>
		</dl>
	</div>
	
	<div id="map_panel">
		<div id="map_canvas" style="width: 400px; height: 400px">
		</div>
	</div>
	
	<div id="route_panel">
		<form id="fix_address" style="display:none">
			<input name="new_address" type="text" style="width:100%"/><br/>
			<a href="#NULL" onclick="try_again()">Add Address Again</a><br/>
			<a href="#NULL" onclick="save_address()">Save Address</a><br/>
		</form>
		<img id="act" src="/dg_assets/activity_indicator.gif" style="display:none" />
		<hr/>
		<h3>Week
			<?for($i=1; $i<=8; $i++){?>
				<a href="#null" id="week_link_<?=$i?>" class="clean_link" onclick="switch_week_to(<?=$i?>)"><?=$i?></a>
			<?}?></h3>
			<a href="#null" class="clean_link" onclick="save_current_week()">save current week</a>
		<hr/>
		<div id="weeks">
			<ul id="none">	
			</ul>
			<? foreach(array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') as $day){ ?>
				<h3><?=$day?></h3>
				<ul style="min-height: 5px" id="<?=strtolower($day)?>">
				</ul>
			<?}?>
		</div>
	</div>
	</div>

	<form id="form" action="/dgf/route_mapper/get_directions" method="POST">
		<input name="address[0]" type="hidden" value="<?=$dgf_address?>" />
	</form>
</body>
</html>
