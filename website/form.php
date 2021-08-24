<html>

	<head>
		<link rel="stylesheet" type="text/css" href="stylesheet.css" />
	</head>

	<body>
		<img src="smart_assist_logo.png" alt="SmartAssist" class="center">
		<h1>Event Creation</h1>

		<form action="processing.php" method="post">
			<div class="row">
				<input type="submit" name="submit" value="Show my events" class="changesite"> 
			</div>
		</form>

		<div class="container">
			<form action="processing.php" method="post">

				<div class="row">
					<div class="col-25">
						<label for="1">Event name:</label> 
					</div>
					<div class="col-75">
						<input type="text" id="1" name="eventname" required><br>
					</div>
				</div>
				<div class="row">
					<div class="col-25">
						<label for="2">Light pattern:</label> 
					</div>
					<div class="col-75">
						<select id="2" name="action"> 
	
						<?php
							//connect to database
							$conn = new mysqli("localhost", "www-data", "password", "SmartAssist") or die ('Cannot connect to db'); 
							if ($conn->connect_error) {
								die("Connection failed: " . $conn->connect_error);
							} 
							$sql = "select actionId, name from action";
							$result = $conn->query($sql);

							//show possible actions in drop-down menu
							if ($result->num_rows > 0) {
								while($row = $result->fetch_assoc()) {
									echo "<option value=\"" . $row["actionId"] . "\">" . $row["name"] . "</option>";
								}
							} 
							$conn->close();
						?>
		
						</select><br> 
					</div>
				</div>

				<div class="row">
					<div class="col-25">
						<label for="2">Voice message:</label> 
					</div>
					<div class="col-75">
						<select id="2" name="voiceaction"> 
							<option value="none">none</option> 
	
						<?php
							$conn = new mysqli("localhost", "www-data", "password", "SmartAssist") or die ('Cannot connect to db'); 
							if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
							} 
							$sql = "select actionId, name, displayName from voiceaction";
							$result = $conn->query($sql);

							//show possible voice actions in drop-down menu
							if ($result->num_rows > 0) {
								while($row = $result->fetch_assoc()) {
									echo "<option value=\"" . $row["name"] . "\">" . $row["displayName"] . "</option>";
								}
							} 
							$conn->close();
						?>
		
						</select><br> 
					</div>
				</div>


				<div class="row">
					<div class="col-25">
						<label for="3">Start time (24h-format):</label> 
					</div>
					<div class="col-75">
						<input type="time" id="3" name="time" required><br>
					</div>
				</div>
				<div class="row">
					<input type="submit" name="submit" value="Create event" class="submitbutton">
				</div>
			</form> 
		</div>
		<br><br><br>
		<div class="container">
			<h3>HowTo</h3>
			<ul>
				<li>You can name the events however you would like.</li>
				<li>Each event has a specific light pattern which will be accompanied by a specific and unique notification sound to alert the user.</li>
				<li>It is possible to add an optional voice message which will be played after the notification light turns off. </li>
				<li>Every event will be activated at a specific time of the day. So choose the time wisely.</li>
			</ul>
		</div>

	</body>

</html> 

