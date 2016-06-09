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
  // echo "Connected successfully";
  $sql = "SELECT name FROM deskusers";  // amount of users
  $result = $conn->query($sql);
  $numberOfPeople = $result->num_rows;


  $nameErr = $emailErr = $calendarErr = $deskErr = "";
  $name = $email = $fixed = $defaultpresent = $calendar = "";
  $errorBoal = false;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST["deskuser"])) {
      

        if (empty($_POST["name"])) {
          $nameErr = "Name is required";
          $errorBoal = true;
        } else {
          $name = test_input($_POST["name"]);
          if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            $nameErr = "Only letters and white space allowed";
            $errorBoal = true; 
          }
        }
        if (empty($_POST["email"])) {
          $emailErr = "Email is required";
          $errorBoal = true;
        } else {
          $email = test_input($_POST["email"]);
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format"; 
            $errorBoal = true;
          }
        }  
        if (empty($_POST["defaultpresent"])) {
          if (!empty($_POST["fixed"])) {// not default =false when fixed=true
            $defaultpresent = 1;
          } else {
            $defaultpresent = 0;
          }
          
        } else {
          $defaultpresent = test_input($_POST["defaultpresent"]);
        }
        if (empty($_POST["fixed"])) {
          $fixed = 0;
        } else {
          $fixed = test_input($_POST["fixed"]);
        }
        if (empty($_POST["calendar"])) {
          $calendarErr = "calendar URL is needed";
          $errorBoal = true;
        } else {
          $calendar = test_input($_POST["calendar"]);
          // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
          if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$calendar)) {
            $calendarErr = "Invalid URL"; 
            $errorBoal = true;
          }
        }

        if (!$errorBoal) {
          $sql2 = "INSERT INTO deskusers (name,fixed,defaultpresent, email)
          VALUES ('".$name."', '".$fixed."','". $defaultpresent."','".$email."')";

          if ($conn->query($sql2) === TRUE) {
              echo "New record created successfully";
              $last_id = $conn->insert_id;

              addCalendar($conn,$last_id,$calendar);
              // $sql3 = "INSERT INTO calendars (deskuser_id, url)
              // VALUES ('".$last_id."','" .$calendar. "')";
              // if ($conn->query($sql3) === TRUE) {
              //   header("Location: {$_SERVER['PHP_SELF']}");
              // }
              // else {
              //   echo "Error" . $conn->error;
              // }
              
          } else {
              echo "Error: " . $sql2 . "<br>" . $conn->error;
          }      
        }
    }
    if (!empty($_POST["numOfDesks"])) {
      $errorBoal2 = false;

      if (empty($_POST["desks"])) {
        $deskErr = "number is required";
        $errorBoal2 = true;
      } else {
        if ($_POST["desks"]<0) {
          $deskErr = "Only positive numbers allowed";
          $errorBoal2 = true; 
        }
      }

      if (!$errorBoal2) {
        $sql3 = "UPDATE flexdesk_settings SET desks=".$_POST["desks"]." WHERE settings_id=1";

        if ($conn->query($sql3) === TRUE) {
            echo "New record created successfully";
            header("Location: {$_SERVER['PHP_SELF']}");
        } else {
            echo "Error: " . $sql3 . "<br>" . $conn->error;
        }        # code...
      }
      
    }
    if (!empty($_POST["changeFixed"])) {
      $fixedUserId = $_POST["userId"];

      changeFixed($conn,$fixedUserId);
    }
    if (!empty($_POST["addCal"])) {
      $calUserId = $_POST["userId"];
      $addCalUrl = $_POST["addCal"];

      addCalendar($conn,$calUserId,$addCalUrl);
    }

  }
  function changeFixed($conn,$userId) {

    $sql = "UPDATE deskusers SET fixed = !fixed WHERE deskusers. deskuser_id =".$userId;
    if ($conn->query($sql) === TRUE) {
      header("Location: {$_SERVER['PHP_SELF']}");
      //echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }  
  function addCalendar($conn,$userId,$calendar) {

    $sql = "INSERT INTO calendars (deskuser_id, url)
    VALUES ('".$userId."','" .$calendar. "')";
    if ($conn->query($sql) === TRUE) {
      header("Location: {$_SERVER['PHP_SELF']}");
    }
    else {
      echo "Error" . $conn->error;
    }
  }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  $numberOfDesk = 1;
  $sql4 = "SELECT desks FROM flexdesk_settings WHERE settings_id = 1 ";
  $result4 = $conn->query($sql4);
  if ($result4->num_rows > 0) {
      // output data of each row
      while($row4 = $result4->fetch_assoc()) {
          $numberOfDesk = $row4["desks"];
      }
  } else {
      echo "no number of desk specified";
  }


?>
<html>  
  <head>      
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./style.css">
    <title>flexdesk</title>

  </head>
  <body>
    <div id="wrapper">

      <header>
        <h1>Flexdesk occupancy admin</h1>
        <nav><a href="index.php">Flexdesk</a></nav>
        
      </header>
      <section class="deskvsemployee" >
        
        <div id="desk"><?php echo "<span>$numberOfDesk - </span>"; ?><img src="images/desk.svg"></div>
        <div id="employee"><?php echo "<span>$numberOfPeople - </span>"; ?><img src="images/deskperson.svg"></div>

        
      </section>
      <section class="settings">
        <section>
        <h1>Desk users </h1>
          <table>
            
            <tr>
              <th>Name</th>
              <th>E-mail</th>
              <th>Fixed desk</th>
              <th>Add calendar url</th>
            </tr>
             <?php

              $sql = "SELECT * FROM deskusers";
              $result = $conn->query($sql);
              $numberOfPeople = $result->num_rows;
              if ($result->num_rows > 0) {
                  // output data of each row
                  while($row = $result->fetch_assoc()) {
                    if ($row["fixed"]==1) {
                      $showFixed = "true";
                    } else {
                      $showFixed = "false";
                    }
                    $name = $row["name"];
                    $email = $row["email"];
                    $action = htmlspecialchars($_SERVER["PHP_SELF"]);
                    $userId = $row["deskuser_id"];

                    $calendar = "";

                    $sqlCal = "SELECT * FROM calendars WHERE deskuser_id=". $userId;
                    $resultCal = $conn->query($sqlCal);
                    if ($resultCal->num_rows > 0) {
                      while ( $rowCal = $resultCal->fetch_assoc()) {
                        $calendar=$calendar. "<td class='tdcal'>".$rowCal["url"]."</td>";
                      }
                    }
                     
                      echo <<<HTML
                      <tr>
                          <td>$name</td>
                          <td>$email</td>
                          <td class=$showFixed>$showFixed <form method="post" action=$action>  <input type="hidden" name="userId" value=$userId><input type="submit" name="changeFixed" value="change"></form></td>
                          <td><form method="post" action=$action>  <input type="hidden" name="userId" value=$userId><input type="text" name="addCal"><input type="submit" name="changeFixed" value="submit"></form></td>
                          $calendar
                        </tr>
HTML;
                  }
              } else {
                  echo "no desk user exist yet";
              }

            ?>
          </table>
        </section>
        <section>
          <h2>Add desk user</h2>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
            <label> Name: <input type="text" name="name"><span class="error">* <?php echo $nameErr;?></span></label><br>

            <!-- <label> Default at the office:<input type="checkbox" name="defaultpresent" value="1" checked><span class="error"></span></label><br> -->

            <label> fixed desk: <input type="checkbox" name="fixed" value="1" ><span class="error"></span></label><br>

            <label> E-mail: <input type="text" name="email"><span class="error">* <?php echo $emailErr;?></span></label><br>

            <label> Calendar: <input type="text" name="calendar"><span class="error">* <?php echo $calendarErr;?></span></label><br>

            <label> <input type="submit" name="deskuser" value="Add desk user"></label>
          </form>
          <!-- <h2>default present</h2> -->
          <h2>App settings</h2>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
            <label> Number of desks: <input type="number" name="desks"><span class="error">* <?php echo $deskErr;?></span></label><br>

            <label> <input type="submit" name="numOfDesks" value="Change Number"></label>
          </form>
        </section>
      </section> 
    </div>
  </body>
  <!-- <script src="moment.min.js"></script> -->
  <script src="javascript/app.js"></script>
</html>  