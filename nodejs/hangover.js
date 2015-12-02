#!/usr/bin/env node

/* Before running:
        npm install twilio
*/

var exec = require('child_process').exec;

var me = 'my_username';

exec("who -q", function(error, stdout, stderr) {

    // Exit if sessions with my username are found
    if(stdout.indexOf(me) > -1)
        process.exit(1);

    var TWILIO_ACCOUNT_SID = process.env['TWILIO_ACCOUNT_SID'];
    var TWILIO_AUTH_TOKEN  = process.env['TWILIO_AUTH_TOKEN'];

    // Phone numbers
    var MY_NUMBER = '+xxx';
    var BOSS_NUMBER = '+xxx';

    // Excuses
    var excuses = [
        'Locked out',
        'Pipes broke',
        'Food poisoning',
        'Not feeling well'
    ];

    // Generate BS message
    var excuse = excuses[Math.floor(Math.random() * excuses.length)];
    var textMessage = 'Gonna work from home. ' + excuse;

    var client = require('twilio')(TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN);

    // Shoot text
    client.messages.create({
        body: textMessage,
        to: BOSS_NUMBER,
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
                + currentdate.getSeconds() + '| Excuse: ' + excuse);
        }
    });
});
