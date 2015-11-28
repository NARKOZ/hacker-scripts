<?php
/**
 * HTTP Stream version of the TinyHttp Client used to connect to Twilio
 * services.
 */

class Services_Twilio_HttpStreamException extends ErrorException {}

class Services_Twilio_HttpStream {

    private $auth_header = null;
    private $uri = null;
    private $debug = false;
    private static $default_options = array(
        "http" => array(
            "headers" => "",
            "timeout" => 60,
            "follow_location" => true,
            "ignore_errors" => true,
        ),
        "ssl" => array(),
    );
    private $options = array();

    public function __construct($uri = '', $kwargs = array()) {
        $this->uri = $uri;
        if (isset($kwargs['debug'])) {
            $this->debug = true;
        }
        if (isset($kwargs['http_options'])) {
            $this->options = $kwargs['http_options'] + self::$default_options;
        } else {
            $this->options = self::$default_options;
        }
    }

    public function __call($name, $args) {
        list($res, $req_headers, $req_body) = $args + array(0, array(), '');

		if (strpos($res, 'http') === 0) {
			$url = $res;
		} else {
			$url = $this->uri . $res;
		}

        $request_options = $this->options;

        if (isset($req_body) && strlen($req_body) > 0) {
            $request_options['http']['content'] = $req_body;
        }

        foreach($req_headers as $key => $value) {
            $request_options['http']['header'] .= sprintf("%s: %s\r\n", $key, $value);
        }

        if (isset($this->auth_header)) {
            $request_options['http']['header'] .= $this->auth_header;
        }

        $request_options['http']['method'] = strtoupper($name);
        $request_options['http']['ignore_errors'] = true;

        if ($this->debug) {
            error_log(var_export($request_options, true));
        }
        $ctx = stream_context_create($request_options);
        $result = file_get_contents($url, false, $ctx);

        if (false === $result) {
            throw new Services_Twilio_HttpStreamException(
                "Unable to connect to service");
        }

        $status_header = array_shift($http_response_header);
        if (1 !== preg_match('#HTTP/\d+\.\d+ (\d+)#', $status_header, $matches)) {
            throw new Services_Twilio_HttpStreamException(
                "Unable to detect the status code in the HTTP result.");
        }

        $status_code = intval($matches[1]);
        $response_headers = array();

        foreach($http_response_header as $header) {
            list($key, $val) = explode(":", $header);
            $response_headers[trim($key)] = trim($val);
        }

        return array($status_code, $response_headers, $result);
    }

    public function authenticate($user, $pass) {
        if (isset($user) && isset($pass)) {
            $this->auth_header = sprintf("Authorization: Basic %s",
                base64_encode(sprintf("%s:%s", $user, $pass)));
        } else {
            $this->auth_header = null;
        }
    }
}
