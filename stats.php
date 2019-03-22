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
	
}
?>
<html>	
<head>
<title>IPL</title>
<link rel="stylesheet" href="final.css"></link>
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
<a href="http://www.iplt20.com"> <img src="images/logo_small.jpg" border="0"> </a>
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
<div align="center">
<form action="stats.php" method="post">
Select Match:
<input type="submit" value="Go!" name="Submit">

<select name="match">
<?php
$today = date("Y-m-d H:i:s",time()+3600);
//$query="select * from matches where date<'".$today."' order by date";
$query="select * from matches order by date";
$result=mysql_query($query);
$num=mysql_num_rows($result);
if($num!=0){
	for($i=0;$i<$num;$i++){
		$row=mysql_fetch_array($result);
		if(isset($_POST['Submit'])&&$_POST['Submit']=="Go!" && $row["sno"]==$_POST['match'])
		echo "<option value=\"".$row[0]."\"selected>".$row[1]."	-	".$row[2]."	vs	".$row[3]."</option>";
		else
		echo "<option value=\"".$row[0]."\">".$row[1]."	-	".$row[2]."	vs	".$row[3]."</option>";	
		}
}
?>
</select>
</form>
</div>
<div align="center">
<?php echo "<br>Final two matches closed bid. Stats will be shown after the bidding closure"; ?>
</div>
<table align="center" style="width: 60%" class="infotable">
<caption>Bids</caption>
</tr>
<tr>
<th>S.No</th>
<th>User</th>
<th>Match</th>
<th>Cheering for</th>
<th>Status</th>
</tr>

<?php
if(isset($_POST['Submit'])&&$_POST['Submit']=="Go!"){
	//$query="SELECT * FROM `bids` where `match`=".$_POST['match']." order by `forteam`";
	$query="SELECT * FROM `bids` where `match`=".$_POST['match']."  order by `match`, `forteam`";
}
else{
	//$query="SELECT * FROM `bids` order by `match`, `forteam`";
	//$query="SELECT * FROM `bids` where where date<'".$today."' order by `match`, `forteam`";
	$query="SELECT * FROM `bids` where `match`  order by `match`, `forteam`";
	
}
$result=mysql_query($query) or die(mysql_error());
$num=mysql_num_rows($result);
if($num!=0){
	for($i=0;$i<$num;$i++){
		$row=mysql_fetch_array($result);
		if($row[1]=="admin" || $row[1]=="temp")
			continue;
			
	?>
	<?php	
	$query1="select * from matches where sno=".$row[2];
	$result1=mysql_query($query1) or die(mysql_error());
	if(mysql_num_rows($result1)!=0){
		$row1=mysql_fetch_array($result1);
	//	$today = date("Y-m-d H:i:s",time()+3600);
	//	if(!greaterDate($today,$row1[1]))
	//	 break;
	} 
	?>

	<tr>
	<td><?php echo ($i+1);?>	</td>
	<td><?php echo ucFirst($row[1]);?></td>
	<td><?php echo $row1[1]."	-	".$row1[2]."	vs	".$row1[3];?></td>

	
	<?php	
	$query1="select * from matches where sno=".$row[2];
	$result1=mysql_query($query1) or die(mysql_error());
	if(mysql_num_rows($result1)!=0){
		$row1=mysql_fetch_array($result1);
		echo $row1[1]."	-	".$row1[2]."	vs	".$row1[3];
		} 
	?>
	
	<?php echo $row1[4]!=""?($row1[4]==$row[3]?"Won":"Lost"):"Might Win/Lose"; ?>
	<?php 
		if($row[3]==$row1[2])
			$won=$row1[5];
		else
			$won=$row1[6];
			
		if($row1[4]!="NA"){ ?>
		<td><?php echo $row[3]?></td>
		<td>
		<?php 
			if($row1[8]==1){
				echo "Retained ".$won." Pts !";
			}
			else if($row1[4]==$row[3] && $row1[8]==0){
				if($won>=200)
				echo "<span style=\"text-decoration: blink\">Won ".$won." Pts !</span>";
				else
				echo "Won ".$won." Pts !";
			}   
			else{
				echo "Lost!";
			}
		}
		else{?>
		<?php
			$today = date("Y-m-d H:i:s",time()+3600);
			//Check match bid status & 
			if(greaterDate($today,$row1[1])){ ?>
			<td><?php echo $row[3]?></td>
			<td>
			<?php 
			if($row1[8]!=1)
			echo "Might Win : ".$won." Pts";
			else
			echo "No opposing Bids";
			}
			else{?>
			<td><?php echo "-----"?></td>
			<td>
			<?php 
				echo "In Progress";
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
</div>
<br><br><br><br><br>
<script type="text/javascript">
 // window.alert("Stats will be shown after bid closure for the ongoing matches");
</script>

</div>
</body>

</html>
