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
TWILIO_AUTH_TOKEN = os.environ.get('TWILIO_AUTH_TOKEN')

# Phone numbers
my_number = '+xxx'
number_of_boss = '+xxx'

# Shout out to https://idontlike.work
excuses = [
    "Locked out",
    "Pipes broke",
    "Food poisoning",
    "Not feeling well",
    "My gas cylinder is leaking. Need to get it fixed.",
    "Got some pain in the back when I woke up in the morning. Need to visit a doctor.",
    "My cat somehow ate marijuana and is behaving weird.",
    "Room mate caught high fever. Need to visit a doctor.",
    "Waiting for a furniture delivery.", "Hard luck finding a cab.",
    "In laws are coming for a doctor's visit.",
    "Car broke-down on the way to work.",
    "Had food poisoning last night.",
    "Need to work peacefully without disturbance.",
    "Someone is coming home for my address proof verification.",
    "Dog died, need to bury him.", "Can't find my headphones.",
    "No electricity, can't iron clothes.",
    "All clothes in laundry. Will be working from home.",
    "Donated blood today. Need some rest.",
    "Ran out of toothpaste and soap, trust me!",
    "My astrologer asked me to stay at home, star alignment is not good.",
    "Came across a black cat when I stepped out of home.",
    "My landlord is shouting at me. Need to kick him out of the house!",
    "Someone painted a d*ck on my car :(",
    "Waiting for broadband connection to be setup.",
    "Google maps shows unusually high traffic on my way to work.",
    "Leg injured while playing football last night. Can't walk. ",
    "Some carpenter work is going on at home.",
    "Cat is sick. Need to take care of him.",
    "Rain water flooding in. Need to take care of home.",
    "My kid is blackmailing to cut his hand if I go to work today.",
    "Raining heavily at my place. Can't leave out.",
    "Uninterested but unavoidable family event.",
    "Fish died, need to bury him.",
    "Pest control visiting home today. ",
    "Morning wood not going away.",
    "Neighbors went on a tour and they asked me to keep an eye on their home.",
    "Roommate locked me in and left to work.",
    "Have lots of pending work, no time to come office.",
    "My shoes got damaged from the rain, I need to buy one for work.",
    "Waiting for motivation to built up.",
    "Waiting for the maid to arrive. Will be firing her today. Wish me luck. Failed twice at it already.",
    "Frequent exposure to bad roads and traffic affects my health.",
    "Had a fight with colleague, don’t want to see his face!",
    "Working from another country. Can't afford to die of the new random Nepah virus.",
    "Want to work with loud music today",
    "Had fight with my neighbor, he said step out of the house and I'll beat you.",
    "No money to travel to office. Please consider increasing my salary too.",
    "My barber couldn't understand my instructions, new hair cut looks horrible.",
    "Had a heavy breakfast, dont feel like moving",
    "No place to sit in office",
    "My ankle got handcuffed to the bedframe since last night.",
    "It's my dog's birthday. Need to bake a cake.",
    "Forgot way to Office, So returned home."
 ]

client = TwilioRestClient(TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN)

client.messages.create(
    to=number_of_boss,
    from_=my_number,
    body="Gonna work from home. " + random.choice(excuses)
)

print "Message sent at " + strftime("%a, %d %b %Y %H:%M:%S")
