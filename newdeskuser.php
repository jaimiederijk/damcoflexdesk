<?php

  $calenderJson= @file_get_contents("https://outlook.office365.com/owa/calendar/f7b47bbaffdc4a2697d1d3c6393cbb27@hva.nl/59012f236f03454a9b440e99e71c30ef10245871582045065878/calendar.ics");
  
  $calenderJson2= @file_get_contents("https://calendar.google.com/calendar/ical/44ird5e4aofb9n5hjqb37ndgdo%40group.calendar.google.com/private-65a9ed8baeca92f0f21a491b24b0f2a5/basic.ics");
  echo($calenderJson);
  echo "</br></br></br></br>";
echo($calenderJson2);
  // $servername = "localhost";
  // $username = "root";
  // $password = "damcosecret";
  // $dbname = "damco";

  // // Create connection
  // $conn = new mysqli($servername, $username, $password, $dbname);

  // // Check connection
  // if ($conn->connect_error) {
  //     die("Connection failed: " . $conn->connect_error);
  // } 
  

  //   $name = $email = $fixed = $defaultpresent= "";

  // if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

  //   $name = test_input($_POST["name"]);
  //   $email = test_input($_POST["email"]);
  //   $defaultpresent = test_input($_POST["defaultpresent"]);
  //   $fixed = test_input($_POST["fixed"]);


  //   $sql = "INSERT INTO deskusers (name,fixed,defaultpresent, email)
  //   VALUES ('".$name."', '".$fixed."','". $defaultpresent."','".$email."')";

  //   if ($conn->query($sql) === TRUE) {
  //       echo "New record created successfully";
  //   } else {
  //       echo "Error: " . $sql . "<br>" . $conn->error;
  //   }
  //   header( "Location: settings.php" );

  // }

  // function test_input($data) {
  //   $data = trim($data);
  //   $data = stripslashes($data);
  //   $data = htmlspecialchars($data);
  //   return $data;
  // }

  // $conn->close();
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
<html>  
  <head>      
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./style.css">
    <title>flexdesk</title>

  </head>
</html>