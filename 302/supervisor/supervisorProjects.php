<?php
		
	require_once("supervisor.php");
	
	if(isset($_POST["appAction"])){//accept or deny applications
		
		/*echo "<h2>Application decision</h2>";
		echo $_POST["appAction"] . "<br><br>" . $_POST["appid"];*/
		
		$type = $_POST["appAction"];
		
		if($type=="Accept"){ $type .= 'e'; }
		
		$appid = $_POST["appid"];//id of the project application
		
		$val = rowSQL("SELECT GroupId, P_Id FROM Project_Applications WHERE ApplicationID=" . $appid);
		
		$gid = $val["GroupId"];//group id
		$pid = $val["P_Id"];//project id
				
		$q1 = runSQL("UPDATE Project_Applications SET Status='".$type."d' WHERE ApplicationID=" . $appid);
		
		if(!$g1){ echo "Project_Applications field updated.<br>"; }
		else{ echo "Something went wrong.<br>"; }
		
		if($type == "Accept"){
			$q2 = runSQL("UPDATE Groups SET GroupProject='".$pid."' WHERE GroupId=".$gid);
			if(!$g2){ echo "Groups field updated."; }
			else{ echo "Something went wrong."; }
		}
		else{//if the project is declined after already being accepted
			$oldproject = singleSQL("SELECT GroupProject FROM Groups WHERE GroupId=".$gid);
			if($oldproject == $pid){//if this was their project previously
				$q2 = runSQL("UPDATE Groups SET GroupProject='0' WHERE GroupId=".$gid);
				if(!$g2){ echo "Reverted to no project."; }
				else{ echo "Something went wrong."; }
			}
		}
		
		echo "<br><a href='supervisorProjects.php'>Back.</a>";

	}
	else if(isset($_POST["viewapplication"])){
		
		$appid = $_POST["viewapplication"];
		
		echo "<span class='nospace'><h2>Application #".$appid."</h2>";
		
		$appRows = rowSQL("SELECT * FROM Project_Applications WHERE ApplicationID=".$appid);
		
		echo "<h3>Group:</h3> " . singleSQL("SELECT GroupName FROM Groups WHERE GroupId=" . $appRows["GroupId"]);
		echo "<br><h3>Project #".  $appRows["P_Id"] .":</h3> " . singleSQL("SELECT Name FROM Projects WHERE P_Id=" . $appRows["P_Id"]);
		
		$groupMembers = arraySQL("SELECT CONCAT(`FirstName`,' ',`LastName`) AS name, a.Username AS username FROM `D_Accounts` a JOIN `Group_Members` g WHERE g.`UserId` = a.`UserId` and `GroupId`=".$appRows["GroupId"]);
		
		/* members listing */
		echo "<br><h3>Group Members:</h3><br>";
		
		echo "<ul class='indent'>";
			foreach($groupMembers as $person){
				echo "<li><a href='/user/?u=".$person['username']."' target='_blank'>".$person['name']."</a></li>";
			}
		echo "</ul>";
		

		/* Skills listing */
		echo "<br><h3>Team Skills:</h3></br>";
		
		$groupSkills = arraySQL("SELECT Skills FROM User_Details u LEFT JOIN Group_Members g ON g.UserId=u.UserId WHERE g.GroupId=".$appRows["GroupId"]);
		
		$allskills = "";
		
		foreach($groupSkills as $skillset){
			$allskills .= $skillset["Skills"] . ",";
		}
		
		$skillArray = array_unique(explode(',', $allskills));
		$referenceArray=array();
		
		echo "<ul class='indent'>";
		foreach($skillArray as $sk){
			
			if(!in_array(strtolower(trim($sk)), $referenceArray)) {
				array_push($referenceArray, strtolower(trim($sk)));
				
				if(!trim($sk) == ""){
					echo "<li>" . trim($sk) . "</li>";
				}
			}
		}
		echo "</ul><br>";
		
		echo "<br><h3>Cover Letter:</h3><br>" . $appRows["CoverLetter"];
		echo "<br><br><h3>Status:</h3> " . $appRows["Status"];
		echo "<br><h3>Time Submitted:</h3> " . date('j/n/y g:ia', strtotime($appRows["TimeSubmitted"]));
		
		echo "<br><br><form action='' method='post'><input type='hidden' name='appid' value='".$appid."'><input name='appAction' type='submit' value='Accept' class='button button1'></form><br>";
		echo "<form action='' method='post'><input type='hidden' name='appid' value='".$appid."'><input name='appAction' type='submit' value='Decline' class='button button1'></form></span>";
		
	}
	else{
		
		$supervisorNum = $_SESSION['SupervisorID'];
		
		echo "<h2>Your Projects (Supervisor #".$supervisorNum.")</h2>";
		
		echo "<table id='applications'>";
		
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
				echo "<li><div class='left'>#" . $single["ApplicationID"] . "</a> - " . substr($single["CoverLetter"], 0, 50) . "...<br>" . date('j/n/y g:ia', strtotime($single["TimeSubmitted"]));
					echo "<form action='' method='post'><input type='hidden' name='viewapplication' value='".$single["ApplicationID"]."'>";
					
					if($single["Status"]!="Applied"){//shows the supervisor their previous decisions
						echo "<input type='submit' value='(".$single["Status"].") View Application'";
						if($single["Status"]=="Accepted"){ echo "class='button button3'>"; }
						else if($single["Status"]=="Declined"){ echo "class='button button2'>"; }
						else{ echo "class='button button1'>"; }
					}else{
						echo "<input type='submit' value='View Application' class='button button1'>";
					}
					
					echo "</form><br>";
				echo "</li>";//fix the floating things (clear)
				
			}			
			echo "</ul></td>";
			
			echo "</tr>";
		}
		
		echo "</table>";
		
	}
	
?>
