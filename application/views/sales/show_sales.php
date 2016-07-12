<h1>Biodiesel Sales</h1>

<form id="dates">
	<input type="hidden" name="dates_sent" value="1" />
	Start Date: <input class="date_pick" type="text" name="start_date" />
	&nbsp;&nbsp;&nbsp;
	End Date: <input class="date_pick" type="text" name="end_date" />
	&nbsp;&nbsp;&nbsp;
	<a href="#refresh" onclick="get_sales()">get sales</a>
</form>


<p class="hide">Select date range then click &quot;get sales&quot;</p>

<div id="result_area">

</div>

<script type="text/javascript">
	
	function get_sales(){
		$(".hide").hide();
		start = $("input[name=start_date]").val()
		end = $("input[name=end_date]").val()
		
		if(start == '' || end == ''){
			alert("One or more date fields empty")
		}
		else{
			$.post("<?=site_url('sales/get_sales')?>", 
				{'start_date':start, 'end_date':end},
				function(data){
					$("#result_area").empty().html(data)
				}, 'html'
				);
		}
	}

</script>

