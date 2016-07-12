<script type="text/javascript">
	$(document).ready(function(){
		$("#ignored").click(function(){ search() })
	}) 
	
	function check(which){
		if(which == 'all'){
			e = $('input[type="checkbox"].location')
			e.attr("checked", 'checked')
			$("#num_selected").text(e.size())
		}else if(which == 'none'){
			e = $('input[type="checkbox"].location')
			e.attr("checked", '')
			$("#num_selected").text(0)
		}else if(which == 'customers'){
			e = $('tr.customer input[type="checkbox"]')
			e.attr("checked", 'checked')
			$("#num_selected").text(e.size())
		}
		else if(which == 'leads'){
			e = $('tr.lead input[type="checkbox"]')
			e.attr("checked", 'checked')
			$("#num_selected").text(e.size())	
		}
		
	}
	
	function update_count(e){
		current = parseInt($("#num_selected").text())
		if($(e).attr("checked")){
			$("#num_selected").text(current+1)
		}else{
			$("#num_selected").text(current-1)
		}
	}
	

	function location_action(s){
		action = $(s).val()
		$(s).val('')
		
		ids = []
		$("input.location[checked=true]").each(function(){ ids.push(this.name)})
		
		if(ids.length > 0) {
			switch(action)
			{
				case 'make_leads':
					
					break;
				case 'add_mine':
					$.post("<?=site_url('leads/make_my_leads')?>",{'ids[]':ids},
						function(){window.location="<?=site_url('leads/mine')?>"})
					break;	
			}//endswitch
		}//endif
	}
</script>
<div id="filters" style="">
	<form id="search_form" action="">
		<!--  <legend>Filters</legend> -->
			<b style="font-size:1.1em">Search Locations:</b>                    
			<input type="text" name="s" />
			<?if(!isset($routes)) : ?>
			<input id="ignored" type="checkbox" name="ignored" 
				onchange="search()" onclick="search()"
				/> Include Ignored
			<?endif?>
			<?= isset($routes) ? '<br/>' : '' ?>
				
			<input id="all" type="checkbox" name="search_all" 
				onchange="search()" onclick="search()"
				/> Search All Fields 
			<img id="act" style="display:none;width:15px;height:15px;" 
				src="/dg_assets/images/activity_indicator.gif" />

		<p>
			Select: <a href="#all" onclick="check('all')">All</a>, 
					<a href="#none" onclick="check('none')">None</a>,
					<a href="#customers" onclick="check('customers')">Customers</a>,
					<a href="#leads" onclick="check('leads')">Leads</a>
			&nbsp;&nbsp;
			<?= isset($routes) ? '<br/>' : '' ?>
			<select onchange="location_action(this)">
				<option>Actions</option>
				
				<?if(isset($routes)) : ?>
					<option value="add_to_route">Add to route</option>
				<?else : ?>
					<option value="add_mine">Add to my leads</option>
				<?endif?>
			</select>
			&nbsp;&nbsp;
			<span id="num_selected">0</span> selected of <span id="num_total">&nbsp;</span>
		</p>
	</form>
</div>
<div id="search_area">
   <table id="header">
		<tr>
		  <th>Locations</th>
		</tr>
	</table>
	<div id="search_results">
 
	</div>
</div>
<div id="dialog" style="display:none">
	
</div>

<!--
<div id="edit_pane" style="display:none;">
	 <form id="edit_form" action="<?=site_url('locations/save_location')?>" method="POST">
		<div id="edit_fields">
		
		</div>
		<p style="text-align:center">
		  <input type="submit" name="submit" onclick="return check_event();" value="Save Location" style="margin:5px;"/> 
		  <input type="submit" value="Cancel" onclick="cancel(); return false;" style="margin:5px;"/> or 
		  <input type="submit" name="submit" value="Delete Location" onclick="return confirm('Delete this location?')"  style="margin:5px;" /> 
		</p>
	 </form>
</div>
-->

