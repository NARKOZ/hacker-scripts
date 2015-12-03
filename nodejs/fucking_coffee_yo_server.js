#!/usr/bin/env node

/* Before running:
        Setup Yo Callback URL and Yo username for coffee machine:
        http://docs.justyo.co/docs/receiving-a-yo-with-the-api
*/

var exec = require('child_process').exec;
var telnet = require('telnet-client');

var ME = 'my_username';
var AUTHORIZED_YO_NAMES = [ME];
var COFFEE_MACHINE_YO_NAME = 'coffeemachine';

// These should be same as what you set up in the Yo API
var CALLBACK_URL = 'http://xxx.com';
var CALLBACK_ENDPOINT = '/coffeemachine';

var PORT = '3000';

exec("who -q", function(error, stdout, stderr) {

    var express = require('express');
    var coffeeApp = express();

    // Exit if no sessions with my username are found
    if(stdout.indexOf(ME) == -1)
        process.exit(1);

    // Got a Yo!
    coffeeApp.get(CALLBACK_ENDPOINT, function (req, res) {

        if(req.query.username === undefined) {
            // Not a Yo, don't make coffee.
            res.sendStatus(401);
        }
        else if(AUTHORIZED_YO_NAMES.indexOf(req.query.username) == -1) {
            // If authorized users didn't Yo, don't make coffee.
            res.sendStatus(401);

            console.log(req.query.username + ' YO\'d.')
        }
        else {
            res.sendStatus(200);

            var coffee_machine_ip = 'xxx.xxx.xxx.xxx';
            var password = 'xxxx';
            var con = new telnet();

            con.on('ready', function(prompt) {
                con.exec('Password: ' + password, function(error, res) {

                    // Brew Coffee!
                    con.exec('sys brew', function(error, res) {

                        // Wait for 24s
                        setTimeout(function() {

                            // Pour Coffee!
                            con.exec('sys pour', function(error, res) {
                                con.end();
                            });
                        }, 24000);
                    });
                });
            });

            con.connect({host: coffee_machine_ip});
        }
    });

    // Not Callback endpoint
    coffeeApp.get('/*', function (req, res) {
        res.sendStatus(404);
    });

    var coffeeServer = coffeeApp.listen(PORT, CALLBACK_URL, function() {
        console.log('Coffee Server listening at %s:%s',
            CALLBACK_URL, PORT);
        console.log('\nYo Callback URL: %s:%s/%s', 
            CALLBACK_URL, PORT, CALLBACK_ENDPOINT);
    });
});
