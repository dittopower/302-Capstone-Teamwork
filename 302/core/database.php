<?php 
	global $mysqli;
	$mysqli = new mysqli('localhost', 'deamon_302', 'rip.Inplace2015', 'deamon_INB302');

	if ($mysqli->connect_error) {
		die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
		echo '<script>alert("Database Connection Failure!");</script>';
	}
	
	function closeCon(){
		global $mysqli;
		mysqli_close($mysqli);
	}
	register_shutdown_function('closeCon');
	
///Functions	
	function singleSQL($sql){
		global $mysqli;
		$p = mysqli_query($mysqli,$sql);
		$result = 0;
		if($p != NULL){
			$t = mysqli_fetch_array($p,MYSQLI_BOTH);
			$result = $t[0];
		}
		return $result;
	}//runs an sql statement and returns only a single result
	
	function rowSQL($sql){
		global $mysqli;
		$p = mysqli_query($mysqli,$sql);
		$row = mysqli_fetch_array($p,MYSQLI_ASSOC);
		if($row != NULL){
			return $row;
		}
		else{
			return 0;
		}
	}//runs a command that will give a result as an single row
	
	function multiSQL($sql){
		global $mysqli;
		$p = mysqli_query($mysqli,$sql);
		if($p != NULL){
			return $p;
		}//while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
		else{
			return 0;
		}
	}//runs a command that will give a result as an array
	
	function arraySQL($sql){
		global $mysqli;
		$result = mysqli_query($mysqli,$sql);
		if($result != NULL){
			$a=array();
			while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
				array_push($a,$row);
			}
			
			return $a;
			
		}
		else{
			return 0;
		}
	}//runs a command that will give a result as an array (for real)
	
	function runSQL($sql){
		global $mysqli;
		return mysqli_query($mysqli,$sql);	
	}//run a command that either passes or failes (doesn't have an output)
	
	function escapeSQL($text){
		global $mysqli;
		return mysqli_real_escape_string($mysqli,$text);
	}
	
	function dump_table($table){
		table("Select * from $table");
	}
	
	function table($sql){
		//Display Table Contents
		$result = multiSQL($sql);
		$header = 1;
		echo "<table>";
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
			if($header){
				echo "<tr>";
				foreach($row as $key=>$value){
					echo "<th>$key</th>";
				}
				echo "</tr>";
				$header = 0;
			}
			echo "<tr>";
			foreach($row as $value){
				echo "<td>$value</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}
?>