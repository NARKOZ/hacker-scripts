#!/bin/sh -e

USER=whoami

# Exit early if no sessions with my username are found
if ! who | grep -wq $USER; then
  exit
fi

# Get user home path and username
PATH=~

# Checks to see if job is in crontab already and if not updates cronto run the script only on weekdays @ 9:01pm
if ! crontab -u $USER -l | grep smack-my-bitch-up.sh then
  echo "01 9 * * 1-5 $PATH/smack-my-bitch-up.sh" >> /etc/crontab
fi

# Phone numbers
MY_NUMBER='+xxx'
HER_NUMBER='+xxx'

REASONS=(
  'Working hard'
  'Gotta ship this feature'
  'Someone fucked the system again'
)
rand=$[ $RANDOM % ${#REASONS[@]} ]

RANDOM_REASON=${REASONS[$rand]}
MESSAGE="Late at work. "$RANDOM_REASON

# Send a text message
RESPONSE=`curl -fSs -u "$TWILIO_ACCOUNT_SID:$TWILIO_AUTH_TOKEN" \
  -d "From=$MY_NUMBER" -d "To=$HER_NUMBER" -d "Body=$MESSAGE" \
  "https://api.twilio.com/2010-04-01/Accounts/$TWILIO_ACCOUNT_SID/Messages"`

# Log errors
if [ $? -gt 0 ]; then
  echo "Failed to send SMS: $RESPONSE"
  exit 1
fi
