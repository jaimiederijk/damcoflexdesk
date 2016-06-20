<?php
  session_start();

  $servername = "localhost";
  $username = "root";
  $password = "damcosecret";
  $dbname = "damco";
  
  if (!isset($_SESSION["dateNumber"])) {
    $_SESSION["dateNumber"]=0;
  }
  
  $currentShortDate = date('Ymd', strtotime($_SESSION["dateNumber"]." days"));
  $dateNumber = 0;
   
  //require 'php/parsecalendar.php';
  require 'php/cronjob.php';

  $numberOfPeople = 0;
  $fixexdeskNotPresent = 0;


  // chosen date 
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty($_GET['changeDay'])) {
      $changeDay = $_GET['changeDay'];
      if ($changeDay=="next") {
        $_SESSION["dateNumber"]++;
        
      }
      if ($changeDay=="prev") {
        $_SESSION["dateNumber"]--;

      }
      if ($changeDay=="today") {
        $_SESSION["dateNumber"]=0;
      } 
      header("Location: {$_SERVER['PHP_SELF']}");  
    }
 

  }


  $currentDate = date("d-m-Y", strtotime($_SESSION["dateNumber"]." days"));

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }



  // handle post request
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // select user form
    if (!empty($_POST['selectuser'])) {
      if (!empty($_POST['selecteduser'])) {
        
        setUserCookie($_POST['selecteduser']);
      }
      
    }
    // change this user custom calendar 
    if (!empty($_POST['changeGoingOffice'])) {
      if (!empty($_POST['date'])) {
        changeUserGoingToOffice($conn,$_POST['date']);
        
      }
      
    }   
  }


  //manualCronjob
  //loopDays();
  function getCustomCalResult ($conn,$date) {
    $resultdate = date("Y-m-d",strtotime($date));
    $num = 0;
    $sql = "SELECT * FROM `custom_calendar` WHERE notofficedate = '".$resultdate."'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $num+=1;
      }
    } else {
      
    }
    return $num;
  }

  function getOccupencyResults ($conn,$currentShortDate) {
    $resultdate = date("Y-m-d",strtotime($currentShortDate));
    $num = getCustomCalResult ($conn,$resultdate);
    
    $resultdesk=array();
    $sql = "SELECT * FROM `occupancy_results` WHERE resultdate = '".$resultdate."'";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {

      array_push($resultdesk, $row['desk'],$row['people']-=$num);
      return $resultdesk;
    }

  }

  $resultDeskOccupency = getOccupencyResults($conn,$currentShortDate);
  $numberOfPeople=$resultDeskOccupency[1];
  $numberOfDesk=$resultDeskOccupency[0];


  function setUserCookie ($id) {
    $cookie_name = "user_id";
    $cookie_value = $id;
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 360), "/");
    header("Location: {$_SERVER['PHP_SELF']}");
  }
       
  function getUsersOptions($conn ) {
    $text = "";
    $sql = "SELECT * FROM deskusers";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $text = $text ."<option value=".$row["deskuser_id"].">". $row["name"] . "</option>";
      }
    }
    return $text;
  }

  function getUserId () {
    return $_COOKIE["user_id"];
    //expand with backups
  }

  function changeUserGoingToOffice ($conn,$date) {
    $notOfficeDate = date("Y-m-d",$date);
    $guest=0;
    $deskuser_id=getUserId();

    $customCalendarId = checkCustomCalendar($conn,$date);
    //if(isset($_COOKIE["user_id"])) {//!!!!!!!!!!more backup
        //$deskuser_id=;
    //}
    
    if ($customCalendarId) {
      $sql = "DELETE FROM `custom_calendar` WHERE custom_calendar_id=". $customCalendarId;//;//
      if ($conn->query($sql) === TRUE) {
        header("Location: {$_SERVER['PHP_SELF']}");
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    } else {
      $sql = "INSERT INTO custom_calendar (notofficedate,guest,deskuser_id)
      VALUES ('".$notOfficeDate."','".$guest."','".$deskuser_id."')";
      if ($conn->query($sql) === TRUE) {
        header("Location: {$_SERVER['PHP_SELF']}");
        //echo("succ");
      }
      else {
        echo "Error" . $conn->error;
      }      
    }


  }

  function checkCustomCalendar ($conn,$dateStamp) {
    //echo($conn);
    $user = getUserId();
    $date = date("Y-m-d",$dateStamp);
    $sql = "SELECT * FROM `custom_calendar`WHERE deskuser_id =".$user." AND notofficedate='".$date."'";// " = ". ;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      //return true;
      while($row = $result->fetch_assoc()) {
        return $row["custom_calendar_id"];
      }
    } else {
      return false;
    }
  }

  function loopThroughWeeks ($weeks,$conn) {
    $currentWeek = strtotime('previous Sunday');
    $today = strtotime('today');
    $result = "";
    //$week = 0;
    for ($i=0; $i < $weeks; $i++) {
      $result = $result . "<div class='weeknumber'> Week: ".date('W',strtotime('+'.$i.' week', $today))."</div>";
      $result = $result .  createWeekdays(strtotime('+'.$i.' week', $currentWeek),$conn);
    }
    return $result;
  }

  function createWeekdays($sundayTimeStamp,$conn) {
    $timestamp = $sundayTimeStamp;
      //$days = array();strtotime('previous Sunday');
    $action =  htmlspecialchars($_SERVER["PHP_SELF"]);
    $result = "";

    for ($i = 0; $i < 5; $i++) {
      $className="";
      $img="";
      //checkCustomCalendar($conn,$user);
      
        //$days[] = strftime('%A', $timestamp);
      $timestamp = strtotime('+1 day', $timestamp);
      if ($timestamp==strtotime('today')) {
        $className="today";
      } else if ($timestamp<strtotime('today')) {
        $className="past";
      }
      if(checkCustomCalendar($conn,$timestamp)) {
        $img="desk.svg";
        $className=$className." emptydesk";
      } else {
        $img="deskperson.svg";
      }
      

      $result = $result . "<div class=".$className.">            
        <form method='post' action=".$action.">
          <span>".date('d/m',$timestamp)."</span>
          <img src='images/".$img."'>
          <input type='hidden' name='date' value=".$timestamp.">
          <input type='submit' name='changeGoingOffice' value='change'>
        </form>
      </div>";
    }
    return $result;
  }

  function getUserName ($conn,$id) {
    $sql = "SELECT * FROM `deskusers` WHERE deskuser_id =".$id;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      //return true;
      while($row = $result->fetch_assoc()) {
        return $row["name"];
      }
    } else {
      echo "Error" . $conn->error;
    }

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
        <h1>Flexdesk occupancy </h1>
        <nav><a href="settings.php">Settings<img src="images/gear_icon.svg"></a></nav>
      </header>
      
      <section class="date">
        <a href="?changeDay=prev" class=""><</a><?php echo "<span> $currentDate </span>" ; ?><a href="?changeDay=next" class="">></a><a href="?changeDay=today" id="todaylink" class="">Today</a>
      </section>
      <section class="deskvsemployee" >
        
        <div id="desk"><?php echo "<span>$numberOfDesk - </span>"; ?><img src="images/desk.svg"></div>
        <div id="employee"><?php echo "<span>$numberOfPeople - </span>"; ?><img src="images/deskperson.svg"></div>

        
      </section>
      <section id="usercal">
        <section id="select_user">
          <h2>Select user</h2>
          <?php 
            if(!isset($_COOKIE["user_id"])) {
              $action = htmlspecialchars($_SERVER["PHP_SELF"]);
              $optionText = getUsersOptions($conn);
              echo <<<HTML
              <p>Select your name to display your calendar</p>

              <form method="post" action=$action>
                <select name="selecteduser">              
                  $optionText
                </select>
                <input type="submit" name="selectuser" value="select user">
              </form>
HTML;
            } else {
              $userName = getUserName($conn,$_COOKIE["user_id"]);
                //echo "Cookie is set!<br>";
                echo "<h2>Calendar:". $userName . "</h2>";
            }   
            ?>
          
        </section>
        <section id="deskuserinput">
          <?php
            if(isset($_COOKIE["user_id"])) {
              echo   
              "<div class='week'>
                <div><span>Mon</span></div><div><span>Tue</span></div><div><span>Wed</span></div><div><span>Thu</span></div><div><span>Fri</span></div>         
                  ". loopThroughWeeks(4,$conn)."      
              </div>";

            }
            
          ?>
        </section>
      </section>
    </div>
  </body>
  <!-- <script src="moment.min.js"></script> -->
  <script src="javascript/app.js"></script>
</html>  