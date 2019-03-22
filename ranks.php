<?php
include('mysqlconnect.php');
function greaterDate($start_date,$end_date)
{
  $start = strtotime($start_date);
  $end = strtotime($end_date);
  if ($start-$end > 0)
    return 1;
  else
   return 0;
}
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
	$totalwin=$row[2];

}
?>
<html>
<head>
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
<link rel="stylesheet" href="final.css"></link>
<title>IPL</title>
</head>
<body style="background: #ffffff">
<div id="container">
<div id="header" align="center">
<img src="images/logo_small.jpg" border="0"> 
</div>
<div id="body">
<span style="font-size: large;"><span style="font-weight: bold;"><?php echo $_COOKIE['user']; ?></span></span><br>
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
<table align="center" class="infotable" style="width: 60%">
	<caption><strong>Current Standings</strong></caption>
	</tr>
	<tr>
		<th>Rank</th>
		<th>User</th>
		<th>Played Matches</th>
		<!--<th>Outstanding Points</th>-->
		<th>Rank Points</th>
		<!-- <th>Lost Points</th>-->
		<th>Deposited Points</th>  
	</tr>
	<?php
 
	$query="SELECT * FROM `members` order by `WonPoints` desc";
	$result=mysql_query($query);
   $num=mysql_num_rows($result);
 
 
 	$j=0;
	for($i=0;$i<$num;$i++,$j++){
		$row=mysql_fetch_array($result);
		if($row[0]=="admin" || $row[0]=="temp"){
			$j--;
			continue;
 		} 
      $query1="SELECT * FROM `bids` WHERE `user`='$row[0]'";
      $result1=mysql_query($query1) or die(mysql_error());
      $nummatches=mysql_num_rows($result1);
 
		if($nummatches== 0)
      {
         $j--;
			continue;
      }
      echo "<tr>";
		echo "<td>".($j+1)."</td>";
		echo "<td> ".ucFirst($row[0])." </td>";
		echo "<td> $nummatches </td>";

//echo "<td> $row[2] </td>"; 
		//if($row[4]>=200)
		//echo "<td><span style=\"text-decoration: blink\"> $row[4] </span></td>";
		//else
		echo "<td>$row[4]</td>";
		//echo "<td> $row[5] </td>";
		echo "<td> $row[6] </td>";
		echo "</tr>";
   }
 
//?>
</table>
</div>
<div style="background: white;" id="footer" align="center">
<ul style="list-style: none; display: inline;" class="footer">
</ul>
</div>
</div>
</body>
</html>
