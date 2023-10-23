[English](./README.md) | [简体中文](./README.zh-CN.md) | Português

# Scripts de Hacker

Baseado numa _[história real](https://www.jitbit.com/alexblog/249-now-thats-what-i-call-a-hacker/)_:

> xxx: Ok, então, nosso engenheiro de construção se mudou para outra empresa. O cara vivia literalmente dentro do terminal. Sabe, aquele tipo de pessoa que ama o Vim, cria diagramas em Dot e escreve postagens de wiki em Markdown... Se algo - qualquer coisa - requer mais do que 90 segundos do tempo dele, ele escreve um script para automatizar isso.

> xxx: Então estamos aqui, revendo a sua, hmm, "herança"

> xxx: Você vai adorar isso.

> xxx: [`smack-my-bitch-up.sh`](https://github.com/NARKOZ/hacker-scripts/blob/master/smack-my-bitch-up.sh) - envia uma mensagem de texto "trabalhando até tarde" para a esposa dele (aparentemente). Escolhe automaticamente razões de uma matriz de strings, de forma aleatória. Funciona dentro de uma tarefa cron. A tarefa é acionada se houver sessões ativas de SSH no servidor após as 21h com o login dele.

> xxx: [`kumar-asshole.sh`](https://github.com/NARKOZ/hacker-scripts/blob/master/kumar-asshole.sh) - varre a caixa de entrada em busca de e-mails de "Kumar" (um DBA em nossos clientes). Procura por palavras-chave como "ajuda", "problema", "desculpa", etc. Se as palavras-chave forem encontradas, o script faz uma conexão SSH com o servidor do cliente e restaura o banco de dados de preparação para o último backup. Em seguida, envia uma resposta "sem problemas, amigo, tenha mais cuidado da próxima vez".

> xxx: [`hangover.sh`](https://github.com/NARKOZ/hacker-scripts/blob/master/hangover.sh) - outra tarefa cron programada para datas específicas. Envia e-mails automáticos como "não me sinto bem/vou trabalhar de casa", etc. Adiciona uma "razão" aleatória de outra matriz predefinida de strings. A tarefa é acionada se não houver sessões interativas no servidor às 8h45 da manhã.

> xxx: (e o oscar vai para) [`fucking-coffee.sh`](https://github.com/NARKOZ/hacker-scripts/blob/master/fucking-coffee.sh) - Este script aguarda exatamente 17 segundos (!), em seguida, abre uma sessão Telnet para a nossa máquina de café (não tínhamos a menor ideia de que a máquina de café estava na rede, rodava Linux e tinha um soquete TCP funcionando) e envia algo como `sys brew`. Acontece que essa coisa começa a preparar um café latte de tamanho médio com metade da cafeína e espera mais 24 segundos (!) antes de despejá-lo em uma xícara. O cronograma é exatamente o tempo que leva para ir da mesa do cara até a máquina.

> xxx: puta m*rda vou manter esses

Original: http://bash.im/quote/436725 (em Russo)  (Archive.org [link](https://web.archive.org/web/20210226092253/http://bash.im/quote/436725))
Pull requests com outras implementações (Python, Perl, Shell, etc) são bem-vindos.

## Uso

Você precisa dessas variáveis de ambiente:

```sh
# usado nos scripts `smack-my-bitch-up` e `hangover`
TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_AUTH_TOKEN=yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy

# usado no script `kumar_asshole`
GMAIL_USERNAME=admin@example.org
GMAIL_PASSWORD=password
```

Para scripts em Ruby você precisa instalar o gems:
`gem install dotenv twilio-ruby gmail`

## Jobs Cron

```sh
# Executa o arquivo `smack-my-bitch-up.sh` de segunda a sexta-feira às 21h20.
20 21 * * 1-5 /path/to/scripts/smack-my-bitch-up.sh >> /path/to/smack-my-bitch-up.log 2>&1

# Executa o arquivo `hangover.sh` segunda a sexta-feira às 8h45 da manhã.
45 8 * * 1-5 /path/to/scripts/hangover.sh >> /path/to/hangover.log 2>&1

# Executa o arquivo `kumar-asshole.sh` a cada 10 minutos.
*/10 * * * * /path/to/scripts/kumar-asshole.sh

# Executa o arquivo `fucking-coffee.sh` de hora em hora das 9h às 18h nos dias úteis.
0 9-18 * * 1-5 /path/to/scripts/fucking-coffee.sh
```

---
O código é disponibilizado sob a licença WTFPL.
