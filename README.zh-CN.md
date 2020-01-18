# Hacker Scripts

根据 *[真实故事](https://www.jitbit.com/alexblog/249-now-thats-what-i-call-a-hacker/)*  改编:

> xxx: 是这样的，我们的构建工程师离职去了另外一家公司，这货基本算是生活在终端里。 你知道么，这人热爱Vim，用Dot作图，甚至用MarkDown来写维基帖子...，如果有什么事情要花上他超过90秒，她一定会整个脚本来让这件事变得“自动化”。

> xxx: 我们现在坐在他的工位上，看着他留下来的这些，呃，“遗产”？

> xxx: 我觉得你们会喜欢这些的

> xxx: [`smack-my-bitch-up.sh(拍老婆马屁脚本)`](https://github.com/NARKOZ/hacker-scripts/blob/master/smack-my-bitch-up.sh) - 它会给他的老婆（很明显是他老婆）发送一条“今晚要加班了”的短信，再自动从文本库中随机地选择一条理由。这个脚本被设置为定时触发，而且只有在工作日晚9点以后，服务器上还有他登陆的SSH进程在运行时才会执行。

> xxx: [`kumar-asshole.sh（库马尔个傻*）`](https://github.com/NARKOZ/hacker-scripts/blob/master/kumar-asshole.sh) - 这个脚本会自动扫描邮箱，如果发现其中有库马尔（库马尔是我们客户公司的一位数据库管理员）发来的邮件，就会在其中寻找关键字如“求助”，“遇到麻烦了”，“抱歉”等等，如果发现了这些关键字，这个脚本会通过SSH连接上客户公司的服务器，把中间数据库（staging database）回滚到最新一次的可用备份。然后它会给邮件发送回复，“没事了哥们，下次小心点哈”。

> xxx: [`hangover.sh（宿醉）`](https://github.com/NARKOZ/hacker-scripts/blob/master/hangover.sh) - 同样是个定时执行的任务，被设置为在特定日期触发，它会自动发送诸如“今天不太舒服”或“今天我在家上班”之类的邮件，同样会从文本库里随机选取一条理由。这个任务会在工作日清晨8点45分以后服务器上仍然没有可交互的SSH进程时真正执行。

> xxx: (最牛的就是接下来这个) [`fucking-coffee.sh（**的咖啡）`](https://github.com/NARKOZ/hacker-scripts/blob/master/fucking-coffee.sh) - 这个脚本在启动之后，会先等待恰好17秒（！），然后启动一个登录进程连接到我们的咖啡机（淦，我们之前完全不知道我们的咖啡机是联网的，上面还运行着Linux系统，甚至还跑着TCP socket连接！），然后它会发送类似“系统！开始煮咖啡！”之类的消息，结果这条消息会让咖啡机开始工作，煮一杯 中杯大小、半咖啡因的拿铁，再等待恰好24秒（！）后，才倒进咖啡杯里。这些时间加起来刚好就是这位程序员从自己的工位走到机器前要的时间。

> xxx: 天了噜我要把这些保留下来。

原文: http://bash.im/quote/436725 (俄语)

欢迎使用其它语言来实现 (Python, Perl, Shell等等)并提交PR。

## 用法

你需要以下这些环境变量：

```bash
# used in `smack-my-bitch-up` and `hangover` scripts
TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_AUTH_TOKEN=yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy

# used in `kumar_asshole` script
GMAIL_USERNAME=admin@example.org
GMAIL_PASSWORD=password
```

为了执行Ruby脚本，你需要安装gems: `gem install dotenv twilio-ruby gmail`

## 定时任务

```bash
# Runs `smack-my-bitch-up.sh` monday to friday at 9:20 pm.
20 21 * * 1-5 /path/to/scripts/smack-my-bitch-up.sh >> /path/to/smack-my-bitch-up.log 2>&1

# Runs `hangover.sh` monday to friday at 8:45 am.
45 8 * * 1-5 /path/to/scripts/hangover.sh >> /path/to/hangover.log 2>&1

# Runs `kumar-asshole.sh` every 10 minutes.
*/10 * * * * /path/to/scripts/kumar-asshole.sh

# Runs `fucking-coffee.sh` hourly from 9am to 6pm on weekdays.
0 9-18 * * 1-5 /path/to/scripts/fucking-coffee.sh
```

------

代码的使用遵循WTFPL（Do What The Fuck You Want To Public License）协议。