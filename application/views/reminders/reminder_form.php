<div class="buttons">
  <button id="add_fu_button" type="button" onclick="$('#fu').show();$(this).hide()" >[+] Add reminder</button>
</div>
<div id="fu" style="display:none">
 <table>
  <tr>
    <td>
      Date: 
    </td>
    <td> 
        <input type="text" class="date_pick" size="10" name="fu_date" /> <br/>
    </td>  
  </tr>
  <tr>
    <td>
      For:  
    </td>    
    <td>    
      <? $this->load->view('users_dropdown', array('field' => 'fu_user_id')) ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      Note: <br/>     
      <textarea name="fu_notes" cols="35" rows="3"></textarea>
    </td>
  </tr>
</table>
<div class="buttons">
  <button type="button" onclick="add_reminder();">Add reminder</button>
</div>
</div>
