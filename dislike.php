<?php
session_start();
include_once "includes/db.inc.php";

if(!isset($_SESSION['user']))
{
  echo "Sorry! Something went wrong";
  exit();
}

$user = $_SESSION['user'];
$user = user_input($user);

if(isset($_POST['postId']) && isset($_SESSION['user']))
{
  $postID = mysqli_real_escape_string($conn, $_POST['postId']);

  $sql = "SELECT * FROM dislikes WHERE post_id='$postID' AND dislike_by='$user';";
  $result = mysqli_query($conn, $sql);
  $result_num_rows = mysqli_num_rows($result);

  if($result_num_rows > 0)
  {
    $query1 = "DELETE FROM dislikes WHERE post_id='$postID' AND dislike_by='$user';";

    if(mysqli_query($conn, $query1))
    {
      echo dislike_count($conn, $postID);
    }
    else{
      echo mysqli_connect_error();
    }
  }
  else {
    $query = "INSERT INTO dislikes (post_id, dislike_by) VALUES ('$postID', '$user');";

    if(mysqli_query($conn, $query))
    {
      echo dislike_count($conn, $postID);
    }

  }


}
?>
