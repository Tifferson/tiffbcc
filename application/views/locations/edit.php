<?= $this->load->view("locations/check_event_js") ?>

<p id="loading_msg">Loading edit screen with Google Maps.  If this takes too long, refresh the page.</p>


<!-- maps api, ajax search api, map search control code -->

<? $key = $this->config->item('google_api_key'); ?>

  <script src="http://maps.google.com/maps?file=api&v=2&key=<?=$key?>"
    type="text/javascript"></script>
 <script src="http://www.google.com/uds/api?file=uds.js&v=1.0&source=uds-msw&key=<?=$key?>"
    type="text/javascript"></script>
  <script type="text/javascript">
    window._uds_msw_donotrepair = true;
  </script>
  <script src="http://www.google.com/uds/solutions/mapsearch/gsmapsearch.js?mode=new"
    type="text/javascript"></script>

	<!-- ajax search stylesheet, map search stylesheet -->
  <style type="text/css">
    @import url("http://www.google.com/uds/solutions/mapsearch/gsmapsearch.css");
  </style>
  <style type="text/css">
    @import url("http://www.google.com/uds/css/gsearch.css");
  </style>

  <style type="text/css">
    .gsmsc-mapDiv {
      height : 275px;
    }

    .gsmsc-idleMapDiv {
      height : 275px;
    }

    #mapsearch {
      width : 365px;
      margin: 10px;
      padding: 4px;
    }
  </style>
  <?
	$home_city = "austin";
	if(trim(strtolower($loc->city)) == $home_city) :
		$title = get_setting('company_name');
		$map_center = get_setting('address1') . ', ' . get_setting('city') . ', ' . get_setting('state') . ' ' . get_setting('zip'); 
		//TODO: optimize company address
	else :
		$title = $loc->city . "," . $loc->state;
		$map_center = $title;
	endif;
  
  ?>
  
  <script type="text/javascript">

    function LoadMapSearchControl() {

      var options = {
            zoomControl : GSmapSearchControl.ZOOM_CONTROL_ENABLE_ALL,
            title : "<?=$title?>", 
            idleMapZoom : GSmapSearchControl.ACTIVE_MAP_ZOOM-2,
            activeMapZoom : GSmapSearchControl.ACTIVE_MAP_ZOOM-2,
			      onBootComplete : boot_complete
            }

      control = new GSmapSearchControl(
            document.getElementById("mapsearch"),
            "<?=$map_center?>",
            options
            );
	
    }
	
	function boot_complete(control){
		//find it on the map!
		control.execute("<?=addslashes($loc->dgf_name) . ', ' . $loc->dgf_address  ?>")
    
		<? // . ', ' . $loc->city . ', ' . $loc->state ?>
	}
    // arrange for this function to be called during body.onload
    // event processing
    //GSearch.setOnLoadCallback(LoadMapSearchControl);
  
  
    function show_google(){
      $(".maps_hidden").show();
      $("#show_google_button").hide()
      LoadMapSearchControl();
    }
  
  </script>

	
	<script type="text/javascript">
		function use_google(){
			$("input[name=dgf_name]").val($('a.gs-title').text())
			$("input[name=dgf_address]").val($('.gs-street').text())
			city_tokens = $('.gs-city').text().split(',')
			$("input[name=city]").val(city_tokens[0])
			$("input[name=state]").val(city_tokens[1].replace(/\s/g,''))
			$("input[name=phone]").val($('.gs-phone').text())
			
			
			return false;
		}
	
    $( function(){ $("#loading_msg").remove() } )

    </script>

	<!-- end google maps nonsense -->


<h1>Edit Location</h1>

<form id="edit_form" action="<?=site_url('locations/save_location')?>" method="POST">

<? $one_day = 24*60*60 ?>
<input type="hidden" name="id" value="<?= $loc->id ?>" />
<input type="hidden" name="was_customer" value="<?= $loc->is_customer ?>" />
<table id="fields">
<tr><td class="pane" style="width:365px"> <?//google map width?>
 <h2> <?=$loc->dgf_name?></h2>
    <!-- primary location content -->
<table >
<tr >
<td>
    Name: 
</td>
<td>
    <input type="text" name="dgf_name" value="<?= clean_txt($loc->dgf_name) ?>" size="35" />  
