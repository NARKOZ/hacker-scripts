# Hacker Scripts

Based on a _[true
story](https://www.jitbit.com/alexblog/249-now-thats-what-i-call-a-hacker/)_:

> xxx: YYY (our build engineer) got lured away (was hired by another company). The dude was literally living inside the terminal and Vim, drawing diagrams in Dot, writing docs in Markdown, if anything would require more than 1.5 minutes, he would write a script. Now sitting and analyzing his legacy. 

> xxx: From the awesome ones

> xxx: [`smack-my-bitch-up.sh`](https://github.com/NARKOZ/hacker-scripts/blob/master/smack-my-bitch-up.sh) - sends "late at work" apparently to his wife and picks excuses from a list. Is set up in a cron, shoots after 9pm if there are any active SSH-sessions on the workstation with his login.

> xxx: [`kumar-asshole.sh`](https://github.com/NARKOZ/hacker-scripts/blob/master/kumar-asshole.sh) - scans the inbox, searching for emails from "Kumar" (foreign DBA with a self explaining last name) with keywords (sorry, help, etc.) rolls back the mentioned staging database to the latest backup and replies like "no worries, be careful next time". Seems like Kumar really annoyed him.

> xxx: [`hangover.sh`](https://github.com/NARKOZ/hacker-scripts/blob/master/hangover.sh) - set in cron to specific dates, sends emails like "not feeling well, gonna work from home" again if there are no interactive sessions on the workstation at 8:45am.

> xxx: And finally, the grand prize: [`fucking-coffee.sh`](https://github.com/NARKOZ/hacker-scripts/blob/master/fucking-coffee.sh) - waits 17 seconds (!!!), logins via SSH to our coffee-machine (geez, we had no frikin idea it is in the network, and it has a sshd up and running) and sends some gibberish. By trial and error we found out that IT starts brewing a mid-sized half-caf latte, which starts puring into the cup exactly by the time when a leisurely walking man arrives from his desk to the coffee-machine.

Original: http://bash.im/quote/436725 (in Russian)  
Pull requests with other implementations (Python, Perl, Shell, etc) are welcome.

## Usage

You need these environment variables:

```sh
# used in `smack-my-bitch-up` and `hangover` scripts
TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_AUTH_TOKEN=yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy

# used in `kumar_asshole` script
GMAIL_USERNAME=admin@example.org
GMAIL_PASSWORD=password
```

For Ruby scripts you need to install gems:
`gem install dotenv twilio-ruby gmail`

## Cron jobs

```sh
# Runs `smack-my-bitch-up.sh` monday to friday at 9:20 pm.
20 21 * * 1-5 /path/to/scripts/smack-my-bitch-up.sh >> /path/to/smack-my-bitch-up.log 2>&1

# Runs `hangover.sh` monday to friday at 8:45 am.
45 8 * * 1-5 /path/to/scripts/hangover.sh >> /path/to/hangover.log 2>&1

# Runs `kumar-asshole.sh` every 10 minutes.
*/10 * * * * /path/to/scripts/kumar-asshole.sh

# Runs `fucking-coffee.sh` hourly from 9am to 6pm on weekdays.
0 9-18 * * 1-5 /path/to/scripts/fucking-coffee.sh
```

---
Code is released under WTFPL.
