<?php

class Services_Twilio_Rest_SmsMessages
    extends Services_Twilio_ListResource
{
    public function __construct($client, $uri) {
        $uri = preg_replace("#SmsMessages#", "SMS/Messages", $uri);
        parent::__construct($client, $uri);
    }

    function create($from, $to, $body, array $params = array()) {
        return parent::_create(array(
            'From' => $from,
            'To' => $to,
            'Body' => $body
        ) + $params);
    }
}
