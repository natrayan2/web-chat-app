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
if(isset($_POST['uname']))
{
	if(!empty($_POST['uname']))
	{
		$uname = $_POST['uname'];
		//$email = $_POST['email'];
		//$phone = $_POST['phone'];
		$pwd = $_POST['pwd'];
		$repwd = $_POST['repwd'];
		if($pwd!=$repwd)
		{
			$error="<h4><p class='red lighten-3 white-text center'>Passwords do not match</p></h4><br>";
		}
		else
		{
			$sql="INSERT INTO register (uname,pwd) VALUES ('$uname','$pwd')";
			if ($con->query($sql) === TRUE)
			{
				header("Location: index.php");
			}
			else
			{
				$temp=$con->error;
				if (strpos($temp, 'Duplicate') !== false)
				{
					$error= "<p class='white-text center'>Username already exist</p><br>";
				} 
				else
				{
					$error="<p class='white-text center'>".$temp."</p><br>";
				}
			}
		}
	} 
	else
	{
		$error= "<p class='white-text center'>Please enter all the fields</p><br>";
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
.b:hover{
	background-color:orange;
	border:2px solid white;
	color:white;
}
.input-container {
  
    width: 100%;
  margin-bottom: 15px;
}

</style>
</head>
<body class="grey lighten-3"><br>
<div class="container">
<div class="row">
	<div class="col l12 m12 s12">
	  <h5 class="center blue-grey" style="color:white;font-family:Comic Sans MS; padding:20px 30px ;">CHAT APPLICATION WITHOUT API</h5>
	</div>
</div>
</div>

<div class="container">
	<div class="row">
		<div class="col s12 m8">
			<div class="card grey lighten-4">
				<div style="padding:20px">
				<h5 class="center" style="color: orange;"><b><img src="/webchat/images/r.png" width="50"></b></h5>
					<form action="register.php" method="post">
					<?php echo $error; ?>
						<div class="input-container">
							<input type="text" style="background-color:#eeeeee;border-radius:40px;border:none;padding-left:10px;" name="uname" placeholder="Enter Your Username"/><br/>
							<input type="password" style="background-color:#eeeeee;border-radius:40px;border:none;padding-left:10px;" name="pwd" placeholder="Enter Your Password"/><br/>
							<input type="password" style="background-color:#eeeeee;border-radius:40px;border:none;padding-left:10px;" name="repwd" placeholder="Enter Your Re-Password"/><br/><br/>
						</div>
						<div class="center">
							<input type="submit" class="center b" name="submit" value="REGISTER" />
						</div>
					</form>   
				</div>
			</div>
		</div>
		<div class="col s12 m4">
			<div class="card">
				<div style="padding:20px">
					<strong>Already have a account?Login here....<a href="index.php"><br></strong><b style="color:red">Login</a></b>
				</div>
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
