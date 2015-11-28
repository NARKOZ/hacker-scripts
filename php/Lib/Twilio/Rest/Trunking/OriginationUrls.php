<?php

class Services_Twilio_Rest_Trunking_OriginationUrls extends Services_Twilio_TrunkingListResource {

    /**
     * Create a new OriginationUrl instance
     *
     * Example usage:
     *
     * .. code-block:: php
     *
     *      $trunkingClient->trunks->get('TK123')->origination_urls->create(array(
     *          "FriendlyName" => "TestUrl",
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
