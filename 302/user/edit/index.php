<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	global $mysqli;
	echo "<a href='../' class='button button1' style='text-decoration: none;float:right;'>Return to Profile</a>";
	echo "<h1>Editing $_SESSION[name]'s Profile</h1>";
	
	//Apply changes
	if(isset($_POST['done'])){
		$g = false;
		if(isset($_POST['gpa'])){//GPA
			if(is_numeric($_POST['gpa']) && $_POST['gpa'] >= 0 && $_POST['gpa'] <= 7){
				$gpa = $_POST['gpa'];
				$g = true;
			}
		}else{
			$gpa = null;
			$g = true;
		}
		
		$e = false;
		if(isset($_POST['email'])){//Email
			if(preg_match("/.*@[A-Za-z0-9]+\.[A-Za-z]+/",$_POST['email'])){
				$email = $_POST['email'];
				$e = true;
			}
		}
		
		$p = false;
		if(isset($_POST['phone'])){//phone
			$phone = str_replace(' ', '', $_POST['phone']);
			if(preg_match("/^[0-9]+$/", $phone)){
				$p = true;
			}
		}
		
		$s = true; $skills = '';
		if(isset($_POST['skills'])){//skills
			$skills = '';
		}
		
		if(isset($_POST['Blurb'])){//blurb
			$b = true;
			$blurb = $_POST['Blurb'];
		}else{
			$b = false;
		}
		
		$sk = false;
		if(isset($_POST['Skype'])){//skype
			$sk = true;
			$skype = $_POST['Skype'];
		}
		
		$f = false;
		if(isset($_POST['Facebook'])){//facebook
			$f = true;
			$facebook = $_POST['Facebook'];
		}
		
		$l = false;
		if(isset($_POST['LinkedIn'])){//linkedin
			$l = true;
			$linked = $_POST['LinkedIn'];
		}
		
		if($g && $s && $b && $l && $e && $f && $p && $sk){
		
			$sqledit= mysqli_prepare($mysqli, "UPDATE `deamon_INB302`.`User_Details` SET GPA = ?, Skills  = ?, Blurb  = ?, LinkedIn  = ?, Email  = ?, Facebook  = ?, Skype  = ?, Phone  = ? WHERE UserId = ?");
			mysqli_stmt_bind_param($sqledit,"sssssssss",$gpa,$skills,$blurb,$linked,$email,$facebook, $skype, $phone, $_SESSION['person']);

			if(mysqli_stmt_execute($sqledit)){
				note('UserDetails',"Updated::$nuser");
				echo "<h3 class='sucess'>Details updated.</h3>";
			} else {
				note('UserDetails',"Failed_Update::$nuser");
				echo "<h3 class='error'>Error Updating Details</h3>";
			}
			mysqli_stmt_close($sqledit);
		}
	}
	
	
	//Display edit page
	$sql= mysqli_prepare($mysqli, "SELECT GPA,Skills,Blurb,LinkedIn,Email,Facebook,Skype,Phone FROM User_Details WHERE UserId = ?");
	mysqli_stmt_bind_param($sql,"s",$_SESSION['person']);
	mysqli_stmt_execute($sql);
	mysqli_stmt_bind_result($sql,$GPA,$Skills,$Blurb,$LinkedIn,$Email,$Facebook,$Skype,$Phone);
	$result = $sql->store_result();
	
	$cardcont = "";
	while (mysqli_stmt_fetch($sql)) 
	{	
		$cardcont .= "<form name='reg' method='post'>";
		$cardcont .= "<div><label for='email'>Email:";if(isset($e) && !$e){$cardcont .= "<span class='error'> Error</span>";}$cardcont .= "</label>";
		$cardcont .= "<input type='email' name='email' id='email' value='".$Email."'></div>";

		$cardcont .= "<div><label for='phone'>Phone Number:";if(isset($p) && !$p){$cardcont .= "<span class='error'> Error</span>";}$cardcont .= "</label>";
		$cardcont .= "<input type='tel' name='phone' id='phone' value='".$Phone."'></div>";
		
		$cardcont .= "<div><label for='LinkedIn'>LinkedIn (optional):";if(isset($l) && !$l){$cardcont .= "<span class='error'> Error</span>";} $cardcont .= "</label>";
		$cardcont .= "<p>linkedin.com/profile/view?id=<b class='target'>Your-Profile</b>&trk=nav_responsive_tab_profile_pic</p>";
		$cardcont .= "<input type='LinkedIn' name='LinkedIn' id='LinkedIn' value='".$LinkedIn."'></div>";
		
		$cardcont .= "<div><label for='Skype'>Skype:";if(isset($sk) && !$sk){$cardcont .= "<span class='error'> Error</span>";} $cardcont .= "</label>";
		$cardcont .= "<input type='Skype' name='Skype' id='Skype' value='".$Skype."'></div>";
		
		$cardcont .= "<div><label for='Facebook'>Facebook:";if(isset($f) && !$f){$cardcont .= "<span class='error'> Error</span>";} $cardcont .= "</label>";
		$cardcont .= "<p>facebook.com/<b class='target'>Your-Profile</b></p>";
		$cardcont .= "<input type='Facebook' name='Facebook' id='Facebook' value='".$Facebook."'></div>";
	card("Contact Details",$cardcont);
		
		$cardcont = "<div><label for='gpa'>Average Grade <i>GPA</i> (optional):";if(isset($g) && !$g){$cardcont .= "<span class='error'> Error</span>";} $cardcont .= "</label>";
		$cardcont .= "<input type='number' name='gpa' id='gpa' min=0 max=7 step=0.1 value=".$GPA."></div>";
		
		$cardcont .="Skills goes here";
	card("Academic Details",$cardcont);
		
		$cardcont = "<div><label for='Blurb'>Blurb:";if(isset($b) && !$b){$cardcont .= "<span class='error'> Error</span>";} $cardcont .= "</label>";
		$cardcont .= "<textarea type='Blurb' name='Blurb' id='Blurb'>".$Blurb."</textarea></div>";
	card("About You",$cardcont);
		
		echo "<div><input type='submit' name='done' class='button button1' value='Update Details'></form>";
	}
	mysqli_stmt_close($sql);
?>