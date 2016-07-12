<h1>Incoming Fuel Log</h1>

<form id="dates">
	<input type="hidden" name="dates_sent" value="1" />
	Start Date: <input class="date" type="text" name="start_date" />
	&nbsp;&nbsp;&nbsp;
	End Date: <input class="date" type="text" name="end_date" />
	&nbsp;&nbsp;&nbsp;
	<a href="#refresh" onclick="get_incoming()">get deliveries</a>
</form>


<p class="hide">Select date range then click &quot;get deliveries&quot;</p>

<div id="result_area">

</div>


<script type="text/javascript">
	function delete_incoming(id){
		if(confirm("Delete incoming fuel delivery?")){
			$.post("<?=site_url("fuel/delete_incoming")?>", {'id':id},
				function(data){
					$("#" + id).remove()
				})
		}
	}
	
	$( function(){
		$(".date").datepicker();
	})
	
	function get_incoming(){
		$(".hide").hide();
		start = $("input[name=start_date]").val()
		end = $("input[name=end_date]").val()
		
		if(start == '' || end == ''){
			alert("One or more date fields empty")
		}
		else{
			$.post("<?=site_url('fuel/get_incoming')?>", 
				{'start_date':start, 'end_date':end},
				function(data){
					$("#result_area").empty().html(data)
				}, 'html'
				);
		}
	}
	
</script>