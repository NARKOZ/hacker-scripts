#!/usr/bin/env ruby

# Skip on weekends
exit if Time.now.saturday? || Time.now.sunday?

log_file_name = File.dirname(__FILE__) + '/logs/hangover.txt'

# Be sure that logs dir always exists
Dir.mkdir('logs') unless File.exists?(log_file_name)

LOG_FILE = File.open(log_file_name, 'a+')

# Exit early if sessions with my_username are found
exit unless `who`[/my_username/].nil?

require 'dotenv'
require 'twilio-ruby'

Dotenv.load

TWILIO_ACCOUNT_SID = ENV['TWILIO_ACCOUNT_SID']
TWILIO_AUTH_TOKEN  = ENV['TWILIO_AUTH_TOKEN']

@twilio = Twilio::REST::Client.new TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN

# Phone numbers
my_number      = '+xxx'
number_of_boss = '+xxx'

excuses = [
  'Locked out',
  'Pipes broke',
  'Food poisoning',
  'Not feeling well'
]

# Send a text message
@twilio.messages.create(
  from: my_number, to: number_of_boss,
  body: 'Gonna work from home. ' + excuses.sample
)

# Log this
LOG_FILE.puts("Message sent at: #{Time.now}")
LOG_FILE.close
