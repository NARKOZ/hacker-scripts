# Hacker Scripts

Based on a _[true
story](https://www.jitbit.com/alexblog/249-now-thats-what-i-call-a-hacker/)_:

> xxx: OK, so, our build engineer has left for another company. The dude was literally living inside the terminal. You know, that type of a guy who loves Vim, creates diagrams in Dot and writes wiki-posts in Markdown... If something - anything - requires more than 90 seconds of his time, he writes a script to automate that.

> xxx: So we're sitting here, looking through his, uhm, "legacy"

> xxx: You're gonna love this

> xxx: [`smack-my-bitch-up.sh`](https://github.com/NARKOZ/hacker-scripts/blob/master/smack-my-bitch-up.sh) - sends a text message "late at work" to his wife (apparently). Automatically picks reasons from an array of strings, randomly. Runs inside a cron-job. The job fires if there are active SSH-sessions on the server after 9pm with his login.

> xxx: `kumar-asshole.sh` - scans the inbox for emails from "Kumar" (a DBA at our clients). Looks for keywords like "help", "trouble", "sorry" etc. If keywords are found - the script SSHes into the clients server and rolls back the staging database to the latest backup. Then sends a reply "no worries mate, be careful next time".

> xxx: [`hangover.sh`](https://github.com/NARKOZ/hacker-scripts/blob/master/hangover.sh) - another cron-job that is set to specific dates. Sends automated emails like "not feeling well/gonna work from home" etc. Adds a random "reason" from another predefined array of strings. Fires if there are no interactive sessions on the server at 8:45am.

> xxx: (and the oscar goes to) `fucking-coffee.sh` - this one waits exactly 17 seconds (!), then opens an SSH session to our coffee-machine (we had no frikin idea the coffee machine is on the network, runs linux and has SSHD up and running) and sends some weird gibberish to it. Looks binary. Turns out this thing starts brewing a mid-sized half-caf latte and waits another 24 (!) seconds before pouring it into a cup. The timing is exactly how long it takes to walk to the machine from the dudes desk.

> xxx: holy sh*t I'm keeping those

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
`gem install dotenv twilio gmail whenever`

## Cron jobs

```sh
# Runs `smack_my_bitch_up` daily at 9:20 pm.
20 21 * * * /bin/bash -l -c 'ruby smack_my_bitch_up.rb'

# Runs `hangover` daily at 8:45 am.
45 8 * * * /bin/bash -l -c 'ruby hangover.rb'

# Runs `kumar_asshole` every 10 minutes.
0,10,20,30,40,50 * * * * /bin/bash -l -c 'ruby kumar_asshole.rb'

# Runs `fucking_coffee` hourly from 9am to 6pm.
0 9,10,11,12,13,14,15,16,17,18 * * * /bin/bash -l -c 'ruby fucking_coffee.rb'

# Runs `hangover.sh` daily at 8:45am with logfile output.
45 8 * * * /bin/bash -l -c '/path/to/scripts/hangover.sh >> /path/to/hangover.log 2>&1'
```

Check `config/schedule.rb` file.

---
Code is released under WTFPL.
