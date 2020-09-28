<?php
	//store the form data in local variables
    $uname = $_POST["userNameTextBox"];
    $pword = $_POST["passwordTextBox"];
	$time = time() - 30;
	$loginFailed = "<h2>Login Failed</h2>";
	$returnToLogin = "<br><br><a href=login.html>Return to login</a></b>";
	$toManyAttempts = "To many failed login attempts. Please try again after 30 sec";

	//Check if username and password are empty
	if (ctype_space($uname) || $uname == "" || ctype_space($pword) || $pword == "")
    {
         echo $loginFailed . "Username and Password cannot be empty." . $returnToLogin;
		 exit;
    }   
	
	$link = mysqli_connect("MYSQLCONNSTR_localdb", "root", "root", "project");
    
    //Check if connection to database is possible
    if (!$link)
    {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
	
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
	{
		$ipAddress = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		$ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
		$ipAddress = $_SERVER['REMOTE_ADDR'];
	}
	
	$query = mysqli_query($link,"select count(*) as total_count from loginlog where tryTime > $time and ipAddress='$ipAddress'");
	$check_login_row = mysqli_fetch_assoc($query);
	$totalCount = $check_login_row['total_count'];
	
	if($totalCount == 3)
	{
		$msg = $loginFailed . $toManyAttempts . $returnToLogin;
	}
	else
	{
		$res = mysqli_query($link,"select * from users where username='$uname' and password='$pword'");
		
		if(mysqli_num_rows($res))
		{
			$_SESSION['IS_LOGIN'] = 'yes';
			mysqli_query($link,"delete from loginlog where ipAddress='$ipAddress'");

			$msg = "login Successful!";
		}
		else
		{
			$totalCount++;
			$remainingAttempts = 3 - $totalCount;
			
			if($remainingAttempts == 0)
			{
				$msg = $loginFailed . $toManyAttempts . $returnToLogin;
			}
			else
			{
				$msg = $loginFailed . "Please enter valid credentials.<br/>$remainingAttempts attempts remaining." . $returnToLogin;
			}
			
			$try_time = time();
			mysqli_query($link, "insert into loginlog(ipAddress,tryTime) VALUES('$ipAddress','$try_time')");
		}
	}
    
	echo $msg;
	
    mysqli_close($link);
?>

