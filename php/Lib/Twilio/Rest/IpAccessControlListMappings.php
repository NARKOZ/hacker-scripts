<?php

class Services_Twilio_Rest_IpAccessControlListMappings extends Services_Twilio_SIPListResource {

    /**
     * Creates a new IpAccessControlListMapping instance
     *
     * Example usage:
     *
     * .. code-block:: php
     *
     *      $client->account->sip->domains->get('SDXXX')->ip_access_control_list_mappings->create("ALXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");
     *
     * :param string $ip_access_control_list_sid: the sid of the IpAccessControList
     *      you're adding to this domain.
     * :param array $params: a single array of parameters which is serialized and
     *      sent directly to the Twilio API.
     */
    public function create($ip_access_control_list_sid, $params = array()) {
        return parent::_create(array(
            'IpAccessControlListSid' => $ip_access_control_list_sid,
        ) + $params);
    }
}

