<?
		$one_year =  60*60*24*365;
		$time = rand( time(), time() + $one_year);
	
?>
<p>
	A statistically random day between today and one year from now is: <?=date("F d, Y", $time)?>
</p>