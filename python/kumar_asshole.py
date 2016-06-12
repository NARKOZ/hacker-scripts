#!/usr/bin/env python

import gmail
import sys
import re
import os
from datetime import timedelta
import datetime


GMAIL_USERNAME = os.environ['GMAIL_USERNAME']
GMAIL_PASSWORD = os.environ['GMAIL_PASSWORD']

g = gmail.login(GMAIL_USERNAME, GMAIL_PASSWORD)

if not g.logged_in:
    sys.exit()

msgs = g.inbox().mail(sender="kumar.a@example.com", unread=True, prefetch=True)

pattern = r'\bhelp\b|\bwrong\b|\bsorry\b'
db_pattern = r'\S+_staging'

for msg in msgs:
    if re.search(pattern, msg, flags=re.I):
        db_name = re.search(db_pattern, msg, flags=re.I)
        if db_name:
            dt_today = datetime.date.today()
            dt_lastday = (dt_today - timedelta(days=1)).strftime ("%Y%m%d")
            backup_file = '/home/backups/databases/%s-.gz'%(dt_lastday)
            if os.path.isfile(backup_file):
                exec_string = 'gunzip -c %s | psql %s'%(backup_file, db_name)
                os.system(exec_string)
                msg.label("Database fixes")
                msg.reply("No problem. I've fixed it. \n\n Please be careful next time.")
            else:
                print 'ERROR: Backup file not found'
                exit(1)
