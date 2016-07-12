
<h1>Upload Lead Sheet CSV</h1>

<?=  $error ?>
<div>
	This page is for uploading the 

	Foodservice Lead Report.

<br/>
	To create the file:
		<ol>
			<li>Open in MS Excel or OpenOffice Calc.</li>
		<!--	<li>Delete the row at the top for headers (leaving only the data behind)</li> -->
			<li>Choose save as... and select CSV as file type (comma separated values)</li>		
		</ol>
	Upload the CSV file with this form, and your new leads will be in the system!
</div>

<form method='POST' enctype='multipart/form-data' action='<?=site_url('leads/confirm_upload')?>'>
<input type="hidden" name="which_csv" value="<?=$which_csv?>" />

<table>
<? if($which_csv == 'fs') : ?>
	<tr><td>Region:</td><td>
		<input type="text" name="region"> (Austin, San Antonio, Houston, Dallas, etc...)
	</td></tr>
<? endif ?>
	<tr><td>File:</td><td> <input type="file" name="userfile" size="30" /></td></tr>
</table>
<br />

<input type="submit" value="upload" />

</form>