</td>
</tr>

<tr>
<td>
    Address 1:
</td>
<td>
    <input type="text" name="dgf_address" value="<?= clean_txt($loc->dgf_address) ?>" size="35" /> 
</td>
</tr>
<tr>
<td>
    Address 2:
</td>
<td>
    <input type="text" name="address2" value="<?= clean_txt($loc->address2) ?>" size="35" /> 
</td>
</tr>



<tr>
<td>
    City: 
</td>
<td>
    <input type="text" name="city" value="<?= clean_txt($loc->city) ?>" size="20" />  
</td>
</tr>

<!--
<tr>
<td>
    Region: 
</td>
<td>
	<select name="region">
		<option value="">...</option>
		<option <?= ($loc->region == 'A') ? 'selected="selected"' : '' ?> value="A">Austin</option>
		<option <?= ($loc->region == 'S') ? 'selected="selected"' : '' ?> value="S">San Antonio</option>
		<option <?= ($loc->region == 'H') ? 'selected="selected"' : '' ?> value="H">Houston</option>
		<option <?= ($loc->region == 'D') ? 'selected="selected"' : '' ?> value="D">Dallas</option>
    </select>
</td>
</tr>
-->

<tr>
<td>
    State: 
</td>
<td>
    <input type="text" name="state" value="<?= $loc->state ?>" size="4" />  
</td>
</tr>


<tr>
<td>
    Zip:
</td>
<td>       
    <input type="text" name="zip" value="<?= $loc->zip ?>" size="5" />
</td>
</tr>


<tr>
<td>
    Phone: 
</td>
<td>  
    <input type="text" name="phone" value="<?= $loc->phone ?>" size="15" />
</td>
</tr>

<tr>
<td>
	<a href="<?= make_url($loc->website) ?>">Website:</a>
</td>
<td>
	<input type="text" name="website" size="35" value="<?= $loc->website ?>" />
</td>
</tr>
<tr>
	<td colspan="2" style="font-size: 1.1em;font-weight: bold;">Contact Info</td>
</tr>
<tr>
	<td>Name:</td>
	<td><input type="text" name="contact_name" size="35" value="<?= $loc->contact_name ?>" /></td>
</tr>
<tr>
	<td>Phone: </td>
	<td><input type="text" name="contact_number" size="35" value="<?= $loc->contact_number ?>" /></td>
</tr>
<tr>
	<td><?=mailto($loc->email, 'Email:') ?></a></td>
	<td><input type="text" name="email" size="35" value="<?= $loc->email ?>" /></td>
</tr>


<!-- start mailing address hidden in table -->
<? $display = empty($loc->mailing_address1) ?  "display:none" : '' ?>
<tr class="mailing" style="<?=$display?>">
  <td colspan="2" style="font-size: 1.1em;font-weight: bold;">Mailing Address</td>
</tr>

<tr class="mailing" style="<?=$display?>">
<td>
    Line 1:
</td>
<td>
    <input type="text" name="mailing_address1" value="<?= $loc->mailing_address1 ?>" size="35" /> 
</td>
</tr>

<tr class="mailing" style="<?=$display?>e">
<td>
    Line 2:
</td>
<td>
    <input type="text" name="mailing_address2" value="<?= $loc->mailing_address2 ?>" size="35" /> 
</td>
</tr>

<tr class="mailing" style="<?=$display?>">
<td>
    State: 
</td>
<td>
    <input type="text" name="mailing_state" value="<?= $loc->mailing_state ?>" size="4" />  
</td>
</tr>

<tr class="mailing" style="<?=$display?>">
<td>
    Zip:
</td>
<td>       
    <input type="text" name="mailing_zip" value="<?= $loc->mailing_zip ?>" size="5" />
</td>
</tr>


<!-- end mailing address hidden in table -->
</table>

<br/>

<!-- buttons beneath first pane -->
<div class="buttons" >
  <input type="checkbox" onclick="if($(this).attr('checked')){$('.mailing').show()}else{$('.mailing').hide()}" 
      <?= empty($display) ? "checked='checked'" : '' ?>
      />
    Mailing address different than location address

  <a id="show_google_button" onclick="show_google();">[+] Show Google Maps View</a>

</div>




<br/>

