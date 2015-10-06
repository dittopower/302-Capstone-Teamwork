<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	
	//Placeholder here
	if(isset($_GET['u'])){
		echo "Someone else's profile";
	}else{
		echo "$_SESSION[name]'s Profile";
		
		lib_login();
		lib_database();
		global $mysqli;
		$sql= mysqli_prepare($mysqli, "SELECT GPA,Skills,Blurb,LinkedIn,User_Details.Email,Facebook,Skype,Phone,FirstName,LastName FROM `deamon_INB302`.`User_Details` INNER JOIN D_Accounts ON D_Accounts.UserId=User_Details.UserId WHERE User_Details.UserId = ?");
		mysqli_stmt_bind_param($sql,"s",$_SESSION['person']);
		mysqli_stmt_execute($sql);
		mysqli_stmt_bind_result($sql,$GPA,$Skills,$Blurb,$LinkedIn,$Email,$Facebook,$Skype,$Phone,$FirstName,$LastName);
		$result = $sql->store_result();

				while (mysqli_stmt_fetch($sql)) {
					echo "<div align=\"center\">";
					echo "<br />Your <b><i>Profile</i></b> is as follows:<br />";
					echo "<b>Name:</b> ".$FirstName.' '.$LastName;
					echo "<br /><b>GPA:</b> ".$GPA;
					echo "<br /><b>Skills:</b> ".$Skills;
					echo "<br /><b>Blurb:</b> ".$Blurb;
					echo "<br /><b>LinkedIn:</b> ".$LinkedIn;
					echo "<br /><b>Email:</b> ".$Email;
					echo "<br /><b>Facebook:</b> ".$Facebook;
					echo "<br /><b>Skype:</b> ".$Skype;
					echo "<br /><b>Phone:</b> ".$Phone;
					echo "</div>"; 
					echo "<a href='./edit'>Edit profile</a>";
					}
					
		mysqli_stmt_close($sql);

		die();
		
		
		
	}

	
?>