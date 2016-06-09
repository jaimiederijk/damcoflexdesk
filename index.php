<?php
  $servername = "localhost";
  $username = "root";
  $password = "damcosecret";
  $dbname = "damco";

  $currentDate = date("d-m-Y");
  $currentShortDate = date("Ymd");
   
  $searchArray = array("vacation","home","thuis");

  $numberOfPeople = 0;
  $fixexdeskNotPresent = 0;

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 

  $sql = "SELECT * FROM deskusers";
  $result = $conn->query($sql);

  $numberOfPeople = $result->num_rows;

  if ($numberOfPeople > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) { //users
        if ($row["defaultpresent"]==1) { // desk user is normaly present
          $positves = 0 ;

          $posPerCal = searchOneUserCalendar($row, $conn,$currentShortDate,$searchArray);
          if ($posPerCal>0) {
            $positves+=1; //add 1 to positive per user per cal
          }

          if ($positves>0 && $row["fixed"] == 0) { //user cal contains positives so min one to the number of people comming
            $numberOfPeople-=1;
          } else if ($positves>0 && $row["fixed"] == 1) {
            $fixexdeskNotPresent +=1;
          }
        } else { //deskuser is not normaly present
          $numberOfPeople-=1;


        }
          
      }
  } else {
      echo "no desk user exist yet";

  }

  function searchOneUserCalendar($row, $conn , $currentShortDate , $searchArray) {
      $sql2 = "SELECT url FROM `calendars` WHERE deskuser_id = ".$row["deskuser_id"] ." ";//get urls that belong to this user
      $result2 = $conn->query($sql2);
      if ($result2->num_rows > 0) { //loop through arrays
        // output data of each row
        while($row2 = $result2->fetch_assoc()) {
          $calenderJson= @file_get_contents($row2["url"]);
          if ($calenderJson===FALSE) {
            $calenderJson= file_get_contents("calbackup.ics");
          }

          $datePositions = mb_stripos_all($calenderJson, "DTSTART:".$currentShortDate); // find all position with the date

          $posPerCal = searchCalendar($datePositions,$calenderJson,$searchArray); // return number of positves in one calendar
          return $posPerCal;
        } 
      }
  }
  

  function mb_stripos_all($haystack, $needle) {// find all occurrences(case-insensitive) substring in a string. 
    //echo $haystack;
    //echo $needle;
    $s = 0;
    $i = 0;
   
    while(is_integer($i)) {
   
      $i = mb_stripos($haystack, $needle, $s);
   
      if(is_integer($i)) {
        $aStrPos[] = $i;
        $s = $i + mb_strlen($needle);
      }
    }
   
    if(isset($aStrPos)) {
      
      return $aStrPos;

    } else {
      return false;
    }
  }

  function searchCalendar($datePositions,$calenderJson,$searchArray) {
   
    $arrlength = count($datePositions);
    $trueResult = 0;

    for($x=0; $x < $arrlength; $x++) {
      $subString = substr($calenderJson,$datePositions[$x],300) ;


      $searchLength = count($searchArray);
      for ($i=0; $i < $searchLength; $i++) { 
        if (stristr($subString,$searchArray[$i])) {
          // echo $subString;
          // echo "<br>";
          // echo "<br>";
          $trueResult+=1;
        }
      }
      // echo $subString; 
      // echo "<br>";

    }
    return $trueResult;
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
  $numberOfDesk += $fixexdeskNotPresent;
        
  require 'php/class.iCalReader.php';

  //$calenderJson2= @file_get_contents("https://calendar.google.com/calendar/ical/44ird5e4aofb9n5hjqb37ndgdo%40group.calendar.google.com/private-65a9ed8baeca92f0f21a491b24b0f2a5/basic.ics");

  $ical   = new ICal("https://calendar.google.com/calendar/ical/44ird5e4aofb9n5hjqb37ndgdo%40group.calendar.google.com/private-65a9ed8baeca92f0f21a491b24b0f2a5/basic.ics");
  $events = $ical->events();
  $date = $events[0]['DTSTART'];
  echo 'The ical date: ';
  echo $date;
  echo "<br />\n";
  echo 'The Unix timestamp: ';
  echo $ical->iCalDateToUnixTimestamp($date);
  echo "<br />\n";
  echo 'The number of events: ';
  echo $ical->event_count;
  echo "<br />\n";
  echo 'The number of todos: ';
  echo $ical->todo_count;
  echo "<br />\n";
  echo '<hr/><hr/>';
  foreach ($events as $event) {
      echo 'SUMMARY: ' . @$event['SUMMARY'] . "<br />\n";
      echo 'DTSTART: ' . $event['DTSTART'] . ' - UNIX-Time: ' . $ical->iCalDateToUnixTimestamp($event['DTSTART']) . "<br />\n";
      echo 'DTEND: ' . $event['DTEND'] . "<br />\n";
      echo 'DTSTAMP: ' . $event['DTSTAMP'] . "<br />\n";
      echo 'UID: ' . @$event['UID'] . "<br />\n";
      echo 'CREATED: ' . @$event['CREATED'] . "<br />\n";
      echo 'LAST-MODIFIED: ' . @$event['LAST-MODIFIED'] . "<br />\n";
      echo 'DESCRIPTION: ' . @$event['DESCRIPTION'] . "<br />\n";
      echo 'LOCATION: ' . @$event['LOCATION'] . "<br />\n";
      echo 'SEQUENCE: ' . @$event['SEQUENCE'] . "<br />\n";
      echo 'STATUS: ' . @$event['STATUS'] . "<br />\n";
      echo 'TRANSP: ' . @$event['TRANSP'] . "<br />\n";
      echo 'ORGANIZER: ' . @$event['ORGANIZER'] . "<br />\n";
      echo 'ATTENDEE(S): ' . @$event['ATTENDEE'] . "<br />\n";
      echo '<hr/>';
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
        <?php echo "<span> $currentDate </span>" ; ?>
      </section>
      <section class="deskvsemployee" >
        
        <div id="desk"><?php echo "<span>$numberOfDesk - </span>"; ?><img src="images/desk.svg"></div>
        <div id="employee"><?php echo "<span>$numberOfPeople - </span>"; ?><img src="images/deskperson.svg"></div>

        
      </section>
     
    </div>
  </body>
  <!-- <script src="moment.min.js"></script> -->
  <script src="javascript/app.js"></script>
</html>  