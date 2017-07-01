#!/usr/bin/env php
<?php

require 'vendor/autoload.php';
(new Dotenv\Dotenv(__DIR__))->load();

(strpos(exec('who'), getenv('USER')) !== false) or exit('no session');

// Phone numbers
$my_number = '+xxx';
$her_number = '+xxx';

$reasons = [
	'Working hard',
	'Gotta ship this feature',
	'Someone fucked up the system again'
];

$rand = rand(0,count($reasons)-1);
$random_reason = $reasons[$rand];

$message = 'Late at work. '.$random_reason;

// Send a text message
$twilio = new Services_Twilio(getenv('TWILIO_ACCOUNT_SID'), getenv('TWILIO_AUTH_TOKEN'));
$twilio->account->messages->sendMessage(
        $my_number,
        $her_number,
        $message
);

echo 'Message sent at: #'.date('Y-m-d').' | Reason: '.$random_reason;
