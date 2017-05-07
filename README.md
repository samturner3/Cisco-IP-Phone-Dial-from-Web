# Cisco-IP-Phone-Dial-from-Web

## Brief

A tool to dial a number on your Cisco IP Phone from your browser with authentication.

In my busy life I am always reading a phone number from the web and entering it into my cisco desk phone.
I built this app to automate this process.

## Feedback

I installed this at a high school for a receptionist as she was always typing in student's phone numbers into the desk phone form the screen.

This app saved her squinting at the screen and dramatically improved her productivity. 

## Security

Originally you could select any phone on the network to send the dial command to, however this was not a great idea in a high school.

I implemented simple authentication whereby the user must enter a generated code on the phone screen before the phone will dial. This ensures the phone being used is actually the one next to the user.

## Usage
 

1. webDial.html (IP ADDRESS, NUMBER TO DIAL) => push to dialPushCheck.php

2. dialPushCheck.php creates random code => push code to $ip-code.php file

3. CiscoIPPhoneExecute fetch $ip-code.php file and display on phone screen.

4. Code is entered in browser. IF match, => Send dial command to phone.

![alt tag](Image1.jpg)

![alt tag](Image2.jpg)

![alt tag](Image3.jpg)

![alt tag](Image4.jpg)

![alt tag](Image5.jpg)

Copyright Sam Turner.
