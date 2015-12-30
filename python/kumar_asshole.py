#!/usr/bin/env python

import gmail
import sys
import re

GMAIL_USERNAME = ENV['GMAIL_USERNAME']
GMAIL_PASSWORD = ENV['GMAIL_PASSWORD']

g = gmail.login(GMAIL_USERNAME, GMAIL_PASSWORD)

if not g.logged_in:
    sys.exit()

msgs = g.inbox().mail(sender="kumar.a@example.com", unread=True, prefetch=True)

pattern = re.compile("\bsorry\b | \bhelp\b | \bwrong\b ", flags=re.I)

for msg in msgs:
    if pattern.match(msg.body):
        msg.label("Database fixes")
        msg.reply("No problem. I've fixed it. \n\n Please be careful next time.")
