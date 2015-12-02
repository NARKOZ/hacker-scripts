<#
.SYNOPSIS
    Simple script to SMS a supervisor informing them you will be working from home
    on the day this script is used.
.DESCRIPTION
    This script was converted using the ruby version of the hangover script. However, the ruby
    version used environment variables to hold the user's account information. Due to issue #42
    (https://github.com/NARKOZ/hacker-scripts/issues/42) I opted to hard code the strings at
    this time until a decision is made by NARKOZ, the project owner, as the how the information
    should be stored.

    This script also uses Twilio to send the SMS messages. The from number MUST be a valid Twilio
    phone number. The to number can be any outgoing number.
.OUTPUT
    This script will output an error message to the PowerShell window if it fails
    to send the message.
.NOTES
    Author:            Tyler Hughes
    Twitter:           @thughesIT
    Blog:              http://tylerhughes.info/

    Changelog:
       1.0             Initial Release
#>
Function Hangover
{
  # Phone numbers (Must include country code and area code)
  $from = '+XXXXXXXXXXX'
  $to = '+XXXXXXXXXXX'

  # Twilio API Information
  $twilio_base_url = 'https://api.twilio.com/2010-04-01'
  $twilio_account_sid = 'XXXXXXXXXXXXXXXXXXX'
  $twilio_auth_token = 'XXXXXXXXXXXXXXXXXX'

  $password = ConvertTo-SecureString -AsPlainText $twilio_auth_token -Force
  $credentials = New-Object System.Management.Automation.PSCredential($twilio_account_sid, $password)

  # Get the message to send
  $excuses =
    'Locked out',
    'Pipes broke',
    'Food poisoning',
    'Not feeling well'

  $excuse = $excuses | Get-Random
  $message = "$excuse. Going to work from home today."
  $body = @{
    From = $from;
    To = $to;
    Body = $message;
  }

  # Send the message and log any errors
  $uri = "$twilio_base_url/Accounts/" + $credentials.UserName + "/SMS/Messages"

  try {
    $response = Invoke-RestMethod -Method Post -Uri $uri -Body $body -Credential $credentials
  }
  catch {
    $time = Get-Date -format u
    Write-Host $time " - Failed to send message: " $message
  }
}