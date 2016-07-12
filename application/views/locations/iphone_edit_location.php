<input type="hidden" name="id" value="<?= $loc->id ?>" />


<!-- secondary content -->

	<ul class="basic" id="detail_content">
	<li>
		<a href="#veg_oil_detail"> Location Info </a>
		<div>
		<table>
			<tr>
			<td>
			    Name: 
			</td>
			<td>
			    <input type="text" name="dgf_name" value="<?= $loc->dgf_name ?>" size="35" />  
			</td>
			</tr>
			<tr>
			<td>
			    Address:
			</td>
			<td>
			    <input type="text" name="dgf_address" value="<?= $loc->dgf_address ?>" size="35" /> 
			</td>
			</tr>
			<tr>
			<td>
			    City: 
			</td>
			<td>
			    <input type="text" name="city" value="<?= $loc->city ?>" size="20" />  
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
				<td colspan="2">
				    Notes: <br/>
			    <textarea name="notes" rows="3" cols="40"><?= trim($loc->notes) ?></textarea>
				</td>
			</tr>
			</table>
		</div>
	</li>
	<li>
        <a href="#veg_oil_detail"> Veg Oil Detail </a>
        <div>
          <table>
          <tr>
            <td>Container Type:</td>
            <td>
           <select name="container_type">
              <? $drum = (preg_match("/drum/i", $loc->container_type)) ? "selected='true'" : '';  ?>
              <? $dump = (preg_match("/dumpster/i", $loc->container_type)) ? "selected='true'" : ''; ?>
              <option value="none">...</option>
              <option <?= $drum ?> >Drum</option>
              <option <?= $dump ?> >Dumpster</option>
           </select>
            </td>
           </tr>
          <tr>
            <td>
                Number of Containers:
             </td>
             <td>

               <input type="text" name="num_containers" size="2" value="<?= $loc->num_containers ?>" />
             </td>
          </tr>
          <tr>
              <td>
                % FFA:
              </td> 
              <td>
                <input type="text" name="ffa_percent" size="4" value="<?= $loc->ffa_percent ?>" />
              </td>
          </tr>
          <tr>
            <td>
              Test Date: 
            </td>
            <td>
    <input type="text" name="ffa_test_date" size="10" value="<?= $loc->ffa_test_date ?>" /> 
            </td>
          </tr>
        </table>
		</div>
  </li>
  <li>
        <a href="#lead_detail"> Lead Detail </a>
        <div><p>
            Contact Name:<br/>
            <input type="text" name="contact_name" size="35" value="<?= $loc->contact_name ?>" />
            <br/>
            Website: <br/>
            <input type="text" name="website" size="35" value="<?= $loc->website ?>" /><br/>
            Email: <br/>
            <input type="text" name="email" size="35" value="<?= $loc->email ?>" />
            <br/>
            Acquisition Status: <br/>
             <input type="text" name="acquisition_status" size="35" value="<?= $loc->acquisition_status ?>"/>
            <br />
            DGF Rep: <br/> 
            <input type="text" name="dgf_rep" size="35" value="<?= $loc->dgf_rep ?>" />
            <br/></p>
        </div>
  </li>
  <li>
        <a href="#restaurant_detail"> Restaurant Detail </a>
        <div><p>
            <!-- restaurant detail -->

            Current Renderer: <input type="text" name="current_renderer" size="35" value="<?= $loc->current_renderer ?>" />
            <br/>
            Food Service Type: <input type="text" name="food_service_type" size="15" value="<?= $loc->food_service_type ?>" />
            <br/>
            Number of Employees: <input type="text" name="num_employees" size="4" value="<?= $loc->num_employees ?>" />
            <br/>
            Square Feet: <input type="text" name="square_feet" size="5" value="<?= $loc->square_feet ?>" />
            <br/>
            People served (per week): <input type="text" name="served_per_week" size="4" value="<?= $loc->served_per_week ?>" />
            <br/>  
            Permit Type: <input type="text" name="permit_type" size="15" value="<?= $loc->permit_type ?>" />
            <br/>
            License Status: <input type="text" name="license_status" size="10" value="<?= $loc->license_status ?>" />
		</p>
	   </div>
    </li>
	<li>
          <a href="#contract_detail">Contract Detail</a>
          <div>
		  
            <!-- contract detail -->
<table>
<tr>
<td>
            Under Contract:   
</td>
<td>
            <input type="text" name="under_contract" size="15" value="<?= $loc->under_contract ?>" />
</td>
</tr>
<tr>
<td>
            Contract Mailed on:
</td>
<td>
            <input type="text" name="contract_mailed" size="10" value="<?= $loc->contract_mailed ?>" />
</td>
</tr>
<tr>
<td>
            Rebate Per Gallon: 
</td>
<td>            
            <input type="text" name="rebate_per_gallon" size="5" value="<?= $loc->rebate_per_gallon ?>" />
</td>
</tr>
<tr>
<td>
            Start Date: 
</td>
<td>             
            <input type="text" name="start_date" size="10" value="<?= $loc->start_date ?>" />
</td>
</tr>
<tr>
<td>
            End Date: 
</td>
<td>             
              <input type="text" name="end_date" size="10" value="<?= $loc->end_date ?>" />
</td>
</tr>
</table>
			
          </div>
      </li>
	  <li>
          <a href="#route_detail">Route Detail</a>
          <div>
		  
            <!-- route detail -->
<table>
<tr>
<td>
            Week Number:
</td>
<td>            
            <input type="text" name="week_number" size="5" value="<?= $loc->week_number ?>" />
</td>
</tr>
<tr>
<td>
            Route Number:   
</td>
<td>
            <input type="text" name="route_number" size="5" value="<?= $loc->route_number ?>" />
</td>
</tr>
<tr>
<td>
            Stop Number: 
</td>
<td>
            <input type="text" name="stop_number" size="5" value="<?= $loc->stop_number ?>" />
</td>
</tr>
</table>
			
          </div>
      </li>
	</ul>

<!-- end secondary content -->
