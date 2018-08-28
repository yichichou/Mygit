<?php

if (isset($_GET['FB_ID']) && isset($_GET['Question_num'])) {

date_default_timezone_set("Asia/Taipei");  //調整時區
$Time=date("Y-m-d H:i:s");
$FB_ID = $_GET['FB_ID'];   //messenger user id
$Gender=$_GET['gender'];  //gender
$UserName = $_GET['user_name'];  //user_name
$Phone = $_GET['user_phone'];  //user_phone
$Email = $_GET['user_mail'];  //user_mail
$FB_name = $_GET['FB_name'];
$Question= $_GET['Question'];
$QuestionNo = $_GET['Question_num'];
//connection to data
$servername = "localhost";
$username = "FB";
$password = "12345678";
$dbname = "fb_interview";

$conn = new mysqli($servername, $username, $password, $dbname);



$sql = "INSERT INTO `fb_chatbot_1`(`Time`,`FB_ID`,`Gender`,`UserName`,`Phone`,`Email`,`FB_name`,`QuestionNo`,`Question`) VALUES ('$Time','$FB_ID','$Gender','$UserName','$Phone','$Email','$FB_name','$QuestionNo','$Question')";
$result = $conn->query($sql);


$conn->close();


}
?>