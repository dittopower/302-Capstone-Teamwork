<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_login();
	lib_group();
	
	$group = $_SESSION['group'];
	
	/*
	
	CREATE TABLE Milestones(
		MID int(11),
		GroupId int(11),
		Content text(1000),
		Checked int(1)
	)
	
	*/
	
	if(ingroup()){
		
		if(isset($_POST["check1"])){
			
		}
		
		$cardcontent = "<form>";
		for($v = 0; $v < 10; $v++){			
			$cardcontent .= "
			<form>
				<input type='submit' value='&#10004;' class='button button3 mar tickBtn'> Checkbox #" . $v . "<br>
				<input type='submit' value='&#10006;' class='button button5 mar tickBtn'> Checkbox #" . $v . "<br>
			</form>";
			
		}
		$cardcontent .= "</form>";
		
		card2("Milestones", $cardcontent);
		
		card2("Add Milestone", "<form><input type='text' placeholder='Type here'><input type='submit' value='Add' class='button button4'></form>");
		
	}
	else{
		echo "You must be in a group to see this page.";	
	}
	
?>