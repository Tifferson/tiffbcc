<li id="reminder_<?=$reminder->id?>">

    <span <?= $reminder->done == '1' ? 'style="text-decoration:line-through"' : '' ?> >
     <?= date_to_mdy($reminder->date) ?></span>: 
      <?=$reminder->username ?><br/><?= $reminder->notes ?>
    <br/>
        <? if($reminder->done == 0) : ?>
        <a href="#done" onclick="finish_reminder(<?=$reminder->id?>)">done</a> | 
        <? endif ?>
        <a href="#delete" onclick="delete_reminder(<?=$reminder->id?>)">delete</a>  
</li>