<!-- start google maps controls -->
<div class="maps_hidden" style="display:none">
  <div class="buttons">
    <a class="positive" onclick="return use_google(); return false;">
      Use Google Results for Address</a>
  </div>

  <div id="mapsearch">
    <span style="color:#676767;font-size:11px;margin:10px;padding:4px;">Loading...</span>
  </div>

</div>
<!-- end google maps controls -->

	<!-- end primary pane in layout table -->

   </td>
   <td class="pane" style="width:300px">

<!-- second panel in layout table -->

<!-- start checkboxes -->
<h3>Status</h3>
<?if($loc->date_added != "0000-00-00") : ?>
	Date Added to Database: <br/>
	<?=date_to_mdy($loc->date_added)?> <br/>
<?endif?>
	<br/>
		<input onclick="customer_handler(this)" 
		type="checkbox" name="is_customer" <?=$loc->is_customer == '1' ? 'checked="checked"' : '' ?>/> 
		Customer

			


<? /*
	<br/><br/>
		<input type="checkbox" onclick="lead_handler(this)" name="is_lead" <?=$loc->is_lead == '1' ? 'checked="checked"' : '' ?>/> 
		<input type="hidden" name="active_rep" disabled="disabled"
			value="<?=$this->session->userdata("user_id")?>" />
		Lead

  */
?>

	<br/><br/>
		<input type="checkbox" onclick="canceled_handler(this)" name="canceled" <?= $loc->canceled == '1' ? 'checked="checked"' : '' ?>/> 
		Canceled

  <div id="canceled" style="<?= $loc->canceled == 1 ?  '' : 'display:none' ?>">
    Canceled by : 
    <?= select('canceled_by', $employees, $loc->canceled_by) ?>
    <br/>
    Canceled Date : <input type="text" name="canceled_date" value="<?= date_to_mdy($loc->canceled_date, false) ?>"
                            class="date_pick" />
    <br/>
    Cancel Reason : <br/>
    <textarea rows="3" cols="35" name="canceled_reason"><?=$loc->canceled_reason?></textarea>

  </div>


	<br/><br/>

		<input onchange="ignore_handler(this)" 
		type="checkbox" name="ignore" 
		<?=$loc->ignore == '1' ? 'checked="checked"' : '' ?>/> 
		Ignored
	<br/>

	<div id="ir" class="not_is_customer" style="<?= $loc->ignore == '0' ? 'display:none' : '' ?>">
		Ignore Reason: <br/>
		<textarea name="ignore_reason" rows="3" cols="35"><?= $loc->ignore_reason ?></textarea>
	</div>
 
 <br/>


<!-- end checkboxes -->

  
<div id="container_dropoff" style="display:none">
<h3>Container Dropoff</h3>
<a href="#hide" onclick="$('#container_dropoff').remove()">hide</a>
<table>
<tr class="is_cust_opt">
    <td>Date:</td>
    <td>
		<input disabled="disabled" type="text" 
			size="10" name="container_dropoff_date" 
			class="date_pick"
			value="<?= date("m/d/Y", time() + 4*$one_day ) ?>" />
    </td>
   </tr>

  <tr class="is_cust_opt">
    <td colspan='2'>Special Notes for or during Barrel Dropoff:</td>
  </tr>
  <tr class="is_cust_opt">
    <td colspan='2'>
		  <textarea disabled="disabled" name="special_notes_for_dropoff" rows="2" cols="35"></textarea>
    </td>
   </tr>
  
</table>
</div>



General Notes (for office): <br/>
 <textarea name="notes" rows="6" cols="35"><?= trim($loc->notes) ?></textarea>
<br/>
<div class="is_customer" style="<?= $loc->is_customer == 0 ? 'display:none' : '' ?>">
Pickup Notes (for driver): <br/>
<textarea name="pickup_specifics" rows="6" cols="35"><?= $loc->pickup_specifics ?></textarea>		
</div>
<br/>

<!-- start reminders -->
<h3>Reminders</h3>

<ul id="reminders">
<? foreach($reminders->result() as $r) :?>
  <? $this->load->view('reminders/reminder_li', array('reminder' => $r)) ?>
<? endforeach ?>
</ul>

<? $this->load->view("reminders/reminder_form") ?>


<!-- end reminders -->

<br/>
<br/>

