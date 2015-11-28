<?php

class Services_Twilio_Rest_UsageTriggers extends Services_Twilio_ListResource {

    public function __construct($client, $uri) {
        $uri = preg_replace("#UsageTriggers#", "Usage/Triggers", $uri);
        parent::__construct($client, $uri);
    }

    /**
     * Create a new UsageTrigger
     * @param string $category The category of usage to fire a trigger for. A full list of categories can be found in the `Usage Categories documentation <http://www.twilio.com/docs/api/rest/usage-records#usage-categories>`_.
     * @param string $value Fire the trigger when usage crosses this value.
     * @param string $url The URL to request when the trigger fires.
     * @param array $params Optional parameters for this trigger. A full list of parameters can be found in the `Usage Trigger documentation <http://www.twilio.com/docs/api/rest/usage-triggers#list-post-optional-parameters>`_.
     * @return Services_Twilio_Rest_UsageTrigger The created trigger
     */
    function create($category, $value, $url, array $params = array()) {
        return parent::_create(array(
            'UsageCategory' => $category,
            'TriggerValue' => $value,
            'CallbackUrl' => $url,
        ) + $params);
    }

}

