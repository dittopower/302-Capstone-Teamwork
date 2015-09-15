<?php
	
	if(isset($_SESSION['person'])){
	
		require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
		lib_database();
		
		$person = $_GET['p'];
		
		$group = $_GET['g'];
		
		$send = $_GET['s'];
		
		if(!empty($send)){
			
			
			
			singleSQL("INSERT INTO Chat UserID, GroupID, Message, TimeSent VALUES($user, $group, $message, NOW())");
			
		}//send message
		else if(!empty($person)){
			
		}//retrieve personal message
		else if(!empty($group)){
			
		}//retrieve group message
		
	}
	
	/*********************
	Chat Database Layout
	**********************
	
	UserID
	int(11)
	
	GroupID (-1 if no group)
	int(11)
	
	Message
	text(1024)
	
	TimeSent
	timestamp()
	
	*/
	
?>