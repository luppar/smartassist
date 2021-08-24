# SmartAssist

SmartAssist is a DIY assistant device based on a [Raspberry Pi 3 Model B+](https://www.raspberrypi.org/products/raspberry-pi-3-model-b-plus/) and the [MATRIX Creator](https://www.matrix.one/products/creator).

## Sprint 1 - ToDo's

* Setting up the board
* Sensor demo
* Use cases
* Languages / Technologies

### Sprint 1 - Outcome

- [x] Board was set up
- [x] Sensor demo is working

	- temperature sensor values are off (checking needed)
	- microphone values look weied (checking needed)

- [x] 3 use cases have been defined

	1. Setting up the device
	2. Create or edit behaviour routine (script)
	3. Check device status via web interface

- [x] Languages to be used
	- Pi + MATRIX
	  - C++, shell scripts
	- Web interface
	  - HTML5, SQL



## Sprint 2 - ToDo's

* Define procedure to create shell script from web interface input

### Sprint 2 - Outcome

- [x] Procedure for scripts has been defined
- Next Sprint in 2 Weeks

## Sprint 3 - ToDo's

* Create Website prototype

	1. Create Events / Notifications with user input
	2. Create Scritps with respect to preselected Events / Notfications
	3. Send Scripts to SpartAssist

### Sprint 3 - Outcome

- [x] Website Prototype is working
	- [x] Create Events / Notifications
	- [x] Create Scripts
	- [ ] Send script to SmartAssist



#### General Issues & Occured Problems

* Local connection with notebook & SmartAssist did not work as intended
* Sending script from localhost to localhost with 'scp' was possible

* Database - Redesig of layout probably necessary (due to redesign of script creation)
* Database - Handling of security issues (e.g., SQL Injection)
* Database - Current layout 

```
+-----------------------+
| Tables_in_SmartAssist |
+-----------------------+
| action                |
| event                 |
+-----------------------+

+----------+-------------+------+-----+---------+----------------+
| Field    | Type        | Null | Key | Default | Extra          |
+----------+-------------+------+-----+---------+----------------+
| actionId | int(5)      | NO   | PRI | NULL    | auto_increment |
| name     | varchar(50) | YES  |     | NULL    |                |
| duration | int(5)      | YES  |     | 10      |                |
+----------+-------------+------+-----+---------+----------------+

+-----------+-------------+------+-----+---------+----------------+
| Field     | Type        | Null | Key | Default | Extra          |
+-----------+-------------+------+-----+---------+----------------+
| eventId   | int(5)      | NO   | PRI | NULL    | auto_increment |
| name      | varchar(50) | YES  |     | NULL    |                |
| actionId  | int(5)      | NO   | MUL | NULL    |                |
| starttime | time        | YES  |     | NULL    |                |
+-----------+-------------+------+-----+---------+----------------+

```

* Website - Redesign necessary
* Website - Improve security (login feature)

* Script Creation - Each notification is now a seperate scipt, this was done due to enable more time flexibility


## Sprint 4 & 5 - ToDo's

* Solved local connection issues

* Website Prototype
	- [x] Create Events / Notifications
	- [x] Create Scripts
	- [x] Send script(s) to SmartAssist
	- [x] Handle SQL injection attempts
	- [x] Remove DB Entries & associated scripts via website

* New Code for LED Rendering & Audio Output
	- [x] 5 LED Patterns available 
	- [x] Notification Sound & LED Rendering at the same time
	- [x] Voice Messages after notification (optional ?)


### Sprint 4 & 5 - Outcome

* DB redesign necessary if we want audio messages

* Improve Website
 	- [x] Design
	- [ ] Login (optional)

* Make the whole system usable
	- Workflow
	- Naming
	- Documentation & Support

* Optional Additions
	- Observation Mode
	- Secure bi-directional communication




