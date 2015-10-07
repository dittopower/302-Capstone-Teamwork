<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_login();
	lib_group();
	
	//User leaving group
	if(isset($_POST['quit'])){
		remove_member();
		group();
	}
	
	//Require user to be in a group
	group_selected();
	
	if(isset($_POST['vote']) && is_numeric($_POST['who']) && $_POST['who'] != $_SESSION['person']){
		switch($_POST['vote']){
			case 'Remove':
				if(runSQL("INSERT INTO `deamon_INB302`.`Group_Mod` (`User_Id`, `Group_Id`, `Action`, `Who`) VALUES ('$_SESSION[person]', '$_SESSION[group]', 'Remove', '$_POST[who]');")){
					note('member_vote',"DONE:: $_POST[vote] > $_POST[who] :: $_SESSION[person]");
				}else{
					note('member_vote',"FAILED:: $_POST[vote] > $_POST[who] :: $_SESSION[person]");
				}
				break;
			case 'Keep':
				if(runSQL("INSERT INTO `deamon_INB302`.`Group_Mod` (`User_Id`, `Group_Id`, `Action`, `Who`) VALUES ('$_SESSION[person]', '$_SESSION[group]', 'Cancel', '$_POST[who]');")){
					note('member_vote',"DONE:: $_POST[vote] > $_POST[who] :: $_SESSION[person]");
				}else{
					note('member_vote',"FAILED:: $_POST[vote] > $_POST[who] :: $_SESSION[person]");
				}
				break;
			case 'Accept':
				if(runSQL("INSERT INTO `deamon_INB302`.`Group_Mod` (`User_Id`, `Group_Id`, `Action`, `Who`) VALUES ('$_SESSION[person]', '$_SESSION[group]', 'Add', '$_POST[who]');")){
					note('member_vote',"DONE:: $_POST[vote] > $_POST[who] :: $_SESSION[person]");
				}else{
					note('member_vote',"FAILED:: $_POST[vote] > $_POST[who] :: $_SESSION[person]");
				}
				break;
			case 'Decline':
				if(runSQL("INSERT INTO `deamon_INB302`.`Group_Mod` (`User_Id`, `Group_Id`, `Action`, `Who`) VALUES ('$_SESSION[person]', '$_SESSION[group]', 'Cancel', '$_POST[who]');")){
					note('member_vote',"DONE:: $_POST[vote] > $_POST[who] :: $_SESSION[person]");
				}else{
					note('member_vote',"FAILED:: $_POST[vote] > $_POST[who] :: $_SESSION[person]");
				}
				break;
			default:
				break;
		}
	}
	
	
	/*
	* Group Members display
	*/
		//$memberids = members_id($group);
		$mymembers = arraySQL("SELECT `Username`, CONCAT(`FirstName`,' ',`LastName`) as Name FROM `D_Accounts` a join Group_Members m on m.`UserId` = a.`UserId` WHERE m.GroupId = '$_SESSION[group]' and a.UserId != '$_SESSION[person]'");
		//$i = 0;
		$cardcontent = "";
		// foreach(members($group) as $item){
			// $name = singleSQL("SELECT Username FROM D_Accounts WHERE UserId='".$memberids[$i]."'");
			// $cardcontent .= "#" . $memberids[$i] . ": <a href='/user/?u=". $name ."'>" . $item . "</a><br>";
			// $i++;
		// }
		foreach($mymembers as $item){
			$cardcontent .= "<a href='/user/?u=$item[Username]'>#$item[Username]: $item[Name]</a><br>";
		}
	card("Group Members",$cardcontent);
		
		
	/*
	* Project Information
	*/
		$cardcontent = "";
		$thing = rowSQL("SELECT Name, ProjectType1, ProjectType2, ProjectType3, Description, skill, requirements, p.UnitCode, p.Supervisor, DATE_FORMAT(`Start`, '%a %d %b %Y') as start, DATE_FORMAT(`End`, '%a %d %b %Y') as end FROM Projects p join Groups on P_Id = GroupProject WHERE GroupId='$_SESSION[group]'");
		$cardcontent .= "Project Title: <strong>" . $thing["Name"] . "</strong>";
		$cardcontent .= "<br>Project Duration: <strong>".$thing['start']." to ".$thing['end']."</strong>";
		$cardcontent .= "<br>Project Description: <strong>" . $thing["Description"] . "</strong>";
		$cardcontent .= "<br>Project Requirements: <strong>" . $thing["requirements"] . "</strong>";
		$cardcontent .= "<br>";
		$cardcontent .= "<br>Project Type: <strong>" . $thing["ProjectType1"] .", ". $thing["ProjectType2"] .", ". $thing["ProjectType3"] . "</strong>";
		$cardcontent .= "<br>Skills required: <strong>" . $thing["skill"] . "</strong>";
		$cardcontent .= "<br>";
		$cardcontent .= "<br>For Unit: <strong>" . $thing["UnitCode"] . "</strong>";
		$cardcontent .= "<br>With supervisor: <strong>" . $thing["Supervisor"] . "</strong>";
	card("Your Project Details",$cardcontent);
	
	
	/*
	* Leaving groups and voting to remove members
	*/
		//Current in progress votes
		$sql = "Select FirstName,Mod_Id,UserId  from Group_Mod join D_Accounts on who = UserId where Who != '$_SESSION[person]' and Group_Id = '$_SESSION[group]' and `Action` = 'Remove' group by Who";
		$result = multiSQL($sql);
		$cardcontent = "<h3>Current Votes</h3><hr>";
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			$cardcontent .= "<form method='POST'>";
			$cardcontent .= "<input type='text' id='r$row[Mod_Id]' name='who' hidden value='$row[UserId]'><Label for='r$row[Mod_Id]'>$row[FirstName]</label><br>";
			$cardcontent .= "<input class='button button1' type='submit' name='vote' value='Remove'>";
			$cardcontent .= "<input class='button button1' type='submit' name='vote' value='Keep'></form><hr>";
		}
		//Start a vote
		$sql = "SELECT FirstName,g.UserId FROM `Group_Members` g join D_Accounts a on g.`UserId` = a.UserId where g.`UserId` != '$_SESSION[person]' and `GroupId` = '$_SESSION[group]'";
		debug($sql);
		$result = multiSQL($sql);
		$cardcontent .= "<h3>Start Vote</h3><hr>";
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			$cardcontent .= "<form method='POST'>";
			$cardcontent .= "<div id='start_vote_person'><input type='text' id='r$row[UserId]' name='who' hidden value='$row[UserId]'><label for='r$row[UserId]'>$row[FirstName]</label><br>";
			$cardcontent .= "<input class='button button1' type='submit' name='vote' value='Remove'></div></form><br>";
		}
		//Leave
		$cardcontent .= "<div id='leave_group'><h3>Leave</h3><hr><form method='POST' onsubmit='return you_sure()'>
		<script>
		function you_sure(){
		return confirm('Are you sure you want leave the group:\"$_SESSION[gname]\". This may cause problems with your completion of assessment. ');
		}
		</script>
		<input class='button button1' type='submit' name='quit' value='Leave Group'></form></div>";
	card("Remove Member",$cardcontent);
	
	
	/*
	* Vote to add members
	*/
		//Current Votes
		$sql = "Select FirstName,Mod_Id,UserId  from Group_Mod join D_Accounts on who = UserId where Who != '$_SESSION[person]' and Group_Id = '$_SESSION[group]' and `Action` = 'Add' group by Who";
		$result = multiSQL($sql);
		$cardcontent = "<h3>Current Votes</h3><hr>";
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			$cardcontent .= "<form method='POST'>";
			$cardcontent .= "<input type='text' id='r$row[Mod_Id]' name='who' hidden value='$row[UserId]'><Label for='r$row[Mod_Id]'>$row[FirstName]</label><br>";
			$cardcontent .= "<input class='button button1' type='submit' name='vote' value='Accept'>";
			$cardcontent .= "<input class='button button1' type='submit' name='vote' value='Decline'></form><hr>";
		}
		//Start a vote
		$sql = "SELECT FirstName,g.UserId FROM `Group_Members` g join D_Accounts a on g.`UserId` = a.UserId where g.`UserId` != '$_SESSION[person]' and `GroupId` = '$_SESSION[group]'";
		debug($sql);
		$result = multiSQL($sql);
		$cardcontent .= "<h3>Start Vote</h3><hr>";
		$cardcontent .= "<form method='POST'><input type='text' name='who'><input type='submit' class='button button1' name='Vote' value='Add'></form>";
	card("Add Member",$cardcontent);
?>