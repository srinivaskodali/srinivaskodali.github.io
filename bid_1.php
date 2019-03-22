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
<title><?php echo $_COOKIE['user']; ?> :: Bid :: LQIN WC2011</title>
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
<div style="text-align: center;">
<?php
if(isset($_POST['Submit'])&&$_POST['Submit']=="Bid!"){
	//For Refresh Override
	echo "Bid amount : $bid Pts";
	//echo "<br>Final two matches closed Bid. Stats will be shown after the bidding closure";
	$query="select * from bids where `match`=".$_POST['match']." and `user`='".$_COOKIE['user']."'";
	$result=mysql_query($query) or die(mysql_error());
	$num=mysql_num_rows($result);
	if($num!=0){
		echo "<br>Sorry! You already have a bid on that match. Please use the Mainpage to modify that bid.";
	}
	else{
	$query="insert into bids values('','".$_COOKIE['user']."',".$_POST['match'].",'".$_POST['team']."','')";
	$result=mysql_query($query) or die(mysql_error());
	$query="update members set `pts`=`pts`-100 where `uname`='".$_COOKIE['user']."'";
	$result=mysql_query($query) or die(mysql_error());
	//$query10="update members set `wonpts`=`wonpts`-100 where `uname`='".$_COOKIE['user']."'";
	//$result10=mysql_query($query10) or die(mysql_error());
	echo "<br>Bid Successful. Wish you luck!";
	echo "<br>";
	
	// ///////////////////////////////////////////////////////////
	$row[0]=$_POST['match'];
	//for($i=0;$i<$matchcount;$i++){
		//$row=mysql_fetch_array($result);
		$query1="SELECT count(sno),`match` FROM `bids` where `match`=".$row[0];
		//echo "<BR>".$query1;
		$result1=mysql_query($query1) or die(mysql_error());
		$row1=mysql_fetch_array($result1);
		$totalcount=$row1[0];
		//echo "TOTAL COUNT:".$totalcount; 
		$query2="SELECT count(sno),`forteam` FROM `bids` where `match`=".$row[0]." group by `forteam`";
		//echo "<BR>".$query2;
		$result2=mysql_query($query2) or die(mysql_error());
		$row2=mysql_fetch_array($result2);
		$tempcount=$row2[0]; //A value of Zero can't come for this
		//echo "TEMP COUNT:".$tempcount;
		$tempfor=$row2[1];
		if($tempcount==$totalcount || $tempcount==0){
			$query31="update `matches` set `SoloBid`=1 where `sno`=".$row[0];
			$result31=mysql_query($query31) or die(mysql_error());
		}
		else{
			$query31="update `matches` set `SoloBid`=0 where `sno`=".$row[0];
			$result31=mysql_query($query31) or die(mysql_error());
		}
		$query3="SELECT `teamone`,`teamtwo` FROM `matches` WHERE `sno`=".$row[0];
		//echo "<BR>".$query3;
		$result3=mysql_query($query3) or die(mysql_error());
		$row3=mysql_fetch_array($result3);
		
		if($tempfor==$row3[0]){
			//echo "Team1";
			//Team 1= tempcount
			//Team 2= totalcount-tempcount
			$bidwinone=(($totalcount-$tempcount)*$bid)/($tempcount>0?$tempcount:1);
			/*
			 * 
			 if(($totalcount-$tempcount)==0)
				$bidwintwo=0;
			else
			*/
				$bidwintwo=($tempcount*$bid)/(($totalcount-$tempcount)>0?($totalcount-$tempcount):1);
				
			if(($totalcount-$tempcount)==0)
				$bidwinone=$bidwintwo/$tempcount;
			// For match row[0] each bid for teamone wins $bidwinone
		}
		else if($tempfor==$row3[1]){
			//echo "Team2";
			$bidwintwo=(($totalcount-$tempcount)*$bid)/($tempcount>0?$tempcount:1);
			/*
			if(($totalcount-$tempcount)==0)
				$bidwinone=0;
			else
			*/
			$bidwinone=($tempcount*$bid)/(($totalcount-$tempcount)>0?($totalcount-$tempcount):1);
			if(($totalcount-$tempcount)==0)
				$bidwintwo=$bidwinone/$tempcount;
		}
		//echo "1:	$bidwinone; 2:	$bidwintwo";
		$query4="update `matches` set `bidding1`=".$bidwinone.",`bidding2`=".$bidwintwo." where `sno`=".$row[0];
		//echo $query4;
		mysql_query($query4) or die(mysql_error());
	
	
	//$query="SELECT `match` FROM `bids` group by `match`";
	//$result=mysql_query($query) or die(mysql_error());
	//$matchcount=mysql_num_rows($result);
	//}
}
	
	?>
	<br>
	<a href="index.php">Click here</a> to go back home!
	<?php 
}
else if(isset($_POST['Submit'])&&$_POST['Submit']=="Proceed!"){
	echo "Bid amount : $bid Pts";
	echo "<br>Final two matches closed Bid. Stats will be shown after the bidding closure";
	$query="select * from bids where `match`=".$_POST['match']." and `user`='".$_COOKIE['user']."'";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	if($num!=0){
		echo "<br>Sorry! You already have a bid on that match. Please use the Mainpage to modify that bid.";
	}
	else{
		// CODE FOR GAMBLE CALCULATION
		$query="SELECT `teamone`,`teamtwo` FROM `matches` where `sno`=".$_POST['match'];
		$result=mysql_query($query) or die(mysql_error());
		$row=mysql_fetch_array($result);
		$teamone=$row[0];
		$teamtwo=$row[1];
		
		$query1="SELECT count(sno),`forteam` FROM `bids` where `match`=".$_POST['match']." group by `forteam` ";
		$result1=mysql_query($query1) or die(mysql_error());
		$num=mysql_num_rows($result1);
		if($num==0){
			$gambleone=$gambletwo=0; //For BOTH
		}
		else{ 
			$row1=mysql_fetch_array($result1);
			$tempteam=$row1[1];
			$tempno=$row1[0];
			if($num==1){
					if($tempteam==$teamone){
						$gambleone=0; //Zero for $tempteam
						$gambletwo=$tempno*$bid; //Full Takeover
					}else{
						$gambleone=$tempno*$bid; //Zero for $tempteam
						$gambletwo=0; //Full Takeover
					}
				}
			else if($num==2){
				$row1=mysql_fetch_array($result1);
				$tempteam1=$row1[1];
				$tempno1=$row1[0];
				
				$teamone=$tempteam;
				$teamtwo=$tempteam1;
				$gambleone= ($tempno1*$bid)/($tempno+1); //For $tempteam
				$gambletwo= ($tempno*$bid)/($tempno1+1); //For $tempteam1
					

//echo "TESTING : PLEASE IGNORE";
//echo "tempteam : $tempteam";
//echo "tempteam : $tempteam1";
//echo "tempno : $tempno";
//echo "tempno1 : $tempno1";
			}
		}
		//Commented for not showing stats during bid----Swaroop
		//echo "<BR>Might Win : $gambleone Rs. for $teamone";
		//echo "<BR>Might Win : $gambletwo Rs. for $teamtwo";
		// CODE FOR GAMBLE CALCULATION - END
	$query="select * from matches where sno=".$_POST['match'];
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	if($num!=0){
		$row=mysql_fetch_array($result);
		echo "<br>Bidding Match : ".$row[1]."	-	".$row[2]."	vs	".$row[3];
		?>
		<form action="bid.php" method="post">
		Bid for : 
		<input type="hidden" value="<?php echo $_POST['match']; ?>" name="match">
		<select name="team">
		<option value="<?php echo $row[2]; ?>"><?php echo $row[2]; ?></option>
		<option value="<?php echo $row[3]; ?>"><?php echo $row[3]; ?></option>
		</select>
		<input type="submit" value="Bid!" name="Submit">
		</form>
		<?php
	}
	else
		echo "<span style=\"color: red; font-size: medium;\">Oops! Sorry no donut for you!</span>";
}
}
else{
	$query="select * from members where uname='".$user."'";
	$result=mysql_query($query);
	$row=mysql_fetch_array($result);
	if($row[2]<100){
		echo "<br><span style=\"color: red; font-size: medium;\">Oops! Sorry you don't have enough Points to Bid. Check with @admin</span>";		
	}
	else{
		echo "Bid amount : $bid Pts";
		//echo "<br>Final two matches closed Bid. Stats will be shown after the bidding closure";
?>
<form action="bid.php" method="post">
Select Match:
<select name="match">
<?php
$today = date("Y-m-d H:i:s",time()+3600);


$query="select * from matches where date>='".$today."' order by date";

$result=mysql_query($query);
$num=mysql_num_rows($result);


//echo $today;
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
}
} 
?>
</div>
</div>

<div style="background: white;" id="footer" align="center">
<ul style="list-style: none; display: inline;" class="footer">
</ul>
</div>
</div>
</body>
</html>
