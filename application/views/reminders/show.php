<? $this->load->view("js/reminders.js") ?>

<h1>Your Reminders</h1>
<?if($reminders->num_rows() > 0) : ?>
<ul id="reminders">
	<?foreach($reminders->result() as $reminder) : ?>
  <li id="reminder_<?=$reminder->id?>">
    <span <?= $reminder->done == '1' ? 'style="text-decoration:line-through"' : '' ?> >
      
    <?= date_to_mdy($reminder->date) ?></span>: 
     <?= anchor( 'locations/edit/' . $reminder->location_id, $reminder->dgf_name ) ?>
  
        (<? if($reminder->done == 0) : ?>
        <a href="#done" onclick="finish_reminder(<?=$reminder->id?>)">done</a> | 
        <? endif ?>
        <a href="#delete" onclick="delete_reminder(<?=$reminder->id?>)">delete</a> )

    <br/><?= $reminder->notes ?>
        <?= !empty($reminder->notes) ? '<br/>' : '' ?>

  </li>
	<?endforeach?>
</ul>
<? else : ?>
<p>You have no reminders</p>
<?endif?>


<? if( isset($other_reminders) ) : ?>

<h1>Other User's Reminders</h1>
<?if($other_reminders->num_rows() > 0) : ?>
<ul id="other_reminders">
	<?foreach($other_reminders->result() as $reminder) : ?>
    <li id="reminder_<?=$reminder->id?>">
    <span <?= $reminder->done == '1' ? 'style="text-decoration:line-through"' : '' ?> >
    <?= date_to_mdy($reminder->date) ?></span>: 
      <?= $reminder->username ?> - 
       
     <?= anchor( 'locations/edit/' . $reminder->location_id, $reminder->dgf_name ) ?>
  
        (<? if($reminder->done == 0) : ?>
        <a href="#done" onclick="finish_reminder(<?=$reminder->id?>)">done</a> | 
        <? endif ?>
        <a href="#delete" onclick="delete_reminder(<?=$reminder->id?>)">delete</a> )

    <br/><?= $reminder->notes ?>
      <?= !empty($reminder->notes) ? '<br/>' : '' ?>
    </li>
	<?endforeach?>
</ul>
<? endif ?>

<? endif ?>
