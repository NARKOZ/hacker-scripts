<?php

/**
 * @category Services
 * @package  Services_Twilio
 * @author   Neuman Vong <neuman@twilio.com>
 * @license  http://creativecommons.org/licenses/MIT/ MIT
 * @link     http://pear.php.net/package/Services_Twilio
 */

/**
 * Abstraction of an instance resource from the Twilio API.
 */
abstract class Services_Twilio_InstanceResource extends Services_Twilio_Resource {

    /**
     * Make a request to the API to update an instance resource
     *
     * :param mixed $params: An array of updates, or a property name
     * :param mixed $value:  A value with which to update the resource
     *
     * :rtype: null
     * :throws: a :php:class:`RestException <Services_Twilio_RestException>` if
     *      the update fails.
     */
    public function update($params, $value = null)
    {
        if (!is_array($params)) {
            $params = array($params => $value);
        }
        $decamelizedParams = $this->client->createData($this->uri, $params);
        $this->updateAttributes($decamelizedParams);
    }

    /**
     * Add all properties from an associative array (the JSON response body) as
     * properties on this instance resource, except the URI
     *
     * :param stdClass $params: An object containing all of the parameters of
     *      this instance
     * :return: Nothing, this is purely side effecting
     * :rtype: null
     */
    public function updateAttributes($params) {
        unset($params->uri);
        foreach ($params as $name => $value) {
            $this->$name = $value;
        }
    }

    /**
     * Get the value of a property on this resource.
     *
     * Instead of defining all of the properties of an object directly, we rely
     * on the API to tell us which properties an object has. This method will
     * query the API to retrieve a property for an object, if it is not already
     * set on the object.
     *
     * If the call is to a subresource, eg ``$client->account->messages``, no
     * request is made.
     *
     * To help with lazy HTTP requests, we don't actually retrieve an object
     * from the API unless you really need it. Hence, this function may make API
     * requests even if the property you're requesting isn't available on the
     * resource.
     *
     * :param string $key: The property name
     *
     * :return mixed: Could be anything.
     * :throws: a :php:class:`RestException <Services_Twilio_RestException>` if
     *      the update fails.
     */
    public function __get($key)
    {
        if ($subresource = $this->getSubresources($key)) {
            return $subresource;
        }
        if (!isset($this->$key)) {
            $params = $this->client->retrieveData($this->uri);
            $this->updateAttributes($params);
        }
        return $this->$key;
    }
}
