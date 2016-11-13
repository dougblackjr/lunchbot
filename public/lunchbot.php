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

//MESSAGE RANDOMIZER
function rndmsg($type) {
  if ($type == "error") {
    $message_response = json_decode(file_get_contents('errormessages.json'),true);
    return $message_response[array_rand($message_response)]['message'];
  } elseif ($type == "snark") {
    $message_response = json_decode(file_get_contents('snarkmessages.json'),true);
    return $message_response[array_rand($message_response)]['message'];
  }
}


// SLACK CODE
// # Grab some of the values from the slash command, create vars for post back to Slack
if (isset($_POST['command'])) {
  $command = $_POST['command'];
  $text = $_POST['text'];
  $token = $_POST['token'];

  // # Check the token and make sure the request is from our team 
  if($token != ''){ #replace this with the token from your slash command configuration page
    $msg = "I have died. I have no regrets. (Slack Token issue)";
    die($msg);
    echo $msg;
  }
}


// LUNCH CODE
# Test variable
$text = 'gerbils';

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
    $reply = rndmsg("snark");

  }

} else {

  # Error message
  $reply = rndmsg("error");

}

echo $reply;