<!-- start work orders -->
<? if($work_orders->num_rows() > 0) : ?>
  <h3>Recent Work Items</h3>  
  <ul>
  <?foreach($work_orders->result() as $wo) : ?>
    <li><?=mysql_to_mdy($wo->due_date)?><br/><?=anchor('Work_orders/edit/' . $wo->id, $wo->task)?></li>
  <?endforeach?>
  </ul>  
  <?= anchor('Work_orders/location/' . $loc->id, "Work Item History")?>
<?endif?>
<!-- end work orders -->


<!-- end second panel -->
	  
</td><td class="pane">

<!-- start third pane -->
	<!-- veg oil detail -->

	        <h3> Veg Oil Info </h3>
	        <div>
	          <table>
	             <tr class="not_is_customer" style="<?= $loc->is_customer == 1 ? 'display:none' : '' ?>">
	                <td>
	                  Current Renderer: 
	                </td>
	                <td>
	    				       <input type="text" name="current_renderer" size="10" value="<?= $loc->current_renderer ?>" />
	          </tr>
			  <tr class="not_is_customer" style="<?= $loc->is_customer == 1 ? 'display:none' : '' ?>">
				<td>
			  		Current Contract Ends on:
				</td>
				<td>
	            <input type="text"
						class="not_is_customer date_pick"
						
	      					name="current_contract_ends" 
	      					size="10" value="<?= date_to_mdy($loc->current_contract_ends, false) ?>" />
				</td>
			 </tr>
			
      <? if( false ) : ?>
			<tr class="is_customer" style="<?= $loc->is_customer == 0 ? 'display:none' : '' ?>">
				<td>Containers Dropped:</td>
				<td><input type="checkbox" name="containers_dropped"
					<?= $loc->containers_dropped == '1' ? "checked='checked'" : '' ?> /></td>
			</tr>
			<? endif ?>

	          <tr class="is_customer" style="<?= $loc->is_customer == 0 ? 'display:none' : '' ?>">
	            <td>Container Type:</td>
	            <td>
	              <?= select_other('container_type', $this->location->containers(), $loc->container_type)?>
	            </td>
	           </tr>
	
			<? if(! ($loc->container_type == 'original') ) : ?>
	          <tr class="is_customer" style="<?= $loc->is_customer == 0 ? 'display:none' : '' ?>">
	            <td>
	                Number of Containers:
	             </td>
	             <td>
	               <input type="text" name="num_containers" size="2" value="<?= p_num($loc->num_containers, 0) ?>" />
	             </td>
	          </tr>
			<? endif ?>
	
	          <tr class="is_customer" style="<?= $loc->is_customer == 0 ? 'display:none' : '' ?>">
	              <td>
	                Acid Number (KOH):
	              </td> 
	              <td>
	                <input type="text" name="ffa_number" size="4" value="<?= p_num($loc->ffa_number) ?>" />
	              </td>
	          </tr>
	          <tr class="is_customer" style="<?= $loc->is_customer == 0 ? 'display:none' : '' ?>">
	            <td>
	              Test Date: 
	            </td>
	            <td>
					<input type="text" 
							class="date_pick"
							name="ffa_test_date" 
							size="10" value="<?= date_to_mdy($loc->ffa_test_date, false) ?>" /> 
	            </td>
	          </tr>
	        </table>
			</div>
	<!-- end veg oil detail -->  


<div class="is_customer" style="<?= $loc->is_customer == 0 ? 'display:none' : '' ?>"> 
          <!-- contract detail --> 
          <h3>Acquisition Details</h3>
          <div>
		  
  
<table>
<tr>
<td>
            Under Contract:   
</td>
<td>
            <input type="checkbox" 
				name="under_contract"
				onclick="contract_handler(this)"
				<?= !empty($loc->under_contract) ? "checked='checked'" : '' ?> />
</td>
</tr>

<tr class="under_contract" style="<?= $loc->under_contract == 0 ? 'display:none' : '' ?>">
	<td>Sales Rep:</td>
	<td>
  <?= select('dgf_rep', $employees, $loc->dgf_rep) ?>
  </td>
</tr>

<tr class="under_contract" style="<?= $loc->under_contract == 0 ? 'display:none' : '' ?>">
<td>
            Start Date: 
