<html>

	<head>
		<link rel="stylesheet" type="text/css" href="stylesheet.css" />
	</head>

	<body>

		<img src="smart_assist_logo.png" alt="SmartAssist" class="center">
		<h1>Transfer</h1>

		<?php

			$names = array();
			$ids = array();
			$times = array();

			//if no scripts selected in the last step, no scripts to create
			$events = $_POST['event'];
			if(empty($events)) {

				echo("<p>No scripts selected.</p><br>");
				echo("<form action=\"processing.php\" method=\"post\">
					<div id=\"eventlist\" class=\"row\">
						<input type=\"submit\" name=\"submit\" value=\"Show my events\" class=\"changesite\">
					</div></form>");

			} else {
				$servername = "localhost";
				$username = "www-data";
				$password = "password";
				$database = "SmartAssist";

				// Create db connection
				$conn = new mysqli($servername, $username, $password, $database);

				// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
				echo "<p>Connected successfully...</p>" . "<br>";

//----------------------------create notification scripts---------------------------------------------------------------
				$infoheader = "#! /bin/sh
# start pre selected action in Background
# wait a certain amount of time and shut the notification down
# clear led array

";
				$template1 = "	echo `timeout ";
				$template2 = "s /home/pi/rendering/./"; 
				$template3 = " &`
	echo `sudo killall ";
				$template4 = "`
	echo `timeout 1s /home/pi/rendering/./clear &`

exit 0
";
				$duration = "";
				$filename = ""; 
				$patternnumber = "";
				$directory = ""; 
				$audiofile = ""; 

				$N = count($events);

				for($i=0; $i < $N; $i++) {		

					//collect info from database for each selected event
					$sql = "SELECT eventId, name, actionId, voiceaction, starttime FROM event WHERE eventId = " . $events[$i];
					$result = $conn->query($sql);

					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
	
							array_push($ids, $row["eventId"]);
							array_push($times, substr($row["starttime"], 0, -3));

							$name = str_replace(" ", "", $row["name"]). "_" . $row["eventId"] . ".sh";
							array_push($names, $name);
							$fileLocation = getenv("DOCUMENT_ROOT") . "/scripts/" . $name;
							$file = fopen($fileLocation,"w");
							if ( $file == false ){
								echo "error";
								fclose($file);
							} else {
								
								$audiofile = $row["voiceaction"];

								//query for id, duration
								$innerquery = "SELECT duration FROM action WHERE actionId = " . $row["actionId"];
								$res = $conn->query($innerquery);
								if($res->num_rows > 0) {

									if($rowi = $res->fetch_assoc()) {
				
										$GLOBALS['duration'] = $rowi["duration"];
										$GLOBALS['filename'] = "render";
										$GLOBALS['patternnumber'] = $row["actionId"];
									}	
								}
								fwrite($file, $infoheader);
								fwrite($file, $template1);
								fwrite($file, $duration);
								fwrite($file, $template2);
								fwrite($file, $filename);
								fwrite($file, " ");
								fwrite($file, $patternnumber); 
								fwrite($file, " ");
								fwrite($file, $directiory);
								fwrite($file, $audiofile); 

								fwrite($file, $template3);
								fwrite($file, $filename);
								fwrite($file, $template4);
								fclose($file);
							}
						}
					} else {
						echo "0 results";
					}
				}

//----------------------------create startup scripts--------------------------------------------------------------------
				$initinfo = "#! /bin/sh
### BEGIN INIT INFO
# Provides: matrix.startup
# Required-Start: \$syslog
# Required-Stop: \$syslog
# Default-Start: 2 3 4 5
# Default-Stop: 0 1 6
# Short-Description: matrix.startup
# Description:
### END INIT INFO

";

				$comment = "
	# Start notification scripts at a specific time
	# everything else is handled in the corresponding scripts

	echo \"/home/pi/rendering/./render 00 none &\" | at now";

				$echo1 = "
	echo \"sh /home/pi/Desktop/scripts/";
				$echo2 = "\" | at ";
				$command = "
exit 0
";
				$fileLocation = getenv("DOCUMENT_ROOT") . "/scripts/matrix.startup";
				$file = fopen($fileLocation,"w");
				if ( $file == false ){
					echo "error";
					fclose($file);
				} else {
					fwrite($file,$initinfo);
					fwrite($file, $comment);
	
					for($i = 0; $i < count($ids); $i++) {
						fwrite($file, $echo1);
						fwrite($file, $names[$i]);
						fwrite($file, $echo2);
						fwrite($file, $times[$i]);	
					}
					fwrite($file, $command);
					fclose($file);
	
					echo "Creating and sending scripts ..." . "<br>" ;
				}
	
				$destination = "./temp";
				$shellcommand = "cd /var/www/html/scripts; scp matrix.startup ";
				foreach ($names as $value) {
					$shellcommand = $shellcommand . $value . " ";
				}
				$shellcommand = $shellcommand . $destination;

				echo "<br><br>";

//---------------------send scripts to device---------------------------------------------------------------------------
				shell_exec("/usr/bin/expect sendstartup.exp");
				shell_exec("/usr/bin/expect sendscript.exp");
				shell_exec("ssh -t pi@192.168.43.200 \"sudo /sbin/shutdown -r 1\"");

				echo "<br>"."<p>Sending finished ...</p>" . "<br>" ;
			}
		?>

	</body>

</html>
