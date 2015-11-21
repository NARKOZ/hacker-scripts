#!/usr/bin/env ruby

# Skip on weekends
exit if Time.now.saturday? || Time.now.sunday?

require 'net/telnet'

coffee_machine_ip = '10.10.42.42'
password = '1234'
password_prompt = 'Password: '

con = Net::Telnet.new('Host' => coffee_machine_ip)
con.cmd('String' => password, 'Match' => /#{password_prompt}/)
con.cmd('sys brew')
sleep 64
con.cmd('sys pour')
con.close
