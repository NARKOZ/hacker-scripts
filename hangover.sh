#!/bin/sh -e

DAYOFWEEK=$(date +%u)

# Skip on weekends
if [ "$DAYOFWEEK" -eq 6 ] || [ "$DAYOFWEEK" -eq 7 ]; then
  exit
fi

# Exit early if any session with my username is found
if who | grep -wq $USER; then
  exit
fi

# Phone numbers
MY_NUMBER='+xxx'
NUMBER_OF_BOSS='+xxx'

EXCUSES=(
  'Locked out'
  'Pipes broke'
  'Food poisoning'
  'Not feeling well'
)
rand=$[ $RANDOM % ${#EXCUSES[@]} ]

RANDOM_EXCUSE=${EXCUSES[$rand]}
if [ "$RANDOM_EXCUSE" = "Locked out" ];then
  MESSAGE="Gonna work from outside of my home. "$RANDOM_EXCUSE
else
  MESSAGE="Gonna work from home. "$RANDOM_EXCUSE
fi

# Send a text message
RESPONSE=`curl -fSs -u "$TWILIO_ACCOUNT_SID:$TWILIO_AUTH_TOKEN" \
  -d "From=$MY_NUMBER" -d "To=$NUMBER_OF_BOSS" -d "Body=$MESSAGE" \
  "https://api.twilio.com/2010-04-01/Accounts/$TWILIO_ACCOUNT_SID/Messages"`

# Log errors
if [ $? -gt 0 ]; then
  echo "Failed to send SMS: $RESPONSE"
  exit 1
fi
