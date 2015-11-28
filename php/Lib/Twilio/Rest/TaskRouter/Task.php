<?php

class Services_Twilio_Rest_TaskRouter_Task extends Services_Twilio_TaskRouterInstanceResource {

    protected function init($client, $uri) {
        $this->setupSubresources('reservations');
    }

}
