#!/usr/bin/env ruby

require 'dotenv'
require 'gmail'

Dotenv.load

GMAIL_USERNAME = ENV['GMAIL_USERNAME']
GMAIL_PASSWORD = ENV['GMAIL_PASSWORD']

gmail = Gmail.connect(GMAIL_USERNAME, GMAIL_PASSWORD)
kumars_email = 'kumar.a@example.com'

DB_NAME_REGEX  = /\S+_staging/
KEYWORDS_REGEX = /sorry|help|wrong/i

gmail.inbox.find(:unread, from: kumars_email).each do |email|
  if email.body[KEYWORDS_REGEX] && (db_name = email.body[DB_NAME_REGEX])
    backup_file = "/home/backups/databases/#{db_name}-" + (Date.today - 1).strftime('%Y%m%d') + '.gz'
    abort 'ERROR: Backup file not found' unless File.exist?(backup_file)

    # Restore DB
    `gunzip -c #{backup_file} | psql #{db_name}`

    # Mark as read, add label and reply
    email.read!
    email.label('Database fixes')
    reply = create_reply(email.subject)
    gmail.deliver(reply)
  end
end

def create_reply(subject)
  gmail.compose do
    to kumars_email
    subject "RE: #{subject}"
    body "No problem. I've fixed it. \n\n Please be careful next time."
  end
end
