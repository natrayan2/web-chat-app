<?php
session_start();
$servername = "localhost:3306";
$username="root";
$password="";
$dbname="webchat";
$con = new mysqli($servername, $username, $password, $dbname);
$output=$chatuser="";
$sid = $_SESSION['id'];
if ($con->connect_error)
{
	echo $con->connect_error;
	exit;
} 
if(isset($_GET['msg']) && isset($_GET['chatuser']))
{
	$msg = rawurldecode($_GET['msg']);
	$ufrom = $_GET['chatuser'];
	if($sid < $ufrom)
	{
		$chatterid = $sid."-".$ufrom;
	}
	else
	{
		$chatterid = $ufrom."-".$sid;
	}
	$sql = "INSERT INTO chat (msg, ufrom, uto, chatterid) VALUES ('$msg', '$ufrom', '$sid', '$chatterid')";
	if ($con->query($sql) === TRUE)
	{
		$output="<tr style='border:none'><td class='right blue white-text' style='font-family:Comic Sans MS;margin:5px; margin-right:30px; max-width:300px;padding:10px; border-radius:10px;'>".$msg."</td></tr>";

	}
}

echo $output;
?>
