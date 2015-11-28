<?php

class Services_Twilio_Rest_Participants
    extends Services_Twilio_ListResource
{
    /* Participants are identified by CallSid, not like PI123 */
    public function getObjectFromJson($params, $idParam = "sid") {
        return parent::getObjectFromJson($params, "call_sid");
    }
}
