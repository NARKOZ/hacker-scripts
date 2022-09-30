#!/usr/bin/env python

import os
import random
from twilio.rest import TwilioRestClient
import subprocess
import sys
from time import strftime

import config

# exit if no sessions with my username are found
output = subprocess.check_output('who')
if config.SYSTEM_USERNAME not in output:
    sys.exit()

reasons = [
    'Working hard',
    'Gotta ship this feature',
    'Someone fucked the system again',
]

client = TwilioRestClient(config.TWILIO_ACCOUNT_SID, config.TWILIO_AUTH_TOKEN)

client.messages.create(
    to=config.EMAIL_CONTACTS['WIFE'],
    from_=config.EMAIL_CONTACTS['ME'],
    body="Late at work. " + random.choice(reasons)
)

print "Message sent at " + strftime("%a, %d %b %Y %H:%M:%S")
