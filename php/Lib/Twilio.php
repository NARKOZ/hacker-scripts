<?php

/*
 * Author:   Neuman Vong neuman@twilio.com
 * License:  http://creativecommons.org/licenses/MIT/ MIT
 * Link:     https://twilio-php.readthedocs.org/en/latest/
 */

function Services_Twilio_autoload($className)
{
    if (substr($className, 0, 15) != 'Services_Twilio'
        && substr($className, 0, 26) != 'TaskRouter_Services_Twilio'
        && substr($className, 0, 23) != 'Lookups_Services_Twilio'
        && substr($className, 0, 23) != 'Monitor_Services_Twilio'
        && substr($className, 0, 23) != 'Pricing_Services_Twilio') {
        return false;
    }
    $file = str_replace('_', '/', $className);
    $file = str_replace('Services/', '', $file);
    return include dirname(__FILE__) . "/$file.php";
}

spl_autoload_register('Services_Twilio_autoload');

/**
 * Base client class
 */
abstract class Base_Services_Twilio extends Services_Twilio_Resource
{
    const USER_AGENT = 'twilio-php/4.6.1';

    protected $http;
    protected $last_response;
    protected $retryAttempts;
    protected $version;
    protected $versions = array('2010-04-01');

    public function __construct(
        $sid,
        $token,
        $version = null,
        Services_Twilio_TinyHttp $_http = null,
        $retryAttempts = 1
    ) {
        $this->version = in_array($version, $this->versions) ? $version : end($this->versions);

        if (null === $_http) {
            if (!in_array('openssl', get_loaded_extensions())) {
                throw new Services_Twilio_HttpException("The OpenSSL extension is required but not currently enabled. For more information, see http://php.net/manual/en/book.openssl.php");
            }
            if (in_array('curl', get_loaded_extensions())) {
                $_http = new Services_Twilio_TinyHttp(
                    $this->_getBaseUri(),
                    array(
                        "curlopts" => array(
                            CURLOPT_USERAGENT => self::qualifiedUserAgent(phpversion()),
                            CURLOPT_HTTPHEADER => array('Accept-Charset: utf-8'),
                        ),
                    )
                );
            } else {
                $_http = new Services_Twilio_HttpStream(
                    $this->_getBaseUri(),
                    array(
                        "http_options" => array(
                            "http" => array(
                                "user_agent" => self::qualifiedUserAgent(phpversion()),
                                "header" => "Accept-Charset: utf-8\r\n",
                            ),
                            "ssl" => array(
                                'verify_peer' => true,
                                'verify_depth' => 5,
                            ),
                        ),
                    )
                );
            }
        }
        $_http->authenticate($sid, $token);
        $this->http = $_http;
        $this->retryAttempts = $retryAttempts;
    }

    /**
     * Build a query string from query data
     *
     * :param array $queryData: An associative array of keys and values. The
     *      values can be a simple type or a list, in which case the list is
     *      converted to multiple query parameters with the same key.
     * :param string $numericPrefix: optional prefix to prepend to numeric keys
     * :return: The encoded query string
     * :rtype: string
     */
    public static function buildQuery($queryData, $numericPrefix = '') {
        $query = '';
        // Loop through all of the $query_data
        foreach ($queryData as $key => $value) {
            // If the key is an int, add the numeric_prefix to the beginning
            if (is_int($key)) {
                $key = $numericPrefix . $key;
            }

            // If the value is an array, we will end up recursing
            if (is_array($value)) {
                // Loop through the values
                foreach ($value as $value2) {
                    // Add an arg_separator if needed
                    if ($query !== '') {
                        $query .= '&';
                    }
                    // Recurse
                    $query .= self::buildQuery(array($key => $value2), $numericPrefix);
                }
            } else {
                // Add an arg_separator if needed
                if ($query !== '') {
                    $query .= '&';
                }
                // Add the key and the urlencoded value (as a string)
                $query .= $key . '=' . urlencode((string)$value);
            }
        }
        return $query;
    }

    /**
     * Construct a URI based on initial path, query params, and paging
     * information
     *
     * We want to use the query params, unless we have a next_page_uri from the
     * API.
     *
     * :param string $path: The request path (may contain query params if it's
     *      a next_page_uri)
     * :param array $params: Query parameters to use with the request
     * :param boolean $full_uri: Whether the $path contains the full uri
     *
     * :return: the URI that should be requested by the library
     * :returntype: string
     */
    public function getRequestUri($path, $params, $full_uri = false)
    {
        $json_path = $full_uri ? $path : "$path.json";
        if (!$full_uri && !empty($params)) {
            $query_path = $json_path . '?' . http_build_query($params, '', '&');
        } else {
            $query_path = $json_path;
        }
        return $query_path;
    }

