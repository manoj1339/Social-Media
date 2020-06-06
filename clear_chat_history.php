<?php
session_start();
include_once "includes/db.inc.php";

if(isset($_SESSION['user']))
{
  if(isset($_POST['from']))
  {
    $user = $_SESSION['user'];
    $user = user_input($user);

    $from = $_POST['from'];
    $from = user_input($from);

    $query = "DELETE FROM chat WHERE user_one='$user' AND user_two='$from';";
    $sql = "DELETE FROM chat WHERE user_one='$from' AND user_two='$user';";
    mysqli_query($conn, $query);
    mysqli_query($conn, $sql);

  }
}
?>
