<?php

class Services_Twilio_Rest_Pricing_MessagingCountries
    extends Services_Twilio_PricingListResource {

    public function getResourceName($camelized = false) {
        if ($camelized) {
            return 'Countries';
        }
        return 'countries';

    }

    public function __construct($client, $uri) {
        $this->instance_name = "Services_Twilio_Rest_Pricing_MessagingCountry";
        parent::__construct($client, $uri);
    }

    public function get($isoCountry) {
        $instance = new $this->instance_name($this->client, $this->uri . "/$isoCountry");
        $instance->iso_country = $isoCountry;
        return $instance;
    }
}
