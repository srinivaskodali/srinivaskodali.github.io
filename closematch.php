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
		if($pass!=$row[1] & $user!="admin"){
			header("Location: index.php");
			setcookie('user','',time()-3600);
		}
	}
}
echo "Entering ...";
//Get all closed matches
$query="SELECT * FROM `matches` WHERE `won`!='NA' and `validated`=0";
$result=mysql_query($query) or die(mysql_error());
$num=mysql_num_rows($result);
echo "<br>Num Closed: $num";
for($i=0;$i<$num;$i++){
	$row=mysql_fetch_array($result) or die(mysql_error());
	echo "Match ID $row[0] : Won : $row[3] ";
	mysql_query("update matches set `validated`=1 where `sno`=$row[0]") or die(mysql_error());
	$matchid=$row[0];
	$winteam=$row[4];
	if($winteam==$row[2]){
		$winlot=$row[5];
	}
	else if($winteam==$row[3]){
		$winlot=$row[6];
	}
	echo "<br>WinLot: $winlot";
	//Update Pts
	$query1="select `user`,`forteam` from bids where `match`=$matchid";
	$result1=mysql_query($query1);
	$num1=mysql_num_rows($result1);
	
	$query0="select `user`,`forteam` from bids where `match`=$matchid and `forteam`='$winteam'";
	$result0=mysql_query($query0);
	$num0=mysql_num_rows($result0);
	if($num1==$num0 || $num0==0){
		for($i1=0;$i1<$num1;$i1++){
		$row1=mysql_fetch_array($result1);
		$query2="update `members` set `pts`=`pts`+100 where `uname`='$row1[0]'";
		$result2=mysql_query($query2);
		echo "<br>Updated 100 for $row1[0]";
		//$query21="update `members` set `wonpts`=`wonpts`+100 where `uname`='$row1[0]'";
		//$result21=mysql_query($query21);
		//$query21="update `members` set `lostpts`=`lostpts`-100 where `uname`='$row1[0]'";
		//$result21=mysql_query($query21);
		echo "<br>Updated 100 for $row1[0] WonPts - No opposition bidder.";
		}
	}
	else{
	for($i1=0;$i1<$num1;$i1++){
		$row1=mysql_fetch_array($result1);
		if($row1[1]==$winteam){
			//Add
			$query2="update `members` set `pts`=`pts`+$winlot+100 where `uname`='$row1[0]'";
			$result2=mysql_query($query2);
			echo "<br>Updated $winlot+100 for $row1[0]";
			$query21="update `members` set `wonpts`=`wonpts`+$winlot where `uname`='$row1[0]'";
			$result21=mysql_query($query21);
			//$query21="update `members` set `lostpts`=`lostpts` where `uname`='$row1[0]'";
			//$result21=mysql_query($query21);
			echo "<br>Updated WonPts $winlot+100 for $row1[0]";
		}
		else{
			//Nothing
		}
	}
	$query21="update `members` set `wonpts`=`wonpts`-100 where `uname` IN (select `user` from bids where `match`=$matchid and `forteam`!='$winteam')";
	$result21=mysql_query($query21);
}
}
?>