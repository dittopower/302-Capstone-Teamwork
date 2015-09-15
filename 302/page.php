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
	
		<div id="infobox">
			<img src="/qut-logo-200.jpg" class="logoimg">
			<h3>QUT || Teamwork</h3>
			<h4>Current Team: Team 18</h4>			
		</div>
		
		<div id="chatarea">
		
			<div id="chat-tabs">
				<ul>
					<li><a href="#global">Global</a></li>
					<li><a href="#team18">Team 18</a></li>
					<li><a href="#joshhenley">Josh Henley</a></li>
					<li><a href="#billgates">Bill Gates</a></li>
				</ul>
				<div id="logoutBtn"><?php user_form();?></div>
			</div>
			
			<div id="chat-text">
				<span class="preText">(1:54pm) Josh:</span> chat text goes here<br>
				<span class="preText">(1:55pm) Josh:</span> something like this<br>
				<span class="preText">(1:56pm) Josh:</span> some more text here
			</div>
			
			<div id="chatControls">
				<input id="chatInput" type="text" placeholder="Type a message here...">
				<input id="chatSend" type="button" class="button-none button1" value="Send">
			</div>
		
		</div>
	</aside>
	
	<nav>
		<ul>
			<li><a href="./">Home/Feed</a></li>
			<li><a href="files.php">Files</a></li>
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