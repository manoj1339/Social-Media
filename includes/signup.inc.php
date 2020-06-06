<?php
  include_once "db.inc.php";


	if(isset($_POST['submit']))
	{

		$fname = user_input($_POST['fname']);
		$lname = user_input($_POST['lname']);
		$email = user_input($_POST['email']);
		$mobile = user_input($_POST['mobile']);
		$uid = user_input($_POST['user_id']);
		$pwd = user_input($_POST['pwd']);
		$rpwd = user_input($_POST['rpwd']);

		$fname = mysqli_real_escape_string($conn, $fname);
		$lname = mysqli_real_escape_string($conn, $lname);
		$email = mysqli_real_escape_string($conn, $email);
		$mobile = mysqli_real_escape_string($conn, $mobile);
		$uid = mysqli_real_escape_string($conn, $uid);
		$pwd = mysqli_real_escape_string($conn, $pwd);

		// VALIDATING USER INPUT DATA
		if($fname=="" || $lname=="" || $email=="" || $mobile=="" || $pwd=="" || $uid =="")
		{
			echo "Please fill all fields";
		}
    else if(strlen($fname) < 3 || strlen($lname) < 3)
    {
      echo "Firstname & lastname >= 3 chars";
    }
		else if(!preg_match("/^[a-zA-z]*$/", $fname))
			{
				echo "Firstname contain letters only";
			}
      else if(!preg_match("/^[a-zA-z]*$/", $lname))
			{

				echo "Lastname contain letters only";

			}
      else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			{

				echo "Invalid Email";

			}
      else if(!preg_match("/^[0-9{10}]*$/", $mobile))
      {

				echo "Invalid Mobile";

		  }
      else if(!preg_match("/^[a-zA-z@]*$/", $uid))
      {
         echo "Username contain only letters, numbers & @ symbol";
      }
			else if(strlen($uid) < 6 || strlen($uid) > 20)
			{
				echo "Username between 6-20 characters";
			}
      else if(strlen($pwd) < 6 || strlen($pwd) > 20)
      {

			    echo "Password between 6-20 characters";
			}
			else if($pwd != $rpwd)
			{
				echo "Password & Confirm password must be same";
			}
			else
			{
				// CHECKING IF USERNAME ALREADY EXISTS
				$sql = "SELECT * FROM users WHERE user_id='$uid' OR email='$email';";
				$result = mysqli_query($conn, $sql);
				$result_check = mysqli_num_rows($result);

				if($result_check > 0)
				{
					echo "This username already taken";
				}
				else
				{
					// INSERTING DATA INTO DATABASE
				  $sql = "INSERT INTO users (firstname, lastname, email, mobile, user_id, password, join_date) VALUES ('$fname', '$lname', '$email', '$mobile', '$uid', '$pwd', now());";

					if(mysqli_query($conn, $sql))
					{
						$query = "INSERT INTO profilephoto (user_id) VALUES ('$uid');";
						mysqli_query($conn, $query);
						echo "<span style='color:#0f0;'>Great! your account is created.</span>";
					}
					else{
						echo "Error: " . mysqli_error($conn);
					}
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
