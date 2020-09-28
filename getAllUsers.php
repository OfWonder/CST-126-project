<?php
    $link = mysqli_connect("localhost", "root", "root", "activity1");
    
    if (!$link) 
    {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    
    $sql = "SELECT ID, FIRST_NAME, LAST_NAME, USERNAME, PASSWORD FROM users";
    $result = $link->query($sql);
    
    if ($result->num_rows > 0) 
    {
      while($row = $result->fetch_assoc()) 
      {
          echo "id: " . $row["ID"] . " - Name: " . $row["FIRST_NAME"] . " " . $row["LAST_NAME"] . " - User Name: " . $row["USERNAME"] . " - Password: " . $row["PASSWORD"] . "<br>" ;
      }
    } 
    else 
    {
      echo "0 results";
    }
    
    mysqli_close($link);
?>

