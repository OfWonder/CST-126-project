<?php
    //store the form data in local variables
	$uname = $_POST["userNameTextBox"];
    $email = $_POST["emailTextBox"];
    $nname = $_POST["nickNameTextBox"];
    $pword = $_POST["passwordTextBox"];
	$cpword = $_POST["confirmPasswordTextBox"];
    
	//create bools for form entry error checking
	$badUserName = false;
	$badEmail = false;
	$badPassword = false;
	$passwordNoMatch = false;
	$userNameTaken = false;
	$errorMessage = "<h2>An error occured:</h2>";
	
	//Verify form was filled out correctly, and if not display apporpriate errors
    
    if (ctype_space($uname) || $uname == "")
    {
        $badUserName = true;
		$errorMessage .= "No Username entered.<br>";
    }
    
    if (ctype_space($email) || $email == "")
    {
        $badEmail = true;
		$errorMessage .= "No Email entered.<br>";	
    }
	
	$emailAt = strpos($email, '@');
	$emailDot = strpos($email, '.');
	
	if ($badEmail == false && $emailAt == null || $emailDot == null)
    {
        $badEmail = true;
		$errorMessage .= "Invalid Email format.<br>";
    }
    
	if ($badEmail == false)
    {
		$emailAtArray = implode("@", $email);
		$emailDotArray = implode(".", $email);
		
		if (count($emialAtArray) > 2 || count(emailDotArray) > 2)
		{
			$badEmail = true;
			$errorMessage .= "Invalid Email format arr.<br>";
		}
    }
	
    if (ctype_space($nname) || $nname == "")
    {
		$nname = $uname;
    }
	
	if (ctype_space($pword) || $pword == "")
    {
        $badPassword = true;
		$errorMessage .= "No Password entered.<br>";
    }
	
	if (ctype_space($cpword) || $cpword == "" || $cpword != $pword)
    {
        $passwordNoMatch = true;
		$errorMessage .= "Passwords do not match.<br>";
    }
    
	$$database = "127.0.0.1:52057";
	$link = mysql_connect($database, "azure", "6#vWHD_$", "project");
    
    //Check if connection to database is possible
    if (!$link) 
    {
		echo $errorMessage;
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
	
	$upuname = 	strtoupper($uname);
	$sql="SELECT FROM users (username) WHERE UPPER(username)=$upuname";
	$result = $link->query($sql);
    
	//check if username is already in use
	if($result->num_rows >= 1)
	{
		$userNameTaken = true;
		$errorMessage .= "Username already exists.<br>";
	}
	
	//display appropiate error messages if any of the verifications failed
	if ($badUserName || $badEmail || $badPassword || $passwordNoMatch || $userNameTaken)
	{
		mysqli_close($link);
		echo $errorMessage;
		echo '<br><a href="register.html"><b>Return to registration page</b></a>';
		exit;
	}
	else
    {
		//Attemp to create user if form was filled out correctly
		$sql = "INSERT INTO users (email, username, nickname, password)
		VALUES ('$email', '$uname', '$nname', '$pword')";
		
		if ($link->query($sql) === TRUE)
		{
			echo "New user created successfully";
			echo '<br><a href="login.html"><b>Go to login page</b></a>';
		}
		else
		{
			echo $errorMessage;
			echo "Error: " . $sql . "<br>" . $conn->error;
			echo '<br><a href="register.html"><b>Return to registration page</b></a>';
		}
    }
	
    mysqli_close($link);
?>

