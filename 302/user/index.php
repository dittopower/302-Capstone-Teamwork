<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	
	//Placeholder here
	if(isset($_GET['u'])){
		echo "Someone else's profile";
	}else{
		echo "$_SESSION[name]'s Profile";
		echo "<a href='./edit'>Edit profile</a>";
	}
	echo "Profile coming soon";
	
?>