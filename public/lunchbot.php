<?php

/*

REQUIREMENTS

* A custom slash command on a Slack team
* A web server running PHP5 with cURL enabled

USAGE

* Place this script on a server running PHP5 with cURL.
* Set up a new custom slash command on your Slack team: 
  http://my.slack.com/services/new/slash-commands
* Under "Choose a command", enter whatever you want for 
  the command. /lunch is easy to remember.
* Under "URL", enter the URL for the script on your server.
* Leave "Method" set to "Post".
* Decide whether you want this command to show in the 
  autocomplete list for slash commands.
* If you do, enter a short description and usage hint.

*/


// SLACK CODE
// # Grab some of the values from the slash command, create vars for post back to Slack
// $command = $_POST['command'];
// $text = $_POST['text'];
// // $token = $_POST['token'];

// # Check the token and make sure the request is from our team 
// if($token != ''){ #replace this with the token from your slash command configuration page
//   $msg = "I have died. I have no regrets. (Slack Token issue)";
//   die($msg);
//   echo $msg;
// }

# Test variable
$text = 'what\'s for lunch tomorrow?';

# Get today's date
$today = date("Y/m/d");
$datetimestamp = new DateTime($today);

#lowercase text
$text = strtolower($text);

# Get menu
$response_array = json_decode(file_get_contents('lunch.json'),true);

# Check if its set
if (isset($text)) {

  #Check if its today
  if (strpos($text, 'today')) {

    $key = array_search($datetimestamp->format("Y/m/d"), array_column($response_array, 'date'));
    $reply = "Today, we've got...Salad: " . $response_array[$key]['salad'] . " and Grill: " . $response_array[$key]['grill'];

  } elseif (strpos($text, 'tomorrow')) {

    # Add one to today
    $datetimestamp->modify('+1 day');
    $key = array_search($datetimestamp->format("Y/m/d"), array_column($response_array, 'date'));

    $reply = "Tomorrow is: Salad: " . $response_array[$key]['salad'] . " and Grill: " . $response_array[$key]['grill'];

  } elseif (strpos($text, 'halal')) {

    $reply = "Of course, there is halal.";

  } else {

    # Default message
    $reply = "I'm just a robot. I'm not omniscient. Try 'today'.";

  }

} else {

  # Error message
  $reply = "I can responde to 'today', 'tomorrow', or 'halal'. Maybe other things.";

}

echo $reply;