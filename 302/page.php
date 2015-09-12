<?php require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	lib_login();
	if(($_SESSION['registered'] != 1) && ($_SERVER['REQUEST_URI'] != "/first_time/")){
		header("Location: http://teamwork.deamon.info/first_time");
		echo "<a href='//teamwork.deamon.info/first_time'>rediect</a>";
		die();
	}
	?>
<HTML>

<HEAD>
	<title>Teamwork</title>
	<link rel="Shortcut Icon" href="https://esoe.qut.edu.au/static/favicon-d177589c7efc2024aefe76fe35962db2.ico">
	<link rel="stylesheet" type="text/css" href="//teamwork.deamon.info/style.css">
</HEAD>
<BODY>
	<aside><!-- sidebar -->
		<h3>Login</h3>
		<?php user_form();?>
		
		<br><br><br>
		
		<h3>Styled things m8</h3>
		<input type="button" value="Press me" class="button button1">
		<br><br>
		<input type="button" value="Press me" class="button button2">
		<br><br>
		<input type="button" value="Press me" class="button button3">
	</aside>
	
	<nav>
		<ul>
			<li><a href="./">Home/Feed</a></li>
			<li><a href="./">Files</a></li>
			<li><a href="./">Edit Profile</a></li>
		</ul>
	</nav>
	
	<section>

<!--page content start -->

<?php function myEnd(){ ?>

<!--page content end -->
			<footer>
				idk if we need a footer<br><br><br>
				Joshua Henley | Damon Jones | Emma Jackson | Will Jakobs | Declan Winter
			</footer>
		</section>
	</BODY>
	</HTML>
<?php 
}
register_shutdown_function('myEnd');
?>