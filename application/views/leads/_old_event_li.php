<li id="event_<?=$event->id?>" <?= $i % 2 == 0 ? 'class="row_alt"' : '' ?>  >
  <table style="width:100%">
    <tr>
      <td style="text-align: left;width:20%">
        	<?= $event->dgf_rep ?> <?= mdate("%m/%d/%y", human_to_unix($event->when)) ?>: 
      </td>
     <td style="text-align: left;width:70%">
       <a href="#event_<?=$event->id?>" onclick="$('#event_p_<?=$event->id?>').toggle()">
     	<?= $event->description ?>
     	</a>
      </td>
       <td style="text-align: right;width:10%">
          <a href="#delete_event" onclick="delete_event(<?=$event->id?>)">delete</a>
      </td>
      
    </tr>
  </table>

	<p id="event_p_<?=$event->id?>" style="display: none">
		<?=$event->notes?>
	</p>
</li>