    /**
     * Fully qualified user agent with the current PHP Version.
     *
     * :return: the user agent
     * :rtype: string
     */
    public static function qualifiedUserAgent($php_version) {
        return self::USER_AGENT . " (php $php_version)";
    }

    /**
     * POST to the resource at the specified path.
     *
     * :param string $path:   Path to the resource
     * :param array  $params: Query string parameters
     *
     * :return: The object representation of the resource
     * :rtype: object
     */
    public function createData($path, $params = array(), $full_uri = false)
    {
		if (!$full_uri) {
			$path = "$path.json";
		}
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $response = $this->http->post(
            $path, $headers, self::buildQuery($params, '')
        );
        return $this->_processResponse($response);
    }

    /**
     * DELETE the resource at the specified path.
     *
     * :param string $path:   Path to the resource
     * :param array  $params: Query string parameters
     *
     * :return: The object representation of the resource
     * :rtype: object
     */
    public function deleteData($path, $params = array())
    {
        $uri = $this->getRequestUri($path, $params);
        return $this->_makeIdempotentRequest(array($this->http, 'delete'),
            $uri, $this->retryAttempts);
    }

    /**
     * Get the retry attempt limit used by the rest client
     *
     * :return: the number of retry attempts
     * :rtype: int
     */
    public function getRetryAttempts() {
        return $this->retryAttempts;
    }

    /**
     * Get the api version used by the rest client
     *
     * :return: the API version in use
     * :returntype: string
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * GET the resource at the specified path.
     *
     * :param string $path:   Path to the resource
     * :param array  $params: Query string parameters
     * :param boolean  $full_uri: Whether the full URI has been passed as an
     *      argument
     *
     * :return: The object representation of the resource
     * :rtype: object
     */
    public function retrieveData($path, $params = array(),
                                 $full_uri = false
    )
    {
        $uri = $this->getRequestUri($path, $params, $full_uri);
        return $this->_makeIdempotentRequest(array($this->http, 'get'),
            $uri, $this->retryAttempts);
    }

    /**
     * Get the base URI for this client.
     *
     * :return: base URI
     * :rtype: string
     */
    protected function _getBaseUri() {
        return 'https://api.twilio.com';
    }

    /**
     * Helper method for implementing request retry logic
     *
     * :param array  $callable:      The function that makes an HTTP request
     * :param string $uri:           The URI to request
     * :param int    $retriesLeft:   Number of times to retry
     *
     * :return: The object representation of the resource
     * :rtype: object
     */
    protected function _makeIdempotentRequest($callable, $uri, $retriesLeft) {
        $response = call_user_func_array($callable, array($uri));
        list($status, $headers, $body) = $response;
        if ($status >= 500 && $retriesLeft > 0) {
            return $this->_makeIdempotentRequest($callable, $uri, $retriesLeft - 1);
        } else {
            return $this->_processResponse($response);
        }
    }

    /**
     * Convert the JSON encoded resource into a PHP object.
     *
     * :param array $response: 3-tuple containing status, headers, and body
     *
     * :return: PHP object decoded from JSON
     * :rtype: object
     * :throws: A :php:class:`Services_Twilio_RestException` if the Response is
     *      in the 300-500 range of status codes.
     */
    private function _processResponse($response)
    {
        list($status, $headers, $body) = $response;
        if ($status === 204) {
            return true;
        }
        $decoded = json_decode($body);
        if ($decoded === null) {
            throw new Services_Twilio_RestException(
                $status,
                'Could not decode response body as JSON. ' .
                'This likely indicates a 500 server error'
            );
        }
        if (200 <= $status && $status < 300) {
            $this->last_response = $decoded;
            return $decoded;
        }
        throw new Services_Twilio_RestException(
            $status,
            isset($decoded->message) ? $decoded->message : '',
            isset($decoded->code) ? $decoded->code : null,
            isset($decoded->more_info) ? $decoded->more_info : null
        );
    }
}

/**
 * Create a client to talk to the Twilio Rest API.
 *
 *
 * :param string               $sid:      Your Account SID
 * :param string               $token:    Your Auth Token from `your dashboard
 *      <https://www.twilio.com/user/account>`_
 * :param string               $version:  API version to use
 * :param $_http:    A HTTP client for making requests.
 * :type $_http: :php:class:`Services_Twilio_TinyHttp`
 * :param int                  $retryAttempts:
 *      Number of times to retry failed requests. Currently only idempotent
 *      requests (GET's and DELETE's) are retried.
 *
 * Here's an example:
 *
 * .. code-block:: php
 *
 *      require('Services/Twilio.php');
 *      $client = new Services_Twilio('AC123', '456bef', null, null, 3);
 *      // Take some action with the client, etc.
 */
