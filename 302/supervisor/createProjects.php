<?php
		
	require_once('supervisor.php');
?>
<html>
	<body>
		<form action='' method='post'>
			<input type='text' name='title' value='projtitle'>
			<input type='text' name='decscription' value='projtitle'>
			<input type='text' name='requirements' value='projtitle'>
			<div>
				<label for='type1'>Project Type Preference 1</label>
				<input list='types1' name='type1' id='type1'>
				<datalist id='types1'>
					<option value='any'>
				<?php
					$result = multiSQL('SELECT * FROM `Project_Types`');
					$types = '';
					while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
						$types .= '<option value='$row[type]'>';
					}
					echo $types;
				?>
				</datalist>
			</div>
			<div>
				<label for='type2'>Project Type Preference 2</label>
				<input list='types2' name='type2' id='type2'>
				<datalist id='types2'>
					<option value='any'>
				<?php
					echo $types;
				?>
				</datalist>
			</div>
			<div>
				<label for='type3'>Project Type Preference 3</label>
				<input list='types3' name='type3' id='type3'>
				<datalist id='types3'>
					<option value='any'>
				<?php
					echo $types;
				?>
				</datalist>
			</div>
			<input type='text' name='skills' value='projtitle'>
			<input type='text' name='unit' value='projtitle'>
			<input type='submit' name='createProj' value='Create' class='button button1'>
		</form>
	</body>
</html>