#!/bin/sh
#

# Exit early if any session with my username is found
if ! who | grep -wq $USER; then
	exit
fi

host=123.123.123.123
user=ass
passwd=ass
fifo=~/.fuck

[ -p "$fifo" ] || { rm -f $fifo; mkfifo $fifo; }
exec 5<>$fifo

telnet $host -l $user <&5 &

sleep 16

echo $passwd > $fifo
sleep 1
echo 'sys brew' > $fifo

sleep 24

echo 'sys pour' > $fifo
sleep 1
echo -e "\035quit" > $fifo
