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

$QuesNum = $_GET['Question_num'];
//connection to data
$servername = "localhost";
$username = "FB";
$password = "12345678";
$dbname = "fb_interview";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql="SELECT Question FROM user_question  WHERE `FB_ID` = '$FB_ID' AND `Class`='Trait' ORDER BY id DESC limit 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $json_data = json_decode($row["Question"]);
		
    }
} else {
    echo "0 results";
}


$Question=$json_data[$QuesNum]->Question;

$QuestionNo=$json_data[$QuesNum]->QuestionNo;

$sql = "INSERT INTO `fb_chatbot_1`(`Time`,`FB_ID`,`Gender`,`UserName`,`Phone`,`Email`,`FB_name`,`QuestionNo`,`Question`) VALUES ('$Time','$FB_ID','$Gender','$UserName','$Phone','$Email','$FB_name','$QuestionNo','$Question')";
$result = $conn->query($sql);


$conn->close();


$String='{"messages": [{"text": "'. $json_data[$QuesNum]->Question.'"}]}';






$String1=
'{
 "messages": [
   {"text": "Welcome to the Chatfuel Rockets!"}
 ]
}';


echo $String;

}
?>