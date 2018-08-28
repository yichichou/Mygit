<html><body>

<?php
if (isset($_GET['FB_ID'])) {	
    $FB_ID = $_GET['FB_ID'];	
    // // 以外部指令的方式呼叫 R 進行繪圖
    $cmd = '"C:\Program Files\R\R-3.5.0\bin\Rscript"' . ' .\Rscript_fbchatbot1_10.R' ." $FB_ID";	
    exec($cmd);
    //header("Location:http://localhost/Report/CreateReport.php?FB_ID=$FB_ID");
}
?>
</body></html>
