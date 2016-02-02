#!/bin/sh -e

# Exit early if any session with my username is found
if who | grep -wq $USER; then
  exit
fi

# Phone numbers
MY_NUMBER='+xxx'
NUMBER_OF_BOSS='+xxx'

EXCUSES=(
'I thought of quitting today, but then decided not to, so I came in late.'
'My hair caught on fire from my blow dryer.'
'I was detained by Homeland Security.'
'I had to chase my cows back into the field.'
'A black bear entered my carport and decided to take a nap on the hood of my car.'
'My lizard had to have emergency surgery in the morning and died during surgery. I had to mourn while deciding whether to have the lizard disposed of by the vet or bring the lizard corpse with me to work.'
'There was fresh powder on the hill. I had to go skiing.'
'There was a store grand opening and I wanted to get the opening day sales.'
'I had to finish watching “My Name is Earl.”'
'All of my clothes were stolen.'
'I was confused by the time change and unsure if it was “spring forward” or “fall back.”'
'A Vaseline truck overturned on the highway and cars were slipping left and right.'
)
rand=$[ $RANDOM % ${#EXCUSES[@]} ]

RANDOM_EXCUSE=${EXCUSES[$rand]}
MESSAGE="Gonna work from home. "$RANDOM_EXCUSE

# Send a text message
RESPONSE=`curl -fSs -u "$TWILIO_ACCOUNT_SID:$TWILIO_AUTH_TOKEN" \
  -d "From=$MY_NUMBER" -d "To=$NUMBER_OF_BOSS" -d "Body=$MESSAGE" \
  "https://api.twilio.com/2010-04-01/Accounts/$TWILIO_ACCOUNT_SID/Messages"`

# Log errors
if [ $? -gt 0 ]; then
  echo "Failed to send SMS: $RESPONSE"
  exit 1
fi
