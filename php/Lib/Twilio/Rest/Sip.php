<?php

/**
 * For Linux filename compatibility, this file needs to be named Sip.php, or
 * camelize() needs to be special cased in setupSubresources
 */
class Services_Twilio_Rest_Sip extends Services_Twilio_InstanceResource {
    protected function init($client, $uri) {
        $this->setupSubresources(
            'domains',
            'ip_access_control_lists',
            'credential_lists'
        );
    }

    public function getResourceName($camelized = false) {
        return "SIP";
    }
}
