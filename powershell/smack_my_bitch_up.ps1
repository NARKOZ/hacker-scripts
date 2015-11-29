$DAYOFWEEK = (Get-Date).DayOfWeek.value__;

# Skip on weekends
if ($DAYOFWEEK -eq 6 -or $DAYOFWEEK -eq 7) {
    return
}

# Exit early if no sessions with my username are found
if (-not (QWINSTA | FINDSTR $env:USERNAME)) {
    return
}

# Phone numbers
$MY_NUMBER='+xxx'
$HER_NUMBER='+xxx'

$REASONS=
  'Working hard',
  'Gotta ship this feature',
  'Someone fucked the system again'

$RAND = Get-Random -Maximum $REASONS.Count

$MSG="Late at work. $REASONS[$RAND]"

$API_URL = "https://api.twilio.com/2010-04-01/Accounts/$env:TWILIO_ACCOUNT_SID/Messages"
$BASE64AUTHINFO = [Convert]::ToBase64String([Text.Encoding]::ASCII.GetBytes(("{0}:{1}" -f $env:TWILIO_ACCOUNT_SID,$env:TWILIO_AUTH_TOKEN)))
$body = @{
    From = $MY_NUMBER;
    To = $HER_NUMBER;
    Body = $MSG;
}

#Send a text message and Log errors
try{
    Invoke-RestMethod -Method Post -Headers @{Authorization=("Basic {0}" -f $BASE64AUTHINFO)} $API_URL -Body $body > $null
}
catch{
    Write-Host "Failed to send SMS: $_"
}
