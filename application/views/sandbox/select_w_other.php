<?
	$field_name = 'fieldname';
	$options = array('choice1' => 'val1', 'choice2' => 'val2');
	$selected = 'val2';
	
	var_dump($_POST);
?>

	<form method="POST">
		<?= select_other($field_name, $options, 'val1') ?>
		<input type="submit" value="Submit" />
	</form>

<?


?>

