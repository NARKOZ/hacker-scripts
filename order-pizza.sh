#!/bin/sh -e

# So dominos.com recently added the ability to text an order.. as we all know pizza is no.1 most important hacker fuel for hacking
# Requires you to setup easy order on your country's dominos website

# Phone numbers
MY_NUMBER='+xxx'
DOMINOS_NUMBER='+374992'

MESSAGE="EASY ORDER"
CONFIRM="CONFIRM"

# Send a text message
RESPONSE=`curl -fSs -u "$TWILIO_ACCOUNT_SID:$TWILIO_AUTH_TOKEN" \
  -d "From=$MY_NUMBER" -d "To=$DOMINOS_NUMBER" -d "Body=$MESSAGE" \
  "https://api.twilio.com/2010-04-01/Accounts/$TWILIO_ACCOUNT_SID/Messages"`
  
  sleep 15

# Confirm message  
RESPONSE2=`curl -fSs -u "$TWILIO_ACCOUNT_SID:$TWILIO_AUTH_TOKEN" \
-d "From=$MY_NUMBER" -d "To=$DOMINOS_NUMBER" -d "Body=$CONFIRM" \
"https://api.twilio.com/2010-04-01/Accounts/$TWILIO_ACCOUNT_SID/Messages"`
  

# Log errors
if [ $? -gt 0 ]; then
  echo "Failed to send SMS: $RESPONSE"
  echo $RESPONSE2
  exit 1
fi
