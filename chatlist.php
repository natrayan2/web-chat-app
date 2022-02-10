<?php
session_start();
$servername = "localhost:3306";
$username="root";
$password="";
$dbname="webchat";
$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error)
{
    die("Connection failed: " . $con->connect_error);
}
$suname = $_SESSION['uname'];
$sid = $_SESSION['id'];
$output=$chatlist="";
$cuname=$chatterid="";
$chatuser="";
if(isset($_GET['chatuser']))
{
	$chatuser = $_GET['chatuser'];
}
$query = "SELECT * FROM register";
$result=$con->query($query);
if($result->num_rows>0)
{
	while($row=$result->fetch_assoc())
	{
		$id = $row['id'];
		$uname = $row['uname'];
		if($sid != $id)
		{
			$chatlist .= "<tr class='tabrowd' onclick='javascript:location.href=\"chatlist.php?chatuser=".$id."\"'>
												<td class='center'>".$row['uname']."</td>
											</tr>";			
		}
	}	
}
$cquery = "SELECT uname FROM register WHERE id = '$chatuser' ";
$cresult=$con->query($cquery);
if($cresult->num_rows>0)
{
	while($row=$cresult->fetch_assoc())
	{
		$cuname = $row['uname'];
	}					
}


if($sid < $chatuser)
{
	$chatterid = $sid."-".$chatuser;
}
else
{
	$chatterid = $chatuser."-".$sid;
}

$sql2 = "SELECT * FROM chat WHERE chatterid='$chatterid' ORDER BY time ASC";
$result2 = $con->query($sql2);
if ($result2->num_rows >= 1)
{
	while($row = $result2->fetch_assoc())
	{
		$rowmsg=$row['msg'];
		$chatterid = $row['chatterid'];	
		$ufrom = $row['ufrom'];
		$uto = $row['uto'];
		$time = $row['time'];
		$chatid = $row['id'];
		$seen = $row['seen'];
		if($chatid)
		{
			if (strpos($rowmsg, 'upload/sharedfile-') !== false)
			{
				$rowmsg="<a class='blue white-text' href='".$row['msg']."' target='blank'>".$row['msg']."</a>";
			}
			if($row['seen'] == 1)
			{
				if($uto == $chatuser)
				{
					$output.="<tr style='border:none'><td class='left grey lighten-4' style='font-family:Safari;margin:5px; max-width:400px;padding:10px; border-radius:10px;'>".$rowmsg."</td></tr>";
				} 
				else
				{
					$output.="<tr style='border:none'><td class='right blue white-text' style='font-family:Comic Sans MS;margin:5px; margin-right:30px; color:white; max-width:400px;padding:10px; border-radius:10px;'>".$rowmsg."</td></tr>";
				}
			} 
			else
			{
				if($uto == $sid)
				{
					$output.="<tr style='border:none'><td class='right blue white-text' style='font-family:Comic Sans MS;margin:5px; margin-right:30px; color:white; max-width:400px;padding:10px; border-radius:10px;'>".$rowmsg."</td></tr>";
				} 
				else
				{
					$output.="<tr style='border:none'><td class='left grey lighten-4' style='font-family:Safari;margin:5px; max-width:400px;padding:10px; border-radius:10px;'>".$rowmsg."</td></tr>";
				}
			}
		}
		
	}
}

if (isset($_FILES['file']))
{
	$z_target_dir = "upload/";
	$z_fileName = $_FILES["file"]["name"];
	$z_fileTmpLoc = $_FILES["file"]["tmp_name"];
	$z_fileType = $_FILES["file"]["type"];
	//$z_fileSize = $_FILES["file"]["size"];
	$z_fileErrorMsg = $_FILES["file"]["error"];
	$z_lastelemarray = explode(".", $z_fileName);
	$z_fileExt = end($z_lastelemarray);
	$z_fileconvname = "sharedfile-".$z_fileName;
	$z_fileconvpath = $z_target_dir.$z_fileconvname.".".$z_fileExt;
	if (file_exists($z_fileTmpLoc))
	{
		if ($z_fileErrorMsg == 1)
		{
			echo "<script>alert('File not supported or large size. Please try again later');</script>";
		} 
		else
		{
			if (move_uploaded_file($z_fileTmpLoc, $z_fileconvpath))
			{
				$sqlfile = "INSERT INTO chat (msg, ufrom, uto, file, chatterid) VALUES ('$z_fileconvpath', '$chatuser', '$sid', 1, '$chatterid')";
				if ($con->query($sqlfile) === TRUE)
				{
					$output.="<tr style='border:none'><td class='right blue white-text' style='font-family:Comic Sans MS;margin:5px; margin-right:50px;max-width:400px; padding:10px; border-radius:10px;'><a class='blue white-text' href='".$z_fileconvpath."' target='blank'>".$z_fileconvpath."</a></td></tr>";
				}
			}
		}
	}
}

?>



<!DOCTYPE html>
<html>
<head>
<title>CHAT APPLICATION WITHOUT API</title>
    <link rel="stylesheet" href="/webchat/css/materialize.min.css">
