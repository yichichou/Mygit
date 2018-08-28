<?php
if (isset($_GET['FB_ID'])) {

	
    $FB_ID = $_GET['FB_ID'];
   //messenger user id
	$Doc=$_GET['Doc'];
	date_default_timezone_set("Asia/Taipei");  //調整時區
	
	$t='.pdf';
	//$type=$_FILES['Doc']['type'];//取得檔案副檔名
	//$new_name=date("Y-m-d H-i-s")$type;
	$renew_name=date("Y-m-d-H-i-s").$t;
	
	
	//判斷是否為word doc.的檔案  
	//http://www.dewen.net.cn/q/12375/%E7%94%A8php%E6%8A%8Aword%E8%BD%AC%E6%8D%A2pdf,%E6%8F%90%E5%8F%96%E5%87%A0%E4%B8%AA%E9%A1%B5%E9%9D%A2%E7%94%9F%E6%88%90%E6%96%B0%E7%9A%84%E6%96%87%E6%A1%A3,windows%E6%93%8D%E4%BD%9C%E7%B3%BB%E7%BB%9F
	//http://www.izhangchao.com/be/be_108.html
	if(strchr($Doc,"pdf")==FALSE||strchr($Doc,"doc")==FALSE){
		$json_data = '
		{
		 "messages": [
		   {"text": "這不是可用來分析的格式喔!"},
		 ]
		}
		';
		echo $json_data;

	}
		
	
	$chi_name=iconv("BIG-5","UTF-8",$renew_name);
	//$file_path_R='C:\\xampp\\htdocs\\Report\\chatbot\\file\\'.$chi_name;  //R檔案讀路徑的方法
	//echo $file_path_R;
	$servername = 'localhost';
	$dbname = 'fb_interview';
	$username = 'FB';
	$password = '12345678';
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	$sql="UPDATE `fb_chatbot_result` SET `doc_copy`='$chi_name' WHERE `FB_ID`='$FB_ID' AND `Doc`='$Doc' ORDER BY `id` DESC limit 1";
	
	$result = $conn->query($sql);
	
	$conn->close();
	#move_uploaded_file($Doc,$renew_name);//複製檔案(檔案,新路徑)
	copy($Doc,'file/'.$chi_name);
	//move_uploaded_file($Doc,'file/'.$chi_name);//複製檔案(檔案,新路徑)
	
	echo '<br/><a href="file/'.$chi_name.'">檢視您上傳的履歷</a>';//顯示檔案路徑


//Call python EXE (pdf履歷)
	if(strchr($Doc,"pdf")==TRUE){
		//Call EXE
			$path='C:\xampp\htdocs\JoBot\api\dist';
			chdir($path);
			//$command_api='activate api';
			//shell_exec($command_api);
			$command = 'START PDFjobotcv_analysis.exe '.$FB_ID;
			$output = shell_exec($command);
	}
//Call python EXE (docx履歷)
	if(strchr($Doc,"docx")==TRUE){
		//Call EXE
			$path='C:\xampp\htdocs\JoBot\api\dist';
			chdir($path);
			//$command_api='activate api';
			//shell_exec($command_api);
			$command = 'START DOCXjobotcv_analysis.exe '.$FB_ID;
			$output = shell_exec($command);
	}
//Call python EXE (doc履歷)
	if(strchr($Doc,"doc")==TRUE&&strchr($Doc,"docx")==FALSE){
		//Call EXE
			$path='C:\xampp\htdocs\JoBot\api\dist';
			chdir($path);
			//$command_api='activate api';
			//shell_exec($command_api);
			$command = 'START DOCjobotcv_analysis.exe '.$FB_ID;
			$output = shell_exec($command);
	}
	/*
    // // 以外部指令的方式呼叫 R 進行繪圖
    $cmd = '"C:\Program Files\R\R-3.5.0\bin\Rscript"' . ' .\Rscript_CV.R' ." $FB_ID";	
	
    exec($cmd);
	*/
    //header("Location:http://localhost/Report/CreateReport.php?FB_ID=$FB_ID");
}

?>