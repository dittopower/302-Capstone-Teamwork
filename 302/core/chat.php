<?php

	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	lib_login();
	lib_group();
	
	if(isset($_SESSION['person'])){
			
		$user = 0;
		
		$person = $_POST['person'];
		$group = $_POST['group'];
		$send = $_POST['send'];
		
		if(isset($_POST['person']) && isset($_POST['message']) && isset($_POST['group'])){
			
			$message = strip_tags($_POST['message']);
			echo realsingleSQL("INSERT INTO Chat (UserID, UserReceive, GroupID, Message, TimeSent) VALUES(".$user.", ".$person.", ".$group.", '". $message . "', NOW())");
			
		}//send message
		else if(isset($_POST['person'])){
			runSQL("SET time_zone = '+10:00';");
			$timelim = "NOW()";
			
			$thesql="SELECT Chat.ChatID, Chat.UserID, Chat.UserReceive, D_Accounts.FirstName, D_Accounts.LastName, Chat.Message, Chat.TimeSent";
			$thesql.=" FROM Chat LEFT JOIN D_Accounts ON Chat.UserID=D_Accounts.UserId";
			$thesql.=" WHERE Chat.UserID=" .$user. " AND Chat.UserReceive=" .$person. " AND Chat.TimeSent < " . $timelim;
			
			$result1 = arraySQL($thesql);
			
			$thesql2="SELECT Chat.ChatID, Chat.UserID, Chat.UserReceive, D_Accounts.FirstName, D_Accounts.LastName, Chat.Message, Chat.TimeSent";
			$thesql2.=" FROM Chat LEFT JOIN D_Accounts ON Chat.UserID=D_Accounts.UserId";
			$thesql2.=" WHERE Chat.UserID=" .$person. " AND Chat.UserReceive=" .$user. " AND Chat.TimeSent < " . $timelim;
			
			$result2 = arraySQL($thesql2);
			
			$aa = array_merge($result1,$result2);
			sort($aa);
			echo json_encode($aa);
			
		}//retrieve person message
		else if (isset($_POST['group'])){
			
			$timelim = "NOW()";
			
			$thesql="SELECT Chat.ChatID, Chat.UserID, D_Accounts.FirstName, D_Accounts.LastName, Chat.Message, Chat.TimeSent, Chat.UserReceive";
			$thesql.=" FROM Chat LEFT JOIN D_Accounts ON Chat.UserID=D_Accounts.UserId";
			$thesql.=" WHERE Chat.GroupID=" .$group. " AND Chat.TimeSent < " . $timelim;
			
			$result = arraySQL($thesql);
			
			sort($result);
			
			echo json_encode($result);
			
		}//retrieve group messages

	}
	
	/*********************
	Chat Database Layout
	**********************
	
	ChatID
	int(11)
	
	UserID
	int(11)
	
	UserReceive (-1 if no person)
	int(11)
	
	GroupID (-1 if no group)
	int(11)
	
	Message
	text(1024)
	
	TimeSent
	timestamp()
	
	CREATE TABLE Chat(
		ChatID int(11),
		UserID int(11),
		UserReceive int(11),
		GroupID int(11),
		Message text(1024),
		TimeSent timestamp
	)
	
	*/
	
?>