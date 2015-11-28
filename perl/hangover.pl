#!/usr/bin/perl

use strict;
use warnings;

use DateTime;
use SMS::Send;
use YAML;

# Config
my $conf = Load( <<'...' );
---
phone_numbers:
  my_number: +15005550006
  boss_number: +xxx
reasons:
  - Locked out
  - Pipes broke
  - Food poisoning
  - Not feeling well
...

my $date = DateTime->now;

# Skip on weekends
if ( $date->day_of_week >= 6 ) {
    exit;
}

# Exit early if no sessions with my username are found
open( my $cmd_who, '-|', 'who' ) || die "Cannot pipe who command ". $!;

my @sessions = grep {
    m/$ENV{'USER'}/
} <$cmd_who>;

close $cmd_who;

exit if ( scalar( @sessions ) == 0 );

# Load Twilio API config
open( my $env, '<', '../.env' ) || die "Cannot find .env file in project root.";
LINE: while ( my $line = <$env> ) {
    next LINE unless ( $line =~ m/^(TWILIO[^=]+)=(.*)(?:[\n\r]*)/ );
    $conf->{'env'}->{ $1 } = $2;
}

close $env;

# Randomize excuse
my $reason_number = int( rand( scalar( @{ $conf->{'reasons'} } ) ) );
my $sms_text = "Gonna work from home. ". $conf->{'reasons'}[ $reason_number ];

# Create an object. There are three required values:
my $sender = SMS::Send->new('Twilio',
    _accountsid => $conf->{'env'}->{'TWILIO_ACCOUNT_SID'},
    _authtoken  => $conf->{'env'}->{'TWILIO_AUTH_TOKEN'},
    _from       => $conf->{'phone_numbers'}->{'my_number'},
);

# Send a message to me
my $sent = $sender->send_sms(
    text => $sms_text,
    to   => $conf->{'phone_numbers'}->{'boss_number'},
);

# Did it send?
if ( $sent ) {
    print "Sent message.\n";
} else {
    print "Message failed.\n";
}