</td>
<td>             
            <input class="date_pick" type="text" onchange="calc_end_date()"
				name="start_date"
				size="10"
				value="<?= date_to_mdy($loc->start_date) ?>" />
</td>
</tr>

<tr class="under_contract" style="<?= $loc->under_contract == 0 ? 'display:none' : '' ?>">
  <td> Contract Length : </td>
  <td>
    <?= select('contract_length', array( '0'=>'0', '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', 'other'=>'other' ), $loc->contract_length); ?> yrs
  </td>
</tr>

<tr id="end_date" class="under_contract" style="<?= $loc->under_contract == 0 ? 'display:none' : '' ?>">
<td>
            End Date: 
</td>
<td>             
              <input type="text" name="end_date" size="10" class="date_pick"
				value="<?= date_to_mdy($loc->end_date) ?>" />
</td>
</tr>

<tr class="is_customer" style="<?= $loc->is_customer == 0 && $loc->under_contract == 0 ? 'display:none' : '' ?>">
<td>
            Acquisition Date: 
</td>
<td>             
            <input class="date_pick" type="text" 
				name="date_added_as_customer"
				size="10"
				value="<?= date_to_mdy($loc->date_added_as_customer) ?>" />
</td>
</tr>


<!-- commission -->
<tr class='is_customer' style="<?= $loc->is_customer == '1' ? '' : 'display:none' ?>">
  <td>Commission Paid:</td>
  <td><input type="checkbox" name="commission_paid" 
					onclick="$('#com_toggle').toggle()"
					<?=$loc->commission_paid == '1' ? 'checked="checked"' : '' ?>/>
  </td>
</tr>
<tr id="com_toggle" style="<?= $loc->commission_paid == 1 ? '' : 'display:none' ?>" >
  <td>Paid Date:</td>
  <td>
    	<input type="text" 
						class="date_pick"
						name="date_commission_paid" 
						size="10" value="<?= date_to_mdy($loc->date_commission_paid, false) ?>" />
  </td>
</tr>

<!-- end commission -->
   

</table>
			
          </div>
            <!-- end contract detail -->
</div> <!-- end acquisition details wrapper -->

 <!-- route detail --> 
<div class="is_customer" style="<?= $loc->is_customer == 0 ? 'display:none' : '' ?>">
    <h3>Current Routes</h3>
		<ul id="current_routes_list">	
    <?if($current_routes) : ?>
		
			<?foreach($current_routes->result() as $r) : ?>
				<li id="route_<?=$r->id?>"><?=anchor("routes/edit/" . $r->id, $r->name)?> 
				    (<a href="#remove" onclick="remove_from_route(<?=$r->id?>)">remove</a>)
            <br/>Scheduled for: <?=mysql_to_mdy($r->next_date, false) ?>
				</li>
      <? $_current_routes[] = $r->id ?>
			<?endforeach?>		
	<?endif?>
  </ul>

    <? if(!empty($_current_routes)) : ?>
        <script type="text/javascript">
            current_routes = <?=json_encode($_current_routes)?>
        </script>
    <? endif ?>
<div id="atr" class="buttons">
  <button onclick="$('#atr').hide();$('#add_to_route').show('slow');" type="button">[+] Add to route</button>
</div>
<br/>
<br/>

<div id="add_to_route" style="display:none">
	<p><b>Suggested Route(s):</b> <?= $suggest_route ? $suggest_route : 'no suggestion'?></p>
  <table>
  <tr>
  <td>
              Route:   
  </td>
  <td>
          <?= $this->load->view('locations/routes_dropdown', 
            array('routes' => $all_routes)) ?>
  </td>
  </tr>
  </table>

  <div class="buttons">
	  <button onclick="add_to_route()" type="button">Add to Route</button>
  </div>
  <br/>
  <br/>
</div>
<br/>
<!-- end route info -->


