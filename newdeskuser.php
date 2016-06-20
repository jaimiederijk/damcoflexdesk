<?php
  $servername = "10.3.0.63";
  $username = "jaapdzq3_jaimie";
  $password = "damcosecret";
  $dbname = "jaapdzq3_damco";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 
  

  // $form = $_POST;

  $sql = "UPDATE `flexdesk_settings` SET `desks` = '51' WHERE `flexdesk_settings`.`settings_id` = 1";

  if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
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