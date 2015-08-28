<HTML>

<HEAD>
	<title>Teamwork</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</HEAD>
<BODY>
	<aside><!-- sidebar -->
		<h2>Styled things m8</h2>
		<br><br>
		<input type="button" value="Press me" class="button button1">
		<br><br>
		<input type="button" value="Press me" class="button button2">
		<br><br>
		<input type="button" value="Press me" class="button button3">
		
		<br><br>
		<input type="text" placeholder="Type text here" class="textinput">
	</aside>
	
	<nav>
		<ul>
			<li><a href="./">Home</a></li>
			<li><a href="./">Not Home</a></li>
			<li><a href="./">Not Home 3</a></li>
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