<?php

abstract class Services_Twilio_MonitorListResource extends Services_Twilio_NextGenListResource {

    public function __construct($client, $uri) {
        $name = $this->getResourceName(true);
        /*
         * By default trim the 's' from the end of the list name to get the
         * instance name (ex Accounts -> Account). This behavior can be
         * overridden by child classes if the rule doesn't work.
         */
        if (!isset($this->instance_name)) {
            $this->instance_name = "Services_Twilio_Rest_Monitor_" . rtrim($name, 's');
        }

        parent::__construct($client, $uri);
    }
}
