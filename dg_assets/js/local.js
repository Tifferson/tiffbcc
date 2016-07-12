//used in conjunction with select_other method
function other(select){
	name = $(select).attr('name')
	if($(select).val() == "other"){
		$("select[name=" + name + "]").hide()
		$("input[name=" + name + "]").show().attr('disabled','').keyup(
			function(e){
			          if (e == null) { // ie
			            keycode = event.keyCode;
			          } else { // mozilla
			            keycode = e.which;
			          }
			          if(keycode == 27){ // escape, close box
			            $("select[name=" + name + "]").show().attr('disabled','')
						      $("input[name=" + name + "]").hide().attr('disabled','disabled')
			          }
			        }
		)
		
	}
}

function go_to(url){
	window.location = url
}

//************* FIRST SITEWIDE document.onReady ATTACHMENT**************/

$( function(){
	
	//for date picker fields
	$(".date_pick").datepicker({
			showOn: 'button'
	});

  //expands xx/xx  into xx/xx/CURRENT_YEAR
	$(".date_pick").blur(function(){
		raw_date = this.value
		date_tokens = raw_date.split(/\-|\//)
    today = new Date()
		
		if(date_tokens.length == 2){
			if(date_tokens[0].length == 1) date_tokens[0] = '0' + date_tokens[0]
			if(date_tokens[1].length == 1) date_tokens[1] = '0' + date_tokens[1]
			this.value = date_tokens[0] + "/" + date_tokens[1] + "/" + today.getYear()
		}
	})
	
	//makes tables sortable via class.  use metadata in class to prevent rows from sorting
	$(".tablesorter").tablesorter();
  
  //makes forms validate via class.  use metadata to apply specific rules
  $(".validate").validate();
	
})
