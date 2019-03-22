<?php 
ob_start();
if($_COOKIE['user'] != "admin"){
	header("Location: index.php");
}
?>
<html>
<head>
<title>IPL</title>
<link rel="stylesheet" href="final.css"></link>
</head>
<body style="background: #e4e9f1">
<?php
//JAVASCRIPT VALIDATION OF NOT NULL FOR UNAME PASS PENDING
include('mysqlconnect.php');
if($_POST['Register']=="Register!"){
	$user=$_POST['uname'];
	$pass=$_POST['pass'];
	$query="select * from members where uname='".$user."'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)!=0){
		echo "User Exists! Please Try a different Uname";
	}
	else{
		if($_POST['minmatch']!="3")
			$query="insert into members values('".$user."','".md5($pass)."',".($_POST['minmatch']*100).",".$_POST['minmatch'].")";
		else
			$query="insert into members values('".$user."','".md5($pass)."',300,3)";
		//echo $query;
		mysql_query($query) or die(mysql_error());
		echo "Success! <a href=\"main.php\">Go Home</a>";
		ob_end_flush();
	}
}
ob_flush();
?>
<h1 style="text-align: center;">Registration</h1>
<br>
<br>
<form action="newuser.php" method="post">
<table align="center" border="1" class="infotable">
	<tr>
		<th colspan="2">
		New User</th>
	</tr>
	<tr>
		<td style="font-weight: bold;">UName</td>
		<td><input type="text" name="uname"></td>
	</tr>
	<tr>
		<td style="font-weight: bold;">Password</td>
		<td><input type="password" name="pass"></td>
	</tr>
	<tr>
		<td style="font-weight: bold;">Min Matches</td>
		<td><input type="text" name="minmatch" value="3"></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit"
			value="Register!" name="Register"></td>
	</tr>
</table>
</form>
</body>
</html>
