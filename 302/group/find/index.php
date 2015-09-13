<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_group();
?>
<form>
	<h1>Find a Team</h1><hr>
	
	<label for='unit'>Unit Code</label>
	<input list='units' name='unit' id='unit'>
	<datalist id='units'>
	<?php
		$result = multiSQL("SELECT DISTINCT g.`UnitCode` FROM `Groups` g GROUP by g.`UnitCode`");
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			echo "<option value='$row[UnitCode]'>";
		}
	?>
	</datalist>
	
	<label for='type1'>Project Type Preference 1</label>
	<input list='types1' name='type1' id='type1'>
	<datalist id='types1'>
		<option value='any'>
	<?php
		$result = multiSQL("SELECT * FROM `Project_Types`");
		$types = "";
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			$types .= "<option value='$row[type]'>";
		}
		echo $types;
	?>
	</datalist>
	
	<label for='type2'>Project Type Preference 2</label>
	<input list='types2' name='type2' id='type2'>
	<datalist id='types2'>
		<option value='any'>
	<?php
		echo $types;
	?>
	</datalist>
	
	<label for='type3'>Project Type Preference 3</label>
	<input list='types3' name='type3' id='type3'>
	<datalist id='types3'>
		<option value='any'>
	<?php
		echo $types;
	?>
	</datalist>
	
	<input type='submit' name='findgroup' value='Submit'>
</form>