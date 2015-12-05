#!/usr/bin/env node

/* Before running:
        npm install twilio
*/

var exec = require('child_process').exec;

var me = 'my_username';

exec("who -q", function(error, stdout, stderr) {

    // Exit if no sessions with my username are found
    if(stdout.indexOf(me) == -1)
        process.exit(1);

    var TWILIO_ACCOUNT_SID = process.env['TWILIO_ACCOUNT_SID'];
    var TWILIO_AUTH_TOKEN  = process.env['TWILIO_AUTH_TOKEN'];

    // Phone numbers
    var MY_NUMBER = '+xxx';
    var HER_NUMBER = '+xxx';

    // Reasons
    var reasons = [
        'Working hard',
        'Gotta ship this feature',
        'Someone fucked the system again'
    ];

    // Generate BS message
    var reason = reasons[Math.floor(Math.random() * reasons.length)];
    var textMessage = 'Late at work. ' + reason;

    var client = require('twilio')(TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN);

    // Shoot text
    client.messages.create({
        body: textMessage,
        to: HER_NUMBER,
        from: MY_NUMBER
    }, function(error, message) {
        if(error)
            console.log('Failed to send SMS: ' + error.message);
        else {
            var currentdate = new Date();

            console.log('Message sent at: '+ (currentdate.getMonth() + 1) + '/'
                + currentdate.getDate()  + '/'
                + currentdate.getFullYear() + ' '
                + currentdate.getHours() + ':'
                + currentdate.getMinutes() + ':'
                + currentdate.getSeconds() + '| Excuse: ' + reason);
        }
    });
});
