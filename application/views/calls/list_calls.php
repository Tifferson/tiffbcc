<h1>Call Log</h1>
<? if($Calls->num_rows() > 0 ) : ?>

<table style="width:100%">
	<tr>
		<th style="width:20%">Time</th>
		<th>Caller</th>
		<th>For</th>
		<th>Callback</th>
		<th style="width:40%">Message</th>
		<th>Taken By</th>
		<th>
			<?if(isset($answered)) : ?>
				When Answered
			<?endif?>
		</th>
		<th></th>
	</tr>
		<?foreach($Calls->result() as $call) : ?>
			<tr  class="<?=alternator('row_alt', 'row')?>" >
				<td style="text-align: center"><?=mysql_datetime_to_human($call->called_when)?></td>
				<td><?=$call->caller ?></td>
				<td><?=$this->User->id_to_name($call->user_id)?></td>
				<td><?=$call->callback ?></td>
				<td><?=$call->message ?>
				<?if(!empty($Call->response)) : ?>
					<br/>
					<b>Response (<?= $this->user->id_to_name($call->answered_by)?>):</b><br/>
					<?=$call->response?>
				<?endif?>
				</td>
				<td><?=$call->taken_by ?></td>
				<td>
				<?if($call->answered == '1') : ?>
					<?=mysql_datetime_to_human($call->answered_when)?>
				<?else : ?>
					<?=anchor('Calls/close/' . $call->id, 'close')?>
				<?endif?>
					<br/>
					<?=anchor("Calls/edit/" . $call->id, "edit" )?>
				</td>
				<td>
				<form  style="display:inline" action="<?=site_url('Calls/delete')?>" method="POST">
					<input type="hidden" name="id" value="<?=$call->id?>" />
					<input type="submit" value="Delete" />
				</form>
				</td>
			</tr>
		<?endforeach?>
</table>
<? else : ?>
<p>No phone calls to display.</p>
<? endif ?>