<!-- start pickups info -->
<?if($pickups->num_rows() > 0) : ?>
	<h3>Pickup Statistics</h3>

	<? 
	$total = $pickups->num_rows();
	if($total > 0 ) :
		$manual = 0;
		$sum = 0;
		$zeroes = 0;
		$data = array();
		
		foreach($pickups->result() as $p) :
			if($p->route_id == 0) $manual++;
			$data[]= $p->gallons;
			$sum += $p->gallons;
			if($p->gallons < 5) $zeroes++;
		endforeach;
		  
		$avg = $total > 0 ? round($sum/$total) : 0;
		$max = max($data);
	?>
		 <? if($avg > 0) : ?>
		  <img src="http://chart.apis.google.com/chart?
	chs=200x60
	&amp;chd=t:<?=join(',', $data)?>
	&amp;cht=lc
	&amp;chds=0,<?=$max?>
	&amp;chtt=Gallons+Collected" />	
		<br/>
		<?endif;?>
		Mean: <b><?= $avg ?></b> gallons<br/>
		Empty on Arrival: <b><?=$zeroes?></b> times<br/>
		Non-Empty Mean: <b><?=$total-$zeroes > 0 ? round($sum/($total-$zeroes)) : 0?></b> gallons<br/>
		<br/>
		Total Pickups: <b><?=$total?></b><br/>
		Percentage on Route: <b><?=round(100*(1.0-(1.0*$manual/$total)))?></b>%
		<br/>
		<?=anchor("pickups/show_history/" . $loc->id, "Pickup History")?>

	<? else : ?>
		No pickups yet for this location
	<? endif ?>
<?endif?>	

		<!-- end pickup info -->
</div>



<!-- end third pane -->
    </td>
  </tr>
</table>


<h1>Events</h1>
<? $i = 0; ?>

<?if($events->num_rows() > 0):?>
<ul id="events">
	<?foreach($events->result() as $event) : ?>
    <?= $this->load->view('leads/event_li', array('event' => $event, 'i' => $i ) ) ?>
	  <? $i++ ?>
	<?endforeach?>
	</ul>
<?else :?>
<ul id="events">
</ul>
<p>No Events Logged</p>
<?endif?>


<fieldset><legend><b>Add New Event</b></legend>
  Description: <br/> 
  <textarea name="description" rows='4' cols='90'></textarea>
  <br/><br/>
  Rep: <input type="text" name="e_dgf_rep" value="<?=$this->session->userdata('user_name')?>"/> 
  Date: <input type="text" name="when" class="date_pick" value="<?=mysql_to_mdy('')?>" />
  <br/><br/>
  <div class="buttons">
	<button type="submit" class="positive" onclick="add_event();return false;">Save Event</button>
  </div>
  <input type="hidden" name="location_id" value="<?=$loc->id?>" />
</fieldset>

<br/>
	<div class="buttons" style="text-align:center">
	<table style="margin: 0 auto">
		<tr>
			<td>
	  <button class="positive"  type="submit" 
			onclick="return check_event();" name="submit" value="Save Location">Save Location</button>
		</td>
		<td>
		or 
		</td>
		<td>
		<button class="negative" type="submit" name="submit" value="Delete Location" 
		onclick="return confirm('Delete this location?')">
			Delete Location
		</button>
		</td>
	</tr>
	</table>
	
	</div>


</form>

<div id="add_lead" title="Leads">
	Add to your leads list?
</div>

<div id="remove_lead" title="Leads">
	Remove from your leads list?
</div>



