<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>DGF Locations</title>
  <meta http-equiv="Content-Type" content="text/html; charset=US-ASCII"/>
  <style type="text/css">
      html, body{
        margin: 0;
        padding: 0;
		font-family: helvetica, verdana, sans-serif;
		font-size: 1.3em;
      }
	  
	  body{
		width: 356px;
		height: 380px;
	  }

      #filters{
        background: #99FF00;
        background: #eee;
        border: 1px solid
      }

      #filters legend{
        font-weight: bold;
      }

      #search_area{
		margin-top: 10px;
        border: 1px solid black;
      }

      table{
        margin: 0;
        padding: 0;
      }
      
      #search_results{
          overflow: auto;
          height: 400px;
      }
      #search_results table{
        width: 100%;
      }
  
      #search_results table tr{
        cursor: pointer;
      }

      #search_results table tr td{
        border-bottom: 1px solid black;
      }

      #search_area #header{
        width: 100%;
        border-bottom: 1px solid black;
      }

	.basic  {
		width: 350px;
		border: 1px solid black;
	}
	.basic div {
		background-color: #eee;
	}

	.basic p, .basic table {
		margin-bottom : 10px;
		border: none;
		text-decoration: none;
		font-weight: bold;
		font-size: 0.9em;
		margin: 0px;
		padding: 10px;
	}
	.basic a {
		cursor:pointer;
		display:block;
		padding:5px;
		margin-top: 0;
		text-decoration: none;
		font-weight: bold;
		font-size: 12px;
		color: black;
		background-color: #00a0c6;
		border-top: 1px solid #FFFFFF;
		border-bottom: 1px solid #999;
		
		background-image: url("/dg_assets/AccordionTab0.gif");
	}
	.basic a:hover {
		background-color: white;
		background-image: url("/dg_assets/AccordionTab2.gif");
	}
	.basic a.selected {
		color: black;
		background-color: #80cfe2;
		background-image: url("/dg_assets/AccordionTab2.gif");
	}

	#detail_content li{
		margin: 0;
		padding: 0;
		list-style-type: none;
	}

	ul#detail_content{
		margin: 0;
		padding: 0;
	}
	
	#message{
		padding: 10px;
		border: 1px solid black;
		font-weight: bold;
		
	}

	#fields{
		margin: 0 auto;
	}
  </style>
  <script src="/dg_assets/jquery-1.2.6.min.js" type="text/javascript"></script>
  <script src="/dg_assets/jquery.dimensions.js" type="text/javascript"></script>
  <script src="/dg_assets/jquery-ui.min.js" type="text/javascript"></script>


  <script type="text/javascript">
      previous = $("#search_results table tr").get(0)
      ajax_requests = []      
        
      function search_handler(e){
         var key;

          $.each(ajax_requests, function(){ this.abort() })          
          ajax_requests = []
          value = $("input[name=g_search]").val()
          if(value != ""){
             $("#act").show()
             ajax_requests.push(
                $.post("/dgf/locations/search", 
                        {'q':value}, 
                        function(data){ $("#search_results").html(data); make_selectable(); $("#act").hide();}
                )
             )
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
            this.style.background = "#ccc"          
         }, 
		 function(){ this.style.background = "white"});
		 $('#search_results table tr').click(select)
      }

      function select(element){
          id = $(this).attr('id')
		  $("#act").show()
          $("#search_area").hide()
		  $.post("/dgf/locations/get_edit/" + id, {}, function(data){
			$("#edit_fields").html(data)
			$("#edit_pane").show()
			$("#detail_content").accordion({autoHeight: true});
			$("#act").hide()
		  }, 'html')
        //  load("/dgf/locations/get_edit/" + id)
      }
      
      function save(){

      }
      function cancel(){
          $("#edit_pane").hide()
          $("#search_area").show()               
      }
      
      function initialize(){
        //attach event handlers
        $("input[name=g_search]").keyup(search_handler).keypress(disableEnterKey);
      }
      $(initialize)
   </script>

</head>
<body>
    
    <div>
        <form action="">
          <fieldset id="filters" style="position:relative">
              <legend>Filters</legend>                    
                <input type="text" name="g_search" size='25' />
          </fieldset>
        </form>
    </div>
	<? if(isset($message)){ ?>
		<p id="message"><?= $message ?></p>
		<script type="text/javascript">
			setTimeout(function(){ $("#message").hide() }, 1500)
		</script>
	<?}?>

    <div id="search_area">
        <div id="search_results">
     
        </div>
    </div>

    <div id="edit_pane" style="display:none;">
         <form id="edit_form" action="/dgf/locations/save_location" method="POST">
			<div id="edit_fields">
			
			</div>
            <p style="text-align:center">
              <input type="submit" value="Save" style="margin-right:15px;"/> or 
              <input type="submit" value="Cancel" onclick="cancel(); return false;" style="margin-left:15px;"/>
            </p>
         </form>
    </div>
	<p style="text-align: center">
		<img id="act" style="display:none" src="/dg_assets/activity_indicator.gif" />
	</p>
</body>
</html>
