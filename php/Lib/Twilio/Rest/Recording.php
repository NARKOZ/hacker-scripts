<?php

class Services_Twilio_Rest_Recording
    extends Services_Twilio_InstanceResource
{
    protected function init($client, $uri) {
        $this->setupSubresources('transcriptions');
    }
}
