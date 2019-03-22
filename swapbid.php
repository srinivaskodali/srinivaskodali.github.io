<?php
$bid=100;
$swpoints=10;
$admin="admin";
include('mysqlconnect.php');
if($_POST['Submit']=="Switch Team!"){
	ob_start();
	$bidid=$_POST['bidid'];
	$match=$_POST['match'];
	$team=$_POST['team'];
	$query="DELETE FROM `bids` WHERE `sno` = $bidid";
	$myquery="SELECT * FROM `bids` WHERE `sno` = $bidid";
	$myresult=mysql_query($myquery) or die(mysql_error());
	$myrow=mysql_fetch_array($myresult);
	$ptsquery="update members set `pts`=`pts`-10 where `uname`='".$_COOKIE['user']."'";
	$admincredit="update members set `pts`=`pts`+10 where `uname`='".$admin."'";
	$result=mysql_query($admincredit) or die(mysql_error());
	$result=mysql_query($query) or die(mysql_error());
	$ptsresult=mysql_query($ptsquery) or die(mysql_error());
	echo "Deleted Bid";
	$query="SELECT `teamone`,`teamtwo` FROM `matches` WHERE `sno`=".$match;
	//echo "<BR>".$query3;
	$result=mysql_query($query) or die(mysql_error());
	$row=mysql_fetch_array($result);
	echo "Match ID : $match";
	echo "Chosen $team";
	if($team==$row[0])
	$team=$row[1];
	else
	$team=$row[0];
	echo "Swapped to $team";

	$query="select * from bids where `match`=".$_POST['match']." and `user`='".$_COOKIE['user']."'";
	$result=mysql_query($query) or die(mysql_error());
	$num=mysql_num_rows($result);
	if($num!=0){
		echo "<br>Sorry! You already have a bid on that match. Please use the Mainpage to modify that bid.";
	}
	else{
		$query="insert into bids values('','".$_COOKIE['user']."',".$_POST['match'].",'".$team."',$myrow[4]+1)";
		$result=mysql_query($query) or die(mysql_error());
		echo "<br>Bid Successful. Wish you luck!";
		echo "<br>";


		//$query="SELECT `match` FROM `bids` group by `match`";
		//$result=mysql_query($query) or die(mysql_error());
		//$matchcount=mysql_num_rows($result);
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
		echo "TEMP COUNT:".$tempcount;
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
			echo "Team1";
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
			echo "Team2";
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
		$query4="update `matches` set `bidding1`=".$bidwinone.",`bidding2`=".$bidwintwo." where `sno`=".$row[0];
		echo $query4;
		mysql_query($query4) or die(mysql_error());
		//}
	}

	?>
<br>
<a href="index.php">Click here</a>
to go back home!
<?php
header('Location: main.php');
ob_flush(); 
}
?>
