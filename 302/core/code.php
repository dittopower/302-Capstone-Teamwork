<?php

///Debug Functions
	global $debug;
	$debug = isset($_GET['debug']) || isset($_POST['debug']);
	//Dump something to the page when debugging
	function debug($thisshit){
		global $debug;
		if ($debug){
			echo "<br>";
			var_dump($thisshit);
			echo "<br>";
		}
	}
	//Dump something to the page with a description.
	function Ndebug($name,$thisshit){
		global $debug;
		if ($debug){
			echo "<hr><h1>$name</h1>";
			var_dump($thisshit);
			echo "<hr>";
		}
	}
	//Dump everything
	function bugs(){
		global $debug;
		if($debug){
			echo "<hr><h1>All: </h1><h2>Variables</h2>";
			var_dump(get_defined_vars());
			echo "<h2>System Variables</h2>";
			echo "<h3>_GET</h3>";
			foreach($_GET as $key=>$val){
				echo "$key  =>  $val <br>";
			}
			echo "<h3>_POST</h3>";
			foreach($_POST as $key=>$val){
				echo "$key  =>  $val <br>";
			}
			echo "<h3>_SESSION</h3>";
			foreach($_SESSION as $key=>$val){
				echo "$key  =>  $val <br>";
			}
			echo "<h3>_SERVER</h3>";
			foreach($_SERVER as $key=>$val){
				echo "$key  =>  $val <br>";
			}
		}
	}



///Error functions
	//Throw the use to an error page. MUST be called before any output.
	function toss ($ecode){
		//header("HTTP/1.0 ".$ecode);
		echo "<h1>Error: $ecode </h1>";
		die();
	}
	
///Text Functions
	//Current Hash encryption
	function encrypt($what, $salt = "Battle", $salt2 = "Mage"){
		 return md5(md5($what.$salt).$salt2);
	}
	
	//Create Salt
	function salt($length = 64){
		return mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
	}
	
	//Make Text html safe/ready.
	function htmlescape($string){
		return htmlentities($string,ENT_QUOTES | ENT_HTML5);
	}
	
	//Return html safe/ready Text to normal Text.
	function htmlunescape($string){
		return html_entity_decode($string,ENT_QUOTES | ENT_HTML5);
	}
	
	//Process incoming text for page input
	function d_text($content){
		$content = preg_replace("/\n/","",$content);
		$content = preg_replace("/\\\l\s([^\s]+)/","<a href='$1'>$1</a>",$content);//quick link
		$content = preg_replace("/\\\L\s([^\s]+)\s([^\s]+)/","<a href='$1'>$2</a>",$content);//quick link & title
		$content = preg_replace("/\\\(h[1-6])\s([^\n\r]+)/","<$1>$2</$1>",$content);//heading
		return $content;
	}
	
	//Escape text for regex
	function d_reg_escape($content){
		$content = preg_quote($content,"/");
		return $content;
	}
	
	//Size
	function size_byte($size,$tounit="~"){
		$units = array("~" => -1,"b" => 0, "kb" => 1, "mb" => 2, "gb" => 3, "tb" => 4, "pb" => 5, "eb" => 6, "zb" => 7, "yb" => 8);
		$tounit = $units[strtolower($tounit)];
		$byte = 1024;
		$unit = 0;
		while($size/$byte >= 1 && $unit != $tounit){
			$size = round($size/$byte,2);
			$unit++;
		}
		return "$size ".strtoupper(array_search($unit,$units));
	}

	//For status messages
	function div($what){
		if(strlen($what) > 0){
		echo "<div class='status'>$what</div>";
		}
	}
	
	function card($heading='Example Card', $content='Generic content placeholder here', $size=''){
		echo "<div class='card' ";
		if($size != ''){
			echo "style='width:$size'";
		}
		echo ">";
		echo "<h3>$heading</h3><hr>";
		echo $content;
		//"<input class='button button1' type='button' value='$button'>";
		echo "</div>";
	}
	
	function card2($heading='Example Card', $content='Generic content placeholder here', $size=''){ /* I NEEDED TO ADD THIS BECAUSE I COULD STOP THE BUTTONS FLOATING */
		echo "<div class='card2' ";
		if($size != ''){
			echo "style='width:$size'";
		}
		echo ">";
		echo "<h3>$heading</h3><hr>";
		echo $content;
		//"<input class='button button1' type='button' value='$button'>";
		echo "</div>";
	}

///Page Functions
	//Page's URL
	$pageurl = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	
?>