<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_group();
	lib_files();
	
	if(isset($_POST['findgroup'])){
		$requested = 0;
		$request = array();
		if(isset($_POST['unit'])){
			if(singleSQL("Select 1 from Unit where UnitCode = '$_POST[unit]'") == 1 && (singleSQL("SELECT 1 FROM `Group_Requests` WHERE `UserId` = '$_SESSION[person]' AND `UnitCode` = '$_POST[unit]'") != 1)){
				$request['unit'] = $_POST['unit'];
			}
		}
		if($_POST['sgpa'] == 'on'){
			$request['similar'] = 1;
		}else{
			$request['similar'] = 0;
		}
		
		if(isset($_POST['type1'])){
			$request[1] = $_POST['type1'];
		}else{
			$request[1] = null;
		}
		if(isset($_POST['type2'])){
			$request[2] = $_POST['type2'];
		}else{
			$request[2] = null;
		}
		if(isset($_POST['type3'])){
			$request[3] = $_POST['type3'];
		}else{
			$request[3] = null;
		}
		if(isset($request['unit'])){
			global $mysqli;
			$sql= mysqli_prepare($mysqli, "INSERT INTO `deamon_INB302`.`Group_Requests` (`UserId`, `UnitCode`, `Similar`,  `PreferenceType1`,  `PreferenceType2`,  `PreferenceType3`) VALUES (?, ?, ?, ?, ?, ?);");
			mysqli_stmt_bind_param($sql,"ssisss",$_SESSION['person'],$request['unit'],$request['similar'],$request[1],$request[2],$request[3]);
			if(mysqli_stmt_execute($sql)){
				$requested = 1;
				note("requests","Sucess::$_SESSION[person]::$_POST[unit]:$_POST[type1]:$_POST[type2]:$_POST[type3]");
			}else{
				note("requests","Failure::$_SESSION[person]::$_POST[unit]:$_POST[type1]:$_POST[type2]:$_POST[type3]");
			}
		}
	}
?>
<div>
<h1>Active Requests</h1>
<?php 
	$result = multiSQL("Select UnitCode, PreferenceType1 as 'Preference 1', PreferenceType2 as 'Preference 2', PreferenceType3 as 'Preference 3' from Group_Requests where UserId = '$_SESSION[person]'"); 
	while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
		card($row['UnitCode'],"Preferences: ".$row['Preference 1'].", ".$row['Preference 2'].", ".$row['Preference 3']);
	}
?>
</div>
<hr>

<form method='POST'>
<style>
form .card div {
    margin-top: 10;
    margin-bottom: 10;
}
</style>
	<h1>Find a Team</h1>
	<?php if(isset($requested)){if($requested){echo "<div class='sucess'>Request Sucessful lodged</div>";}else{echo "<div class='error'>Request Failed.</div>";}}?>
	
	<div class='card'>
	<div>
		<label for='unit'>Unit Code</label>
		<input list='units' name='unit' id='unit'>
		<datalist id='units'>
		<?php
			$result = multiSQL("SELECT * FROM Unit");
			while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
				echo "<option value='$row[UnitCode]'>$row[Unit]</option>";
			}
		?>
		</datalist>
		<?php if(isset($requested) && (!isset($request['unit']))){echo "<br><span class='error'>You already have a request for a group in this subject please resolve it before trying again.</span>";}?>
	</div>
	
	<div>
		<label for='sgpa'>Prefer Similar GPA Students?</label>
		<input type='checkbox' name='sgpa' id='sgpa'>
	</div>
	
	<div>
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
	
	<input type='submit' class='button button1' name='findgroup' value='Submit'>
	</div>

</form>