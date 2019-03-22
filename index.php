<?php 
ob_start();
?>
<html>
<head>
<link rel="stylesheet" href="final.css"></link>
<title>IPL-6</title>
</head>
<body style="background: #FFffff" "background-image: images/sach.jpg">

<div id="container">
<div id="header" align="center">

<img src="images/logo_small.jpg" border="0"> 

<!-- <h1 style="text-align: center;">LQIN - Cricket Fan Club!</h1> !-->
</div>
<br>
<br />
<br>
<div id="body">
<form action="index.php" method="post">
<table align="center" class="infotable">
	<tr>
		<th colspan="2">Login</th>
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
		<td colspan="2" align="center"><input type="submit" value="Login!"
			name="Submit"></td>
	</tr>
</table>
</form>
<br>
<h4>Rules and Regulations	:</h4>

<ol>
<li>Participants needs to have a valid username and password in order to participate in the tournament.</li> 
<li>Username and Password are provided during the registration process. </li>
 <li>Users subscribing to this tornament would be charged 500 points, making sure that the user participates in atleast five matches.  </li>
 <li>If user participates in less than five matches, 500 points would be deducted.</li>
<li>Cheering for a team is allowed till 1hr before the match starts. Even in exceptional cases, cheering is not allowed once the match starts.</li>
<li>When the user cheers for a team, 100 points would be deducted from his account. If the team he is cheering loses, he loses 100 points, If the team wins, (100 + (100 * no. of losers)/no. of winners) points would be deposited into his account.</li> 
<li>User cannot cheer for a team if he does not have minimum of 100 points in his account.</li>
<li>When the user switch the bid for a team, 10 points would be deducted from his account </li>
<li>User cannot switch the bid for a team if he does not have minimum of 10 points in his account.</li>
<li>If the user has 0 points in his account and wishes to further participate in the tournament, the admin collects 300 points from the user and deposits 300 points to the user's account.</li>
<li>Points redemption will be done only after the IPL tournament ends.</li>
<li>Organisers reserve the right to change the rules any time during the tournament.</li>
<li>In case of any conflicts, organisers decision will be the final one.</li> 
<li>Users involving in any unfair practices during the tournament will be banned from participation for the rest of the matches, and no points will be redeemed to them.</li>
</ol>
</div>
<br><br><br><br><br>
<div style="background: white;" id="footer" align="center">
<ul style="list-style: none; display: inline;" class="footer">
<li>
</li>
</ul>
</div>
</div>
<?php
include('mysqlconnect.php');
if($_POST['Submit']=="Login!"){
	$user=$_POST['uname'];
	$pass=$_POST['pass'];
	$query="select * from members where uname='".$user."'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)!=0){
		$row=mysql_fetch_array($result);
		if(md5($_POST['pass'])==$row[1]){
			echo "Login success!";
			header("Location: stats.php");
			setcookie("user",$user,time()+3600);
			setcookie("id",md5($pass),time()+3600);
			ob_end_flush();
		}
		else{
			echo "Login failure!";
		}
	}
	else{
		echo "User does not exist. Run along!";
	}
}
if($_COOKIE['user'] != ""){
	header("Location: stats.php");
}
ob_flush();
?>
</body>
</html>
