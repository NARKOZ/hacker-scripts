<?php

class Services_Twilio_Rest_Trunking_IpAccessControlLists extends Services_Twilio_TrunkingListResource {

    /**
     * Create a new IpAccessControlLists instance
     *
     * Example usage:
     *
     * .. code-block:: php
     *
     *      $trunkingClient->trunks->get('TK123')->ip_access_control_lists->create(array(
     *          "IpAccessControlListSid" => "AL1234xxxx",
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
