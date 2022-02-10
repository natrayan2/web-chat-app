<?php
session_start();
$servername = "localhost:3306";
$username="root";
$password="";
$dbname="webchat";
$con = new mysqli($servername, $username, $password, $dbname);
$output=$msg=$chatuser="";
$suname = $_SESSION['uname'];
$sid = $_SESSION['id'];
if(isset($_GET['chatuser']))
{
	$chatuser = $_GET['chatuser'];
	if($sid < $chatuser)
	{
		$chatterid = $sid."-".$chatuser;
	}
	else
	{
		$chatterid = $chatuser."-".$sid;
	}
	$sql = "SELECT * FROM chat WHERE chatterid='$chatterid' AND uto='$chatuser' AND seen=0 ORDER BY time ASC";
	$result = $con->query($sql);
	if ($result->num_rows >= 1)
	{
		while($row = $result->fetch_assoc())
		{
			$rowmsg=$row['msg'];	
			if (strpos($rowmsg, 'upload/sharedfile-') !== false)
			{
				$rowmsg="<a class='blue white-text' href='".$row['msg']."' width='300' height='200' target='blank'>".$row['msg']."</a>";
			}
			$output.="<tr style='border:none'><td class='left grey lighten-4' style='margin:5px; max-width:400px;padding:10px; border-radius:10px;'>".$rowmsg."</td></tr>";
			$sql2 = "UPDATE chat SET seen=1 WHERE id=".$row['id'];
			$result2 = $con->query($sql2);
		}		
	}
}

echo $output;
?>