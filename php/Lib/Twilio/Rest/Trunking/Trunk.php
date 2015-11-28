<?php

class Services_Twilio_Rest_Trunking_Trunk extends Services_Twilio_TrunkingInstanceResource {

    protected function init($client, $uri) {
        $this->setupSubresources(
            'credential_lists',
            'ip_access_control_lists',
            'origination_urls',
            'phone_numbers'
        );
    }
}
