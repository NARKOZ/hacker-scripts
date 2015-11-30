#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

use Bestnetwork\Telnet\TelnetClient;

(strpos(exec('who'), getenv('USER')) !== false) or exit('no session');
sleep(17);
$con = new TelnetClient('10.10.42.42');
$con->execute('1234', 'Password: ');
$con->execute('sys brew');
sleep(24);
$con->execute('sys pour');
