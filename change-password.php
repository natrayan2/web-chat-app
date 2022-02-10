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
$error=$correct="";
$suname = $_SESSION['uname'];
$sid = $_SESSION['id'];
if(!isset($_SESSION['id']))
{
 header("Location: index.php");
 exit;
} 
else
{
  if(isset($_POST['submit']))
  {
    if(!empty($_POST['newpwd']) && !empty($_POST['repwd']))
    {
		$newpwd = $_POST['newpwd'];
		$repwd = $_POST['repwd'];
		if($newpwd==$repwd)
		{
			$sql = "SELECT * FROM register WHERE id='$sid'";
			$result = $con->query($sql);
			if ($result->num_rows == 1)
			{
				while($row = $result->fetch_assoc())
				{
					$sql="UPDATE register SET pwd='$newpwd' WHERE id='$sid'";
					if ($con->query($sql) === TRUE)
					{
						$correct="<p class='card green center white-text' style='padding:10px;font-family:safari;font-size:20px;border-radius:20px;'>Password updated successfully.<a href='chatlist.php' class='white-text' style='padding:10px;font-family:safari;font-size:30px;border-radius:20px;'><u>Go To Chat Page</u></a></p>";
					}
					else
					{
						$error= "<p class='card red center white-text' style='padding:10px;font-family:safari;font-size:30px;'>Something went wrong</p><br>";
					}
				}
			} 
			else
			{
			   $error="<p class='card red center white-text'style='padding:10px;font-family:safari;font-size:30px;'>Please check the old password</p><br>";
			}
		} 
		else
		{
		$error="<p class='card red center white-text'style='padding:10px;font-family:safari;font-size:30px;'>New passwords do not match</p><br>";
		}
    } else
    {
      $error="<p class='card red center white-text' style='padding:10px;font-family:safari;font-size:30px;border-radius:20px;'>Please enter all the fields</p><br>";
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>CHAT APPLICATION WITHOUT API</title>
    <link rel="stylesheet" href="/webchat/css/materialize.min.css">
	<link rel="stylesheet" href="/webchat/js/materialize.min.js">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>
<body style="background-color:#e3f2fd;"><br><br><br>
<div class="row">
<div class="center col l6 offset-l3">
	<h4 style="font-family:safari;">Hello, <?php echo $suname; ?></h4>
</div>
</div>
<div class="row">
	<div class="container">
		<div class="card" style="opacity:0.5;">
			
			<h4 class="center light-blue lighten-1 white-text" style="font-family:safari;">Change-Password</h4>
		</div><br>
		<?php echo $correct; ?>
		<br>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<div class="white row">
			<div class="col l5 ">
				<p class="center">New Password </p>
			</div>
			<div class="col l7">
				<input type="password" name="newpwd">
			</div>
		</div>
		<div class="white row">
			<div class="col l5">
				<p class="center">Re-Enter Password </p>
			</div>
			<div class="col l7">
				<input type="password" name="repwd">
			</div>
		</div>
		<div class="row center">
			<input type="submit" name="submit" class="btn blue">
		</div>
		</form>
		<?php echo $error; ?>
	</div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>M.AutoInit();</script>
</body>
</html>