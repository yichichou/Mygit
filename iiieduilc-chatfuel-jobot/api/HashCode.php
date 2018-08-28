<?php
date_default_timezone_set("Asia/Taipei");  //調整時區
$Time=date("Y-m-d H:i:s");
$FB_ID = $_GET['messenger_user_id'];   //messenger user id
$HashCode=hash('sha256',$FB_ID.$Time);
//$HashCode=hash('sha256',random_bytes(32));
/* $Gender=$_GET['gender'];  //gender
$UserName = $_GET['user_name'];  //user_name
$Phone = $_GET['user_phone'];  //user_phone
$Email = $_GET['user_mail'];  //user_mail
$FB_name = $_GET['FB_name']; */



$servername = 'localhost';
$dbname = 'fb_interview';
$username = 'FB';
$password = '12345678';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$sql= "INSERT INTO `fb_chatbot_result`(`Time`,`HashCode`,`FB_ID`) VALUES ('$Time','$HashCode','$FB_ID')";

//$sql= "INSERT INTO `fb_chatbot_result`(`Time`,`HashCode`,`FB_ID`,`Gender`,`UserName`,`Phone`,`Email`,`FB_name`) VALUES ('$Time','$HashCode','$FB_ID','$Gender','$UserName','$Phone','$Email','$FB_name')";
$result = $conn->query($sql);




$conn->close();


?>