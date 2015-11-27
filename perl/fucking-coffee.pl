#!/usr/bin/perl

use strict;
use warnings;

use DateTime;
use YAML;
use Net::Telnet;

# Config
my $conf = Load( <<'...' );
---
coffee_machine_ip: 10.10.42.42
password: 1234
password_prompt: Password:
delay_before_brew: 17
delay: 24
...

# Skip on weekends
my $date = DateTime->now;
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

sleep $conf->{'delay_before_brew'};

my $con = Net::Telnet->new(
    'Host' => $conf->{'coffee_machine_ip'},
);

$con->watifor( $conf->{'password_prompt'} );
$con->cmd( $conf->{'password'} );
$con->cmd( 'sys brew' );
sleep $conf->{'delay'};
$con->cmd( 'sys pour' );
$con->close;