<style>
*{
	margin:0;
	padding:0;
}
.chatbox,.searchlist{
		overflow:auto;
}
input[type="text"]:focus
{
	color:blue;
	padding-left:10px;
	font-family:Comic Sans MS;
}
.tabrowd{
	cursor:pointer;
}
.tabrowd:hover{
	background-color:#00bcd4;
	color:white;
}

</style>
</head>

<body>
	<div class="row">
		<div class="col l3 m4 s12" style="border:1px solid #00bcd4">
			<div class="row cyan">
				<div class="left white-text">
					<h5><b style="padding-left:10px;font-family:Comic Sans MS;"><?php echo $suname;?></b></h5>
				</div>
				<div class="right" style="padding:10px">
					<a class='dropdown-trigger' href='#' data-target='dropdown1' style="float:right"><img src="/webchat/images/settings_white.png" style="width:30px;"></a>
					<ul id='dropdown1' class='dropdown-content'>
						<li><a class="blue-text text-darken-2" style="font-family:Comic Sans MS;" href="change-password.php">Change Password</a></li>
						<li><a class="blue-text text-darken-2" style="font-family:Comic Sans MS;" href="logout.php">Logout</a></li>
					</ul>
					
				</div>
			</div>
			<div class="row">
				<div class="col l10 push-l1 m12 s12 searchlist" style="padding:0;border:1px solid #00bcd4;height:82vh;border-radius:5px;color:black;margin-bottom:0;">
					<table id="m" style="font-family:Comic Sans MS;">
							<?php echo $chatlist; ?>
					</table>
				</div>
			</div>
		</div>
		<div class="col l9 m8 s12" style="padding:0;border:1px solid #00bcd4">
			<div class="cyan" style="height:55px;">
				<div class="left cyan-text">
					<h5 style="background-color:white;font-family:Comic Sans MS;"><b><?php echo $cuname; ?></b></h5>
				</div>
			</div>
			<div class="chatbox" style="height:78vh; padding:3px;" id="chatboxd">
				<table style="border-collapse:collapse;" id="parent">
					<?php echo $output; ?>
				</table>
			</div>
			<div class="cyan lighten-4" style="border:1px solid #00bcd4;">
				<form id="fileform" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="POST" enctype="multipart/form-data">
					<div class="col l1 m1 s1 white-text file-field input-field">
						<div class="center">
							<img src="/webchat/images/attachment.png" width="20" height="25">
							<input type="file" name="file" onchange="submitform();" multiple>
						</div>
						<div class="file-path-wrapper" type="text">
						</div>
					</div>
				</form>
				<div class="col l10 m8 s8 push-m1 push-s1"><input type="text" name="msg" style="background-color:#eeeeee;border-radius:40px;border:none;padding-left:10px;margin-top:10px;" placeholder="enter text...." id="msg"></div>
				<div class="col l1 m1 s1 push-m1 push-s1"><button class="col btn-flat" id="submtbtn" style="padding-left:10px;margin-top:10px;" onclick="ajaxfunc();"><img src="/webchat/images/enter.png" style="color:white;margin-top:10px;" width="25"></div>
			</div>
		</div>
	</div>

	<script>
	gotolast();
	function gotolast()
	{
		var objDiv = document.getElementById("chatboxd");
		objDiv.scrollTop = objDiv.scrollHeight;
	}
	setInterval(checknewmsg, 1000);
	function checknewmsg()
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				msgres=this.responseText;
				if(msgres.length!=0)
				{
					var table = document.getElementById('parent');
					table.innerHTML+=msgres;
					gotolast();
				}
			}
		};
		xmlhttp.open("GET", "getmsg.php?chatuser="+<?php echo $chatuser; ?>, true);
		xmlhttp.send();
	}
	</script>
	
	<script>
	function ajaxfunc()
	{
		var msg = document.getElementById("msg").value;

		if (msg.length != 0)
		{
			var msg = encodeURIComponent(msg);
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function()
			{
				if (this.readyState == 4 && this.status == 200)
				{
					msgres=this.responseText;
					var table = document.getElementById('parent');
						var tr = document.createElement('tr');
						var td = document.createElement('td');
						var text = document.createTextNode(msgres);

						tr.appendChild(td);
						table.appendChild(tr);
						tr.style.border="none";
						tr.innerHTML=msgres;
						document.getElementById("msg").value="";
						gotolast();
				}
			};
			xmlhttp.open("GET", "submitmsg.php?chatuser="+<?php echo $chatuser; ?>+"&msg=" + msg, true);
			xmlhttp.send();
		}
	}
	</script>
	<script>
	addEventListener('keypress', function (e) 
	{
		var key = e.which || e.keyCode;
		if (key === 13) 
		{
			ajaxfunc();
		}
	});
	</script>
	<script>
	function submitform()
	{
		form=document.getElementById('fileform');
		form.method="POST";
		form.action="<?php echo $_SERVER["REQUEST_URI"];?>";
		form.enctype="multipart/form-data";
		form.submit();
	}
	</script>
	  
    <script src="/webchat/js/materialize.min.js"></script>
	<script>M.AutoInit();</script>
</body>
</html>
