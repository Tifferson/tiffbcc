<?
$today=date("m/d/Y");
$past=date("m/d/Y", strtotime('-90 days'));

?>
<h1>Closed Work Orders</h1>

<form id="dates">
	<input type="hidden" name="dates_sent" value="1" />
	Start Date: <input class="date_pick" type="text" value="<?=$past?>" name="start_date" />
	&nbsp;&nbsp;&nbsp;
	End Date: <input class="date_pick" type="text" value="<?=$today?>" name="end_date" />
	&nbsp;&nbsp;&nbsp;
	<button type="button" onclick="get_wos()">get work orders</button> (by due date)
  &nbsp;&nbsp;&nbsp;
  <span id="num_found"></span>
</form>


<p class="hide">Select date range then click &quot;get work orders&quot;</p>

<div id="result_area">

</div>

<script type="text/javascript">
	
	function get_wos(){
		$(".hide").hide();
		start = $("input[name=start_date]").val()
		end = $("input[name=end_date]").val()
		
		if(start == '' || end == ''){
			alert("One or more date fields empty")
		}
		else{
			$.post("<?=site_url('Work_orders/get_closed')?>", 
				{'start_date':start, 'end_date':end},
				function(data){
					$("#result_area").empty().html(data)
          $(".tablesorter").tablesorter()
				}, 'html'
				);
		}
	}

</script>

