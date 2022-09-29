#!/usr/bin/env perl6

use v6;

use Gmail; #just a fake module

my %conf =
    kumar-email => 'kumar.a@example.com',
    database_regex => rx /<-space>+ '_staging'/,
    keywords_regex => rx:i /< sorry help wrong >/,
    backup_path => '/home/backups/databases/',
;

my $gmail = Gmail.new(
    :account( %*ENV<GMAIL_USERNAME> ),
    :password( %*ENV<GMAIL_PASSWORD> );
);

for $gmail.find(:inbox, :unread, :from( %conf<kumar-email> )).lines -> $email {
    if $email.body.raw-source ~~ %conf<keywords_regex> and ( my $db-name = $email.body.raw-source ~~ %conf<database_regex> ) {
        my $yesterday = Date.today.pred;
        my $backup-file = %conf<backup_path> ~ $dbname ~ '-' ~ $yesterday.year ~ $yesterday.month ~ $yesterday.day ~ '.gz';

        die 'ERROR: Backup file not found' if $backup-file.IO !~~ :e;

        shell 'gunzip -c ' ~ $backup-file ~ ' | psql ' ~ $db_name;

        $email.read;
        $email.label( 'Database fixes' );
        $gmail.deliver(
            $gmail.compose(
                :to( %conf<kumar-email> ),
                :subject( 'RE: ' ~ $email.subject ),
                :body( "No problem. I've fixed it. \n\n Please be careful next time." ),
            ),
        );
    }
}
