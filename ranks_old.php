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
<body style="background: #e4e9f1">
<div id="container">
<div id="header" align="center">
<a href="http://www.iplt20.com"> <img src="images/ipl_logo.gif" border="0"> </a>
<h1 style="text-align: center;">LQIN - IPL Fan Club!</h1>
</div>
<div id="body">
<span style="font-size: large;">Hello, <span style="font-weight: bold;"><?php echo $_COOKIE['user']; ?></span></span><br>
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
<table align="center" class="infotable" style="width: 60%">
	<caption>My Bids</caption>
	</tr>
	<tr>
		<th>Rank</th>
		<th>User</th>
		<!--<th>Outstanding Points</th>-->
		<th>Rank Points</th>
		<!-- <th>Lost Points</th>-->
		<th>Deposited Points</th>  
	</tr>
	<?php
	$query="SELECT * FROM `members` order by `wonpts` desc";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	for($i=0;$i<$num;$i++){
		$row=mysql_fetch_array($result);
		if($row[0]=="admin" || $row[0]=="temp")
			continue;
		echo "<tr>";
		echo "<td>".($i+1)."</td>";
//		echo "<td> $row[0] </td>";
		echo "<td> ".ucFirst($row[0])." </td>";

//echo "<td> $row[2] </td>"; 
		echo "<td> $row[4] </td>";
		//echo "<td> $row[5] </td>";
		echo "<td> $row[6] </td>";
		echo "</tr>";
	}
?>
</table>
</div>
<br><br><br><br><br>
<div style="background: white;" id="footer" align="center">
<ul style="list-style: none; display: inline;" class="footer">
<li>
<a href="http://www.iplt20.com/team.php?team=RCB"> <img src="images/RCB_teamLogo.gif" border="0"></a>
</li>
<li>
<a href="http://www.iplt20.com/team.php?team=CSK"> <img src="images/CSK_teamLogo.gif" border="0"></a>
</li>
<li>
<a href="http://www.iplt20.com/team.php?team=DC"><img src="images/DC_teamLogo.gif" border="0"></a>
</li>
<li>
<a href="http://www.iplt20.com/team.php?team=DD"><img src="images/DD_teamLogo.gif" border="0"></a>
</li>
<li>
<a href="http://www.iplt20.com/team.php?team=KKR"><img src="images/KKR_teamLogo.gif" border="0"></a>
</li>
<li>
<a href="http://www.iplt20.com/team.php?team=KXP"><img src="images/KP_teamLogo.gif" border="0"></a>
</li>
<li>
<a href="http://www.iplt20.com/team.php?team=MI"><img src="images/MI_teamLogo.gif" border="0"></a>
</li>

<li>
<a href="http://www.iplt20.com/team.php?team=RR"><img src="images/RR_teamLogo.gif" border="0"></a>
</li>
</ul>
</div>
</div>
</body>
</html>
