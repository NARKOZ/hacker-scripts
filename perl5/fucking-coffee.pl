#!/usr/bin/env perl
use 5.10.0;
use strict;
use warnings;

use DateTime;
use Net::Telnet ();

# Skip on weekends
my $day_of_week = DateTime->now->day_of_week();
exit if $day_of_week == 6 || $day_of_week == 7;


# Exit early if no sessions with my username are found
exit unless `who | grep $ENV{USER}`;


#my $coffee_machine_ip = '10.10.42.42';
my $coffee_machine_ip = 'localhost';
my $password = '1234';
my $delay = 24;


my $con = Net::Telnet->new(
    -host => $coffee_machine_ip, -timeout => 30
);
$con->waitfor('/password: $/i');
$con->print($password);
$con->cmd('sys brew');
sleep $delay;
$con->cmd('sys pour');
$con->close;

