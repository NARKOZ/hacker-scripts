#!/bin/sh
#

# Exit early if no sessions with my username are found
if ! who | grep -wq $USER; then
	exit
fi

host=123.123.123.123
user=ass
passwd=ass
fifo=~/.fuck

[ -p "$fifo" ] || { rm -f $fifo; mkfifo $fifo; }
exec 5<>$fifo # open $fifo for both reading and writing on fd 5

telnet $host -l $user <&5 &

sleep 16

echo $passwd > $fifo
sleep 1
echo 'sys brew' > $fifo

sleep 24

echo 'sys pour' > $fifo
sleep 1
# telnet escape character ^] ascii is \035
echo -e "\035quit" > $fifo
