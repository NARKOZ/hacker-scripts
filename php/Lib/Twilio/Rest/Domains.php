<?php

class Services_Twilio_Rest_Domains extends Services_Twilio_SIPListResource {

    /**
     * Creates a new Domain instance
     *
     * Example usage:
     *
     *  .. code-block:: php
     *
     *      $client->account->sip->domains->create(
     *          "MyFriendlyName", "MyDomainName"
     *      );
     *
     * :param string $friendly_name: the friendly name of this domain
     * :param string $domain_name: the domain name for this domain
     * :param array $params: a single array of parameters which is serialized and
     *      sent directly to the Twilio API.
     */
    public function create($friendly_name, $domain_name, $params = array()) {
        return parent::_create(array(
            'FriendlyName' => $friendly_name,
            'DomainName' => $domain_name,
        ) + $params);
    }
}

