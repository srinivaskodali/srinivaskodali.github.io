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
<body style="background: #e4e9f1">
<h1 style="text-align: center;">LQIN - IPL Fan Club!</h1>
<br>
Hello, <span style="font-weight: bold;"><?php echo $_COOKIE['user']; ?></span><br>
<a href="main.php">My Bid!</a>&nbsp;&nbsp;&nbsp;<a href="bid.php">Make Bid!</a>&nbsp;&nbsp;&nbsp;<a href="ranks.php">Rankings!</a>&nbsp;&nbsp;&nbsp;<a href="stats.php">View Stats!</a>&nbsp;&nbsp;&nbsp;<a href="#" onclick="show_prompt()">Change Password</a><?php if($_COOKIE['user']=="admin"){?>&nbsp;&nbsp;&nbsp;<a href="close.php">Close Match!</a>&nbsp;&nbsp;&nbsp;<a href="closematch.php">Refresh Stats!</a><?php }?>&nbsp;&nbsp;&nbsp;<a href="logout.php">Logout!</a>
<table align="center" class="infotable">
	<caption>My Bids!</caption>
	<tr>
		<th>S.No</th>
		<th>Match</th>
		<th>Cheering for</th>
		<th>Status</th>
		<th>Change Bid</th>
	</tr>
	<?php
	$query="select * from bids where user='".$user."'";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	if($num!=0){
		for($i=0;$i<$num;$i++){
			$row=mysql_fetch_array($result);
			?>
	<tr>
		<td><?php echo ($i+1);?></td>
		<td><?php	
		$query1="select * from matches where sno=".$row[2];
		$result1=mysql_query($query1) or die(mysql_error());
		if(mysql_num_rows($result1)!=0){
			$row1=mysql_fetch_array($result1);
			echo $row1[1]."	-	".$row1[2]."	vs	".$row1[3];
		}
		?>
		</td>
		<td><?php echo $row[3]?></td>
		<td>
		<?php 
		if($row[3]==$row1[2])
			$won=$row1[5];
		else
			$won=$row1[6];
		
		//echo "totalwin set to $totalwin";
		if($row1[4]!="NA"){
			if($row1[4]==$row[3]){
				echo "Won ".$won." Pts !";
				//$totalwin+=$won;
				//echo "totalwin set to $totalwin";
			} 
			else{
				echo "Lost!";
				//$totalwin-=100;
				//echo "totalwin set to $totalwin";
			}
			echo "</td><td align=\"center\">Closed!";
		}
		else{
			$today = date("Y-m-d H:i:s",time()+3600);
			if(greaterDate($today,$row1[1])){
			echo "Might Win : ".$won." Pts";
			}
			else
				echo "In Progress";
			?>
			</td>
			<td align="center">
			<?php
			
			if(greaterDate($today,$row1[1])){
				echo "Not possible!";
			}
			else{
			?>
			<form action="swapbid.php" method="post">
			<input type="hidden" name="bidid" value="<?php echo $row[0]; ?>" />
			<input type="hidden" name="match" value="<?php echo $row[2]; ?>" />
			<input type="hidden" name="team" value="<?php echo $row[3]; ?>" />
			<input type="Submit" value="Switch Team!" name="Submit" />
			</form>
			<?php
			}
		}
		?>
		</td>
	</tr>
	<?php
		}
		}
		?>
</table>
<?php
$query="select * from members where uname='".$user."'";
$result=mysql_query($query);
if(mysql_num_rows($result)!=0){
	$row=mysql_fetch_array($result);
	$totalwin=$row[2];
}

echo "<br><div align=\"center\">Total : <span align=\"center\" style=\"color: ".($totalwin>=0?"green":"red").";\">$totalwin</span>";
$query1="SELECT * FROM `bids` WHERE `user`='$user'";
$result1=mysql_query($query1) or die(mysql_error());
$nummatches=mysql_num_rows($result1);
$reqdmatches=$row[3];
echo "<br>";
echo "Played : Required Minimum No. of Matches - <span align=\"center\" style=\"color: ".($nummatches>=$reqdmatches?"green":"red").";\">$nummatches</span>	:	$reqdmatches";
echo "</div>"; 

?>
</body>
</html>
