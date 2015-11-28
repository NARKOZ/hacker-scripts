

today = Sys.Date()

# skip weekends
if( weekdays(today) %in% c('Saturday','Sunday') ){
  quit()  
}

# exit if no sessions with my username are found
output = system("who", intern = TRUE)
if( !( grep('^my_user_name', output) ) ){
  quit()
}

# returns 'None' if the key doesn't exist
TWILIO_ACCOUNT_SID = Sys.getenv('TWILIO_ACCOUNT_SID')
TWILIO_AUTH_TOKEN  = Sys.getenv('TWILIO_AUTH_TOKEN')

# Phone numbers
my_number = '+xxx'
her_number = '+xxx'

reasons = c(
  'Working hard',
  'Gotta ship this feature',
  'Someone fucked the system again'
)

