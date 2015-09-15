<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_files();
	$subject = $_GET['unit'];
	$debug = true;
	
	if(isset($_POST['G_resolve'])){
		debug("yo");
		$result = multiSQL("SELECT `UserId`, GPA, `Similar`, `PreferenceType1`, `PreferenceType2`, `PreferenceType3` FROM `Group_Requests` NATURAL join User_Details WHERE `UnitCode` = '$subject' ORDER by User_Details.GPA desc, `PreferenceType1`, `PreferenceType2`, 'PreferenceType3'");
		$groups = array();
		$previous = [];
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			$match = 0;
			debug($row);
					debug($previous['GPA']);
			//if($previous != ""){
			if(in_array($previous['PreferenceType1'],$row)){
				$match ++;debug("+1");
			}
			if(in_array($previous['PreferenceType2'],$row)){
				$match ++;debug("+1");
			}
			if(in_array($previous['PreferenceType3'],$row)){
				$match ++;debug("+1");
			}
			if($row['Similar'] == "1"){
				if($previous['GPA'] >= ($row['GPA'] -1)){
					$match ++;
					debug("+1");
				}else{
					debug("$row[GPA] -");
					debug($previous['GPA']);
					$match -= ($row['GPA'] - $previous['GPA']);
				}
			}
			//}
			debug("Match:$match");
			if($match > 1){//matching threshhold should be higher and decrease towards the formation date.
				$groups[$previous['group']][] = $row['UserId'];
				$row['group'] = $previous['group'];
			}else{
				$groups[][] = $row['UserId'];
				$row['group'] = count($groups)-1;
			}
			
			$previous = $row;
		}
		debug("primary process done");
		debug($groups);
		
		debug("Secondary");
		foreach($groups as $id=>$group){
			debug($id);
			debug($group);
			if(count($group) > 2){//should have an import for subject minimum group size
				debug("$id - Right size");
				if(count($group) > 5){
					//split the group
				}else{
					//form the group
					debug("don't split");
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
			}else{
				debug("$id - wrong size");
			}
		}
	}
	
	echo "<h1>".singleSQL("Select CONCAT(UnitCode,': ',Unit) from Unit where UnitCode='$subject'")."</h1><hr>";
	
	echo "<div><h2>Groups</h2>";
	table("Select GroupName as 'Group Name', Name as 'Project Name', count(*) as 'No. Members' from Groups g join Projects p join Group_Members m where `GroupProject` = P_ID and g.`GroupId` = m.GroupId and g.UnitCode = '$subject' group by GroupName");
	echo "</div>";
	
	echo "<div><h2>Seeking Groups</h2>";
	table("Select a.Username as 'Student No.', a.FirstName from Group_Requests g join D_Accounts a  where g.UnitCode = '$subject' and g.UserId = a.UserId");
	echo "</div>";
?>

<form method='POST'>
	<input type='submit' name='G_resolve' value='Resolve Teams'>
</form>