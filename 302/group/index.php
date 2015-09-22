<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_login();
	lib_group();
	
	
		group_selected();
		
		echo "<h2>Group Members</h2>";
		
		$memberids = members_id($group);
		$i = 0;
		foreach(members($group) as $item){
			echo "#" . $memberids[$i] . ": " . $item . "<br>";
			$i++;
		}

		echo "<br><h2>Your Project Details</h2>";
		
		$thing = rowSQL("SELECT Name, ProjectType1, ProjectType2, ProjectType3, Description, skill, requirements, UnitCode, Supervisor, DATE_FORMAT(`Start`, '%a %d %b %Y') as start, DATE_FORMAT(`End`, '%a %d %b %Y') as end FROM Projects WHERE P_Id=(SELECT GroupProject FROM Groups WHERE GroupId='$_SESSION[group]')");
		
		echo "Project Title: <strong>" . $thing["Name"] . "</strong>";
		echo "<br>Project Duration: <strong>".$thing['start']." to ".$thing['end']."</strong>";
		echo "<br>Project Description: <strong>" . $thing["Description"] . "</strong>";
		echo "<br>Project Requirements: <strong>" . $thing["requirements"] . "</strong>";
		
		echo "<br>";
		echo "<br>Project Type: <strong>" . $thing["ProjectType1"] .", ". $thing["ProjectType2"] .", ". $thing["ProjectType3"] . "</strong>";
		echo "<br>Skills required: <strong>" . $thing["skill"] . "</strong>";
		
		echo "<br>";
		echo "<br>For Unit: <strong>" . $thing["UnitCode"] . "</strong>";
		
		echo "<br>With supervisor: <strong>" . $thing["Supervisor"] . "</strong>";
?>