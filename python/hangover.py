#!/usr/bin/env python

import os
import random
from twilio.rest import TwilioRestClient
from time import strftime
import subprocess

import config

# exit if sessions with my username are found
output = subprocess.check_output('who')
if config.SYSTEM_USERNAME in output:
    sys.exit()

excuses = [
    'Locked out',
    'Pipes broke',
    'Food poisoning',
    'Not feeling well'
]

client = TwilioRestClient(config.TWILIO_ACCOUNT_SID, config.TWILIO_AUTH_TOKEN)

client.messages.create(
    to=config.PHONEBOOK['BOSS'],
    from_=config.PHONEBOOK['ME'],
    body="Gonna work from home. " + random.choice(excuses)
)

print "Message sent at " + strftime("%a, %d %b %Y %H:%M:%S")
