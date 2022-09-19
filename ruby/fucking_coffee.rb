#!/usr/bin/env ruby

# Exit early if no sessions with my username are found
exit unless `who -q`.include? ENV['USER']

require 'net/telnet'

coffee_machine_ip = '10.10.42.42'
password = '1234'
password_prompt = 'Password: '
delay_before_brew = 17
delay = 24

sleep delay_before_brew
con = Net::Telnet.new('Host' => coffee_machine_ip)
con.cmd('String' => password, 'Match' => /#{password_prompt}/)
con.cmd('sys brew')
sleep delay
con.cmd('sys pour')
con.close
