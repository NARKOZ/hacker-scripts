#!/bin/sh -e

# Fill in the blanks
# Phone numbers with leading country code: +46 012 345 6788
MY_NUMBER=''
NUMBER_OF_BOSS=''

# Twilio account information
TWILIO_ACCOUNT_SID=""
TWILIO_AUTH_TOKEN=""

# Check for vars
if [ -z "$MY_NUMBER" ] || [ -z "$NUMBER_OF_BOSS" ]; then
  echo "You need to set the phone numbers"
  exit 1
fi

# Check for Twilio
if [ -z "$TWILIO_AUTH_TOKEN" ] || [ -z "$TWILIO_ACCOUNT_SID" ]; then
  echo "You need to set the twilio variables"
  exit 1
fi

DAYOFWEEK=$(date +%u)

# Skip on weekends
if [ "$DAYOFWEEK" -eq 6 ] || [ "$DAYOFWEEK" -eq 7 ]; then
  exit
fi

# Exit early if any session with my_username is found
if who | grep -wq 'my_username'; then
  exit
fi

EXCUSES=(
  'Locked out'
  'Pipes broke'
  'Food poisoning'
  'Not feeling well'
)
rand=$[ $RANDOM % ${#EXCUSES[@]} ]

RANDOM_EXCUSE=${EXCUSES[$rand]}
MESSAGE="Gonna work from home. "$RANDOM_EXCUSE

# Send a text message
RESPONSE=`curl -fSs -u "$TWILIO_ACCOUNT_SID:$TWILIO_AUTH_TOKEN" \
  -d "From=$MY_NUMBER" -d "To=$NUMBER_OF_BOSS" -d "Body=$MESSAGE" \
  "https://api.twilio.com/2010-04-01/Accounts/$TWILIO_ACCOUNT_SID/SMS/Messages"`

# Log errors
if [ $? -gt 0 ]; then
  echo "Failed to send SMS: $RESPONSE"
  exit 1
fi
