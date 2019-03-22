<?php
//$today = date("Y-m-d H:i:s");
$dat=time()-3600;
$today=date("Y-m-d H:i:s", $dat);
echo $today;
echo "<br>".md5("test");
?>