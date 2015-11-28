<?php

abstract class Services_Twilio_PricingListResource extends Services_Twilio_NextGenListResource {
    public function __construct($client, $uri) {
        $name = $this->getResourceName(true);

        if (!isset($this->instance_name)) {
            $this->instance_name = 'Services_Twilio_Rest_Pricing_'. rtrim($name, 's');
        }

        parent::__construct($client, $uri);
    }

}
