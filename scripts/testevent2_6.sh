#! /bin/sh
# start pre selected action in Background
# wait a certain amount of time and shut the notification down
# clear led array

	echo `timeout 10s /home/pi/rendering/./render 2 call_doctor &`
	echo `sudo killall render`
	echo `timeout 1s /home/pi/rendering/./clear &`

exit 0
