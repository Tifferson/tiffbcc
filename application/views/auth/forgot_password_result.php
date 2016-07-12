<? if(isset($lookup_failed)) : ?>

  <h1>No Email Address</h1>
  <p>You have no email address stored in the system.  Please contact one of your site admins.
    
      <ul>
        <?foreach($admins->result() as $admin) : ?>
          <li><?=$admin->name ?>: <a href="mailto:<?=$admin->email?>"><?=$admin->email?></a>
        <?endforeach?>
      </ul>

  </p>



<? else : ?>
  <h1>Password Sent</h1>
  <?if($mail_sent) : ?>
    <p>Your password has been sent to your stored email address.</p>
  <?else:?>
    <p>Error sending email</p>  
  <?endif?>
<? endif ?>

