<script type="text/javascript">
	String.prototype.trim = function () {
       return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    }


 	function check_event(){
		if($("textarea[name=description]").val().trim() == ""){
			return true;
		}
		else{
			alert("Click Save Event to save your sales event");
			$("input[name='save event']").focus()
			return false;
		} 
	}
</script>