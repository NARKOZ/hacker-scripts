#!/usr/bin/env perl6

use v6;

use SMS::Send; #just a fake module

my %conf =
    phone_numbers => {
        my_number => '+15005550006',
        her_number => '+xxx',
    },
    reasons => [
        'Working hard',
        'Gotta ship this feature',
        'Someone fucked the system again',
    ],
;

exit if DateTime.now.day-of-week >= 6;

my @sessions = grep { /^ <{ $*USER.Str }> <.ws>/ }, run( 'who', :out ).out.lines;

exit if @sessions.elems == 0;

my $sender = SMS::Send.new(
    :twilio,
    :account-id( %*ENV<TWILIO_ACCOUNT_SID> ),
    :auth-token( $*ENV<TWILIO_AUTH_TOKEN> ),
    :from( %conf<phone_numbers><my_number> ),
);

my $sms-text = 'Late at work.' ~ %conf<reasons>.pick;

my $sent = $sender.send-sms(
    :text( $sms-text ),
    :to( %conf<phone_numbers><her_number> ),
);

say $sent ?? 'Sent message.' !! 'Message failed.';
