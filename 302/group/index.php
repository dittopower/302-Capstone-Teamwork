<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_login();
	lib_group();
	
	
		group_selected();
		
		$memberids = members_id($group);
		$i = 0;
		$cardcontent = "";
		foreach(members($group) as $item){
			$cardcontent .= "#" . $memberids[$i] . ": " . $item . "<br>";
			$i++;
		}
		card("Group Members",$cardcontent);
		
		$cardcontent = "";
		$thing = rowSQL("SELECT Name, ProjectType1, ProjectType2, ProjectType3, Description, skill, requirements, p.UnitCode, Supervisor, DATE_FORMAT(`Start`, '%a %d %b %Y') as start, DATE_FORMAT(`End`, '%a %d %b %Y') as end FROM Projects p join Groups on P_Id = GroupProject WHERE GroupId='$_SESSION[group]'");
		
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
		
	//Remove users card
		$sql = "Select FirstName,Mod_Id  from Group_Mod join D_Accounts on who = UserId where Who != '$_SESSION[person]' and Group_Id = '$_SESSION[group]' and `Action` = 'Remove' group by Who";
		$result = multiSQL($sql);
		$cardcontent = "<h3>Current Votes</h3><hr>";
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			$cardcontent .= "<form method='POST'>";
			$cardcontent .= "<input type='text' id='r$row[Mod_Id]' name='who' hidden value='$row[Mod_Id]'><Label for='r$row[Mod_Id]'>$row[FirstName]</label><br>";
			$cardcontent .= "<input class='button button1' type='submit' name='vote' value='Remove'>";
			$cardcontent .= "<input class='button button1' type='submit' name='vote' value='Keep'></form><hr>";
		}
		$sql = "SELECT FirstName,g.UserId FROM `Group_Members` g join D_Accounts a on g.`UserId` = a.UserId where g.`UserId` != '$_SESSION[person]' and `GroupId` = '$_SESSION[group]'";
		debug($sql);
		$result = multiSQL($sql);
		$cardcontent .= "<h3>Start Vote</h3><hr>";
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			$cardcontent .= "<form method='POST'>";
			$cardcontent .= "<input type='text' id='r$row[UserId]' name='who' hidden value='$row[UserId]'>";
			$cardcontent .= "<input class='button button1' type='submit' name='vote' value='$row[FirstName]'></form>";
		}
		$cardcontent .= "<h3>Leave</h3><hr><form method='POST'><input class='button button1' type='submit' name='quit' value='Leave Group'></form>";
		card("Remove Member",$cardcontent);
		
	//Add users
		$sql = "Select FirstName,Mod_Id  from Group_Mod join D_Accounts on who = UserId where Who != '$_SESSION[person]' and Group_Id = '$_SESSION[group]' and `Action` = 'Add' group by Who";
		$result = multiSQL($sql);
		$cardcontent = "<h3>Current Votes</h3><hr>";
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			$cardcontent .= "<form method='POST'>";
			$cardcontent .= "<input type='text' id='r$row[Mod_Id]' name='who' hidden value='$row[Mod_Id]'><Label for='r$row[Mod_Id]'>$row[FirstName]</label><br>";
			$cardcontent .= "<input class='button button1' type='submit' name='vote' value='Accept'>";
			$cardcontent .= "<input class='button button1' type='submit' name='vote' value='Decline'></form><hr>";
		}
		$sql = "SELECT FirstName,g.UserId FROM `Group_Members` g join D_Accounts a on g.`UserId` = a.UserId where g.`UserId` != '$_SESSION[person]' and `GroupId` = '$_SESSION[group]'";
		debug($sql);
		$result = multiSQL($sql);
		$cardcontent .= "<h3>Start Vote</h3><hr>";
		$cardcontent .= "<form method='POST'><input type='text' name='who'><input type='submit' class='button button1' name='Vote' value='Add'></form>";
		card("Add Member",$cardcontent);
?>