<?php
	session_start();
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	
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
				<h3>Not a chat</h3>
				<ul>
					<li>other</li>
					<li>supervisor</li>
					<li>functionality</li>
					<li>here.</li>
				</ul>
			</div>
		</div>
	</aside>
	
	<nav>
		<ul>
			<li><a href="http://<?php echo "$_SERVER[HTTP_HOST]";?>/projects.php">View Projects</a></li>
			<li><a href="http://<?php echo "$_SERVER[HTTP_HOST]";?>/createProjects.php">Create Project</a></li>
			<li><a href="http://<?php echo "$_SERVER[HTTP_HOST]";?>/supervisorGroups.php">View Groups</a></li>
		</ul>
		<div id="logoutBtn"><?php echo $_SESSION['SupervisorID']; ?> | <form action="" method="post"><input type="submit" name="SupervisorLogout" value="Logout" class="button button1"></form></div>
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
	
	} else{  echo "You do not have permission to access this page."; exit(); } ?>
