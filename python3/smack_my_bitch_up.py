#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import random

from twilio import TwilioRestException
from twilio.rest import TwilioRestClient

from hackerutils import get_dotenv, get_log_path, sh

dotenv = get_dotenv()

TWILIO_ACCOUNT_SID = dotenv['TWILIO_ACCOUNT_SID']
TWILIO_AUTH_TOKEN = dotenv['TWILIO_AUTH_TOKEN']

LOG_FILE_PATH = get_log_path('smack_my_bitch_up.txt')


def main():
    # Exit early if no sessions with my_username are found.
    if not any(s.startswith(b'my_username ') for s in sh('who').split(b'\n')):
        return

    client = TwilioRestClient(TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN)

    # Phone numbers.
    my_number = '+xxx'
    her_number = '+xxx'

    reasons = [
        'Working hard',
        'Gotta ship this feature',
        'Someone fucked the system again',
    ]

    try:
        # Send a text message.
        client.messages.create(
            to=her_number,
            from_=my_number,
            body='Late at work. ' + random.choice(reasons),
        )
    except TwilioRestException as e:
        # Log errors.
        with LOG_FILE_PATH.open('a') as f:
            f.write('Failed to send SMS: {}'.format(e))
        raise


if __name__ == '__main__':
    main()
