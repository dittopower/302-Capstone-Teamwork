<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	
	echo "<h1>Select a group:</h1>";
		$sql = "SELECT g.`GroupId`,`GroupName`,`GroupProject`,`UnitCode` FROM `Groups` g join Group_Members m on g.`GroupId` = m.GroupId WHERE m.UserId = '$_SESSION[person]'";
		debug($sql);
		$result = multiSQL($sql);
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){?>
			<div class="card">
				<h3><?php echo "$row[GroupName]";?></h3><hr>
				Unit: <?php echo "$row[UnitCode]";?><br>
				Project: <?php echo "$row[GroupProject]";?><br>
				<a href='<?php echo "http://$_SERVER[HTTP_HOST]/group?group=$row[GroupId]"; ?>'><input class='button button1' type='button' value='Open'></a>
			</div>
	<?php
		}
		echo "<hr><h1><a href='http://$_SERVER[HTTP_HOST]/group/find'>Find a Group</a></h1>";
?>