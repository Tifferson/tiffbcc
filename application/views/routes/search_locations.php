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
	
</script>
<div id="filters" style="">
	<form id="search_form" action="">
		<!--  <legend>Filters</legend> -->
			<b style="font-size:1.1em">Search Locations:</b>                    
			<input type="text" name="s" />
			<?= isset($routes) ? '<br/>' : '' ?>
				
			<input id="all" type="checkbox" name="search_all" 
				onchange="search()" onclick="search()"
				/> Search All Fields 
      <br/>

			<a href="#unassigned" onclick="get_unassigned()"> 
        Get Unassigned Locations</a> 


			<img id="act" style="display:none;width:15px;height:15px;" 
				src="/dg_assets/images/activity_indicator.gif" />

		<p>
			Select: <a href="#all" onclick="check('all')">All</a>, 
					<a href="#none" onclick="check('none')">None</a>
			&nbsp;&nbsp;
			<br/>
      
    <? /*
			<select onchange="location_action(this)">
				<option>Actions</option>
				
				<option value="add_to_route">Add to route</option>

			</select>
      */ 
    ?>

      <button type="button" onclick="location_action()">Add selection to route</button>
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
	<div id="search_results" style="height:100%">
 
	</div>
</div>
<div id="dialog" style="display:none">
	
</div>



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
     //params = $("#search_form").serialize()
     params = { 's':$('input[name=s]').val(),             
                'customer':'1'}

    if( $('input[name=search_all]').attr('checked') ){
      params['search_all'] = 1
    }
       

		 ajax_requests.push(
			$.post(url, 
        params, 
				function(data){ $("#search_results").html(data); 
			//	make_selectable(); 
				$("#act").hide();}
			)
		 )
	  }else{
		//$.post("<?=site_url('locations/save_query')?>", $("#search_form").serialize())
	 }
  }


  function get_unassigned(){
     $("#act").show()
		 url = "<?= site_url('/routes/get_unassigned') ?>";

		 ajax_requests.push(
			$.post(url, 
        {}, 
				function(data){ $("#search_results").html(data); 
			//	make_selectable(); 
				$("#act").hide();}
			)
		 )

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

  }


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
  
  

  

</script>

