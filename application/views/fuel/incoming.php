<? if($this->session->flashdata("display_errors")) : 
	echo $this->session->flashdata("display_errors");
endif;
?>
<p id="errors" class="error" style="display:none;padding:10px">
	Fields with errors marked in red
	<span id="rin" style="display:none"><br/>RIN invalid</span>
</p>
<h1>Enter Incoming Fuel</h1>
<?=  form_open_multipart("fuel/upload_incoming") ?>
	<table style="width:60%">
		<tr>
			<td>Date Delivered:</td>
			<td><input class="validate" type="text" name="when"/> </td>
		</tr>
		<tr>
			<td>Product: </td>
			<td>
				<?= select_other('product_id', $products ); ?>
			</td>
		</tr>
		<tr>
			<td>Supplier: </td>
			<td>
				<?= select_other('supplier_id', $suppliers ); ?>
			</td>
		</tr>
		<tr>
			<td>Quantity:</td>
			<td><input class="validate" type="text" name="amount"/> (gallons)</td>
		</tr>
		<tr>
			<td>Price (per gallon):</td>
			<td><input class="validate" type="text" name="price"/> </td>
		</tr>
		
		<tr class="tax_credit bio" style="display:none">
			<td>Tax Credit Taken (by supplier):</td>
			<td><input type="checkbox" onclick="$('.excise').toggle()" name="tax_credit_taken"/> </td>
		</tr>
		
		<tr>
			<td>Recieving Container: </td>
			<td>
				<?= select('container_id', $containers ); ?>
			</td>
		</tr>
		
		<tr class="excise" style="display:none">
			<td>Excise Taxes Paid (by supplier):</td>
			<td><input type="checkbox" name="excise_taxes_paid"/> </td>
		</tr>


		<tr class="coa bio" style="display:none">
			<td>COA: </td>
			<td>
				<input type="file" name="coa" />
			</td>
		</tr>
		<tr>
			<td>Bill of Lading?</td>
			<td>
				<input type="checkbox" name="bol" onclick='$(".bol").toggle()' />
			</td>
		</tr>
		
		<tr class="bol" style="display:none">
			<td>Bill of Lading: </td>
			<td>
				<input type="file" name="bill_of_lading" />
			</td>
		</tr>

		<tr>
			<td>Weigh sheets?</td>
			<td>
				<input type="checkbox" name="weigh_sheets" onclick='$(".weigh").toggle()' />
			</td>
		</tr>
		<tr class="weigh">
			<td>Weigh Sheet Before: </td>
			<td>
				<input type="file" name="weigh_before" />
			</td>
		</tr>
		<tr class="weigh">
			<td>Weigh Sheet After: </td>
			<td>
				<input type="file" name="weigh_after" />
			</td>
		</tr>
		<tr>
			<td>Internal Quality Report: </td>
			<td>
				<input type="file" name="internal_quality_report" />
			</td>
		</tr>
		
		<tr class="bio" style="display:none">
			<td>Certificate for Biodiesel: </td>
			<td>
				<input type="file" name="cert_for_bio" />
			</td>
		</tr>
		
		<tr class="bio" style="display:none">
			<td>RIN:</td>
			<td><input class="validate" type="text" size="38" name="rin"/> </td>
		</tr>
		
		<tr>
			<td colspan="2">
				Notes:<br/>
				<textarea name="notes" rows="4" cols="50"></textarea>
			</td>
		</tr>

		<tr>
			<td colspan="2"><input onclick="return validate()" type="submit" value="Submit" /></td>
		</tr>
	</table>
	
<?= '</form>' ?>

<script type="text/javascript">
	$( function(){
			$("input[name=when]").datepicker()
			$("select[name=product_id]").change(product_handler)
			$(".weigh").hide()
	})
	
	function validate(){
		valid = true
		//general filled in-ness
		$(".validate").each( function(){ 
				valid = (valid && !($(this).val() == ""));
				if(this.value == "") $(this).css("border", "1px solid red");
				else $(this).css("border", "");
			} )
			
		//specific rin stuff
		rin = $("input[name=rin]")
		if(rin.val().length != 38){
			rin.css("border", "1px solid red");
			$("#rin").show();
		//	valid = false
		}else $("#rin").hide()
		
		if(!valid) $("#errors").show()
		else $("#errors").hide()
		return valid
	}
	
	
	function product_handler(){
		e =  $("select[name=product_id]")
		if(!(e.val() == 'other' || e.val() == '')){
			$.post("<?=site_url('fuel/get_product')?>",{'id':e.val()}, 
				function(data){
	
					if(data.name == "B100" || data.name == "B99") $(".bio").show()
					else $(".bio").hide()
					
					/*
					if(data.excise_taxes_paid == "1")
						$("input[name=excise_taxes_paid]").attr("checked", "checked")
					if(data.tax_credit_taken == "1")
						$("input[name=tax_credit_taken]").attr("checked", "checked")
					*/
				}, "json")
		}
	}
	
</script>