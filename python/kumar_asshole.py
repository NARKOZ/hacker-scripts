#!/usr/bin/env python

import gmail
import sys
import re

import config

g = gmail.login(config.GMAIL_USERNAME, config.GMAIL_PASSWORD)

if not g.logged_in:
    sys.exit()

msgs = g.inbox().mail(
    sender=config.EMAIL_CONTACTS['KUMAR'],
    unread=True,
    prefetch=True
)

pattern = re.compile("\bsorry\b | \bhelp\b | \bwrong\b ", flags=re.I)

for msg in msgs:
    if pattern.match(msg.body):
        msg.label("Database fixes")
        msg.reply("No problem. I've fixed it.\n\nPlease be careful next time.")
