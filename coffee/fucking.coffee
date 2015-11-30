#!/usr/bin/env coffee

username = 'name'

host = 'localhost'
port = '3000'
pass = '5555'

sh = require('child_process').execSync

# weekend
process.exit 0 if new Date().getDay() in [6, 0]

# no sessions
process.exit 0 unless new RegExp(username).test sh('who -q').toString()

conn = require('net').createConnection port, host

setTimeout ->
  conn.write "#{pass}\nsys brew\n"
  setTimeout ->
    conn.end 'sys pour'
    process.exit 0
  , 2 * 1000
, 1 * 1000

# alert
sh 'say come here and take your fucking coffee'
