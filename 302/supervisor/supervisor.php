<?php
	session_start();
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	
	lib_code();
	
	if(isset($_POST["SupervisorLogout"])){
		unset($_SESSION);
		$_SESSION = array();
		session_destroy();
		
		header("Location: /");
		exit();
	}//logout
	
	if( isset($_SESSION["SupervisorID"]) ){
		lib_database();
		//lib_group();
?>
	
<HTML>

<HEAD>
	<title>Teamwork</title>
	<link rel="Shortcut Icon" href="https://esoe.qut.edu.au/static/favicon-d177589c7efc2024aefe76fe35962db2.ico">
	<link rel="stylesheet" type="text/css" href="http://<?php echo "$_SERVER[HTTP_HOST]";?>/style.css">
	
	<script src="http://<?php echo "$_SERVER[HTTP_HOST]";?>/jquery-2.1.4.min.js"></script>
	
</HEAD>
<BODY>
	<aside><!-- sidebar -->
	
		<div id="infobox">
			<img src="http://<?php echo "$_SERVER[HTTP_HOST]";?>/qut-logo-200.jpg" class="logoimg">
			<h3>QUT || Teamwork</h3>
			<h4>Supervisor View</h4>			
		</div>
		
		<div id="chatarea">
			<div id="supervisorSide">
				<h3>Supervisor Actions</h3>
				<input type="button" value="Generate Summary Report" class='button button1'><br><br>
				<input type="button" value="Some other function" class='button button1'>
			</div>
		</div>
	</aside>
	
	<nav>
		<ul>
			<li><a href="/supervisor/supervisorProjects.php">View Projects</a></li>
			<li><a href="/supervisor/createProjects.php">Create Project</a></li>
			<li><a href="/supervisor/supervisorGroups.php">View Groups</a></li>
		</ul>
		<form id='logoutBtn' class='_pannel' method='POST' action=''>
			<?php echo singleSQL("SELECT CONCAT(FirstName, ' ', Surname) FROM Supervisor WHERE SupervisorID=".$_SESSION['SupervisorID']); ?>&nbsp;
			<input class='button button1' name="SupervisorLogout" id='logoutbutton' type='submit' value='Logout'>
		</form>
	</nav>
	
	<section>	

<!--page content start -->

<?php function myEnd(){ ?>

<!--page content end -->
			<footer>
				For QUT 2015.<br><br><br>
				Joshua Henley | Damon Jones | Emma Jackson | Will Jakobs | Declan Winter
			</footer>
		</section>
	</BODY>
	</HTML>
<?php
	}
	
	register_shutdown_function('myEnd');
	
	} else{ header("Location: http://" . $_SERVER[HTTP_HOST]); } ?>
