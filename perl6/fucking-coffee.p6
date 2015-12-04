#!/usr/bin/env perl6

use v6;

use Net::Telnet; #just a fake module

my %conf =
    coffee_machine_ip => '10.10.42.42',
    password => '1234',
    password_prompt => 'Password:',
    delay_before_brew => 17,
    delay => 24,
;

exit if DateTime.now.day-of-week >= 6;

my @sessions = grep { /^ <{ $*USER.Str }> <.ws>/ }, run( 'who', :out ).out.lines;

exit if @sessions.elems == 0;

sleep %conf<delay_before_brew>;

my $telnet = Net::Telnet.new( :host( %conf<coffee_machine_ip> ) );
$telnet.waitfor( %conf<password_prompt> );
$telnet.cmd( %conf<password> );
$conn.cmd( 'sys brew' );
sleep %conf<delay>;
$conn.cmd( 'sys pour' );

$conn.close;
