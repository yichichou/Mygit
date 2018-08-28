<?php
if (isset($_GET['Doc'])) {

	
 
   //messenger user id
	$Doc=$_GET['Doc'];
	
	
	//判斷是否為word doc.的檔案  
	//http://www.dewen.net.cn/q/12375/%E7%94%A8php%E6%8A%8Aword%E8%BD%AC%E6%8D%A2pdf,%E6%8F%90%E5%8F%96%E5%87%A0%E4%B8%AA%E9%A1%B5%E9%9D%A2%E7%94%9F%E6%88%90%E6%96%B0%E7%9A%84%E6%96%87%E6%A1%A3,windows%E6%93%8D%E4%BD%9C%E7%B3%BB%E7%BB%9F
	//http://www.izhangchao.com/be/be_108.html
	
	if(strpos($Doc,".pdf")===FALSE){
		$json_data = '
		{
		 "messages": [
		   {"text": "這不是pdf檔的格式喔!"}
		 ]
		}
		';
		echo $json_data;

	}
}

?>