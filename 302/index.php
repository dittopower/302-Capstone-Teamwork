<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	
	echo "<h1>Select a group:</h1>";
		$sql = "SELECT g.`GroupId`,`GroupName`,`GroupProject`,`UnitCode` FROM `Groups` g join Group_Members m on g.`GroupId` = m.GroupId WHERE m.UserId = '$_SESSION[person]'";
		debug($sql);
		$result = multiSQL($sql);
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			$cardcontent = "Unit: $row[UnitCode]<br>";
			$cardcontent .= "Project: $row[GroupProject]<br>";
			$cardcontent .= "<a href='http://$_SERVER[HTTP_HOST]/group?group=$row[GroupId]'><input class='button button1' type='button' value='Open'></a>";
			card($row['GroupName'],$cardcontent,200);

		}
		echo "<hr><br><form action='http://$_SERVER[HTTP_HOST]/group/find/'><input type='submit' value='Find a Group' class='button button1'></form>";

?>