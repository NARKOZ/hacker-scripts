#!/usr/bin/env node

/* Before running:
        npm install nodemailer
        npm install imap

I realize this is long. IMAP can only fetch emails and nodemailer can only
send. Could try implementing with Gmail Node API later.
*/

var GMAIL_USERNAME = process.env['GMAIL_USERNAME'];
var GMAIL_PASSWORD = process.env['GMAIL_PASSWORD'];

var KUMAR_EMAIL = 'kumar.asshole@example.com';
var EMAIL = 'No worries mate, be careful next time';

// Scan for unread email from Kumar
var Imap = require('imap');
var imap = new Imap({
    user: GMAIL_USERNAME,
    password: GMAIL_PASSWORD,
    host: 'imap.gmail.com',
    port: 993,
    tls: true,
    tlsOptions: { rejectUnauthorized: false }
});

function openInbox(cb) {
    imap.openBox('INBOX', false, cb);
}

imap.once('ready', function() {
    openInbox(function(err, box) {
        if (err)
            process.exit(1);

        imap.search(['UNSEEN', ['FROM', KUMAR_EMAIL]],
            function(err, results) {

            if (err)
                process.exit(1);

            // RegEx search for keywords; ignore case
            var kumarPattern = new RegExp(/sorry|help|wrong/i);

            // IMAP dumps all headers, so need to parse and get email body
            var MailParser = require("mailparser").MailParser;

            var f = imap.fetch(results, {bodies: ''});
            f.on('message', function(msg, seqno) {
                msg.on('body', function(stream, info) {
                    var kumarEmail = "";

                    stream.on('data', function(chunk) {
                        kumarEmail += chunk.toString('utf8');
                    });

                    stream.once('end', function() {
                        var mailparser = new MailParser();
                        mailparser.on("end", function(mail_object){

                            // If the RegEx matches
                            if(mail_object.text.match(kumarPattern)) {
                                // Shoot email to Kumar!
                                var nodemailer = require('nodemailer');

                                // create reusable transporter object using SMTP transport
                                var transporter = nodemailer.createTransport({
                                    service: 'Gmail',
                                    auth: {
                                        user: GMAIL_USERNAME,
                                        pass: GMAIL_PASSWORD
                                    }
                                });

                                // setup e-mail data
                                var mailOptions = {
                                    from: GMAIL_USERNAME,
                                    to: KUMAR_EMAIL,
                                    subject: 'Database Fixes',
                                    text: EMAIL
                                };

                                // send mail with defined transport object
                                transporter.sendMail(mailOptions, function(error, info) {
                                    if(error)
                                        process.exit(1)
                                });
                            }
                        });

                        mailparser.write(kumarEmail);
                        mailparser.end();
                    });
                });

                msg.once('end', function() {
                    // Fetched all unread from kumar
                });
            });

            f.once('error', function(err) {
                process.exit(1);
            });

            f.once('end', function() {
                imap.end();
            });
        });
    });
});

imap.connect();
