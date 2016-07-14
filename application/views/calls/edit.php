<h1>Edit Call</h1>
	
<form action="<?=site_url('Calls/update_call')?>" method="POST">

	<input type="hidden" name="id" value="<?= $Call->id ?>" />
	
	<!-- start editable fields -->
	<table>
		<tr>
			<td>Caller: </td><td> <input type="text" name="caller" value="<?=$Call->caller?>" /></td>
		</tr>
			<tr>
  		  <td>Call is for:</td>
  		  <td>		<?= $this->load->view('users_dropdown', 
  		                array("selected" => $Call->user_id, "field" => 'user_id')) ?> </td>
  		</tr>
		<tr>
			<td>Callback Number: </td><td> <input type="text" name="callback" value="<?=$Call->callback?>" /></td>
		</tr>
		<tr>
			<td colspan="2">
				Message:
				<br/>
				<textarea name="message" rows="4" cols="50"><?=$Call->message?></textarea>
			</td>
		</tr>
		<tr>
			<td>Person who took call:</td>
			<td><input type="text" name="taken_by" value="<?=$Call->taken_by?>" /></td>
		</tr>

		<tr>
			<td>Time Taken: <b><? //mysql_datetime_to_human($Call->called_when)?></b></td>
			<td>
				Date: <input id="date" type="text" name="date" value="<?=mysql_to_mdy($Call->called_when)?>" /> <br/>
				Time: <input id="time" type="text" name="time" value="<?=mysql_to_time($Call->called_when)?>" />
			</td>
		</tr>
		<tr>
			<td>Person Handling Call:</td>
			<td>
				<?= $this->load->view('users_dropdown', 
			array("selected" => $this->session->userdata('user_id'),
				"field" => 'answered_by')) ?>
			</td>
		</tr>	
		<tr>
			<td colspan="2">	
				Response: <br/>
				<textarea rows="4" cols="50" name="response"><?=$Call->response?></textarea><br/>
			</td>
		</tr>
	</table>
	<!-- end editable fields -->
	
	<!-- controls -->
	<input type="submit" value="Save" /> -or-  <?= anchor('Calls', 'Back')?>
	
</form>

<script type="text/javascript">
  $("#date").datepicker()
  $("#time").clockpick()
</script>

