<?php

//GET request
//if (isset($_GET['filename'])){
date_default_timezone_set("Asia/Taipei");  //調整時區

$FB_ID = $_GET['messenger_user_id'];   //messenger user id
$Gender=$_GET['gender'];  //gender
$UserName = $_GET['user_name'];  //user_name
$Phone = $_GET['user_phone'];  //user_phone
$Email = $_GET['user_mail'];  //user_mail
$FB_name = $_GET['FB_name'];
$Picture= $_GET['Picture'];
$Doc=$_GET['Doc'];

$servername = 'localhost';
$dbname = 'fb_interview';
$username = 'FB';
$password = '12345678';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$sql= "UPDATE `fb_chatbot_result` SET `Gender`='$Gender',`UserName`='$UserName',`Phone`='$Phone', `Email`='$Email', `FB_name`='$FB_name',`Picture`='$Picture',`Doc`='$Doc' WHERE `FB_ID`='$FB_ID' ORDER BY id DESC limit 1";

//$sql= "INSERT INTO `fb_chatbot_result`(`FB_ID`,`Gender`,`UserName`,`Phone`,`Email`,`FB_name`,`Picture`,`Doc`) VALUES ('$FB_ID','$Gender','$UserName','$Phone','$Email','$FB_name','$Picture','$Doc')";
$result = $conn->query($sql);



/*
 if ($result->num_rows > 0) {
     output data of each row
     while($row = $result->fetch_assoc()) {
         echo $row["UserName"]."<br>";
     }
 } else {
     echo "0 results";
 }
*/
$conn->close();


?>