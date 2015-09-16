<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_group();
?>
<div class='members'>
<?php var_dump(members());

function members_i($group = ""){
		if($group == ""){
			$group = $_SESSION['group'];
		}
		$result = multiSQL("SELECT UserId as name FROM `Group_Members` g WHERE UserId != '$_SESSION[person]' and `GroupId` = '$group'");
		$text = [];
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			$text[] = $row['name'];
		}
		return $text;
	}
	var_dump(members_id());
?>

</div>