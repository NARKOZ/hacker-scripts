#!/usr/bin/env ruby

# Exit early if no sessions with my username are found
exit unless `who -q`.include? ENV['USER']

require 'dotenv'
require 'twilio-ruby'

Dotenv.load

TWILIO_ACCOUNT_SID = ENV['TWILIO_ACCOUNT_SID']
TWILIO_AUTH_TOKEN  = ENV['TWILIO_AUTH_TOKEN']

@twilio = Twilio::REST::Client.new TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN

# Phone numbers
my_number  = '+xxx'
her_number = '+xxx'

reason = [
  'Working hard',
  'Gotta ship this feature',
  'Someone fucked the system again'
].sample

# Send a text message
@twilio.messages.create(
  from: my_number, to: her_number, body: "Late at work. #{reason}"
)

# Log this
puts "Message sent at: #{Time.now} | Reason: #{reason}"
