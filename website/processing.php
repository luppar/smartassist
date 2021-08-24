<html>

	<head>
		<link rel="stylesheet" type="text/css" href="stylesheet.css" />
	</head>

	<body>

		<img src="smart_assist_logo.png" alt="SmartAssist" class="center">

		<h1>Events</h1>
		<br>

		<?php

			//connect to database	
			$conn = new mysqli("localhost", "www-data", "password", "SmartAssist") or die ('Cannot connect to db'); 
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			} 

			//create new event
			if ($_POST["submit"] == "Create event"){
				$name = $conn->real_escape_string($_POST["eventname"]);
				$name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $name);
				$actionId = $_POST["action"];
				$voiceaction = $_POST["voiceaction"];
				$time = $_POST["time"];

				$sql = "INSERT INTO event (name, actionId, voiceaction, starttime) 
					VALUES('".$name."', ".$actionId.", '".$voiceaction."', '".$time.":00"."')";
	
				if ($conn->query($sql) === TRUE) {
					echo "<p>New event created successfully.</p>" . "<br><br>";
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}

			//delete event or show list of events
			} else {
				//delete selected events
				if ($_POST["submit"] == "Delete selected events"){
					$events = $_POST['event'];
					if(empty($events)) {
						echo("<p>No scripts selected.</p>");
					}
					else {
						//delete event from table and delete script
						$N = count($events);
						//concatenate sql query in variable $sql
						$sql = "DELETE from event WHERE eventId IN (";
						for($i=0; $i < $N; $i++) {
					
							$sql = $sql . $events[$i] . ",";
					
							//look for name of event to determine filename of the script
							$namequery = "SELECT name from event WHERE eventId = " . $events[$i];
							$result = $conn->query($namequery);

							$filename = "";
							if ($result->num_rows > 0) {
								while($row = $result->fetch_assoc()) {
	
									//concatenate filename and delete script with this name
									$filename = $row["name"];
									$filename = "scripts/" . $filename . "_" . $events[$i] . ".sh";
									unlink($filename);
								}
							}			
						}
						$sql = substr($sql, 0, -1);
						$sql = $sql . ")";

						if ($conn->query($sql) === TRUE) {
							echo "<p>Events successfully deleted. </p>" . "<br><br>";
						} else {
							echo "Error: " . $sql . "<br>" . $conn->error;
						}
					}
				}
			}

		?>

		<form action="form.php" method="post">
			<div class="row">
				<input type="submit" value="Create new event" class="changesite">
			</div>
		</form>

		<div class="container">
			<form action="createscripts.php" method="post">
				<h3>Please select your events: </h3> 

				<?php

					//show list of events

					$sql = "select * from event";
					$result = $conn->query($sql);

					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							//output result of query in a list with checkboxes
							echo "<div class=\"row\"><label class=\"checkcontainer\">";
							echo "<input type=\"checkbox\" name=\"event[]\" value=\"" . $row["eventId"] . "\" >" . 
								$row["name"] . ", " . 	$row["starttime"] ;
							echo "<span class=\"checkmark\"></span></label></div>";
						}
					} else {
						echo "<p>No events available.</p> <br><br>";
					}

					$conn->close();
				?>


				<div class="row">
					<input type="submit" name = "submit" value="Create script(s) and upload" class="submitbutton">
				</div>

				<div class="row">
					<input type="submit" name = "submit" value="Delete selected events" formaction="processing.php" class="submitbutton" >
				</div>

			</form>
		</div>
	</body>
</html> 
