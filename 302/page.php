<?php require_once "$_SERVER[DOCUMENT_ROOT]/lib.php"; lib_login()?>
<HTML>

<HEAD>
	<title>Teamwork</title>
	<link rel="stylesheet" type="text/css" href="style.css">
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