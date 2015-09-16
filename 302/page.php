<?php require_once "$_SERVER[DOCUMENT_ROOT]/lib.php";
	lib_login();
	lib_group();
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
	
	<script src="jquery-2.1.4.min.js"></script>
	<script src="date.format.js"></script>
	
	<script>
	
		var user = "<?php echo $_SESSION['person']?>";
	
		var loop = setInterval(looping, 2000);
		
		var tabs = [{id:"g-2",name:"Global",content:""},
			{id:"g18",name:"Group 18",content:""},
			{id:"0",name:"Josh Henley",content:""},
			{id:"1",name:"Bill Gates",content:""}];
		//update this with relevant people
			
		var currentTab = "0";
		
		$( document ).ready(function() {
				
			$("#chatInput").keyup(function(event){
				if(event.keyCode == 13){
					$("#chatSend").click();
				}
			});
			
			chatInitiate();
			changeTab(0);
		
		});
				
		function getData(person, tab){
			
			var url="core/chat.php";
			
			var sendData = {person: person};
			
			if(person.charAt(0) == 'g'){
				sendData = {group: person.substring(1)};
			}//send different data if its a group
			
			$.post(url, sendData, function(data) {
				
				var jsonData = $.parseJSON(data);
				var html = "";
				
				$.each(jsonData, function(idx, obj) {
					if(tabs[tab]["content"].indexOf("<span id='cid" + obj.ChatID + "'") == -1){
						
						var tt = obj.TimeSent;
						//var timestamp = new Date(tt);
						
						//timestamp.setHours(timestamp.getHours() + 15);
						
						//var time = timestamp.format("g:ia");
						times=/-(\d\d)-(\d\d)\s(\d\d\:\d\d)/.exec(tt);
						time = times[2] + "/" + times[1] + "-" + times[3];
						
						html += "<span id='cid" + obj.ChatID + "' class='preText'>("+time+") uid" + obj.UserID + ":</span> " + obj.Message + "<br>";
					}//<span class="preText">(1:54pm) Josh:</span> chat text goes here<br>
				});
				
				tabs[tab]["content"] += html;
				
				changeTab(currentTab);//refresh chat
				
			});			

		}
	
		function looping(){
			
			getData(tabs[currentTab]["id"], currentTab);
			
		}//every 2 seconds
		
		function sendMessage(){
			
			var text = $("#chatInput").val();
			var sendTo = tabs[currentTab]["id"];
			var tgroup = "-1";
			
			if(sendTo.charAt(0) == 'g'){
				tgroup = sendTo.substring(1);
				sendTo = "-1";
			}
			
			var url="core/chat.php";
			
			$.post(url, {person: sendTo, message: text, group: tgroup}, function(data) {
				console.log("Message sent: " + sendTo + ":" + text + ":" + tgroup);
				console.log(data);
			});	

			$("#chatInput").val("");
			
		}
		
		function changeTab(tab){
			
			$("#chat-text").html(tabs[tab]["content"]);
			
			currentTab = tab;
			
			var objDiv = document.getElementById("chat-text");
			objDiv.scrollTop = objDiv.scrollHeight;
			
			looping();
			
		}
		
		function chatInitiate(){
						
			var html = "";
			
			for (var i=0; i < tabs.length; i++) {
				html += "<li><a onclick=\"changeTab('" + i + "');\" >" + tabs[i]["name"] + "</a></li>";
			}
			
			$("#ctbs").html(html);
			
			console.log("Chat tabs updated.");
			
		}
		
	</script>
	
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
					<span id="ctbs">
						<!-- Chat tabs get loaded into here. -->
					</span>
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
				<input id="chatSend" type="button" class="button-none button1" value="Send" onclick="sendMessage();">
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