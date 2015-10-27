<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_perms();
	
	if(isset($_GET['groupidreport'])){
		
		echo "<h1>Generate report for group #".$_GET['groupidreport']."</h1>";
		
	}else if(isset($_GET['sub'])){
		if(strlen($_GET['sub']) < 11){
			$level = getUserLevel($_GET['sub']);
			if($level > 0){
				
				echo "<span class='admintables'>";
				
				$result = multiSQL("SELECT g.`GroupId`, g.`GroupName`, p.Name, count(*) as mem FROM `Groups` g join Projects p on g.`GroupProject` = p.P_Id JOIN Group_Members m on m.GroupId = g.`GroupId` WHERE g.`Supervisor` = '$_SESSION[person]' group by g.`GroupId`");
				
				$cardcont = "<table><tr><th>Group Name</th><th>Project</th><th>Members</th></tr>";
				
				while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){ 
					$cardcont .= "<tr><td><a href='http://$_SERVER[HTTP_HOST]/group/?group=$row[GroupId]'>$row[GroupName]</a></td><td>$row[Name]</td><td>Members: $row[mem]</td></tr>";
				}
				card("My Groups", $cardcont . "</table>");
				
				$cardcont = "";
				
				if($level > 1){
					$result = multiSQL("SELECT g.`GroupId`, g.`GroupName`, p.Name, count(*) as mem FROM `Groups` g join Projects p on g.`GroupProject` = p.P_Id join D_Perms d on d.what = g.UnitCode JOIN Group_Members m on m.GroupId = g.`GroupId` WHERE d.UserId = '$_SESSION[person]' group by g.`GroupId`");
					
					$cardcont .= "<table><tr><th>Group Name</th><th>Project</th><th>Members</th><th></th></tr>";
					
					while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){ 
						$cardcont .= "<tr><td><a href='http://$_SERVER[HTTP_HOST]/group/?group=$row[GroupId]'>$row[GroupName]</a></td><td>$row[Name]</td><td>Members: $row[mem]</td>";
						
						$cardcont .= "<td><form method='GET'><input type='hidden' value='" . $row["GroupId"] . "' name='groupidreport'><input type='submit' value='Generate Report' class='button button1 smallerbtn'></form></td>";
						$cardcont .= "</tr>";
					}
					card("All $_GET[sub] Groups", $cardcont . "</table>");
					
					
				//handling staff changes
					if(isset($_POST['who'])&& is_numeric($_POST['who'])){
						if(isset($_POST['revoke'])){//delete
							runSQL("UPDATE `D_Perms` SET `level` = level-1 WHERE UserId = '92213407' and what = 'INB302';");
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