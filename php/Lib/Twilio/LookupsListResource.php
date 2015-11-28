<?php

abstract class Services_Twilio_LookupsListResource extends Services_Twilio_NextGenListResource {

    public function __construct($client, $uri) {
        $name = $this->getResourceName(true);
        /*
         * By default trim the 's' from the end of the list name to get the
         * instance name (ex Accounts -> Account). This behavior can be
         * overridden by child classes if the rule doesn't work.
         */
        if (!isset($this->instance_name)) {
            $this->instance_name = "Services_Twilio_Rest_Lookups_" . rtrim($name, 's');
        }

        parent::__construct($client, $uri);
    }

    /**
     * Gets a resource from this list. Overridden to add
     * filter parameters.
     *
     * :param string $number: The phone number
     * :return: The resource
     * :rtype: :php:class:`InstanceResource <Services_Twilio_InstanceResource>`
     */
    public function get($number, $filters = array()) {
        $number = rawurlencode($number);
        $full_path = $this->uri . "/$number";
        if (!empty($filters)) {
            $full_path .= '?' . http_build_query($filters, '', '&');
        }

        $instance = new $this->instance_name(
            $this->client, $full_path
        );
        return $instance;
    }
}
