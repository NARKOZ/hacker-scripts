#!/usr/bin/env python

import datetime
import os
import random
from twilio.rest import TwilioRestClient
import subprocess
import sys
from time import strftime


today = datetime.date.today()

# skip weekends
if today.strftime('%A') == 'Saturday' || today('%A') == 'Sunday':
    sys.exit()

# exit if no sessions with my username are found
output = subprocess.check_output('who')
if 'my_username' not in output:
    sys.exit()

# returns 'None' if the key doesn't exist
TWILIO_ACCOUNT_SID = os.environ.get('TWILIO_ACCOUNT_SID')
TWILIO_AUTH_TOKEN  = os.environ.get('TWILIO_AUTH_TOKEN')

# Phone numbers
my_number      = '+xxx'
her_number = '+xxx'

reasons = [
  'Working hard',
  'Gotta ship this feature',
  'Someone fucked the system again'
]

client = TwilioRestClient(TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN)

client.messages.create(
    to=her_number,
    from=my_number,
    body="Late at work. " + random.choice(reasons)
)

try:
    if not os.path.exists('logs'):
        os.mkdir('logs')
    with open('logs/file.txt', 'a') as lh:
        lh.write("Message sent at " + strftime("%a, %d %b %Y %H:%M:%S") + "\n")
except Exception as e:
    print e
