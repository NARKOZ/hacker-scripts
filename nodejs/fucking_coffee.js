/* Before running:
        npm install telnet-client
*/

var exec = require('child_process').exec;
var telnet = require('telnet-client')

var me = 'my_username'

exec("who", function(error, stdout, stderr) {

    // Exit if no sessions with my username are found
    if(stdout.indexOf(me) == -1)
        process.exit(/*1*/)

    var coffee_machine_ip = '10.10.42.42'
    var password = '1234'
    var cmd = 'Password: ' + password
    var con = new telnet()

    con.on('ready', function(prompt) {
        con.exec(cmd, function(error, res) {

            // Brew Coffee!
            con.exec('sys brew', function(error, res) {

                // Wait for 24s
                setTimeout(function() {

                    // Pour Coffee!
                    con.exec('sys pour', function(error, res) {
                        con.end()
                    })
                }, 24000);
            })
        })
    })

    con.connect({host: coffee_machine_ip})
});
