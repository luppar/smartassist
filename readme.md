![SmartAssist Logo](/images/smart_assist_logo.png)

SmartAssist is a DIY assistant device based on a [Raspberry Pi 3 Model B+](https://www.raspberrypi.org/products/raspberry-pi-3-model-b-plus/) and the [MATRIX Creator](https://www.matrix.one/products/creator) which was developed during the Software Praktikum at the University of Salzburg.

# Device & Software Setup

This guide was tested several times. The latest test was performed on the 5.02.2020 following the guide step by step. As the software and linux distributions are getting updated regularely, we unfortunately can not guarantee that this guide will still work in a couple of weeks or months.

## What you need 

You will need the following hardware components to create this amazing device.

* [Raspberry Pi 3 Model B+](https://www.raspberrypi.org/products/raspberry-pi-3-model-b-plus/)
* [MATRIX Creator](https://www.matrix.one/products/creator)
* microSD Card 8GB (or larger)
* 5V 2.5A Micro USB Power Supply


## Getting the Harware to run

* Download an OS for your Raspberry Pi
	* We recommend "Raspbian Buster with desktop and recommended software".
	* Get the image [here](https://www.raspberrypi.org/downloads/raspbian/).
* Flash the Image on your SD Card
	* A guide covering the installation of operating system images may be found [here](https://www.raspberrypi.org/documentation/installation/installing-images/).
* Insert the flashed microSD Card into your Raspberry Pi
* Attach MATRIX Creator onto the Raspberry Pi GPIO pins
* Connect the power cable and all necessary peripheral devices (e.g., keyboard, mouse and screen)

* In case you want add additional hardware buttons and use our action_buttons implementation please familiarize yourself with the [GPIO pinouts](https://matrix-io.github.io/matrix-documentation/matrix-creator/resources/pinout/).

### Device Installation Video

![Hardware Setup](/images/hardware_setup.gif)

## Setting up the Software

Before starting, ensure you have access to the terminal of your Raspberry Pi via an [SSH-session](https://www.raspberrypi.org/documentation/remote-access/ssh/) or connect a screen, mouse, and keyboard. Once you've opened the terminal, insert and run the following commands.

* Add the MATRIX repository and key
```
curl https://apt.matrix.one/doc/apt-key.gpg | sudo apt-key add -
echo "deb https://apt.matrix.one/raspbian $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/matrixlabs.list
```

* Update all repository and packages
```
sudo apt-get update
sudo apt-get upgrade
```
* Install the MATRIX HAL packages


```
sudo apt-get install matrixio-creator-init libmatrixio-creator-hal libmatrixio-creator-hal-dev
```

* Reboot your device

```
sudo reboot
```

* Download the [rendering](https://github.com/sweng-plus/sp-r1-steinberger_lupp/tree/master/smart_assistant/rendering) folder from this repo and place it in the home folder of your Raspberry Pi device

* Compile the rendering software

```
cd rendering/
make
```

* Install libSoX for audio playback

```
sudo apt-get install sox
```

* Switch audio output to analogue (headphone jack)

```
amixer cset numid=3 1
```

* Test the rendering software 

```
./render 1 call_doctor
```

If the LEDs light up and the sound is playing, everything is working as it is supposed to! 

### Helpful information

MATRIX HAL header files are installed in /usr/include/matrix_hal, in case anything is missing during compilation.
The compiled MATRIX HAL library file is installed in /usr/lib/libmatrix_creator_hal.so.

## Website Setup

This guide works for Ubuntu 18.04 with an apache webserver, set up by following [this tutorial](https://wiki.ubuntuusers.de/LAMP/).

To use the website on localhost, follow these steps:

* Ensure that your local webserver is up and running by visiting [localhost](http://localhost) in your preferred browser.

* Copy all contents of the [website-setup folder](https://github.com/sweng-plus/sp-r1-steinberger_lupp/tree/master/website/website-setup) into your webserver's Document Root folder.
Create a new folder "scripts" in your Document Root folder and make sure the user "www-data" is the owner of this folder. 

You now should be able to visit the [transfer](http://localhost/createscripts.php) page.

### Database Setup

* Install a MySql-Database to use with your webserver
```
sudo apt-get install mysql-server php-mysql
```
* Log into the database with the root user
```
mysql -u root -p
```
* Create the SmartAssist database
```
create database if not exists SmartAssist; 
```
* Create the database user for the website
```
create user 'www-data'@'localhost' identified by 'password';
grant usage on *.* to 'www-data'@'localhost' identified by 'password';  
```
* Grant the newly created user privilege to use the SmartAssist database
```
grant all privileges on SmartAssist.* to 'www-data'@'localhost';
flush privileges; 
```

* Create the necessary tables for the application
```
use SmartAssist;
CREATE TABLE action (
  actionId int(5) NOT NULL AUTO_INCREMENT,
  name varchar(50) DEFAULT NULL,
  duration int(5) DEFAULT '60',
  PRIMARY KEY (actionId)
);
CREATE TABLE event (
  eventId int(5) NOT NULL AUTO_INCREMENT,
  name varchar(50) DEFAULT NULL,
  actionId int(5) NOT NULL,
  voiceaction varchar(50) DEFAULT NULL,
  starttime time DEFAULT NULL,
  PRIMARY KEY (eventId),
  KEY actionId (actionId),
  FOREIGN KEY (actionId) REFERENCES action (actionId)
);
CREATE TABLE voiceaction (
  actionId int(5) NOT NULL AUTO_INCREMENT,
  name varchar(50) DEFAULT NULL,
  displayName varchar(50) DEFAULT NULL,
  PRIMARY KEY (actionId)
);
```

* Fill the tables with the necessary data
```
INSERT INTO voiceaction (name, displayName) VALUES 
("call_doctor", "\"Please call your doctor!\""),
("someone_called", "\"Someone called. Please check your phone!\""),
("take_medication", "\"It is time to take your medication!\""),
("take_pill", "\"Don't forget to take your pill!\""),
("time_to_sleep", "\"It's time to sleep. Please get ready for bed!\"")
;
INSERT INTO action (name) VALUES
("sleep"), ("social"), ("mobility"), ("activity")
;
```
* Exit MySql
```
quit;
```
* Now see if the website is working properly by visiting the [form](http://localhost/form.php) page and creating your first events.
