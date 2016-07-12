<div style="width:500px;margin:0 auto;">
<h1>Forgot Password</h1>
<?= $this->load->view('flash_message', array('flash_persist' => true)) ?>
<form action="<?=site_url('Auth/process_forgot_password')?>" method="POST" class="validate">

<div style="border: 1px solid #CFD4E6">
<div style="border: 2px solid white;background:#CFD4E6;padding:20px 5px">

<table style="margin:0 auto;">	
	<tr>
		<td>Username: </td>
		<td>
			<input type="text" name="username" class="required" />
	</td>
	</tr>

	<tr>
		<td  colspan="2" >	
	<div class="buttons" style="float:right">
		<button type="submit">Find Password</button>
	</div>
		
		</td>
	</tr>
</table>

</div>
</div>
</form>


</div>