class Services_Twilio extends Base_Services_Twilio
{
    protected $versions = array('2008-08-01', '2010-04-01');

    public function __construct(
        $sid,
        $token,
        $version = null,
        Services_Twilio_TinyHttp $_http = null,
        $retryAttempts = 1
    )
    {
        parent::__construct($sid, $token, $version, $_http, $retryAttempts);

        $this->accounts = new Services_Twilio_Rest_Accounts($this, "/{$this->version}/Accounts");
        $this->account = $this->accounts->get($sid);
    }
}

/**
 * Create a client to talk to the Twilio TaskRouter API.
 *
 *
 * :param string               $sid:      Your Account SID
 * :param string               $token:    Your Auth Token from `your dashboard
 *      <https://www.twilio.com/user/account>`_
 * :param string               $workspaceSid:
 *      Workspace SID to work with
 * :param string               $version:  API version to use
 * :param $_http:    A HTTP client for making requests.
 * :type $_http: :php:class:`Services_Twilio_TinyHttp`
 * :param int                  $retryAttempts:
 *      Number of times to retry failed requests. Currently only idempotent
 *      requests (GET's and DELETE's) are retried.
 *
 * Here's an example:
 *
 * .. code-block:: php
 *
 *      require('Services/Twilio.php');
 *      $client = new TaskRouter_Services_Twilio('AC123', '456bef', null, null, 3);
 *      // Take some action with the client, etc.
 */
class TaskRouter_Services_Twilio extends Base_Services_Twilio
{
    protected $versions = array('v1');
    private $accountSid;

    public function __construct(
        $sid,
        $token,
        $workspaceSid,
        $version = null,
        Services_Twilio_TinyHttp $_http = null,
        $retryAttempts = 1
    )
    {
        parent::__construct($sid, $token, $version, $_http, $retryAttempts);

        $this->workspaces = new Services_Twilio_Rest_TaskRouter_Workspaces($this, "/{$this->version}/Workspaces");
        $this->workspace = $this->workspaces->get($workspaceSid);
        $this->accountSid = $sid;
    }

	/**
	 * Construct a URI based on initial path, query params, and paging
	 * information
	 *
	 * We want to use the query params, unless we have a next_page_uri from the
	 * API.
	 *
	 * :param string $path: The request path (may contain query params if it's
	 *      a next_page_uri)
	 * :param array $params: Query parameters to use with the request
	 * :param boolean $full_uri: Whether the $path contains the full uri
	 *
	 * :return: the URI that should be requested by the library
	 * :returntype: string
	 */
	public function getRequestUri($path, $params, $full_uri = false)
	{
		if (!$full_uri && !empty($params)) {
			$query_path = $path . '?' . http_build_query($params, '', '&');
		} else {
			$query_path = $path;
		}
		return $query_path;
	}

    public static function createWorkspace($sid, $token, $friendlyName, array $params = array(), Services_Twilio_TinyHttp $_http = null)
    {
        $taskrouterClient = new TaskRouter_Services_Twilio($sid, $token, null, null, $_http);
        return $taskrouterClient->workspaces->create($friendlyName, $params);
    }

    public function getTaskQueuesStatistics(array $params = array())
    {
        return $this->retrieveData("/{$this->version}/Workspaces/{$this->workspace->sid}/TaskQueues/Statistics", $params);
    }

    public function getTaskQueueStatistics($taskQueueSid, array $params = array())
    {
        return $this->retrieveData("/{$this->version}/Workspaces/{$this->workspace->sid}/TaskQueues/{$taskQueueSid}/Statistics", $params);
    }

    public function getWorkersStatistics(array $params = array())
    {
        return $this->retrieveData("/{$this->version}/Workspaces/{$this->workspace->sid}/Workers/Statistics", $params);
    }

    public function getWorkerStatistics($workerSid, array $params = array())
    {
        return $this->retrieveData("/{$this->version}/Workspaces/{$this->workspace->sid}/Workers/{$workerSid}/Statistics", $params);
    }

    public function getWorkflowStatistics($workflowSid, array $params = array())
    {
        return $this->retrieveData("/{$this->version}/Workspaces/{$this->workspace->sid}/Workflows/{$workflowSid}/Statistics", $params);
    }

    public function getWorkspaceStatistics(array $params = array())
    {
        return $this->retrieveData("/{$this->version}/Workspaces/{$this->workspace->sid}/Statistics", $params);
    }

