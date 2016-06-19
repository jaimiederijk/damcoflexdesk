<?php

	function processUserCal ($currentShortDate) {
		/**
		*
		* @package  ics-parser
		* @author   Martin Thoma <info@martin-thoma.de>
		* @license  http://www.opensource.org/licenses/mit-license.php MIT License
		* @link     https://github.com/MartinThoma/ics-parser/
		*/
		require 'php/class.iCalReader.php';

		session_start();

		$servername = "localhost";
		$username = "root";
		$password = "damcosecret";
		$dbname = "damco";

		$searchArray = array("vacation","home","thuis");

		$currentShortDate = $currentShortDate;

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

		        $ical   = new ICal($row2["url"]);
		        $events = $ical->events();

		      $datePositions = findEventsWithDate($events, $currentShortDate); // find all events with the date

		      $posPerCal = searchCalendar($datePositions,$events,$searchArray); // return number of positves in one calendar

		      if ($posPerCal>0) {
		        return $posPerCal;
		      }
		      
		    } 
		  }
		}

		function findEventsWithDate($events,$date) {
			$eventsLength=count($events);
			$results = array();

			for ($i=0; $i < $eventsLength; $i++) { 
			  if (mb_stripos($events[$i]['DTSTART'],$date) !== false) {
			    array_push($results, $i);
			  }
			}
			return $results;
		}

		function searchCalendar($datePositions,$events,$searchArray) {

			$arrlength = count($datePositions);
			$trueResult = 0;

			for($x=0; $x < $arrlength; $x++) { //foreach event on this date


			  $searchLength = count($searchArray); //for each searchterm
			  for ($i=0; $i < $searchLength; $i++) { 
			    if (mb_stripos($events[$datePositions[$x]]['SUMMARY'],$searchArray[$i]) !== false) {
			      $trueResult+=1;
			    }
			  }

			}
			return $trueResult;
		}

		function getDeskNumber($conn,$fixexdeskNotPresent) {
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
			return $numberOfDesk;
		}

		$numberOfDesk = getDeskNumber($conn,$fixexdeskNotPresent);

		//end result numberOfPeople and numberOfDesk

		function storeResults ($conn,$numberOfDesk,$numberOfPeople,$date) {
			$people = $numberOfPeople;
			$desk = $numberOfDesk;
			$resultdate = date("Y-m-d",$date);

			$sql = "INSERT INTO occupancy_results (resultdate,desk,people)
			VALUES ('".$resultdate."','".$desk."','".$people."')";

			if ($conn->query($sql) === TRUE) {
			//header("Location: {$_SERVER['PHP_SELF']}");

			} else {
				echo "Error" . $conn->error;
			}
		}

		storeResults ($conn,$numberOfDesk,$numberOfPeople,$currentShortDate);

	}
?>