<h1>Customer Acquisition by Month</h1>
<div class="c">
  <!-- BEGIN: load jquery --> 
  <script type="text/javascript" src="/dg_assets/js/jquery-1.3.2.min.js"></script> 
  <!-- END: load jquery --> 
  
  <!-- BEGIN: load extras --> 
  
  <script type="text/javascript" src="/dg_assets/js/jquery-1.3.2.min.js"></script> 
  <script language="javascript" type="text/javascript" src="/dg_assets/js/jqplot/jquery.jqplot.min.js"></script>
<link rel="stylesheet" type="text/css" href="/dg_assets/js/jqplot/jquery.jqplot.css" />
  
  <!-- END: load extras --> 
  
  <!-- BEGIN: load jqplot --> 
  <script language="javascript" type="text/javascript" src="/dg_assets/js/jqplot/jquery.jqplot.min.js"></script> 
  
  <script type="text/javascript" src="/dg_assets/js/jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="/dg_assets/js/jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="/dg_assets/js/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="/dg_assets/js/jqplot/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="/dg_assets/js/jqplot/plugins/jqplot.cursor.min.js"></script>
  
    <!-- END: load jqplot --> 

<? 	error_log("array:".print_r($graph['data'],true)); ?>
<div id="chartdiv">
<script language="javascript" type="text/javascript">
$.jqplot.config.enablePlugins = true;

$.jqplot('chartdiv',  [<?php echo $graph['linedata']; ?>],
{ axes:{
  	xaxis:{
  	    numberTicks: <?php echo count($graph['data']); ?>,
            renderer:$.jqplot.DateAxisRenderer,
            tickOptions:{
                fontSize:'10pt', 
                fontFamily:'Tahoma', 
                angle:-30,
                formatString:'%b<br />%Y'
                
            }
        },
	 yaxis:{
	    max: <?php echo ceil($graph['max']/10)*10; ?>, // this rounds to nearest 10s
	    tickInterval: 10,
	    min: <?php echo ceil($graph['min']/10)*10; ?>, // rounds to 10s
	    tickOptions:{formatString:'%d'}
	} 
  },
  series:[{color:'#5FAB78'}],
  highlighter: {sizeAdjust: 15},
  cursor: {show: false}

});


</script>
</div>

</div>

