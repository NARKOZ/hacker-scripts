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
my $MY_NUMBER  = '+xxx';
my $HER_NUMBER = '+xxx';

my $REASONS = [
  'Working hard',
  'Gotta ship this feature',
  'Someone fucked the system again',
];

my $rand = int(rand(scalar(@$REASONS)));

my $random_reason = $REASONS->[$rand];
my $message       = "Late at work. ${random_reason}";

# Send a text message
my $twilio = SMS::Send->new('Twilio',
  _accountsid => $ENV{TWILIO_ACCOUNT_SID},
  _authtoken  => $ENV{TWILIO_AUTH_TOKEN},
  _from       => $MY_NUMBER,
);
 
my $sent = $sender->send_sms(
  text => $message, to => $HER_NUMBER
);
 
# Log errors
die "Failed to send SMS" if not $sent;

