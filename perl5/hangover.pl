#!/usr/bin/env perl
use 5.10.0;
use strict;
use warnings;

use DateTime;
use SMS::Send::Twilio;

# Skip on weekends
my $day_of_week = DateTime->now->day_of_week();
exit if $day_of_week == 6 || $day_of_week == 7;


# Exit early if no sessions with my username are found
exit unless `who | grep $ENV{USER}`;

# Phone numbers
my $MY_NUMBER      = '+xxx';
my $NUMBER_OF_BOSS = '+xxx';

my $EXCUSES = [
  'Locked out',
  'Pipes broke',
  'Food poisoning',
  'Not feeling well',
];

my $rand = int(rand(scalar(@$EXCUSES)));

my $random_excuse = $EXCUSES->[$rand];
my $message       = "Gonna work from home. ${random_excuse}";

# Send a text message
my $twilio = SMS::Send->new('Twilio',
  _accountsid => $ENV{TWILIO_ACCOUNT_SID},
  _authtoken  => $ENV{TWILIO_AUTH_TOKEN},
  _from       => $MY_NUMBER,
);
 
my $sent = $sender->send_sms(
  text => $message, to => $NUMBER_OF_BOSS
);
 
# Log errors
die "Failed to send SMS" if not $sent;

