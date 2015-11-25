$DAYOFWEEK = (Get-Date).DayOfWeek.value__;

# Skip on weekends
if ($DAYOFWEEK -eq 6 -or $DAYOFWEEK -eq 7) {
    return
}

# Exit early if no sessions with my username are found
if ((QWINSTA $env:USERNAME | measure).Count -gt 0){
    return
}

# Phone numbers
$MY_NUMBER='+xxx'
$HER_NUMBER='+xxx'

$TWILIO_ACCOUNT_SID = 'xxx'
$TWILIO_AUTH_TOKEN = 'xxx'

$REASONS=
  'Working hard',
  'Gotta ship this feature',
  'Someone fucked the system again'

$RAND = Get-Random -Maximum $REASONS.Count

$MSG="Late at work. $REASONS[$RAND]"


$BASE64AUTHINFO = [Convert]::ToBase64String([Text.Encoding]::ASCII.GetBytes(("{0}:{1}" -f $TWILIO_ACCOUNT_SID,$TWILIO_AUTH_TOKEN)))

#Send a text messag and Log errors
try{
    Invoke-RestMethod -Method Post -Headers @{Authorization=("Basic {0}" -f $BASE64AUTHINFO)} "https://api.twilio.com/2010-04-01/Accounts/$TWILIO_ACCOUNT_SID/Messages" -Body "From=$MY_NUMBER&To=$HER_NUMBER&Body=$MSG" > $null
}
catch{
    Write-Host "Failed to send SMS: $_"
}

