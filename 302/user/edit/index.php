<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	
	echo "<h1>Editing $_SESSION[name]'s Profile</h1>";
					
		lib_login();
		lib_database();
		global $mysqli;

		$sql= mysqli_prepare($mysqli, "SELECT GPA,Skills,Blurb,LinkedIn,D_Accounts.Email,Facebook,Skype,Phone,FirstName,LastName FROM `deamon_INB302`.`User_Details` INNER JOIN D_Accounts ON D_Accounts.UserId=User_Details.UserId WHERE User_Details.UserId = ?");
		mysqli_stmt_bind_param($sql,"s",$_SESSION['person']);
		mysqli_stmt_execute($sql);
		mysqli_stmt_bind_result($sql,$GPA,$Skills,$Blurb,$LinkedIn,$Email,$Facebook,$Skype,$Phone,$FirstName,$LastName);
		$result = $sql->store_result();

		while (mysqli_stmt_fetch($sql)) 
		{	
			echo "<form name='reg' method='post'>";
			echo "<div><label for='email'>Email:";if(isset($e) && !$e){echo "<span style='color:red;'> Error</span>";}echo "</label>";
			echo "<input type='email' name='email' id='email' value='".$Email."'></div>";
			echo "<div><label for='gpa'>Average Grade <i>GPA</i> (optional):";if(isset($g) && !$g){echo "<span style='color:red;'> Error</span>";} echo "</label>";
			echo "<input type='number' name='gpa' id='gpa' min=0 max=7 step=0.1 value=".$GPA."></div>";
			echo "<div><label for='phone'>Phone Number:";if(isset($p) && !$p){echo "<span style='color:red;'> Error</span>";}echo "</label>";
			echo "<input type='tel' name='phone' id='phone' value='".$Phone."'></div>";
			echo "<div><label for='LinkedIn'>LinkedIn (optional):";if(isset($l) && !$l){echo "<span style='color:red;'> Error</span>";} echo "</label>";
			echo "<p>linkedin.com/profile/view?id=<b>Your-Profile</b>&trk=nav_responsive_tab_profile_pic</p>";
			echo "<input type='LinkedIn' name='LinkedIn' id='LinkedIn' value='".$LinkedIn."'></div>";
			echo "<div><label for='Skype'>Skype:";if(isset($sk) && !$sk){echo "<span style='color:red;'> Error</span>";} echo "</label>";
			echo "<input type='Skype' name='Skype' id='Skype' value='".$Skype."'></div>";
			echo "<div><label for='Facebook'>Facebook:";if(isset($f) && !$f){echo "<span style='color:red;'> Error</span>";} echo "</label>";
			echo "<p>facebook.com/<b>Your-Profile</b></p>";
			echo "<input type='Facebook' name='Facebook' id='Facebook' value='".$Facebook."'></div>";
			echo "<div><label for='Blurb'>Blurb:";if(isset($b) && !$b){echo "<span style='color:red;'> Error</span>";} echo "</label>";
			echo "<textarea type='Blurb' name='Blurb' id='Blurb'>".$Blurb."</textarea></div>";
			echo "<div><input type='submit' name='done'  text='Update Details'></form>";
		}
		
		mysqli_stmt_close($sql);
		
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
				note('UserDetails',"Set::$nuser");
				echo "Details updated.";			
			} else {
				note('UserDetails',"Failed_Set::$nuser");
				echo "Something went wrong.";
			}
			mysqli_stmt_close($sqledit);

			die();
		}
	echo "<br /><br /><a href='../'>Done</a>";
?>