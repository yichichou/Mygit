<?php

//GET request
//if (isset($_GET['filename'])){
date_default_timezone_set("Asia/Taipei");  //調整時區
$Time=date("Y-m-d H:i:s");
$FB_ID = $_GET['messenger_user_id'];   //messenger user id
$Answer = $_GET['Answer'];  //last user freeform input*/


$servername = 'localhost';
$dbname = 'fb_interview';
$username = 'FB';
$password = '12345678';


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
//更新特定user問題的答案

//顯示user最新的hashcode
$Selectsql="SELECT HashCode FROM `fb_chatbot_result` WHERE `FB_ID` = '$FB_ID' ORDER BY id DESC limit 1";

$Selectresult = $conn->query($Selectsql);

if ($Selectresult->num_rows > 0) {
    // output data of each row
	$row = $Selectresult->fetch_assoc();
	$HashCode=$row["HashCode"];
} else {
    echo "0 results";
}


$sql = "UPDATE `fb_chatbot_1` SET `Time`='$Time',`HashCode`='$HashCode', `Answer`='$Answer'  WHERE `FB_ID` = '$FB_ID' AND `Answer`='' ORDER BY id DESC limit 1";
//$sql = "INSERT INTO `fb_chatbot_1`(`Time`,`FB_ID`,`Gender`,`UserName`,`Phone`,`Email`,`FB_name`,`QuestionNo`,`Question`,`Answer`) VALUES ('$Time','$FB_ID','$Gender','$UserName','$Phone','$Email','$FB_name','$QuestionNo','$Question','$Answer')";


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