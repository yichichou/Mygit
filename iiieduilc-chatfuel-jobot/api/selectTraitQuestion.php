<?php
if(isset($_GET['FB_ID'])){
$FB_ID=$_GET['FB_ID'];
//隨機在資料表中選十筆題目
//connection to data
$servername = "localhost";
$username = "FB";
$password = "12345678";
$dbname = "fb_interview";

$db = new mysqli($servername, $username, $password, $dbname);

$stmt="SELECT * FROM question  WHERE `Class` = 'Trait' ORDER BY RAND() LIMIT 10";
$result = $db->query($stmt);

	
    if ($result->num_rows > 0) {
        // output data of each row
		$rows_1 = array();
		while($r = mysqli_fetch_assoc($result)) {
			//echo json_encode($r);
			$rows_1[] = $r;

        }
			$str_1 = json_encode($rows_1,JSON_UNESCAPED_UNICODE); //json變字串
			echo $str_1 ;
    } else {
        echo "0 results";
    }


   $sql = "INSERT INTO user_question (FB_ID,Class, Question) VALUES ('$FB_ID','Trait', '$str_1')";//存進個人的題庫中

	if ($db->query($sql) === TRUE) {
		echo "上傳成功";
	} else {
		echo "Error: " . $sql . "<br>" . $db->error;
	}

	$db->close();
}
?>




