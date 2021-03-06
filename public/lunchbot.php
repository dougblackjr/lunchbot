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
// Randomized the error and snarky messages.
// $type can be error, snark, or static.
function rndmsg($type) {

  if ($type == "error") {

    $message_response = json_decode( file_get_contents ( 'json/errormessages.json' ), true);
    
    return $message_response[array_rand($message_response)]['message'];

  } elseif ($type == "snark") {

    $message_response = json_decode( file_get_contents( 'json/snarkmessages.json' ), true);
    
    return $message_response[array_rand($message_response)]['message'];

  } else {

    return "Have you tried asking 'What for lunch today?'?";

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

} else {
  $text = 'what\'s for lunch today?';
}

# Get today's date
$today = date("Y/m/d");
$datetimestamp = new DateTime($today);

# Set clear response_array
$response_array = array();

#lowercase text
if (isset($text)) {$text = strtolower($text);};

# Get menu
$lunch_array = json_decode(file_get_contents('json/lunch.json'),true);

# Check if its set
if (isset($text)) {

  #Check if its today
  if (strpos($text, 'today') || $text == 'today') {

    # Get key of array matching today's date
    $key = array_search($datetimestamp->format("Y/m/d"), array_column($lunch_array, 'date'));
    
    # Check if key is empty. If it is, there is no info for today. Else, it throws out the menu.
    if ( empty($key) ) {

      $reply = "Sorry, I don't have information for today.";      

    } else {

      $reply = "Today, we've got\nCreate: " . $lunch_array[$key]['create'] . "\nSoup: " . $lunch_array[$key]['soup'] . "\nGrill: " . $lunch_array[$key]['grill'] . "\nDeli: " . $lunch_array[$key]['deli'];

      # No line breaks
      // $reply = "Today, we've got " . $lunch_array[$key]['create'] . ", " . $lunch_array[$key]['soup'] . ", " . $lunch_array[$key]['grill'] . ", " . $lunch_array[$key]['deli'];

    }

  } elseif (strpos($text, 'tomorrow') || $text == 'tomorrow') {

    # Add one to today
    $datetimestamp->modify('+1 day');
    
    # Get key of array matching tomorrow's date
    $key = array_search($datetimestamp->format("Y/m/d"), array_column($lunch_array, 'date'));

    # Check if key is empty. If it is, there is no info for tomorrow. Else, it throws out the menu.
    if (empty($key)) {

      $reply = "Sorry, I don't have information for tomorrow.";

    } else {

      $reply = "Tomorrow is: \nCreate: " . $lunch_array[$key]['create'] . "\nSoup: " . $lunch_array[$key]['soup'] . "\nGrill: " . $lunch_array[$key]['grill'] . "\nDeli: " . $lunch_array[$key]['deli'];

      # No line breaks
      // $reply = "Tomorrow, we've got " . $lunch_array[$key]['create'] . ", " . $lunch_array[$key]['soup'] . ", " . $lunch_array[$key]['grill'] . ", " . $lunch_array[$key]['deli'];

    }

  } elseif (strpos($text, 'halal') || $text = 'halal') {

    # Someone will mention the halal cart at our office. This is just fun for us. Try your own context specific messages.
    $reply = "Of course, there is halal.";

  } else {

    # Default snarky message. Change this to type of "static" for one message.
    $reply = rndmsg("snark");

  }

} else {

  # Error message if variable is not set. Change this to type of "static" for one message.
  $reply = rndmsg("error");

}

# Send the reply back to Slack
// echo $reply;

# Send reply back as JSON
$response_array['response_type'] = 'in_channel';
$response_array['text'] = $reply;
ignore_user_abort(true);
set_time_limit(0);

ob_start();
// do initial processing here
echo json_encode($response_array); // send the response
header('Connection: close');
header("Content-Type: application/json");
header('Content-Length: '.ob_get_length());
ob_end_flush();
ob_flush();
flush();