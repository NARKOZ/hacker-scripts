#!/usr/bin/env ruby

# Skip on weekends
exit if Time.now.saturday? || Time.now.sunday?

# Exit early if no sessions with my_username are found
exit if `who`[/my_username/].nil?

require 'net/telnet'

coffee_machine_ip = '10.10.42.42'
password = '1234'
password_prompt = 'Password: '
delay = 24

con = Net::Telnet.new('Host' => coffee_machine_ip)
con.cmd('String' => password, 'Match' => /#{password_prompt}/)
con.cmd('sys brew')
sleep delay
con.cmd('sys pour')
con.close
