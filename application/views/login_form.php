<div style="width:500px;margin:0 auto;">
<h1>Login to the Biodiesel Control Center</h1>
<?= $this->load->view('flash_message', array('flash_persist' => true)) ?>
<form action="<?=site_url('Auth/do_login')?>" method="POST">

<div style="border: 1px solid #CFD4E6">
<div style="border: 2px solid white;background:#CFD4E6;padding:20px 5px">

<table style="margin:0 auto;">	
	<tr>
		<td>Username: </td>
		<td>
			<input type="text" name="user" />
	</td>
	</tr>
	<tr>
	<td>
		Password:
	</td>
	<td>
		<input type="password" name="password" />
	</td>
	</tr>
	<tr>
		<td  colspan="2" >	
	<div class="buttons" style="float:right">
		<button type="submit">Login</button>
	</div>
		
		</td>
	</tr>
	<tr>
		<td  colspan="2" style="font-size:0.8em;text-align:right">	
			<a href="<?=site_url('Auth/forgot_password')?>">I forgot my password</a>
		</td>
	</tr>
</table>

</div>
</div>
</form>


</div>
