<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_code();
	lib_perms();
	lib_group();
	lib_feed();
	lib_database();
	
	//Function for making groups
	function makeGroup($group){
		Ndebug("New Group",$group);
		global $subject;
		$sql = "INSERT INTO `deamon_INB302`.`Groups` (`GroupName`, `GroupProject`, `UnitCode`) VALUES ('Pending', '0', '$subject')";
		if(runSQL($sql)){
			debug("Group Created");
			$gid = singleSQL("Select LAST_INSERT_ID();");
			note("Groups","Created::$gid::$_SESSION[person]");
			runSQL("UPDATE `deamon_INB302`.`Groups` SET `GroupName` = '$subject G$gid' WHERE `Groups`.`GroupId` = $gid");
			$sql = "INSERT INTO `deamon_INB302`.`Group_Members` (`GroupId`, `UserId`) VALUES";
			foreach($group as $member){
				$sql .= " ('$gid', '$member'),";
			}
			$sql = trim($sql, ',');
			debug("$sql");
			if(runSQL($sql)){
				debug("Inserted?");
				note("Groups","$gid :: Inserted Members");
				foreach($group as $member){
					runSQL("DELETE FROM `deamon_INB302`.`Group_Requests` WHERE `Group_Requests`.`UserId` = '$member' AND `Group_Requests`.`UnitCode` = '$subject'");
				}
			}else{
				debug("Failed insert");
				note("Groups","$gid :: Failed Members");
			}
		}else{
			debug("Failed group creation");
			note("Groups","FAILED::$sql");
		}
	}
	
	if(isset($_GET['sub'])){
		if(strlen($_GET['sub']) < 11){
			$level = getUserLevel($_GET['sub']);
			if($level > 0){
				$subject = $_GET['sub'];
				
				//This is the automated group maker
				if(isset($_POST['G_resolve'])){
					$result = multiSQL("SELECT `UserId`, GPA, `Similar`, `PreferenceType1`, `PreferenceType2`, `PreferenceType3` FROM `Group_Requests` NATURAL join User_Details WHERE `UnitCode` = '$subject' ORDER by User_Details.GPA desc, `PreferenceType1`, `PreferenceType2`, 'PreferenceType3'");
					$fields = array();
					$students = array();
					while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
						$students[$row['UserId']] = $row;
						debug($row);
						if(isset($fields[$row['PreferenceType1']])){
							$fields[$row['PreferenceType1']][] = $row['UserId'];
						}else{
							$fields[$row['PreferenceType1']] = array();
							$fields[$row['PreferenceType1']][] = $row['UserId'];
						}
						if(isset($fields[$row['PreferenceType2']])){
							$fields[$row['PreferenceType2']][] = $row['UserId'];
						}else{
							$fields[$row['PreferenceType2']] = array();
							$fields[$row['PreferenceType2']][] = $row['UserId'];
						}
						if(isset($fields[$row['PreferenceType3']])){
							$fields[$row['PreferenceType3']][] = $row['UserId'];
						}else{
							$fields[$row['PreferenceType3']] = array();
							$fields[$row['PreferenceType3']][] = $row['UserId'];
						}
					}
					foreach($fields as $key=>$arrays){
						$fields[$key] = array_unique($arrays);
					}
					debug("primary process done");
					debug($fields);
					debug($students);
					
					
					foreach($students as $person=>$row){
						if(isset($students[$person])){///This line is only necessary because foreach loops apparently run off the variables inital state...
						debug($person);
						//find people with simlar preferences
						$ab = array_intersect($fields[$row['PreferenceType1']],$fields[$row['PreferenceType2']]);
						$ac = array_intersect($fields[$row['PreferenceType1']],$fields[$row['PreferenceType3']]);
						$bc = array_intersect($fields[$row['PreferenceType2']],$fields[$row['PreferenceType3']]);
						
						//Create one list that puts people with the most similar prefs first
						$abc = array_merge($ab,$ac,$bc);
						$matches = array_count_values($abc);
						natsort($matches);
						$matches=array_reverse($matches,true);
						unset($matches[$person]);
						debug($matches);
						
						//if there's enough similar people match a team
						if(count($matches) > 1){
							$group = array();
							$group[] = $person;
							foreach($matches as $key=>$value){
								if($row['Similar'] == 1){//check gpa
									if(($students[$key]['GPA']/$row['GPA']) > 0.75){
										$group[] = $key;
									}
								}else{
									$group[] = $key;
								}
								if(count($group) > 4){//max group forming size of 5 people
									break;
								}
							}
							if(count($group) > 1){//Make a group. minimum 2 people
								debug($group);
								foreach($group as $mem){
									unset($students[$mem]);
									foreach($fields as $key=>$value){
										if(($me = array_search($mem, $value)) !== false) {
											unset($fields[$key][$me]);
										}
									}
								}
								makeGroup($group);
							}
						}
						Ndebug("Cycle",$students);
						}
					}
					debug("secondary done");
				}//automated group maker ends here
	
				
				echo "<span class='admintables'>";
				//My groups
				$result = multiSQL("SELECT g.`GroupId`, g.`GroupName`, p.Name, count(*) as mem FROM `Groups` g join Projects p on g.`GroupProject` = p.P_Id JOIN Group_Members m on m.GroupId = g.`GroupId` WHERE g.`Supervisor` = '$_SESSION[person]' group by g.`GroupId`");
				$cardcont = "<table><tr><th>Group Name</th><th>Project</th><th>Members</th></tr>";
				while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){ 
					$cardcont .= "<tr><td><a href='http://$_SERVER[HTTP_HOST]/group/?group=$row[GroupId]'>$row[GroupName]</a></td><td>$row[Name]</td><td>Members: $row[mem]</td></tr>";
				}
				card("My Groups", $cardcont . "</table>");
				
				
				//Seeking groups
				$result = multiSQL("Select a.Username, a.FirstName from Group_Requests g join D_Accounts a  where g.UnitCode = '$subject' and g.UserId = a.UserId");
				$cardcont = "<table><tr><th>Student Id</th><th>Name</th></tr>";
				while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){ 
					$cardcont .= "<tr><td><a href='http://$_SERVER[HTTP_HOST]/user/?u=$row[Username]'>$row[Username]</a></td><td>$row[FirstName]</td></tr>";
				}
				$cardcont .= "</table><form method='POST' class='wideinput'><input type='submit' class='button button1' name='G_resolve' value='Resolve Teams'></form>";
				card("Seeking Groups", $cardcont);
				
				
				$cardcont = "";
				if($level > 1){
					
					//All groups
					$result = multiSQL("SELECT g.`GroupId`, g.`GroupName`, p.Name, count(*) as mem FROM `Groups` g join Projects p on g.`GroupProject` = p.P_Id join D_Perms d on d.what = g.UnitCode JOIN Group_Members m on m.GroupId = g.`GroupId` WHERE d.UserId = '$_SESSION[person]' group by g.`GroupId`");
					$cardcont .= "<table><tr><th>Group Name</th><th>Project</th><th>Members</th><th></th></tr>";
					while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){ 
						$cardcont .= "<tr><td><a href='http://$_SERVER[HTTP_HOST]/group/?group=$row[GroupId]'>$row[GroupName]</a></td><td>$row[Name]</td><td>Members: $row[mem]</td>";
						$cardcont .= "<td><form method='GET' action='generateReport.php' target='_blank'><input type='hidden' value='" . $row["GroupId"] . "' name='groupidreport'><input type='submit' value='Generate Report' class='button button1 smallerbtn'></form></td>";
						$cardcont .= "</tr>";
					}
					card("All $_GET[sub] Groups", $cardcont . "</table>");
					
					
					//handling staff changes
					if(isset($_POST['who'])&& is_numeric($_POST['who'])){
						if(isset($_POST['revoke'])){//delete
							runSQL("UPDATE `D_Perms` SET `level` = level-1 WHERE UserId = '$_POST[who]' and what = 'INB302';");
							runSQL("DELETE FROM `D_Perms` WHERE level = 0");
							note("$_GET[sub]","Revoking::$_POST[who]");
						}else if(isset($_POST['raise'])){//buff
							if(singleSQL("Select 1 from D_Perms where UserId='$_POST[who]' and what='$_GET[sub]'") == 1){
								runSQL("UPDATE `D_Perms` SET `level`= '2' WHERE `UserId`='$_POST[who]' and `what`='$_GET[sub]'");
							}else{
								runSQL("INSERT INTO `D_Perms`(`UserId`, `what`, `level`) VALUES ('$_POST[who]','$_GET[sub]', 1)");
							}
							note("$_GET[sub]","Raising::$_POST[who]");
						}
					}
					
					$result = multiSQL("SELECT a.UserId, a.Username, a.FirstName, IF(p.level > 1, 'Lecturer/Coordinator', 'Tutor') as Role FROM `D_Perms` p join D_Accounts a on p.UserId = a.UserId WHERE what = '$_GET[sub]'");
					
					$cardcont = "<table><tr><th>Username</th><th>Name</th><th>Role</th><th>Permissions</th></tr>";
					
					while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){ 
					
						$cardcont .= "<tr><td>$row[Username]</td><td>$row[FirstName]</td><td>$row[Role]</td>";

						$cardcont .= "<td><form method=POST class='inline'>
						<input type=text name=who value='$row[UserId]' hidden>
						<input class='button button2 inline mar' type=submit name=revoke value='-'>
						<input class='button button3 inline mar' type=submit name=raise value='+'>
						</form></td></tr>";
					}
					card("$_GET[sub] Staff", $cardcont . "</table>");
				}
				
				echo "</span>";
				
			}else{
				echo "You are not authorised for $_GET[sub]";
			}
		}else{
			echo "Invaild subject selection";
		}
	}else{
		echo "<h1>Please select subject you are authorised to monitor.</h1>";
		$result = multiSQL("Select what from D_Perms where UserId = '$_SESSION[person]'");
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){ 
			card("<a href='http://$_SERVER[HTTP_HOST]/admin?sub=$row[what]'>$row[what]</a>");
		}
	}
	
?>