<?php

class Services_Twilio_Rest_OutgoingCallerIds
    extends Services_Twilio_ListResource
{
    public function create($phoneNumber, array $params = array())
    {
        return parent::_create(array(
            'PhoneNumber' => $phoneNumber,
        ) + $params);
    }
}