<!-- event and checkbox functions -->
<script type="text/javascript">
  i = <?=$i?>;
  
  function add_event(){
	
	d = $('textarea[name=description]').val()
	
	if( d.replace(/\s+/g,'') == ""){
		alert("Empty description");
		return;
	}
    
    params = {
      'location_id':$('input[name=location_id]').val(),
      'when':$('input[name=when]').val(),
      'dgf_rep':$("input[name=e_dgf_rep]").val(),
      'description':d,
      'i':i
    }

    $.post("<?=site_url('leads/save_event')?>", 
      params, 
      function(data){ 
			$("#events").append(data) 
			//clear form
			$("textarea[name=description]").val('');
			
			//replace date with current
			date = new Date();
			date_string = '' + (date.getMonth()+1) + "/" + date.getDate() + "/" + date.getFullYear()
			$("input[name=when]").val(date_string);
		})
     i++; 
  }
  
  function delete_event(id){
     $.post("<?=site_url('leads/delete_event')?>", 
        {'id':id}, 
        function(data){ $("#event_" + id).remove() })
  }

  function canceled_handler(e){
    if($(e).attr('checked')){
      $("#canceled").show()
    }
    else{
      $("#canceled").hide()
    }

  }

	function customer_handler(e){
		if($(e).attr('checked')){
			//show fields for customer
			if($("input[name=was_customer]").val() == "0"){
				//if formerly not a customer     
				$("#container_dropoff").show()
				$("#container_dropoff input,#container_dropoff textarea").each(function(){ this.disabled = false })
			}
      $("input[name='ignore']").attr('checked', '')
			$("input[name='current_renderer']").val("<?=get_setting('company_name')?>") //TODO: use customer name
			$(".is_customer").show()
			$(".not_is_customer").hide()
		}else{
			//unchecked customer
			$("#container_dropoff").hide()
			$("#container_dropoff input,#container_dropoff textarea ").each(function(){ this.disabled = true })
			$("input[name='pending_verification']").attr('checked', '')
			$("input[name='is_lead']").attr('checked', '')
			$("select[name=route_id]").val('')
			$(".is_customer").hide()
			$(".not_is_customer").show()
		}
	}
	
	function contract_handler(e){
		if($(e).attr('checked')){
			$(".under_contract").show()
		}
		else{
			$(".under_contract").hide()
		}
	}

	function ignore_handler(e){
		if($(e).attr('checked')){
			customer_handler($("input[name='is_customer']").attr('checked', ''))
			$("#ir").show()
		}
		else
		{
			$("#ir").hide()
		}
	}
	

//TODO: CHANGE HOW LEADS WORK!!!
	function lead_handler(e){
		if($(e).attr('checked')){
			$("#add_lead").dialog('open');
		}else{
			$("#remove_lead").dialog('open');
		}
	}
	
	function add_to_my_leads(){
		id = $("input[name=id]").val()
		$.post("<?=site_url('leads/make_my_leads')?>", {'ids':id})
	}
	
	function remove_from_my_leads(){
		id = $("input[name=id]").val()
		$.post("<?=site_url('leads/remove_my_leads')?>", {'ids':id})
	}

  function calc_end_date(){
    start_date = $('input[name=start_date]').val()
    years = $('select[name=contract_length]').val()

    if( start_date == "" || 
        years == "other" ||
        years == "" ) return;

    tokens = start_date.split('/')
    tokens[2] = parseInt(tokens[2]) + parseInt(years)

    $('input[name=end_date]').val('' + tokens[0] + '/' + tokens[1] + '/' + tokens[2])

  }

  $(document).ready(function(){ $('select[name=contract_length]').change(function(){ calc_end_date() } ) } );
		
	//initialize datepickers
	$( function() {
		contract_handler($("input[name=under_contract]"))
		customer_handler($("input[name=is_customer]"))
		$("#add_lead").dialog( 
			{ 	buttons : { "No": function() { $(this).dialog("close") },
							"Yes": function(){ add_to_my_leads(); $(this).dialog("close") }
							},
				autoOpen: false,
				modal: 	true
			});
			
		$("#remove_lead").dialog( 
				{ 	buttons : { "No": function() { $(this).dialog("close") },
								"Yes": function(){ remove_from_my_leads(); $(this).dialog("close") }
								},
					autoOpen: false,
					modal: 	true
				});
	});


  
</script>
<!-- end event and checkbox functions -->


<!-- route functions -->
<script type="text/javascript">
  function add_to_route(){
    route_id = $('select[name=route_id]').val()
    if(route_id == ""){ 
      alert("No route selected")
    }
    else if(window.current_routes != undefined && $.inArray(route_id, current_routes) > -1){
       alert("Already on that route")
    }
    else{
      loc_id = $("input[name=id]").val()
      $.post("<?=site_url('routes/add_location')?>", {'location_id':loc_id, 'route_id':route_id}, 
          function(data){ $("#current_routes_list").append(data) }, 'html')
      $('#add_to_route').hide('slow')
      $('#atr').show()
    }  
  }

  function remove_from_route(route_id){
    loc_id = $("input[name=id]").val()
    if(confirm("Remove this location from route?")){
        $.post("<?=site_url('routes/remove_location')?>", {'location_id':loc_id, 'route_id':route_id}, 
          function(data){ $("#route_" + route_id).remove() })
    }
  }


</script>	
<!-- end route functions -->

<? $this->load->view("js/reminders_js") ?>

