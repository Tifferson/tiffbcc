<p>&nbsp;</p>
<div style="float:left">
<form action="<?=site_url('calls')?>" method="POST">
	<input type="hidden" name="new" value="1" />
	<fieldset>
		<legend><b>New Call</b></legend>
		<table>
		<tr>
			<td>Caller: </td><td> <input type="text" name="caller" /></td>
		</tr>
		<tr>
		  <td>For:</td>
		  <td>		<?= $this->load->view('users_dropdown', 
		                array("selected" => '', "field" => 'user_id')) ?> </td>
		</tr>
		<tr>
			<td>Callback: </td><td> <input type="text" name="callback" /></td>
		</tr>
		<tr>
			<td colspan="2">
		Message:
		<br/>
		<textarea name="message" rows="4" cols="50"></textarea>
		</td>
		</tr>
		<tr>
			<td>Taken By:</td>
			<td><input type="text" name="taken_by" /></td>
		</tr>
		<tr>
			<td>Time Taken:</td>
			<td>
				Date: <input id="date" type="text" name="date" /> <br/>
				Time: <input id="time" type="text" name="time" />
			</td>
		</tr>
	</table>
	<input type="submit" value="Save" /> -or- 
	<?= anchor('Calls', 'Back')?>
	</fieldset>
	
</form>
</div>

<script type="text/javascript">
  $("#date").datepicker()
  $("#time").clockpick()
</script>