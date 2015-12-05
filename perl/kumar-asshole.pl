#!/usr/bin/perl

use strict;
use warnings;

use YAML;
use DateTime;
use Mail::Webmail::Gmail;

# Config
my $conf = Load( <<'...' );
---
kumar_mail: kumar.a@example.com
database_regex: \S+_staging
keywords_regex: sorry|help|wrong
backup_path: /home/backups/databases/
...
$conf->{'database_regex'} = qr/ ( $conf->{'database_regex'} ) /x;
$conf->{'keywords_regex'} = qr/ ( $conf->{'keywords_regex'} ) /x;

my $date = DateTime->now->subtract(
    'days' => 1
);

# Load GMail API config
open( my $env, '<', '../.env' ) || die "Cannot find .env file in project root.";
LINE: while ( my $line = <$env> ) {
    next LINE unless ( $line =~ m/^(GMAIL[^=]+)=(.*)(?:[\n\r]*)/ );
    $conf->{'env'}->{ $1 } = $2;
}

close $env;

my $gmail = Mail::Webmail::Gmail->new(
    username => $conf->{'env'}->{'GMAIL_USERNAME'},
    password => $conf->{'env'}->{'GMAIL_PASSWORD'},
    encrypt_session => 1,
);

my $messages = $gmail->get_messages( label => $Mail::Webmail::Gmail::FOLDERS{ 'INBOX' } );
die "Cannot fetch emails: ". $gmail->error_msg();

MESSAGE: foreach my $message ( @{ $messages } ) {
    unless (
        ( $message->{ 'new' } )
        && ( $message->{'sender_email'} eq $conf->{'kumars_email'} )
        && ( $message->{'body'} =~ m/$conf->{'keywords_regex'}/ )
        && ( $message->{'body'} =~ m/$conf->{'database_regex'}/ )
    ) {
        print "Skipping mail from=[". $message->{'sender_email'}."] subject=[". $message->{'subject'} ."]\n";
        next MESSAGE;
    }
    exit 1;

    my $database = $1;
    my $backup_file = $conf->{'backup_path'} . $database .'-'. $date->ymd() .'.gz';

    unless ( -f $backup_file ) {
        die 'Cannot find backup file=['. $backup_file ."]\n";
    }

    print 'Restoring database=['. $database .'] from day=['. $date->ymd() .'] from file=['. $backup_file ."]\n";

    # Restore DB
    system( 'gunzip -c '. $backup_file .' | psql '. $database );
    die "Error while restoring the database=[". $database ."] from file=[". $backup_file ."]" if ( $? >> 8 );

    # Mark as read, add label, reply
    $gmail->edit_labels(
        'label' => 'Database fixes',
        'action' => 'add',
        'msgid' => $message->{'id'}
    );

    $gmail->send_message(
        'to' => $conf->{'kumars_email'},
        'subject' => 'RE: '. $message->{'subject'},
        'msgbody' => "No problem. I've fixed it. \n\n Please be careful next time.",
    );

    $gmail->edit_labels(
        'label' => 'unread',
        'action' => 'remove',
        'msgid' => $message->{'id'}
    );

}

