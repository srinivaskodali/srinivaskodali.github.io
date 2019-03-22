<?php
include('mysqlconnect.php');
$bid=100; 
if($_COOKIE['user'] == ""){
	header("Location: index.php");
}
else{
	$user=$_COOKIE['user'];
	$pass=$_COOKIE['id'];
	$query="select * from members where uname='".$user."'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)!=0){
		$row=mysql_fetch_array($result);
		if($pass!=$row[1]){
			header("Location: index.php");
			setcookie('user','',time()-3600);
		}
	}
	
}
?>
<html>
<head>
<link rel="stylesheet" href="final.css"></link>
<title>IPL</title>
<script type="text/javascript">
function show_prompt()
{
var name=prompt("Please enter new password :","");
if (name!=null && name!="")
  {
  window.location.href="passchange.php?newpass="+name;
  }
}
</script>
</head>
<body style="background: #ffffff">
<div id="container">
<div id="header" align="center">
<img src="images/logo_small.jpg" border="0">

</div>
<div id="body"><span style="font-size: large;">Hello, <span style="font-weight: bold;"><?php echo $_COOKIE['user']; ?></span></span><br>
<br />
<br>
<!--  <a href="main.php">My Bid!</a>&nbsp;&nbsp;&nbsp;<a href="bid.php">Make Bid!</a>&nbsp;&nbsp;&nbsp;<a href="ranks.php">Rankings!</a>&nbsp;&nbsp;&nbsp;<a href="stats.php">View Stats!</a>&nbsp;&nbsp;&nbsp;<a onclick="show_prompt()">Change Password</a><?php if($_COOKIE['user']=="admin"){?>&nbsp;&nbsp;&nbsp;<a href="close.php">Close Match!</a>&nbsp;&nbsp;&nbsp;<a href="closematch.php">Refresh Stats!</a><?php }?>&nbsp;&nbsp;&nbsp;<a href="logout.php">Logout!</a> -->
<ul class="temp">
<li><a href="main.php">My Bid</a></li>
<li><a href="bid.php">Make Bid</a></li>
<li><a href="ranks.php">Rankings</a></li>
<li><a href="stats.php">View Stats</a></li>
<li><a onclick="show_prompt()">Change Password</a></li>
<?php if($_COOKIE['user']=="admin"){?>
<li><a href="close.php">Close Match</a></li>
<li><a href="closematch.php">Refresh Stats</a></li>
<?php }?>
<li><a href="logout.php">Logout</a></li>
</ul>
<div style="text-align: center;">
<?php
if($_POST['Submit']=="Ok!"){
	$query="update `matches` set `won`='".$_POST['team']."' where `sno`=".$_POST['match'];
	mysql_query($query) or die(mysql_error());
	?>
	<br>
	<a href="index.php">Click here</a> to go back home!
	<?php 
}
else if($_POST['Submit']=="Proceed!"){
	$query="select * from matches where sno=".$_POST['match'];
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	if($num!=0){
		$row=mysql_fetch_array($result);
		echo "<br>Closing Match : ".$row[1]."	-	".$row[2]."	vs	".$row[3];
		?>
		<form action="close.php" method="post">
		Winner : 
		<input type="hidden" value="<?php echo $_POST['match']; ?>" name="match">
		<select name="team">
		<option value="<?php echo $row[2]; ?>"><?php echo $row[2]; ?></option>
		<option value="<?php echo $row[3]; ?>"><?php echo $row[3]; ?></option>
		</select>
		<input type="submit" value="Ok!" name="Submit">
		</form>
		<?php
	}
	else
		echo "Oops! Sorry no donut for you!";
}
else{
	$query="select * from members where uname='".$user."'";
	$result=mysql_query($query);
	$row=mysql_fetch_array($result);
	//if($row[2]<100){
	//	echo "<br>Oops! Sorry you don't have enough Points to Bid. Check with @admin";		
	//}
	//else{
?>
<form action="close.php" method="post">
Select Match:
<select name="match">
<?php
$today = date("Y-m-d H:i:s",time()+3600);
$query="select * from matches where date<'".$today."' and `won`='NA' order by date";
$result=mysql_query($query);
$num=mysql_num_rows($result);
if($num!=0){
	for($i=0;$i<$num;$i++){
		$row=mysql_fetch_array($result);
		echo "<option value=\"".$row[0]."\">".$row[1]."	-	".$row[2]."	vs	".$row[3]."</option>";
	}
}
?>
</select>
<input type="submit" value="Proceed!" name="Submit">
</form>
<?php
//echo $query;
//}
} 
?>
</div>

</div>
</body>
</html>