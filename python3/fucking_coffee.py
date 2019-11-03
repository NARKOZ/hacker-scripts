#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import telnetlib
import time

from hackerutils import sh

COFFEE_MACHINE_ADDR = 'REPLACE WITH YOUR IP ADDRESS HERE'
COFFEE_MACHINE_PASS = 'REPLACE WITH YOUR PASSWORD HERE'
COFFEE_MACHINE_PROM = 'Password: '


def main():
    # Exit early if no sessions with my_username are found.
    if not any(s.startswith(b'my_username ') for s in sh('who').split(b'\n')):
        return

    time.sleep(17)

    conn = telnetlib.Telnet(host=COFFEE_MACHINE_ADDR)
    conn.open()
    conn.expect([COFFEE_MACHINE_PROM])
    conn.write(COFFEE_MACHINE_PASS)

    conn.write('sys brew')
    time.sleep(64)

    conn.write('sys pour')
    conn.close()


if __name__ == '__main__':
    main()
