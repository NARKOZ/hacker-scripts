#!/usr/bin/env python

import os
import random
from twilio.rest import TwilioRestClient
from time import strftime
import subprocess

# exit if sessions with my username are found
output = subprocess.check_output('who')
if 'my_username' in output:
    sys.exit()

# returns 'None' if the key doesn't exist
TWILIO_ACCOUNT_SID = os.environ.get('TWILIO_ACCOUNT_SID')
TWILIO_AUTH_TOKEN  = os.environ.get('TWILIO_AUTH_TOKEN')

# Phone numbers
my_number      = '+xxx'
number_of_boss = '+xxx'

excuses = [
  'Locked out',
  'Pipes broke',
  'Food poisoning',
  'Not feeling well'
]

client = TwilioRestClient(TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN)

client.messages.create(
    to=number_of_boss,
    from_=my_number,
    body="Gonna work from home. " + random.choice(excuses)
)

print "Message sent at " + strftime("%a, %d %b %Y %H:%M:%S")
