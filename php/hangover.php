#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

(new Dotenv\Dotenv(__DIR__))->load();
(strpos(exec('who'), getenv('USER')) === false) or exit('session found');

$my_number = '+xxx';
$number_of_boss = '+xxx';
$excuse = ['Locked out', 'Pipes broke', 'Food poisoning', 'Not feeling well'];
$excuse = $excuse[array_rand($excuse)];

$twilio = new Services_Twilio(getenv('TWILIO_ACCOUNT_SID'), getenv('TWILIO_AUTH_TOKEN'));
$twilio->account->messages->sendMessage(
	$my_number,
	$number_of_boss,
	"Gonna work from home. {$excuse}"
);

echo "Message sent at: #".date('Y-m-d')." | Excuse: {$excuse}";
