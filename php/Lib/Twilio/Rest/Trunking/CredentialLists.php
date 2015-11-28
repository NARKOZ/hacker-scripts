<?php

class Services_Twilio_Rest_Trunking_CredentialLists extends Services_Twilio_TrunkingListResource {

    /**
     * Create a new CredentialLists instance
     *
     * Example usage:
     *
     * .. code-block:: php
     *
     *      $trunkingClient->trunks->get('TK123')->credential_lists->create(array(
     *          "CredentialListSid" => "CL1234xxxxx",
     *          ....
     *      ));
     *
     * :param array $params: a single array of parameters which is serialized and
     *      sent directly to the Twilio API.
     *
     */
    public function create($params = array()) {
        return parent::_create($params);
    }
}
