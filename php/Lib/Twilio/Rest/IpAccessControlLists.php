<?php

class Services_Twilio_Rest_IpAccessControlLists extends Services_Twilio_SIPListResource {

    /**
     * Creates a new IpAccessControlLists instance
     *
     * Example usage:
     *
     * .. code-block:: php
     *
     *      $client->account->sip->ip_access_control_lists->create("MyFriendlyName");
     *
     * :param string $friendly_name: the friendly name of this ip access control list
     * :param array $params: a single array of parameters which is serialized and
     *      sent directly to the Twilio API.
     * :return: the created list
     * :rtype: :class:`Services_Twilio_Rest_IpAccessControlList`
     *
     */
    public function create($friendly_name, $params = array()) {
        return parent::_create(array(
            'FriendlyName' => $friendly_name,
        ) + $params);
    }

}
