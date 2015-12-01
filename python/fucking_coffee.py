#!/usr/bin/env python

import sys
import subprocess
import telnetlib
import time

# exit if no sessions with my username are found
if os.environ.get('USER') in subprocess.check_output('who'):
    sys.exit()

coffee_machine_ip = '10.10.42.42'
password = '1234'
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
