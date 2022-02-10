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
$error="";
if(isset($_POST['submit']))
{
	if(!empty($_POST['uname']) && !empty($_POST['pwd']))
	{
		$uname = $_POST['uname'];
		$pwd = $_POST['pwd'];
		$sql = "SELECT * FROM register WHERE uname='$uname' AND BINARY pwd='$pwd'";
		$result = $con->query($sql);
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				$_SESSION['uname'] = $row['uname'];
				$_SESSION['id'] = $row['id'];			
				header("Location: chatlist.php");
				die();
			}
		}
		else
		{
			$error= "<br><h4><p class='red-text center'>Username or password is incorrect</h4>";
		}
	}
	else
	{
		$error= "<br><h4><p class='red-text center'>Please enter username and password.</h4>";
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
.b{
color:orange;
cursor:pointer;
padding:6px 50px;	
background-color:white;
border:2px solid orange;
font-family:Algerian;
font-size:20px;
}


</style>
</head>

<body class="grey lighten-3"><br><br>
<div class="container">
<div class="row">
	<div class="col l12 m12 s12">
	  <h5 class="center blue-grey" style="color:white;font-family:Comic Sans MS; padding:20px 30px ;">CHAT APPLICATION WITHOUT API</h5>
	</div>
</div>
</div>
<br><br>
<div class="container">
	<div class="row">
		<div class="col l7 s12 m10 push-m1">
			<div class="grey lighten-4 card">
				<div class="center" style="padding:20px">
					<div class="center">
						<img src="/webchat/images/login.png" width="200"><br/><br/>
					</div>
					<form action="index.php" method="post">
					<?php echo $error; ?>
						<input type="text" style="background-color:#eeeeee;color:red;border-radius:40px;font-family:Comic Sans MS;border:none;padding-left:10px;" name="uname" placeholder="Enter Your Username"/><br/>
						<input type="password" style="background-color:#eeeeee;color:red;border-radius:40px;font-family:Comic Sans MS;border:none;padding-left:10px;" name="pwd" placeholder="Enter Your Password"/><br/><br/>
						<input type="submit" class="b" style="" name="submit" value="Login" />
					</form>   
				</div>
			</div>
		</div>
		<div class="col l5 s12 m10 push-m1">
			<div class="card">
				<div style="padding:20px">
					<strong>Are you new User..? Register here..</strong><a href="register.php"><br><b style="color:red">JoinUs</a></b>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="/webchat/js/materialize.min.js"></script>
<script>
	M.AutoInit();
</script>

</body>
</html>
