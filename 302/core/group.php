<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	lib_login();
	lib_database();
	lib_files();
	
	/*
	Set a users current Group
		- Defaults to GET or POST group if not specified
		- Check that the user is in the group returns false if not.
	*/
	function group($g = ""){
		if($g == ""){
			if(is_numeric($_GET['group'])){
				$g = $_GET['group'];
			}
		}
		if($g != $_SESSION['group'] && $g != ""){
			if(singleSQL("Select 1 from Group_Members where UserId = '$_SESSION[person]' and GroupId = '$g';")){
				$_SESSION['group'] = $g;
				$_SESSION['gname'] = singleSQL("Select GroupName from `Groups` g join Group_Members m on g.`GroupId` = m.GroupId where UserId = '$_SESSION[person]' and g.GroupId = '$g';");
			}else{
				unset($_SESSION['group']);
				return false;
			}
		}
		return true;
	}
	group();
	
	function inGroup(){
		return isset($_SESSION['group']);
	}
	/*
	Get the names of members of a group.
	returns array.
	*/
	function members($group = ""){
		if($group == ""){
			$group = $_SESSION['group'];
		}
		$result = multiSQL("SELECT CONCAT(`FirstName`,' ',`LastName`) as name FROM `D_Accounts` a JOIN `Group_Members` g WHERE g.`UserId` = a.`UserId` and g.UserId != '$_SESSION[person]' and `GroupId` = '$group'");
		$text = [];
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			$text[] = $row['name'];
		}
		return $text;
	}
	
	/*
	Get the ids of members of a group.
	returns array.
	*/
	function members_id($group = ""){
		if($group == ""){
			$group = $_SESSION['group'];
		}
		$result = multiSQL("SELECT UserId as name FROM `Group_Members` WHERE UserId != '$_SESSION[person]' and `GroupId` = '$group'");
		$text = [];
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			$text[] = $row['name'];
		}
		return $text;
	}
	
	/*
	Adds a member to a group.
	*/
	function add_member($group = "", $user = "",$role = null){
		if($group == ""){
			$group = $_SESSION['group'];
		}
		if($user == ""){
			$user = $_SESSION['person'];
		}
		if(runSQL("INSERT INTO `deamon_INB302`.`Group_Members` (`GroupId`, `UserId`, `Role`) VALUES ('$group', '$user', $role);")){
			note("membership","ADDED::$user >> $group :: by $_SESSION[person]");
			return true;
		}else{
			note("membership","FAILED::$user >> $group :: by $_SESSION[person]");
			return false;
		}
	}
	
	/*
	Removes a member from a group.
	*/
	function remove_member($group = "", $user = ""){
		if($group == ""){
			$group = $_SESSION['group'];
		}
		if($user == ""){
			$user = $_SESSION['person'];
		}
		if(runSQL("DELETE FROM `deamon_INB302`.`Group_Members` WHERE `Group_Members`.`GroupId` = '$group' AND `Group_Members`.`UserId` = '$user';")){
			note("membership","REMOVED::$user >> $group :: by $_SESSION[person]");
			return true;
		}else{
			note("membership","Failed-Remove::$user >> $group :: by $_SESSION[person]");
			return false;
		}
	}
	
	/*
	Insists the user select a group before continuing.
	*/
	function group_selected(){
		if(!inGroup()){
			echo "<h1>Please select a group:</h1>";
			$sql = "SELECT g.`GroupId`,`GroupName`,`GroupProject`,`UnitCode` FROM `Groups` g join Group_Members m on g.`GroupId` = m.GroupId WHERE m.UserId = '$_SESSION[person]'";
			debug($sql);
			$result = multiSQL($sql);
			while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
				echo "<div><a href='./?group=$row[GroupId]'>$row[GroupName]</a> on $row[GroupProject] for $row[UnitCode]";
			}
			echo "<hr><h1>Or</h1><ul><li><a href='http://$_SERVER[HTTP_HOST]/group/find'>Find a Group</a></li></ul>";
			die();
		}
	}
?>