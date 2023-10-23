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
const string numberOfBoss = "+xxx";

TwilioClient.Init(twilioAccountSid, authToken);

var excuses = await new ChatGpt().GetExcusesToMyBoss();

var rand = new Random().Next(excuses.Length);
var message = $"Gonna work from home. {excuses[rand]}";

//Send a text message
var response = MessageResource.Create(
    body: message,
    from: new Twilio.Types.PhoneNumber(myNumber),
    to: new Twilio.Types.PhoneNumber(numberOfBoss)
);

Console.WriteLine(response.Sid);
