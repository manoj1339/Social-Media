<?php

  // DATABASE CONNECTION FOR ROOT USER

  $server_name = "localhost";
	$user_name = "root";
	$password = "";
	$db_name = "social";

	$conn = mysqli_connect($server_name, $user_name, $password, $db_name);
  mysqli_query($conn,"SET CHARACTER SET 'utf8mb4_bin'");

  // Retrieve user's info and profile pics
  function fetch_user_info_profile($conn, $user){
    // user info
    $query = "SELECT * FROM users WHERE user_id='$user' LIMIT 1;";

  	$result = mysqli_query($conn, $query);
  	$result_rows = mysqli_num_rows($result);

    $user_info = array();

  	if($result_rows > 0)
  	{
  		while($row_data = mysqli_fetch_assoc($result))
  		{
  			$user_info['firstname'] = $row_data['firstname'];
  			$user_info['lastname'] = $row_data['lastname'];
  			$user_info['email'] = $row_data['email'];
  			$user_info['mobile'] = $row_data['mobile'];
  			$user_info['join_date'] = $row_data['join_date'];
  			$user_info['last_login'] = $row_data['last_login'];
        $user_info['destination'] = $row_data['destination'];
  		}

      if($user_info['destination'] == null)
      {
        $user_info['destination'] = "icons/user.png";
      }
      else
      {
        $user_info['destination'] = $user_info['destination'];
      }

  	}

    return $user_info;

  }

  // function to validate user INPUT
  function user_input($data)
  {
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    return $data;
  }

  // function to get friend request count
  function friend_count($user, $conn){
    $query_friend_count = "SELECT * FROM friends WHERE user_two='$user' AND accepted='0';";
    $friend_count_execute = mysqli_query($conn, $query_friend_count);
    $friend_count = mysqli_num_rows($friend_count_execute);

    if($friend_count == 0){
      $friend_count = "";
    }
    else{
      $friend_count = "<span>". $friend_count ."</span>";
    }
    return $friend_count;
  }

  // function to decide user is online or offline
  function fetch_user_last_activity($conn, $user){
    $user_last_activity = '';
    $query = "SELECT * FROM login_details WHERE user_id='$user' LIMIT 1";
    $result = mysqli_query($conn, $query);

    while($row = mysqli_fetch_assoc($result)){
      $user_last_activity = $row['last_activity'];
    }

    return $user_last_activity;
  }

  // avoid duplication in login_details table in DATABASE
  function user_count($conn, $user){
    $query = "SELECT * FROM login_details WHERE user_id='$user'";
    $result = mysqli_query($conn, $query);
    $count = mysqli_num_rows($result);
    return $count;
  }

  /* Function to update whether message read or unread */
  function fetch_total_msg_count($conn, $user){
    $query = "SELECT * FROM chat WHERE user_two='$user' AND is_read='unread'";
    $result = mysqli_query($conn, $query);
    $count = mysqli_num_rows($result);

    if($count == 0){
      $count = "";
    }
    else{
      $count = "<span>". $count ."</span>";
    }
    return $count;
  }

  /* Function to update whether message read or unread */
  function fetch_individual_msg_count($conn, $user, $to_user){
    $query = "SELECT * FROM chat WHERE user_one='$to_user' AND user_two='$user' AND is_read='unread'";
    $result = mysqli_query($conn, $query);
    $count = mysqli_num_rows($result);

    if($count == 0){
      $count = "";
    }
    else{
      $count = "<span>". $count ."</span>";
    }
    return $count;
  }

  /* function to know that time elapsed from activity */

  //Function definition
  function timeAgo($time_ago)
  {
      $time_ago = strtotime($time_ago);
      $cur_time   = time();
      $time_elapsed   = $cur_time - $time_ago;
      $seconds    = $time_elapsed ;
      $minutes    = round($time_elapsed / 60 );
      $hours      = round($time_elapsed / 3600);
      $days       = round($time_elapsed / 86400 );
      $weeks      = round($time_elapsed / 604800);
      $months     = round($time_elapsed / 2600640 );
      $years      = round($time_elapsed / 31207680 );
      // Seconds
      if($seconds <= 60){
          return "just now";
      }
      //Minutes
      else if($minutes <=60){
          if($minutes==1){
              return "one minute ago";
          }
          else{
              return "$minutes minutes ago";
          }
      }
      //Hours
      else if($hours <=24){
          if($hours==1){
              return "an hour ago";
          }else{
              return "$hours hrs ago";
          }
      }
      //Days
      else if($days <= 7){
          if($days==1){
              return "yesterday";
          }else{
              return "$days days ago";
          }
      }
      //Weeks
      else if($weeks <= 4.3){
          if($weeks==1){
              return "a week ago";
          }else{
              return "$weeks weeks ago";
          }
      }
      //Months
      else if($months <=12){
          if($months==1){
              return "a month ago";
          }else{
              return "$months months ago";
          }
      }
      //Years
      else{
          if($years==1){
              return "one year ago";
          }else{
              return "$years years ago";
          }
      }
  }

  // function to fetch like count
  function like_count($conn, $post)
  {
    $query = "SELECT * FROM likes WHERE post_id='$post';";
    $result = mysqli_query($conn, $query);
    $result_rows = mysqli_num_rows($result);

    return $result_rows;
  }

  // function to check post like or not
  function like_or_not($conn, $post, $user)
  {
    $query = "SELECT * FROM likes WHERE post_id='$post' AND like_by='$user';";
    $result = mysqli_query($conn, $query);
    $result_rows = mysqli_num_rows($result);

    if($result_rows > 0)
    {
      return 'liked';
    }
    else {
      return '';
    }

  }

  // function to fetch dislike count
  function dislike_count($conn, $post)
  {
    $query = "SELECT * FROM dislikes WHERE post_id='$post';";
    $result = mysqli_query($conn, $query);
    $result_rows = mysqli_num_rows($result);

    return $result_rows;
  }

  // function to check post dislike or not
  function dislike_or_not($conn, $post, $user)
  {
    $query = "SELECT * FROM dislikes WHERE post_id='$post' AND dislike_by='$user';";
    $result = mysqli_query($conn, $query);
    $result_rows = mysqli_num_rows($result);

    if($result_rows > 0)
    {
      return 'disliked';
    }
    else {
      return '';
    }

  }

  // Function to fetch comment from DATABASE
  function fetch_comment_user($conn, $postid)
  {
    $output = "";
    $query = "SELECT * FROM comments WHERE post_id='$postid';";

    $result = mysqli_query($conn, $query);
    $result_rows = mysqli_num_rows($result);


    if($result_rows > 0)
    {
      while($row = mysqli_fetch_assoc($result))
      {
        $userInfo = fetch_user_info_profile($conn, $row['comment_by']);
        $output .= '
        <div class="commentedUser">
          <div class="commentedUserImage">
            <img src="'.$userInfo['destination'].'" alt="user"><br/>
            <span class="commentedUsername">'.$row['comment_by'].'</span>
          </div>
          <div class="commentedUserMessage">
            '.$row['comment'].'
          </div>
        </div>
        ';
      }

      return $output;
    }
    else
    {
      $output .= "No comments Found";
      return $output;
    }

  }

  // function to fetch dislike count
  function comment_count($conn, $post)
  {
    $query = "SELECT * FROM comments WHERE post_id='$post';";
    $result = mysqli_query($conn, $query);
    $result_rows = mysqli_num_rows($result);

    return $result_rows;
  }

  // Function to compressed the image
  function compressImage($source_url, $destination_url, $quality) {

      //$quality :: 0 - 100

      if( $destination_url == NULL || $destination_url == "" ) $destination_url = $source_url;

      $info = getimagesize($source_url);

      if ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg')
      {
          $image = imagecreatefromjpeg($source_url);
          //save file
          //ranges from 0 (worst quality, smaller file) to 100 (best quality, biggest file). The default is the default IJG quality value (about 75).
          imagejpeg($image, $destination_url, $quality);

          //Free up memory
          imagedestroy($image);
      }
      elseif ($info['mime'] == 'image/png')
      {
          $image = imagecreatefrompng($source_url);

          imageAlphaBlending($image, true);
          imageSaveAlpha($image, true);
          /* chang to png quality */
                 $png_quality = 9 - round(($quality / 100 ) * 9 );
                 imagePng($image, $destination_url, $png_quality);//Compression level: from 0 (no compression) to 9(full compression).
                 //Free up memory
                 imagedestroy($image);
      }
      else
      {
        return FALSE;
      }

      return $destination_url;
  }

  // Function to change the dimension of images
  function resize($newWidth, $newHeight, $originalFile, $targetFile) {

      $info = getimagesize($originalFile);
      $mime = $info['mime'];

      switch ($mime) {
              case 'image/jpeg':
                      $image_create_func = 'imagecreatefromjpeg';
                      $image_save_func = 'imagejpeg';
                      $new_image_ext = 'jpg';
                      break;

              case 'image/png':
                      $image_create_func = 'imagecreatefrompng';
                      $image_save_func = 'imagepng';
                      $new_image_ext = 'png';
                      break;

              case 'image/gif':
                      $image_create_func = 'imagecreatefromgif';
                      $image_save_func = 'imagegif';
                      $new_image_ext = 'gif';
                      break;

              default:
                      echo "<script>alert('File not supported');</script>";
      }
      $img = $image_create_func($originalFile);
          list($width, $height) = getimagesize($originalFile);

          $tmp = imagecreatetruecolor($newWidth, $newHeight);
          imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

          if (file_exists($targetFile))
          {
             unlink($targetFile);
          }
          $image_save_func($tmp, "$targetFile");
  }

  // Function to find each user's friends count
  function friends_count_for_each_user($conn, $user)
  {
    $friends = array();

    $q1 = "SELECT * FROM friends WHERE user_one='$user' AND accepted='1'";

    $friend_list1 = mysqli_query($conn, $q1);
    while($users = mysqli_fetch_assoc($friend_list1)){
      array_push($friends, $users['user_two']);
    }

    $q2 = "SELECT * FROM friends WHERE user_two='$user' AND accepted='1'";

    $friend_list2 = mysqli_query($conn, $q2);
    while($users = mysqli_fetch_assoc($friend_list2)){
      array_push($friends, $users['user_one']);
    }

    return count($friends);

  }

  // Function to fetch individual user's post count
  function post_count_for_each_user($conn, $user)
  {
    $query = "SELECT * FROM posts WHERE post_by='$user'";

    $result = mysqli_query($conn, $query);
    $num_rows = mysqli_num_rows($result);

    return $num_rows;

  }

?>