<?= $this->load->view("locations/check_event_js") ?>
<script type="text/javascript">
  previous = $("#search_results table tr").get(0)
  ajax_requests = []      
	
  function search(){
	search_handler({})
  }
	
  function search_handler(e){
	 var key;

	  $.each(ajax_requests, function(){ this.abort() })          
	  ajax_requests.length = 0
	
	  s = $("input[name=s]").val()
	  if(s.replace(/\s/g,'')  != ""){
		 
		 $("#act").show()
		 url = "<?= site_url('/locations/search') ?>";
		 ajax_requests.push(
			$.post(url, 
			$("#search_form").serialize(), 
				function(data){ $("#search_results").html(data); 
				<?if(!isset($routes)) : ?>
				make_selectable(); 
				<?endif;?>
				$("#act").hide();}
			)
		 )
	  }else{
		$.post("<?=site_url('locations/save_query')?>", $("#search_form").serialize())
	 }
  }

  function disableEnterKey(e)
  {
	   var key;     
	   if(window.event)
			key = window.event.keyCode; //IE
	   else
			key = e.which; //firefox     

	   return (key != 13);
  }

  function make_selectable(){
	 $('#search_results table tr').hover(function(){
		$(this).addClass("hovering");          
	 }, 
	 function(){ 
		$(this).removeClass("hovering")
	 });
	// $('#search_results table tr').click(select)
  }

/*
  function select(element){
	 id = $(this).attr('id')
	 query = $('input[name=s]').val()
	 , function(data){
		window.location = "<?=site_url("locations/edit/")?>" + '/' + id
		}, 'html')
	 
	  
/ *	  $("#act").show()
	  $("#search_area").hide()
	  $.post("<?=site_url('locations/get_edit')?>" + '/' + id, {}, function(data){
		$("#edit_fields").html(data)
		$("#edit_pane").show()
	//	$("#detail_content").accordion({autoHeight: true});
		$("#act").hide()
	  }, 'html')
	//  load("/dgf/locations/get_edit/" + id)
*
  }
 */

  function save(){

  }

  function cancel(){
	  $("#edit_pane").hide()
	  $("#search_area").show()               
  }
  
  $(document).ready(
  function initialize(){
	//attach event handlers
	$("input[name=s]").keyup(search_handler).keypress(disableEnterKey).focus();
	<?if(isset($current_search) && $current_search) : ?>
		search_info = <?=json_encode($current_search)?> ;
		
		$("input[name=s]").val(search_info.s)
		if(search_info.search_all == "1" ) $("input[name=search_all]").attr("checked", "checked");
		if(search_info.ignored == "1")  $("input[name=ignored]").attr("checked", "checked");
		
		search()
	<?endif?>
  })
  
  

  
  /*
  
var map;
var geocoder;

function init_map(){
	map = new GMap2(document.getElementById("map_canvas"));
	map.setCenter(new GLatLng(30.251409,-97.697181), 12);
	map.addControl(new GMapTypeControl());
	//map.addControl(new GLargeMapControl());
	//map.addControl(new GSmallZoomControl());
	map.addControl(new GScaleControl());
	map.addControl(new GSmallMapControl());
	geocoder = new GClientGeocoder();
	
}
	
function addAddressToMap(id, address){
 geocoder.getLatLng(
	address,
	function(point) {
	  if (!point) {
		alert(address + " not found")
	  } else {
		add_to_map(id, point);
	  }
	}
  );
}

function add_to_map(id, point){
	var marker = new GMarker(point);
	map.addOverlay(marker);
	
	$.post('/dgf/routes/get_address/' + id, {}, function(data){
				html = "<p style='padding:5px'><b>" + data.dgf_name + "</b><br/>" + data.dgf_address + "<br/>" + data.city + ", " + data.zip + "<br/>Containers: " + data.container_type + "<br/>Contact: " + data.contact_name + "<br/>Phone: " + data.phone + "</p>"
				marker.bindInfoWindowHtml(html)
				map.setCenter(point);
	}, 'json')
}*/
</script>

