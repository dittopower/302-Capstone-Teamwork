<?php
		
	require_once("supervisor.php");
		
	if(isset($_POST["viewapplication"])){
		
		$appid = $_POST["viewapplication"];
		
		echo "<h2>Application #".$appid."</h2>";
		
		$appRows = rowSQL("SELECT * FROM Project_Applications WHERE ApplicationID=".$appid);
		echo "<br>Group: " . singleSQL("SELECT GroupName FROM Groups WHERE GroupId=" . $appRows["GroupId"]);
		echo "<br>Project #".  $appRows["P_Id"] .": " . singleSQL("SELECT Name FROM Projects WHERE P_Id=" . $appRows["P_Id"]);
		echo "<br><br>Cover Letter: " . $appRows["CoverLetter"];
		echo "<br><br>Status: " . $appRows["Status"];
		echo "<br>Time Submitted: " . date('j/n/y g:ia', strtotime($appRows["TimeSubmitted"]));
		
	}
	else{
		
		$supervisorNum = $_SESSION['SupervisorID'];
		
		echo "<h2>Your Projects (Supervisor #".$supervisorNum.")</h2>";
		
		echo "<table>";	
		
		echo "<tr><th>Title</th>";
		echo "<th>Description</th>";
		echo "<th>Requirements</th>";
		echo "<th>Type</th>";
		echo "<th>Skills</th>";
		echo "<th>Unit</th>";
		echo "<th class='wide'>Applications</th></tr>";
		
		
		$projects = arraySQL("SELECT * FROM Projects WHERE Supervisor=".$supervisorNum." AND P_Id <> 0 ORDER BY P_Id ASC");
		
		foreach($projects as $thing){
			echo "<tr><td>" . $thing["Name"] . "</td>";
			echo "<td>" . $thing["Description"] . "</td>";
			echo "<td>" . $thing["requirements"] . "</td>";
			echo "<td><ul><li>" . $thing["ProjectType1"] ."</li><li>". $thing["ProjectType2"] ."</li><li>". $thing["ProjectType3"] . "</li></ul></td>";
			
			$skills=explode(",",$thing["skill"]);
			
			echo "<td><ul>";
				foreach($skills as $skill){
					echo "<li>" . $skill . "</li>";
				}
			echo "</ul></td>";
			
			echo "<td>" . $thing["UnitCode"] . "</td>";	
			
			
			echo "<td class='wide'><ul>";			
			$apps = arraySQL("SELECT * FROM Project_Applications WHERE P_Id=" . $thing["P_Id"]);
			foreach($apps as $single){
				echo "<li>#" . $single["ApplicationID"] . "</a> - " . substr($single["CoverLetter"], 0, 50) . "...<br>" . date('j/n/y g:ia', strtotime($single["TimeSubmitted"]));
				echo "<form action='' method='post'><input type='hidden' name='viewapplication' value='".$single["ApplicationID"]."'><input type='submit' value='View'></form></li>";
			}			
			echo "</ul></td>";
			
			echo "</tr>";
		}
		
		echo "</table>";
		
	}
	
?>
