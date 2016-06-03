<?php
  $servername = "localhost";
  $username = "root";
  $password = "damcosecret";
  $dbname = "damco";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 
  

    $name = $email = $fixed = $defaultpresent= "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    $name = test_input($_POST["name"]);
    $email = test_input($_POST["email"]);
    $defaultpresent = test_input($_POST["defaultpresent"]);
    $fixed = test_input($_POST["fixed"]);


    $sql = "INSERT INTO deskusers (name,fixed,defaultpresent, email)
    VALUES ('".$name."', '".$fixed."','". $defaultpresent."','".$email."')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    header( "Location: settings.php" );

  }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  $conn->close();
  // echo "Connected successfully";

  // $sql = "SELECT * FROM deskusers";
  // $result = $conn->query($sql);

  // if ($result->num_rows > 0) {
  //     // output data of each row
  //     while($row = $result->fetch_assoc()) {
  //         echo "deskuser_id: " . $row["deskuser_id"]. " - Name: " . $row["name"]. " " . $row["name"]. "<br>";
  //     }
  // } else {
  //     echo "0 results";
  // }

?>