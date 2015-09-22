<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_login();
	lib_group();
	
	$group = 1;
	
	if($group == 1){//ingroup()
		
		if(isset($_POST['apply'])){
			$newproject = $_POST['apply'];
			echo "Setting group #" . $group . " to project: " . $newproject;
			
			
		}
	
		$currentProject = singleSQL("SELECT GroupProject FROM Groups WHERE GroupId=" . $group);
		
		echo "<h2>All Projects</h2>";
		
		echo "<table>";	
		
		echo "<tr><th>Title</th>";
		echo "<th>Description</th>";
		echo "<th>Requirements</th>";
		echo "<th>Type</th>";
		echo "<th>Skills</th>";
		echo "<th>Unit</th>";
		echo "<th>Supervisor</th>";
		echo "<th>Apply</th></tr>";
		
		$projects = arraySQL("SELECT * FROM Projects ORDER BY P_Id ASC");
		
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
			echo "<td>" . $thing["Supervisor"] . "</td>";
			
			if($thing["P_Id"] == $currentProject){
				echo "<td>Selected.</td>";
			}
			else{
				echo "<td><form action='' method='post'><input type='hidden' name='apply' value='".$thing["P_Id"]."'>";
				echo "<input type='submit' class='button button1' value='Join'></form></td>";
			}
			
			echo "</tr>";
		}
		
		echo "</table>";
		
	}
	else{
		echo "You must be in a group to select a project.";	
	}
	
?>