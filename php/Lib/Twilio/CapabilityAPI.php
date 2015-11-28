<?php
include_once 'JWT.php';
/**
 * Twilio API Capability Token generator
 *
 * @category Services
 * @package  Services_Twilio
 * @author Justin Witz <justin.witz@twilio.com>
 * @license  http://creativecommons.org/licenses/MIT/ MIT
 */
class Services_Twilio_API_Capability
{
	protected $accountSid;
	protected $authToken;
	private $version;
	private $friendlyName;
	private $policies;

	public function __construct($accountSid, $authToken, $version, $friendlyName)
	{
		$this->accountSid = $accountSid;
		$this->authToken = $authToken;
		$this->version = $version;
		$this->friendlyName = $friendlyName;
		$this->policies = array();
	}

	public function addPolicyDeconstructed($url, $method, $queryFilter = array(), $postFilter = array(), $allow = true) {
		$policy = new Policy($url, $method, $queryFilter, $postFilter, $allow);
		array_push($this->policies, $policy);
		return $policy;
	}

	public function allow($url, $method, $queryFilter = array(), $postFilter = array()) {
		$this->addPolicyDeconstructed($url, $method, $queryFilter, $postFilter, true);
	}

	public function deny($url, $method, $queryFilter = array(), $postFilter = array()) {
		$this->addPolicyDeconstructed($url, $method, $queryFilter, $postFilter, false);
	}

	/**
	 * @deprecated Please use {Services_Twilio_API_Capability.allow, Services_Twilio_API_Capability.disallow} instead
	 */
	public function addPolicy($policy) {
		array_push($this->policies, $policy);
	}

	/**
	 * @deprecated Please use {Services_Twilio_API_Capability.allow, Services_Twilio_API_Capability.disallow} instead
	 */
	public function generatePolicy($url, $method, $queryFilter = array(), $postFilter = array(), $allow = true)
	{
		return $this->addPolicyDeconstructed($url, $method, $queryFilter, $postFilter, $allow);
	}

	/**
	 * @deprecated Please use {Services_Twilio_API_Capability.allow, Services_Twilio_API_Capability.disallow} instead
	 */
	public function generateAndAddPolicy($url, $method, $queryFilter = array(), $postFilter = array(), $allow = true) {
		$this->addPolicyDeconstructed($url, $method, $queryFilter, $postFilter, $allow);
	}

	/**
	 * Generates a new token based on the credentials and permissions that
	 * previously has been granted to this token.
	 *
	 * @param $ttl the expiration time of the token (in seconds). Default
	 *        value is 3600 (1hr)
	 * @param $extraAttributes extra attributes to be tied to the jwt.
	 * @return the newly generated token that is valid for $ttl seconds
	 */
	public function generateToken($ttl = 3600, $extraAttributes = null)
	{
		$payload = array(
			'version' => $this->version,
			'friendly_name' => $this->friendlyName,
			'policies' => array(),
			'iss' => $this->accountSid,
			'exp' => time() + $ttl
		);
		if(isset($extraAttributes)) {
			foreach ($extraAttributes as $key => $value) {
				$payload[$key] = $value;
			}
		}

		$policyStrings = array();

		foreach ($this->policies as $policy) {
			$policyStrings[] = $policy->toArray();
		}

		$payload['policies'] = $policyStrings;
		return JWT::encode($payload, $this->authToken, 'HS256');
	}
}

/**
 * Twilio API Policy constructor
 *
 * @category Services
 * @package  Services_Twilio
 * @author Justin Witz <justin.witz@twilio.com>
 * @license  http://creativecommons.org/licenses/MIT/ MIT
 */
class Policy
{
	private $url;
	private $method;
	private $queryFilter;
	private $postFilter;
	private $allow;

	public function __construct($url, $method, $queryFilter = array(), $postFilter = array(), $allow = true)
	{
		$this->url = $url;
		$this->method = $method;
		$this->queryFilter = $queryFilter;
		$this->postFilter = $postFilter;
		$this->allow = $allow;
	}

	public function addQueryFilter($queryFilter)
	{
		array_push($this->queryFilter, $queryFilter);
	}

	public function addPostFilter($postFilter)
	{
		array_push($this->postFilter, $postFilter);
	}

	public function toArray() {
		$policy_array = array('url' => $this->url, 'method' => $this->method, 'allow' => $this->allow);
		if (!is_null($this->queryFilter)) {
			if (count($this->queryFilter) > 0 ) {
				$policy_array['query_filter'] = $this->queryFilter;
			} else {
				$policy_array['query_filter'] = new stdClass();
			}
		}

		if (!is_null($this->postFilter)) {
			if (count($this->postFilter) > 0 ) {
				$policy_array['post_filter'] = $this->postFilter;
			} else {
				$policy_array['post_filter'] = new stdClass();
			}
		}

		return $policy_array;
	}
}
