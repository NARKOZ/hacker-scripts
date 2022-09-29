#!/usr/bin/env perl6

use v6;

use SMS::Send; #just a fake module

my %conf =
    phone_numbers => {
        my_number => '+15005550006',
        boss_number => '+xxx',
    },
    reasons => [
        'Locked out',
        'Pipes broke',
        'Food poisoning',
        'Not feeling well',
    ],
;

exit if DateTime.now.day-of-week >= 6;

my @sessions = grep { /^ <{ $*USER.Str }> <.ws>/ }, run( 'who', :out ).out.lines;

exit if @sessions.elems > 0;

my $sender = SMS::Send.new(
    :twilio,
    :account-id( %*ENV<TWILIO_ACCOUNT_SID> ),
    :auth-token( $*ENV<TWILIO_AUTH_TOKEN> ),
    :from( %conf<phone_numbers><my_number> ),
);

my $sms-text = 'Gonna work from home.' ~ %conf<reasons>.pick;

my $sent = $sender.send-sms(
    :text( $sms-text ),
    :to( %conf<phone_numbers><boss_number> ),
);

say $sent ?? 'Sent message.' !! 'Message failed.';
