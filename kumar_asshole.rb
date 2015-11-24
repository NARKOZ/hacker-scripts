#!/usr/bin/env ruby

require 'dotenv'
require 'gmail'

Dotenv.load

GMAIL_USERNAME = ENV['GMAIL_USERNAME']
GMAIL_PASSWORD = ENV['GMAIL_PASSWORD']

gmail = Gmail.connect(GMAIL_USERNAME, GMAIL_PASSWORD)

KEYWORDS_REGEX = /sorry|help|wrong/i

gmail.inbox.find(:unread, from: 'kumar.a@example.com').each do |email|
  if email.body[KEYWORDS_REGEX]
    # Restore DB and send a reply
    email.label('Database fixes')
    reply = create_reply(email.subject)
    gmail.deliver(reply)
  end
end

def create_reply(subject)
  gmail.compose do
    to "kumar.a@example.com"
    subject "RE: #{subject}"
    body "No problem. I've fixed it. \n\n Please be careful next time."
  end
end
