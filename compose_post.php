<?php
session_start();
include_once "includes/db.inc.php";

if(!isset($_SESSION['user']))
{
  echo "Sorry! post cannot be submitted";
  exit();
}

$user = $_SESSION['user'];
$user = user_input($user);

if(isset($_POST['postMsgBody']) && isset($_SESSION['user'])){

  if(isset($_FILES['postFile']['name'])){
    $file_name = $_FILES['postFile']['name'];
    $file_tmp_name = $_FILES['postFile']['tmp_name'];
    $file_size = $_FILES['postFile']['size'];
    $file_error = $_FILES['postFile']['error'];
    $file_type = $_FILES['postFile']['type'];

    $file_ext_array = explode(".", $file_name);
    $file_ext = strtolower(end($file_ext_array));

    $allowed = array('jpg', 'jpeg', 'png');

    if(in_array($file_ext, $allowed))
    {
      $new_file_name = uniqid('postimage').".".$file_ext;
      
      $destination = "postImages/" . $new_file_name;
      $optimized_url = "postImages/O" . $new_file_name;
      $resized_url = "R" . $new_file_name;

      if(move_uploaded_file($file_tmp_name, $destination))
				{
          compressImage($destination,$optimized_url,25);
          resize(500, 350, $optimized_url, "postImages/". $resized_url);

          unlink($destination);
          unlink($optimized_url);

          $post_msg_body = mysqli_real_escape_string($conn, $_POST['postMsgBody']);
          $post_id = uniqid('POST');

          $query = "INSERT INTO posts (post_id, post_body, post_by, post_img, post_time, post_on_user, post_action) VALUES ('$post_id', '$post_msg_body', '$user', '$resized_url', now(), '', 'posted by');";
          if(mysqli_query($conn, $query)){
            echo "Posted successfully";
          }
          else {
            echo mysqli_connect_error();
          }
				}
				else
				{
					echo "Error in uploading File" . $file_name;
				}
    }
    else{
      echo "Upload only images";
    }
  }
  else{
    $post_msg_body = mysqli_real_escape_string($conn, $_POST['postMsgBody']);
    $post_id = uniqid('POST');

    $query = "INSERT INTO posts (post_id, post_body, post_by, post_img, post_time, post_on_user, post_action) VALUES ('$post_id', '$post_msg_body', '$user', '', now(), '', 'posted by');";
    if(mysqli_query($conn, $query)){
      echo "Posted successfully";
    }
    else {
      echo mysqli_connect_error();
    }
  }

}
else {
  echo "Something went wrong";
}

?>
