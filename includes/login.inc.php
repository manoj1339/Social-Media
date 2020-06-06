<?php
  session_start();

  include_once "db.inc.php";

	if(isset($_POST['submit']))
	{

		$uname = user_input($_POST['uname']);
		$pwd = user_input($_POST['pwd']);

		$uname = mysqli_real_escape_string($conn, $uname);
		$pwd = mysqli_real_escape_string($conn, $pwd);

		if($uname=="" || $pwd=="")
		{
			echo "Please Enter correct username & password.";
		}
		else
		{
			// RETRIEVING DATA FROM DATABASE
			$sql = "SELECT * FROM users WHERE (user_id='$uname' OR email='$uname') AND password='$pwd' LIMIT 1;";

			if($result=mysqli_query($conn, $sql))
			{
				$resultCheck = mysqli_num_rows($result);

				if($resultCheck < 1)
				{
          header('location: ../index.php');
					echo "<script>alert('Enter valid username and password')</script>";
				}
				else
				{


					while($row = mysqli_fetch_assoc($result))
					{

						// CREATING SESSION FOR WEBSITE
						$_SESSION['user'] = $row['user_id'];
            $user = $_SESSION["user"];

						header("Location: ../home.php");
            $count = user_count($conn, $user);

            if($count < 1){
              mysqli_query($conn, "INSERT INTO login_details (user_id, last_activity) VALUES ('$user', now());");
            }

					}
				}
			}
			else
			{
				echo mysqli_error($conn);
			}

		}

	}
	else
	{
		header("Location: ../index.php");
		exit();
	}

	mysqli_close($conn);
?>
