<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	if(isset($_POST['done'])){
		lib_login();
		
		$sql = "INSERT INTO `deamon_INB302`.`User_Details` (`UserId`, `GPA`, `Skills`, `Blurb`, `LinkedIn`, `Email`, `Facebook`, `Skype`, `Phone`) VALUES ('', NULL, '', '', NULL, NULL, NULL, NULL, NULL);";
		
		$_SESSION['registered'] = 1;
		header("Location: http://teamwork.deamon.info");
		echo "<a href='//teamwork.deamon.info/'>rediect</a>";
		die();
	}
	page();
?>
<h1>First Time Setup</h1>
<p>As this is the first time you've logged on, You need to complete your profile before continuing.</p>
<form name='reg' method='post'>
	<label for='email'>Email:</label>
	<input type='email' name='email' id='email' placeholder='email@connect.qut.edu.au'>
<br>
	<label for='gpa'>Average Grade (GPA), optional:</label>
	<input type='number' name='gpa' id='gpa' min=0 max=7 value=4>
<br>
	<label for='phone'>Phone Number:</label>
	<input type='tel' name='phone' id='phone' placeholder='0400 000 000'>
<br>
	<label for='LinkedIn'>LinkedIn: optional</label>
	<p>linkedin.com/profile/view?id=<b>Your-Profile</b>&trk=nav_responsive_tab_profile_pic</p>
	<input type='LinkedIn' name='LinkedIn' id='LinkedIn' placeholder='Your-Profile url'>
<br>
	<label for='Skype'>Skype:</label>
	<input type='Skype' name='Skype' id='Skype' placeholder='0400 000 000'>
<br>
	<label for='Facebook'>Facebook:</label>
	<p>facebook.com/<b>Your-Profile</b></p>
	<input type='Facebook' name='Facebook' id='Facebook' placeholder='Your-Profile Url'>
<br>
	<label for='Blurb'>Blurb:</label>
	<textarea type='Blurb' name='Blurb' id='Blurb' placeholder='Describe yourself, your expectations, your academics'></textarea>
<br>
	<input type='submit' name='done'  value='Update Details'>
</form>