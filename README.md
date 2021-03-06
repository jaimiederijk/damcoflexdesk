# Flex desks occupancy app

## The app
[Live version](http://jaimiederijk.nl/damco/)

The app is a tool that can help employees determine if they want to work from home or go to the office based on the availability of a working space. The app shows the number of desks available based on the number of people that are expected to go to the office. 


The app takes it's data from the users callendars. The callendars are gathered on the admin page and conected to the users. Every half hour a cronjob is run that reads these callendars and updates the app.



## Main functions

- Gather calendars from google and Outlook that are associated with the user.
- Parse these calendars and find any events that match the search terms. 
- Store the dates of the positive matches.
- Use the number of positive matches per date as a indication for the number of people that are not going to the office on that day.
- If the user with a positive match on that date has a fixed desk then increase the number of available desk.

- Custom calendar within the app to quickly change user status.

## Instalation
I used [Xampp](https://www.apachefriends.org/index.html) to develop this website.
It uses PHP and MySQL.

- Clone or download this repo
- Place the clone in the htdocs folder in your Xampp installation
- In MySQL Create a database named 'damco' and import this [SQL file](damco (2).sql)
- Change the connection.php to you own settings
- Go to your Xampp localhost and here you will find the app.

## App structure
```
	index.php 							//landing page. only page that the user sees
	settings.php 						//setting for the admin adding users callendars and searchterms
		/php 							//php folder
			class.iCalReader.php 		//library that helps parsing ics callendars after my own failed.
			connection.php 				// unified place for data needed to conect to the DB
			cronjob.php 				// file that is called for the cronjob. Can set the number of days to parse
			parsecallendar.php 			// Extracting the needed data from the callendar data 
		/javascript
			app.js 						// the only js file
		/css
			app.css 					// css for the app index page
			settings.css 				// css for the settings
```


## Minor Everything web applied
Here I will explain how I applied what we have learned.

### Web App From Scratch
- Asynchronous request in the javascript
- Object Literal Pattern used in the javascript

### CSS To The Rescue
- Used Mobile First Design approach
- Used semantic html.
- Works in older browsers

### Browser technologies
- Basic functionality works in all cases (server side rendering via php)
- Enhanced with javascript and css
- Keyboard navigatable

## Asynchronous request

I intercept all submits and send them to the procesForm function were the default behavior that results in a page refresh is stopped. A post request is created that sends the data from the form to the php. There the database is updated with the new value and in the javascript the changes in values are simulated until the page is refreshed. The submit listener is a example of feature detection.
```
		addSubmitListeners : function () {
			var formsLength = htmlElements.forms.length;
			for (var i = 0; i < formsLength; i++) {
				if (htmlElements.forms[i].addEventListener) {
					htmlElements.forms[i].addEventListener("submit",handleForms.processForm);
				} else if (htmlElements.forms[i].attachEvent) {
					htmlElements.forms[i].attachEvent("submit",handleForms.processForm);
				}
			};
		},
		processForm : function (e) {
			e.preventDefault();
			
			var that=this;
			var req = new XMLHttpRequest();
			var FD  = new FormData(this);

			req.open("POST", "index.php");
			req.onreadystatechange = function (Evt) {
				if (req.readyState == 4) {
					if(req.status == 200) {
					  	handleForms.checkWichForm(that);
						
					} else
					  alert("Error loading page\n");
				}
			};
			req.send(FD);

			return false;
		},
```
## Basic functionality
All of the variable content in this app is created by php. Functions like createWeekdays generate the important html. CreateWeekdays creates the html for the 5 work days in a week. The function takes a unix timstamp and a sql connection as arguments. The timestamp functions as the starting point of the week. The function starts a loop of 5 for the five days in a week. In the loop it determines if the day that it is currently creating has any characteristics that require a classname. The getOccupencyResults functions returns the data from the database and the customcallendar. This data forms the basis for the app. From this data I can determine the amount of freedesk available which get stored in the $freedesk varaible. The form contains a input hidden to send along extra data to the receiving end.   

```
function createWeekdays($sundayTimeStamp,$conn) {
    $timestamp = $sundayTimeStamp;
      //$days = array();strtotime('previous Sunday');
    $action =  htmlspecialchars($_SERVER["PHP_SELF"]);
    $result = "";
    $numberOfDesk=0;
    $numberOfPeople=0;

    for ($i = 0; $i < 5; $i++) {
      $className="";
      $img="";
      $timestamp = strtotime('+1 day', $timestamp);
      if ($timestamp==strtotime('today')) {
        $className="today";
      } else if ($timestamp<strtotime('today')) {
        $className="past";
      }
      if ($i == 0) {
        $className=$className." monday";
      }
      if(checkCustomCalendar($conn,$timestamp)) {
        $img="deskpersonhome.svg";
        $className=$className." emptydesk";
      } else {
        $img="deskperson.svg";
      }
      
      $resultDeskOccupency = getOccupencyResults($conn,date("Ymd",$timestamp));
      $numberOfPeople=$resultDeskOccupency[1];
      $numberOfDesk=$resultDeskOccupency[0];
      $freeDesk=$numberOfDesk-$numberOfPeople;
      $divId = "d".date('d-m',$timestamp);

      $result = $result . "<div id='$divId' class='".$className."'>            
        <form method='post' action=".$action.">
          
          <input type='hidden' name='date' value=".$timestamp.">
          <button type='submit' name='changeGoingOffice' value='change'>
            <div>
              <h3>".date('D',$timestamp)."</h3>
              <span data-date=".date('d-m',$timestamp).">".date('d',$timestamp)."</span>
            </div>
            <div class='deskvsemployee'>
              <p class='desk'>available desk: <span>$freeDesk</span></p>
              
            </div>
          </button>
        </form>
        
      </div>";
    }
    return $result;
  }
```

## SQL database
First time that I have set up a SQL database. It seems to be very fast in returning results.
![sql](images/readme/sql.png)

## Gather and parsing the calendars
Most calendar apps allow the user to share their calendar. I'm using this functionality to get acces and get the data that I need. I store the calendar urls in a sql database. Using php I loop through all the users urls and see if there are any positive matches.
The function below is the start of the search through the calendar.
This php function takes 4 arguments:

- $row = the sql info from one user
- $conn = the sql connection
- $currentShortDate = date that we are searching
- $searchArray = the search terms


The function  loops through all the urls that belong to the user. On each Url it first finds the events on a date and stores them in a array. The searchCalendar function then searches through all the found events for the search terms. If a postive has been found it return a 1. It inserts the date in the customcal Database and the function stops and return a 1. The 1 is subtracted from the number of people comming or if a fixed desk gets added to the number of desk 

```
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

      if ($posPerCal>0) {//if terms have been found
      	insertIntoCustomCal($conn,$row["deskuser_id"],0,$currentShortDate);
        return $posPerCal;
      }
      
    } 
  }
}
```   

$numberOfPeople is the total number of user so the code runs when users exist. From here the above function is called for each user. 
```
if ($numberOfPeople > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) { //users
    if ($row["defaultpresent"]==1) { // desk user is normaly present
      $positves = 0 ;

      $posPerCal = searchOneUserCalendar($row, $conn,$currentShortDate,$searchArray);
      if ($posPerCal>0) {
        $positves+=1; //add 1 to positive per user per cal
      }
      if ($row["fixed"] == 1) {
        $numberOfPeople-=1;
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
}
```

### Cronjob

I use a cronjob to run the parse calender functionality every 30 minutes. In this way the long time it takes to excute the code is not a problem for the user.

## Showing the result

I retrieve the data from a sql database and generate the content based on this data using php.
below is a example of how I retrieve the result for occupency.
```
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
```

## Custom calendar
In my custom calendar I only want to show the work days as the weekend is useless. I start by getting the date from the last sunday and from there loop through a specified number of weeks where it generates the html for every workday with date and results.

```
  function loopThroughWeeks ($weeks,$conn) {
    $currentWeek = strtotime('previous Sunday');
    $today = strtotime('today');
    $result = "";
    //$week = 0;
    for ($i=0; $i < $weeks; $i++) {
      $result = $result . "<div class='weeknumber'><span> Week: ".date('W',strtotime('+'.$i.' week', $today))."</span></div>";
      $result = $result .  createWeekdays(strtotime('+'.$i.' week', $currentWeek),$conn);
    }
    return $result;
  }

  function createWeekdays($sundayTimeStamp,$conn) {
    $timestamp = $sundayTimeStamp;
      //$days = array();strtotime('previous Sunday');
    $action =  htmlspecialchars($_SERVER["PHP_SELF"]);
    $result = "";
    $numberOfDesk=0;
    $numberOfPeople=0;

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
      
      $resultDeskOccupency = getOccupencyResults($conn,date("Ymd",$timestamp));
      $numberOfPeople=$resultDeskOccupency[1];
      $numberOfDesk=$resultDeskOccupency[0];
      $divId = "d".date('d-m',$timestamp);

      $result = $result . "<div id='$divId' class='".$className."'>            
        <form method='post' action=".$action.">
          <span>".date('d-m',$timestamp)."</span>
          
          <input type='hidden' name='date' value=".$timestamp.">
          <button type='submit' name='changeGoingOffice' value='change'><img src='images/".$img."'></button>
        </form>
        <div class='deskvsemployee'>
          <div class='desk'><span>$numberOfDesk - </span><img src='images/desk.svg'></div>
          <div class='employee'><p><span>$numberOfPeople</span> - </p><img src='images/deskperson.svg'></div>
        </div>
      </div>";
    }
    return $result;
  }
```