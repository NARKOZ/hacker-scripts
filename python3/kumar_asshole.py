#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import re

import gmail
import yagmail

from hackerutils import get_dotenv

dotenv = get_dotenv()

GMAIL_USERNAME = dotenv['GMAIL_USERNAME']
GMAIL_PASSWORD = dotenv['GMAIL_PASSWORD']

KUMAR_EMAIL = 'kumar.a@example.com'
KEYWORDS_REGEX = re.compile(r'sorry|help|wrong', re.IGNORECASE)

REPLY_BODY = "No problem. I've fixed it. \n\n Please be careful next time."


yagmail.register(GMAIL_USERNAME, GMAIL_PASSWORD)


def send_reply(subject):
    yag = yagmail.SMTP(GMAIL_USERNAME)
    yag.send(
        to=KUMAR_EMAIL,
        subject='RE: {}'.format(subject),
        contents=REPLY_BODY,
    )


def main():
    g = gmail.login(GMAIL_USERNAME, GMAIL_PASSWORD)
    for mail in g.inbox().mail(unread=True, sender=KUMAR_EMAIL, prefetch=True):
        if KEYWORDS_REGEX.search(mail.body):
            # Restore DB and send a reply.
            mail.add_label('Database fixes')
            send_reply(mail.subject)


if __name__ == '__main__':
    main()
