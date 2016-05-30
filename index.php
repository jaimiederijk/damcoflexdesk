<?php

  $calenderJson= file_get_contents("https://calendar.google.com/calendar/ical/jaimiedegiantrijk%40gmail.com/private-2fcab5e98afb38061fdae47a15effece/basic.ics");  
  $currentShortDate = date("Ymd");

  function mb_stripos_all($haystack, $needle) {
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

  $searchArray = array("vacation","home","thuis");
  $datePositions = mb_stripos_all($calenderJson, "DTSTART:".$currentShortDate);
  
  $currentDate = date("d-m-Y");
  $numberOfDesk = 40; 
  $numberOfPeople = searchCalendar($datePositions,$calenderJson,$searchArray);


// foreach($datePositions as $x => $x_value) {
//                 echo "Key=" . $x . ", Value=" . $x_value;
//                 echo "<br>";
//             }
       
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
        <nav><a href="/settings">Settings<img src="images/gear_icon.svg"></a></nav>
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