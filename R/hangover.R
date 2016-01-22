library(httr)

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
number_of_boss= '+xxx'

excuse = c(
  'Locked out',
  'Pipes broke',
  'Food poisoning',
  'Not feeling well'
      )

POST(paste("https://api.twilio.com/2010-04-01/Accounts/",TWILIO_ACCOUNT_SID,"/Messages.json",sep=""),
          body = list(From=my_number,To=number_of_boss,Body=paste("Gonna work from home. ", sample(excuse,1))),
	            authenticate(TWILIO_ACCOUNT_SID,TWILIO_AUTH_TOKEN) )

print( paste("Message sent at",Sys.time()) )
