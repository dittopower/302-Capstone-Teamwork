<?php
	require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	page();
	lib_group();
?>
<div class='members'>
<?php var_dump(members());?>
</div>