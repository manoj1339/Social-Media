<?php
session_start();
include_once "includes/db.inc.php";

if(isset($_GET["accept"])&& isset($_GET["id"]))
{

	  $user = $_SESSION["user"];
	  $uid = $_GET["id"];
		$accept = $_GET["accept"];


		$accept = preg_replace("#[^0-9]#", "", $accept);
		$accept = user_input($accept);
		$uid = user_input($uid);

		if($accept == 1)
		{
			$sql = "UPDATE friends SET accepted='1', datemade=now() WHERE (user_two='$user' AND user_one='$uid');";

			if(mysqli_query($conn, $sql))
			{
				echo '<span style="color:green;display:block;line-height:132px">You are now friend with '.$uid.'</span>';
			}
		}

		if($accept == 0)
		{
			$sql = "DELETE FROM friends WHERE (user_two='$user' AND user_one='$uid');";

			if(mysqli_query($conn, $sql))
			{
				echo '<span style="color:red;display:block;line-height:132px">You rejected '.$uid.' request</span>';
			}
		}


}
else
{
	header("location: index.php");
}

?>
