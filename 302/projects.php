<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_login();
	lib_group();
	
	$group = 1;
	
	/*
	
	CREATE TABLE Project_Applications(
		ApplicationID int(11),
		P_Id int(11),
		GroupId int(11),
		CoverLetter text(1000),
		TimeSubmitted timestamp
	)
	
	*/
	 if(isset($_POST['supervisor'])){
		
		$supervisorNum = $_POST['supervisor'];
					
		echo "<h2>Your Projects (Supervisor #".$supervisorNum.")</h2>";
		
		echo "<table>";	
		
		echo "<tr><th>Title</th>";
		echo "<th>Description</th>";
		echo "<th>Requirements</th>";
		echo "<th>Type</th>";
		echo "<th>Skills</th>";
		echo "<th>Unit</th>";
		echo "<th>Applications</th></tr>";
		
		
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
			echo "<td>applications go here</td>";
			
			echo "</tr>";
		}
		
		echo "</table>";
		
	}
	else if($group == 1){//ingroup()
		
		if(isset($_POST['apply']) && isset($_POST['coverletter'])){
			$newproject = $_POST['apply'];
			
			$coverLetter = $_POST['coverletter'];
			
			echo "Group #" . $group . " applying to project #" . $newproject . "<br><br>";
			
			$result = runSQL("INSERT INTO Project_Applications (P_Id, GroupId, CoverLetter, Status, TimeSubmitted) VALUES(".$newproject.", ".$group.", '".$coverLetter."', 'Applied', now())");
			
			if($result){ echo "Project successfully applied for."; }
			else{ echo "Something went wrong.<br><br><br>" . $coverLetter; }
		}
		else if(isset($_POST['apply']) && (!isset($_POST['coverletter'])) ){ 
			
			$projectID = $_POST['apply'];
			
			$projectName = singleSQL("SELECT Name From Projects WHERE P_Id=" . $projectID);
		
		?>
			
			<h2>Applying for project #<?php echo $projectID . " (" . $projectName . ")"; ?></h2><br>
			<div class="apply">
				<form action="" method="post">
					<textarea name="coverletter" placeholder="Write your cover letter here..."></textarea><br>
					<input type="hidden" name="apply" value="<?php echo $projectID; ?>">
					<input type="submit" value="Submit Application" class="button button1">
				</form>
			</div>
			
		<?php
		}else{
	
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
				
				$app = singleSQL("SELECT ApplicationID FROM Project_Applications WHERE GroupId=" . $group . " AND P_Id=" . $thing["P_Id"]);
				
				if($app != "" && $app != 0 && $app != "0"){
					echo "<td>Applied.</td>";
				}
				else{
					echo "<td><form action='' method='post'><input type='hidden' name='apply' value='".$thing["P_Id"]."'>";
					echo "<input type='submit' class='button button1' value='Apply'></form></td>";
				}
				
				echo "</tr>";
			}
			
			echo "</table>";
			
		}//if no input
	}
	else{
		echo "You must be in a group to select a project.";	
	}	
?>
<br><br>
<form action="" method="post">
	<input type="hidden" name="supervisor" value="0">
	<input type="submit" value="Supervisor view preview" class="button button1">
</form>