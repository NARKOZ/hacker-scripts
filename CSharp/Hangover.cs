namespace Hacker_Scripts
{
    using System;
    using Twilio;
    using System.Linq;

    class Hangover
    {
        public static string TWILIO_ACCOUNT_SID = Environment.GetEnvironmentVariable("TWILIO_ACCOUNT_SID");
        public static string AUTH_TOKEN = Environment.GetEnvironmentVariable("TWILIO_AUTH_TOKEN");

        public static string YOUR_NUMBER = "9879789978";
        public static string BOSS_NUMBER = "3213213233";

        static void Main(string[] args)
        {
            var twilio = new TwilioRestClient(TWILIO_ACCOUNT_SID, AUTH_TOKEN);

            string[] randomMessages = {
                "Locked out",
                "Pipes broke",
                "Food poisoning",
                "Not feeling well"
            };

            int randomIndex = new Random().Next(randomMessages.Count());
            String messageToSend = (randomMessages[randomIndex]);

            var message = twilio.SendMessage(YOUR_NUMBER, BOSS_NUMBER, messageToSend);
            Console.WriteLine(message.Sid);
        }
    }
}