    protected function _getBaseUri()
    {
        return 'https://taskrouter.twilio.com';
    }
}

/**
 * Create a client to talk to the Twilio Lookups API.
 *
 *
 * :param string               $sid:      Your Account SID
 * :param string               $token:    Your Auth Token from `your dashboard
 *      <https://www.twilio.com/user/account>`_
 * :param string               $version:  API version to use
 * :param $_http:    A HTTP client for making requests.
 * :type $_http: :php:class:`Services_Twilio_TinyHttp`
 * :param int                  $retryAttempts:
 *      Number of times to retry failed requests. Currently only idempotent
 *      requests (GET's and DELETE's) are retried.
 *
 * Here's an example:
 *
 * .. code-block:: php
 *
 *      require('Services/Twilio.php');
 *      $client = new Lookups_Services_Twilio('AC123', '456bef', null, null, 3);
 *      // Take some action with the client, etc.
 */
class Lookups_Services_Twilio extends Base_Services_Twilio
{
    protected $versions = array('v1');
    private $accountSid;

    public function __construct(
        $sid,
        $token,
        $version = null,
        Services_Twilio_TinyHttp $_http = null,
        $retryAttempts = 1
    )
    {
        parent::__construct($sid, $token, $version, $_http, $retryAttempts);

        $this->accountSid = $sid;
        $this->phone_numbers = new Services_Twilio_Rest_Lookups_PhoneNumbers($this, "/{$this->version}/PhoneNumbers");
    }

	/**
	 * Construct a URI based on initial path, query params, and paging
	 * information
	 *
	 * We want to use the query params, unless we have a next_page_uri from the
	 * API.
	 *
	 * :param string $path: The request path (may contain query params if it's
	 *      a next_page_uri)
	 * :param array $params: Query parameters to use with the request
	 * :param boolean $full_uri: Whether the $path contains the full uri
	 *
	 * :return: the URI that should be requested by the library
	 * :returntype: string
	 */
	public function getRequestUri($path, $params, $full_uri = false)
	{
		if (!$full_uri && !empty($params)) {
			$query_path = $path . '?' . http_build_query($params, '', '&');
		} else {
			$query_path = $path;
		}
		return $query_path;
	}

    /**
     * Get the base URI for this client.
     *
     * :return: base URI
     * :rtype: string
     */
    protected function _getBaseUri()
    {
        return 'https://lookups.twilio.com';
    }

}

/**
 * Create a client to talk to the Twilio Pricing API.
 *
 *
 * :param string               $sid:      Your Account SID
 * :param string               $token:    Your Auth Token from `your dashboard
 *      <https://www.twilio.com/user/account>`_
 * :param string               $version:  API version to use
 * :param $_http:    A HTTP client for making requests.
 * :type $_http: :php:class:`Services_Twilio_TinyHttp`
 * :param int                  $retryAttempts:
 *      Number of times to retry failed requests. Currently only idempotent
 *      requests (GET's and DELETE's) are retried.
 *
 * Here's an example:
 *
 * .. code-block:: php
 *
 *      require('Services/Twilio.php');
 *      $client = new Pricing_Services_Twilio('AC123', '456bef', null, null, 3);
 *      // Take some action with the client, etc.
 */
class Pricing_Services_Twilio extends Base_Services_Twilio
{
    protected $versions = array('v1');

    public function __construct(
        $sid,
        $token,
        $version = null,
        Services_Twilio_TinyHttp $_http = null,
        $retryAttempts = 1
    ) {
        parent::__construct($sid, $token, $version, $_http, $retryAttempts);

        $this->voiceCountries = new Services_Twilio_Rest_Pricing_VoiceCountries(
            $this, "/{$this->version}/Voice/Countries"
        );
        $this->voiceNumbers = new Services_Twilio_Rest_Pricing_VoiceNumbers(
            $this, "/{$this->version}/Voice/Numbers"
        );
        $this->phoneNumberCountries = new Services_Twilio_Rest_Pricing_PhoneNumberCountries(
            $this, "/{$this->version}/PhoneNumbers/Countries"
        );
        $this->messagingCountries = new Services_Twilio_Rest_Pricing_MessagingCountries(
            $this, "/{$this->version}/Messaging/Countries"
        );
    }

    /**
     * Construct a URI based on initial path, query params, and paging
     * information
     *
     * We want to use the query params, unless we have a next_page_uri from the
     * API.
     *
     * :param string $path: The request path (may contain query params if it's
     *      a next_page_uri)
     * :param array $params: Query parameters to use with the request
     * :param boolean $full_uri: Whether the $path contains the full uri
     *
     * :return: the URI that should be requested by the library
     * :returntype: string
     */
    public function getRequestUri($path, $params, $full_uri = false)
    {
        if (!$full_uri && !empty($params)) {
            $query_path = $path . '?' . http_build_query($params, '', '&');
        } else {
            $query_path = $path;
        }
        return $query_path;
    }

