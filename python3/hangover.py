#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import random

from twilio import TwilioRestException
from twilio.rest import TwilioRestClient

from hackerutils import get_dotenv, get_log_path, sh

dotenv = get_dotenv()

TWILIO_ACCOUNT_SID = dotenv['TWILIO_ACCOUNT_SID']
TWILIO_AUTH_TOKEN = dotenv['TWILIO_AUTH_TOKEN']

LOG_FILE_PATH = get_log_path('hangover.txt')


def main():
    # Exit early if any session with my_username is found.
    if any(s.startswith(b'my_username ') for s in sh('who').split(b'\n')):
        return

    client = TwilioRestClient(TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN)

    # Phone numbers.
    my_number = '+xxx'
    number_of_boss = '+xxx'

    excuses = [
        'Locked out',
        'Pipes broke',
        'Food poisoning',
        'Not feeling well',
    ]

    try:
        # Send a text message.
        client.messages.create(
            to=number_of_boss,
            from_=my_number,
            body='Gonna work from home. ' + random.choice(excuses),
        )
    except TwilioRestException as e:
        # Log errors.
        with LOG_FILE_PATH.open('a') as f:
            f.write('Failed to send SMS: {}'.format(e))
        raise


if __name__ == '__main__':
    main()
