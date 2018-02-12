using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Twilio;

namespace Hacker_Scripts
{
    class SmackMyBitch
    {
        public static string TWILIO_ACCOUNT_SID = Environment.GetEnvironmentVariable("TWILIO_ACCOUNT_SID");
        public static string AUTH_TOKEN = Environment.GetEnvironmentVariable("TWILIO_AUTH_TOKEN");

        public static string YOUR_NUMBER = "9879789978";
        public static string HER_NUMBER = "3213213233";

        static void Main(string[] args)
        {
            var twilio = new TwilioRestClient(TWILIO_ACCOUNT_SID, AUTH_TOKEN);

            string[] randomMessages = {
                "Working hard",
                "Gotta ship this feature",
                "Someone fucked the system again"
            };

            int randomIndex = new Random().Next(randomMessages.Count());
            String messageToSend = (randomMessages[randomIndex]);

            var message = twilio.SendMessage(YOUR_NUMBER, HER_NUMBER, messageToSend);
            Console.WriteLine(message.Sid);
        }
    }
}
