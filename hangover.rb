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
 'I thought of quitting today, but then decided not to, so I came in late.',
'My hair caught on fire from my blow dryer.',
'I was detained by Homeland Security.',
'I had to chase my cows back into the field.',
'A black bear entered my carport and decided to take a nap on the hood of my car.',
'My lizard had to have emergency surgery in the morning and died during surgery. I had to mourn while deciding whether to have the lizard disposed of by the vet or bring the lizard corpse with me to work.',
'There was fresh powder on the hill. I had to go skiing.',
'There was a store grand opening and I wanted to get the opening day sales.',
'I had to finish watching “My Name is Earl.”',
'All of my clothes were stolen.',
'I was confused by the time change and unsure if it was “spring forward” or “fall back.”',
'A Vaseline truck overturned on the highway and cars were slipping left and right.'
].sample

# Send a text message
@twilio.messages.create(
  from: my_number, to: number_of_boss,
  body: "Gonna work from home. #{excuse}"
)

# Log this
puts "Message sent at: #{Time.now} | Excuse: #{excuse}"
