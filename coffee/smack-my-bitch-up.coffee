sys = require 'util'
exec = require('child_process').exec

usr = process.env.USER

reasons = [
    'Working hard',
    'Gotta ship this feature',
    'Someone fucked the system again'
]

rand = (ary) ->
    i = Math.floor Math.random() * ary.length
    ary[i]

child = exec 'who -q', (e, stdin) ->
    if stdin.match new RegExp usr, 'i'
        excuse = "Late at work. #{rand reasons}"
        require('twilio')().sendSms
            to: '+xxx' #her_num
            from: '+xxx' #my_num
            body: excuse
            , (err) -> console.log if err then err else "EXCUSE: #{excuse}"