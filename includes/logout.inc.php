<?php
   session_start();
   include_once "db.inc.php";
   
  if(isset($_POST['submit']))
	{

		//  logout time will updated

		$uname = $_SESSION["user"];
		$query = "UPDATE users SET last_login=now() WHERE user_id='$uname'";
		mysqli_query($conn, $query);


		session_unset();
		session_destroy();
		header("Location: ../index.php");
	}
?>
