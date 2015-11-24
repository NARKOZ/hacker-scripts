#!/usr/bin/env python

import datetime
import os
import random
from twilio.rest import TwilioRestClient
from time import strftime
import subprocess

today = datetime.date.today()

# skip weekends
if today.strftime('%A') in ('Saturday', 'Sunday'):
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
    from=my_number,
    body="Gonna work from home. " + random.choice(excuses)
)

try:
    f = open('logs/file.txt', 'a')
except IOError as e:
    # dir & file don't exist; create them
    os.mkdir('logs')
    f = open('logs/file.txt', 'a')
except Exception as e:
    print e
else:
    pass

# log it
f.write("Message sent at " + strftime("%a, %d %b %Y %H:%M:%S") + "\n")
f.close()
