<?php
	
	if(isset($_SESSION['person'])){
	
		require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
		lib_database();
		
		$user = $_SESSION['person'];
		
		$person = $_POST['person'];
		$group = $_POST['group'];
		$send = $_POST['send'];
		
		if(!empty($send)){
			
			$message = strip_tags($_POST['Message']);
			
			echo singleSQL("INSERT INTO Chat UserID, UserReceive, GroupID, Message, TimeSent VALUES(".$user.", ".$person.", ".$group.", '". $message . "', NOW())");
			
		}//send message
		else if(!empty($person)){
			
			$timelim = "NOW()";
			
			$thesql="SELECT Chat.UserID, Chat.UserReceive, D_Accounts.FirstName, D_Accounts.LastName, Chat.Message, Chat.TimeSent";
			$thesql.=" FROM Chat WHERE Chat.UserID=" .$user. " AND Chat.UserReceive=" .$person. " AND Chat.TimeSent < " . $timelim
			$thesql.=" LEFT JOIN D_Accounts ON Chat.UserID=D_Accounts.UserId";
			
			$result1 = multiSQL($thesql);
			
			$thesql2="SELECT Chat.UserID, Chat.UserReceive, D_Accounts.FirstName, D_Accounts.LastName, Chat.Message, Chat.TimeSent";
			$thesql2.=" FROM Chat WHERE Chat.UserID=" .$person. " AND Chat.UserReceive=" .$user. " AND Chat.TimeSent < " . $timelim
			$thesql2.=" LEFT JOIN D_Accounts ON Chat.UserID=D_Accounts.UserId";
			
			$result2 = multiSQL($thesql2);
			
			echo json_encode(array_merge($result1,$result2));
			
		}//retrieve person message
		else if (!empty($group)){
			
			$timelim = "NOW()";
			
			$thesql="SELECT Chat.UserID, D_Accounts.FirstName, D_Accounts.LastName, Chat.Message, Chat.TimeSent, Chat.UserReceive";
			$thesql.=" FROM Chat WHERE Chat.GroupID=" .$group. " AND Chat.TimeSent < " . $timelim
			$thesql.=" LEFT JOIN D_Accounts ON Chat.UserID=D_Accounts.UserId";
			
			$result = multiSQL($thesql);
			
			echo json_encode($result);
			
		}//retrieve group messages

	}
	
	/*********************
	Chat Database Layout
	**********************
	
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
		UserID int(11),
		UserReceive int(11),
		GroupID int(11),
		Message text(1024),
		TimeSent timestamp()
	)
	
	*/
	
?>