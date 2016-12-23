#!/usr/bin/env ruby

# Exit early if sessions with my username are found
exit if `who -q`.include? ENV['USER']

require 'dotenv'
require 'twilio-ruby'

Dotenv.load

TWILIO_ACCOUNT_SID = ENV['TWILIO_ACCOUNT_SID']
TWILIO_AUTH_TOKEN  = ENV['TWILIO_AUTH_TOKEN']

@twilio = Twilio::REST::Client.new TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN

# Phone numbers
my_number      = '+xxx'
number_of_boss = '+xxx'

excuse = [
  'Locked out',
  'Pipes broke',
  'Food poisoning',
  'Not feeling well'
].sample

# Send a text message
@twilio.messages.create(
  from: my_number, to: number_of_boss,
  body: "Gonna work from home. #{excuse}"
)

# Log this
puts "Message sent at: #{Time.now} | Excuse: #{excuse}"
