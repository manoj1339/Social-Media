<?php

session_start();
include_once "includes/db.inc.php";

if(!isset($_SESSION['user']))
{
	exit();
}


if(isset($_POST['to']))
{

	if(!empty($_POST['msg']))
	{
		$user = $_SESSION['user'];
		$to_user = user_input($_POST['to']);
		$msg = user_input($_POST['msg']);

		$to_user = mysqli_real_escape_string($conn, $to_user);
		$msg = mysqli_real_escape_string($conn, $msg);


		$sql = "INSERT INTO chat (user_one, user_two, message, date) VALUES ('$user', '$to_user', '$msg', now());";

		if(mysqli_query($conn, $sql))
		{
			echo 'working';
		}


	}


}
?>
