<?php
namespace HackerScripts\Lib;

/**
 * Telnet class
 *
 * Used to execute remote commands via telnet connection
 * Usess sockets functions and fgetc() to process result
 *
 * All methods throw Exceptions on error
 *
 * Written by Dalibor Andzakovic <dali@swerve.co.nz>
 * Based on the code originally written by Marc Ennaji and extended by
 * Matthias Blaser <mb@adfinis.ch>
 *
 * Extended by Christian Hammers <chammers@netcologne.de>
 * Modified by Frederik Sauer <fsa@dwarf.dk>
 *
 */

class Telnet {

    private $host;
    private $port;
    private $timeout;
    private $stream_timeout_sec;
    private $stream_timeout_usec;

    private $socket  = NULL;
    private $buffer = NULL;
    private $prompt;
    private $errno;
    private $errstr;
    private $strip_prompt = TRUE;

    private $NULL;
    private $DC1;
    private $WILL;
    private $WONT;
    private $DO;
    private $DONT;
    private $IAC;

    private $global_buffer = '';

    const TELNET_ERROR = FALSE;
    const TELNET_OK = TRUE;

    public function __construct($host = '127.0.0.1', $port = '23', $timeout = 10, $prompt = '$', $stream_timeout = 1) {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->setPrompt($prompt);
        $this->setStreamTimeout($stream_timeout);

        // set some telnet special characters
        $this->NULL = chr(0);
        $this->DC1 = chr(17);
        $this->WILL = chr(251);
        $this->WONT = chr(252);
        $this->DO = chr(253);
        $this->DONT = chr(254);
        $this->IAC = chr(255);

        $this->connect();
    }

    public function __destruct() {
        // clean up resources
        $this->disconnect();
        $this->buffer = NULL;
        $this->global_buffer = NULL;
    }

    public function connect() {
        // check if we need to convert host to IP
        if (!preg_match('/([0-9]{1,3}\\.){3,3}[0-9]{1,3}/', $this->host)) {
            $ip = gethostbyname($this->host);

            if ($this->host == $ip) {
                throw new Exception("Cannot resolve $this->host");
            } else {
                $this->host = $ip;
            }
        }

        // attempt connection - suppress warnings
        $this->socket = @fsockopen($this->host, $this->port, $this->errno, $this->errstr, $this->timeout);

        if (!$this->socket) {
            throw new Exception("Cannot connect to $this->host on port $this->port");
        }

        if (!empty($this->prompt)) {
            $this->waitPrompt();
        }

        return self::TELNET_OK;
    }

    public function disconnect() {
        if ($this->socket) {
            if (! fclose($this->socket)) {
                throw new Exception("Error while closing telnet socket");
            }
            $this->socket = NULL;
        }
        return self::TELNET_OK;
    }

    public function exec($command, $add_newline = TRUE) {
        $this->write($command, $add_newline);
        $this->waitPrompt();
        return $this->getBuffer();
    }

    public function login($username, $password) {
        try {
            $this->setPrompt('login:');
            $this->waitPrompt();
            $this->write($username);
            $this->setPrompt('Password:');
            $this->waitPrompt();
            $this->write($password);
            $this->setPrompt();
            $this->waitPrompt();
        } catch (Exception $e) {
            throw new Exception("Login failed.");
        }

        return self::TELNET_OK;
    }

    public function setPrompt($str = '$') {
        return $this->setRegexPrompt(preg_quote($str, '/'));
    }

    public function setRegexPrompt($str = '\$') {
        $this->prompt = $str;
        return self::TELNET_OK;
    }

    public function setStreamTimeout($timeout) {
        $this->stream_timeout_usec = (int)(fmod($timeout, 1) * 1000000);
        $this->stream_timeout_sec = (int)$timeout;
    }

    public function stripPromptFromBuffer($strip) {
        $this->strip_prompt = $strip;
    } // function stripPromptFromBuffer

    protected function getc() {
        stream_set_timeout($this->socket, $this->stream_timeout_sec, $this->stream_timeout_usec);
        $c = fgetc($this->socket);
        $this->global_buffer .= $c;
        return $c;
    }

    public function clearBuffer() {
        $this->buffer = '';
    }

    public function readTo($prompt) {
        if (!$this->socket) {
            throw new Exception("Telnet connection closed");
        }

        // clear the buffer
        $this->clearBuffer();

        $until_t = time() + $this->timeout;
        do {
            // time's up (loop can be exited at end or through continue!)
            if (time() > $until_t) {
                throw new Exception("Couldn't find the requested : '$prompt' within {$this->timeout} seconds");
            }

            $c = $this->getc();

            if ($c === FALSE) {
                if (empty($prompt)) {
                    return self::TELNET_OK;
                }
                throw new Exception("Couldn't find the requested : '" . $prompt . "', it was not in the data returned from server: " . $this->buffer);
            }

            // Interpreted As Command
            if ($c == $this->IAC) {
                if ($this->negotiateTelnetOptions()) {
                    continue;
                }
            }

            // append current char to global buffer
            $this->buffer .= $c;

            // we've encountered the prompt. Break out of the loop
            if (!empty($prompt) && preg_match("/{$prompt}$/", $this->buffer)) {
                return self::TELNET_OK;
            }

        } while ($c != $this->NULL || $c != $this->DC1);
    }

    public function write($buffer, $add_newline = TRUE) {
        if (!$this->socket) {
            throw new Exception("Telnet connection closed");
        }

        // clear buffer from last command
        $this->clearBuffer();

        if ($add_newline == TRUE) {
            $buffer .= "\n";
        }

        $this->global_buffer .= $buffer;
        if (!fwrite($this->socket, $buffer) < 0) {
            throw new Exception("Error writing to socket");
        }

        return self::TELNET_OK;
    }

    protected function getBuffer() {
        // Remove all carriage returns from line breaks
        $buf =  preg_replace('/\r\n|\r/', "\n", $this->buffer);
        // Cut last line from buffer (almost always prompt)
        if ($this->strip_prompt) {
            $buf = explode("\n", $buf);
            unset($buf[count($buf) - 1]);
            $buf = implode("\n", $buf);
        }
        return trim($buf);
    }

    public function getGlobalBuffer() {
        return $this->global_buffer;
    }

    protected function negotiateTelnetOptions() {
        $c = $this->getc();

        if ($c != $this->IAC) {
            if (($c == $this->DO) || ($c == $this->DONT)) {
                $opt = $this->getc();
                fwrite($this->socket, $this->IAC . $this->WONT . $opt);
            } else if (($c == $this->WILL) || ($c == $this->WONT)) {
                $opt = $this->getc();
                fwrite($this->socket, $this->IAC . $this->DONT . $opt);
            } else {
                throw new Exception('Error: unknown control character ' . ord($c));
            }
        } else {
            throw new Exception('Error: Something Wicked Happened');
        }

        return self::TELNET_OK;
    }

    protected function waitPrompt() {
        return $this->readTo($this->prompt);
    }
}