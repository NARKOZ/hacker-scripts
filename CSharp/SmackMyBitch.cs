using Twilio;
using Twilio.Rest.Api.V2010.Account;

//Exit early if any session with my username is found
if (args[0] is null)
{
    return;
}

var twilioAccountSid = Environment.GetEnvironmentVariable("TWILIO_ACCOUNT_SID");
var authToken = Environment.GetEnvironmentVariable("TWILIO_AUTH_TOKEN");

//Phone numbers
const string myNumber = "+xxx";
const string herNumber = "+xxx";

TwilioClient.Init(twilioAccountSid, authToken);

string[] reasons = {
    "Working hard",
    "Gotta ship this feature",
    "Someone fucked the system again"
};

var randomNumber = new Random().Next(reasons.Length);
var reason = reasons[randomNumber];
var message = $"Late at work. {reason}";

//Send a text message
MessageResource.Create(
    body: message,
    from: new Twilio.Types.PhoneNumber(myNumber),
    to: new Twilio.Types.PhoneNumber(herNumber)
);

//Log this
Console.WriteLine($@"Message sent at: #{DateTime.Now} | Reason: #{reason}");
