<?php

	require_once("supervisor.php");
	
	if(isset($_POST["appAction"])){//accept or deny applications
		
		/*echo "<h2>Application decision</h2>";
		echo $_POST["appAction"] . "<br><br>" . $_POST["appid"];*/
		
		$type = $_POST["appAction"];
		
		if($type=="Accept"){
			$type = "SupervisorAccepted";
		}
		else if($type=="Decline"){
			$type = "SupervisorDeclined";
		}
		
		$appid = $_POST["appid"];//id of the project application
		
		$val = rowSQL("SELECT GroupId, P_Id FROM Project_Applications WHERE ApplicationID=" . $appid);
		
		$gid = $val["GroupId"];//group id
		$pid = $val["P_Id"];//project id
				
		$q1 = runSQL("UPDATE Project_Applications SET Status='".$type."' WHERE ApplicationID=" . $appid);
		
		if(!$g1){ echo "Project_Applications field updated.<br>"; }
		else{ echo "Something went wrong.<br>"; }
		
		$oldproject = singleSQL("SELECT GroupProject FROM Groups WHERE GroupId=".$gid);
		if($oldproject == $pid){//if this was their project previously
			$q2 = runSQL("UPDATE Groups SET GroupProject='0' WHERE GroupId=".$gid);
			if(!$g2){ echo "Reverted to no project."; }
			else{ echo "Something went wrong."; }
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
		
		echo "<br><br><h3>Actions:</h3><br>";
		
		if($appRows["Status"] == "StudentAccepted" || $appRows["Status"] == "StudentDeclined"){
			echo "No actions available, application already processed.";
		}else{
			echo "<form action='' method='post'><input type='hidden' name='appid' value='".$appid."'><input name='appAction' type='submit' value='Accept' class='button button3'></form><br>";
			echo "<form action='' method='post'><input type='hidden' name='appid' value='".$appid."'><input name='appAction' type='submit' value='Decline' class='button button2'></form></span>";
		}
	}
	else{
		
		//********** edit project start **************//
		
		if(isset($_POST["editid"]) && isset($_POST["editproject"])){
			
			
			//eh
			
			echo "<span class='error'>Project #" . $_POST["editid"] . " updated.</span>";//not actually an error
			
			echo "<p>&nbsp;</p>";
		}

		//********** edit project end **************//
		
		$supervisorNum = $_SESSION['SupervisorID'];
		
		echo "<h2>Your Projects (Supervisor #".$supervisorNum.")</h2>";

		$projects = arraySQL("SELECT *, DATE_FORMAT(`Start`,'%d/%c/%Y') as Start, DATE_FORMAT(`End`,'%d/%c/%Y') as End FROM Projects WHERE Supervisor=".$supervisorNum." AND P_Id <> 0 ORDER BY P_Id ASC");
		
		$cardcontent="";
		
		foreach($projects as $thing){
			
			$cardcontent .= "<table class='cardtable'>";
			
			$cardcontent .= "<tr>";
			$cardcontent .= "<th>Description</th>";
			$cardcontent .= "<th>Requirements</th>";
			$cardcontent .= "<th>Project Type</th>";
			$cardcontent .= "<th>Skills</th>";
			$cardcontent .= "<th>Unit</th>";
			$cardcontent .= "<th class='wide'>Applications</th></tr>";
			
			$cardcontent .= "<tr>";
			
			$cardcontent .= "<td>" . $thing["Description"] . "<br><br><strong>Start:</strong> ".$thing["Start"]."<br><strong>End:</strong> ".$thing["End"];
			$cardcontent .= "<br><br><form method='post'><input type='hidden' name='editid' value='".$thing["P_Id"]."'><input type='submit' name='editproject' value='Edit Project' class='button button1'></form></td>";
			
			$cardcontent .= "<td>" . $thing["requirements"] . "</td>";
			$cardcontent .= "<td><ul><li>" . $thing["ProjectType1"] ."</li><li>". $thing["ProjectType2"] ."</li><li>". $thing["ProjectType3"] . "</li></ul></td>";
			
			$skills=explode(",",$thing["skill"]);
			
			$cardcontent .= "<td><ul>";
				foreach($skills as $skill){
					$cardcontent .= "<li>" . $skill . "</li>";
				}
			$cardcontent .= "</ul></td>";
			
			$cardcontent .= "<td>" . $thing["UnitCode"] . "</td>";	
			
			$cardcontent .= "<td class='wide'>";			
			$apps = arraySQL("SELECT * FROM Project_Applications WHERE P_Id=" . $thing["P_Id"]);
			foreach($apps as $single){
				
				$cardcontent .= "<span class='preText'>" . date('j/n/y g:ia', strtotime($single["TimeSubmitted"])) . "</span><br>";
				$cardcontent .= "#" . $single["ApplicationID"] . "</a> - <strong>\"" . substr($single["CoverLetter"], 0, 75) . "...\"</strong><br>";
				
				
				$cardcontent .= "<form action='' method='post'><input type='hidden' name='viewapplication' value='".$single["ApplicationID"]."'>";
					
					if($single["Status"]!="Applied"){//shows the supervisor their previous decisions
						$cardcontent .= "<input type='submit' value='(".$single["Status"].") View Application'";
						
						if($single["Status"]=="SupervisorAccepted"){ $cardcontent .= " class='button button1'>"; }
						else if($single["Status"]=="SupervisorDeclined"){ $cardcontent .= " class='button button2'>"; }
						else if($single["Status"]=="StudentDeclined"){ $cardcontent .= " class='button button5'>"; }
						else if($single["Status"]=="StudentAccepted"){ $cardcontent .= " class='button button3'>"; }
						else{ $cardcontent .= " class='button button1'>"; }
					}else{
						$cardcontent .= "<input type='submit' value='View Application' class='button button1'>";
					}
					
					$cardcontent .= "</form><br>";
				$cardcontent .= "";//fix the floating things (clear)
				
			}			
			$cardcontent .= "</td>";
			
			$cardcontent .= "</tr></table>";
			
			card2($thing["Name"],$cardcontent,"calc(100% - 60px)");//bro
			
			$cardcontent="";
			
		}
		
	}
	
?>
