<?php
header("Location: index.php");
setcookie("user","",time()-3600);
setcookie("id","",time()-3600); 
?>