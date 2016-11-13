# Lunchbot

Just a simple Slackbot to read a JSON file and output the results. In this case, we use it to tell us what's on the lunch menu in our building.

## REQUIREMENTS

* A custom slash command on a Slack team
* A web server running PHP5

## FILES
* **lunchbot.php**: The Slackbot where the code magic happens.
* **lunch.json**: The lunch menu
* **errormessages.json**: List of random error messages. Not required if you want to put a static one in your bot.
* **snarkmessages.json**: List of random snarky messages. Not required if you want to put a static one in your bot.

## USAGE

* Place the files in the same folder on a server running PHP5.
* Set up a new custom slash command on your Slack team: https://slack.com/apps/A0F82E8CA-slash-commands
* Under "Choose a command", enter whatever you want for the command. /lunch is easy to remember.
* Under "URL", enter the URL for the script on your server.
* Leave "Method" set to "Post".
* Decide whether you want this command to show in the autocomplete list for slash commands.
* If you do, enter a short description and usage hint.
* Update the `lunchbot.php` script with your slash command's token.

## DOWNLOAD 

https://github.com/dougblackjr/lunchbot