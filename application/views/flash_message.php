<? $msg = $this->session->flashdata("message");
if (!empty($msg)) : ?>
<table id="flash_message" style="width:100%" class="<?= $this->session->flashdata('message_class') ?>">
<tr>
  <td><?= $this->session->flashdata("message"); ?></td>
  <td style="text-align:right"><a href="#hide_msg" onclick="$('#flash_message').hide('normal')">hide</a></td>
</tr>
</table>
<? endif; ?>
