<?php

class Services_Twilio_Rest_CredentialLists extends Services_Twilio_SIPListResource {

    /**
     * Creates a new CredentialList instance
     *
     * Example usage:
     *
     * .. code-block:: php
     *
     *      $client->account->sip->credential_lists->create("MyFriendlyName");
     *
     * :param string $friendly_name: the friendly name of this credential list
     * :param array $params: a single array of parameters which is serialized and
     *      sent directly to the Twilio API.
     */
    public function create($friendly_name, $params = array()) {
        return parent::_create(array(
            'FriendlyName' => $friendly_name,
        ) + $params);
    }

}
