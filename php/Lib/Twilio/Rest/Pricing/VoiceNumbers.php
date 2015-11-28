<?php

class Services_Twilio_Rest_Pricing_VoiceNumbers
    extends Services_Twilio_PricingListResource {

    public function get($number) {
        $instance = new $this->instance_name($this->client, $this->uri . "/$number");
        $instance->number = $number;
        return $instance;
    }
}