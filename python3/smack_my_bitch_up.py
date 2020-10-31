#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import getpass
import os
import random

from dotenv import load_dotenv
from twilio.rest import Client, TwilioException

from hackerutils import get_log_path, sh

load_dotenv()

TWILIO_ACCOUNT_SID = os.getenv("TWILIO_ACCOUNT_SID")
TWILIO_AUTH_TOKEN = os.getenv("TWILIO_AUTH_TOKEN")

LOG_FILE_PATH = get_log_path("smack_my_bitch_up.txt")


def main():
    # Exit early if no sessions with my_username are found.
    username = getpass.getuser().encode()
    if not any(s.startswith(username) for s in sh("who").split(b"\n")):
        return

    client = Client(TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN)

    # Phone numbers.
    my_number = "+" + os.getenv("TWILIO_TRIAL_NO")
    her_number = "+" + os.getenv("TWILIO_HER_NO")

    reasons = [
        "Working hard",
        "Gotta ship this feature",
        "Someone fucked the system again",
    ]

    try:
        # Send a text message.
        client.messages.create(
            to=her_number,
            from_=my_number,
            body="Late at work. " + random.choice(reasons),
        )
    except TwilioException as e:
        # Log errors.
        with LOG_FILE_PATH.open("a") as f:
            f.write("Failed to send SMS: {}".format(e))
        raise


if __name__ == "__main__":
    main()
