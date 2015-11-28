<?php

class Services_Twilio_Rest_IpAddresses extends Services_Twilio_SIPListResource {

    public function __construct($client, $uri) {
        $this->instance_name = "Services_Twilio_Rest_IpAddress";
        parent::__construct($client, $uri);
    }

    /**
     * Creates a new IpAddress instance
     *
     * Example usage:
     *
     * .. code-block:: php
     *
     *      $client->account->sip->ip_access_control_lists->get('ALXXX')->ip_addresses->create(
     *          "FriendlyName", "127.0.0.1"
     *      );
     *
     * :param string $friendly_name: the friendly name for the new IpAddress object
     * :param string $ip_address: the ip address for the new IpAddress object
     * :param array $params: a single array of parameters which is serialized and
     *      sent directly to the Twilio API.
     */
    public function create($friendly_name, $ip_address, $params = array()) {
        return parent::_create(array(
            'FriendlyName' => $friendly_name,
            'IpAddress' => $ip_address,
        ) + $params);
    }
}

