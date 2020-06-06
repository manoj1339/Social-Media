<?php
  session_start();
  include_once "includes/db.inc.php";

  $profile_url = "";
	if(!isset($_SESSION['user']))
	{
		header("location: index.php");
    exit();
	}
  else{
    $user = $_SESSION["user"];
  }

  $user = user_input($user);
  $user_info = fetch_user_info_profile($conn, $user);
?>


<!DOCTYPE html>
<html>
<head>
<title>Chats</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.1/emojionearea.min.css">
<link rel="stylesheet" href="styles/jquery-ui.css">
<link rel="stylesheet" href="styles/jquery-ui.min.css">
<link rel="stylesheet" href="styles/style.css" type="text/css" />

<script src="scripts/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.1/emojionearea.min.js"></script>
<script src="scripts/jquery-ui.min.js"></script>
<script src="scripts/jquery-ui.js"></script>
<script type="text/javascript" src="scripts/script.js"></script>
</head>
<body>
  <div id="body-wrapper">
  	<header>
  		 <a href="home.php"><div class="logo"><h1>HOME</h1></div></a>

  		 <!-- Navigation -->

       <ul class="fnc">
         <li><a href="home.php"><i class="fa fa-window-maximize" aria-hidden="true"></i></a>
           <div></div>
         </li>
			   <li><a href="friendRequest.php"><i class="fa fa-user-plus" aria-hidden="true"></i></a>
           <div><?php echo friend_count($user, $conn); ?></div>
         </li>
			 	 <li><a href="#"><i class="fa fa-bell" aria-hidden="true"></i></a>
           <div></div>
         </li>
         <li><a href="search.php"><i class="fa fa-search" aria-hidden="true"></i></a>
           <div></div>
         </li>
				 <li class="active"><a href="chat.php"><i class="fa fa-comments" aria-hidden="true"></i></a>
           <div id="totalUnreadMessages"></div>
         </li>
			 </ul>

       <div class="profile_img">
         <img id="profile_pic" data-button="close" src="<?php echo $user_info['destination']; ?>" alt="<?php echo $user; ?>" />
         <div id="userId"><?php echo $user; ?></div>

         <div id="profileModal">
            <div>
              <a href="profile.php?u=<?php echo $user; ?>">My Profile</a>
            </div>
            <div>
              <a href="edit_profile.php">Edit Profile</a>
            </div>
            <div>
              <form method="post" action="includes/logout.inc.php">
                <button type="submit" name="submit"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</button>
              </form>
            </div>
         </div>
       </div>
  	</header>

		<div id="wrapper">

    </div>

    <div id="modelWrapper">

    </div>

</div>

<?php include_once "footer.php";  ?>

</body>
</html>
