<h2>List of All Reports</h2>
<h1>Internal Reports</h1>
<div class="reports">
<? // $this->load->view("fuel/reports")?>
<? $this->load->view("locations/reports")?>
<? $this->load->view("pickups/reports")?>
</div>

<?$roles = $this->session->userdata("roles") ?>
<?if($roles['r'] == '1' || $this->session->userdata("is_admin")) :?>


<?
/*echo <<<END
<h1>Regulatory Reports</h1>
<div class="reports">
<h1>RINs</h1>
	<ul>
		<li><?=anchor("rins", "All RINs")?>
			<br/>All the RINs being managed
		</li>
		<li>Quarterly Sales</li>
		<li>Separated RINs</li>
	</ul>
<h1>Excise Taxes</h1>
	<ul>
		<li>Bi-weekly </li>
		<li>Form 720</li>
	</ul>
</div>
END;
*/
?>
<? endif ?>

