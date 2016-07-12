<? foreach($stops->result() as $stop) : ?>
	<li id="order_<?=$stop->id?>" style="width:50%">
    <table style="width:100%">
      <tr>		
        <td>
          <?=anchor("locations/edit/$stop->id", $stop->dgf_name)?><br/>
		      <span class="subinfo">
			     <?=loc_address($stop)?>
		      </span>
        </td>
        <td style="text-align:right">
          <a href="#delete" onclick="remove(<?=$stop->id?>)">remove</a>
        </td>
       </tr>
      </table>
	</li>
<?endforeach?>
