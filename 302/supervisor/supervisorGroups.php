<?php
		
	require_once("supervisor.php");
					
	//display groups
	
	$supervisorNum = $_SESSION['SupervisorID'];
	
	echo "<h2>Your Groups (Supervisor #".$supervisorNum.")</h2>";
	
	echo "<table>";	
	
	echo "<tr><th>GroupId</th>";
	echo "<th>GroupName</th>";
	echo "<th>GroupProject</th>";
	echo "<th>UnitCode</th>";
	echo "<th>Group Members</th>";
	echo "<th></th><th></th></tr>";
	
	$groups = arraySQL("SELECT * FROM Groups WHERE Supervisor=".$supervisorNum);//maybe it needs to go off of the projects supervisor not the groups
	
	foreach($groups as $thing){
		echo "<tr>";
		
		echo "<td>" . $thing["GroupId"] . "</td>";
		echo "<td>" . $thing["GroupName"] . "</td>";
		echo "<td>" . $thing["GroupProject"] . "</td>";
		echo "<td>" . $thing["UnitCode"] . "</td>";
		
		echo "<td><ul>";
		
		$groupmembers = arraySQL("SELECT CONCAT(`FirstName`,' ',`LastName`) as name FROM `D_Accounts` a JOIN `Group_Members` g WHERE g.`UserId` = a.`UserId` and `GroupId` = '".$thing["GroupId"]."'");;
		
		foreach($groupmembers as $item){
			echo "<li>".$item["name"]."</li>";
		}
		echo "</ul></td>";
		
		echo "<td><input type='button' value='Generate Report' class='button button1'></td>";
		echo "<td><input type='button' value='View' class='button button1'></td>";
		
		echo "</tr>";
	}
	
	echo "</table>";
	
?>