#!/usr/bin/env ruby

# Skip on weekends
exit if Time.now.saturday? || Time.now.sunday?

log_file_name = File.dirname(__FILE__) + '/logs/smack_my_bitch_up.txt'

# Be sure that logs dir always exists
Dir.mkdir('logs') unless File.exists?(log_file_name)

LOG_FILE = File.open(log_file_name, 'a+')

# Exit early if no sessions with my username are found
exit if `who -q`.include? ENV['USER']

require 'dotenv'
require 'twilio-ruby'

Dotenv.load

TWILIO_ACCOUNT_SID = ENV['TWILIO_ACCOUNT_SID']
TWILIO_AUTH_TOKEN  = ENV['TWILIO_AUTH_TOKEN']

@twilio = Twilio::REST::Client.new TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN

# Phone numbers
my_number  = '+xxx'
her_number = '+xxx'

reasons = [
  'Working hard',
  'Gotta ship this feature',
  'Someone fucked the system again'
]

# Send a text message
@twilio.messages.create(
  from: my_number, to: her_number, body: 'Late at work. ' + reasons.sample
)

# Log this
LOG_FILE.puts("Message sent at: #{Time.now}")
LOG_FILE.close
