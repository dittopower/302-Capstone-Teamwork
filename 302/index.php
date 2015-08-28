<?php
	require_once "lib.php";
	lib_perms();
	lib_login();
	
	if(isUser()){
		echo "Hi, $_SESSION[name]";
	}else{
		echo "this site..";
	}
	user_form();
?>