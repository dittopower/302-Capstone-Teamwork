<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_media();
	lib_group();
	
	//Start Content
	group_selected();
	$uploadTxt = '';
	if($_POST['do'] == 'Delete'){//Delete files
		div(media_delete());
	}
	media_form();

	echo "<hr>";

	//Display File list
	$result = multiSQL("Select media_id, location, share, a.FirstName from D_Media m join D_Accounts a on m.`owner` = a.UserId where people = $_SESSION[group]");
	echo "<table><tr><th>File Name:</th><th>Size</th><th>Owner</th><th>Sharing Status:</th><th>Controls:</th></tr>";
	while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
		echo "<tr><td><a href='//$_SERVER[HTTP_HOST]/files/view?$row[media_id]' target='_blank'>".basename($row['location'])."</a></td><td>";
		echo size_byte(filesize($home.$row['location']))."</td><td>$row[FirstName]</td><td>";
		switch($row['share']){
			case 0:
				echo "Group Only";
				break;
			case 1:
				echo "Specific People";
				break;
			case 2:
				echo "Friends";
				break;
			case 3:
				echo "Public Link";
				break;
		}
		echo "</td><td><a href='//$_SERVER[HTTP_HOST]/files/view?$row[media_id]&download' target='_blank'>Download</a>";
		echo "<br><a href='//$_SERVER[HTTP_HOST]/edit?url=".htmlescape($row['location'])."' target='_blank'>Edit</a>";
		echo "<form method='POST'><input type='text' name='file' value='$row[media_id]' hidden><input type='submit' value='Delete' name='do'></form></td></tr>";
	}
	echo "</table>";
?>