<page begining header, nv  whatever here>

<!--page content start -->


<!--page content end -->
<?php
function myend(){
?>
	<footer body whaterever>
	</body>
<?php 
}
register_shutdown_function('myEnd');
?>