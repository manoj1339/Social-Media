<?php

session_start();
include_once "includes/db.inc.php";

if(!isset($_SESSION['user'])){
  header('location: index.php');
	exit();
}


if(isset($_POST['to']))
{
	$user = $_SESSION['user'];
	$to_user = $_POST['to'];
  $sql = "SELECT * FROM chat WHERE (user_one='$user' AND user_two='$to_user') OR (user_one='$to_user' AND user_two='$user') ORDER BY id ASC;";

	$result = mysqli_query($conn, $sql);
	$result_check = mysqli_num_rows($result);
	$output = "";

  if($result){

    if($result_check > 0)
  	{
  		while($row = mysqli_fetch_assoc($result))
  		{
  			$sender = $row["user_one"];
  			$reciever = $row["user_two"];
  			$message = $row["message"];
  			$date = $row["date"];


  			if($sender == $user)
  			{
  				$output .= '<div class="msg you">'.$message.'<div class="dateString">'. date("l h:i:sA", strtotime($date)) .'</div></div>';
  			}
  			else
  			{
  				$output .= '<div class="msg friend">'.$message.'<div class="dateString">'. date("l h:i:sA", strtotime($date))  .'</div></div>';
  			}
  		}

  		echo "$output";
  	}
  	else
  	{
  		echo '<div style="text-align:center">No messages</div>';
  	}

  }



}

?>
