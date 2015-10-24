<?php
		
	require_once('supervisor.php');
	
	if($_POST['title'] != null && $_POST['description'] != null && $_POST['requirements'] != null && $_POST['type1'] != null && $_POST['type2'] != null && $_POST['type3'] != null && $_POST['skills'] != null && $_POST['unit'] != null && $_POST['start'] != null && $_POST['end'] != null && $_POST['dueby'] != null){
		$title = $_POST['title'];
		$description = $_POST['description'];
		$requirements = $_POST['requirements'];
		$type1 = $_POST['type1'];
		$type2 = $_POST['type2'];
		$type3 = $_POST['type3'];
		$skills = $_POST['skills'];
		$unit = $_POST['unit'];
		$start = $_POST['start'];
		$end = $_POST['end'];
		$dueby = $_POST['dueby'];
		
		$res = runSQL("INSERT INTO Projects (Name, ProjectType1, ProjectType2, ProjectType3, Description, skill, requirements, UnitCode, Start, End, Dueby) VALUES('".$title."', '".$type1."', '".$type2."', '".$type3."', '".$description."', '".$skills."', '".$requirements."', '".$unit."', '".$start."', '".$end."', '".$dueby."')");
			
		if($res){
			echo "Project Created.";
		}
	}
	else{
?>
		<form action='createProjects.php' method='POST' id='projcreate'>
			<?php if(isset ($_POST['title']) && $_POST['title'] == null){echo "<span id='span'>You are missisng a title</span><br>";} ?>
			<input type='text' name='title' placeholder='Project Title' value='<?php echo $_POST["title"]; ?>'><br><br>
			<?php if(isset ($_POST['description']) && $_POST['description'] == null){echo "<span id='span'>You are missisng a project description</span><br>";} ?>
			<textarea rows="4" cols="40" name='description' form='projcreate' placeholder='Project Description' value='<?php echo $_POST["description"]; ?>'><?php if(isset($_POST['description'])){echo $_POST['description'];}?></textarea><br><br>
			<?php if(isset ($_POST['requirements']) && $_POST['requirements'] == null){echo "<span id='span'>You are missisng requirements</span><br>";} ?>
			<input type='text' name='requirements' placeholder='Requirements' value='<?php echo $_POST["requirements"]; ?>'><br><br>
			<div>
				<label for='type1'>Project Type Preference 1</label>
				<?php if(isset ($_POST['type1']) && $_POST['type1'] == null){echo "<br><span id='span'>You are missisng this preference</span><br>";} ?>
				<input list='types1' name='type1' id='type1' value='<?php echo $_POST["type1"]; ?>'>
				<datalist id='types1'>
					<option value='any'>
				<?php
					$result = multiSQL('SELECT * FROM `Project_Types`');
					$types = "";
					while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
						$types .= "<option value='$row[type]'>";
					}
					echo $types;
				?>
				</datalist>
			</div>
			<div>
				<label for='type2'>Project Type Preference 2</label>
				<?php if(isset ($_POST['type2']) && $_POST['type2'] == null){echo "<br><span id='span'>You are missisng this preference</span><br>";} ?>
				<input list='types2' name='type2' id='type2' value='<?php echo $_POST["type2"]; ?>'>
				<datalist id='types2'>
					<option value='any'>
				<?php
					echo $types;
				?>
				</datalist>
			</div>
			<div>
				<label for='type3'>Project Type Preference 3</label>
				<?php if(isset ($_POST['type3']) && $_POST['type3'] == null){echo "<br><span id='span'>You are missisng this preference</span><br>";} ?>
				<input list='types3' name='type3' id='type3' value='<?php echo $_POST["type3"]; ?>'>
				<datalist id='types3'>
					<option value='any'>
				<?php
					echo $types;
				?>
				</datalist>
			</div><br>
			<?php if(isset ($_POST['skills']) && $_POST['skills'] == null){echo "<span id='span'>You are missisng the skills required</span><br>";} ?>
			<input type='text' name='skills' placeholder='Skills required' value='<?php echo $_POST["skills"]; ?>'><br><br>
			<?php if(isset ($_POST['unit']) && $_POST['unit'] == null){echo "<span id='span'>You are missisng this date</span><br>";} ?>
			<input type='text' name='unit' placeholder='Unit Code' value='<?php echo $_POST["unit"]; ?>'><br><br>
			Project Start Date:<br><?php if(isset ($_POST['start']) && $_POST['start'] == null){echo "<span id='span'>You are missisng this date</span><br>";} ?>
			<input type='date' name='start' value='<?php echo $_POST["start"]; ?>'><br><br>
			Project End Date:<?php if(isset ($_POST['end']) && $_POST['end'] == null){echo "<br><span id='span'>You are missisng this date</span><br>";} ?>
			<br><input type='date' name='end' value='<?php echo $_POST["end"]; ?>'><br><br>
			Deliverables Due Date:<br><?php if(isset ($_POST['dueby']) && $_POST['dueby'] == null){echo "<span id='span'>You are missisng this date</span><br>";} ?>
			<br><input type='date' name='dueby' value='<?php echo $_POST["dueby"]; ?>'><br><br>
			<input type='submit' name='createproj' value='Create' class='button button1'><br><br>
		</form>
<?php 
	}
/* 	
if(isset($_POST['title']) != null && $_POST['description'] != null && $_POST['requirements'] != null && $_POST['type1'] != null && $_POST['type2'] != null && $_POST['type3'] != null && $_POST['skills'] != null && $_POST['unit'] != null && $_POST['start'] != null && $_POST['end'] != null && $_POST['dueby'] != null){
if(isset($_POST['title']) && isset($_POST['description']) && isset($_POST['requirements']) && isset($_POST['type1']) && isset($_POST['type2']) && isset($_POST['type3']) && isset($_POST['skills']) && isset($_POST['unit']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['dueby'])){
 */
?>