    protected function _getBaseUri() {
        return 'https://pricing.twilio.com';
    }

}

/**
 * Create a client to talk to the Twilio Monitor API.
 *
 *
 * :param string               $sid:      Your Account SID
 * :param string               $token:    Your Auth Token from `your dashboard
 *      <https://www.twilio.com/user/account>`_
 * :param string               $version:  API version to use
 * :param $_http:    A HTTP client for making requests.
 * :type $_http: :php:class:`Services_Twilio_TinyHttp`
 * :param int                  $retryAttempts:
 *      Number of times to retry failed requests. Currently only idempotent
 *      requests (GET's and DELETE's) are retried.
 *
 * Here's an example:
 *
 * .. code-block:: php
 *
 *      require('Services/Twilio.php');
 *      $client = new Monitor_Services_Twilio('AC123', '456bef', null, null, 3);
 *      // Take some action with the client, etc.
 */
class Monitor_Services_Twilio extends Base_Services_Twilio
{
    protected $versions = array('v1');

    public function __construct(
        $sid,
        $token,
        $version = null,
        Services_Twilio_TinyHttp $_http = null,
        $retryAttempts = 1
    )
    {
        parent::__construct($sid, $token, $version, $_http, $retryAttempts);

        $this->events = new Services_Twilio_Rest_Monitor_Events($this, "/{$this->version}/Events");
        $this->alerts = new Services_Twilio_Rest_Monitor_Alerts($this, "/{$this->version}/Alerts");
    }

    /**
     * Construct a URI based on initial path, query params, and paging
     * information
     *
     * We want to use the query params, unless we have a next_page_uri from the
     * API.
     *
     * :param string $path: The request path (may contain query params if it's
     *      a next_page_uri)
     * :param array $params: Query parameters to use with the request
     * :param boolean $full_uri: Whether the $path contains the full uri
     *
     * :return: the URI that should be requested by the library
     * :returntype: string
     */
    public function getRequestUri($path, $params, $full_uri = false)
    {
        if (!$full_uri && !empty($params)) {
            $query_path = $path . '?' . http_build_query($params, '', '&');
        } else {
            $query_path = $path;
        }
        return $query_path;
    }

    protected function _getBaseUri()
    {
        return 'https://monitor.twilio.com';
    }

}

/**
 * Create a client to talk to the Twilio SIP Trunking API.
 *
 *
 * :param string               $sid:      Your Account SID
 * :param string               $token:    Your Auth Token from `your dashboard
 *      <https://www.twilio.com/user/account>`_
 * :param string               $version:  API version to use
 * :param $_http:    A HTTP client for making requests.
 * :type $_http: :php:class:`Services_Twilio_TinyHttp`
 * :param int                  $retryAttempts:
 *      Number of times to retry failed requests. Currently only idempotent
 *      requests (GET's and DELETE's) are retried.
 *
 * Here's an example:
 *
 * .. code-block:: php
 *
 *      require('Services/Twilio.php');
 *      $client = new Trunking_Services_Twilio('AC123', '456bef', null, null, 3);
 *      // Take some action with the client, etc.
 */
class Trunking_Services_Twilio extends Base_Services_Twilio
{
    protected $versions = array('v1');

    public function __construct(
        $sid,
        $token,
        $version = null,
        Services_Twilio_TinyHttp $_http = null,
        $retryAttempts = 1
    )
    {
        parent::__construct($sid, $token, $version, $_http, $retryAttempts);

        $this->trunks = new Services_Twilio_Rest_Trunking_Trunks($this, "/{$this->version}/Trunks");
    }

    /**
     * Construct a URI based on initial path, query params, and paging
     * information
     *
     * We want to use the query params, unless we have a next_page_uri from the
     * API.
     *
     * :param string $path: The request path (may contain query params if it's
     *      a next_page_uri)
     * :param array $params: Query parameters to use with the request
     * :param boolean $full_uri: Whether the $path contains the full uri
     *
     * :return: the URI that should be requested by the library
     * :returntype: string
     */
    public function getRequestUri($path, $params, $full_uri = false)
    {
        if (!$full_uri && !empty($params)) {
            $query_path = $path . '?' . http_build_query($params, '', '&');
        } else {
            $query_path = $path;
        }
        return $query_path;
    }

    protected function _getBaseUri()
    {
        return 'https://trunking.twilio.com';
    }

}
