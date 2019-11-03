#!/usr/bin/env python

import sys
import subprocess
import telnetlib
import time

# exit if no sessions with my username are found
output = subprocess.check_output('who')
if 'my_username' not in output:
    sys.exit()

coffee_machine_ip = 'REPLACE THIS WITH YOUR IP ADDRESS'
password = 'REPLACE THIS WITH YOUR PASSWORD'
password_prompt = 'Password: '

con = telnetlib.Telnet(coffee_machine_ip)
con.read_until(password_prompt)
con.write(password + "\n")

# Make some coffee!
con.write("sys brew\n")
time.sleep(64)

# love the smell!
con.write("sys pour\n")
con.close()    
