<?php
include_once 'JWT.php';
/**
 * Twilio Capability Token generator
 *
 * @category Services
 * @package  Services_Twilio
 * @author Jeff Lindsay <jeff.lindsay@twilio.com>
 * @license  http://creativecommons.org/licenses/MIT/ MIT
 */
class Services_Twilio_Capability
{
    public $accountSid;
    public $authToken;
    public $scopes;

    /**
     * Create a new TwilioCapability with zero permissions. Next steps are to
     * grant access to resources by configuring this token through the
     * functions allowXXXX.
     *
     * @param $accountSid the account sid to which this token is granted access
     * @param $authToken the secret key used to sign the token. Note, this auth
     *        token is not visible to the user of the token.
     */
    public function __construct($accountSid, $authToken)
    {
        $this->accountSid = $accountSid;
        $this->authToken = $authToken;
        $this->scopes = array();
		$this->clientName = false;
    }

    /**
     * If the user of this token should be allowed to accept incoming
     * connections then configure the TwilioCapability through this method and
     * specify the client name.
     *
     * @param $clientName
     */
    public function allowClientIncoming($clientName)
    {

        // clientName must be a non-zero length alphanumeric string
        if (preg_match('/\W/', $clientName)) {
            throw new InvalidArgumentException(
                'Only alphanumeric characters allowed in client name.');
        }

        if (strlen($clientName) == 0) {
            throw new InvalidArgumentException(
                'Client name must not be a zero length string.');
        }

		$this->clientName = $clientName;
        $this->allow('client', 'incoming',
            array('clientName' => $clientName));
    }

    /**
     * Allow the user of this token to make outgoing connections.
     *
     * @param $appSid the application to which this token grants access
     * @param $appParams signed parameters that the user of this token cannot
     *        overwrite.
     */
    public function allowClientOutgoing($appSid, array $appParams=array())
    {
        $this->allow('client', 'outgoing', array(
            'appSid' => $appSid,
            'appParams' => http_build_query($appParams, '', '&')));
    }

    /**
     * Allow the user of this token to access their event stream.
     *
     * @param $filters key/value filters to apply to the event stream
     */
    public function allowEventStream(array $filters=array())
    {
        $this->allow('stream', 'subscribe', array(
            'path' => '/2010-04-01/Events',
            'params' => http_build_query($filters, '', '&'),
        ));
    }

    /**
     * Generates a new token based on the credentials and permissions that
     * previously has been granted to this token.
     *
     * @param $ttl the expiration time of the token (in seconds). Default
     *        value is 3600 (1hr)
     * @return the newly generated token that is valid for $ttl seconds
     */
    public function generateToken($ttl = 3600)
    {
        $payload = array(
            'scope' => array(),
            'iss' => $this->accountSid,
            'exp' => time() + $ttl,
        );
        $scopeStrings = array();

        foreach ($this->scopes as $scope) {
			if ($scope->privilege == "outgoing" && $this->clientName)
				$scope->params["clientName"] = $this->clientName;
            $scopeStrings[] = $scope->toString();
        }

        $payload['scope'] = implode(' ', $scopeStrings);
        return JWT::encode($payload, $this->authToken, 'HS256');
    }

    protected function allow($service, $privilege, $params) {
        $this->scopes[] = new ScopeURI($service, $privilege, $params);
    }
}

/**
 * Scope URI implementation
 *
 * Simple way to represent configurable privileges in an OAuth
 * friendly way. For our case, they look like this:
 *
 * scope:<service>:<privilege>?<params>
 *
 * For example:
 * scope:client:incoming?name=jonas
 *
 * @author Jeff Lindsay <jeff.lindsay@twilio.com>
 */
class ScopeURI
{
    public $service;
    public $privilege;
    public $params;

    public function __construct($service, $privilege, $params = array())
    {
        $this->service = $service;
        $this->privilege = $privilege;
        $this->params = $params;
    }

    public function toString()
    {
        $uri = "scope:{$this->service}:{$this->privilege}";
        if (count($this->params)) {
            $uri .= "?".http_build_query($this->params, '', '&');
        }
        return $uri;
    }

    /**
     * Parse a scope URI into a ScopeURI object
     *
     * @param string    $uri  The scope URI
     * @return ScopeURI The parsed scope uri
     */
    public static function parse($uri)
    {
        if (strpos($uri, 'scope:') !== 0) {
            throw new UnexpectedValueException(
                'Not a scope URI according to scheme');
        }

        $parts = explode('?', $uri, 1);
        $params = null;

        if (count($parts) > 1) {
            parse_str($parts[1], $params);
        }

        $parts = explode(':', $parts[0], 2);

        if (count($parts) != 3) {
            throw new UnexpectedValueException(
                'Not enough parts for scope URI');
        }

        list($scheme, $service, $privilege) = $parts;
        return new ScopeURI($service, $privilege, $params);
    }

}