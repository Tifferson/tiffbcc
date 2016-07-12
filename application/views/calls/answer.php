<h1>Close Call</h1>
<? if($call) : ?>
<p>
<b>From:</b> <?= $call->caller ?> <br/><br/>
<b>message:</b><br/>
<?=$call->message?>
</p>

<form action="<?=site_url("Calls/record_answer")?>" method="POST">
	<input type="hidden" name="id" value="<?=$call->id?>" />
	<b>Closed by:</b> 
		<?= $this->load->view('users_dropdown', 
			array("selected" => '',
				"field" => 'answered_by')) ?>
	<br/>
	<b>Response:</b> <br/>
	<textarea rows="4" cols="50" name="response"></textarea><br/>
	<input type="submit" value="Save" /> or <?= anchor("Calls", "Back") ?>
</form>


<? else : ?>
<p> error </p>

<? endif ?>

