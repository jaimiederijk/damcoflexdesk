<?php
	
	// create array with the dates for the next 30 days
	function createNextMonthArray() { 
		$date = date("Ymd");
		$result = array($date);


		for ($i=0; $i < 5; $i++) { 
			$timestamp = strtotime($date);
			$date =date("Ymd", strtotime('+1 day', $timestamp));
			array_push($result,$date);
		}
		return $result;
	}

	

	function loopDays () {
		require_once 'parsecalendar.php';
		$monthArray = createNextMonthArray();

		$arrlength = count($monthArray);

		for($x = 0; $x < $arrlength; $x++) {
		    
		    processUserCal($monthArray[$x]);
		    
		}
	}
?>